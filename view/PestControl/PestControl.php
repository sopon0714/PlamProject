<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "PestControl";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$idformal = '';
$fullname = '';
$fyear = 0;
$fmin = -1;
$fmax = -1;
$fpro = 0;
$fdist = 0;

$page = 1;
$limit = 10;
$start = (($page - 1) * $limit)+1;
$end = $start+$limit;
$DATA = getActivity($idformal, $fullname, $fpro, $fdist ,$fyear ,$fmin ,$fmax,2,0,0,'','');
$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
if($fyear == 0){
    $year = $currentYear;
}else{
    $year = $fyear;
}
$times = $DATA[0]["numrow"];
if($times < $limit) $end = $times+1;
$pages = ceil($times/$limit);

$head = "กำจัดวัชพืช";

?>
<?php include_once("../Activity/Activity.php"); ?>
<script src="../Activity/Activity.js"></script>
