<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "CutBranch";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$idformal = '';
$fullname = '';
$fyear = 0;
$fmin = -1;
$fmax = -1;
$fpro = 0;
$fdist = 0;
if (isset($_POST['s_year']))  $fyear = rtrim($_POST['s_year']);
if (isset($_POST['s_min']))  $fmin = rtrim($_POST['s_min']);
if (isset($_POST['s_max']))  $fmax = rtrim($_POST['s_max']);
if (isset($_POST['s_formalid']))  $idformal = rtrim($_POST['s_formalid']);
if (isset($_POST['s_province']))  $fpro     = $_POST['s_province'];
if (isset($_POST['s_distrinct'])) $fdist    = $_POST['s_distrinct'];
if (isset($_POST['s_name'])) {
    $fullname = rtrim($_POST['s_name']);
    $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
}
$DATA = getActivity($idformal, $fullname, $fpro, $fdist ,$fyear ,$fmin ,$fmax,1,0,0,'','');
$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
if($fyear == 0){
    $year = $currentYear;
}else{
    $year = $fyear;
}
$page = 1;
$limit = 10;
$start = (($page - 1) * $limit)+1;
$end = $start+$limit;
$times = $DATA[0]["numrow"];
if($times < $limit) $end = $times+1;
$pages = ceil($times/$limit);
if($times == 0){
    $start = 0;
    $pages = 1;
}
$head = "ล้างคอขวด";

?>
<?php include_once("../Activity/Activity.php"); ?>
<script src="../Activity/Activity.js"></script>