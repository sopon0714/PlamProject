<?php
include_once("../../dbConnect.php");
$id = $_GET['id'];
$sql = "SELECT MAX(dp.ID) AS ID,dp.dbpestLID,dpl.Name FROM `dim-pest` AS dp 
JOIN `db-pestlist` AS dpl ON dp.dbpestLID = dpl.PID
WHERE dp.dbpestTID = $id
GROUP BY dp.dbpestLID
";
$data = selectAll($sql);
echo json_encode($data);
