<?php

session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "WeedList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$page = "WeedList.php";
$modal = "WeedListModal.php";

$DATAPEST = getWeed();
$num = getCountWeed();

$str_title1 = "รายชื่อวัชพืช";
$str_num = "จำนวนวัชพืช";
$str_add = "เพิ่มวัชพืช";
$str_title2 = "รายละเอียดวัชพืช";
$str_danger = "อันตรายของวัชพืช";

$path_style = "../../picture/pest/weed/style/";
$path_danger = "../../picture/pest/weed/danger/";

include_once("../PestList/List.php");

$str_title_add = "เพิ่มชนิดวัชพืช";
$str_picture = "รูปวัชพืช";
$str_title_edit = "แก้ไขชนิดวัชพืช";
$str_placeholder = "ชื่อวัชพืช";

include_once("../PestList/Modal.php");
include_once("../../cropImage/cropImage.php");

?>
<script>
    folder = "weed";
    page = "WeedList.php";
</script>
<script src="../PestList/PestList.js"></script>
