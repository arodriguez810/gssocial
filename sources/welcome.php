<?php
session_unset();
if (!empty($_SESSION['user_id'])) {
    $_SESSION['user_id'] = '';
    $query = mysqli_query($sqlConnect, "DELETE FROM " . T_APP_SESSIONS . " WHERE `session_id` = '" . Wo_Secure($_SESSION['user_id']) . "'");
}
session_destroy();
if (isset($_COOKIE['user_id'])) {
    $query = mysqli_query($sqlConnect, "DELETE FROM " . T_APP_SESSIONS . " WHERE `session_id` = '" . Wo_Secure($_COOKIE['user_id']) . "'");
    $_COOKIE['user_id'] = '';
    unset($_COOKIE['user_id']);
    setcookie('user_id', '', -1);
    setcookie('user_id', '', -1, '/');
}
if (isset($_COOKIE['switched_accounts']) && $_COOKIE['switched_accounts'] !== '') {
    $_COOKIE['switched_accounts'] = '';
    unset($_COOKIE['switched_accounts']);
    setcookie('switched_accounts', '', -1);
    setcookie('switched_accounts', '', time() - 3600, '/');
    setcookie('switched_accounts', '', -1, '/');
}
$_SESSION = array();
unset($_SESSION);
$wo['externalSession'] = $_GET;
if (!$wo['externalSession']["username"]) {
    echo '';
    die();
}
//startextra
{
    $qaemail = $wo['externalSession']["username"] . "@generaldeseguros.com.do";
    try {
        Wo_RegisterUser(array(
            "password" => "generalmasterkey",
            "username" => $wo['externalSession']["username"],
            //"email" => $wo['externalSession']["correo"],
            "email" => $wo['externalSession']["username"] . "@generaldeseguros.com.do",
            "first_name" => $wo['externalSession']["nombre"],
            "last_name" => $wo['externalSession']["apellido"],
            "country_id" => 60,
            "language" => "spanish",
            'email_code' => Wo_Secure(md5(rand(1111, 9999) . time()), 0),
            "post_privacy" => "everyone",
            "verified" => 1,
            "active" => 1,
            "gender" => $wo['externalSession']["gender"] === "masculino" ? "male" : "female",
        ));
    } catch (Exception $e) {

    }

}
$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords'] = $wo['config']['siteKeywords'];
$wo['page'] = 'welcome';
$wo['title'] = $wo['config']['siteTitle'];

Wo_Login($wo['externalSession']["username"], "generalmasterkey");
Wo_SetLoginWithSession($qaemail);
header("Location: " . $config['site_url']);
exit();
