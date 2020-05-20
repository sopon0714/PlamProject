<?php

session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "InsectList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$page = "InsectList.php";
$modal = "InsectListModal.php";

$DATAPEST = getInsect();
$num = getCountInsect();

$str_title1 = "รายชื่อแมลง";
$str_num = "จำนวนแมลง";
$str_add = "เพิ่มแมลง";
$str_title2 = "รายละเอียดแมลง";
$str_danger = "อันตรายของแมลง";

$path_style = "../../picture/pest/insect/style/";
$path_danger = "../../picture/pest/insect/danger/";

include_once("../PestList/List.php");

$str_title_add = "เพิ่มชนิดแมลง";
$str_picture = "รูปแมลง";
$str_title_edit = "แก้ไขชนิดแมลง";
$str_placeholder = "ชื่อแมลง";

include_once("../PestList/Modal.php");
include_once("../../cropImage/cropImage.php");

?>
<script>
    folder = "insect";
    page = "InsectList.php";
</script>
<script src="../PestList/PestList.js"></script>
