<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
?>

<?php
$action  = $_POST['action'] ?? "";
switch ($action) {
     case "insert":
          $fmid = $_POST['FMID'];
          $fsid = $_POST['SubFarmID'];
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
          $DIMSUBFARM = getDIMSubFarm($fsid);
          $sql = "INSERT INTO `log-harvest` (`ID`, `isDelete`, `GuessFrom`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Weight`, `UnitPrice`, `TotalPrice`, `PICs`) 
          VALUES (NULL, '0', NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMTIME[1]['ID']}', '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', '{$DIMFARM[1]['ID']}', '$weight', '$UnitPrice', ' $total', 'xxxxx')";
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
          header("OilPalmAreaVolDetail.php?FMID=$fmid");
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
          print_r(json_encode($arr));
          break;
     case 'delete';
          $id = $_POST['id'];

          $sql = "UPDATE `log-harvest` SET `isDelete` = '1' WHERE `log-harvest`.`ID` = $id ";
          $o_did = updateData($sql);
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
     //echo $sql . "<br/>";
     $DIMFarmer = selectData($sql);
     if ($DIMFarmer[0]['numrow'] == 0) {
          $sql = "INSERT INTO `dim-user` (`ID`, `dbID`, `Type`, `Title`, `FullName`, `Alias`, `FormalID`) VALUES (NULL, '$FID', 'F', '{$DataFarmer[1]['Title']}', '$title {$DataFarmer[1]['FirstName']} {$DataFarmer[1]['LastName']}', '{$DataFarmer[1]['FirstName']}', '{$DataFarmer[1]['FormalID']}');";
          $IDDIMF = addinsertData($sql);
          $sql = "SELECT * FROM `dim-user` WHERE`ID`='$IDDIMF'";
          //echo $sql . "<br/>";
          $DIMFarmer = selectData($sql);
     }
     print_r($DIMFarmer);
     return  $DIMFarmer;
}
function getDIMFarm($FID)
{
     $sql = "SELECT * FROM `db-farm` WHERE FMID = '$FID' ";
     echo $sql . "<br/>";
     $DataFarm = selectData($sql);
     $sql = "SELECT * FROM `dim-farm` WHERE `IsFarm`='1' AND `dbID`='{$DataFarm[1]['FMID']}' AND `Name` ='{$DataFarm[1]['Name']}' AND `Alias`='{$DataFarm[1]['Alias']}'";
     //echo $sql . "<br/>";
     $DIMFarm = selectData($sql);
     if ($DIMFarm[0]['numrow'] == 0) {
          $sql = "INSERT INTO `dim-farm` (`ID`, `IsFarm`, `dbID`, `Name`, `Alias`) VALUES (NULL, '1', '{$DataFarm[1]['FMID']}', '{$DataFarm[1]['Name']}', '{$DataFarm[1]['Alias']}')";
          $IDDIMF = addinsertData($sql);
          $sql = "SELECT * FROM `dim-farm` WHERE`ID`='$IDDIMF'";
          //echo $sql . "<br/>";
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
