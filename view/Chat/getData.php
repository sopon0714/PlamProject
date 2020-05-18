<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
function getAllProvince()
{
    $sql = "SELECT * FROM `db-province` ORDER BY `Province`";
    $data = select($sql);
    return $data;
}
function getAllFarmer()
{
    $sql = "SELECT * FROM `db-farmer` ORDER BY`FirstName` ,`LastName`";
    $data = select($sql);
    return $data;
}
