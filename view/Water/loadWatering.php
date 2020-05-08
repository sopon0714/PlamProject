<?php
include_once("../../dbConnect.php");
$date = $_POST['date'];
$p_date = explode('-', $date);
$sql_DimDate = "SELECT ID FROM `dim-time` AS dt WHERE dt.dd = $p_date[0] AND dt.Month = $p_date[1] AND dt.Year1 = $p_date[2]";
$data1 = selectData($sql_DimDate);
$DIMdate = $data1[1]['ID'];

$DSFID = $_POST['DSFID'];
$sql = "SELECT lw.ID,dt.Date,lw.StartTime,lw.StopTime,lw.Period FROM `log-watering` AS lw 
JOIN `dim-time` AS dt ON dt.ID = lw.DIMdateID
WHERE lw.isDelete = 0 AND lw.DIMSubFID = $DSFID AND lw.DIMdateID = $DIMdate";
$data = selectAll($sql);
for ($i = 0; $i < count($data); $i++) {
    $a = explode('-', $data[$i]['Date']);
    $data[$i]['Date'] = $a[2] . "-" . $a[1] . "-" . ($a[0] + 543);
}
echo json_encode($data);
