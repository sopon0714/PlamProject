<?php
include_once("./../../dbConnect.php");
$myConDB = connectDB();
date_default_timezone_set("Asia/Bangkok");
$currentYear = date("Y") + 543;
$backYear = $currentYear - 1;
set_time_limit(0);
ini_set('memory_limit', '-1');

function creatCard($styleC, $headC, $textC, $iconC, $size = 3)
{
    echo "<div class='col-xl-$size col-12 mb-4'>
        <div class='card border-left-primary $styleC shadow h-100 py-2'>
            <div class='card-body'>
                <div class='row no-gutters align-items-center'>
                    <div class='col mr-2'>
                        <div class='font-weight-bold  text-uppercase mb-1'>$headC</div>
                        <div class='h5 mb-0 font-weight-bold text-gray-800'>$textC</div>
                    </div>
                    <div class='col-auto'>
                        <i class='material-icons icon-big'>$iconC</i>
                    </div>
                </div>
            </div>
        </div>
    </div>";
}
//-----------------FertilizerList --------------------
function getCountFertilizer()
{
    $sql = "SELECT COUNT(*) AS countFertilizer FROM `log-fertilizer` WHERE `log-fertilizer`.`isDelete`= 0";
    $countFertilizer = selectData($sql)[1]['countFertilizer'];
    return $countFertilizer;
}
function getFertilizer()
{
    $sql = "SELECT * FROM `log-fertilizer` WHERE `log-fertilizer`.`isDelete`=0 ORDER BY `log-fertilizer`.`Name`";
    $DATAFER = selectData($sql);
    $INFO = array();
    for ($i = 1; $i <= $DATAFER[0]['numrow']; $i++) {
        $INFO[$DATAFER[$i]['ID']]['FID'] = $DATAFER[$i]['ID'];
        $INFO[$DATAFER[$i]['ID']]['Name'] = $DATAFER[$i]['Name'];
        $INFO[$DATAFER[$i]['ID']]['Alias'] = $DATAFER[$i]['Alias'];
        $INFO[$DATAFER[$i]['ID']]['composition']['หลัก'] = array();
        $INFO[$DATAFER[$i]['ID']]['composition']['รอง'] = array();
        $sql = "SELECT * FROM `log-fertilizercomposition` WHERE `FerID` = {$DATAFER[$i]['ID']} ORDER BY `log-fertilizercomposition`.`NID`";
        $DATANUTR = selectData($sql);
        for ($j = 1; $j <= $DATANUTR[0]['numrow']; $j++) {
            $sql = "SELECT`dim-nutrient`.`Name`, `log-nutrient`.`Type` FROM `log-nutrient` 
            INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-nutrient`.`DIMnutrID`
            WHERE `dim-nutrient`.`dbID` = {$DATANUTR[$j]['NID']} ORDER BY `log-nutrient`.`ID` DESC LIMIT 1";
            $DATADETAILNUTR = selectData($sql);
            if ($DATADETAILNUTR[1]['Type'] == 'ธาตุอาหารหลัก') {
                array_push($INFO[$DATAFER[$i]['ID']]['composition']['หลัก'], $DATADETAILNUTR[1]['Name']);
            } else if ($DATADETAILNUTR[1]['Type']  == 'ธาตุอาหารรอง') {
                array_push($INFO[$DATAFER[$i]['ID']]['composition']['รอง'], $DATADETAILNUTR[1]['Name']);
            }
        }
    }
    return $INFO;
}
function getNutr()
{
    $sql = "SELECT * FROM `db-nutrient` ORDER BY `db-nutrient`.`Name`";
    $DATA = selectData($sql);
    return $DATA;
}
function getMainNutr()
{
    $sql = "SELECT * FROM `db-nutrient`WHERE `Type` = 'ธาตุอาหารหลัก' ORDER BY `db-nutrient`.`Name`";
    $DATA = selectData($sql);
    return $DATA;
}
function getSubNutr()
{
    $sql = "SELECT * FROM `db-nutrient`WHERE `Type` = 'ธาตุอาหารรอง' ORDER BY `db-nutrient`.`Name`";
    $DATA = selectData($sql);
    return $DATA;
}
//-----------------Department.php---------------------
//จำนวนหน่วยงานทั้งหมด
function getCountDepartment()
{
    $sql = "SELECT COUNT(*) AS countDepartment FROM `db-department`";
    $countDepartment = selectData($sql)[1]['countDepartment'];
    return $countDepartment;
}
//หน่วยงานทั้งหมด
function getDepartment()
{
    $sql = "SELECT * FROM `db-department`";
    $DEPARTMENT = selectData($sql);
    return $DEPARTMENT;
}
//หน่วยงาน ตาม id หน่วยงาน
function getDepartmentUser($did)
{
    $sql = "SELECT * FROM `db-department` WHERE DID = $did ";
    $DEPARTMENTUSER = selectData($sql);
    return $DEPARTMENTUSER;
}
//หน่วยงานทั้งหมด (table) 
function getAllDepartment()
{
    $sql = "SELECT `db-department`.`DID`,`db-department`.`Department`,`db-department`.`Alias`,`db-department`.`Note`,COUNT(`db-user`.`DID`) AS count_de FROM `db-department` 
    LEFT JOIN `db-user` ON `db-department`.DID = `db-user`.DID GROUP BY `db-department`.`DID`,`db-department`.`Department`,`db-department`.`Alias`,`db-department`.`Note`";
    $ALLDEPARTMENT = selectData($sql);
    return $ALLDEPARTMENT;
}
//-----------------OtherUserlist.php---------------------
//จำนวนผู้ใช้ทั้งหมด
function getCountUser()
{
    $sql = "SELECT COUNT(*) AS countUser FROM `db-user`";
    $countUser = selectData($sql)[1]['countUser'];
    return $countUser;
}
//จำนวนผู้ดูแลระบบทั้งหมด
function getCountAdmin()
{
    $sql = "SELECT COUNT(*) AS countAdmin FROM `db-user` WHERE IsAdmin = 1 ";
    $countAdmin = selectData($sql)[1]['countAdmin'];
    return $countAdmin;
}
//emailtype ทั้งหมด
function getEmailtype()
{
    $sql = "SELECT * FROM `db-emailtype`";
    $EMAILTYPE = selectData($sql);
    return $EMAILTYPE;
}
//emailtype ตาม id emailtype
function getEmailtypeUser($etid)
{
    $sql = "SELECT * FROM `db-emailtype` WHERE ETID = $etid ";
    $EMIALTYPEUSER = selectData($sql);
    return $EMIALTYPEUSER;
}
//ผู้ใช้ ตาม id ผู้ใช้
function getUser($id_u)
{
    $sql = "SELECT * FROM `db-user` WHERE `UID`=$id_u";
    $USER = selectData($sql);
    return $USER;
}
//จังหวัดทั้งหมด
function getProvince()
{
    $sql = "SELECT * FROM `db-province` ORDER BY `db-province`.`Province`  ASC";
    $PROVINCE = selectData($sql);
    return $PROVINCE;
}
//อำเภอทั้งหมด
function getDistrinct()
{
    $sql = "SELECT * FROM `db-distrinct`  
    ORDER BY `db-distrinct`.`AD1ID` ASC, `db-distrinct`.`Distrinct` ASC";
    $DATA = selectData($sql);
    return $DATA;
}
//ตำบลทั้งหมด
function getSubDistrinct()
{
    $sql = "SELECT * FROM `db-subdistrinct`  
    ORDER BY `db-subdistrinct`.`AD2ID` ASC, `db-subdistrinct`.`subDistrinct` ASC";
    $DATA = selectData($sql);
    return $DATA;
}
//อำเภอในจังหวัด ตาม id จังหวัด
function getDistrinctInProvince($ad1id)
{
    $sql = "SELECT * FROM `db-distrinct` WHERE `AD1ID`=$ad1id ORDER BY `db-distrinct`.`Distrinct`  ASC";
    $DISTRINCT_PROVINCE = selectData($sql);
    return $DISTRINCT_PROVINCE;
}
//ตำบลในอำเภอ ตาม id อำเภอ
function getSubDistrinctInDistrinct($ad2id)
{
    $sql = "SELECT * FROM `db-subdistrinct` WHERE `db-subdistrinct`.`AD2ID` = $ad2id ORDER BY `db-subdistrinct`.`subDistrinct` ASC";
    $SUBDISTRINCT_DISTRINCT = selectData($sql);
    return $SUBDISTRINCT_DISTRINCT;
}

//-----------------------FarmerList--------------------------
function getFarmerAll()
{
    $sql = "SELECT `log-farmer`.`ID`,`dim-user`.`dbID`,`dim-user`.`FullName` FROM `log-farmer`
    JOIN `dim-user` ON `log-farmer`.`DIMuserID` = `dim-user`.`ID`
    WHERE `log-farmer`.`ID` IN (
    SELECT MAX(`log-farmer`.`ID`) as max FROM `log-farmer`
    JOIN `dim-user` ON `log-farmer`.`DIMuserID` = `dim-user`.`ID`
    GROUP BY `dim-user`.`dbID`)";
    $data = selectData($sql);
    return $data;
}
function getCountFarmer()
{
    $sql = "SELECT COUNT(*) AS countFarmer FROM `db-farmer`";
    $countFarmer = selectData($sql)[1]['countFarmer'];
    return $countFarmer;
}

function getCountAllArea()
{
    $sql = "SELECT SUM(`AreaRai`) AS countsubFarm 
    FROM (SELECT `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL) AS farm";
    $countAllArea = selectData($sql)[1]['countAllArea'];
    return $countAllArea;
}
//จำนวนพื้นที่ทั้งหมด
function getCountArea()
{
    $sql = "SELECT IF(SUM(`AreaRai`) IS NULL ,0,SUM(`AreaRai`)) AS countArea FROM `db-subfarm`";
    return selectData($sql)[1]['countArea'];
}

//ตารางเกษตรกร (table)
function getFarmer(&$idformal, &$fullname, &$fpro, &$fdist,$start,$limit,$latitude,$longitude)
{
    //INFO
    $sql = "SELECT * FROM(
        SELECT dataFarmer.UFID AS dbID,dataFarmer.FormalID,dataFarmer.FirstName,CONCAT(dataFarmer.Title,' ',dataFarmer.FirstName,' ',dataFarmer.LastName)AS FullName,dataFarmer.Province,dataFarmer.Distrinct,dataFarmer.AD3ID, dataFarmer.AD2ID,dataFarmer.AD1ID,dataFarmer.subDistrinct,dataFarmer.Latitude,dataFarmer.Longitude,IF(dataFarmer.numFarm IS NULL,0,dataFarmer.numFarm)AS numFarm,IF(dataFarmer.numSubFarm IS NULL,0,dataFarmer.numSubFarm)AS numSubFarm,IF(dataFarmer.numTree IS NULL,0,dataFarmer.numTree)AS numTree,IF(dataFarmer.numArea1 IS NULL,0,dataFarmer.numArea1)AS numArea1,IF(dataFarmer.numArea2 IS NULL,0,dataFarmer.numArea2)AS numArea2 FROM(
        SELECT * FROM(
        SELECT * FROM(
        SELECT * FROM(
        SELECT * FROM(
        SELECT * FROM(
        SELECT `db-farmer`.`UFID`,
            CASE WHEN  `db-farmer`.`Title` IN ('1') THEN 'นาย'
            WHEN  `db-farmer`.`Title` IN ('2') THEN 'นาง' 
            WHEN  `db-farmer`.`Title` IN ('3') THEN 'นางสาว' END AS Title,
            `db-farmer`.`FirstName`, `db-farmer`.`LastName`, `db-farmer`.`FormalID`,
             `Distrinct`,`Province`, `subDistrinct`,`db-farmer`.`AD3ID`,`Latitude`,`Longitude`,`db-distrinct`.`AD2ID`,`db-distrinct`.`AD1ID`
             FROM `db-farmer` 
             JOIN `db-subdistrinct` ON `db-subdistrinct`.`AD3ID` = `db-farmer`.`AD3ID` 
             JOIN `db-distrinct` ON `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
             JOIN `db-province` ON `db-province`.`AD1ID` = `db-distrinct`.`AD1ID`)AS data1
             LEFT JOIN (
             SELECT COUNT(*) AS numFarm ,farm.`dbID` AS UFID1
            FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
            WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL) AS farm
            GROUP BY farm.`dbID`)AS countFarm ON countFarm.UFID1 = data1.UFID)AS data2
            LEFT JOIN (
                SELECT SUM(`NumSubFarm`) AS numSubFarm, subfarm.`dbID` AS UFID2
            FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
            WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL) AS subfarm
            GROUP BY subfarm.`dbID`)AS coutSubFarm ON coutSubFarm.UFID2 = data2.UFID)AS data3
            LEFT JOIN (SELECT SUM(`NumTree`) AS numTree,tree.`dbID` AS UFID3
            FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
            WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL) AS tree
             GROUP BY tree.`dbID`)AS countTree ON countTree.UFID3 = data3.UFID)AS data4
             LEFT JOIN (
             SELECT SUM(`AreaRai`) AS numArea1,area1.`dbID` AS UFID4
            FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
            WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL) AS area1
            GROUP BY area1.`dbID`)AS countArea1 ON countArea1.UFID4 = data4.UFID)AS data5
            LEFT JOIN (
                     SELECT SUM(`AreaNgan`) AS numArea2,area2.`dbID` AS UFID5
            FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
            WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL) AS area2
            GROUP BY area2.`dbID`)AS countArea2 ON countArea2.UFID5 = data5.UFID)AS dataFarmer)AS dataFarmerAll
            WHERE 1 ";
    if ($idformal != '') $sql = $sql . " AND dataFarmerAll.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql = $sql . " AND dataFarmerAll.`FullName` LIKE '%" . $fullname . "%' ";
    if ($fpro    != 0)  $sql = $sql . " AND dataFarmerAll.`AD1ID` = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql = $sql . " AND dataFarmerAll.`AD2ID`= '" . $fdist . "' ";

    if ($latitude != '') $sql = $sql . " AND dataFarmerAll.`Latitude` LIKE '%" . $latitude . "%' ";
    if ($longitude != '') $sql = $sql . " AND dataFarmerAll.`Longitude` LIKE '%" . $longitude . "%' ";

    $sql = $sql . " ORDER BY dataFarmerAll.`FirstName`";
    if ($limit != 0) $sql = $sql . " LIMIT ".$start." , ".$limit;
    $DATA = selectData($sql);
    return $DATA;
}
//-----------------------FarmerListDetail--------------------------

function getFarmerByUFID($ufid)
{
    $sql = "SELECT * , CASE WHEN `Title` IN ('1') THEN 'นาย'
    WHEN `Title` IN ('2') THEN 'นาง' 
    WHEN `Title` IN ('3') THEN 'นางสาว' END AS Title2                   
    FROM `db-farmer` JOIN `db-subdistrinct` ON `db-subdistrinct`.`AD3ID` = `db-farmer`.`AD3ID` 
    JOIN `db-distrinct` ON `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
    JOIN `db-province` ON `db-province`.`AD1ID` = `db-distrinct`.`AD1ID` WHERE `UFID` =$ufid ";
    return selectData($sql);
}

function getCountOwnerFarm($ufid)
{
    $sql = "SELECT COUNT(*) AS countownerFarm 
    FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL AND `dim-user`.`dbID` = $ufid) AS farm";
    $countownerFarm = selectData($sql)[1]['countownerFarm'];
    return $countownerFarm;
}

function getCountOwnerSubFarm($ufid)
{
    $sql = "SELECT SUM(`NumSubFarm`) AS countownersubFarm 
    FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL AND `dim-user`.`dbID` = $ufid) AS farm";
    $countownerFarm = selectData($sql)[1]['countownersubFarm'];
    if ($countownerFarm == NULL)
        return 0;
    return $countownerFarm;
}

function getCountOwnerAreaRai($ufid)
{
    $sql = "SELECT SUM(`AreaRai`) AS countownerAreaRai
    FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL AND `dim-user`.`dbID` = $ufid) AS farm";
    $countownerAreaRai = selectData($sql)[1]['countownerAreaRai'];
    if ($countownerAreaRai == NULL)
        return 0;
    return $countownerAreaRai;
}
function getCountOwnerAreaNgan($ufid)
{
    $sql = "SELECT SUM(`AreaNgan`) AS countownerAreaNgan
    FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL AND `dim-user`.`dbID` = $ufid) AS farm";
    $countownerAreaNgan = selectData($sql)[1]['countownerAreaNgan'];
    if ($countownerAreaNgan == NULL)
        return 0;
    return $countownerAreaNgan;
}
function getCountOwnerTree($ufid)
{
    $sql = "SELECT SUM(`NumTree`) AS countownerTree
    FROM (SELECT `dim-user`.`dbID`, `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL AND `dim-user`.`dbID` = $ufid) AS farm";
    $countownerTree = selectData($sql)[1]['countownerTree'];
    if ($countownerTree == NULL)
        return 0;
    return $countownerTree;
}

//FarmerListDetail
function getProfile($ufid)
{
    $sql = "SELECT * , CASE WHEN `Title` IN ('1') THEN 'นาย'
    WHEN `Title` IN ('2') THEN 'นาง' 
    WHEN `Title` IN ('3') THEN 'นางสาว' END AS Title                   
    FROM `db-farmer` JOIN `db-subdistrinct` ON `db-subdistrinct`.`AD3ID` = `db-farmer`.`AD3ID` 
    JOIN `db-distrinct` ON `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
    JOIN `db-province` ON `db-province`.`AD1ID` = `db-distrinct`.`AD1ID` WHERE `UFID` =$ufid ";
    $data = selectData($sql);
    return $data;
}
function getOwnerFarmer($ufid)
{
    $sql = "SELECT  `db-farm`.`FMID`,`db-farm`.`Name`,`db-province`.`Province`,`db-distrinct`.`Distrinct`,`db-subdistrinct`.`subDistrinct`, `db-farm`.`AD3ID`,
    `db-farm`.`Latitude`, `db-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`AreaRai`,`log-farm`.`AreaNgan`,`log-farm`.`NumTree` FROM `log-farm` 
    JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
    JOIN `dim-farm` ON  `dim-farm`.`ID` =  `log-farm`.`DIMfarmID`
    JOIN `db-farm` ON  `db-farm`.`FMID` = `dim-farm`.`dbID`
    JOIN `db-subdistrinct` ON `db-subdistrinct`.`AD3ID` = `db-farm`.`AD3ID`
    JOIN `db-distrinct` ON `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
    JOIN `db-province` ON `db-province`.`AD1ID` = `db-distrinct`.`AD1ID`
    WHERE `dim-user`.`Type` = 'F' AND `log-farm`.`EndT` IS NULL 
    AND `dim-user`.`dbID` = $ufid AND `log-farm`.`DIMSubfID` IS NULL";
    $data = selectData($sql);
    return $data;
}

//OwnerFarm Table
function getOwnerFarm($ufid)
{
    $sql = "SELECT `dim-farm`.`Name`,`db-province`.`Province`,`db-distrinct`.`Distrinct`,`NumSubFarm`,`AreaRai`,`AreaNgan`,`NumTree`FROM `log-farm`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    INNER JOIN `db-farm` ON `db-farm`.`FMID` = `dim-farm`.`dbID`
    INNER JOIN `db-subdistrinct` ON `db-subdistrinct`.`AD3ID` = `db-farm`.`AD3ID` 
    INNER JOIN `db-distrinct` ON `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
    INNER JOIN `db-province` ON `db-province`.`AD1ID` = `db-distrinct`.`AD1ID` 
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` AND `dim-user`.`Type` = 'F'
    WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL AND `dim-user`.`dbID` = $ufid  ";
    $ownerFarm = selectData($sql);
    return $ownerFarm;
}
//----------------------------Nutrient--------------------------------------

function getCountNutrientM()
{
    $sql = "SELECT COUNT(*) AS countFer FROM `db-nutrient` 
    join `dim-nutrient` on (`dbID` = `db-nutrient`.`NID`) 
    WHERE `db-nutrient`.`Name` = `dim-nutrient`.`Name` AND `db-nutrient`.`Type` ='ธาตุอาหารหลัก' ";
    return selectData($sql)[1]['countFer'];
}
function getCountNutrientS()
{
    $sql = "SELECT COUNT(*) AS countFer FROM `db-nutrient` 
    join `dim-nutrient` on (`dbID` = `db-nutrient`.`NID`) 
    WHERE `db-nutrient`.`Name` = `dim-nutrient`.`Name` AND `db-nutrient`.`Type` ='ธาตุอาหารรอง'";
    return selectData($sql)[1]['countFer'];
}

//-----------------------------OilPalmAreaList-------------------------------
// จำนวนสวน
function getCountFarm()
{
    $sql = "SELECT COUNT(ID) AS CountFarm FROM `log-farm` WHERE ISNULL(`EndT`) AND ISNULL(`DIMSubfID`)";
    $countFarm = selectData($sql)[1]['CountFarm'];
    return $countFarm;
}
// จำนวนแปลง
function getCountSubfarm()
{
    $sql = "SELECT COUNT(ID) AS CountSubfarm FROM `log-farm` WHERE ISNULL(`EndT`) AND !ISNULL(`DIMSubfID`)";
    $countSubfarm = selectData($sql)[1]['CountSubfarm'];
    return $countSubfarm;
}
// จำนวนไร่ 
function getAreaRaiLog()
{
    $sql = "SELECT SUM(AreaRai) AS AreaRai, SUM(AreaNgan) AS AreaNgan 
    FROM `log-farm` WHERE DIMSubfID IS null";
    $data = selectData($sql);
    return $data;
}
// จำนวนไร่ 
function getAreaRai()
{
    $sql = "SELECT SUM(`AreaRai`)AS AreaRai FROM `db-subfarm`";
    $areaRai = selectData($sql)[1]['AreaRai'];
    return $areaRai;
}
function getAreaNgan()
{
    $sql = "SELECT SUM(`AreaNgan`)AS AreaNgan FROM `db-subfarm`";
    $data = selectData($sql)[1]['AreaNgan'];
    return $data;
}
// จำนวนต้นไม้
function getCountTree()
{
    $sql = "SELECT IF(sum(`log-farm`.`NumTree`) IS NULL,0,sum(`log-farm`.`NumTree`) )as numTree FROM `log-farm` where `log-farm`.`EndT` is null AND `log-farm`.`DIMSubfID` is null";
    $numTree = selectData($sql)[1]['numTree'];
    return $numTree;
}
// ตารางสวนปาล์มน้ำมันในระบบ
function getOilPalmAreaList(&$idformal, &$fullname, &$fpro, &$fdist,$start,$limit,$latitude,$longitude)
{
    $namef = explode(" ", $fullname);
    if (isset($namef[1])) {
        $fnamef = $namef[0];
        $lnamef = $namef[1];
    } else {
        $fnamef = $fullname;
        $lnamef = $fullname;
    }
    $sql = "SELECT `log-farm`.`ID`,`dim-farm`.`dbID` AS FMID ,
    `dim-address`.`Province`,`dim-address`.`Distrinct`,`dim-address`.`dbsubDID`,`log-farm`.`Latitude`,`log-farm`.`Longitude`,
    `dim-user`.`FullName`, `dim-user`.`Alias`, `dim-farm`.`Name`, `log-farm`.`NumSubFarm`,
    `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`,`log-farm`.`NumTree` 
    FROM `log-farm` 
    INNER JOIN `dim-user`ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
    INNER JOIN `dim-address`ON `dim-address`.`ID` =`log-farm`.`DIMaddrID`
    INNER JOIN `dim-farm`ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    WHERE `log-farm`.`DIMSubfID` IS NULL AND`log-farm`.`EndT`IS NULL ";
    if ($idformal != '') $sql .= " AND `dim-user`.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";
    if ($latitude != '') $sql = $sql . " AND `log-farm`.`Latitude` = '" . $latitude . "' ";
    if ($longitude != '') $sql = $sql . " AND`log-farm`.`Longitude` = '" . $longitude . "' ";
    $sql .= " ORDER BY `dim-address`.`Province`,`dim-address`.`Distrinct`,`dim-user`.`Alias`";
    if ($limit != 0) $sql = $sql . " LIMIT ".$start." , ".$limit;

    $OilPalmAreaList = selectData($sql);
    return $OilPalmAreaList;
}
// ตารางรายการแปลงปลูกปาล์มน้ำมัน ต้องมีการส่งค่า DIMfarmID มาด้วย
function getOilPalmAreaListDetail($DIMfarmID)
{
    $sql = "SELECT `db-subfarm`.`FSID`,`db-subfarm`.`Name`,`db-subfarm`.`AreaRai`,`db-subfarm`.`AreaNgan`,`log-farm`.`NumTree` , FLOOR(TIMESTAMPDIFF(DAY,`dim-time`.`Date`,CURRENT_TIME)% 30.4375 )as day, FLOOR(TIMESTAMPDIFF( MONTH,`dim-time`.`Date`,CURRENT_TIME)% 12 )as month, FLOOR(TIMESTAMPDIFF( YEAR,`dim-time`.`Date`,CURRENT_TIME))as year 
    from `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID` INNER JOIN `log-planting` ON `dim-farm`.`ID` =`log-planting`.`DIMsubFID` INNER JOIN `dim-time` on `log-planting`.`DIMdateID` = `dim-time`.`ID` INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID` = `dim-farm`.`dbID` 
    WHERE ISNULL(`log-farm`.`EndID`) AND `log-farm`.`DIMfarmID` = '$DIMfarmID'";
    $OilPalmAreaListDetail = selectData($sql);
    return $OilPalmAreaListDetail;
}


function getOilPalmAreaListDetailByIdFarm($fmid)
{
    $sql = "SELECT `db-subfarm`.`FSID`,`db-subfarm`.`Name`,`db-subfarm`.`AreaRai`,`db-subfarm`.`AreaNgan`,`log-farm`.`NumTree` ,`log-farm`.`Latitude`,`log-farm`.`Longitude`,`dim-address`.`Province`,`dim-address`.`Distrinct`
    FROM  `db-subfarm` INNER JOIN `dim-farm` ON `db-subfarm`.`FSID`=`dim-farm`.`dbID` 
    INNER JOIN `log-farm` ON `log-farm`.`DIMSubfID`=`dim-farm`.`ID`
    INNER JOIN `dim-address` ON `dim-address`.`ID`=`log-farm`.`DIMaddrID`
    WHERE `dim-farm`.`IsFarm`=0  AND `log-farm`.`EndT`IS NULL AND `db-subfarm`.`FMID` = $fmid";
    $OilPalmAreaListDetail = selectData($sql);
    $sql = "SELECT `db-subfarm`.`FSID`, FLOOR(TIMESTAMPDIFF(DAY,`dim-time`.`Date`,CURRENT_TIME)% 30.4375 )as day, FLOOR(TIMESTAMPDIFF( MONTH,`dim-time`.`Date`,CURRENT_TIME)% 12 )as month, FLOOR(TIMESTAMPDIFF( YEAR,`dim-time`.`Date`,CURRENT_TIME))as year 
    FROM `log-planting`
    INNER JOIN  `dim-farm` ON `dim-farm`.`ID`=`log-planting`.`DIMsubFID`
    INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID` =`dim-farm`.`dbID`
    INNER JOIN `dim-time` ON `dim-time`.`ID`= `log-planting`.`DIMdateID`
    WHERE `dim-farm`.`IsFarm`=0 AND `log-planting`.`isDelete`=0 AND `db-subfarm`.`FMID`=$fmid AND `log-planting`.`NumGrowth1` IS NOT NULL";
    $OldOilPalmDetail = selectData($sql);
    for ($i = 1; $i < count($OilPalmAreaListDetail); $i++) {
        for ($j = 1; $j < count($OldOilPalmDetail); $j++) {
            if ($OldOilPalmDetail[$j]['FSID'] == $OilPalmAreaListDetail[$i]['FSID']) {
                $OilPalmAreaListDetail[$i]['day'] = $OldOilPalmDetail[$j]['day'];
                $OilPalmAreaListDetail[$i]['month'] = $OldOilPalmDetail[$j]['month'];
                $OilPalmAreaListDetail[$i]['year'] = $OldOilPalmDetail[$j]['year'];
                break;
            }
        }
        if ($j == count($OldOilPalmDetail)) {
            $OilPalmAreaListDetail[$i]['year'] = null;
        }
    }
    return $OilPalmAreaListDetail;
}

function getOldPalmByIdSubFarm($fsid)
{
    $sql = "SELECT `db-subfarm`.`FSID` ,`log-farm`.`NumTree`, FLOOR(TIMESTAMPDIFF(DAY,`dim-time`.`Date`,CURRENT_TIME)% 30.4375 )as day, FLOOR(TIMESTAMPDIFF( MONTH,`dim-time`.`Date`,CURRENT_TIME)% 12 )as month, FLOOR(TIMESTAMPDIFF( YEAR,`dim-time`.`Date`,CURRENT_TIME))as year 
    from `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID` INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.DIMaddrID INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID` = `dim-farm`.`dbID`  LEFT JOIN `log-planting` ON `dim-farm`.`ID` =`log-planting`.`DIMsubFID` LEFT JOIN `dim-time` on `log-planting`.`DIMdateID` = `dim-time`.`ID` 
    WHERE `log-farm`.`EndID`IS NULL AND  `db-subfarm`.`FSID`= $fsid AND `log-planting`.`NumGrowth1` IS NOT NULL";
    $OilPalmAreaListDetail = selectData($sql);
    return $OilPalmAreaListDetail;
}


// ***************** เริ่ม sql หน้า OilPalmAreaListDetail.php *****************

// sql ค่าของ areatotal มีการรับค่า ID ของ logfarmID
function getLogfarmIDpalm($fmid)
{
    $sqllogfarmID = "SELECT `log-farm`.`ID`,`dim-user`.`FullName`,`dim-farm`.`Name`,`db-farm`.`Alias` FROM `log-farm` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID` 
    INNER JOIN `db-farm` ON `dim-farm`.`dbID` = `db-farm`.`FMID` 
    INNER JOIN `db-farmer` ON `db-farmer`.`UFID` = `db-farm`.`UFID` 
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
    WHERE `db-farm`.`FMID` = '" . $fmid . "' AND `dim-farm`.`IsFarm` = 1 AND`log-farm`.`EndT` is null AND `log-farm`.`DIMSubfID` is null";
    $logfarmID = selectData($sqllogfarmID);
    return  $logfarmID;
}

function getAreatotal($logfarmID)
{
    $sql = "SELECT * FROM `log-farm`WHERE `ID`='$logfarmID'";
    $Areatotal = selectData($sql);
    return $Areatotal;
}
function getAreatotalByIdFarm($fmid)
{
    $sql = "SELECT `log-farm`.`ID`,`log-farm`.`AreaRai`,`log-farm`.`AreaNgan`,`log-farm`.`AreaWa` 
    FROM `log-farm` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`log-farm`.`DIMfarmID`
    INNER JOIN `db-farm` ON `db-farm`.`FMID`=`dim-farm`.`dbID` 
    WHERE `log-farm`.`DIMSubfID`IS  NULL AND `log-farm`.`EndT` IS NULL
    AND `dim-farm`.`IsFarm`=1 AND `db-farm`.`FMID`=$fmid";
    $Areatotal = selectData($sql);
    return $Areatotal;
}
function getAreatotalByIdSubFarm($fsid)
{
    $sql = "SELECT `log-farm`.`ID`,`log-farm`.`AreaRai`,`log-farm`.`AreaNgan`,`log-farm`.`AreaWa` 
    FROM `log-farm` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`log-farm`.`DIMSubfID`
    INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID`=`dim-farm`.`dbID` 
    WHERE  `log-farm`.`EndT` IS NULL
    AND `dim-farm`.`IsFarm`=0 AND `db-subfarm`.`FSID`=$fsid";
    $Areatotal = selectData($sql);
    return $Areatotal;
}

// sql ค่าของ subfarm มีการรับค่า ID ของ DIMfarmID
function getSubfarm($fmid)
{
    $sql = "SELECT t.FSID as fsid,t.n as namesub,t.n2,t.AreaTotal ,NumTree FROM 
    (SELECT `db-subfarm`.`FSID`,`db-subfarm`.Name as n,`db-farm`.Name as n2,`db-subfarm`.AreaTotal ,NumTree,`log-farm`.`DIMfarmID`,`log-farm`.`DIMSubfID`, `dim-farm`.`IsFarm` FROM `db-subfarm` 
    inner join `db-farm` on `db-subfarm`.`FMID` = `db-farm`.`FMID` 
    INNER JOIN `dim-farm` on `db-farm`.FMID = `dim-farm`.`dbID`  
    INNER JOIN `log-farm` on `log-farm`.`DIMfarmID`=`dim-farm`.`ID`
    where `log-farm`.`NumSubFarm` = '1' 
    GROUP by `db-subfarm`.Name
    ORDER by `log-farm`.`ID` DESC) as t 
    where t.`DIMfarmID`='$fmid'
    GROUP by t.n";
    $Subfarm = selectData($sql);
    return $Subfarm;
}
function getSubfarmByFMID($fmid)
{
    $sql = "SELECT * FROM `db-subfarm` WHERE FMID = '$fmid' ";
    $data = selectData($sql);
    return $data;
}
function getSubfarmByModify($dim_farm, $modify)
{
    $sql = "SELECT * FROM `log-farm` 
    JOIN `dim-farm` ON `log-farm`.`DIMSubfID` = `dim-farm`.`ID`
    WHERE '$modify' >= `log-farm`.`StartT` AND ( '$modify' <= `log-farm`.`EndT`
    OR  `log-farm`.`EndT` IS NULL) AND  `log-farm`.`DIMSubfID` IS NOT NULL
    AND `log-farm`.`DIMfarmID` = '$dim_farm'";
    $data = selectData($sql);
    return $data;
}

// sql ค่าของ address มีการรับค่าชื่อสวน เราไม่รู้ว่า ทำไมอิงใช่ซื่อนะ ถ้าเปลี่ยนเป็น ID อันล่างที่เขียนไว้
// function getAddress($Name)
// {
//     $sql = "SELECT Address , subDistrinct , Distrinct , Province FROM `db-farm`
//     inner join db-subdistrinct on `db-subdistrinct`.`AD3ID` = `db-farm`.`AD3ID`
//     inner join db-distrinct on `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
//     inner join db-province on `db-province`.`AD1ID` = `db-distrinct`.`AD1ID`
//     where Name = '$Name'";
//     $address = selectData($sql);
//     return $address;
// }
// อันนี้จะเป็นรับค่า ID FMID เขียนมาให้ 2 แบบลองเลือกเอาว่าอันไหนเหมาะสมกว่า
function getAddress($FMID)
{
    $sql = "SELECT Address , subDistrinct , Distrinct , Province FROM `db-farm` 
    inner join `db-subdistrinct` on `db-subdistrinct`.`AD3ID` = `db-farm`.`AD3ID` 
    inner join `db-distrinct` on `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID` 
    inner join `db-province` on `db-province`.`AD1ID` = `db-distrinct`.`AD1ID`
     where `FMID`='$FMID'";
    $address = selectData($sql);
    return $address;
}

// sql ค่าของ DATAFarm มีการรับค่า fmid
function getDATAFarmByFMID($fmid)
{
    $sql = "SELECT FMID,Name,Alias,Address,UFID,Icon, `db-farm`.`AD3ID`,`db-subdistrinct`.`AD2ID`,`db-distrinct`.`AD1ID`,`db-farm`.`Latitude`,`db-farm`.`Longitude`
    FROM `db-farm`INNER JOIN `db-subdistrinct`ON `db-farm`.`AD3ID`=`db-subdistrinct`.`AD3ID`
    INNER JOIN `db-distrinct`ON`db-distrinct`.`AD2ID`=`db-subdistrinct`.`AD2ID` WHERE `FMID`='$fmid'";
    $DATAFarm = selectData($sql);
    return $DATAFarm;
}

// sql ค่าของ Latlong มีการรับค่า ID ของ logfarmID
function getLatLong($logfarmID)
{
    $sql = "SELECT `log-farm`.`Latitude` , `log-farm`.`Longitude`  FROM `log-farm`
    where `log-farm`.`ID` = '$logfarmID'";
    $Latlong = selectData($sql);
    return $Latlong;
}


// sql ค่าของ Manycoor มีการรับค่า ID ของ logfarmID
function getManycoor($fmid)
{
    $sql = "SELECT `log-farm`.`Latitude` , `log-farm`.`Longitude` FROM `log-farm`
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    WHERE `log-farm`.`DIMSubfID` IS NOT null AND `dim-farm`.`dbID` = '$fmid' AND `log-farm`.`EndT` IS NULL";
    $Manycoor = selectData($sql);
    return $Manycoor;
}

// sql ค่าของ Idfarmer มีการรับค่า ID ของ logfarmID
function getIdFarmer($logfarmID)
{
    $sql = "SELECT `log-icon`.`DIMiconID`,`log-icon`.`Path`,`log-icon`.`FileName` FROM `log-icon` 
    INNER JOIN `dim-user` on`log-icon`.`DIMiconID` = `dim-user`.`ID`
    INNER JOIN `db-farmer` on `db-farmer`.`UFID` = `dim-user`.`dbID`
    WHERE `log-icon`.`Type` = 5 AND `db-farmer`.`FirstName`='$logfarmID'";
    $Idfarmer = selectData($sql);
    return $Idfarmer;
}

// sql ค่าของ Idfarm มีการรับค่า ID ของ fmid
function getIdFarm($fmid)
{
    $sql = "SELECT `log-icon`.`DIMiconID`,`log-icon`.`Path`,`log-icon`.`FileName` FROM `log-icon` 
    INNER JOIN `dim-farm` on`log-icon`.`DIMiconID` = `dim-farm`.`ID`
    INNER JOIN `db-farm` on `db-farm`.`FMID` = `dim-farm`.`dbID`
    WHERE `log-icon`.`Type` = 4 AND `db-farm`.`FMID`= '$fmid'";
    $Idfarm = selectData($sql);
    return $Idfarm;
}

// sql ค่าของ Coorsfarm มีการรับค่า ID ของ logfarmID
function getCoorsFarm($fmid)
{
    $sql = "SELECT `db-coorfarm`.`Latitude`,`db-coorfarm`.`Longitude`,`db-subfarm`.`FSID`,`db-coorfarm`.`Corner` FROM `db-coorfarm`
    INNER JOIN `db-subfarm` ON `db-coorfarm`.`FSID`=`db-subfarm`.`FSID`
    INNER JOIN `db-farm` ON `db-subfarm`.`FMID` = `db-farm`.`FMID`
    WHERE `db-farm`.`FMID` =$fmid ORDER BY `db-subfarm`.`FSID`,`db-coorfarm`.`Corner`";
    $Coorsfarm = selectData($sql);
    return $Coorsfarm;
}

// sql ค่าของ Numcoor มีการรับค่า ID ของ logfarmID
function getCountCoor($fmid)
{
    $sql = "SELECT`db-subfarm`.`FSID`,COUNT(*) as count FROM `db-coorfarm` 
    INNER JOIN `db-subfarm` ON `db-coorfarm`.`FSID`=`db-subfarm`.`FSID` 
    INNER JOIN `db-farm` ON `db-subfarm`.`FMID` = `db-farm`.`FMID` 
    WHERE `db-farm`.`FMID` = '$fmid' GROUP BY `db-subfarm`.`FSID`";
    $Numcoor = selectData($sql);
    return $Numcoor;
}

function getCoorsSubFarm($fsid)
{
    $sql = "SELECT * FROM `db-coorfarm` WHERE `db-coorfarm`.`FSID` =$fsid ORDER BY `db-coorfarm`.`Corner`";
    $Coorsfarm = selectData($sql);
    return $Coorsfarm;
}


// ***************** เริ่ม sql หน้า OilPalmAreaListSubDetail.php *****************

// sql ค่าของ LogfarmID มีการรับค่า ID ของ FSID
function getLogfarmID($suid)
{
    $sql = "SELECT  `db-farm`.`FMID`,`log-farm`.`ID`,`dim-farm`.`Name`,`db-farmer`.`FirstName`,`db-farmer`.`LastName`,`db-farm`.`Alias`, `db-subfarm`.`Name` as nsubfarm ,`log-farm`.`DIMfarmID`,`log-farm`.`DIMSubfID` ,`log-farm`.`NumTree`FROM `log-farm`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    INNER JOIN `db-farm` ON `dim-farm`.`dbID` = `db-farm`.`FMID`
    INNER JOIN `db-farmer` ON `db-farmer`.`UFID` = `db-farm`.`UFID`
    INNER JOIN `db-subfarm` ON `db-subfarm`.`FMID`=`db-farm`.`FMID`
    WHERE  `dim-farm`.`IsFarm` = 1 AND`log-farm`.`EndT` is null AND `log-farm`.`DIMSubfID` is null AND `db-subfarm`.`FSID` = '$suid'";
    $LogfarmID = selectData($sql);
    return $LogfarmID;
}

// sql ค่าของ DataFarm มีการรับค่า ID ของ FSID
function getDataSubFarmByFSID($suid)
{
    $sql = "SELECT 	`db-subfarm`.* ,`db-subdistrinct`.`AD2ID`,`db-distrinct`.`AD1ID` FROM `db-subfarm` INNER JOIN `db-subdistrinct`ON `db-subfarm`.`AD3ID`=`db-subdistrinct`.`AD3ID`
    INNER JOIN `db-distrinct`ON`db-distrinct`.`AD2ID`=`db-subdistrinct`.`AD2ID` WHERE `FSID`='$suid'";
    $DataSubFarm = selectData($sql);
    return $DataSubFarm;
}

// sql ค่าของ AddressSubDetail มีการรับค่า ID ของ FSID
function getAddressSubDetail($suid)
{
    $sql = "SELECT Address , subDistrinct , Distrinct , Province FROM `db-subfarm` 
    inner join `db-subdistrinct` on `db-subdistrinct`.`AD3ID` = `db-subfarm`.`AD3ID` 
    inner join `db-distrinct` on `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID` 
    inner join `db-province` on `db-province`.`AD1ID` = `db-distrinct`.`AD1ID`
    where `db-subfarm`.`FSID` = '$suid'";
    $AddressSubDetail = selectData($sql);
    return $AddressSubDetail;
}

// sql ค่าของ AreatotalSubDetail มีการรับค่า ID ของ logfarmID
function getAreatotalSubDetail($logfarmID)
{
    $sql = "SELECT `log-farm`.`AreaRai`,`log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,
    `log-farm`.`ID`, `log-farm`.`NumTree`
    FROM `db-farm`  
    inner join `db-subfarm` on `db-farm`.`FMID` = `db-subfarm`.`FMID` 
    INNER JOIN `dim-farm` on `db-farm`.FMID = `dim-farm`.`dbID`  
    INNER JOIN `log-farm` on `log-farm`.`DIMfarmID`=`dim-farm`.`ID`
    where `log-farm`.`ID` = '$logfarmID'
    group by `log-farm`.`ID`";
    $AreatotalSubDetail = selectData($sql);
    return $AreatotalSubDetail;
}

// sql ค่าของ Tree มีการรับค่า ID ของ logfarmID
function getTree($logfarmID)
{
    $sql = "SELECT `dim-farm`.`Name`,`log-planting`.`NumGrowth1`,`log-planting`.`NumGrowth2`,`log-planting`.`NumDead`,`dim-time`.`Date` 
    FROM `dim-farm`
   INNER JOIN `log-planting` on `log-planting`.`DIMsubFID` = `dim-farm`.`ID`
   INNER JOIN `dim-time` on `dim-time`.`ID` = `log-planting`.`DIMdateID`
   WHERE`dim-farm`.`Name` = '$logfarmID'
   GROUP BY  `dim-farm`.`Name`,`log-planting`.`NumGrowth1`,`log-planting`.`NumGrowth2`,`log-planting`.`NumDead`
   ORDER BY `log-planting`.`NumGrowth1`  DESC";
    $Tree = selectData($sql);
    return $Tree;
}
function getLogPlantingBySubfarmId($fsid)
{
    $sql = "SELECT `log-planting`.`ID` ,`dim-time`.`dd`,`dim-time`.`Month`,`dim-time`.`Year2`,IFNULL(`log-planting`.`NumGrowth1`,0) as NumGrowth1,IFNULL(`log-planting`.`NumGrowth2`,0) as NumGrowth2 ,IFNULL(`log-planting`.`NumDead`,0) as  NumDead
    FROM `log-planting` 
    INNER JOIN  `dim-farm` ON `dim-farm`.`ID` =`log-planting`.`DIMsubFID`
    INNER JOIN `db-subfarm` ON `dim-farm`.`dbID`=`db-subfarm`.`FSID`
    INNER JOIN `dim-time` ON `log-planting`.`DIMdateID`= `dim-time`.`ID`
    WHERE `db-subfarm`.`FSID`=$fsid AND `log-planting`.`isDelete`=0
    ORDER BY `dim-time`.`Date`";
    $LogPlanting = selectData($sql);
    return $LogPlanting;
}
// sql ค่าของ Tree มีการรับค่า ID ของ logfarmID
function getDmy($suid)
{
    $sql = "SELECT `dim-farm`.`Name` , `log-planting`.`DIMdateID` ,FLOOR(TIMESTAMPDIFF(DAY,`dim-time`.`Date`,CURRENT_TIME)% 30.4375 )as day,FLOOR(TIMESTAMPDIFF( MONTH,`dim-time`.`Date`,CURRENT_TIME)% 12 )as month,FLOOR(TIMESTAMPDIFF( YEAR,`dim-time`.`Date`,CURRENT_TIME))as year from
    `dim-farm` INNER JOIN `log-planting` ON `dim-farm`.`ID` =`log-planting`.`DIMsubFID`
    INNER JOIN `dim-time` on `log-planting`.`DIMdateID` = `dim-time`.`ID`
    INNER JOIN `db-subfarm`ON `db-subfarm`.`FSID`=`dim-farm`.`dbID`
    WHERE`dim-farm`.`dbID` = '$suid'
    group by `dim-farm`.`Name`,`dim-time`.`ID`";
    $dmy = selectData($sql);
    return $dmy;
}
function getYearAll()
{
    $sql = "SELECT  DISTINCT `Year2` FROM `dim-time` ORDER BY `Year2` DESC";
    $DATA = selectData($sql);
    return $DATA;
}
// sql ค่าของ Year 
function getYear($id, $isFarm)
{
    $time = time();
    if ($isFarm) {
        $sql = "SELECT DISTINCT `dim-time`.`Year2` FROM  `dim-time`
        WHERE  `dim-time`.`Year2` >= (SELECT `dim-time`.`Year2` FROM `log-farm`
        INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-farm`.`StartID`
        INNER JOIN `dim-farm`   ON `dim-farm` .`ID` = `log-farm`.`DIMfarmID`      
        WHERE `dim-farm` .`dbID` =$id
        GROUP BY `dim-farm` .`dbID`)
        AND `dim-time`.`Year2`<=(SELECT  IFNULL(`dim-time`.`Year2`,9999) AS  Year2  FROM `log-farm`
        LEFT JOIN `dim-time` ON `dim-time`.`ID` = `log-farm`.`EndID`
        WHERE `log-farm`.`ID` IN 
        (SELECT MAX(`log-farm`.`ID`) as logFarmID FROM `log-farm` 
        INNER JOIN `dim-farm`   ON `dim-farm`.`ID` =`log-farm` .`DIMfarmID` 
        WHERE `dim-farm`.`dbID` = $id AND `log-farm`.`DIMSubfID` IS NULL))
        ORDER BY `dim-time`.`Year2` DESC";
        $Year = selectData($sql);
    } else {
        $sql = "SELECT DISTINCT `dim-time`.`Year2` FROM  `dim-time`
        WHERE  `dim-time`.`Year2` >= (SELECT `dim-time`.`Year2` FROM `log-farm`
        INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-farm`.`StartID`
        INNER JOIN `dim-farm`   ON `dim-farm` .`ID` = `log-farm`.`DIMSubfID`      
        WHERE `dim-farm` .`dbID` =$id
        GROUP BY `dim-farm` .`dbID`)
        ORDER BY `dim-time`.`Year2` DESC";
        $Year = selectData($sql);
    }

    return $Year;
}

// sql ค่าของ Maxyear มีการรับค่า ID ของ logfarmID
function getMaxyear($logfarmID)
{
    $sql = "SELECT max(m.Year2) as max from (SELECT t.Year2 FROM(SELECT `dim-time`.`Year2`,`dim-farm`.`Name`,`log-harvest`.`Weight` FROM `log-harvest` INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID` INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-harvest`.`DIMsubFID` WHERE `dim-farm`.`Name` = '$logfarmID' AND`dim-farm`.`IsFarm`='0' ORDER BY `dim-time`.`Year2` ASC) as t 
    GROUP BY t.`Year2`) as m";
    $Maxyear = selectData($sql)[1]['max'];
    return $Maxyear;
}

// sql ค่าของ TempVOL มีการรับค่า ID ของ logfarmID
function getTempVOL($logfarmID)
{
    $sql = "SELECT `dim-nutrient`.`ID`,`dim-nutrient`.`Name` AS ferName,`dim-time`.`Year2` AS YY,
    SUM(`log-fertilising`.`Vol`) AS sumvol 
    FROM `log-fertilising` 
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-fertilising`.`DIMferID`  
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    where `DIMfarmID`= '$logfarmID' AND `DIMsubFID`=' $logfarmID'
    GROUP BY `dim-nutrient`.`Name` ,`dim-time`.`Year2`
    ORDER BY `dim-nutrient`.`Name` ,`dim-time`.`Year2` ";
    $TempVOL = selectData($sql);
    return $TempVOL;
}

// sql ค่าของ Yearvol มีการรับค่า ID ของ logfarmID
function getYearvol($logfarmID)
{
    $sql = "SELECT`dim-time`.`Year2`,`dim-farm`.`Name` FROM `log-fertilising`
    INNER JOIN `dim-time` ON `log-fertilising`.`DIMdateID` = `dim-time`.`ID`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    where `dim-farm`.`Name` = '$logfarmID'
    group by `dim-time`.`Year2`  
    ORDER BY `dim-time`.`Year2`  DESC LIMIT 3";
    $Yearvol = selectData($sql);
    return $Yearvol;
}

// sql ค่าของ Namevol มีการรับค่า ID ของ logfarmID
function getNamevol($suid)
{
    $sql = "SELECT `dim-nutrient`.`ID`,`dim-nutrient`.`Name`as namevol , `dim-farm`.`Name` FROM `log-fertilising` 
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-fertilising`.`DIMferID`  
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID` = `dim-farm`.`dbID`
    where `dim-farm`.`dbID` = '$suid'
    GROUP BY `dim-nutrient`.`Name` 
    ORDER BY `dim-nutrient`.`Name`";
    $Namevol = selectData($sql);
    return $Namevol;
}
// sql ค่าของ Numvol มีการรับค่า ID ของ logfarmID
function getNumvol($suid)
{
    $sql = "SELECT  `db-nutrient`.`EQ1`,`db-nutrient`.`EQ2` , `dim-farm`.`Name` FROM `db-nutrient`
    INNER JOIN `dim-nutrient` ON `dim-nutrient`.`dbID` = `db-nutrient`.`NID`
    INNER JOIN `log-fertilising` ON `log-fertilising`.`DIMferID` = `dim-nutrient`.`ID`
    INNER JOIN `dim-farm` on `dim-farm`.`ID`= `log-fertilising`.`DIMsubFID`
    INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID` = `dim-farm`.`dbID`
    where `dim-farm`.`dbID` = '$suid'";
    $Numvol = selectData($sql);
    return $Numvol;
}

// sql ค่าของ IdfarmerSubDetail มีการรับค่า ID ของ logfarmID
function getIdfarmerSubDetail($suid)
{
    $sql = "SELECT `log-icon`.`DIMiconID`,`log-icon`.`Path`,`log-icon`.`FileName` FROM `log-icon` 
    INNER JOIN `dim-user` on`log-icon`.`DIMiconID` = `dim-user`.`ID`
    INNER JOIN `db-farmer` on `db-farmer`.`UFID` = `dim-user`.`dbID`
    inner JOIN `db-farm` ON `db-farm`.`UFID` = `db-farmer`.`UFID`
    INNER JOIN `db-subfarm` on `db-subfarm`.`FMID` = `db-farm`.`FMID`
    WHERE `log-icon`.`Type` = 5  AND `db-subfarm`.`FSID`= '$suid'";
    $IdfarmerSubDetail = selectData($sql);
    return $IdfarmerSubDetail;
}

// sql ค่าของ IdfarmSubDetail มีการรับค่า ID ของ logfarmID
function getIdfarmSubDetail($suid)
{
    $sql = "SELECT `log-icon`.`DIMiconID`,`log-icon`.`Path`,`log-icon`.`FileName` FROM `log-icon` 
    INNER JOIN `dim-farm` on`log-icon`.`DIMiconID` = `dim-farm`.`ID`
    INNER JOIN `db-subfarm` on `db-subfarm`.`FSID` = `dim-farm`.`dbID`
    WHERE `log-icon`.`Type` = 3 AND `db-subfarm`.`FSID` = '$suid'";
    $IdfarmSubDetail = selectData($sql);
    return $IdfarmSubDetail;
}
function getVolFertilising($fsid)
{

    $sql = "SELECT * FROM `db-nutrient` ORDER BY `db-nutrient`.`Name`";
    $INFOFERT = selectData($sql);
    for ($i = 1; $i < count($INFOFERT); $i++) {
        $INFOFERT[$i]['dataVol'] = getVolFertYear($fsid, $INFOFERT[$i]['NID']);
    }
    return  $INFOFERT;
}
function getVolFertYear($fsid, $fid)
{
    $MaxYear = ((int) date("Y")) + 543;
    $dataVol = [];
    for ($i = 2; $i >= 0; $i--) {
        $thisYear =  $MaxYear - $i;
        $sql = "SELECT `dim-time`.`Year2`,`dim-farm`.`dbID` as FSID,`dim-nutrient`.`dbID`AS NID,SUM(`log-fertilising`.`Vol`) as Vol
        FROM `log-fertilising` INNER JOIN `dim-time` ON `dim-time`.`ID`=`log-fertilising`.`DIMdateID`
        INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`log-fertilising`.`DIMsubFID`
        INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` =`log-fertilising`.`DIMferID`
        WHERE  `log-fertilising`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid AND `dim-nutrient`.`dbID`=$fid AND `dim-time`.`Year2`= $thisYear
        GROUP BY    `dim-time`.`Year2`,`dim-farm`.`dbID`,`dim-nutrient`.`dbID`";
        $VOL = selectData($sql);
        if ($VOL[0]['numrow'] != 0) {
            array_push($dataVol, round($VOL[1]['Vol'], 2));
        } else {
            array_push($dataVol, 0);
        }
    }
    $dataFormat = "[";
    for ($i = 0; $i < count($dataVol); $i++) {
        $dataFormat .= "{$dataVol[$i]},";
    }
    $dataFormat = substr($dataFormat, 0, -1);
    $dataFormat .= "]";
    return $dataFormat;
}

//--------------------------------OilPalmAreaVol-------------------------------------

//ผลผลิตปาล์มทั้งหมด
function getHarvest()
{
    $sql = "SELECT `log-harvest`.* ,`dim-time`.`Year2` FROM `log-harvest` INNER JOIN `dim-time` ON `dim-time`.`ID`=`log-harvest`.`DIMdateID` WHERE `isDelete` = 0";
    $HARVEST = selectData($sql);
    return $HARVEST;
}
function getDetailLogFarm($fmid)
{
    $sql = "SELECT `dim-farm`.`dbID` AS FMID  ,`dim-farm`.`ID` AS DIMfarmID ,`dim-user`.`dbID`AS UFID,
    `dim-user`.`ID` AS DIMownerID ,`dim-user`.`Title`,`dim-user`.`FullName`,`dim-farm`.`Name` as NameFarm ,
     `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`, `log-farm`.`NumSubFarm`,`log-farm`.`NumTree`
      FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMfarmID`
       INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` 
       INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID` 
       WHERE `log-farm`.`ID` IN (SELECT MAX(`log-farm`.`ID`) as ID FROM `log-farm` 
       INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMfarmID` 
    WHERE `log-farm`.`DIMSubfID` IS NULL AND `dim-farm`.`dbID` = $fmid ) ";
    $INFOFARM = selectData($sql);
    $sql = "SELECT * FROM  `log-icon` WHERE `log-icon`.`ID` =
    (SELECT MAX(`log-icon`.`ID`) AS  ID FROM `log-icon` 
    WHERE `log-icon`.`DIMiconID`={$INFOFARM[1]['DIMfarmID']} AND `log-icon`.`Type`=4)";
    $logiconFarm =  selectData($sql);
    $sql = "SELECT * FROM  `log-icon` WHERE `log-icon`.`ID` =
    (SELECT MAX(`log-icon`.`ID`) AS  ID FROM `log-icon` 
    WHERE `log-icon`.`DIMiconID`={$INFOFARM[1]['DIMownerID']} AND `log-icon`.`Type`=5)";
    $logiconFarmmer =  selectData($sql);
    if ($logiconFarm[0]['numrow'] == 0) {
        $INFOFARM[1]['iconFarm'] = "default.png";
    } else {
        $INFOFARM[1]['iconFarm'] = $logiconFarm[1]['FileName'];
    }
    if ($logiconFarmmer[0]['numrow'] == 0) {
        $INFOFARM[1]['iconFarmmer'] = "default.png";
    } else {
        $INFOFARM[1]['iconFarmmer'] = $logiconFarmmer[1]['FileName'];
    }


    return $INFOFARM;
}
function getDetailLogSubFarm($fsid)
{
    $sql = "SELECT f.`dbID` AS FMID  ,f.`ID` AS DIMfarmID , sf.`dbID` AS FSID  ,
            sf.`ID` AS DIMSubfarmID ,`dim-user`.`dbID`AS UFID,
            `dim-user`.`ID` AS DIMownerID ,`dim-user`.`Title`,`dim-user`.`FullName`,
            f.`Name` as NameFarm ,sf.`Name` as NameSubfarm  , `log-farm`.`NumTree` 
             FROM `log-farm`
            INNER JOIN `dim-farm` as sf ON sf.`ID` =`log-farm`.`DIMSubfID`
            INNER JOIN `dim-farm`  as f ON f.`ID` =`log-farm`.`DIMfarmID`
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID` 
            INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID` 
            WHERE `log-farm`.`ID` IN (SELECT MAX(`log-farm`.`ID`) as ID FROM `log-farm` 
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMSubfID` 
            WHERE `dim-farm`.`dbID` = $fsid ) ";
    $INFOSUBFARM = selectData($sql);
    $sql = "SELECT * FROM  `log-icon` WHERE `log-icon`.`ID` =
    (SELECT MAX(`log-icon`.`ID`) AS  ID FROM `log-icon` 
    WHERE `log-icon`.`DIMiconID`={$INFOSUBFARM[1]['DIMSubfarmID']} AND `log-icon`.`Type`=3)";
    $logiconsubFarm =  selectData($sql);
    $sql = "SELECT * FROM  `log-icon` WHERE `log-icon`.`ID` =
    (SELECT MAX(`log-icon`.`ID`) AS  ID FROM `log-icon` 
    WHERE `log-icon`.`DIMiconID`={$INFOSUBFARM[1]['DIMownerID']} AND `log-icon`.`Type`=5)";
    $logiconFarmmer =  selectData($sql);
    if ($logiconsubFarm[0]['numrow'] == 0) {
        $INFOSUBFARM[1]['iconSubfarm'] = "default.png";
    } else {
        $INFOSUBFARM[1]['iconSubfarm'] = $logiconsubFarm[1]['FileName'];
    }
    if ($logiconFarmmer[0]['numrow'] == 0) {
        $INFOSUBFARM[1]['iconFarmmer'] = "default.png";
    } else {
        $INFOSUBFARM[1]['iconFarmmer'] = $logiconFarmmer[1]['FileName'];
    }
    return $INFOSUBFARM;
}
///////////////////////////////////////////////////////////////////////////////////
//ผลผลิตปาร์มแบบมี ID
function getHarvestID($farmID)
{
    $sql = "SELECT * FROM `log-harvest`
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
            WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0  AND `isFarm`= 1";
    $HARVEST = selectData($sql);
    $currentYear = date("Y") + 543;
    $backYear = $currentYear - 1;
    $harvestCurrentYear = 0;
    $x = count($HARVEST);
    for ($i = 1; $i < $x; $i++) {
        if ((int) date('Y', $HARVEST[$i]["Modify"]) + 543 == $currentYear) {
            $harvestCurrentYear = $harvestCurrentYear + (int) $HARVEST[$i]["Weight"];
        }
    }
    return $harvestCurrentYear;
}

//ผลผลิตต่อเดือน
function getHarvestPerMonth($farmID, $year)
{

    for ($i = 1; $i <= 12; $i++)
        $HARVESTMONTH["$i"] = 0;

    $sql = "SELECT * FROM `log-harvest` 
        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
        WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0 ";
    $HARVEST = selectData($sql);
    $x = count($HARVEST);
    for ($i = 0; $i < $x; $i++) {
        if ((int) date('Y', $HARVEST[$i]["Modify"]) + 543 == $year) {
            $HARVESTMONTH[(date("n", $HARVEST[$i]["Modify"]))] = $HARVESTMONTH[(date("n", $HARVEST[$i]["Modify"]))] + $HARVEST[$i]["Weight"];
        }
    }

    return $HARVESTMONTH;
}

//สรุปผลผลิตต่อปี
function getSummarizeHarvest($farmID, $year)
{
    $sql = "SELECT * FROM `log-harvest` 
    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
    WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0 ";
    $HARVEST = selectData($sql);
    $SUMMARIZE["Weight"] = 0;
    $SUMMARIZE["Price"] = 0;
    $x = count($HARVEST);
    for ($i = 1; $i < $x; $i++) {
        if ((int) date('Y', $HARVEST[$i]["Modify"]) + 543 == $year) {
            $SUMMARIZE["Weight"] = $SUMMARIZE["Weight"] + $HARVEST[$i]["Weight"];
            $SUMMARIZE["Price"] = $SUMMARIZE["Price"] + $HARVEST[$i]["TotalPrice"];
        }
    }
    return $SUMMARIZE;
}

function CheckPlantting($fsid)
{
    $sql = "SELECT * FROM `log-planting` INNER JOIN `dim-farm` ON `log-planting`.`DIMsubFID`= `dim-farm`.`ID`
     WHERE `log-planting`.`NumGrowth1` IS NOT NULL AND `log-planting`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid";
    $Plantting = selectData($sql);
    if ($Plantting[0]['numrow'] == 0) {
        return true;
    } else {
        return false;
    }
}
//แก้ไข
//ผลผลิตปาร์มแบบมี ID และเป็นแต่ละปีของตารางรายการเก็บผลผลิตต่อแปลง
function getHarvestYearID($farmID, $year)
{
    $sql = "SELECT * , `log-harvest`.`ID` AS `logID` FROM `log-harvest`
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMsubfID`
            JOIN `db-subfarm` ON `dim-farm`.`dbID` = `db-subfarm`.`FSID`
            WHERE `FMID` = $farmID AND `isDelete`= 0  AND `isFarm`= 0";
    $HARVEST = selectData($sql);
    $x = count($HARVEST);
    $num = 0;
    for ($i = 1; $i < $x; $i++) {
        if ((int) date('Y', $HARVEST[$i]["Modify"]) + 543 == $year) {
            $HARVESTYEAR[$num]["ID"] = $HARVEST[$i]['logID'];
            $HARVESTYEAR[$num]["alias"] = $HARVEST[$i]['Alias'];
            $HARVESTYEAR[$num]["modifyday"] = date('d', $HARVEST[$i]["Modify"]);
            $HARVESTYEAR[$num]["modifymonth"] = date('n', $HARVEST[$i]["Modify"]);
            $HARVESTYEAR[$num]["modifyyear"] = date('Y', $HARVEST[$i]["Modify"]) + 543;
            $HARVESTYEAR[$num]["weight"] = $HARVEST[$i]['Weight'];
            $HARVESTYEAR[$num]["price"] = $HARVEST[$i]['UnitPrice'];
            $HARVESTYEAR[$num]["totalprice"] = $HARVEST[$i]['TotalPrice'];
            $num++;
        }
    }
    return $HARVESTYEAR;
}

//ฟาร์มทั้งหมด
function getFarm()
{
    $sql = "SELECT * FROM `db-farm`";
    $FARM = selectData($sql);
    return $FARM;
}

//ฟาร์มแบบมี ID เจ้าของ
function getFarmOwnerID($ownerID)
{
    $sql = "SELECT * FROM `db-farm` WHERE `UFID` = $ownerID";
    $FARM = selectData($sql);
    return $FARM;
}

//ฟาร์มแบบมี ID ฟาร์ม
function getFarmFMID($farmID)
{
    $sql = "SELECT * FROM `db-farm` WHERE `UFID` = $farmID";
    $FARM = selectData($sql);
    return $FARM;
}

//แปลงทั้งหมด
function getAllSubFarm()
{
    $sql = "SELECT * FROM `db-subfarm` ";
    $FARMAREA = selectData($sql);
    return $FARMAREA;
}

//แปลงแบบมี ID
function getCountSubFarmID($farmID)
{
    $sql = "SELECT count(*) AS countSubFarm FROM `db-subfarm` WHERE `FMID` = $farmID ";
    $farmArea = selectData($sql)[1]["countSubFarm"];
    return $farmArea;
}

//พื้นที่ไร่ของแปลง
function getCountAreaRaiByFMID($farmID)
{
    $sql = "SELECT sum(`AreaRai`) AS sumAreaRai FROM `db-subfarm` WHERE `FMID` = $farmID ";
    $areaRai = selectData($sql)[1]['sumAreaRai'];
    return $areaRai;
}

//พื้นที่วาของแปลง
function getCountAreaWa($farmID)
{
    $sql = "SELECT sum(`AreaWa`) AS sumAreaWa FROM `db-subfarm` WHERE `FMID` = $farmID ";
    $areaWa = selectData($sql)[1]['sumAreaWa'];
    return $areaWa;
}

//พื้นที่งานของแปลง
function getCountAreaNgan($farmID)
{
    $sql = "SELECT sum(`AreaNgan`) AS sumAreaNgan FROM `db-subfarm` WHERE `FMID` = $farmID ";
    $areaNgan = selectData($sql)[1]['sumAreaNgan'];
    return $areaNgan;
}


//log-planting (ปุ๋ย กับ จำนวนต้นไม้)
function getLogPlanting()
{
    $sql = "SELECT * FROM `log-planting` WHERE `isDelete` = 0 ";
    $PLANTING = selectData($sql);
    return $PLANTING;
}
//getfarm หน้า OilPalmAreaVolDetail.php
function getFarmByFMID($farmID)
{
    $sql = "SELECT * FROM `db-farm` WHERE `FMID` = $farmID";
    return selectData($sql);
}
//ปีของผลผลิตที่มีใน log
function getYearOfHarvet($farmID)
{
    $num = 0;
    $sql = "SELECT * FROM `log-harvest`
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
            WHERE `dbID` = $farmID AND `isDelete`= 0 AND `isFarm` = 1";
    $HARVEST = selectData($sql);
    $x = count($HARVEST);
    $YEAR = 0;
    for ($i = 1; $i < $x; $i++) {
        $ALLYEAR[$num] = (date("Y", $HARVEST[$i]["Modify"]) + 543);
        $num += 1;
    }
    $YEAR = array_unique($ALLYEAR);
    rsort($YEAR);
    return $YEAR;
}
//ผลผลิตต่อปี
function getHarvestPerYear($farmID)
{
    $sql = "SELECT * FROM `log-harvest`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
                WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0";
    $A = selectData($sql);
    $x = count($A);
    $num = 0;
    for ($i = 1; $i < $x; $i++) {
        $GETYEAR[$num] = (date("Y", $A[$i]["Modify"]));
        $num += 1;
    }
    $YEAR = array_unique($GETYEAR);
    rsort($YEAR);
    $x = count($YEAR);

    $sql = "SELECT * FROM `log-harvest` 
        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
        WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0 ";
    $HARVEST = selectData($sql);
    for ($i = 1; $i < $x; $i++) {
        $PRODUCT["$YEAR[$i]"] = 0;
    }
    $x = count($HARVEST);
    // print_r($PRODUCT);
    for ($i = 1; $i < $x; $i++) {
        $PRODUCT[(date("Y", $HARVEST[$i]["Modify"]))] = $PRODUCT[(date("Y", $HARVEST[$i]["Modify"]))] + $HARVEST[$i]["Weight"];
    }

    return $PRODUCT;
}


//แก้ไข
//ข้อมูลของ เจ้าของฟาร์ม และ ฟาร์ม
function getOwnerData($farmID)
{
    $sql = "SELECT `db-farm`.`FMID` AS `farmID` , `db-farm`.`Name` AS `farmName` , `db-farm`.`Alias` AS `farmAlias` , 
            `db-farm`.`Icon` AS `farmIcon` ,`db-farmer`.`UFID` as `ownerID` , `db-farmer`.`FirstName` as `FirstName` ,
            `db-farmer`.`LastName` as `LastName` , `db-farmer`.`Icon` as `ownerIcon`,
            `dim-user`.`FullName` as FullName , `dim-user`.`Alias` as `ownerAlias` 
            FROM `db-farm`
            JOIN `db-farmer` ON `db-farm`.`UFID` = `db-farmer`.`UFID`
            JOIN `dim-user` ON `db-farmer`.`UFID` = `dim-user`.`dbID`
            WHERE `db-farm`.`FMID` = $farmID AND `dim-user`.`Type` = 'F' 
            ORDER BY `dim-user`.`ID` DESC
            ";
    $FARMERDATA = selectData($sql);
    return $FARMERDATA;
}
//ใช้ดึง icon หน้า OilPalmAreaVolDetail.php
function getIcon($farmID)
{
    $sql = "SELECT * FROM `dim-farm` 
    JOIN `db-farm` ON `dim-farm`.`dbID` = `db-farm`.`FMID` 
    WHERE `dim-farm`.`dbID` = $farmID AND `isFarm` = 1";
    return selectdata($sql);
}
function getAllSubFarmById($farmID)
{
    $sql = "SELECT * FROM `dim-farm`
    JOIN `db-subfarm` ON `dim-farm`.`dbID` = `db-subfarm`.`FSID`
    WHERE `isFarm` = 0 AND `FMID` = $farmID";
    return selectData($sql);
}
//จำนวนต้นไม้ของฟาร์มนั้นๆ และ การ์ดจำนวนต้น หน้า OilPalmAreaVolDetail.php
function getTreeID($farmID)
{
    $sql = "SELECT * FROM `log-planting` 
            JOIN `dim-farm` ON `log-planting`.`DIMfarmID` = `dim-farm`.`dbID`
            WHERE `isDelete`= 0 AND `dim-farm`.`isFarm` = 1  AND `dim-farm`.`dbID` = $farmID  
            ";
    $TREE = selectData($sql);
    $x = count($TREE);
    $sumTree = 0;
    for ($i = 1; $i < $x; $i++) {
        $sumTree = $sumTree + $TREE[$i]["NumGrowth1"] + $TREE[$i]["NumGrowth2"] - $TREE[$i]["NumDead"];
    }
    return $sumTree;
}

//นับจำนวนแปลงของสวนนั้นๆ และ ใช้กับการ์ดจำนวนแปลง หน้า OilPalmAreaVolDetail.php
function getCountSubFarmByFMID($farmID)
{
    $sql = "SELECT count(*) AS countFarm FROM `db-subfarm` WHERE `FMID` = $farmID";
    $countFarm = selectData($sql)[1]['countFarm'];
    return $countFarm;
}

//ชื่อเจ้าของฟาร์ม
function getOwnerName($farmID)
{
    $sql = "SELECT * FROM `db-farm`
            JOIN `db-farmer` ON `db-farm`.`UFID` = `db-farmer`.`UFID`
            WHERE `db-farm`.`FMID` = $farmID
            ";
    $OWNERNAME = selectData($sql);
    return $OWNERNAME;
}

//ใช้ตอนกราฟปุ๋ย
function getFactFertilising($farmID)
{
    $sql = "SELECT * FROM `fact-fertilizer`
            JOIN `log-fertilising` ON `log-fertilising`.`ID` = `fact-fertilizer`.`LOGferID`
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMfarmID`
            WHERE `dim-farm`.`dbID` = $farmID AND `fact-fertilizer`.`isDelete`= 0  AND `fact-fertilizer`.`Unit`= 1
            ";
    $FACTFER = selectData($sql);
    return $FACTFER;
}

//ใช้ตอนกราฟปุ๋ย
function getLogFertilizer($farmID)
{
    $sql = "SELECT * FROM `log-fertilising` 
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMfarmID`
                WHERE  `dim-farm`.`dbID` = $farmID AND `log-fertilising`.`isDelete`= 0  AND `Usage`= 1
                ";
    $LOGFER = selectData($sql);
    return $LOGFER;
}

//กราฟปุ๋ยต่อต้น
function getFerPerTree($farmID)
{
    $currentYear = date("Y") + 543;
    $num = 0;
    //หาปีที่มีผผลผลิตไปใส่ในอาเรย์
    $sql = "SELECT * FROM `log-harvest` 
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
            WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0";
    $GETYEAR = selectData($sql);
    $x = count($GETYEAR);
    $num = 0;
    for ($i = 1; $i < $x; $i++) {
        $YEAR[$num] = date("Y", $GETYEAR[$i]["Modify"]);
        $num++;
    }
    $YEAR = array_unique($YEAR);
    rsort($YEAR);
    $y = count($YEAR);
    for ($i = 0; $i < $y; $i++) {
        $FER["$YEAR[$i]"] = 0;
        $TREEPERYEAR["$YEAR[$i]"] = 0;
    }


    for ($j = 0; $j < $y; $j++) {

        $requireYear = (int) $YEAR[$j];

        if ($requireYear == $currentYear - 543) {
            $FERTHISYEAR = getLogFertilizer($farmID);
            $x = count($FERTHISYEAR);
            for ($i = 1; $i < $x; $i++) {
                if ((int) date("Y", $FERTHISYEAR[$i]["Modify"]) == (int) $requireYear) {
                    $FER[(date("Y", $FERTHISYEAR[$i]["Modify"]))] = $FER[(date("Y", $FERTHISYEAR[$i]["Modify"]))] + $FERTHISYEAR[$i]["Vol"];
                }
            }
        } else {
            $FERNOTTHISYEAR = getFactFertilising($farmID);
            $x = count($FERNOTTHISYEAR);
            for ($i = 1; $i < $x; $i++) {
                if ((int) date("Y", $FERNOTTHISYEAR[$i]["Modify1"]) == (int) $requireYear) {
                    $FER[(date("Y", $FERNOTTHISYEAR[$i]["Modify1"]))] = $FER[(date("Y", $FERNOTTHISYEAR[$i]["Modify1"]))] + $FERNOTTHISYEAR[$i]["Vol2"];
                }
            }
            $sql = "SELECT * FROM `log-planting`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-planting`.`DIMfarmID`
                WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0  AND `isFarm`= 1";
            $TREE = selectData($sql);
            $x = count($TREE);
            for ($i = 1; $i < $x; $i++) {
                if ((int) date('Y', $TREE[$i]["Modify"]) == (int) $requireYear) {
                    $TREEPERYEAR[(date("Y", $TREE[$i]["Modify"]))] = $TREEPERYEAR[(date("Y", $TREE[$i]["Modify"]))] + $TREE[$i]['NumGrowth1'] + $TREE[$i]['NumGrowth2'] - $TREE[$i]['NumDead'];
                }
            }
        }
    }

    for ($i = 0; $i < $y; $i++) {
        $requireYear = (int) $YEAR[$i];
        if ($TREEPERYEAR[$requireYear] == 0) {
            $FER[$requireYear] = $FER[$requireYear];
        } else {
            $FER[$requireYear] = (int) $FER[$requireYear] / (int) $TREEPERYEAR[$requireYear];
        }
    }

    return $FER;
}

//LogFertilising
function getLogfertilising()
{
    $sql = "SELECT * FROM `log-fertilising` WHERE `isDelete` = 0 ";
    $FERTILISING = selectData($sql);
    return $FERTILISING;
}

//การ์ด - ผลผลิตปาล์มปีนี้
function getHarvestYear($year)
{
    $HARVEST = getHarvest();
    $harvestYear = 0.0;
    for ($i = 1; $i < count($HARVEST); $i++) {
        if ($HARVEST[$i]["Year2"] == $year) {
            $harvestYear = $harvestYear +  $HARVEST[$i]["Weight"];
        }
    }
    return $harvestYear;
}

//การ์ด - ผลผลิตปาล์มปีที่แล้ว


//การ์ด - ต้นไม้ทั้งหมด
function getAllTree()
{
    $TREE = getLogPlanting();
    $x = count($TREE);
    $allTree = 0;
    for ($i = 1; $i < $x; $i++) {
        $allTree = $allTree + (int) $TREE[$i]["NumGrowth1"] + (int) $TREE[$i]["NumGrowth2"] - (int) $TREE[$i]["NumDead"];
    }
    return $allTree;
}

//การ์ด - พื้นที่ทั้งหมด
function getAllArea()
{
    $AREA = getAllSubFarm();
    $allArea = 0;
    $x = count($AREA);
    for ($i = 1; $i < $x; $i++) {
        $allArea = $allArea + (int) $AREA[$i]["AreaRai"];
    }
    return $allArea;
}

//การ์ด - ปริมาณที่ใส่ปุ๋ย
function getVolumeFertilising()
{
    $FERTILISING = getLogfertilising();
    $x = count($FERTILISING);
    $volumeFer = 0;
    for ($i = 1; $i < $x; $i++) {
        $volumeFer = $volumeFer + (float) $FERTILISING[$i]["Vol"];
    }
    return $volumeFer;
}

//ใช้ตอนค้นหา ปีที่มีการให้ปุ๋ย
function getYearFer()
{
    $sql = "SELECT `dim-time`.`Year2` FROM `log-fertilising`
    INNER JOIN `dim-time`ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    WHERE `log-fertilising`.`isDelete`= 0 
    GROUP BY `dim-time`.`Year2` 
    ORDER BY `dim-time`.`Year2` DESC";
    $YearFer = selectData($sql);
    return $YearFer;
}

//ตารางผลผลิตสวนปาล์มน้ำมันในระบบ หน้า OilPalmAreaVol.php
function getTableAllHarvest(&$idformal, &$fullname, &$fpro, &$fdist)
{
    $idformal = '';
    $fpro = 0;
    $fdist = 0;
    $fullname = '';
    if (isset($_POST['s_formalid']))  $idformal = rtrim($_POST['s_formalid']);
    if (isset($_POST['s_province']))  $fpro     = $_POST['s_province'];
    if (isset($_POST['s_distrinct'])) $fdist    = $_POST['s_distrinct'];
    if (isset($_POST['s_name'])) {
        $fullname = rtrim($_POST['s_name']);
        $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
        $namef = explode(" ", $fullname);
        if (isset($namef[1])) {
            $fnamef = $namef[0];
            $lnamef = $namef[1];
        } else {
            $fnamef = $fullname;
            $lnamef = $fullname;
        }
    }

    $sql = "SELECT `dim-farm`.`dbID` AS FMID ,`dim-user`.`FullName`,`dim-farm`.`Name` as NameFarm ,
      `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`, `log-farm`.`NumSubFarm`,`log-farm`.`Latitude`,
      `log-farm`.`Longitude`,`log-farm`.`NumTree`,`dim-address`.`Distrinct`,`dim-address`.`Province`,
       0  as VolHarvest
    FROM `log-farm`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMfarmID` 
    INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
    INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
    WHERE  `log-farm`.`ID` IN
    (SELECT MAX(`log-farm`.`ID`)  as ID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMfarmID` 
    WHERE `log-farm`.`DIMSubfID` IS NULL
    GROUP BY `dim-farm`.`dbID` ) ";
    if ($idformal != '') $sql .= " AND `dim-user`.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";
    $sql .= " ORDER BY `dim-user`.`FullName`";
    $INFOFARM = selectData($sql);
    $FarmHarvest = null;
    for ($i = 1; $i < count($INFOFARM); $i++) {
        $FarmHarvest[$INFOFARM[$i]['FMID']]['FMID'] = $INFOFARM[$i]['FMID'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['FullName'] = $INFOFARM[$i]['FullName'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['NameFarm'] = $INFOFARM[$i]['NameFarm'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['NumSubFarm'] = $INFOFARM[$i]['NumSubFarm'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['AreaRai'] = $INFOFARM[$i]['AreaRai'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['AreaNgan'] = $INFOFARM[$i]['AreaNgan'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['NumTree'] = $INFOFARM[$i]['NumTree'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['Latitude'] = $INFOFARM[$i]['Latitude'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['Longitude'] = $INFOFARM[$i]['Longitude'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['Distrinct'] = $INFOFARM[$i]['Distrinct'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['Province'] = $INFOFARM[$i]['Province'];
        $FarmHarvest[$INFOFARM[$i]['FMID']]['VolHarvest'] = 0;
    }
    $sql = "SELECT `dim-farm`.`dbID` AS FMID ,SUM(`log-harvest`.`Weight`) as Weight  FROM `log-harvest` 
    INNER JOIN   `dim-time` ON `dim-time`.`ID`=`log-harvest`.`DIMdateID`
    INNER JOIN `dim-farm`  ON   `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
    WHERE  `log-harvest`.`isDelete`=0 AND `dim-time`.`Year2`=" . (date("Y") + 543) . "
    GROUP BY  `dim-farm`.`dbID`";
    $VOLHarvest = selectData($sql);
    for ($i = 1; $i < count($VOLHarvest); $i++) {
        $FarmHarvest[$VOLHarvest[$i]['FMID']]['VolHarvest'] += round($VOLHarvest[$i]['Weight'], 2);
    }
    return  $FarmHarvest;
}

//  start - หน้าการจัดการศีตรูพืช
//INSECTLIST.php
function getCountInsect()
{
    $sql = "SELECT COUNT(*) AS countInsect FROM `db-pestlist` WHERE `PTID` = 1";
    $countInsect = selectData($sql)[1]['countInsect'];
    return $countInsect;
}

function getInsect()
{
    $sql = "SELECT `PID`,`Alias`,`Icon` FROM `db-pestlist` WHERE `PTID` = 1";
    $ALLINSECT = selectData($sql);
    $countInsect = count($ALLINSECT);
    $num = 0;
    for ($i = 0; $i < $countInsect; $i++) {
        $DATA[$num] = $ALLINSECT[$i];
        $num++;
    }

    if (isset($_GET['id'])) $selectedID = $_GET['id'];
    else if ($num > 0) $selectedID = $DATA[1]["PID"];
    else $selectedID = 0;

    $sql = "SELECT * FROM `db-pestlist` WHERE `PTID`=1 AND PID=" . $selectedID;
    $INFO = selectData($sql);

    $INSECT['info'] = $INFO;
    $INSECT['data'] = $DATA;
    $INSECT['selectedID'] = $selectedID;

    return $INSECT;
}

//DISESASESLIST.php
function getCountDiseases()
{
    $sql = "SELECT COUNT(*) AS countDiseases FROM `db-pestlist` WHERE `PTID` = 2";
    $countDiseases = selectData($sql)[1]['countDiseases'];
    return $countDiseases;
}

function getDiseases()
{
    $sql = "SELECT `PID`,`Alias`,`Icon` FROM `db-pestlist` WHERE `PTID` = 2";
    $ALLDISESASES = selectData($sql);
    $countDiseases = count($ALLDISESASES);
    $num = 0;
    for ($i = 0; $i < $countDiseases; $i++) {
        $DATA[$num] = $ALLDISESASES[$i];
        $num++;
    }

    if (isset($_GET['id'])) $selectedID = $_GET['id'];
    else if ($num > 0) $selectedID = $DATA[1]["PID"];
    else $selectedID = 0;

    $sql = "SELECT * FROM `db-pestlist` WHERE `PTID`=2 AND PID=" . $selectedID;
    $INFO = selectData($sql);

    $DISESASES['info'] = $INFO;
    $DISESASES['data'] = $DATA;
    $DISESASES['selectedID'] = $selectedID;

    return $DISESASES;
}

//WEEDLIST.php
function getCountWeed()
{
    $sql = "SELECT COUNT(*) AS countWeed FROM `db-pestlist` WHERE `PTID` = 3";
    $countWeed = selectData($sql)[1]['countWeed'];
    return $countWeed;
}

function getWeed()
{
    $sql = "SELECT `PID`,`Alias`,`Icon` FROM `db-pestlist` WHERE `PTID` = 3";
    $ALLWEED = selectData($sql);
    $countWeed = count($ALLWEED);
    $num = 0;
    for ($i = 0; $i < $countWeed; $i++) {
        $DATA[$num] = $ALLWEED[$i];
        $num++;
    }

    if (isset($_GET['id'])) $selectedID = $_GET['id'];
    else if ($num > 0) $selectedID = $DATA[1]["PID"];
    else $selectedID = 0;

    $sql = "SELECT * FROM `db-pestlist` WHERE `PTID`=3 AND PID=" . $selectedID;
    $INFO = selectData($sql);

    $WEED['info'] = $INFO;
    $WEED['data'] = $DATA;
    $WEED['selectedID'] = $selectedID;

    return $WEED;
}

//OTHER-PESTLIST.php
function getCountOtherPest()
{
    $sql = "SELECT COUNT(*) AS countOhterPest FROM `db-pestlist` WHERE `PTID` = 4";
    $countOhterPest = selectData($sql)[1]['countOhterPest'];
    return $countOhterPest;
}

function getOtherPest()
{
    $sql = "SELECT `PID`,`Alias`,`Icon` FROM `db-pestlist` WHERE `PTID` = 4";
    $ALLOTHER = selectData($sql);
    $countOther = count($ALLOTHER);
    $num = 0;
    for ($i = 0; $i < $countOther; $i++) {
        $DATA[$num] = $ALLOTHER[$i];
        $num++;
    }

    if (isset($_GET['id'])) $selectedID = $_GET['id'];
    else if ($num > 0) $selectedID = $DATA[1]["PID"];
    else $selectedID = 0;

    $sql = "SELECT * FROM `db-pestlist` WHERE `PTID`=4 AND PID=" . $selectedID;
    $INFO = selectData($sql);

    $OTHERPEST['info'] = $INFO;
    $OTHERPEST['data'] = $DATA;
    $OTHERPEST['selectedID'] = $selectedID;

    return $OTHERPEST;
}

//manage.php
function getImgPest($img)
{
    if ($img != null) {
        $data = $img;
        $img_array = explode(';', $data);
        $img_array2 = explode(",", $img_array[1]);
        $dataI = base64_decode($img_array2[1]);
        return $dataI;
    } else return null;
}

function check_dup_name_picture($folder, $namePic)
{
    $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
    foreach ($objScan as $obj) {
        $type = strrchr($obj, ".");
        if ($type == '.png' || $type == '.jpg') {
            if ($obj == $namePic) {
                return true;
            }
        }
    }
    return false;
}
function check_Pic($folder, $dataPic)
{
    $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
    print_r($objScan);

    $checkPic = array();
    foreach ($objScan as $obj) {
        $type = strrchr($obj, ".");
        if ($type == '.png' || $type == '.jpg') {
            $checkPic[$obj] = 0;
        }
    }

    foreach ($objScan as $obj) {
        $type = strrchr($obj, ".");
        echo 'type =' . $type . '<br>';
        if ($type == '.png' || $type == '.jpg') {
            foreach ($dataPic as $pic) {
                echo 'pic  = ' . $pic . '<br>';
                echo '$folder."/".$obj  = ' . $folder . "/" . $obj . '<br>';
                if ($folder . "/" . $obj == $pic || "," . $folder . "/" . $obj == $pic) {
                    echo '$checkPic[$obj]  = ' . $checkPic[$obj] . '<br>';

                    $checkPic[$obj]++;
                }
            }
        }
    }
    return $checkPic;
}
function del_Pic($folder, $checkPic)
{
    $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
    foreach ($objScan as $obj) {
        $type = strrchr($obj, ".");
        if ($type == '.png' || $type == '.jpg') {
            if ($checkPic[$obj] == 0) {
                echo 'del pho' . $obj;
                unlink($folder . "/" . $obj);
            }
        }
    }
}
function delAllFileInfolder($folder = '')
{
    if (is_dir($folder) && $folder != '') {
        //Get a list of all of the file names in the folder.
        $files = glob($folder . '/*');

        //Loop through the file list.
        foreach ($files as $file) {
            //Make sure that this is a file and not a directory.
            if (is_file($file)) {
                //Use the unlink function to delete the file.
                unlink($file);
            }
        }
    }
}


function last_idPest()
{
    $sql = "SELECT MAX(`PID`)as max FROM `db-pestlist`";
    $myConDB = connectDB();
    $result = $myConDB->prepare($sql);
    $result->execute();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $max =  $row['max'];
    }
    //$max = selectData($sql);
    return $max;
}

function select_dimPest()
{
    $sql = "SELECT * FROM `dim-pest`";

    $DATA = selectData($sql);
    return $DATA;
}
// end - หน้าการจัดการศีตรูพืช 
// update By โสภณ โตใหญ่

//OilPalmAreaList.php
function getAllFarmer()
{
    $sql = "SELECT * FROM `db-farmer` ORDER BY`FirstName` ,`LastName`";
    $data = selectData($sql);
    return $data;
}
function getCountCutBranch()
{
    $sql = "SELECT COUNT(lp.isDelete) AS total FROM `log-activity` AS lp WHERE lp.isDelete = 0";
    $data = selectData($sql)[1]['total'];
    return $data;
}
//PEST
function getCountPestAlarm()
{
    $sql = "SELECT COUNT(lp.isDelete) AS totalPestAlarm FROM `log-pestalarm` AS lp WHERE lp.isDelete = 0";
    $data = selectData($sql)[1]['totalPestAlarm'];
    return $data;
}

function getPestType()
{
    $sql = "SELECT * FROM `db-pesttype`";
    $data = selectData($sql);
    return $data;
}
function getPestById($ptid)
{
    $sql = "SELECT * FROM `db-pestlist` WHERE PTID = '$ptid'";
    $data = selectData($sql);
    return $data;
}
function getPestByModify($typeID, $modify)
{
    $sql = "SELECT * FROM `log-pest` 
    JOIN `dim-pest` ON `log-pest`.`DIMpestID` = `dim-pest`.`ID`
    WHERE '$modify' >= `log-pest`.`StartT` AND ( '$modify' <= `log-pest`.`EndT`
    OR  `log-pest`.`EndT` IS NULL) AND  `log-pest`.`DIMpestID` IS NOT NULL
    AND `dim-pest`.`dbpestTID` = '$typeID'";
    $data = selectData($sql);
    return $data;
}
function getPestLogByDIMpestID($DIMpestID)
{
    $sql = "SELECT * FROM
    (SELECT MAX(`log-icon`.`ID`) AS ID FROM `dim-pest`
    JOIN `log-icon` ON `dim-pest`.`ID` = `log-icon`.`DIMiconID`
    WHERE `dim-pest`.`ID` = '$DIMpestID' AND  `log-icon`.`Type` = 1)AS t1
    JOIN `log-icon` ON `log-icon`.`ID`= t1.ID
    JOIN  `dim-pest` ON `dim-pest`.`ID` = `log-icon`.`DIMiconID`";
    $data = selectData($sql);
    return $data;
}
function getPestByPID($pid)
{
    $sql = "SELECT * FROM `db-pestlist` WHERE PID = '$pid'";
    $data = selectData($sql);
    return $data;
}
function getPestByTID($ptid)
{
    $sql = "SELECT * FROM `db-pestlist` WHERE PTID = '$ptid'";
    $data = selectData($sql);
    return $data;
}
function getPestLogByPID($dpid)
{
    $sql = "SELECT * FROM (
        SELECT `log-pest`.`DIMpestID`,MAX(`log-pest`.`ID`) FROM `dim-pest`
        JOIN `log-pest` ON `log-pest`.`DIMpestID` =`dim-pest`.`ID`
        WHERE `dim-pest`.`ID` = $dpid)AS t1
        JOIN `dim-pest` ON `dim-pest`.`ID` =  t1.`DIMpestID`";
    $data1 = selectData($sql);
    $DIMiconID = $data1[1]['DIMpestID'];

    $sql = "SELECT `log-icon`.`FileName` AS Icon FROM (
        SELECT `log-icon`.`ID`,MAX(`log-icon`.`ID`) FROM `log-icon`
        WHERE  `log-icon`.`DIMiconID` = $DIMiconID AND Type = 1)AS t1
        JOIN `log-icon` ON `log-icon`.`ID` =  t1.`ID`";
    $data2 = selectData($sql);

    $data1[1]['Icon'] = $data2[1]['Icon'];

    return $data1;
}

function getPest(&$idformal, &$fullname, &$fpro, &$fdist, &$fyear, &$ftype,$start,$limit,$latitude,$longitude)
{
    $sql = "SELECT * FROM (
        SELECT * FROM (
        SELECT * FROM (
        SELECT * FROM (
        
        SELECT IF(1,1,0) AS check_show,df1.`Name` AS NameFarm_old,df2.`Name` AS NamesubFarm_old,`dim-pest`.`Alias` AS NamePest_old,MAX(`log-farm`.`ID`),`log-pestalarm`.`ID`,`log-pestalarm`.`Modify`,`log-pestalarm`.`DIMdateID`,
            `log-pestalarm`.`DIMownerID`,`dim-user`.`dbID` AS dbID_owner,`log-pestalarm`.`DIMfarmID`,df1.`dbID` AS dbID_farm,`log-pestalarm`.`DIMsubFID`,df2.`dbID` AS dbID_subfarm,`log-pestalarm`.`DIMpestID`,`dim-pest`.`dbpestLID`AS dbID_pest,`log-pestalarm`.`Note`,`log-pestalarm`.`PICs`,  `log-farm`.`Latitude`,
            `log-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`NumTree`,`log-farm`.`AreaRai`,
            `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,`log-farm`.`AreaTotal`,`dim-address`.`dbsubDID`,
            `dim-address`.`dbDistID`,`dim-address`.`dbprovID`,`dim-time`.`Year2`,`dim-time`.`Date`,
            `dim-address`.`Distrinct`,`dim-address`.`Province`
            FROM `log-pestalarm` 
            JOIN `log-farm` ON  `log-pestalarm`.`DIMsubFID` =  `log-farm`.`DIMSubfID`
            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
            JOIN `dim-user` ON `dim-user`.`ID` =  `log-pestalarm`.`DIMownerID`
            
        JOIN `dim-farm` AS df1 ON df1.`ID` = `log-pestalarm`.`DIMfarmID`
        
        JOIN `dim-farm` AS df2 ON df2.`ID` = `log-pestalarm`.`DIMsubFID`
        
        JOIN `dim-pest` ON `dim-pest`.`ID` = `log-pestalarm`.`DIMpestID`
        
            WHERE `log-pestalarm`.`isDelete` = 0
            GROUP BY `dim-address`.`ID`,`log-pestalarm`.`ID`,`log-pestalarm`.`Modify`,`log-pestalarm`.`DIMdateID`,
            `log-pestalarm`.`DIMownerID`,`log-pestalarm`.`DIMfarmID`,`log-pestalarm`.`DIMsubFID`,
            `log-pestalarm`.`DIMpestID`,`log-pestalarm`.`Note`,`log-pestalarm`.`PICs`,  `log-farm`.`Latitude`,
            `log-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`NumTree`,`log-farm`.`AreaRai`,
            `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,`log-farm`.`AreaTotal`,`dim-address`.`dbsubDID`,
            `dim-address`.`dbDistID`,`dim-address`.`dbprovID`,`dim-time`.`Year2`,`dim-time`.`Date`,
            `dim-address`.`Distrinct`,`dim-address`.`Province`  
            ORDER BY `dim-time`.`Date` DESC) AS tb_data
        LEFT JOIN (SELECT `dim-user`.`ID` AS dim_owner, `dim-user`.`dbID` AS dbID_owner_,`dim-user`.`FullName`AS OwnerName,`dim-user`.`FormalID` FROM(
                    SELECT `log-farmer`.`DIMuserID` FROM (
                    SELECT MAX(`log-farmer`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-user`.`ID`,`dim-user`.`dbID`,`dim-user`.`FullName` FROM  (
                    SELECT `dim-user`.`dbID` FROM `dim-user`)AS t1
                    JOIN `dim-user` ON `dim-user`.`dbID` = t1.dbID
                    WHERE `dim-user`.`Type` = 'F' )AS t2
                    JOIN `log-farmer` ON `log-farmer`.`DIMuserID` = t2.ID
                    GROUP BY  t2.dbID) AS t3
                    JOIN `log-farmer` ON `log-farmer`.`ID` = t3.ID) AS t4
                    JOIN  `dim-user` ON  `dim-user`.`ID` = t4.DIMuserID) AS tb_dim_user ON tb_data.dbID_owner = tb_dim_user.dbID_owner_) AS tb_data_dimuser
        LEFT JOIN (SELECT `dim-farm`.`ID` AS dim_farm, `dim-farm`.`dbID` AS FMID,`dim-farm`.`Name` AS Namefarm,t4.EndT AS EndT_farm FROM(
                    SELECT `log-farm`.`DIMfarmID`,`log-farm`.`EndT` FROM (
                    SELECT MAX(`log-farm`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM  (
                    SELECT `dim-farm`.`dbID` FROM `dim-farm`)AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    WHERE `dim-farm`.`IsFarm` = 1)AS t2
                    JOIN `log-farm` ON `log-farm`.`DIMfarmID` = t2.ID
                    GROUP BY t2.dbID) AS t3
                    JOIN `log-farm` ON `log-farm`.`ID` = t3.ID) AS t4
                    JOIN  `dim-farm` ON  `dim-farm`.`ID` = t4.DIMfarmID) AS tb_dim_farm ON tb_data_dimuser.dbID_farm = tb_dim_farm.FMID) AS tb_data_dimuser_dimf
        LEFT JOIN (SELECT `dim-farm`.`ID` AS dim_subfarm, `dim-farm`.`dbID` AS FSID,`dim-farm`.`Name` AS Namesubfarm,t4.EndT AS EndT_sub FROM(
                    SELECT `log-farm`.`DIMSubfID`,`log-farm`.`EndT` FROM (
                    SELECT MAX(`log-farm`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM  (
                    SELECT `dim-farm`.`dbID` FROM `dim-farm`)AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    WHERE `dim-farm`.`IsFarm` = 0)AS t2
                    JOIN `log-farm` ON `log-farm`.`DIMSubfID` = t2.ID
                    GROUP BY  t2.dbID) AS t3
                    JOIN `log-farm` ON `log-farm`.`ID` = t3.ID) AS t4
                    JOIN  `dim-farm` ON  `dim-farm`.`ID` = t4.DIMSubfID)AS tb_dim_subfarm ON tb_data_dimuser_dimf.dbID_subfarm = tb_dim_subfarm.FSID) AS tb_data_dimuser_dimf_dimsf
        LEFT JOIN (SELECT `dim-pest`.`ID` AS dim_pest,`dim-pest`.`dbpestLID`,`dim-pest`.`dbpestTID`,`dim-pest`.`Alias` AS PestAlias,`dim-pest`.`TypeTH` FROM(
                    SELECT `log-pest`.`DIMpestID` FROM (
                    SELECT MAX(`log-pest`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-pest`.`ID`,`dim-pest`.`dbpestLID`,`dim-pest`.`Name` FROM  (
                    SELECT `dim-pest`.`dbpestLID` FROM `dim-pest`)AS t1
                    JOIN `dim-pest` ON `dim-pest`.`dbpestLID` = t1.dbpestLID)AS t2
                    JOIN `log-pest` ON `log-pest`.`DIMpestID` = t2.ID
                    GROUP BY  t2.dbpestLID) AS t3
                    JOIN `log-pest` ON `log-pest`.`ID` = t3.ID) AS t4
                    JOIN  `dim-pest` ON  `dim-pest`.`ID` = t4.DIMpestID)AS tb_dim_pest ON tb_data_dimuser_dimf_dimsf.dbID_pest = tb_dim_pest.dbpestLID 
                    WHERE 1 ";
        if ($fpro    != 0)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.dbprovID = '" . $fpro . "' ";
        if ($fdist   != 0)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.dbDistID = '" . $fdist . "' ";
        if ($fyear   != 0)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.Year2 = '" . $fyear . "' ";

        if ($idformal != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`FormalID` LIKE '%" . $idformal . "%' ";
        if ($fullname != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`OwnerName` LIKE '%" . $fullname . "%' ";

        if ($ftype   != 0)  $sql = $sql . " AND tb_dim_pest.`dbpestTID` = '" . $ftype . "' ";
        if ($latitude != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`Latitude` = '" . $latitude . "' ";
        if ($longitude != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`Longitude` = '" . $longitude . "' ";

        if ($limit != 0) $sql = $sql . " LIMIT ".$start." , ".$limit;

    $LOG = selectData($sql);
    // print_r("sql = ".$sql);

    // print_r("show log ----- ");
    // print_r($LOG);

    // $fp = fopen('pest.json', 'w');
    // // fwrite($fp, "pest = ");
    // // fclose($fp);
    // // $fp = fopen('pest.json', 'a');
    // fwrite($fp, json_encode($LOG));
    // fclose($fp);

    for ($i = 1; $i <= $LOG[0]['numrow']; $i++) {
        if ($LOG[$i]['check_show'] == 1) {
            $time = $LOG[$i]['Date'];
            $time = strtotime($time);
            // function thai_date_short($time){   // 19  ธ.ค. 2556
            global $dayTH, $monthTH_brev;
            $thai_date_return = date("j", $time);
            $strMonth = date("n", $time);
            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $strMonthThai = $strMonthCut[$strMonth];
            $thai_date_return .= " " . $strMonthThai;
            $thai_date_return .= " " . (date("Y", $time) + 543);

            $date =  $thai_date_return;
            // }
            // $lati = str_replace('.', '-', $LOG[$i]["Latitude"]);
            // $longi = str_replace('.', '-', $LOG[$i]["Longitude"]);
            $LOG[$i]['Date'] = $date;
            // $LOG[$i]['Latitude'] = $lati;
            // $LOG[$i]['Longitude'] = $longi;
        }
    }
    return $LOG;
}
function getLogHarvest($fmid,$start,$limit)
{
    $sql = "SELECT `log-harvest`.*, RS1.`Name`,`dim-time`.`dd`,`dim-time`.`Month`,`dim-time`.`Year2` FROM `log-harvest` 
    INNER JOIN `dim-time`  ON `dim-time`.`ID`=`log-harvest`.`DIMdateID`
    INNER JOIN `dim-farm` as dimfarm1 ON dimfarm1.`ID` = `log-harvest`.`DIMfarmID`
    INNER JOIN `dim-farm` as dimfarm2 ON dimfarm2.`ID` = `log-harvest`.`DIMsubFID`
   INNER JOIN (SELECT  `dim-farm`.`dbID`, `dim-farm`.`Name`  FROM `log-farm`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`log-farm`.`DIMSubfID`
    WHERE `log-farm`.`ID` IN 
    (SELECT MAX(`log-farm`.`ID`) as logFarmID FROM `log-farm` 
    INNER JOIN `dim-farm`  as t1 ON  t1.`ID` =`log-farm` .`DIMfarmID` 
    INNER JOIN `dim-farm` as t2  ON t2.`ID` = `log-farm`.`DIMSubfID`
    WHERE  t1.`dbID` = $fmid
    GROUP BY t2.`dbID`)) as RS1 ON RS1.`dbID` = dimfarm2.`dbID`
    WHERE `log-harvest`.`isDelete`=0 AND dimfarm1.`dbID`=$fmid  
    ORDER BY `dim-time`.`Date` DESC";
    if ($limit != 0) $sql = $sql . " LIMIT ".$start." , ".$limit;
    $LogHarvest = selectData($sql);
    return   $LogHarvest;
}
function getFarmByModify($modify)
{
    $sql = "SELECT * , `dim-farm`.`ID`AS DIMFID FROM `log-farm` 
    JOIN `dim-farm` ON `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
    WHERE '$modify' >= `log-farm`.`StartT` AND ( '$modify' <= `log-farm`.`EndT`
    OR  `log-farm`.`EndT` IS NULL) AND  `log-farm`.`DIMSubfID` IS NULL";
    $data = selectData($sql);
    return $data;
}
function getsubFarmByModify2($fmid, $modify)
{
    $sql = "SELECT sf.`ID` AS DIMFSID,sf.`Name` FROM `log-farm` 
    INNER JOIN `dim-farm` as f ON  f.`ID` = `log-farm`.`DIMfarmID`
    INNER JOIN `dim-farm` as sf ON  sf.`ID` = `log-farm`.`DIMSubfID`
    WHERE f.`dbID`=$fmid AND '$modify' >= `log-farm`.`StartT` AND ( '$modify' <= `log-farm`.`EndT`
    OR  `log-farm`.`EndT` IS NULL) AND  `log-farm`.`DIMSubfID` IS NOT NULL  ORDER BY sf.`Name`";
    $data = selectData($sql);
    return $data;
}
function CheckHaveFarm($fmid)
{
    $sql = "SELECT * FROM `db-subfarm` WHERE `db-subfarm`.`FMID` = $fmid";
    $INFO = selectData($sql);
    if ($INFO[0]['numrow'] > 0) {
        return true;
    } else {
        return false;
    }
}
function getNameSubfarm($fmid)
{
    $sql = "SELECT * FROM `db-subfarm` WHERE `db-subfarm`.`FMID` = $fmid ORDER BY `db-subfarm`.`Name`";
    $SUBFARM = selectData($sql);
    return $SUBFARM;
}
function getAreaLogFarm()
{

    $sql = "SELECT 
    SUM(`log-farm`.`AreaRai`) AS AreaRai, SUM(`log-farm`.`AreaNgan`) AS AreaNgan,SUM(`log-farm`.`NumTree`) AS NumTree,
    COUNT(*) AS NumFarm ,  SUM(`log-farm`.`NumSubFarm`) AS NumSubFarm
  FROM `log-farm`
  INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMfarmID` 
  INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
  INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
  WHERE  `log-farm`.`ID` IN
  (SELECT MAX(`log-farm`.`ID`)  as ID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMfarmID` 
  WHERE `log-farm`.`DIMSubfID` IS NULL GROUP BY `dim-farm`.`dbID` ) ";
    $INFOFARM = selectData($sql);
    return $INFOFARM;
}

function getChartPest($year, $fsid)
{
    $datapest = array();
    $ArrName = array("แมลงศัตรูพืช", "โรคพืช", "วัชพืช", "ศัตรูพืชอื่นๆ");
    $labelYear = "[";
    $labelData[1] = "[";
    $labelData[2] = "[";
    $labelData[3] = "[";
    $labelData[4] = "[";
    $Check = false;
    for ($j = 19; $j >= 0; $j--) {

        $thisYear = $year - $j;
        $sql = "SELECT `dim-pest`.`dbpestTID`,COUNT(*) AS num  FROM `dim-pest`
            INNER JOIN `log-pestalarm` ON `log-pestalarm`.`DIMpestID` = `dim-pest`.`dbpestLID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-pestalarm`.`DIMsubFID`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
            WHERE  `log-pestalarm`.`isDelete`=0  AND `dim-time`.`Year2`='$thisYear'  AND `dim-farm`.`dbID`=$fsid
             GROUP BY `dim-pest`.`dbpestTID`";
        $NUM = selectData($sql);
        if ($Check || $NUM[0]['numrow'] > 0) {
            $Check = true;
            $labelYear .= "\"$thisYear\",";
            $num[1] = 0;
            $num[2] = 0;
            $num[3] = 0;
            $num[4] = 0;
            for ($i = 1; $i < count($NUM); $i++) {
                $num[$NUM[$i]['dbpestTID']] = $NUM[$i]['num'];
            }
            $labelData[1] .=  number_format($num[1], 0, '.', ',') . ",";
            $labelData[2] .=  number_format($num[2], 0, '.', ',') . ",";
            $labelData[3] .=  number_format($num[3], 0, '.', ',') . ",";
            $labelData[4] .=  number_format($num[4], 0, '.', ',') . ",";
        }
    }
    if ($Check) {
        $labelYear = substr($labelYear, 0, -1);
        $labelData[1] = substr($labelData[1], 0, -1);
        $labelData[2] = substr($labelData[2], 0, -1);
        $labelData[3] = substr($labelData[3], 0, -1);
        $labelData[4] = substr($labelData[4], 0, -1);
    }
    $labelYear .=  "]";
    $labelData[1] .=  "]";
    $labelData[2] .=  "]";
    $labelData[3] .=  "]";
    $labelData[4] .=  "]";


    $datapest["labelYear"] = $labelYear;
    $datapest["ArrName"] = $ArrName;
    $datapest["labeldata"] = $labelData;
    return $datapest;
}

function getActivity(&$idformal, &$fullname, &$fpro, &$fdist, &$fyear, &$fmin, &$fmax, $DBactID,$start,$limit,$latitude,$longitude)
{
    if ($fmin == 0 && $fmax == 0) {
        $fmin = -1;
        $fmax = -1;
    }
    // echo 'fyear = '.$fyear.'<br>';
    // echo 'ftype = '.$ftype.'<br>';
    // echo 'idformal = '.$idformal.'<br>';
    // echo 'fpro = '.$fpro.'<br>';
    // echo 'fdist = '.$fdist.'<br>';

    $sql = "SELECT * FROM (
        SELECT * FROM (
        SELECT * FROM (
        SELECT * FROM (
                SELECT IF(1,1,0) AS check_show,MAX(t1.Date)AS max_date,COUNT(t1.dbID_farm)AS time,t1.`ID`,t1.`Modify`,t1.`DIMdateID`,
                    t1.`DIMownerID`,t1.`DIMfarmID`,t1.`DIMsubFID`,t1.dbID_farm,t1.dbID_subfarm,t1.NameFarm_old,t1.NamesubFarm_old,t1.dbID_owner,
                    t1.`Note`,t1.`PICs`,  t1.`Latitude`,
                    t1.`Longitude`,t1.`NumSubFarm`,t1.`NumTree`,t1.`AreaRai`,
                    t1.`AreaNgan`,t1.`AreaWa`,t1.`AreaTotal`,t1.`dbsubDID`,
                    t1.`dbDistID`,t1.`dbprovID`,t1.`Year2`,t1.`Date`,
                    t1.`Distrinct`,t1.`Province` FROM (
                    SELECT MAX(`log-farm`.`ID`)AS LFID,df1.`dbID` AS dbID_farm,df2.`dbID` AS dbID_subfarm,df1.`Name` AS NameFarm_old,df2.`Name` AS NamesubFarm_old,`log-activity`.`ID`,`log-activity`.`Modify`,`log-activity`.`DIMdateID`,`dim-user`.`dbID` AS dbID_owner,
                    `log-activity`.`DIMownerID`,`log-activity`.`DIMfarmID`,`log-activity`.`DIMsubFID`,
                    `log-activity`.`Note`,`log-activity`.`PICs`,  `log-farm`.`Latitude`,
                    `log-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`NumTree`,`log-farm`.`AreaRai`,
                    `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,`log-farm`.`AreaTotal`,`dim-address`.`dbsubDID`,
                    `dim-address`.`dbDistID`,`dim-address`.`dbprovID`,`dim-time`.`Year2`,`dim-time`.`Date`,
                    `dim-address`.`Distrinct`,`dim-address`.`Province`
                    FROM `log-activity` 
                    JOIN `log-farm` ON  `log-activity`.`DIMsubFID` =  `log-farm`.`DIMSubfID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                    JOIN `dim-user` ON `dim-user`.`ID` =  `log-activity`.`DIMownerID`
            
                    JOIN `dim-farm` AS df1 ON df1.`ID` = `log-activity`.`DIMfarmID`
        
                    JOIN `dim-farm` AS df2 ON df2.`ID` = `log-activity`.`DIMsubFID`
                    WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = '$DBactID' 
                        GROUP BY `dim-address`.`ID`,`log-activity`.`ID`,`log-activity`.`Modify`,`log-activity`.`DIMdateID`,
                    `log-activity`.`DIMownerID`,`log-activity`.`DIMfarmID`,`log-activity`.`DIMsubFID`,
                    `log-activity`.`Note`,`log-activity`.`PICs`,  `log-farm`.`Latitude`,
                    `log-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`NumTree`,`log-farm`.`AreaRai`,
                    `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,`log-farm`.`AreaTotal`,`dim-address`.`dbsubDID`,
                    `dim-address`.`dbDistID`,`dim-address`.`dbprovID`,`dim-time`.`Year2`,`dim-time`.`Date`,
                    `dim-address`.`Distrinct`,`dim-address`.`Province`
                    ORDER BY `log-activity`.`ID` ASC)AS t1 GROUP BY t1.dbID_farm ORDER BY `max_date` DESC)AS tb_data
        LEFT JOIN (SELECT `dim-user`.`ID` AS dim_owner, `dim-user`.`dbID` AS dbID_owner_,`dim-user`.`FullName`AS OwnerName,`dim-user`.`FormalID` FROM(
                    SELECT `log-farmer`.`DIMuserID` FROM (
                    SELECT MAX(`log-farmer`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-user`.`ID`,`dim-user`.`dbID`,`dim-user`.`FullName` FROM  (
                    SELECT `dim-user`.`dbID` FROM `dim-user`)AS t1
                    JOIN `dim-user` ON `dim-user`.`dbID` = t1.dbID
                    WHERE `dim-user`.`Type` = 'F' )AS t2
                    JOIN `log-farmer` ON `log-farmer`.`DIMuserID` = t2.ID
                    GROUP BY  t2.dbID) AS t3
                    JOIN `log-farmer` ON `log-farmer`.`ID` = t3.ID) AS t4
                    JOIN  `dim-user` ON  `dim-user`.`ID` = t4.DIMuserID) AS tb_dim_user ON tb_data.dbID_owner = tb_dim_user.dbID_owner_) AS tb_data_dimuser
        LEFT JOIN (SELECT `dim-farm`.`ID` AS dim_farm, `dim-farm`.`dbID` AS FMID,`dim-farm`.`Name` AS Namefarm,t4.EndT AS EndT_farm FROM(
                    SELECT `log-farm`.`DIMfarmID`,`log-farm`.`EndT` FROM (
                    SELECT MAX(`log-farm`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM  (
                    SELECT `dim-farm`.`dbID` FROM `dim-farm`)AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    WHERE `dim-farm`.`IsFarm` = 1)AS t2
                    JOIN `log-farm` ON `log-farm`.`DIMfarmID` = t2.ID
                    GROUP BY t2.dbID) AS t3
                    JOIN `log-farm` ON `log-farm`.`ID` = t3.ID) AS t4
                    JOIN  `dim-farm` ON  `dim-farm`.`ID` = t4.DIMfarmID) AS tb_dim_farm ON tb_data_dimuser.dbID_farm = tb_dim_farm.FMID) AS tb_data_dimuser_dimf
        LEFT JOIN (SELECT `dim-farm`.`ID` AS dim_subfarm, `dim-farm`.`dbID` AS FSID,`dim-farm`.`Name` AS Namesubfarm,t4.EndT AS EndT_sub FROM(
                    SELECT `log-farm`.`DIMSubfID`,`log-farm`.`EndT` FROM (
                    SELECT MAX(`log-farm`.`ID`)AS ID FROM (
                    SELECT DISTINCT `dim-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM  (
                    SELECT `dim-farm`.`dbID` FROM `dim-farm`)AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    WHERE `dim-farm`.`IsFarm` = 0)AS t2
                    JOIN `log-farm` ON `log-farm`.`DIMSubfID` = t2.ID
                    GROUP BY  t2.dbID) AS t3
                    JOIN `log-farm` ON `log-farm`.`ID` = t3.ID) AS t4
                    JOIN  `dim-farm` ON  `dim-farm`.`ID` = t4.DIMSubfID)AS tb_dim_subfarm ON tb_data_dimuser_dimf.dbID_subfarm = tb_dim_subfarm.FSID) AS tb_data_dimuser_dimf_dimsf
            WHERE 1 ";
    if ($fyear   != 0)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`Year2` = '$fyear' ";
    if ($fpro    != 0)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.dbDistID = '" . $fdist . "' ";
    if ($fmin != -1 && $fmax != -1)  $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.time >= '" . $fmin . "' AND tb_data_dimuser_dimf_dimsf.time <= '$fmax'";
    if ($idformal != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`OwnerName` LIKE '%" . $fullname . "%' ";
    if ($latitude != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`Latitude` = '" . $latitude . "' ";
    if ($longitude != '') $sql = $sql . " AND tb_data_dimuser_dimf_dimsf.`Longitude` = '" . $longitude . "' ";
    if ($limit != 0) $sql = $sql . " LIMIT ".$start." , ".$limit;

    $LOG = selectData($sql);

    for ($i = 1; $i <= $LOG[0]['numrow']; $i++) {
        if ($LOG[$i]['check_show'] == 1) {
            $time = $LOG[$i]['Date'];
            $time = strtotime($time);
            // function thai_date_short($time){   // 19  ธ.ค. 2556
            global $dayTH, $monthTH_brev;
            $thai_date_return = date("j", $time);
            $strMonth = date("n", $time);
            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $strMonthThai = $strMonthCut[$strMonth];
            $thai_date_return .= " " . $strMonthThai;
            $thai_date_return .= " " . (date("Y", $time) + 543);

            $date =  $thai_date_return;
            // }
            // $lati = str_replace('.', '-', $LOG[$i]["Latitude"]);
            // $longi = str_replace('.', '-', $LOG[$i]["Longitude"]);
            $LOG[$i]['Date'] = $date;
            // $LOG[$i]['Latitude'] = $lati;
            // $LOG[$i]['Longitude'] = $longi;
        }
    }
    // print_r($LOG);
    return $LOG;
}
function getActivityDetail($farmID, $DBactID)
{
    $sql = "SELECT MAX(`log-farm`.`ID`)AS LFID,`dim-farm`.`dbID`,`log-activity`.`ID`,`log-activity`.`Modify`,`log-activity`.`DIMdateID`,
    `log-activity`.`DIMownerID`,`log-activity`.`DIMfarmID`,`log-activity`.`DIMsubFID`,
    `log-activity`.`Note`,`log-activity`.`PICs`,  `log-farm`.`Latitude`,
    `log-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`NumTree`,`log-farm`.`AreaRai`,
    `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,`log-farm`.`AreaTotal`,`dim-address`.`dbsubDID`,
    `dim-address`.`dbDistID`,`dim-address`.`dbprovID`,`dim-time`.`Year2`,`dim-time`.`Date`,
    `dim-address`.`Distrinct`,`dim-address`.`Province`
    FROM `log-activity` 
    JOIN `log-farm` ON  `log-activity`.`DIMsubFID` =  `log-farm`.`DIMSubfID`
    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
   JOIN  `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    WHERE `log-activity`.`isDelete` = 0 AND `dim-farm`.`dbID`= '$farmID' AND `log-activity`.`DBactID` = '$DBactID'
    GROUP BY `dim-address`.`ID`,`log-activity`.`ID`,`log-activity`.`Modify`,`log-activity`.`DIMdateID`,
    `log-activity`.`DIMownerID`,`log-activity`.`DIMfarmID`,`log-activity`.`DIMsubFID`,
    `log-activity`.`Note`,`log-activity`.`PICs`,  `log-farm`.`Latitude`,
    `log-farm`.`Longitude`,`log-farm`.`NumSubFarm`,`log-farm`.`NumTree`,`log-farm`.`AreaRai`,
    `log-farm`.`AreaNgan`,`log-farm`.`AreaWa`,`log-farm`.`AreaTotal`,`dim-address`.`dbsubDID`,
    `dim-address`.`dbDistID`,`dim-address`.`dbprovID`,`dim-time`.`Year2`,`dim-time`.`Date`,
    `dim-address`.`Distrinct`,`dim-address`.`Province`  
    ORDER BY `dim-time`.`Date` DESC";

    $LOG = selectData($sql);
    $LOGFARM = getDetailLogFarm($farmID);
    $LOG[0]['OwnerName'] = $LOGFARM[1]['FullName'];
    $LOG[0]['Namefarm'] = $LOGFARM[1]['NameFarm'];

    $LOG[0]['IconOwner'] = $LOGFARM[1]['iconFarmmer'];
    $LOG[0]['IconFarm'] = $LOGFARM[1]['iconFarm'];

    $LOG[0]['UFID'] = $LOGFARM[1]['UFID'];
    $LOG[0]['FMID'] = $LOGFARM[1]['FMID'];

    $LOG[0]['DIMfarmID'] = $LOGFARM[1]['DIMfarmID'];

    for ($i = 1; $i <= $LOG[0]['numrow']; $i++) {
        $LOG[$i]['check_show'] = 1;
        $dim_user = $LOG[$i]['DIMownerID'];
        $dim_farm = $LOG[$i]['DIMfarmID'];
        $dim_subfarm = $LOG[$i]['DIMsubFID'];

        $sql = "SELECT `dim-farm`.`Name` FROM `dim-farm` WHERE `dim-farm`.`ID` = '$dim_farm'";
        $LOG[$i]['NameFarm_old'] = selectData($sql)[1]['Name'];

        $sql = "SELECT `dim-farm`.`Name` FROM `dim-farm` WHERE `dim-farm`.`ID` = '$dim_subfarm'";
        $LOG[$i]['NamesubFarm_old'] = selectData($sql)[1]['Name'];

        $sql = "SELECT `dim-user`.`ID`, `dim-user`.`FullName`,`dim-user`.`FormalID` FROM(
            SELECT `log-farmer`.`DIMuserID` FROM (
            SELECT MAX(`log-farmer`.`ID`)AS ID FROM (
            SELECT `dim-user`.`ID`,`dim-user`.`dbID`,`dim-user`.`FullName` FROM  (
            SELECT `dim-user`.`dbID` FROM `dim-user`
            WHERE `dim-user`.`ID` = '$dim_user')AS t1
            JOIN `dim-user` ON `dim-user`.`dbID` = t1.dbID
            WHERE `dim-user`.`Type` = 'F' )AS t2
            JOIN `log-farmer` ON `log-farmer`.`DIMuserID` = t2.ID) AS t3
            JOIN `log-farmer` ON `log-farmer`.`ID` = t3.ID) AS t4
            JOIN  `dim-user` ON  `dim-user`.`ID` = t4.DIMuserID";

        $DATA = selectData($sql);
        if ($DATA[0]['numrow'] == 0) {
            $LOG[$i]['check_show'] = 0;
        } else {
            $LOG[$i]['dim_owner'] = $DATA[1]['ID'];
            $LOG[$i]['OwnerName'] = $DATA[1]['FullName'];
        }


        $sql = "SELECT `dim-farm`.`ID`, `dim-farm`.`dbID`,`dim-farm`.`Name`,t4.EndT FROM(
            SELECT `log-farm`.`DIMfarmID`,`log-farm`.`EndT` FROM (
            SELECT MAX(`log-farm`.`ID`)AS ID FROM (
            SELECT `dim-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM  (
            SELECT `dim-farm`.`dbID` FROM `dim-farm`
            WHERE `dim-farm`.`ID` = '$dim_farm')AS t1
            JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
            WHERE `dim-farm`.`IsFarm` = 1)AS t2
            JOIN `log-farm` ON `log-farm`.`DIMfarmID` = t2.ID) AS t3
            JOIN `log-farm` ON `log-farm`.`ID` = t3.ID) AS t4
            JOIN  `dim-farm` ON  `dim-farm`.`ID` = t4.DIMfarmID";

        $DATA = selectData($sql);
        if ($DATA[0]['numrow'] == 0) {
            $LOG[$i]['check_show'] = 0;
        } else {
            $LOG[$i]['dim_farm'] = $DATA[1]['ID'];
            $LOG[$i]['FMID'] = $DATA[1]['dbID'];
            $LOG[$i]['EndT_farm'] = $DATA[1]['EndT'];
            $LOG[$i]['Namefarm'] = $DATA[1]['Name'];
        }


        $sql = "SELECT `dim-farm`.`ID`, `dim-farm`.`dbID`,`dim-farm`.`Name`,t4.EndT FROM(
            SELECT `log-farm`.`DIMSubfID`,`log-farm`.`EndT` FROM (
            SELECT MAX(`log-farm`.`ID`)AS ID FROM (
            SELECT `dim-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM  (
            SELECT `dim-farm`.`dbID` FROM `dim-farm`
            WHERE `dim-farm`.`ID` = '$dim_subfarm')AS t1
            JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
            WHERE `dim-farm`.`IsFarm` = 0)AS t2
            JOIN `log-farm` ON `log-farm`.`DIMSubfID` = t2.ID) AS t3
            JOIN `log-farm` ON `log-farm`.`ID` = t3.ID) AS t4
            JOIN  `dim-farm` ON  `dim-farm`.`ID` = t4.DIMSubfID";

        $DATA = selectData($sql);
        if ($DATA[0]['numrow'] == 0) {
            $LOG[$i]['check_show'] = 0;
        } else {
            $LOG[$i]['dim_subfarm'] = $DATA[1]['ID'];
            $LOG[$i]['FSID'] = $DATA[1]['dbID'];
            $LOG[$i]['EndT_sub'] = $DATA[1]['EndT'];
            $LOG[$i]['Namesubfarm'] = $DATA[1]['Name'];
        }
    }
    // echo "size = ".$LOG[0]['numrow']."<br>";
    // print_r($LOG[0]);
    // echo "<br>--------------------------------------------------<br>";
    // print_r($LOG[1]);
    // echo "<br>--------------------------------------------------<br>";
    // print_r($LOG[2]);
    // echo "<br>--------------------------------------------------<br>";
    // print_r($LOG[3]);

    return $LOG;
}
function getTableAllRain(&$year, &$idformal, &$fullname, &$fpro, &$fdist, &$score_From, &$score_To)
{
    $score_From = 0;
    $score_To = 0;
    $idformal = '';
    $fpro = 0;
    $fdist = 0;
    $fullname = '';
    if (isset($_POST['score_From']))  $score_From = $_POST['score_From'];
    if (isset($_POST['score_To']))  $score_To = $_POST['score_To'];
    if (isset($_POST['s_formalid']))  $idformal = rtrim($_POST['s_formalid']);
    if (isset($_POST['year']))  $year = $_POST['year'];
    if (isset($_POST['s_province']))  $fpro = $_POST['s_province'];
    if (isset($_POST['s_distrinct'])) $fdist = $_POST['s_distrinct'];
    if (isset($_POST['s_name'])) {
        $fullname = rtrim($_POST['s_name']);
        $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
        $namef = explode(" ", $fullname);
        if (isset($namef[1])) {
            $fnamef = $namef[0];
            $lnamef = $namef[1];
        } else {
            $fnamef = $fullname;
            $lnamef = $fullname;
        }
    }
    $sql = "SELECT  sf.`dbID` AS FSID ,f.`dbID` AS FMID ,`dim-user`.`FullName`,f.`Name` as NameFarm ,sf.`Name` as NameSubfarm ,
            `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`,`log-farm`.`Latitude`,
            `log-farm`.`Longitude`,`log-farm`.`NumTree`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
            INNER JOIN `dim-farm`as f ON f.`ID` =`log-farm`.`DIMfarmID` 
            INNER JOIN `dim-farm` as sf ON sf.`ID` =`log-farm`.`DIMSubfID` 
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
            INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE  `log-farm`.`ID` IN
            (SELECT MAX(`log-farm`.`ID`)  as LogID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMSubfID` 
            WHERE `log-farm`.`DIMSubfID` IS NOT NULL ";
    if ($idformal != '') $sql .= " AND `dim-user`.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";

    $sql .= " GROUP BY `dim-farm`.`dbID`) ORDER BY `dim-user`.`FullName`,f.`Name`  ,sf.`Name`";
    $INFOSUBFARM =  selectData($sql);
    $INFOSUBFARMRAIN = array();
    if ($INFOSUBFARM[0]['numrow'] == 0) {
        $INFOSUBFARMRAIN = null;
    }
    if ($score_From == 0 && $score_To == 0) {
        $checkscore = true;
    } else {
        $checkscore = false;
    }
    $currentYear = date("Y") + 543;
    for ($i = 1; $i < count($INFOSUBFARM); $i++) {
        if (checkrangDrying($INFOSUBFARM[$i]['FSID'], $year, $score_From, $score_To) || $checkscore || $currentYear == $year) {
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['FMID'] = $INFOSUBFARM[$i]['FMID'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['FSID'] = $INFOSUBFARM[$i]['FSID'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['FullName'] = $INFOSUBFARM[$i]['FullName'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['NameFarm'] = $INFOSUBFARM[$i]['NameFarm'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['NameSubfarm'] = $INFOSUBFARM[$i]['NameSubfarm'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['AreaRai'] = $INFOSUBFARM[$i]['AreaRai'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['AreaNgan'] = $INFOSUBFARM[$i]['AreaNgan'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['Latitude'] = $INFOSUBFARM[$i]['Latitude'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['Longitude'] = $INFOSUBFARM[$i]['Longitude'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['NumTree'] = $INFOSUBFARM[$i]['NumTree'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['Distrinct'] = $INFOSUBFARM[$i]['Distrinct'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['Province'] = $INFOSUBFARM[$i]['Province'];
            $INFORAIN = getInfoRain($INFOSUBFARM[$i]['FSID'], $year);
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['rainDay'] = $INFORAIN['rainDay'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['notrainDay'] = $INFORAIN['notrainDay'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['totalVol'] = $INFORAIN['totalVol'];
            $INFOSUBFARMRAIN[$INFOSUBFARM[$i]['FSID']]['longDay'] = $INFORAIN['longDay'];
        }
    }
    return $INFOSUBFARMRAIN;
}
function checkrangDrying($fsid, $year, $start, $end)
{
    $sql = "SELECT `dim-farm`.`dbID` FROM `fact-drying` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
    INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
    INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
    WHERE `dim-farm`.`dbID` = $fsid AND STARTDATE.`Year2` = '$year' AND `fact-drying`.`Period`>=$start AND `fact-drying`.`Period`<=$end";
    $DATA = selectData($sql);
    if ($DATA[0]['numrow'] > 0) {
        return true;
    } else {
        $currentYear = date("Y") + 543;
        if ($currentYear == $year) {
            $sql = "SELECT `dim-farm`.`dbID` FROM `fact-drying` 
                INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                WHERE `dim-farm`.`dbID` = $fsid AND STARTDATE.`Year2` = '$year' AND  ENDDATE.`Date` IS NULL ";
            $DATA = selectData($sql);
            if ($DATA[0]['numrow'] > 0) {
                $p = date_diff(date_create($DATA[1]['StartTime']), date_create(date("Y-m-d")))->format("%a");
                if ($p >= $start && $p <= $end) {
                    return true;
                }
            } else {
                $p = date("z");
                if ($p >= $start && $p <= $end) {
                    return true;
                }
            }
        }

        return false;
    }
}
function getInfoRain($fsid, $year)
{
    $DATA = array();
    $sql = "SELECT COUNT(DISTINCT `log-raining`.`DIMdateID`) AS rainDay,IFNULL(ROUND(SUM(`log-raining`.`Vol`),2),0) AS totalVol  FROM `log-raining` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-raining`.`DIMsubFID`
    INNER JOIN `dim-time`  ON `dim-time`.`ID` =  `log-raining`.`DIMdateID`
    WHERE `dim-farm`.`dbID` = $fsid AND `dim-time`.`Year2`= $year AND `log-raining`.`isDelete` =0";
    $thisYear = date("Y") + 543;
    $INFO = selectData($sql);
    $DATA['rainDay'] = $INFO[1]['rainDay'];
    $DATA['totalVol'] = $INFO[1]['totalVol'];
    if ($year != $thisYear) {
        if (((int) ($year + 1)) % 4 == 0) {
            $DATA['notrainDay'] = 366 - $DATA['rainDay'];
        } else {
            $DATA['notrainDay'] = 365 - $DATA['rainDay'];
        }
    } else {
        $DATA['notrainDay'] = (date("z") + 1) - $DATA['rainDay'];
    }
    $sql = "SELECT DISTINCT `dim-time`.`Date` FROM `log-raining` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-raining`.`DIMsubFID`
    INNER JOIN `dim-time`  ON `dim-time`.`ID` =  `log-raining`.`DIMdateID`
    WHERE `dim-farm`.`dbID` = $fsid AND `dim-time`.`Year2`= $year AND `log-raining`.`isDelete` =0
    ORDER BY  `dim-time`.`Date`";
    $INFO = selectData($sql);
    if ($INFO[0]['numrow'] > 0) {
        $daystart = 0;
        $dayend = date("z", strtotime($INFO[1]['Date']));
        $longDay = $dayend - $daystart;
        $daystart = date("z", strtotime($INFO[1]['Date']));
        for ($i = 2; $i < count($INFO); $i++) {
            $dayend = date("z", strtotime($INFO[$i]['Date']));
            if ($longDay < $dayend - $daystart) {
                $longDay = $dayend - $daystart;
            }
            $daystart = date("z", strtotime($INFO[$i]['Date']));
        }
        if ($year != $thisYear) {
            if (((int) ($year + 1)) % 4 == 0) {
                $dayend = 365;
                if ($longDay < $dayend - $daystart) {
                    $longDay = $dayend - $daystart;
                }
            } else {
                $dayend = 364;
                if ($longDay < $dayend - $daystart) {
                    $longDay = $dayend - $daystart;
                }
            }
        } else {
            $dayend = date("z");
            if ($longDay < $dayend - $daystart) {
                $longDay = $dayend - $daystart;
            }
        }
    } else {
        $longDay = date("z");
    }
    $DATA['longDay'] = $longDay + 1;
    return $DATA;
}
function getTableAllWater(&$year, &$idformal, &$fullname, &$fpro, &$fdist, &$score_From, &$score_To)
{
    $idformal = '';
    $fpro = 0;
    $fdist = 0;
    $fullname = '';
    $score_From = 0;
    $score_To = 0;
    if (isset($_POST['score_From']))  $score_From = $_POST['score_From'];
    if (isset($_POST['score_To']))  $score_To = $_POST['score_To'];
    if (isset($_POST['s_formalid']))  $idformal = rtrim($_POST['s_formalid']);
    if (isset($_POST['year']))  $year = $_POST['year'];
    if (isset($_POST['s_province']))  $fpro = $_POST['s_province'];
    if (isset($_POST['s_distrinct'])) $fdist = $_POST['s_distrinct'];
    if (isset($_POST['s_name'])) {
        $fullname = rtrim($_POST['s_name']);
        $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
        $namef = explode(" ", $fullname);
        if (isset($namef[1])) {
            $fnamef = $namef[0];
            $lnamef = $namef[1];
        } else {
            $fnamef = $fullname;
            $lnamef = $fullname;
        }
    }
    $sql = "SELECT  sf.`dbID` AS FSID ,f.`dbID` AS FMID,`dim-user`.`FullName`,f.`Name` as NameFarm ,sf.`Name` as NameSubfarm ,
            `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`,`log-farm`.`Latitude`,
            `log-farm`.`Longitude`,`log-farm`.`NumTree`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
            INNER JOIN `dim-farm`as f ON f.`ID` =`log-farm`.`DIMfarmID` 
            INNER JOIN `dim-farm` as sf ON sf.`ID` =`log-farm`.`DIMSubfID` 
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
            INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE  `log-farm`.`ID` IN
            (SELECT MAX(`log-farm`.`ID`)  as LogID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMSubfID` 
            WHERE `log-farm`.`DIMSubfID` IS NOT NULL";
    if ($idformal != '') $sql .= " AND `dim-user`.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";

    $sql .= " GROUP BY `dim-farm`.`dbID`) ORDER BY `dim-user`.`FullName`,f.`Name`  ,sf.`Name`";
    $INFOSUBFARM =  selectData($sql);
    $INFOSUBFARMWATER = array();
    if ($INFOSUBFARM[0]['numrow'] == 0) {
        $INFOSUBFARMWATER = null;
    }
    if ($score_From == 0 && $score_To == 0) {
        $checkscore = true;
    } else {
        $checkscore = false;
    }
    $currentYear = date("Y") + 543;
    for ($i = 1; $i < count($INFOSUBFARM); $i++) {
        if (checkrangDrying($INFOSUBFARM[$i]['FSID'], $year, $score_From, $score_To) ||  $checkscore || $currentYear == $year) {
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['FMID'] = $INFOSUBFARM[$i]['FMID'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['FSID'] = $INFOSUBFARM[$i]['FSID'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['FullName'] = $INFOSUBFARM[$i]['FullName'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['NameFarm'] = $INFOSUBFARM[$i]['NameFarm'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['NameSubfarm'] = $INFOSUBFARM[$i]['NameSubfarm'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['AreaRai'] = $INFOSUBFARM[$i]['AreaRai'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['AreaNgan'] = $INFOSUBFARM[$i]['AreaNgan'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['Latitude'] = $INFOSUBFARM[$i]['Latitude'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['Longitude'] = $INFOSUBFARM[$i]['Longitude'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['NumTree'] = $INFOSUBFARM[$i]['NumTree'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['Distrinct'] = $INFOSUBFARM[$i]['Distrinct'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['Province'] = $INFOSUBFARM[$i]['Province'];
            $INFO = getInfoWater($INFOSUBFARM[$i]['FSID'], $year);
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['lastDate'] = $INFO['lastDate'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['lastVol'] = $INFO['lastVol'];
            $INFOSUBFARMWATER[$INFOSUBFARM[$i]['FSID']]['totalVol'] = $INFO['totalVol'];
        }
    }
    return $INFOSUBFARMWATER;
}
function getInfoWater($fsid, $year)
{
    $DATA = array();
    $sql = "SELECT `dim-time`.`dd`,  `dim-time`.`Month`, `dim-time`.`Year2`,`dim-time`.`Date`,ROUND(`log-watering`.`Vol`,2) as Vol FROM `log-watering`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-watering`.`DIMsubFID`
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
    WHERE `log-watering`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid AND `dim-time`.`Year2`= $year
    AND `dim-time`.`Date` =
    (SELECT MAX(`dim-time`.`Date`)  as Date FROM `log-watering`
     INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-watering`.`DIMsubFID`
     INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
    WHERE `log-watering`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid AND `dim-time`.`Year2`= $year)";
    $INFO = selectData($sql);
    $strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    if ($INFO[0]['numrow'] == 0) {
        $DATA['lastDate'] = "-";
        $DATA['lastVol'] = "0.00";
    } else {
        $day = date_diff(date_create($INFO[1]['Date']), date_create(date("Y-m-d")))->format("%a");
        if ($day == 0) {
            $DATA['lastDate'] =  "วันนี้";
        } else {
            $DATA['lastDate'] =  "เมื่อ " . $day . " วันก่อน";
        }

        $DATA['lastVol'] = $INFO[1]['Vol'];
    }
    $sql = "SELECT IFNULL(ROUND(SUM(`log-watering`.`Vol`),2),0.00) AS totalVol FROM `log-watering`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-watering`.`DIMsubFID`
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
    WHERE `log-watering`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid AND `dim-time`.`Year2`= $year";
    $INFO = selectData($sql);
    $DATA['totalVol'] = $INFO[1]['totalVol'];
    return $DATA;
}
function getAvgWater($year)
{
    $sql = "SELECT IFNULL(ROUND(ROUND(SUM(`log-raining`.`Vol`),2)/COUNT(DISTINCT `dim-farm`.`dbID`),2),0) AS  AVGVol  FROM `log-raining` 
    INNER JOIN `dim-time` ON `dim-time`.`ID`=`log-raining`.`DIMdateID`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-raining`.`DIMsubFID`
    WHERE `dim-time`.`Year2` = $year AND `log-raining`.`isDelete`=0 ";
    $DATA = selectData($sql);
    return $DATA[1]['AVGVol'];
}
function getAvgFertilising($year)
{
    $sql = "SELECT IFNULL(ROUND(COUNT(*)/COUNT(DISTINCT `dim-farm`.`dbID`),2),0) AS AVGfertilising FROM `log-fertilising` INNER JOIN `dim-time` ON `dim-time`.`ID`=`log-fertilising`.`DIMdateID` INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID` 
    WHERE `dim-time`.`Year2` = $year AND `log-fertilising`.`isDelete`=0";
    $DATA = selectData($sql);
    return $DATA[1]['AVGfertilising'];
}
function getLogRain($fsid, $year = 0)
{
    $sql = "SELECT `log-raining`.`ID` AS LogID,`dim-time`.`dd`,`dim-time`.`Month`,`dim-time`.`Year2`,
    `log-raining`.`StartTime`,`log-raining`.`StopTime`,`log-raining`.`Period`,`log-raining`.`Vol`
    FROM `log-raining`
    INNER JOIN `dim-time` ON `dim-time`.`ID` =`log-raining`.`DIMdateID`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`log-raining`.`DIMsubFID`
    WHERE `log-raining`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid ";
    if ($year != 0) $sql .= " AND `dim-time`.`Year2` = '$year'";
    $sql .= " ORDER BY `log-raining`.`StartTime` DESC";
    $DATA = selectData($sql);
    return  $DATA;
}
function getLogWater($fsid, $year = 0)
{
    $sql = "SELECT `log-watering`.`ID` AS LogID,`dim-time`.`dd`,`dim-time`.`Month`,`dim-time`.`Year2`,
    `log-watering`.`StartTime`,`log-watering`.`StopTime`,`log-watering`.`Period`,`log-watering`.`Vol`
    FROM `log-watering`
    INNER JOIN `dim-time` ON `dim-time`.`ID` =`log-watering`.`DIMdateID`
    INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`log-watering`.`DIMsubFID`
    WHERE `log-watering`.`isDelete`=0 AND `dim-farm`.`dbID`=$fsid  ";
    if ($year != 0) $sql .= " AND `dim-time`.`Year2` = '$year'";
    $sql .= " ORDER BY `log-watering`.`StartTime` DESC";
    $DATA = selectData($sql);
    return  $DATA;
}
function getFactDry($fsid, $year = 0)
{
    $sql = "SELECT StartT.`Date` AS StartT,  EndT.`Date` AS  EndT,`fact-drying`.`Period`  FROM `fact-drying` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`fact-drying`.`DIMsubFID`
    INNER JOIN `dim-time`  AS StartT ON StartT.`ID` = `fact-drying`.`DIMstartDID`
    LEFT JOIN `dim-time`  AS EndT  ON EndT.`ID` = `fact-drying`.`DIMstopDID` 
    WHERE `dim-farm`.`dbID`=$fsid ";
    if ($year != 0) $sql .= " AND StartT.`Year2` = '$year'";
    $sql .= " ORDER BY StartT.`Date` DESC";
    $DATA = selectData($sql);
    return  $DATA;
}
function getTextEventWatering($fsid)
{
    $INFOLOGRAIN = getLogRain($fsid);
    $INFOLOGWATER = getLogWater($fsid);
    $text = "[";
    for ($i = 1; $i < count($INFOLOGRAIN); $i++) {
        $timeStart = date("Y-m-d\TH:i:s", $INFOLOGRAIN[$i]['StartTime']);
        $timeEnd = date("Y-m-d\TH:i:s", $INFOLOGRAIN[$i]['StopTime']);
        $text .= "{
            title: 'มีฝนตกปริมาณ {$INFOLOGRAIN[$i]['Vol']} มม.',
            start: '$timeStart',
            end: '$timeEnd',
            color: '#36B926',
            textColor: '#FFFFFF'
        },";
    }

    for ($i = 1; $i < count($INFOLOGWATER); $i++) {
        $timeStart = date("Y-m-d\TH:i:s", $INFOLOGWATER[$i]['StartTime']);
        $timeEnd = date("Y-m-d\TH:i:s", $INFOLOGWATER[$i]['StopTime']);
        $text .= "{
            title: 'รดน้ำปริมาณ {$INFOLOGWATER[$i]['Vol']} ลิตร',
            start: '$timeStart',
            end: '$timeEnd',
            color: '#2B9EF7',
            textColor: '#FFFFFF'
        },";
    }

    if ($INFOLOGWATER[0]['numrow'] > 0 || $INFOLOGRAIN[0]['numrow'] > 0) {
        $text = substr($text, 0, -1) . "]";
    } else {
        $text = "[]";
    }

    return $text;
}
function getTextEventDry($fsid)
{
    $INFOLOGDRY = getFactDry($fsid);

    $text = "[";
    for ($i = 1; $i < count($INFOLOGDRY); $i++) {
        if ($INFOLOGDRY[$i]['Period'] > 0) {
            $day = $INFOLOGDRY[$i]['Period'];
            $start = $INFOLOGDRY[$i]['StartT'];
            $end = $INFOLOGDRY[$i]['EndT'];
        } else {
            $date1 = date_create($INFOLOGDRY[$i]['StartT']);
            $date2 = date_create(date("Y-m-d"));
            $diff = date_diff($date1, $date2);
            $day = $diff->format("%a");
            $start = $INFOLOGDRY[$i]['StartT'];
            $end = date("Y-m-d");
        }
        $text .= "{
            title: 'การให้น้ำขาดช่วง $day วัน',
            start: '{$start}',
            end: '{$end}',
            color: '#E51B1B',
            textColor: '#FFFFFF'
        },";
    }
    if ($INFOLOGDRY[0]['numrow'] > 0) {
        $text = substr($text, 0, -1) . "]";
    } else {
        $text = "[]";
    }

    return $text;
}
function getChartActivity($year, $fsid)
{
    $data = array();
    $ArrName = array("ล้างคอขวด", "กำจัดวัชพืช");
    $labelYear = "[";
    $labelData[1] = "[";
    $labelData[2] = "[";
    $Check = false;
    for ($j = 9; $j >= 0; $j--) {

        $thisYear = $year - $j;
        $sql = "SELECT `log-activity`.`DBactID`,COUNT(*) AS num  FROM `log-activity` 
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
        INNER JOIN `dim-time` ON `dim-time`.`ID` =  `log-activity`.`DIMdateID`
        WHERE   `log-activity` .`isDelete`=0  AND `dim-time`.`Year2`='$thisYear'  AND `dim-farm`.`dbID`=$fsid
         GROUP BY `log-activity`.`DBactID`
         ORDER BY`log-activity`.`DBactID`";
        $NUM = selectData($sql);
        if ($Check || $NUM[0]['numrow'] > 0) {
            $Check = true;
            $labelYear .= "\"$thisYear\",";
            $num[1] = 0;
            $num[2] = 0;
            for ($i = 1; $i < count($NUM); $i++) {
                $num[$NUM[$i]['DBactID']] = $NUM[$i]['num'];
            }
            $labelData[1] .=  number_format($num[1], 0, '.', ',') . ",";
            $labelData[2] .=  number_format($num[2], 0, '.', ',') . ",";
        }
    }
    if ($Check) {
        $labelYear = substr($labelYear, 0, -1);
        $labelData[1] = substr($labelData[1], 0, -1);
        $labelData[2] = substr($labelData[2], 0, -1);
    }
    $labelYear .=  "]";
    $labelData[1] .=  "]";
    $labelData[2] .=  "]";


    $data["labelYear"] = $labelYear;
    $data["ArrName"] = $ArrName;
    $data["labeldata"] = $labelData;
    return $data;
}
function getAvgActivity($year, $DBactID)
{
    $sql = "SELECT IFNULL(ROUND(ROUND(COUNT(d1.dbID),2)/COUNT(DISTINCT d1.`dbID`),2),0) AS  AVGTime FROM `log-activity` 
    INNER JOIN `dim-time` ON `dim-time`.`ID`=`log-activity`.`DIMdateID`
    INNER JOIN `dim-farm` AS d1 ON d1.`ID` = `log-activity`.`DIMsubFID`
    INNER JOIN `dim-farm` AS d2 ON d2.`ID` = `log-activity`.`DIMfarmID`
    WHERE `dim-time`.`Year2` = '$year' AND `log-activity`.`isDelete`=0
    AND  `log-activity`.`DBactID` = '$DBactID'";
    $DATA = selectData($sql);

    return $DATA[1]['AVGTime'];
}
function getYearAgriMap()
{

    $sql = "SELECT DISTINCT(Year2) FROM `log-fertilising` 
    JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    WHERE `log-fertilising`.`isDelete` = 0 
    UNION
    SELECT DISTINCT(Year2) FROM `log-harvest` 
    JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
    WHERE `log-harvest`.`isDelete` = 0 
    UNION
    SELECT DISTINCT(Year2) FROM `log-watering` 
    JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
    WHERE `log-watering`.`isDelete` = 0 
    UNION
    SELECT DISTINCT(Year2) FROM `log-raining` 
    JOIN `dim-time` ON `dim-time`.`ID` = `log-raining`.`DIMdateID`
    WHERE `log-raining`.`isDelete` = 0 
    UNION
    SELECT DISTINCT(Year2) FROM `log-pestalarm` 
    JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
    WHERE `log-pestalarm`.`isDelete` = 0 
    UNION
    SELECT DISTINCT(Year2) FROM `log-activity` 
    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
    WHERE `log-activity`.`isDelete` = 0 ORDER BY `Year2` ASC
    ";
    $data = selectData($sql);
    return $data;
}
function getTextCalendar($year, $fpro, $fdist, $fullname, $checkbox)
{

    $text = "[";
    $fullname = rtrim($fullname);
    $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
    $namef = explode(" ", $fullname);
    if (isset($namef[1])) {
        $fnamef = $namef[0];
        $lnamef = $namef[1];
    } else {
        $fnamef = $fullname;
        $lnamef = $fullname;
    }

    $sql = "SELECT SUBFARM.`dbID` AS FSID,`dim-user`.`FullName`,FARM.`Alias` as NameFarm, SUBFARM.`Alias` as NamesubFarm
            FROM `log-farm`
            INNER JOIN `dim-farm` AS SUBFARM ON SUBFARM.`ID` =`log-farm`.`DIMSubfID` 
            INNER JOIN `dim-farm` AS FARM ON FARM.`ID` =`log-farm`.`DIMfarmID` 
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
            INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE  `log-farm`.`ID` IN
            (SELECT MAX(`log-farm`.`ID`)  as ID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMSubfID`
            GROUP BY `dim-farm`.`dbID` ) ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";
    $sql .= " ORDER BY `dim-user`.`FullName`";
    $DATASUBFARM = selectData($sql);
    if ($DATASUBFARM[0]['numrow'] != 0) {
        $INFOFARM = [];
        $text1 = "(";
        for ($i = 1; $i <= $DATASUBFARM[0]['numrow']; $i++) {
            $text1 .= "'{$DATASUBFARM[$i]['FSID']}',";
            $INFOFARM[$DATASUBFARM[$i]['FSID']]['NameFarm'] = $DATASUBFARM[$i]['NameFarm'];
            $INFOFARM[$DATASUBFARM[$i]['FSID']]['NamesubFarm'] = $DATASUBFARM[$i]['NamesubFarm'];
        }
        $text1 = substr($text1, 0, -1) . ")";
        if ($checkbox['เก็บเกี่ยว'] == 1) {
            $sql = "SELECT `dim-time`.`Date`, COUNT(`dim-farm`.`dbID`) AS numSubFarm FROM `log-harvest`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMsubFID`
             WHERE  `log-harvest`.`isDelete` = 0 AND `dim-farm`.`dbID` IN $text1  ";
            if ($year != 0) $sql .= " AND `dim-time`.`Year2` = '$year'";
            $sql .= "    GROUP BY  `dim-time`.`Date`
                         ORDER BY  `dim-time`.`Date`";
            $DATA = selectData($sql);
            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                $text .= "{
                    title: 'เก็บเกี่ยว {$DATA[$i]['numSubFarm']} แปลง',
                    start: '{$DATA[$i]['Date']}',
                    color: '#47C43F',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'เก็บเกี่ยว',
                        color: '#47C43F',
                        date: '{$DATA[$i]['Date']}'
                      }
                },";
            }
        }
        if ($checkbox['ให้น้ำ'] == 1) {
            $sql = "SELECT `dim-time`.`Date`, COUNT(`dim-farm`.`dbID`) AS numSubFarm FROM `log-raining`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-raining`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-raining`.`DIMsubFID`
             WHERE  `log-raining`.`isDelete` = 0 AND `dim-farm`.`dbID` IN $text1  ";
            if ($year != 0) $sql .= " AND `dim-time`.`Year2` = '$year'";
            $sql .= "    GROUP BY  `dim-time`.`Date`
                         ORDER BY  `dim-time`.`Date`";
            $DATA = selectData($sql);
            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                $text .= "{
                    title: 'ฝนตก {$DATA[$i]['numSubFarm']} แปลง',
                    start: '{$DATA[$i]['Date']}',
                    color: '#214ACA',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'ฝนตก',
                        color: '#214ACA',
                        date: '{$DATA[$i]['Date']}'
                      }
                    
                },";
            }
            $sql = "SELECT `dim-time`.`Date`, COUNT(`dim-farm`.`dbID`) AS numSubFarm FROM `log-watering`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-watering`.`DIMsubFID`
             WHERE  `log-watering`.`isDelete` = 0 AND `dim-farm`.`dbID` IN $text1  ";
            if ($year != 0) $sql .= " AND `dim-time`.`Year2` = '$year'";
            $sql .= "    GROUP BY  `dim-time`.`Date`
                         ORDER BY  `dim-time`.`Date`";
            $DATA = selectData($sql);
            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                $text .= "{
                    title: 'รดน้ำ {$DATA[$i]['numSubFarm']} แปลง',
                    start: '{$DATA[$i]['Date']}',
                    color: '#21CCEA',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'รดน้ำ',
                        color: '#21CCEA',
                        date: '{$DATA[$i]['Date']}'
                      }
                },";
            }
        }
        if ($checkbox['ขาดน้ำ'] == 1) {
            $myfile = fopen("./infoDrying.json", "r");
            $INFO = json_decode(fread($myfile, filesize("./infoDrying.json")), true);
            fclose($myfile);
            $INFODATE = array();
            foreach ($INFO as $Date => $FSID) {
                $num = 0;
                $DateYear = date("Y", strtotime($Date)) + 543;
                if ($DateYear == $year || $year == 0) {
                    foreach ($INFO[$Date] as $FSID => $status) {
                        if (isset($INFOFARM[$FSID]) && isset($INFO[$Date][$FSID])) {
                            $num++;
                        }
                    }
                    $INFODATE[$Date] = $num;
                }
            }
            foreach ($INFODATE as $Date => $num) {
                if ($num != 0) {
                    $text .= "{
                    title: 'ขาดน้ำ $num แปลง',
                    start: '$Date',
                    color: '#E51B1B',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'ขาดน้ำ',
                        color: '#E51B1B',
                        date: '$Date'
                      }
                },";
                }
            }
        }
        if ($checkbox['ล้างคอขวด'] == 1) {
            $sql = "SELECT `dim-time`.`Date`, COUNT(`dim-farm`.`dbID`) AS numSubFarm FROM `log-activity` 
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
            WHERE  `log-activity`.`DBactID` = 1 AND `log-activity`.`isDelete`=0 AND `dim-farm`.`dbID` IN  $text1 ";
            if ($year != 0) $sql .= "AND `dim-time`.`Year2` = '$year'";
            $sql .= "    GROUP BY  `dim-time`.`Date`
                         ORDER BY  `dim-time`.`Date`";
            $DATA = selectData($sql);
            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                $text .= "{
                    title: 'ล้างคอขวด {$DATA[$i]['numSubFarm']} แปลง',
                    start: '{$DATA[$i]['Date']}',
                    color: '#F4950B',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'ล้างคอขวด',
                        color: '#F4950B',
                        date: '{$DATA[$i]['Date']}'
                      }
                },";
            }
        }
        if ($checkbox['ให้ปุ๋ย'] == 1) {
            $sql = "SELECT `dim-time`.`Date`, COUNT(`dim-farm`.`dbID`) AS numSubFarm  FROM `log-fertilising`
             INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-fertilising`.`DIMsubFID`
            INNER JOIN  `dim-time` ON   `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
            INNER JOIN `log-fertilizer` ON `log-fertilizer`.`ID` = `log-fertilising`.`ferID`
            WHERE `log-fertilising`.`isDelete`=0 AND `dim-farm`.`dbID` IN  $text1 ";
            if ($year != 0) $sql .= "AND `dim-time`.`Year2` = '$year'";
            $sql .= "   GROUP BY  `dim-time`.`Date`
                        ORDER BY  `dim-time`.`Date`";
            $DATA = selectData($sql);
            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                $text .= "{
                    title: 'ใส่ปุ๋ย {$DATA[$i]['numSubFarm']} แปลง',
                    start: '{$DATA[$i]['Date']}',
                    color: '#665561',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'ให้ปุ๋ย',
                        color: '#665561',
                        date: '{$DATA[$i]['Date']}'
                      }
                },";
            }
        }
        if ($checkbox['พบศัตรูพืช'] == 1) {
            $sql = "SELECT `dim-time`.`Date`, COUNT(`dim-farm`.`dbID`) AS numSubFarm  FROM `log-pestalarm` 
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-pestalarm`.`DIMsubFID`
            INNER JOIN `dim-pest` ON `dim-pest`.`ID` = `log-pestalarm`.`DIMpestID`
            WHERE    `log-pestalarm`.`isDelete`=0 AND `dim-farm`.`dbID` IN  $text1 ";
            if ($year != 0) $sql .= "AND `dim-time`.`Year2` = '$year'";
            $sql .= "   GROUP BY  `dim-time`.`Date`
                        ORDER BY  `dim-time`.`Date`";
            $DATA = selectData($sql);
            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                $text .= "{
                    title: 'พบศัตรูพืช {$DATA[$i]['numSubFarm']} แปลง',
                    start: '{$DATA[$i]['Date']}',
                    color: '#CAC721',
                    textColor: '#FFFFFF',
                    extendedProps: {
                        status: 'พบศัตรูพืช',
                        color: '#CAC721',
                        date: '{$DATA[$i]['Date']}'
                      }
                },";
            }
        }
        if (strlen($text) > 1) {
            $text = substr($text, 0, -1) . "]";
        } else {
            $text = "[]";
        }
        return $text;
    } else {
        return "[]";
    }
}
function INFOCalendar($year, $fpro, $fdist, $fullname, $checkbox)
{
}
function getTableAllFertilising(&$year, &$idformal, &$fullname, &$fpro, &$fdist)
{
    $idformal = '';
    $fpro = 0;
    $fdist = 0;
    $fullname = '';
    if (isset($_POST['s_formalid']))  $idformal = rtrim($_POST['s_formalid']);
    if (isset($_POST['year']))  $year = $_POST['year'];
    if (isset($_POST['s_province']))  $fpro = $_POST['s_province'];
    if (isset($_POST['s_distrinct'])) $fdist = $_POST['s_distrinct'];
    if (isset($_POST['s_name'])) {
        $fullname = rtrim($_POST['s_name']);
        $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
        $namef = explode(" ", $fullname);
        if (isset($namef[1])) {
            $fnamef = $namef[0];
            $lnamef = $namef[1];
        } else {
            $fnamef = $fullname;
            $lnamef = $fullname;
        }
    }
    $sql = "SELECT  sf.`dbID` AS FSID ,f.`dbID` AS FMID,`dim-user`.`FullName`,f.`Name` as NameFarm ,sf.`Name` as NameSubfarm ,
            `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`,`log-farm`.`Latitude`,
            `log-farm`.`Longitude`,`log-farm`.`NumTree`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
            INNER JOIN `dim-farm`as f ON f.`ID` =`log-farm`.`DIMfarmID` 
            INNER JOIN `dim-farm` as sf ON sf.`ID` =`log-farm`.`DIMSubfID` 
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
            INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE  `log-farm`.`ID` IN
            (SELECT MAX(`log-farm`.`ID`)  as LogID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMSubfID` 
            WHERE `log-farm`.`DIMSubfID` IS NOT NULL";
    if ($idformal != '') $sql .= " AND `dim-user`.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";

    $sql .= " GROUP BY `dim-farm`.`dbID`) ORDER BY `dim-user`.`FullName`,f.`Name`  ,sf.`Name`";
    $INFOSUBFARM =  selectData($sql);
    $INFOSUBFARMFertilising = array();
    if ($INFOSUBFARM[0]['numrow'] == 0) {
        $INFOSUBFARMFertilising = null;
    }
    for ($i = 1; $i < count($INFOSUBFARM); $i++) {
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['FMID'] = $INFOSUBFARM[$i]['FMID'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['FSID'] = $INFOSUBFARM[$i]['FSID'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['FullName'] = $INFOSUBFARM[$i]['FullName'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['NameFarm'] = $INFOSUBFARM[$i]['NameFarm'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['NameSubfarm'] = $INFOSUBFARM[$i]['NameSubfarm'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['AreaRai'] = $INFOSUBFARM[$i]['AreaRai'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['AreaNgan'] = $INFOSUBFARM[$i]['AreaNgan'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['Latitude'] = $INFOSUBFARM[$i]['Latitude'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['Longitude'] = $INFOSUBFARM[$i]['Longitude'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['NumTree'] = $INFOSUBFARM[$i]['NumTree'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['Distrinct'] = $INFOSUBFARM[$i]['Distrinct'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['Province'] = $INFOSUBFARM[$i]['Province'];
        $INFO = getInfoFertilising($INFOSUBFARM[$i]['FSID'], $year);
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['countFertilising'] =  $INFO['countFertilising'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['N'] =  $INFO['N']['sumVol'] . "/" . $INFO['N']['UnitUse'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['N'] .= ($INFO['N']['Unit'] == 1) ? "Kg" : "g";
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['P'] =  $INFO['P']['sumVol'] . "/" . $INFO['P']['UnitUse'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['P'] .= ($INFO['P']['Unit'] == 1) ? "Kg" : "g";
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['K'] =  $INFO['K']['sumVol'] . "/" . $INFO['K']['UnitUse'];
        $INFOSUBFARMFertilising[$INFOSUBFARM[$i]['FSID']]['K'] .= ($INFO['K']['Unit'] == 1) ? "Kg" : "g";
    }
    return $INFOSUBFARMFertilising;
}
function getInfoFertilising($FSID, $year)
{
    $INFO = array();
    $sql = "SELECT COUNT(*) as countFertilising FROM `log-fertilising` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    WHERE `dim-farm`.`dbID`=$FSID AND `dim-time`.`Year2` = $year AND `log-fertilising`.`isDelete`=0";
    $DATA = selectData($sql);
    $INFO['countFertilising'] = $DATA[1]['countFertilising'];

    $DATA = getSumVolFertilising($FSID, $year, 1);

    if ($DATA[0]['numrow'] != 0) {
        $INFO['N']['sumVol'] = $DATA[1]['sumVol'];
        $INFO['N']['Unit'] = $DATA[1]['Unit'];
    } else {
        $INFO['N']['sumVol'] = 0;
        $INFO['N']['Unit'] = 1;
    }
    $INFO['N']['UnitUse'] =  getVolUseFertilising($FSID, 1, $year);
    $DATA = getSumVolFertilising($FSID, $year, 2);
    $vol = 0;
    if ($DATA[0]['numrow'] != 0) {
        $INFO['P']['sumVol'] = $DATA[1]['sumVol'];
        $INFO['P']['Unit'] = $DATA[1]['Unit'];
    } else {
        $INFO['P']['sumVol'] = 0;
        $INFO['P']['Unit'] = 1;
    }
    $INFO['P']['UnitUse'] =  getVolUseFertilising($FSID, 2, $year);
    $DATA = getSumVolFertilising($FSID, $year, 3);
    if ($DATA[0]['numrow'] != 0) {
        $INFO['K']['sumVol'] = $DATA[1]['sumVol'];
        $INFO['K']['Unit'] = $DATA[1]['Unit'];
    } else {
        $INFO['K']['sumVol'] = 0;
        $INFO['K']['Unit'] = 1;
    }
    $INFO['K']['UnitUse'] =  getVolUseFertilising($FSID, 3, $year);
    return $INFO;
}
function getVolUseFertilising($FSID, $NID, $year)
{
    $sql = "SELECT * FROM `log-nutrient`
    INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-nutrient`.`DIMnutrID`
    WHERE `dim-nutrient`.`dbID` =$NID 
    ORDER BY `log-nutrient`.`ID` DESC
    LIMIT 1";
    $DATANUTR = selectData($sql);
    $Usage = $DATANUTR[1]['Usage'];
    $EQ1 = $DATANUTR[1]['EQ1'];
    $EQ2 = $DATANUTR[1]['EQ2'];
    $sql = "SELECT * FROM `log-farm` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
    WHERE `dim-farm`.`dbID` =$FSID 
    ORDER BY `log-farm`.`ID` DESC
    LIMIT 1";
    $DATSUBFARM = selectData($sql);
    $NumTree = $DATSUBFARM[1]['NumTree'];
    if ($Usage == 1) {
        $sql = "SELECT $year -`dim-time`.`Year2`+1 as AgeTree FROM `log-planting` 
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-planting`.`DIMsubFID`
        INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-planting`.`DIMdateID`
        WHERE `log-planting`.`isDelete` = 0 AND `dim-farm`.`dbID` = $FSID AND `log-planting`.`NumGrowth1` IS NOT NULL";
        $DATA = selectData($sql);
        if ($DATA[0]['numrow'] == 0) {
            $AgeTree = 0;
        } else {
            $AgeTree = $DATA[1]['AgeTree'];
        }
        $Vol = $NumTree * (($AgeTree * $EQ1) + $EQ2);
    } else if ($Usage == 2) {
        $sql = "SELECT `HarvestVol` FROM `fact-farming` 
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-farming`.`DIMsubFID`
        WHERE `dim-farm`.`dbID`=$FSID AND `fact-farming`.`TagetYear`=$year-543";
        $DATA = selectData($sql);
        if ($DATA[0]['numrow'] == 0) {
            $HarvestVol = 0;
        } else {
            $HarvestVol = $DATA[1]['HarvestVol'] / 1000;
        }
        $Vol = $NumTree * (($HarvestVol * $EQ1) + $EQ2);
    } else {
        $Vol = $NumTree * $EQ2;
    }
    return round($Vol, 2);
}
function getinfoFertilisingDetail($FSID)
{
    $sql = "SELECT `log-fertilising`.`ID`,`dim-time`.`dd` AS day,`dim-time`.`Month`,
    `dim-time`.`Year2` ,`dim-time`.`Date`  ,`log-fertilizer`.`Name`,ROUND(`log-fertilising`.`Vol`,2) AS Vol,IF(`log-fertilising`.`Unit`=1,'Kg','g') AS Unit
    FROM `log-fertilising` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    INNER JOIN `log-fertilizer` ON `log-fertilizer`.`ID` = `log-fertilising`.`ferID`
    WHERE `dim-farm`.`dbID` = $FSID AND `log-fertilising`.`isDelete`=0
    ORDER BY `dim-time`.`Date` DESC";
    $DATA = selectData($sql);
    return $DATA;
}
function getSumVolFertilising($FSID, $year, $NID)
{
    $sql = "SELECT ROUND(SUM(`log-fertilisingdetail`.`Vol`),2) AS sumVol,`log-nutrient`.`Unit` FROM `log-fertilising` 
    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    INNER JOIN `log-fertilisingdetail` ON `log-fertilisingdetail`.`fertilisingID` = `log-fertilising`.`ID`
    INNER JOIN `log-nutrient` ON `log-nutrient`.`ID` = `log-fertilisingdetail`.`logNID`
    INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-nutrient`.`DIMnutrID`
    WHERE `dim-farm`.`dbID`=$FSID AND `dim-time`.`Year2` =$year AND `dim-nutrient`.`dbID` = $NID AND `log-fertilising`.`isDelete`=0
    GROUP BY `log-nutrient`.`Unit`";
    $DATA = selectData($sql);
    return $DATA;
}

function getFertilizerList()
{
    $sql = "SELECT * FROM `log-fertilizer`
    WHERE `isDelete` = 0
    ORDER BY `Name`";
    $DATA = selectData($sql);
    return $DATA;
}
function getTextEventFertilising($fsid)
{
    $INFOLOGFertilising = getinfoFertilisingDetail($fsid);
    $text = "[";
    for ($i = 1; $i <= $INFOLOGFertilising[0]['numrow']; $i++) {
        $timeStart = $INFOLOGFertilising[$i]['Date'];
        $text .= "{
            title: 'มีการใส่{$INFOLOGFertilising[$i]['Name']}  จำนวน {$INFOLOGFertilising[$i]['Vol']} {$INFOLOGFertilising[$i]['Unit']}.',
            start: '$timeStart',
            color: '#665561',
            textColor: '#FFFFFF'
        },";
    }

    if ($INFOLOGFertilising[0]['numrow'] > 0) {
        $text = substr($text, 0, -1) . "]";
    } else {
        $text = "[]";
    }

    return $text;
}
function getInfoNutr()
{
    $sql = "SELECT   `dim-nutrient`.`dbID` AS NID ,`dim-nutrient`.`Name`,`log-nutrient`.`Type`,IF(`log-nutrient`.`Unit`=1,'Kg','g') AS  Unit ,`log-nutrient`.`Unit` AS UnitNum FROM `log-nutrient`
        INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID`= `log-nutrient`.`DIMnutrID`
        WHERE `log-nutrient`.`EndT` IS NULL
        ORDER BY `log-nutrient`.`Type` ,`dim-nutrient`.`dbID`";
    $DATA = selectData($sql);
    return $DATA;
}

function getFarmAll()
{
    $sql = "SELECT `log-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name` AS F_name,`dim-address`.`dbsubDID` FROM `log-farm`
	JOIN `dim-address` ON `dim-address`.`ID` =  `log-farm`.`DIMaddrID`
    JOIN `dim-farm` ON `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
    WHERE `log-farm`.`ID` IN (
    SELECT MAX(`log-farm`.`ID`) as max FROM `log-farm`
    JOIN `dim-farm` ON `log-farm`.`DIMfarmID`= `dim-farm`.`ID`
    GROUP BY `dim-farm`.`dbID`)  
    ORDER BY `dim-address`.`dbsubDID` ASC, `dim-farm`.`Name` ASC";
    $DATA = selectData($sql);
    return $DATA;
}
function getSubfarmAll()
{
    $sql = "SELECT t1.`ID`,t1.`dbID` AS sb_dbID,t1.`Name` AS SF_name,`dim-farm`.`dbID` AS f_dbID FROM (
    SELECT `log-farm`.`ID`,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMfarmID` FROM `log-farm`
    JOIN `dim-farm` ON `log-farm`.`DIMSubfID` = `dim-farm`.`ID`
    WHERE `log-farm`.`ID` IN (
    SELECT MAX(`log-farm`.`ID`) as max FROM `log-farm`
    JOIN `dim-farm` ON `log-farm`.`DIMSubfID`= `dim-farm`.`ID`
    GROUP BY `dim-farm`.`dbID`)
    )AS t1
    JOIN `dim-farm` ON t1.`DIMfarmID`= `dim-farm`.`ID`  
    ORDER BY `f_dbID` ASC,sf_name ASC";
    $DATA = selectData($sql);
    return $DATA;
}
function getFarmerAll_Chart()
{
    $sql = "SELECT `log-farmer`.`ID`,`dim-user`.`dbID`,`dim-user`.`FullName` AS FM_name FROM `log-farmer`
    JOIN `dim-user` ON `log-farmer`.`DIMuserID` = `dim-user`.`ID`
    WHERE `log-farmer`.`ID` IN (
    SELECT MAX(`log-farmer`.`ID`) as max FROM `log-farmer`
    JOIN `dim-user` ON `log-farmer`.`DIMuserID` = `dim-user`.`ID`
    GROUP BY `dim-user`.`dbID`)";
    $data = selectData($sql);
    return $data;
}
