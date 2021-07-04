<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
?>

<?php
$action  = $_POST['action'] ?? "";
switch ($action) {
     case 'pagination' :
          $idformal = $_POST['idformal'];
          $fullname = $_POST['fullname'];
          $fpro = $_POST['fpro']; 
          $fdist = $_POST['fdist'];
          $start = $_POST['start'];
          $limit = $_POST['limit'];
          $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
          $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

          print_r(json_encode(getTableAllHarvest($idformal, $fullname, $fpro, $fdist,$start,$limit,$latitude,$longitude)));
          // print_r(getPest($idformal, $fullname, $fpro, $fdist, $fyear, $ftype,$start,$limit,$latitude,$longitude));

      break;
      case 'pagination2' :
          $fmid = $_POST['fmid'];
          $start = $_POST['start'];
          $limit = $_POST['limit'];
          print_r(json_encode(getLogHarvest($fmid,$start,$limit)));
          // print_r(getPest($idformal, $fullname, $fpro, $fdist, $fyear, $ftype,$start,$limit,$latitude,$longitude));

      break;
     case "insert":
          $fmid = $_POST['FMID'];
          $dimfsid = $_POST['SubFarmID'];
          $date = $_POST['date'];
          $weight = $_POST['weight'];
          $UnitPrice = $_POST['UnitPrice'];
          $total = $UnitPrice * $weight;
          $dataPic = explode('manu20', $_POST['pic']); 
          $countfiles = sizeof($dataPic) - 1;
          $extension = ".png";
          $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
          $sql = "SELECT * FROM `db-farm` WHERE `db-farm`.`FMID`=$fmid";
          $DBFARM = selectData($sql);
          $time = time();
          $DIMTIME = getDIMDate($date);
          $DIMFARMER = getDIMFarmer($DBFARM[1]['UFID']);
          $DIMFARM = getDIMFarm($fmid);
          $sql = "INSERT INTO `log-harvest` (`ID`, `isDelete`, `GuessFrom`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Weight`, `UnitPrice`, `TotalPrice`, `PICs`) 
          VALUES (NULL, '0', NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMTIME[1]['ID']}', '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', '$dimfsid', '$weight', '$UnitPrice', ' $total', 'xxxxx')";
          $IDLog = addinsertData($sql);
          $sql = "UPDATE `log-harvest` SET `PICs` = 'picture/activities/harvest/$IDLog' WHERE `log-harvest`.`ID` = $IDLog";
          updateData($sql);
          $path  = "../../picture/activities/harvest/$IDLog";
          if (!file_exists($path)) {
               mkdir("../../picture/activities/harvest/$IDLog");
          }
          if ($countfiles > 0) {
               for ($i = 0; $i < $countfiles; $i++) {
                    $Pic = getImgPest($dataPic[$i]);
                    file_put_contents($path . "/" . $i . $extension, $Pic);
               }
          }
          //////////// fact-farming ////////////////////
          $tagetYear = $DIMTIME[1]['Year1'] + 1;
          $sql = "SELECT * FROM `dim-farm` WHERE`ID` = $dimfsid";
          $DIMSUBFARM = selectData($sql);
          $sql = "SELECT `fact-farming`.* ,`dim-farm`.`dbID` AS FSID FROM `fact-farming` 
          INNER JOIN `dim-farm`ON `dim-farm`.`ID` = `fact-farming`.`DIMsubFID`
          WHERE TagetYear = $tagetYear AND `dim-farm`.`dbID` ={$DIMSUBFARM[1]['dbID']}";
          $FACTFARMING = selectData($sql);
          if ($FACTFARMING[0]['numrow'] == 0) {
               $sql = "SELECT * FROM `log-farm` 
               INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
               WHERE   `dim-farm`.`dbID`={$DIMSUBFARM[1]['dbID']}
               ORDER BY `log-farm`.`ID` DESC 
               LIMIT 1";
               $DATA = selectData($sql);
               $sql = "INSERT INTO `fact-farming` (`ID`, `isDelete`, `TagetYear`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`,  `NumTree`, `HarvestVol`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
               VALUES (NULL, '0', '$tagetYear', '{$LOG_LOGIN[1]['ID']}', '{$DIMTIME[1]['ID']}', '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', '$dimfsid', '{$DATA[1]['NumTree']}', '$weight', '{$DATA[1]['AreaRai']}', '{$DATA[1]['AreaNgan']}', '{$DATA[1]['AreaWa']}', '{$DATA[1]['AreaTotal']}')";
               addinsertData($sql);
          } else {
               $Vol = $FACTFARMING[1]['HarvestVol'] + $weight;
               $sql = "UPDATE `fact-farming` SET `HarvestVol` = '$Vol' WHERE `fact-farming`.`ID` = {$FACTFARMING[1]['ID']}";
               updateData($sql);
          }

          //////////// fact-farming ////////////////////



          //////////// fact-fertilising ////////////////////
          $sql ="SELECT `fact-fertilising`.* FROM `fact-fertilising` 
          INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-fertilising`.`DIMsubFID`
          WHERE `dim-farm`.`dbID`={$DIMSUBFARM[1]['dbID']}  AND `fact-fertilising`.`TagetYear`=".($DIMTIME[1]['Year1']+2);
          $FACTFERTILISING = selectData($sql);
          if($FACTFERTILISING[0]['numrow']==1){
               $wantN=  getVolUseFertilising($DIMSUBFARM[1]['dbID'], 1, $DIMTIME[1]['Year2']+1);
               $wantP=  getVolUseFertilising($DIMSUBFARM[1]['dbID'], 2, $DIMTIME[1]['Year2']+1);
               $wantK=  getVolUseFertilising($DIMSUBFARM[1]['dbID'], 3, $DIMTIME[1]['Year2']+1);
               $sql ="UPDATE `fact-fertilising` SET `WantN` = '$wantN', `WantP` = '$wantP', `WantK` = '$wantK' WHERE `fact-fertilising`.`ID` = {$FACTFERTILISING[1]['ID']}";
               updateData($sql);
          }
          //////////// fact-fertilising ////////////////////

          header("location:./OilPalmAreaVolDetail.php?FMID=$fmid");
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
     case 'delete';
          $id = $_POST['id'];

          $sql = "UPDATE `log-harvest` SET `isDelete` = '1' WHERE `log-harvest`.`ID` = $id ";
          $o_did = updateData($sql);

          //////////// fact-farming ////////////////////
          $sql = "SELECT * FROM `log-harvest` WHERE `ID` = $id";
          $DATA = selectData($sql);
          $sql = "SELECT * FROM `dim-time`  WHERE ID = {$DATA[1]['DIMdateID']}";
          $DIMTIME = selectData($sql);
          $tagetYear = $DIMTIME[1]['Year1'] + 1;
          $sql = "SELECT * FROM `dim-farm` WHERE`ID` = {$DATA[1]['DIMsubFID']}";
          $DIMSUBFARM = selectData($sql);
          $sql = "SELECT `fact-farming`.* ,`dim-farm`.`dbID` AS FSID FROM `fact-farming` 
          INNER JOIN `dim-farm`ON `dim-farm`.`ID` = `fact-farming`.`DIMsubFID`
          WHERE TagetYear = $tagetYear AND `dim-farm`.`dbID` ={$DIMSUBFARM[1]['dbID']}";
          $FACTFARMING = selectData($sql);
          $Vol = $FACTFARMING[1]['HarvestVol'] - $DATA[1]['Weight'];
          $sql = "UPDATE `fact-farming` SET `HarvestVol` = '$Vol' WHERE `fact-farming`.`ID` = {$FACTFARMING[1]['ID']}";
          updateData($sql);

          //////////// fact-farming ////////////////////

          //////////// fact-fertilising ////////////////////
          $sql ="SELECT `fact-fertilising`.* FROM `fact-fertilising` 
          INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-fertilising`.`DIMsubFID`
          WHERE `dim-farm`.`dbID`={$DIMSUBFARM[1]['dbID']}  AND `fact-fertilising`.`TagetYear`=".($DIMTIME[1]['Year1']+2);
          $FACTFERTILISING = selectData($sql);
          if($FACTFERTILISING[0]['numrow']==1){
               $wantN=  getVolUseFertilising($DIMSUBFARM[1]['dbID'], 1, $DIMTIME[1]['Year2']+1);
               $wantP=  getVolUseFertilising($DIMSUBFARM[1]['dbID'], 2, $DIMTIME[1]['Year2']+1);
               $wantK=  getVolUseFertilising($DIMSUBFARM[1]['dbID'], 3, $DIMTIME[1]['Year2']+1);
               $sql ="UPDATE `fact-fertilising` SET `WantN` = '$wantN', `WantP` = '$wantP', `WantK` = '$wantK' WHERE `fact-fertilising`.`ID` = {$FACTFERTILISING[1]['ID']}";
               updateData($sql);
          }
          //////////// fact-fertilising ////////////////////
          break;
     case 'setSelectSubfarm';
          $FIMD = $_POST['FIMD'];
          $date = $_POST['date'];
          $INFODATA = getsubFarmByModify2($FIMD, strtotime($date));
          $html = " <option value=\"0\" selected>เลือกแปลง</option>";
          for ($i = 1; $i < count($INFODATA); $i++) {
               $html .= " <option value=\"{$INFODATA[$i]['DIMFSID']}\" selected>{$INFODATA[$i]['Name']}</option>";
          }
          echo $html;
          break;
}
function getDIMFarmer($FID)
{
     $sql = "SELECT * FROM `db-farmer` WHERE UFID = '$FID' ";
     $DataFarmer = selectData($sql);
     $title = "";
     if ($DataFarmer[1]['Title'] == 1) {
          $title = "นาย";
     } else if ($DataFarmer[1]['Title'] == 2) {
          $title = "นาง";
     } else {
          $title = "นางสาว";
     }
     $sql = "SELECT * FROM `dim-user` WHERE`dbID`='$FID' AND `Type`='F' AND `Title`='{$DataFarmer[1]['Title']}'  AND `FullName` ='$title {$DataFarmer[1]['FirstName']} {$DataFarmer[1]['LastName']}' AND `Alias`='{$DataFarmer[1]['FirstName']}' AND `FormalID`='{$DataFarmer[1]['FormalID']}'";
     $DIMFarmer = selectData($sql);
     if ($DIMFarmer[0]['numrow'] == 0) {
          $sql = "INSERT INTO `dim-user` (`ID`, `dbID`, `Type`, `Title`, `FullName`, `Alias`, `FormalID`) VALUES (NULL, '$FID', 'F', '{$DataFarmer[1]['Title']}', '$title {$DataFarmer[1]['FirstName']} {$DataFarmer[1]['LastName']}', '{$DataFarmer[1]['FirstName']}', '{$DataFarmer[1]['FormalID']}');";
          $IDDIMF = addinsertData($sql);
          $sql = "SELECT * FROM `dim-user` WHERE`ID`='$IDDIMF'";
          $DIMFarmer = selectData($sql);
     }
     return  $DIMFarmer;
}
function getDIMFarm($FID)
{
     $sql = "SELECT * FROM `db-farm` WHERE FMID = '$FID' ";

     $DataFarm = selectData($sql);
     $sql = "SELECT * FROM `dim-farm` WHERE `IsFarm`='1' AND `dbID`='{$DataFarm[1]['FMID']}' AND `Name` ='{$DataFarm[1]['Name']}' AND `Alias`='{$DataFarm[1]['Alias']}'";
     //echo $sql . "<br/>";
     $DIMFarm = selectData($sql);
     if ($DIMFarm[0]['numrow'] == 0) {
          $sql = "INSERT INTO `dim-farm` (`ID`, `IsFarm`, `dbID`, `Name`, `Alias`) VALUES (NULL, '1', '{$DataFarm[1]['FMID']}', '{$DataFarm[1]['Name']}', '{$DataFarm[1]['Alias']}')";
          $IDDIMF = addinsertData($sql);
          $sql = "SELECT * FROM `dim-farm` WHERE`ID`='$IDDIMF'";
          $DIMFarm = selectData($sql);
     }
     return  $DIMFarm;
}
function getDIMSubFarm($FSID)
{
     $sql = "SELECT * FROM `db-subfarm` WHERE FSID = $FSID ";
     $DataSubFarm = selectData($sql);
     $sql = "SELECT * FROM `dim-farm` WHERE `IsFarm`='0' AND `dbID`='$FSID' AND `Name` ='{$DataSubFarm[1]['Name']}' AND `Alias`='{$DataSubFarm[1]['Alias']}'";
     $DIMSubFarm = selectData($sql);
     if ($DIMSubFarm[0]['numrow'] == 0) {
          $sql = "INSERT INTO `dim-farm` (`ID`, `IsFarm`, `dbID`, `Name`, `Alias`) VALUES (NULL, '0', '$FSID', '{$DataSubFarm[1]['Name']}', '{$DataSubFarm[1]['Alias']}')";
          $IDDIMF = addinsertData($sql);
          $sql = "SELECT * FROM `dim-farm` WHERE`ID`='$IDDIMF'";
          //echo $sql . "<br/>";
          $DIMSubFarm = selectData($sql);
     }
     return $DIMSubFarm;
}
