<?php
require 'vendor/autoload.php';
require 'libs/DummyProvider.php';

$provider = new DummyProvider();
$chat = new AngularTalk_Room('chat', $provider);
$chat->set_mode(AngularTalk_Room::MODE_CHAT);
$chat->ajaxEndpoint = '?chatEndpoint';
$chat->sender = $provider->authorInfo(1, $chat);
$chat->sender->isModerator = true;
$chat->soundOnNew = array(
    'audio/mpeg' => 'static/notification.mp3',
    'audio/ogg'  => 'static/notification.ogg'
);
$chat->debug = true;
?>
<!DOCTYPE html>
<html lang="en" ng-app="angularTalk">
<head>
    <meta charset="utf-8">
    <title>angular-talk</title>

    <link href="static/example.css" rel="stylesheet"/>
    <link href="dist/css/angular-talk.min.css" rel="stylesheet"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"/>

</head>

<body>

<div class="container">

    <div class="page-header">
        <h1>angular-talk</h1>
    </div>
    <div id="chat">
        <?php
        echo $chat->render();
        ?>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
<script src="dist/js/angular-talk.tpls.js"></script>
</body>
</html>
