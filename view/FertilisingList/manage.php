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
        $sql = "SELECT `fact-fertilising` .* FROM `fact-fertilising` 
                INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-fertilising`.`DIMsubFID`
                WHERE `fact-fertilising`.`TagetYear`=". ($DIMTIME[1]['Year1']+1) ."
                AND `dim-farm`.`dbID` = $fsid";
        $DATA = selectData($sql);
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

            //add fact-fertilising

            $sql = "SELECT `fact-fertilising` .* FROM `fact-fertilising` 
                INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-fertilising`.`DIMsubFID`
                WHERE `fact-fertilising`.`TagetYear`=". ($DIMTIME[1]['Year1']+1) ."
                AND `dim-farm`.`dbID` = $fsid";
            $DATA = selectData($sql);
            if($DETAILFER[$i]['NID']==1||$DETAILFER[$i]['NID']==2||$DETAILFER[$i]['NID']==3){
                
                if($DATA[0]['numrow']!=0){
                   
                    switch($DETAILFER[$i]['NID']){
                        case 1:
                            $vol = $DATA[1]['UseN']+$ChangVol;
                            $sql ="UPDATE `fact-fertilising` SET `UseN` = '$vol' WHERE `fact-fertilising`.`ID` = {$DATA[1]['ID']}";
                            break;
                        case 2:
                            $vol = $DATA[1]['UseP']+$ChangVol;
                            $sql ="UPDATE `fact-fertilising` SET  `UseP` = '$vol' WHERE `fact-fertilising`.`ID` = {$DATA[1]['ID']}";
                            break;
                        case 3:
                            $vol = $DATA[1]['UseK']+$ChangVol;
                            $sql ="UPDATE `fact-fertilising` SET `UseK` = '$vol' WHERE `fact-fertilising`.`ID` = {$DATA[1]['ID']}";
                            break;
                    }
                    updateData($sql);
                }else{
                    $tagetYear = $DIMTIME[1]['Year1'] + 1;
                    switch($DETAILFER[$i]['NID']){
                        case 1:
                            $N= $ChangVol;
                            $P= 0;
                            $K= 0;
                            break;
                        case 2:
                            $N= 0;
                            $P= $ChangVol;
                            $K= 0;
                            break;
                        case 3:
                            $N= 0;
                            $P= 0;
                            $K= $ChangVol;
                            break;
                    }
                    $wantN=  getVolUseFertilising($fsid, 1, $DIMTIME[1]['Year2']);
                    $wantP=  getVolUseFertilising($fsid, 2, $DIMTIME[1]['Year2']);
                    $wantK=  getVolUseFertilising($fsid, 3, $DIMTIME[1]['Year2']);
                
                    $sql ="INSERT INTO `fact-fertilising` (`ID`, `isDelete`, `TagetYear`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`,  `UseN`, `WantN`, `UnitN`, `UseP`, `WantP`, `UnitP`, `UseK`, `WantK`, `UnitK`) 
                    VALUES (NULL, '0', '$tagetYear', '{$LOG_LOGIN[1]['ID']}', '{$DIMTIME[1]['ID']}', '{$LOGFARM[1]['DIMownerID']}', '{$LOGFARM[1]['DIMfarmID']}', '{$LOGFARM[1]['DIMSubfID']}', '$N', '$wantN', '1', '$P', '$wantP', '1', '$K', '$wantK', '1')";
                    addinsertData($sql);
                }
            }
            
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

        //add fact-fertilising
        $sql ="SELECT `log-fertilisingdetail`.*,`log-nutrient`.`DIMnutrID` AS NID , `dim-time`.`Year1` ,`dim-farm`.`dbID` AS FSID FROM `log-fertilisingdetail`  
        INNER JOIN `log-fertilising` ON `log-fertilising`.`ID` = `log-fertilisingdetail`.fertilisingID
        INNER JOIN `log-nutrient` ON `log-nutrient`.`ID` = `log-fertilisingdetail`.`logNID`
        INNER JOIN `dim-time` ON `dim-time`.`ID` =   `log-fertilising`.`DIMdateID`
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
        WHERE `log-nutrient`.`EndT` IS NULL  AND `log-fertilisingdetail`.`fertilisingID`=$id AND `log-nutrient`.`DIMnutrID` IN (1,2,3)";
        $DATA =selectData($sql);
        if($DATA[0]['numrow']>0){
            $sql = "SELECT `fact-fertilising` .* FROM `fact-fertilising` 
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-fertilising`.`DIMsubFID`
            WHERE `fact-fertilising`.`TagetYear`=". ($DATA[1]['Year1']+1) ."
            AND `dim-farm`.`dbID` = {$DATA[1]['FSID']}";
            $DATA2= selectData($sql);
            $id =  $DATA2[1]['ID'];
            for($i=1;$i<=$DATA[0]['numrow'];$i++){
                switch($DATA[$i]['NID']){
                    case 1:
                        $vol = $DATA2[1]['UseN']-$DATA[$i]['Vol'];
                        $sql ="UPDATE `fact-fertilising` SET `UseN` = '$vol' WHERE `fact-fertilising`.`ID` = $id";
                        break;
                    case 2:
                        $vol = $DATA2[1]['UseP']-$DATA[$i]['Vol'];
                        $sql ="UPDATE `fact-fertilising` SET  `UseP` = '$vol' WHERE `fact-fertilising`.`ID` = $id";
                        break;
                    case 3:
                        $vol = $DATA2[1]['UseK']-$DATA[$i]['Vol'];
                        $sql ="UPDATE `fact-fertilising` SET `UseK` = '$vol' WHERE `fact-fertilising`.`ID` = $id";
                        break;
                }
                updateData($sql);
            }
        }
        
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
