<?php

session_start();
require_once('db.php');
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');


/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'oldtoken';
    header('Location: ./clearsessions.php');
}

if ( isset($_SESSION['oauth_token']) || isset($_SESSION['access_token']) )
{
    if (!isset($_POST['phone_number']))
    {
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        /* Request access tokens from twitter */
        $_SESSION['access_token'] = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        /* Remove no longer needed request tokens */
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);


    }
    else
    {
        $accessToken = $_SESSION['access_token'];
        $database = new db(DB_USERNAME, DB_PASSWORD, "localhost", DB_NAME, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $database ->insertRow("INSERT INTO `users`
                                        (oauth_token, oauth_token_secret, user_id, screen_name, phone_number)
                                        VALUES (?, ?, ?, ?, ?)",
            array($accessToken['oauth_token'], $accessToken['oauth_token_secret'], $accessToken['user_id'], $accessToken['screen_name'], $_POST['phone_number']));

    }

    include('register.html.inc');

}
else
{
    header('Location: ./clearsessions.php');
}