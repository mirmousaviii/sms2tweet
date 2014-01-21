<?php

session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'oldtoken';
    header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
    /* The user has been verified and the access tokens can be saved for future use */
    //$_SESSION['status'] = 'verified';
    //header('Location: ./index.php');
    //print_r($_SESSION);
    $accessToken = $_SESSION['access_token'];
    $database = new db(DB_USERNAME, DB_PASSWORD, "localhost", DB_NAME, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $database ->insertRow("INSERT INTO `users`
                                        (oauth_token, oauth_token_secret, user_id, screen_name, phone_number)
                                        VALUES (?, ?, ?, ?, ?)",
                            array($accessToken['oauth_token'], $accessToken['oauth_token_secret'], $accessToken['user_id'], $accessToken['screen_name'], '09120000000'));


} else {
    /* Save HTTP status for error dialog on connnect page.*/
    header('Location: ./clearsessions.php');
}
