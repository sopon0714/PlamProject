<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
if (isset($_POST['add'])) {
    $namefarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['namefarm']));
    $aliasfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['aliasfarm']));
    $addfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['addfarm']));
    $subdistrinct = $_POST['subdistrinct'];
    $farmer = $_POST['farmer'];
    $sql = "SELECT * FROM `db-subdistrinct` WHERE AD3ID='$subdistrinct'";
    $dataAddress = selectData($sql);
    $sql = "INSERT INTO `db-farm` ( `Name`, `Alias`, `Icon`, `Address`, `AD3ID`, `UFID`, `IsCoordinate`, `Latitude`, `Longitude`) VALUES ( '$namefarm', '$aliasfarm', 'default.png', '$addfarm', '$subdistrinct', '$farmer', '0', ' {$dataAddress[1]['Latitude']}', ' {$dataAddress[1]['Longitude']}')";
    $IDFarm = addinsertData($sql);
    $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
    $Date = getDIMDate();
    $DIMFarmer = getDIMFarmer($farmer);
    $sql = "INSERT INTO `dim-farm` (`ID`, `IsFarm`, `dbID`, `Name`, `Alias`) VALUES (NULL, '1', '$IDFarm', '$namefarm', '$aliasfarm');";
    echo $sql;
    $IDDIMFarm = addinsertData($sql);
    $DIMAddr = getDIMAddr($subdistrinct, $addfarm);
    $time = time();
    $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$Date[1]['ID']}', NULL, NULL, '{$DIMFarmer[1]['ID']}', '$IDDIMFarm', NULL, '{$DIMAddr[1]['ID']}', '0', ' {$dataAddress[1]['Latitude']}', ' {$dataAddress[1]['Longitude']}', '0', '0', '0', '0', '0', '0')";
    echo $sql;
    addinsertData($sql);
    header("location:./OilPalmAreaList.php");
}
if (isset($_POST['editFarm'])) {

    $namefarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['namefarm']));
    $aliasfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['aliasfarm']));
    $addfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['addfarm']));
    $subdistrinct = $_POST['subdistrinct'];
    $farmer = $_POST['farmer'];
    $IDFarm = $_POST['IDFarm'];
    $DIMFarm = getDIMFarm($IDFarm);
    $Date = getDIMDate();
    $time = time();
    $sql = "SELECT * FROM `db-subdistrinct` WHERE AD3ID='$subdistrinct'";
    $dataAddress = selectData($sql);
    $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL AND `DIMSubfID` IS NULL";
    $LOGFarm = selectData($sql);
    $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$Date[1]['ID']}' WHERE `log-farm`.`ID` = '{$LOGFarm[1]['ID']}'";
    updateData($sql);
    $sql = "UPDATE `db-farm` SET `Name` = '$namefarm', `Alias` = '$aliasfarm', `Address` = '$addfarm', `AD3ID` = '$subdistrinct', `UFID` = '$farmer' WHERE `db-farm`.`FMID` = '$IDFarm'";
    updateData($sql);
    $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
    $DIMFarmer = getDIMFarmer($farmer);
    $DIMFarm = getDIMFarm($IDFarm);
    $DIMAddr = getDIMAddr($subdistrinct, $addfarm);
    print_r($DIMAddr);
    $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$Date[1]['ID']}', NULL, NULL, '{$DIMFarmer[1]['ID']}', '{$DIMFarm[1]['ID']}', NULL, '{$DIMAddr[1]['ID']}', '0', ' {$dataAddress[1]['Latitude']}', ' {$dataAddress[1]['Longitude']}', '{$LOGFarm[1]['NumSubFarm']}', '{$LOGFarm[1]['NumTree']}', '{$LOGFarm[1]['AreaRai']}', '{$LOGFarm[1]['AreaNgan']}', '{$LOGFarm[1]['AreaWa']}', '{$LOGFarm[1]['AreaTotal']}')";
    //echo $sql;
    $IDLog = addinsertData($sql);
    $_SESSION[('id')] = $namefarm;
    $_SESSION[('fmid')] = $IDFarm;
    $_SESSION[('fname')] = $DIMFarmer[1]['Alias'];
    $_SESSION[('logid')] = $IDLog;

    header("location:./OilPalmAreaListDetail.php?fmid=$IDFarm");
}
if (isset($_POST['delete'])) {

    $FID = $_POST['fid'];
    $time = time();
    $DIMDATE = getDIMDate();
    // update log-farm
    $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'  WHERE `log-farm`.`ID` IN 
    (SELECT `log-farm`.`ID` FROM `log-farm` INNER JOIN `dim-farm` ON `log-farm`.`DIMfarmID`=`dim-farm`.`ID` 
    WHERE`dim-farm`.`dbID`=$FID AND `dim-farm`.`IsFarm`=1 AND `log-farm`.`EndT`IS NULL) ";
    updateData($sql);
    //update log-icon subfarm
    $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'
      WHERE `log-icon`.`Type`= 3 AND `log-icon`.`EndT` IS NULL AND `log-icon`.`DIMiconID` IN 
      (SELECT `dim-farm`.`ID` FROM `dim-farm` INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID`=`dim-farm`.`dbID`
       WHERE `dim-farm`.`IsFarm`=0 AND `db-subfarm`.`FMID` = $FID) ";
    updateData($sql);
    // update log-iconfarm
    $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'
      WHERE `log-icon`.`Type`= 4 AND `log-icon`.`EndT` IS NULL AND `log-icon`.`DIMiconID` IN 
      (SELECT `dim-farm`.`ID` FROM `dim-farm` INNER JOIN `db-farm`ON `db-farm`.`FMID` = `dim-farm`.`dbID`
       WHERE `dim-farm`.`IsFarm`=1 AND `db-farm`.`FMID` =$FID) ";
    updateData($sql);

    $sql = "DELETE FROM `db-coorfarm` WHERE `db-coorfarm`.`FCID`IN 
    (SELECT `db-coorfarm`.`FCID` FROM `db-coorfarm` INNER JOIN `db-subfarm`
     ON  `db-coorfarm`.`FSID` = `db-subfarm`.`FSID` WHERE `db-subfarm`.`FMID` = '$FID')";
    delete($sql);
    $sql = "DELETE FROM `db-subfarm` WHERE `db-subfarm`.`FMID`='$FID'";
    delete($sql);

    echo $sql . "<br/>";
    $sql = "DELETE FROM`db-farm` WHERE `db-farm`.`FMID`='$FID'";
    delete($sql);
    echo $sql . "<br/>";
}
if (isset($_POST['deleteSub'])) {
    $FID = $_POST['fsid'];
    $time = time();
    $DIMDATE = getDIMDate();
    $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'  
    WHERE `log-farm`.`ID` IN (SELECT `log-farm`.`ID` FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID` 
        WHERE `dim-farm`.`dbID`=' $FID' AND `log-farm`.`EndT` IS NULL)  ";
    updateData($sql);
    echo $sql . "<br/>";
    $sql = "DELETE FROM `db-coorfarm` WHERE `db-coorfarm`.`FCID`IN 
    (SELECT `db-coorfarm`.`FCID` FROM `db-coorfarm` INNER JOIN `db-subfarm`
     ON  `db-coorfarm`.`FSID` = `db-subfarm`.`FSID` WHERE `db-subfarm`.`FSID` = '$FID')";
    delete($sql);
    echo $sql . "<br/>";
    $sql = "DELETE FROM `db-subfarm` WHERE `db-subfarm`.`FSID`='$FID'";
    delete($sql);
    echo $sql . "<br/>";
}
if (isset($_POST['search'])) {
    $province = $_POST['province'] ?? "";
    $amp = $_POST['amp'] ?? "";
    $name = $_POST['name'] ?? "";
    $FormalID = $_POST['FormalID'] ?? "";
    $text = "";
    if ($province != "") {
        $text .= " AND `dim-address`.`dbprovID`='$province' ";
    }
    if ($amp != "") {
        $text .= " AND `dim-address`.`dbDistID`='$amp' ";
    }
    if ($name != "") {
        $text .= " AND `dim-user`.`Alias` LIKE '%$name%' ";
    }
    if ($FormalID != "") {
        $text .= "AND `dim-user`.`FormalID` LIKE  '%$FormalID%' ";
    }


    $sql = "SELECT `log-farm`.`ID`,`dim-farm`.`dbID` AS FMID ,`dim-address`.`Province`,`dim-address`.`Distrinct`,`dim-user`.`Alias`, `dim-farm`.`Name`,`log-farm`.`NumSubFarm`,
    `log-farm`.`AreaRai`,`log-farm`.`NumTree`,`log-farm`.`Latitude`,`log-farm`.`Longitude` FROM `log-farm` 
    INNER JOIN `dim-user`ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
    INNER JOIN `dim-address`ON `dim-address`.`ID` =`log-farm`.`DIMaddrID`
    INNER JOIN `dim-farm`ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    WHERE `log-farm`.`DIMSubfID` IS NULL AND `log-farm`.`EndT`IS NULL $text 
    ORDER BY `dim-address`.`Province`,`dim-address`.`Distrinct`,`dim-user`.`Alias`";
    $data = selectAll($sql);
    echo json_encode($data);
}
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "changphotoFarm":

            if (isset($_POST['imagebase64'])) {
                $fmid = $_POST['FMID'];


                $data = $_POST['imagebase64'];

                $img_array = explode(';', $data);
                $img_array2 = explode(",", $img_array[1]);
                $data = base64_decode($img_array2[1]);

                $DIMFARM = getDIMFarm($fmid);
                $id_dim = $DIMFARM[1]['ID'];
                $time = time();
                $Icon = $time . '.png';
                $sql =   "UPDATE `db-farm` SET `Icon` = '$Icon' WHERE `db-farm`.`FMID` = $fmid ";
                updateData($sql);
                if (!file_exists("../../icon/farm/$fmid")) {
                    mkdir("../../icon/farm/$fmid");
                }

                file_put_contents("../../icon/farm/$fmid/$Icon", $data);


                $data_t =  getDIMDate();
                $id_t = $data_t[1]['ID'];
                $loglogin = $_SESSION[md5('LOG_LOGIN')];
                $loglogin_id = $loglogin[1]['ID'];
                $path = "icon/farm/" . $fmid;


                $sql =   "UPDATE `log-icon` SET EndT='$time', EndID='$id_t'
                WHERE `DIMiconID`='$id_dim' AND EndT IS NULL AND `Type`='4'";

                updateData($sql);

                $sql = "INSERT INTO `log-icon` (`ID`,LOGloginID,StartT,StartID,DIMiconID,`Type`,`FileName`,`Path`) 
                 VALUES ('','$loglogin_id','$time','$id_t','$id_dim','4','$Icon','$path')";

                addinsertData($sql);
                header("location:./OilPalmAreaListDetail.php?fmid=$fmid");
            }
            break;
    }
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


function getDIMAddr($AID, $addfarm)
{
    $sql = "SELECT subDistrinct ,Distrinct,Province,`AD3ID`,`db-distrinct`.`AD2ID`,`db-province`.`AD1ID` FROM `db-subdistrinct`INNER JOIN `db-distrinct` ON  `db-subdistrinct`.`AD2ID`=`db-distrinct`.`AD2ID`
    INNER JOIN `db-province` ON `db-distrinct`.`AD1ID`=`db-province`.`AD1ID` WHERE `AD3ID`=$AID";
    $DataFarm = selectData($sql);
    $Fulltext = "$addfarm ต.{$DataFarm[1]['subDistrinct']} อ.{$DataFarm[1]['Distrinct']} จ.{$DataFarm[1]['Province']}";
    $sql = "SELECT * FROM `dim-address` WHERE `FullAddress`='" . $Fulltext . "' ";
    $DIMFarm = selectData($sql);
    if ($DIMFarm[0]['numrow'] == 0) {
        $sql = "INSERT INTO `dim-address` (`ID`, `FullAddress`, `dbsubDID`, `dbDistID`, `dbprovID`, `SubDistrinct`, `Distrinct`, `Province`) VALUES (NULL, '$Fulltext', '{$DataFarm[1]['AD3ID']}', '{$DataFarm[1]['AD2ID']}', '{$DataFarm[1]['AD1ID']}', '{$DataFarm[1]['subDistrinct']}', '{$DataFarm[1]['Distrinct']}', '{$DataFarm[1]['Province']}');";
        $IDDIMF = addinsertData($sql);
        $sql = "SELECT * FROM `dim-address` WHERE`ID`='$IDDIMF'";
        $DIMFarm = selectData($sql);
    }
    return  $DIMFarm;
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
        $sql = "SELECT * FROM `dim-user` WHERE`ID`='$IDDIMF'";
        //echo $sql . "<br/>";
        $DIMFarm = selectData($sql);
    }
    return  $DIMFarm;
}
