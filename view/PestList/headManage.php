<?php
    session_start();
    ob_start();

    require "../../dbConnect.php";
    $request = $_POST['request'];
    require_once("../../set-log-login.php");
    include_once("./../../query/query.php");

    $loglogin = $_SESSION[md5('LOG_LOGIN')];
    $loglogin_id = $loglogin[1]['ID'];
    $startID = $loglogin[1]['StartID'];

    // echo 'dptid = '.$dptid.'<br>';

?>