<?php

session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "OtherPestList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$page = "OtherPestList.php";
$modal = "OtherPestListModal.php";

$DATAPEST = getOtherPest();
$num = getCountOtherPest();

$str_title1 = "รายชื่อศัตรูพืชอื่นๆ";
$str_num = "จำนวนศัตรูพืชอื่นๆ";
$str_add = "เพิ่มศัตรูพืชอื่นๆ";
$str_title2 = "รายละเอียดศัตรูพืชอื่นๆ";
$str_danger = "อันตรายของศัตรูพืชอื่นๆ";

$path_style = "../../picture/pest/other/style/";
$path_danger = "../../picture/pest/other/danger/";

include_once("../PestList/List.php");

$str_title_add = "เพิ่มชนิดศัตรูพืชอื่นๆ";
$str_picture = "รูปศัตรูพืชอื่นๆ";
$str_title_edit = "แก้ไขชนิดศัตรูพืชอื่นๆ";
$str_placeholder = "ชื่อศัตรูพืชอื่นๆ";

include_once("../PestList/Modal.php");
include_once("../../cropImage/cropImage.php");

?>
<script>
    folder = "other";
    page = "OtherPestList.php";
</script>
<script src="../PestList/PestList.js"></script>
