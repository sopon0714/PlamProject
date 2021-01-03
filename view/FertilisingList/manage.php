<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
date_default_timezone_set('Asia/Bangkok');
?>

<?php
$action  = $_POST['action'] ?? "";
switch ($action) {
    case 'addFertilising':
        $fsid = $_POST['FSID'];
        $date = $_POST['date'];
        $ferID = $_POST['ferID'];
        $unit = $_POST['unit'];
        $Vol = $_POST['Vol'];
        $dataPic = explode('manu20', $_POST['pic']);
        $countfiles = sizeof($dataPic) - 1;
        $extension = ".png";
        $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
        $time = time();
        $DIMTIME = getDIMDate($date);
        $sql = "SELECT * FROM `log-farm` 
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
        WHERE `dim-farm`.`dbID` = $fsid
        GROUP BY `log-farm`.`ID` DESC
        LIMIT 1";
        $LOGFARM = selectData($sql);
        $sql = "INSERT INTO `log-fertilising` (`ID`, `isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `ferID`, `Unit`, `Vol`, `PICs`) 
        VALUES (NULL, '0', '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMTIME[1]['ID']}', '{$LOGFARM[1]['DIMownerID']}', '{$LOGFARM[1]['DIMfarmID']}', '{$LOGFARM[1]['DIMSubfID']}', '$ferID', '$unit', '$Vol', 'xxxx')";
        $logid = addinsertData($sql);
        $sql = "UPDATE `log-fertilising` SET `PICs` = 'picture/activities/fertilising/$logid' WHERE `log-fertilising`.`ID` = $logid";
        updateData($sql);
        $path  = "../../picture/activities/fertilising/$logid";
        if (!file_exists($path)) {
            mkdir("../../picture/activities/fertilising/$logid");
        }
        if ($countfiles > 0) {
            for ($i = 0; $i < $countfiles; $i++) {
                $Pic = getImgPest($dataPic[$i]);
                file_put_contents($path . "/" . $i . $extension, $Pic);
            }
        }
        $sql = "SELECT * FROM `log-fertilizercomposition`WHERE `FerID` = $ferID";
        $DETAILFER = selectData($sql);
        for ($i = 1; $i <= $DETAILFER[0]['numrow']; $i++) {
            $sql = "SELECT `log-nutrient`.* FROM `log-nutrient`
            INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-nutrient`.`DIMnutrID`
            WHERE `dim-nutrient`.`dbID` ={$DETAILFER[$i]['NID']}
            GROUP BY `log-nutrient`.`ID` DESC
            LIMIT 1";
            $DATANUTR = selectData($sql);
            if ($unit == 1 && $DATANUTR[1]['Unit'] == 2) {
                $ChangVol = $Vol * 10.0;
            } else if ($unit == 2 && $DATANUTR[1]['Unit'] == 1) {
                $ChangVol = $Vol / 100000.0;
            } else {
                $ChangVol = $Vol / 100;
            }
            $ChangVol = $ChangVol * $DETAILFER[$i]['Percent'];
            $sql = "INSERT INTO `log-fertilisingdetail` (`ID`, `fertilisingID`, `logNID`, `Vol`, `Unit`) VALUES (NULL, '$logid', '{$DATANUTR[1]['ID']}', '$ChangVol', '{$DATANUTR[1]['Unit']}')";
            addinsertData($sql);
        }
        header("location:./FertilisingDetail.php?FSID=$fsid&Active=3");
        break;
    case 'scanDir';
        $lid = $_POST['lid'];
        $path = $_POST['path'];
        $folder = $path . $lid;
        $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
        $arr = array();
        foreach ($objScan as $obj) {
            $type = strrchr($obj, ".");
            if ($type == '.png' || $type == '.jpg') {
                $arr[] = $obj;
            }
        }
        echo json_encode($arr);
        break;
    case 'deleteLog':
        $id = $_POST['logid'];
        $sql = "UPDATE `log-fertilising` SET `isDelete` = '1' WHERE `log-fertilising`.`ID` = $id";
        $o_did = updateData($sql);
        break;
    case 'loadData':
        $fsid = $_POST['fsid'];
        $year = $_POST['year'];
        $sql = "SELECT   `dim-nutrient`.`dbID` AS NID ,`dim-nutrient`.`Name`,`log-nutrient`.`Type`,IF(`log-nutrient`.`Unit`=1,'Kg','g') AS  Unit FROM `log-nutrient`
        INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID`= `log-nutrient`.`DIMnutrID`
        WHERE `log-nutrient`.`EndT` IS NULL
        ORDER BY `log-nutrient`.`Type` ,`dim-nutrient`.`dbID`";
        $DATA = selectData($sql);
        $text = "";
        for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {

            $color = "#458846";
            $diff = 0;
            $DATAVOL = getSumVolFertilising($fsid, $year, $DATA[$i]['NID']);
            if ($DATAVOL[0]['numrow'] != 0) {
                $Vol = $DATAVOL[1]['sumVol'];
            } else {
                $Vol = 0;
            }

            $Voluse = getVolUseFertilising($fsid, $DATA[$i]['NID'], $year);
            if ($Vol < $Voluse) {
                $color = "#CF2626";
                $diff = round($Voluse - $Vol, 2);
            }
            $text .= "  <tr style=\"color:$color ;\" >
                            <td class=\"text-left\">{$DATA[$i]['Name']}</td>
                            <td class=\"text-center\">{$DATA[$i]['Type']}</td>
                            <td class=\"text-right\">$Voluse</td>
                            <td class=\"text-right\">$Vol</td>
                            <td class=\"text-right\">$diff</td>
                            <td class=\"text-center\">{$DATA[$i]['Unit']}</td>
                        </tr>";
        }
        echo $text;
        break;
}
function getDIMOwner($dim_farm)
{
    $sql = "SELECT `log-farm`.`DIMownerID` FROM(
      SELECT MAX(`log-farm`.`ID`)  AS ID FROM `log-farm` 
      WHERE `log-farm`.`DIMfarmID`  = '$dim_farm')AS t1
      JOIN `log-farm` ON `log-farm`.`ID` = t1.ID";

    $data = selectData($sql)[1]['DIMownerID'];
    return $data;
}
