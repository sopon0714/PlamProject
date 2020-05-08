<?php
include_once("../../dbConnect.php");

$year = $_POST['year'];
$ID_Province = $_POST['ID_Province'];
$ID_Distrinct = $_POST['ID_Distrinct'];
$name = $_POST['name'];
$passport = $_POST['passport'];

$sqlFarm = "";
$sql_Join_Pro_Dis = "";
$sqlProvince = "";
$sqlDistrinct = "";

// echo "\n\n " . $year . " " . $ID_Province . " " . $ID_Distrinct . " " . $name . " " . $passport . "\n\n";

if ($year == date("Y") + 543)
   $sqlFarm = "SELECT df.dbID AS 'FID',dfs.dbID AS 'SFID',dfs.ID AS 'DSFID',du.dbID AS 'UID',du.Alias AS 'Name',df.Name AS 'FName',dfs.Name AS 'SFName',lf.AreaTotal AS 'SumArea',lf.NumTree AS 'SumNumTree',ds.AD3ID,ds.Latitude,ds.Longitude
                  FROM `log-farm` AS lf 
                     JOIN `dim-farm` as df ON df.ID = lf.DIMfarmID
                     JOIN `dim-farm` as dfs ON dfs.ID = lf.DIMSubfID 
                     JOIN `db-subfarm` AS ds ON ds.FSID = dfs.dbID 
                     JOIN `dim-user` as du ON du.ID = lf.DIMownerID
                  WHERE lf.EndID IS NULL";
else
   $sqlFarm = "SELECT df.dbID AS 'FID',dfs.dbID AS 'SFID',dfs.ID AS 'DSFID',du.dbID AS 'UID',du.Alias AS 'Name',df.Name AS 'FName',dfs.Name AS 'SFName',ff.AreaTotal AS 'SumArea',ff.NumTree AS 'SumNumTree',ds.AD3ID,ds.Latitude,ds.Longitude
                  FROM `fact-farming` AS ff
                     JOIN `dim-farm` as df ON df.ID = ff.DIMfarmID
                     JOIN `dim-farm` as dfs ON dfs.ID = ff.DIMSubfID 
                     JOIN `db-subfarm` AS ds ON ds.FSID = dfs.dbID 
                     JOIN `dim-user` as du ON du.ID = ff.DIMownerID
                  WHERE ff.isDelete = 0 AND ff.TagetYear = $year-543";

if ($name != null)
   $sqlFarm = $sqlFarm . " AND du.Alias = '$name'";
if ($passport != null)
   $sqlFarm = $sqlFarm . " AND du.FormalID = '$passport'";


if ($ID_Province != "null") {
   $sql_Join_Pro_Dis = " JOIN `db-subdistrinct` AS dsd ON dsd.AD3ID = S.AD3ID
                         JOIN `db-distrinct` AS dd ON dd.AD2ID = dsd.AD2ID ";
   $sqlProvince = "AND dd.AD1ID = '$ID_Province' ";
}
if ($ID_Distrinct != null)
   $sqlDistrincts = "AND dd.AD2ID = '$ID_Distrinct' ";


$factRain = "SELECT fw.ID,dt.Date,S.FID,S.SFID AS 'SFID',S.DSFID,S.UID,S.Name,S.FName,S.SFName AS 'subFName',S.SumArea,S.SumNumTree,S.Latitude,S.Longitude,fw.WaterPeriod,fw.RainPeriod,fw.TotalPeriod
                     FROM `fact-watering` AS fw
                     INNER JOIN ($sqlFarm) AS S ON S.DSFID = fw.DIMsubFID
                     JOIN `dim-time` AS dt ON dt.ID = fw.DIMdateID " . $sql_Join_Pro_Dis .
   "WHERE dt.Year2 = $year " . $sqlProvince . $sqlDistrinct . " ORDER by S.SFID,dt.Date";

$data = selectAll($factRain);
for ($i = 0; $i < count($data); $i++) {
   $a = explode('-', $data[$i]['Date']);
   $data[$i]['Date'] = $a[2] . "-" . $a[1] . "-" . ($a[0] + 543);
}
echo json_encode($data);
// echo $sqlPestAlarm;
