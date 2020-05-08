<?php
include_once("../../dbConnect.php");
$DSFID = $_GET['DSFID'];
$sql = "SELECT 'ฝนตก' AS title,dt.Date AS start,'#257e4a' AS color 
FROM `log-raining` AS lr 
JOIN `dim-time` AS dt ON dt.ID = lr.DIMdateID
WHERE lr.isDelete = 0 AND lr.DIMsubFID = $DSFID";
$data = selectAll($sql);
echo json_encode($data);
