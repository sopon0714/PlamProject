<?php

session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "DiseasesList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$page = "DiseasesList.php";
$modal = "DiseasesListModal.php";

$DATAPEST = getDiseases();
$num = getCountDiseases();

$str_title1 = "รายชื่อโรคพืช";
$str_num = "จำนวนโรคพืช";
$str_add = "เพิ่มโรคพืช";
$str_title2 = "รายละเอียดโรคพืช";
$str_danger = "อันตรายของโรคพืช";

$path_style = "../../picture/pest/disease/style/";
$path_danger = "../../picture/pest/disease/danger/";

include_once("../PestList/List.php");

$str_title_add = "เพิ่มชนิดโรคพืช";
$str_picture = "รูปโรคพืช";
$str_title_edit = "แก้ไขชนิดโรคพืช";
$str_placeholder = "ชื่อโรคพืช";

include_once("../PestList/Modal.php");
include_once("../../cropImage/cropImage.php");

?>
<script>
    folder = "disease";
    page = "DiseasesList.php";
</script>
<script src="../PestList/PestList.js"></script>
