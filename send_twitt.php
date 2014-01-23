<?php

require_once('db.php');
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');


if ( !empty($_GET['From']) && !empty($_GET['Message']) and ($_SERVER["REMOTE_ADDR"] == SERVER_IP) )
{
    $from = $_GET['From'];
    $msg  = $_GET['Message'];

    $database = new db(DB_USERNAME, DB_PASSWORD, "localhost", DB_NAME, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $user = $database->getRow("SELECT * FROM users WHERE phone_number =?", array($from ));



    if (!empty($user))
    {
        $connection = new TwitterOAuth  (CONSUMER_KEY, CONSUMER_SECRET, $user['oauth_token'], $user['oauth_token_secret']);
        $connection->post('statuses/update', array('status' => $msg));


        echo 'Done.';
        $database ->Disconnect();

    }
    else
    {
        echo 'Unknown sender';
    }
}