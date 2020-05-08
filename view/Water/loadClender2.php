<?php
include_once("../../dbConnect.php");
$sql = "SELECT dt.Date FROM `fact-watering` AS fw 
JOIN `dim-time` AS dt ON dt.ID = fw.DIMdateID
WHERE fw.RainPeriod = 0";
$data = selectAll($sql);
echo json_encode($data);
