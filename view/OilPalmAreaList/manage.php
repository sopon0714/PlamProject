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
    $ChackChangeAddress = false;
    $sql = "SELECT * FROM `db-subdistrinct` WHERE AD3ID='$subdistrinct'";
    $dataAddress = selectData($sql);
    $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL ";
    $LOGFarm = selectData($sql);
    $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$Date[1]['ID']}' WHERE `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL";
    updateData($sql);
    $sql = "UPDATE `db-farm` SET `Name` = '$namefarm', `Alias` = '$aliasfarm', `Address` = '$addfarm', `AD3ID` = '$subdistrinct', `UFID` = '$farmer' WHERE `db-farm`.`FMID` = '$IDFarm'";
    updateData($sql);

    $sql = "SELECT * FROM `log-icon` WHERE `Type`=4 AND `DIMiconID`={$DIMFarm[1]['ID']} AND `EndT` IS NULL ";
    $LOGIConFarm = selectData($sql);
    if ($LOGIConFarm[0]['numrow'] > 0) {
        $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$Date[1]['ID']}' WHERE `Type`=4 AND `DIMiconID`={$DIMFarm[1]['ID']} AND `EndT` IS NULL";
        updateData($sql);
    }
    $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
    $DIMFarmer = getDIMFarmer($farmer);
    $DIMFarm = getDIMFarm($IDFarm);
    $DIMAddr = getDIMAddr($subdistrinct, $addfarm);

    if ($LOGIConFarm[0]['numrow'] > 0) {
        $sql = "INSERT INTO `log-icon` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMiconID`, `Type`, `FileName`, `Path`) 
        VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$Date[1]['ID']}', NULL, NULL, '{$DIMFarm[1]['ID']}', '4', '{$LOGIConFarm[1]['FileName']}', '{$LOGIConFarm[1]['Path']}')";
        addinsertData($sql);
    }
    for ($i = 1; $i < count($LOGFarm); $i++) {
        $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
        VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$Date[1]['ID']}', NULL, NULL, '{$DIMFarmer[1]['ID']}', '{$DIMFarm[1]['ID']}', ";
        if ($LOGFarm[$i]['DIMSubfID'] == NULL) {
            $sql .= "NULL, '{$DIMAddr[1]['ID']}',";
        } else {
            $sql .= "'{$LOGFarm[$i]['DIMSubfID']}' ,'{$LOGFarm[$i]['DIMaddrID']}', ";
        }

        $sql .= "'{$LOGFarm[$i]['IsCoordinate']}',' {$LOGFarm[$i]['Latitude']}', ' {$LOGFarm[$i]['Longitude']}', '{$LOGFarm[$i]['NumSubFarm']}', '{$LOGFarm[$i]['NumTree']}', '{$LOGFarm[$i]['AreaRai']}', '{$LOGFarm[$i]['AreaNgan']}', '{$LOGFarm[1]['AreaWa']}', '{$LOGFarm[$i]['AreaTotal']}')";

        addinsertData($sql);
    }
    if ($ChackChangeAddress) {
        $sql = " UPDATE `db-subfarm` SET `IsCoordinate` = '0', `Latitude` = '{$dataAddress[1]['Latitude']}', `Longitude` = '{$dataAddress[1]['Longitude']}' WHERE `db-subfarm`.`FMID` = $IDFarm";
        updateData($sql);
        $sql = " UPDATE `db-farm` SET `IsCoordinate` = '0', `Latitude` = '{$dataAddress[1]['Latitude']}', `Longitude` = '{$dataAddress[1]['Longitude']}' WHERE `db-farm`.`FMID` = $IDFarm";
        updateData($sql);
        $sql = "DELETE FROM `db-coorfarm` WHERE `db-coorfarm`.`FCID`IN 
        (SELECT `db-coorfarm`.`FCID` FROM `db-coorfarm` INNER JOIN `db-subfarm`
         ON  `db-coorfarm`.`FSID` = `db-subfarm`.`FSID` WHERE `db-subfarm`.`FMID` = '$IDFarm')";
        delete($sql);
    }

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
        case "changphotoSubFarm":

            if (isset($_POST['imagebase64'])) {
                $fmid = $_POST['FMID'];
                $fsid = $_POST['FSID'];
                $data = $_POST['imagebase64'];

                $img_array = explode(';', $data);
                $img_array2 = explode(",", $img_array[1]);
                $data = base64_decode($img_array2[1]);

                $DIMSUBFARM = getDIMSubFarm($fsid);
                $id_dim = $DIMSUBFARM[1]['ID'];
                $time = time();
                $Icon = $time . '.png';
                $sql =   "UPDATE `db-subfarm` SET `Icon` = '$Icon' WHERE `db-subfarm`.`FSID` = $fsid ";
                updateData($sql);
                if (!file_exists("../../icon/subfarm/$fsid")) {
                    mkdir("../../icon/subfarm/$fsid");
                }

                file_put_contents("../../icon/subfarm/$fsid/$Icon", $data);


                $data_t =  getDIMDate();
                $id_t = $data_t[1]['ID'];
                $loglogin = $_SESSION[md5('LOG_LOGIN')];
                $loglogin_id = $loglogin[1]['ID'];
                $path = "icon/subfarm/" . $fsid;


                $sql =   "UPDATE `log-icon` SET EndT='$time', EndID='$id_t'
                    WHERE `DIMiconID`='$id_dim' AND EndT IS NULL AND `Type`='3'";
                updateData($sql);

                $sql = "INSERT INTO `log-icon` (`ID`,LOGloginID,StartT,StartID,DIMiconID,`Type`,`FileName`,`Path`) 
                     VALUES ('','$loglogin_id','$time','$id_t','$id_dim','3','$Icon','$path')";

                addinsertData($sql);
                header("location:./OilPalmAreaListSubDetail.php?FSID=$fsid&FMID=$fmid");
            }
            break;
        case "addSubFarm":
            $nameSubfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['nameSubfarm']));
            $initialsSubfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['initialsSubfarm']));
            $AreaRai = $_POST['AreaRai'];
            $AreaNgan = $_POST['AreaNgan'];
            $AreaWa = $_POST['AreaWa'];
            $AD3ID = $_POST['distrinctSF'];
            $addfarmSF = preg_replace('/[[:space:]]+/', ' ', trim($_POST['addfarmSF']));;
            $Areatotal = (400 * $AreaRai) + ($AreaNgan * 100) + $AreaWa;
            $fmid = $_POST['fmid'];
            $time = time();
            $sql = "SELECT * FROM `db-farm` WHERE `db-farm`.`FMID`=$fmid";
            $INFOFARM = selectData($sql);
            $sql = "INSERT INTO `db-subfarm` (`FSID`, `Name`, `Alias`, `Icon`, `Address`, `AD3ID`, `FMID`, `IsCoordinate`, `Latitude`, `Longitude`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '$nameSubfarm', '$initialsSubfarm', 'default.png', '$addfarmSF', '$AD3ID',  '$fmid', '0', ' {$INFOFARM[1]['Latitude']}', ' {$INFOFARM[1]['Longitude']}', '$AreaRai', '$AreaNgan', '$AreaWa', '$Areatotal')";
            $idDBSubfarm = addinsertData($sql);
            $DIMSubfarm = getDIMSubFarm($idDBSubfarm);
            $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
            $DIMDATE = getDIMDate();
            $DIMFARMER = getDIMFarmer($INFOFARM[1]['UFID']);
            $DIMFARM = getDIMFarm($fmid);
            $DIMAdderss = getDIMAddr($AD3ID, $addfarmSF);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', '{$DIMSubfarm[1]['ID']}', '{$DIMAdderss[1]['ID']}', '0', '{$INFOFARM[1]['Latitude']}', '{$INFOFARM[1]['Longitude']}', '1', '0', '$AreaRai', '$AreaNgan', '$AreaWa', '$Areatotal')";
            addinsertData($sql);
            $sql = "SELECT * FROM `log-farm` WHERE `DIMfarmID`={$DIMFARM[1]['ID']} AND `EndT` IS NULL AND `DIMSubfID` IS NULL";
            $INFOLOGFARM = selectData($sql);
            $sumNumSubFarm = ((int) $INFOLOGFARM[1]['NumSubFarm']) + 1;
            $sumAreaRai = ((int) $INFOLOGFARM[1]['AreaRai']) + $AreaRai;
            $sumAreaNgan = ((int) $INFOLOGFARM[1]['AreaNgan']) + $AreaNgan;
            $sumAreaWa = ((int) $INFOLOGFARM[1]['AreaWa']) + $AreaWa;
            $sumAreaTotal = (int) $INFOLOGFARM[1]['AreaTotal'] + $Areatotal;
            $sql = "UPDATE `log-farm` SET `EndT` = '$time',  `EndID` = '{$DIMDATE[1]['ID']}' WHERE `log-farm`.`ID` = {$INFOLOGFARM[1]['ID']}";
            updateData($sql);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', NULL, '{$INFOLOGFARM[1]['DIMaddrID']}', '{$INFOLOGFARM[1]['IsCoordinate']}', '{$INFOLOGFARM[1]['Latitude']}', '{$INFOLOGFARM[1]['Longitude']}', '$sumNumSubFarm', '{$INFOLOGFARM[1]['NumTree']}', '$sumAreaRai', '$sumAreaNgan', '$sumAreaWa', '$sumAreaTotal')";
            addinsertData($sql);
            header("location:./OilPalmAreaListDetail.php?fmid=$fmid");

            break;
        case "editLatLngMapFarm":
            $fmid = $_POST['fmid'];
            $lat = $_POST['lat'];
            $lng = $_POST['lng'];
            $sql =   "UPDATE `db-farm` SET `IsCoordinate` = '1', `Latitude` = '$lat', `Longitude` = '$lng' 
            WHERE `db-farm`.`FMID` = $fmid";
            updateData($sql);
            $DIMDATE = getDIMDate();
            $time = time();
            $DIMFarm = getDIMFarm($fmid);
            $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
            $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL AND `log-farm`.`DIMSubfID` IS NULL  ";
            $LOGFarm = selectData($sql);
            $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'  WHERE `log-farm`.`ID` IN 
            (SELECT `log-farm`.`ID` FROM `log-farm` INNER JOIN `dim-farm` ON `log-farm`.`DIMfarmID`=`dim-farm`.`ID` 
            WHERE`dim-farm`.`dbID`=$fmid AND `dim-farm`.`IsFarm`=1 AND `log-farm`.`EndT`IS NULL AND `log-farm`.`DIMSubfID` IS NULL ) ";
            updateData($sql);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$LOGFarm[1]['DIMownerID']}', '{$LOGFarm[1]['DIMfarmID']}', NULL, '{$LOGFarm[1]['DIMaddrID']}', '1', '$lat', '$lng', '{$LOGFarm[1]['NumSubFarm']}', '{$LOGFarm[1]['NumTree']}', '{$LOGFarm[1]['AreaRai']}', '{$LOGFarm[1]['AreaNgan']}', '{$LOGFarm[1]['AreaWa']}', '{$LOGFarm[1]['AreaTotal']}')";
            addinsertData($sql);
            break;
        case "deleteSubFarm":
            $fsid = $_POST['fsid'];
            $time = time();
            $sql = "SELECT * FROM `db-subfarm` WHERE `db-subfarm`.`FSID` = $fsid";
            $INFOSUBFARM = selectData($sql);
            $DIMDATE = getDIMDate();
            $DIMFarm = getDIMFarm($INFOSUBFARM[1]['FMID']);
            $DIMSUBFARM  = getDIMSubFarm($fsid);

            $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL AND `log-farm`.`DIMSubfID` IS NULL ";
            $LOGFarm = selectData($sql);
            $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL AND `log-farm`.`DIMSubfID` ={$DIMSUBFARM[1]['ID']}";
            $LOGSubFarm = selectData($sql);

            $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'  WHERE `log-farm`.`ID` IN 
            (SELECT `log-farm`.`ID` FROM `log-farm` INNER JOIN `dim-farm` ON `log-farm`.`DIMSubfID`=`dim-farm`.`ID` 
            WHERE`dim-farm`.`dbID`=$fsid AND `dim-farm`.`IsFarm`=0 AND `log-farm`.`EndT`IS NULL) ";
            updateData($sql);
            $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'
            WHERE `log-icon`.`Type`= 3 AND `log-icon`.`EndT` IS NULL AND `log-icon`.`DIMiconID` IN 
            (SELECT `dim-farm`.`ID` FROM `dim-farm` INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID`=`dim-farm`.`dbID`
             WHERE `dim-farm`.`IsFarm`=0 AND `db-subfarm`.`FSID` = $fsid) ";
            updateData($sql);

            $sql = "DELETE FROM `db-coorfarm` WHERE `db-coorfarm`.`FSID`= $fsid";
            delete($sql);
            $sql = "DELETE FROM `db-subfarm` WHERE `db-subfarm`.`FSID`= $fsid";
            delete($sql);

            $sql = "SELECT AVG(`Latitude`) as Latitude , AVG(`Longitude`) as Longitude FROM `db-subfarm` WHERE `db-subfarm`.`FMID` = {$INFOSUBFARM[1]['FMID']}";
            $INFOAVGLATLNG = selectData($sql);
            $Latitude = $LOGFarm[1]['Latitude'];
            $Longitude = $LOGFarm[1]['Longitude'];
            if ($INFOAVGLATLNG[0]['Latitude'] != NULL) {
                $Latitude = $INFOAVGLATLNG[1]['Latitude'];
                $Longitude = $INFOAVGLATLNG[1]['Longitude'];
            }
            $sql = "UPDATE `db-farm` SET `IsCoordinate` = '1', `Latitude` = ' $Latitude', `Longitude` = ' $Longitude' 
            WHERE `db-farm`.`FMID` ={$INFOSUBFARM[1]['FMID']}";
            updateData($sql);

            $DiffNumTree = $LOGFarm[1]['NumTree'] - $LOGSubFarm[1]['NumTree'];
            $DiffRai = $LOGFarm[1]['AreaRai'] - $LOGSubFarm[1]['AreaRai'];
            $DiffNgan = $LOGFarm[1]['AreaNgan'] - $LOGSubFarm[1]['AreaNgan'];
            $DiffWa = $LOGFarm[1]['AreaWa'] - $LOGSubFarm[1]['AreaWa'];
            $Difftotal = $LOGFarm[1]['AreaTotal'] - $LOGSubFarm[1]['AreaTotal'];
            $DiffNumSubFarm  =  $LOGFarm[1]['NumSubFarm'] - 1;

            $sql = "UPDATE `log-farm` SET `EndT` = '$time',  `EndID` = '{$DIMDATE[1]['ID']}' WHERE `log-farm`.`ID` = {$LOGFarm[1]['ID']}";
            updateData($sql);
            if ($INFOAVGLATLNG[1]['Latitude'] != NULL) {
                $Latitude = $INFOAVGLATLNG[1]['Latitude'];
                $Longitude = $INFOAVGLATLNG[1]['Longitude'];
            }
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$LOGFarm[1]['DIMownerID']}', '{$LOGFarm[1]['DIMfarmID']}', NULL, '{$LOGFarm[1]['DIMaddrID']}', '{$LOGFarm[1]['IsCoordinate']}', '$Latitude', '$Longitude', '$DiffNumSubFarm', '$DiffNumTree', '$DiffRai', '$DiffNgan', '$DiffWa', '$Difftotal')";
            addinsertData($sql);
            header("location:./OilPalmAreaListDetail.php?fmid={$INFOSUBFARM[1]['FMID']}");
            break;
        case "editSubFarm":
            $nameSubfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['nameSubfarm']));
            $initialsSubfarm = preg_replace('/[[:space:]]+/', ' ', trim($_POST['initialsSubfarm']));
            $AreaRai = $_POST['AreaRai'];
            $AreaNgan = $_POST['AreaNgan'];
            $AreaWa = $_POST['AreaWa'];
            $AD3ID = $_POST['distrinct'];
            $addfarmSF = preg_replace('/[[:space:]]+/', ' ', trim($_POST['addfarmSF']));;
            $Areatotal = (400 * $AreaRai) + ($AreaNgan * 100) + $AreaWa;
            $fmid = $_POST['fmid'];
            $fsid = $_POST['fsid'];
            $time = time();
            $DIMSubfarm = getDIMSubFarm($fsid);
            $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
            $DIMDATE = getDIMDate();
            $sql = "SELECT * FROM `db-farm` WHERE `db-farm`.`FMID`=$fmid";
            $INFOFARM = selectData($sql);
            $DIMFARMER = getDIMFarmer($INFOFARM[1]['UFID']);
            $DIMFARM = getDIMFarm($fmid);
            $DIMAdderss = getDIMAddr($AD3ID, $addfarmSF);

            $sql = "SELECT * FROM `log-farm` WHERE `DIMfarmID`={$DIMFARM[1]['ID']} AND `DIMSubfID`={$DIMSubfarm[1]['ID']} AND`EndT` IS NULL";
            $INFOSUBFARM = selectData($sql);
            $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}' WHERE  `DIMfarmID`={$DIMFARM[1]['ID']} AND `DIMSubfID`={$DIMSubfarm[1]['ID']} AND`EndT` IS NULL";
            updateData($sql);
            $sql = "SELECT * FROM `log-icon`   WHERE Type=3 AND DIMiconID ={$DIMSubfarm[1]['ID']} AND`EndT` IS NULL";
            $INFOICONSUBFARM = selectData($sql);
            $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}' WHERE Type=3 AND DIMiconID ={$DIMSubfarm[1]['ID']} AND`EndT` IS NULL";
            updateData($sql);
            $sql = "UPDATE `db-subfarm` SET `Name` = '$nameSubfarm', `Alias` = '$initialsSubfarm', `Address` = '$addfarmSF', `AD3ID` = '$AD3ID', 
            `AreaRai` = '$AreaRai', `AreaNgan` = '$AreaNgan', `AreaWa` = '$AreaWa', `AreaTotal` = '$Areatotal'
             WHERE `db-subfarm`.`FSID` = $fsid";
            updateData($sql);
            $DIMSubfarm = getDIMSubFarm($fsid);
            if ($INFOICONSUBFARM[0]['numrow'] > 0) {
                $sql = "INSERT INTO `log-icon` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMiconID`, `Type`, `FileName`, `Path`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$DIMSubfarm[1]['ID']}', '3', '{$INFOICONSUBFARM[1]['FileName']}', '{$INFOICONSUBFARM[1]['Path']}')";
                addinsertData($sql);
            }

            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', '{$DIMSubfarm[1]['ID']}', '{$DIMAdderss[1]['ID']}', '0', '{$INFOSUBFARM[1]['Latitude']}', '{$INFOSUBFARM[1]['Longitude']}', '{$INFOSUBFARM[1]['NumSubFarm']}', '{$INFOSUBFARM[1]['NumTree']}', '$AreaRai', '$AreaNgan', '$AreaWa', '$Areatotal')";
            addinsertData($sql);

            $DiffRai = $AreaRai - ((int) $INFOSUBFARM[1]['AreaRai']);
            $DiffNgan = $AreaNgan - ((int) $INFOSUBFARM[1]['AreaNgan']);
            $DiffWa = $AreaWa - ((int) $INFOSUBFARM[1]['AreaWa']);
            $Difftotal  = $Areatotal - ((int) $INFOSUBFARM[1]['AreaTotal']);

            $sql = "SELECT * FROM `log-farm` WHERE `DIMfarmID`={$DIMFARM[1]['ID']} AND `EndT` IS NULL AND `DIMSubfID` IS NULL";
            $INFOLOGFARM = selectData($sql);
            $sumAreaRai = ((int) $INFOLOGFARM[1]['AreaRai']) + $DiffRai;
            $sumAreaNgan = ((int) $INFOLOGFARM[1]['AreaNgan']) + $DiffNgan;
            $sumAreaWa = ((int) $INFOLOGFARM[1]['AreaWa']) + $DiffWa;
            $sumAreaTotal = (int) $INFOLOGFARM[1]['AreaTotal'] + $Difftotal;
            $sql = "UPDATE `log-farm` SET `EndT` = '$time',  `EndID` = '{$DIMDATE[1]['ID']}' WHERE `log-farm`.`ID` = {$INFOLOGFARM[1]['ID']}";
            updateData($sql);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', NULL, '{$INFOLOGFARM[1]['DIMaddrID']}', '{$INFOLOGFARM[1]['IsCoordinate']}', '{$INFOLOGFARM[1]['Latitude']}', '{$INFOLOGFARM[1]['Longitude']}', '{$INFOLOGFARM[1]['NumSubFarm']}', '{$INFOLOGFARM[1]['NumTree']}', '$sumAreaRai', '$sumAreaNgan', '$sumAreaWa', '$sumAreaTotal')";
            addinsertData($sql);
            header("location:./OilPalmAreaListSubDetail.php?FSID=$fsid&FMID=$fmid");
            break;
        case "editlocationMap":
            $fsid = $_POST['FSID'];
            $latArray = json_decode($_POST['resultlat'], true);
            $lngArray = json_decode($_POST['resultlng'], true);
            $LatAvg = 0;
            $LongAvg = 0;
            $sql = "DELETE FROM `db-coorfarm` WHERE `db-coorfarm`.`FSID`= $fsid";
            delete($sql);
            $sql = "INSERT INTO `db-coorfarm` (`FCID`, `FSID`, `Corner`, `Latitude`, `Longitude`, `ModifyDT`)
             VALUES ";
            for ($i = 0; $i < count($latArray); $i++) {
                $numcoor = $i + 1;
                $LatAvg += $latArray[$i];
                $LongAvg += $lngArray[$i];
                $sql .= "(NULL, '$fsid', '$numcoor', '$latArray[$i]', '$lngArray[$i]', current_timestamp()),";
            }
            $LatAvg =  $LatAvg / count($latArray);
            $LongAvg = $LongAvg / count($latArray);
            $sql = substr($sql, 0, -1);
            addinsertData($sql);
            $sql = "UPDATE `db-subfarm` SET `IsCoordinate` = '1', `Latitude` = '$LatAvg', `Longitude` = '$LongAvg' WHERE `db-subfarm`.`FSID` = $fsid";
            updateData($sql);
            $sql = "SELECT * FROM `db-subfarm` WHERE `db-subfarm`.`FSID`=$fsid";
            $INFOSUBFARM = selectData($sql);
            $sql = "SELECT AVG(`Latitude`) as Latitude , AVG(`Longitude`) as Longitude FROM `db-subfarm` WHERE `db-subfarm`.`FMID` = {$INFOSUBFARM[1]['FMID']}";
            $INFOAVGLATLNG = selectData($sql);
            $sql = "UPDATE `db-farm` SET `IsCoordinate` = '1', `Latitude` = '{$INFOAVGLATLNG[1]['Latitude']}', `Longitude` = '{$INFOAVGLATLNG[1]['Longitude']}' 
            WHERE `db-farm`.`FMID` ={$INFOSUBFARM[1]['FMID']}";
            updateData($sql);
            $DIMFarm = getDIMFarm($INFOSUBFARM[1]['FMID']);
            $DIMSUBFARM  = getDIMSubFarm($fsid);
            $Date = getDIMDate();
            $time = time();
            $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
            $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL AND `log-farm`.`DIMSubfID` IS NULL ";
            $LOGFarm = selectData($sql);
            $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFarm[1]['ID']}' AND `EndT` IS NULL AND `log-farm`.`DIMSubfID` ={$DIMSUBFARM[1]['ID']}";
            $LOGSubFarm = selectData($sql);
            $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$Date[1]['ID']}' WHERE `log-farm`.`ID` = '{$LOGFarm[1]['ID']}' OR `log-farm`.`ID` = '{$LOGSubFarm[1]['ID']}' ";
            updateData($sql);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$LOGFarm[1]['DIMownerID']}', '{$LOGFarm[1]['DIMfarmID']}', NULL, '{$LOGFarm[1]['DIMaddrID']}', '1', '{$INFOAVGLATLNG[1]['Latitude']}', '{$INFOAVGLATLNG[1]['Longitude']}', '{$LOGFarm[1]['NumSubFarm']}', '{$LOGFarm[1]['NumTree']}', '{$LOGFarm[1]['AreaRai']}', '{$LOGFarm[1]['AreaNgan']}', '{$LOGFarm[1]['AreaWa']}', '{$LOGFarm[1]['AreaTotal']}')";
            addinsertData($sql);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$LOGSubFarm[1]['DIMownerID']}', '{$LOGSubFarm[1]['DIMfarmID']}', '{$LOGSubFarm[1]['DIMSubfID']}', '{$LOGSubFarm[1]['DIMaddrID']}', '1', '$LatAvg', '$LongAvg', '{$LOGSubFarm[1]['NumSubFarm']}', '{$LOGSubFarm[1]['NumTree']}', '{$LOGSubFarm[1]['AreaRai']}', '{$LOGSubFarm[1]['AreaNgan']}', '{$LOGSubFarm[1]['AreaWa']}', '{$LOGSubFarm[1]['AreaTotal']}')";
            addinsertData($sql);
            break;
        case "addPlantting":
            $fmid = $_POST['fmid'];
            $fsid = $_POST['fsid'];
            $TypePlantting = $_POST['TypePlantting'];
            $dateActive = $_POST['dateActive'];
            $PalmTree = $_POST['PalmTree'];
            $DIMSUBFARM = getDIMSubFarm($fsid);
            $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
            $DIMDATE = getDIMDate($dateActive);
            $time = time();
            $sql = "SELECT * FROM `db-farm` WHERE `db-farm`.`FMID`=$fmid";
            $INFOFARM = selectData($sql);
            $DIMFARMER = getDIMFarmer($INFOFARM[1]['UFID']);
            $DIMFARM = getDIMFarm($fmid);
            $timestemp = strtotime($dateActive);
            $NUM1  = "NULL";
            $NUM2  = "NULL";
            $NUM3  = "NULL";
            if ($TypePlantting == 1) {
                $NUM1  = "'$PalmTree'";
            } else if ($TypePlantting == 2) {
                $NUM2  = "'$PalmTree'";
            } else  if ($TypePlantting == 3) {
                $NUM3  = "'$PalmTree'";
            }

            $sql = "INSERT INTO `log-planting` (`ID`, `isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `NumGrowth1`, `NumGrowth2`, `NumDead`, `PICs`) VALUES (NULL, '0', '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATE[1]['ID']}', '{$DIMFARMER[1]['ID']}', '{$DIMFARM[1]['ID']}', '{$DIMSUBFARM[1]['ID']}', $NUM1, $NUM2, $NUM3, 'test')";
            echo  $sql . "<br>";
            $id = addinsertData($sql);

            $sql = "UPDATE `log-planting` SET `PICs` = 'picture/activities/planting/$id' WHERE `log-planting`.`ID` = '$id'";
            echo  $sql;
            $sql = "SELECT * FROM `log-farm` WHERE `DIMfarmID`={$DIMFARM[1]['ID']} AND `EndT` IS NULL AND `DIMSubfID` IS NULL";
            $INFOLOGFARM = selectData($sql);
            $sql = "SELECT * FROM `log-farm` WHERE  `log-farm`.`DIMfarmID` = '{$DIMFARM[1]['ID']}' AND `EndT` IS NULL AND `log-farm`.`DIMSubfID` ={$DIMSUBFARM[1]['ID']}";
            $LOGSubFarm = selectData($sql);

            $sql = "UPDATE `log-farm` SET `EndT` = '$time',  `EndID` = '{$DIMDATE[1]['ID']}' WHERE `log-farm`.`ID` = {$INFOLOGFARM[1]['ID']}";
            updateData($sql);
            $sql = "UPDATE `log-farm` SET `EndT` = '$time',  `EndID` = '{$DIMDATE[1]['ID']}' WHERE `log-farm`.`ID` = {$LOGSubFarm[1]['ID']}";
            updateData($sql);

            if ($TypePlantting != 3) {
                $PalmTreeDeffFM = $INFOLOGFARM[1]['NumTree'] + $PalmTree;
                $PalmTreeDeffFS = $LOGSubFarm[1]['NumTree'] + $PalmTree;
            } else {
                $PalmTreeDeffFM = $INFOLOGFARM[1]['NumTree'] - $PalmTree;
                $PalmTreeDeffFS = $LOGSubFarm[1]['NumTree'] - $PalmTree;
            }


            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$INFOLOGFARM[1]['DIMownerID']}', '{$INFOLOGFARM[1]['DIMfarmID']}', NULL, '{$INFOLOGFARM[1]['DIMaddrID']}', '{$INFOLOGFARM[1]['IsCoordinate']}', '{$INFOLOGFARM[1]['Latitude']}', '{$INFOLOGFARM[1]['Longitude']}', '{$INFOLOGFARM[1]['NumSubFarm']}', '$PalmTreeDeffFM', '{$INFOLOGFARM[1]['AreaRai']}', '{$INFOLOGFARM[1]['AreaNgan']}', '{$INFOLOGFARM[1]['AreaWa']}', '{$INFOLOGFARM[1]['AreaTotal']}')";
            addinsertData($sql);
            $sql = "INSERT INTO `log-farm` (`ID`, `LOGloginID`, `StartT`, `StartID`, `EndT`, `EndID`, `DIMownerID`, `DIMfarmID`, `DIMSubfID`, `DIMaddrID`, `IsCoordinate`, `Latitude`, `Longitude`, `NumSubFarm`, `NumTree`, `AreaRai`, `AreaNgan`, `AreaWa`, `AreaTotal`) 
            VALUES (NULL, '{$LOG_LOGIN[1]['ID']}', '$time', '{$DIMDATE[1]['ID']}', NULL, NULL, '{$LOGSubFarm[1]['DIMownerID']}', '{$LOGSubFarm[1]['DIMfarmID']}', '{$LOGSubFarm[1]['DIMSubfID']}', '{$LOGSubFarm[1]['DIMaddrID']}', '{$LOGSubFarm[1]['IsCoordinate']}', '{$LOGSubFarm[1]['Latitude']}', '{$LOGSubFarm[1]['Longitude']}', '{$LOGSubFarm[1]['NumSubFarm']}', '$PalmTreeDeffFS', '{$LOGSubFarm[1]['AreaRai']}', '{$LOGSubFarm[1]['AreaNgan']}', '{$LOGSubFarm[1]['AreaWa']}', '{$LOGSubFarm[1]['AreaTotal']}')";
            addinsertData($sql);

            header("location:./OilPalmAreaListSubDetail.php?FSID=$fsid&FMID=$fmid");
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
