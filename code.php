<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once 'libs/random_compat-2.0.2/lib/random.php';

$conn = null;
$id = 0;
if ($_GET['i'] != null) {
    $id = $_GET['i'];
    $id = base64_decode($id);
    $id = preg_replace('/[a-zA-Z]+$/', '', $id);
}

$add = null;
if ($_GET['add'] != null) {
    $json = file_get_contents('php://input');
    if ($json == null) {
        exit(405);
    }
    $add = json_decode($json);
    if ($add == null) {
        exit(405);
    }
    $add = $add->code;
    if ($add == null) {
        exit(405);
    }
}

try
{
    $serverName = "tcp:live-edit5.database.windows.net,1433";
    $connectionOptions = array("Database"=>"live-edit-5",
        "Uid"=>"dmssargent", "PWD"=>"#MustangRobotics");
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if($conn == false)
        die(sqlsrv_errors());
}
catch(Exception $e)
{
    die("Error!");
}

if ($add == null) {
    $query = sqlsrv_query($conn, 'SELECT code FROM snapedit2 WHERE ID=?', array($id));
    if ($query) {
        $sqlsrv_fetch_array = sqlsrv_fetch_array($query);
        if (sizeof($sqlsrv_fetch_array) > 0) {
            echo $sqlsrv_fetch_array[0];
        } else {
            echo "No result";
            exit(405);
        }
    }
} else {
    $query = sqlsrv_query($conn, "INSERT INTO snapedit2 (Person, Code) VALUES (?, ?)", array($_GET['add'], $add));
    if (!$query) {
        echo "Failure!";
        exit(501);
    }
    $query2 = sqlsrv_query($conn, 'SELECT ID FROM snapedit2 WHERE Person = ? AND Code= ?', array($_GET['add'], $add));
    $sqlsrv_fetch_array = sqlsrv_fetch_array($query2);
    if (sizeof($sqlsrv_fetch_array) > 0) {
        echo base64_encode($sqlsrv_fetch_array[0] . random_str(5));
    } else {
        echo "No result";
        exit(405);
    }
}

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}