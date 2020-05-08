<?php
include_once("./../../dbConnect.php");
$myConDB = connectDB();

$currentYear = date("Y") + 543;
$backYear = $currentYear - 1;

function creatCard( $styleC, $headC, $textC, $iconC ) { 
    echo "<div class='col-xl-3 col-12 mb-4'>
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
//-----------------Department.php---------------------
//จำนวนหน่วยงานทั้งหมด
function getCountDepartment(){
    $sql = "SELECT COUNT(*) AS countDepartment FROM `db-department`";
    $countDepartment = selectData($sql)[1]['countDepartment'];
    return $countDepartment;
}
//หน่วยงานทั้งหมด
function getDepartment(){
    $sql = "SELECT * FROM `db-department`";
    $DEPARTMENT = selectData($sql);
    return $DEPARTMENT;
}
//หน่วยงาน ตาม id หน่วยงาน
function getDepartmentUser($did){
    $sql = "SELECT * FROM `db-department` WHERE DID = $did ";
    $DEPARTMENTUSER = selectData($sql);
    return $DEPARTMENTUSER;
}
//หน่วยงานทั้งหมด (table) 
function getAllDepartment(){
    $sql = "SELECT `db-department`.`DID`,`db-department`.`Department`,`db-department`.`Alias`,`db-department`.`Note`,COUNT(`db-user`.`DID`) AS count_de FROM `db-department` 
    LEFT JOIN `db-user` ON `db-department`.DID = `db-user`.DID GROUP BY `db-department`.`DID`,`db-department`.`Department`,`db-department`.`Alias`,`db-department`.`Note`";
    $ALLDEPARTMENT = selectData($sql);
    return $ALLDEPARTMENT;

}
//-----------------OtherUserlist.php---------------------
//จำนวนผู้ใช้ทั้งหมด
function getCountUser(){
    $sql = "SELECT COUNT(*) AS countUser FROM `db-user`";
    $countUser = selectData($sql)[1]['countUser'];
    return $countUser;
}
//จำนวนผู้ดูแลระบบทั้งหมด
function getCountAdmin(){
    $sql = "SELECT COUNT(*) AS countAdmin FROM `db-user` WHERE IsAdmin = 1 ";
    $countAdmin = selectData($sql)[1]['countAdmin'];
    return $countAdmin;
}
//emailtype ทั้งหมด
function getEmailtype(){
    $sql = "SELECT * FROM `db-emailtype`";
    $EMAILTYPE = selectData($sql);
    return $EMAILTYPE;
}
//emailtype ตาม id emailtype
function getEmailtypeUser($etid){
    $sql = "SELECT * FROM `db-emailtype` WHERE ETID = $etid ";
    $EMIALTYPEUSER= selectData($sql);
    return $EMIALTYPEUSER;
}
//ผู้ใช้ ตาม id ผู้ใช้
function getUser($id_u){
    $sql ="SELECT * FROM `db-user` WHERE `UID`=$id_u";
    $USER= selectData($sql);
    return $USER;
}
//จังหวัดทั้งหมด
function getProvince(){
    $sql = "SELECT * FROM `db-province` ORDER BY `db-province`.`Province`  ASC";
    $PROVINCE = selectData($sql);
    return $PROVINCE;
}
//อำเภอในจังหวัด ตาม id จังหวัด
function getDistrinctInProvince($fpro){
    $sql = "SELECT * FROM `db-distrinct` WHERE `AD1ID`=$fpro ORDER BY `db-distrinct`.`Distrinct`  ASC";
    $DISTRINCT_PROVINCE = selectData($sql);
    return $DISTRINCT_PROVINCE;
}

//-----------------------FarmerList--------------------------

function getCountFarmer()
{
    $sql = "SELECT COUNT(*) AS countFarmer 
    FROM (SELECT `UFID`FROM `db-farmer` JOIN `dim-user` ON `dim-user`.`dbID`=`db-farmer`.`UFID`WHERE `dim-user`.`Type`='F') AS farmer";
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
function getCountArea(){
    $sql = "SELECT SUM(`AreaRai`) AS countArea FROM `db-subfarm`";
    return selectData($sql)[1]['countArea'];
}

//ตารางเกษตรกร (table)
function getFarmer(&$idformal,&$fullname,&$fpro,&$fdist)
{
    $myConDB = connectDB();

    $idformal = '';
    $fpro = '';
    $fdist = '';
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

    $sql = "SELECT UFID,Title,FirstName,LastName,FormalID,Icon,`Address`,`db-farmer`.`AD3ID`,IsBlock,`db-farmer`.`ModifyDT`,`db-distrinct`.AD2ID,`db-distrinct`.AD1ID,subDistrinct,Distrinct,Province FROM `db-farmer` 
                INNER JOIN `db-subdistrinct` ON `db-farmer`.`AD3ID`=  `db-subdistrinct`.AD3ID
                INNER JOIN `db-distrinct` ON `db-subdistrinct`.`AD2ID`=  `db-distrinct`.AD2ID
                INNER JOIN `db-province` ON `db-distrinct`.`AD1ID`=  `db-province`.AD1ID
                WHERE 1 ";

    if ($idformal != '') $sql = $sql . " AND FormalID LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql = $sql . " AND (FirstName LIKE '%" . $fnamef . "%' OR LastName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql = $sql . " AND `db-distrinct`.AD1ID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql = $sql . " AND `db-distrinct`.AD2ID = '" . $fdist . "' ";

    //echo $sql;

    $result2 = $myConDB->prepare($sql);
    $result2->execute();


    //INFO
    $sql = "SELECT `UFID`,`FirstName`,`LastName`,`Distrinct`,`Province`, 
    `dim-user`.`FullName`, `dim-user`.`ID` AS dimFID , `db-farmer`.`FormalID`
     FROM `db-farmer` 
     JOIN `dim-user` ON `dim-user`.`dbID`=`db-farmer`.`UFID`
     JOIN `db-subdistrinct` ON `db-subdistrinct`.`AD3ID` = `db-farmer`.`AD3ID` 
     JOIN `db-distrinct` ON `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
     JOIN `db-province` ON `db-province`.`AD1ID` = `db-distrinct`.`AD1ID` 
     WHERE `dim-user`.`Type`='F' ";
    if ($idformal != '') $sql = $sql . " AND `db-farmer`.`FormalID` LIKE '%" . $idformal . "%' ";
    if ($fullname != '') $sql = $sql . " AND (FirstName LIKE '%" . $fnamef . "%' OR LastName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql = $sql . " AND `db-distrinct`.AD1ID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql = $sql . " AND `db-distrinct`.AD2ID = '" . $fdist . "' ";
    $sql = $sql . " ORDER BY  `dim-user`.`FullName`";
    //echo $sql;
    $myConDB = connectDB();
    $result = $myConDB->prepare($sql);
    $result->execute();

    $numFermer = 0;
    $FARMER = NULL;
    foreach ($result as $tmp => $tmpDATA) {
        //print_r($tmpDATA);
        if ($tmpDATA['UFID'] > 0) {
            $FARMER[$numFermer]['dbID']        = $tmpDATA['UFID'];
            $FARMER[$numFermer]['dimID']       = $tmpDATA['dimFID'];
            $FARMER[$numFermer]['FullName']    = $tmpDATA['FullName'];
            $FARMER[$numFermer]['Province']    = $tmpDATA['Province'];
            $FARMER[$numFermer]['Distrinct']   = $tmpDATA['Distrinct'];
            $FARMER[$numFermer]['numFarm']     = getCountOwnerFarm($tmpDATA['UFID']);
            $FARMER[$numFermer]['numSubFarm']  = getCountOwnerSubFarm($tmpDATA['UFID']);
            $FARMER[$numFermer]['numTree']     = getCountOwnerTree($tmpDATA['UFID']);
            $FARMER[$numFermer]['numArea1']    = getCountOwnerAreaRai($tmpDATA['UFID']);
            $FARMER[$numFermer]['numArea2']    = getCountOwnerAreaNgan($tmpDATA['UFID']);
            $FARMER[$numFermer]['FormalID']    = $tmpDATA['FormalID'];
            $fermerINDEX[$tmpDATA['dimFID']]   = $numFermer;
            $numFermer++;
        }
    }
    // $sql1 = "SELECT `DIMownerID`, `DIMfarmID`, `NumSubFarm`,`NumTree`,`AreaRai`, `AreaNgan`FROM `log-farm`
    // WHERE `DIMSubfID` IS NULL AND `EndT` IS NULL";
    // $myConDB = connectDB();
    // $result1 = $myConDB->prepare($sql1);
    // $result1->execute();
    // foreach ($result1 as $tmp => $tmpDATA) {
    //     $tmpID = $fermerINDEX[$tmpDATA['DIMownerID']];
    //     if ($tmpID >= 0) {
    //         //print_r($tmpDATA);
    //         $FARMER[$tmpID]['numFarm']++;
    //         $FARMER[$tmpID]['numSubFarm']   += $tmpDATA['NumSubFarm'];
    //         $FARMER[$tmpID]['numTree']      += $tmpDATA['NumTree'];
    //         $FARMER[$tmpID]['numArea1']     += $tmpDATA['AreaRai'];
    //         $FARMER[$tmpID]['numArea2']     += $tmpDATA['AreaNgan'];
    //     }
    // }
    if(!is_null($FARMER))
        return $FARMER;
    else return 0;
}
//-----------------------FarmerListDetail--------------------------

function getFarmerByUFID($ufid){
    $sql = "SELECT * , CASE WHEN `Title` IN ('1') THEN 'นาย'
    WHEN `Title` IN ('2') THEN 'นาง' 
    WHEN `Title` IN ('3') THEN 'นางสาว' END AS Title                   
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
    if($countownerFarm == NULL)
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
    if($countownerAreaRai == NULL)
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
    if($countownerAreaNgan == NULL)
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
    if($countownerTree == NULL)
        return 0;
    return $countownerTree;
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
//----------------------------Fertilizer--------------------------------------

function getCountFertilizer(){
    $sql = "SELECT COUNT(*) AS countFer FROM `db-fertilizer` 
    join `dim-fertilizer` on (`dbID` = `db-fertilizer`.`FID`) 
    WHERE `db-fertilizer`.`Name` = `dim-fertilizer`.`Name` AND `db-fertilizer`.`Alias` = `dim-fertilizer`.`Alias`";
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
function getAreaRai()
{
    $sql = "SELECT SUM(`AreaRai`)AS AreaRai FROM `db-subfarm`";
    $areaRai = selectData($sql)[1]['AreaRai'];
    return $areaRai;
}
// จำนวนต้นไม้
function getCountTree()
{
    $sql = "SELECT sum(`log-farm`.`NumTree`) as numTree FROM `log-farm` where `log-farm`.`EndT` is null AND `log-farm`.`DIMSubfID` is null";
    $numTree = selectData($sql)[1]['numTree'];
    return $numTree;
}
// ตารางสวนปาล์มน้ำมันในระบบ
function getOilPalmAreaList()
{
    $sql = "SELECT `log-farm`.`ID`,`dim-farm`.`dbID` AS FMID ,
    `dim-address`.`Province`,`dim-address`.`Distrinct`,
    `dim-user`.`FullName`, `dim-user`.`Alias`, `dim-farm`.`Name`, `log-farm`.`NumSubFarm`,
    `log-farm`.`AreaRai`, `log-farm`.`AreaNgan`,`log-farm`.`NumTree` 
    FROM `log-farm` 
    INNER JOIN `dim-user`ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
    INNER JOIN `dim-address`ON `dim-address`.`ID` =`log-farm`.`DIMaddrID`
    INNER JOIN `dim-farm`ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
    WHERE `log-farm`.`DIMSubfID` IS NULL AND`log-farm`.`EndT`IS NULL
    ORDER BY `dim-address`.`Province`,`dim-address`.`Distrinct`,`dim-user`.`Alias`";
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
    $sql = "SELECT FMID,Name,Alias,Address,UFID, `db-farm`.`AD3ID`,`db-subdistrinct`.`AD2ID`,`db-distrinct`.`AD1ID`
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
    $sql = "SELECT `db-coorfarm`.`Latitude`,`db-coorfarm`.`Longitude`,`db-subfarm`.`FSID` FROM `db-coorfarm`
    INNER JOIN `db-subfarm` ON `db-coorfarm`.`FSID`=`db-subfarm`.`FSID`
    INNER JOIN `db-farm` ON `db-subfarm`.`FMID` = `db-farm`.`FMID`
    WHERE `db-farm`.`FMID` = '$fmid'";
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
function getDataFarmByFSID($suid)
{
    $sql = "SELECT 	`db-subfarm`.* ,`db-subdistrinct`.`AD2ID`,`db-distrinct`.`AD1ID` FROM `db-subfarm` INNER JOIN `db-subdistrinct`ON `db-subfarm`.`AD3ID`=`db-subdistrinct`.`AD3ID`
    INNER JOIN `db-distrinct`ON`db-distrinct`.`AD2ID`=`db-subdistrinct`.`AD2ID` WHERE `FSID`='$suid'";
    $DataFarm = selectData($sql);
    return $DataFarm;
}

// sql ค่าของ AddressSubDetail มีการรับค่า ID ของ FSID
function getAddressSubDetail($suid)
{
    $sql = "SELECT Address , subDistrinct , Distrinct , Province FROM `db-farm`
    inner join `db-subdistrinct` on `db-subdistrinct`.`AD3ID` = `db-farm`.`AD3ID`
    inner join `db-distrinct` on `db-distrinct`.`AD2ID` = `db-subdistrinct`.`AD2ID`
    inner join `db-province` on `db-province`.`AD1ID` = `db-distrinct`.`AD1ID`
    INNER JOIN `db-subfarm` on `db-subfarm`.`FMID` = `db-farm`.`FMID`
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

// sql ค่าของ Year 
function getYear()
{
    $sql = "SELECT DISTINCT `dim-time`.`Year2` FROM `log-harvest`
    INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID`  
    ORDER BY `dim-time`.`Year2` DESC";
    $Year = selectData($sql);
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
    $sql = "SELECT `dim-fertilizer`.`ID`,`dim-fertilizer`.`Name` AS ferName,`dim-time`.`Year2` AS YY,
    SUM(`log-fertilising`.`Vol`) AS sumvol 
    FROM `log-fertilising` 
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    INNER JOIN `dim-fertilizer` ON `dim-fertilizer`.`ID` = `log-fertilising`.`DIMferID`  
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    where `DIMfarmID`= '$logfarmID' AND `DIMsubFID`=' $logfarmID'
    GROUP BY `dim-fertilizer`.`Name` ,`dim-time`.`Year2`
    ORDER BY `dim-fertilizer`.`Name` ,`dim-time`.`Year2` ";
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
    $sql = "SELECT `dim-fertilizer`.`ID`,`dim-fertilizer`.`Name`as namevol , `dim-farm`.`Name` FROM `log-fertilising` 
    INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    INNER JOIN `dim-fertilizer` ON `dim-fertilizer`.`ID` = `log-fertilising`.`DIMferID`  
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-fertilising`.`DIMsubFID`
    INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID` = `dim-farm`.`dbID`
    where `dim-farm`.`dbID` = '$suid'
    GROUP BY `dim-fertilizer`.`Name` 
    ORDER BY `dim-fertilizer`.`Name`";
    $Namevol = selectData($sql);
    return $Namevol;
}
// sql ค่าของ Numvol มีการรับค่า ID ของ logfarmID
function getNumvol($suid)
{
    $sql = "SELECT  `db-fertilizer`.`EQ1`,`db-fertilizer`.`EQ2` , `dim-farm`.`Name` FROM `db-fertilizer`
    INNER JOIN `dim-fertilizer` ON `dim-fertilizer`.`dbID` = `db-fertilizer`.`FID`
    INNER JOIN `log-fertilising` ON `log-fertilising`.`DIMferID` = `dim-fertilizer`.`ID`
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

//--------------------------------OilPalmAreaVol-------------------------------------
//ผลผลิตปาล์มทั้งหมด
function getHarvest()
{
    $sql = "SELECT * FROM `log-harvest` WHERE `isDelete` = 0";
    $HARVEST = selectData($sql);
    return $HARVEST;
}

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
    for($i=1;$i<$x;$i++)
    {
        if((int)date('Y',$HARVEST[$i]["Modify"]) + 543==$currentYear){
            $harvestCurrentYear = $harvestCurrentYear + (int)$HARVEST[$i]["Weight"];
        }
    }
    return $harvestCurrentYear;
}

//ผลผลิตต่อเดือน
function getHarvestPerMonth($farmID,$year)
{
      
    for($i = 1 ;$i <= 12; $i++)
        $HARVESTMONTH["$i"] = 0 ; 
      
    $sql="SELECT * FROM `log-harvest` 
        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
        WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0 ";
    $HARVEST = selectData($sql);
    $x = count($HARVEST);
    for($i=0;$i<$x;$i++){
        if((int)date('Y',$HARVEST[$i]["Modify"]) + 543==$year)
        {
            $HARVESTMONTH[(date("n",$HARVEST[$i]["Modify"]))] = $HARVESTMONTH[(date("n",$HARVEST[$i]["Modify"]))] + $HARVEST[$i]["Weight"];
        }
    }
    
    return $HARVESTMONTH;
}

//สรุปผลผลิตต่อปี
function getSummarizeHarvest($farmID,$year)
{
    $sql="SELECT * FROM `log-harvest` 
    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
    WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0 ";
    $HARVEST = selectData($sql);
    $SUMMARIZE["Weight"] = 0;
    $SUMMARIZE["Price"] = 0;
    $x = count($HARVEST);
    for($i=1;$i<$x;$i++){
        if((int)date('Y',$HARVEST[$i]["Modify"]) + 543==$year)
        {
            $SUMMARIZE["Weight"] = $SUMMARIZE["Weight"] + $HARVEST[$i]["Weight"];
            $SUMMARIZE["Price"] = $SUMMARIZE["Price"] + $HARVEST[$i]["TotalPrice"];
        }
    }
    return $SUMMARIZE;
}


//แก้ไข
//ผลผลิตปาร์มแบบมี ID และเป็นแต่ละปีของตารางรายการเก็บผลผลิตต่อแปลง
function getHarvestYearID($farmID,$year)
{
    $sql = "SELECT * , `log-harvest`.`ID` AS `logID` FROM `log-harvest`
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMsubfID`
            JOIN `db-subfarm` ON `dim-farm`.`dbID` = `db-subfarm`.`FSID`
            WHERE `FMID` = $farmID AND `isDelete`= 0  AND `isFarm`= 0";
    $HARVEST = selectData($sql);
    $x = count($HARVEST);
    $num = 0;
    for($i=1;$i<$x;$i++)
    {
        if((int)date('Y',$HARVEST[$i]["Modify"]) + 543==$year){
            $HARVESTYEAR[$num]["ID"] = $HARVEST[$i]['logID'];
            $HARVESTYEAR[$num]["alias"] = $HARVEST[$i]['Alias'];
            $HARVESTYEAR[$num]["modifyday"] = date('d',$HARVEST[$i]["Modify"]);
            $HARVESTYEAR[$num]["modifymonth"] = date('n',$HARVEST[$i]["Modify"]);
            $HARVESTYEAR[$num]["modifyyear"] = date('Y',$HARVEST[$i]["Modify"])+543;
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
function getFarmByFMID($farmID){
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
    for($i = 1;$i < $x;$i++)
    {
        $ALLYEAR[$num] = (date("Y",$HARVEST[$i]["Modify"])+543);
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
    for($i=1;$i<$x;$i++)
    {
        $GETYEAR[$num] = (date("Y",$A[$i]["Modify"]));
        $num += 1;
    }
    $YEAR= array_unique($GETYEAR);
    rsort($YEAR);
    $x= count($YEAR);
      
    $sql="SELECT * FROM `log-harvest` 
        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
        WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0 ";
    $HARVEST = selectData($sql);
    for ($i=1; $i < $x; $i++) { 
        $PRODUCT["$YEAR[$i]"] = 0;
    }
    $x = count($HARVEST);
    // print_r($PRODUCT);
    for($i=1;$i<$x;$i++){
        $PRODUCT[(date("Y",$HARVEST[$i]["Modify"]))] = $PRODUCT[(date("Y",$HARVEST[$i]["Modify"]))] + $HARVEST[$i]["Weight"];
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
function getIcon($farmID){
    $sql = "SELECT * FROM `dim-farm` 
    JOIN `db-farm` ON `dim-farm`.`dbID` = `db-farm`.`FMID` 
    WHERE `dim-farm`.`dbID` = $farmID AND `isFarm` = 1";
    return selectdata($sql);
}
function getAllSubFarmById($farmID){
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
    for($i=1;$i<$x;$i++)
    {
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
    $sql="SELECT * FROM `log-fertilising` 
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
    $num=0;
    //หาปีที่มีผผลผลิตไปใส่ในอาเรย์
    $sql = "SELECT * FROM `log-harvest` 
            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMfarmID`
            WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0";
    $GETYEAR = selectData($sql);
    $x= count($GETYEAR);
    $num = 0;
    for ($i=1; $i < $x; $i++) { 
        $YEAR[$num] = date("Y",$GETYEAR[$i]["Modify"]);
        $num++;
    }
    $YEAR= array_unique($YEAR);
    rsort($YEAR);
    $y= count($YEAR);
    for ($i=0; $i < $y; $i++) { 
        $FER["$YEAR[$i]"] = 0;
        $TREEPERYEAR["$YEAR[$i]"]=0;
    }


    for ($j=0; $j < $y; $j++) {

        $requireYear=(int)$YEAR[$j];

        if($requireYear==$currentYear-543){
        $FERTHISYEAR = getLogFertilizer($farmID);
        $x = count($FERTHISYEAR);
            for($i=1;$i<$x;$i++)
            {   
                if((int)date("Y",$FERTHISYEAR[$i]["Modify"]) ==(int)$requireYear)
                {
                    $FER[(date("Y",$FERTHISYEAR[$i]["Modify"]))] = $FER[(date("Y",$FERTHISYEAR[$i]["Modify"]))] + $FERTHISYEAR[$i]["Vol"];
                }
            }
        }
        else{
            $FERNOTTHISYEAR = getFactFertilising($farmID);
            $x = count($FERNOTTHISYEAR);
            for($i=1;$i<$x;$i++)
            {
                if((int)date("Y",$FERNOTTHISYEAR[$i]["Modify1"]) ==(int)$requireYear){
                      $FER[(date("Y",$FERNOTTHISYEAR[$i]["Modify1"]))] = $FER[(date("Y",$FERNOTTHISYEAR[$i]["Modify1"]))] + $FERNOTTHISYEAR[$i]["Vol2"]; 
                }
            }
            $sql = "SELECT * FROM `log-planting`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-planting`.`DIMfarmID`
                WHERE `dim-farm`.`dbID` = $farmID AND `isDelete`= 0  AND `isFarm`= 1";
            $TREE = selectData($sql);
            $x=count($TREE);
            for($i=1;$i<$x;$i++)
            {
                if((int)date('Y',$TREE[$i]["Modify"]) ==(int)$requireYear)   {  
                    $TREEPERYEAR[(date("Y",$TREE[$i]["Modify"]))] = $TREEPERYEAR[(date("Y",$TREE[$i]["Modify"]))] + $TREE[$i]['NumGrowth1'] + $TREE[$i]['NumGrowth2'] - $TREE[$i]['NumDead'];
                }
            }
        }
    }

    for ($i=0; $i < $y; $i++) {
        $requireYear=(int)$YEAR[$i];
            if($TREEPERYEAR[$requireYear]==0){
                $FER[$requireYear]=$FER[$requireYear];
            }
            else{
                $FER[$requireYear]=(int)$FER[$requireYear] / (int)$TREEPERYEAR[$requireYear];
            }
    } 

    return $FER;
}

//LogFertilising
function getLogfertilising(){
    $sql = "SELECT * FROM `log-fertilising` WHERE `isDelete` = 0 ";
    $FERTILISING = selectData($sql);
    return $FERTILISING;
}

//การ์ด - ผลผลิตปาล์มปีนี้
function getHarvestCurrentYear()
{
    $currentYear = date("Y") + 543;
    $backYear = $currentYear - 1;
    $HARVEST = getHarvest();
    $harvestCurrentYear = 0;
    $x = count($HARVEST);
    for($i=1;$i<$x;$i++)
    {
        if((int)date('Y',$HARVEST[$i]["Modify"]) + 543==$currentYear){
            $harvestCurrentYear = $harvestCurrentYear + (int)$HARVEST[$i]["Weight"];
        }
    }
    return $harvestCurrentYear;
}

//การ์ด - ผลผลิตปาล์มปีที่แล้ว
function getHarvestBackYear()
{
    $currentYear = date("Y") + 543;
    $backYear = $currentYear - 1;
    $HARVEST = getHarvest();
    $harvestBackYear = 0;
    $x = count($HARVEST);
    for($i=1;$i<$x;$i++)
    {
        if((int)date('Y',$HARVEST[$i]["Modify"]) + 543==$backYear){
            $harvestBackYear = $harvestBackYear + (int)$HARVEST[$i]["Weight"];
        }
    }
    return $harvestBackYear;
}

//การ์ด - ต้นไม้ทั้งหมด
function getAllTree()
{
    $TREE = getLogPlanting();
    $x = count($TREE);
    $allTree = 0;
    for($i=1;$i<$x;$i++)
    {
        $allTree =$allTree + (int)$TREE[$i]["NumGrowth1"] + (int)$TREE[$i]["NumGrowth2"] - (int)$TREE[$i]["NumDead"];
    }
    return $allTree;
}

//การ์ด - พื้นที่ทั้งหมด
function getAllArea()
{
    $AREA = getAllSubFarm();
    $allArea = 0;
    $x = count($AREA);
    for($i=1;$i<$x;$i++)
    {
        $allArea = $allArea + (int)$AREA[$i]["AreaRai"];
    }
    return $allArea;
}

//การ์ด - ปริมาณที่ใส่ปุ๋ย
function getVolumeFertilising()
{
    $FERTILISING = getLogfertilising();
    $x = count($FERTILISING);
    $volumeFer = 0;
    for($i=1;$i<$x;$i++)
    {
        $volumeFer = $volumeFer + (float)$FERTILISING[$i]["Vol"];
    }
    return $volumeFer;
}

//ใช้ตอนค้นหา ปีที่มีการให้ปุ๋ย
function getYearFer(){
    $sql = "SELECT `dim-time`.`Year2` FROM `log-fertilising`
    INNER JOIN `dim-time`ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
    WHERE `log-fertilising`.`isDelete`= 0 
    GROUP BY `dim-time`.`Year2` 
    ORDER BY `dim-time`.`Year2` DESC";
    $YearFer = selectData($sql);
    return $YearFer;
}

//ตารางผลผลิตสวนปาล์มน้ำมันในระบบ หน้า OilPalmAreaVol.php
function getTableAllHarvest()
{
    $FARM = getFarm();
    $x = count($FARM);
    //นับจำนวนฟาร์มทั้งหมดแล้วเอาแต่ ID
    for($i=1;$i<$x;$i++)
    {
        $FARMIDNOTUNIQUE[$i] = $FARM[$i]['FMID'];
    }
    $FARMID = array_unique($FARMIDNOTUNIQUE);
    rsort($FARMID);
    $x = count($FARMID);
    $ALLHARVEST = null;

    //กำหนดค่าให้อาเรย์ของตาราง
    for($i=1;$i<$x;$i++)
    {
        $ALLHARVEST["$FARM[$i]"]["farmID"] = 0;
        $ALLHARVEST["$FARM[$i]"]["ownerName"] = "";
        $ALLHARVEST["$FARM[$i]"]["farmName"] = "";
        $ALLHARVEST["$FARM[$i]"]["subFarm"] = 0;
        $ALLHARVEST["$FARM[$i]"]["area"] = 0;
        $ALLHARVEST["$FARM[$i]"]["tree"] = 0;
        $ALLHARVEST["$FARM[$i]"]["weight"] = 0;
    }      
    
    //query ข้อมูลลงในอาเรย์ตาราง
    for($i=0;$i<$x;$i++)
    {
        $FMID = $FARMID[$i];
        $countSubFarm = (int)getCountSubFarmID($FMID);
        $countArea = (int)getAreaRai($FMID);
        $countTree = (int)getTreeID($FMID);
        $countWeight = (int)getHarvestID($FMID);
        $ownerName = (string)getOwnerName($FMID)[1]["FirstName"];
        $farmName = (string)getFarmFMID($FMID)[1]["Alias"];
        $ALLHARVEST[$i]["farmID"] = $FMID;
        $ALLHARVEST[$i]["ownerName"] = $ownerName;
        $ALLHARVEST[$i]["farmName"] = (string)$farmName;
        $ALLHARVEST[$i]["subFarm"] = (int)$countSubFarm;
        $ALLHARVEST[$i]["area"] = (int)$countArea;
        $ALLHARVEST[$i]["tree"] = (int)$countTree;
        $ALLHARVEST[$i]["weight"] = (int)$countWeight;
    }

    return $ALLHARVEST;
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
function getCountOhterPest()
{
    $sql = "SELECT COUNT(*) AS countOhterPest FROM `db-pestlist` WHERE `PTID` = 4";
    $countOhterPest = selectData($sql)[1]['countOhterPest'];
    return $countOhterPest;
}

function getOhterPest()
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
  } 
  else return null;
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



?>