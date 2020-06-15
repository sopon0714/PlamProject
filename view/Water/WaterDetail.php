<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Water";
$fsid = $_GET['FSID'] ?? "";
$type = $_GET['Type'] ?? "";
if ($type == 1) {
    $title = "รายละเอียดปริมาณฝน";
    $title2 = "ข้อมูลปริมาณฝนในแปลง";
} else {
    $title = "รายละเอียดการให้น้ำ";
    $title2 = "ข้อมูลการให้น้ำในแปลง";
}
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$INFOSUBFARM =   getDetailLogSubFarm($fsid);
print_r($INFOSUBFARM);
?>

<!-- <link href='../Calendar/packages/core/main.css' rel='stylesheet' />
<link href='../Calendar/packages/daygrid/main.css' rel='stylesheet' />
<link href='../Calendar/packages/timegrid/main.css' rel='stylesheet' />
<link href='../Calendar/packages/list/main.css' rel='stylesheet' /> -->


<div class="container">

    <!------------ Start Head ------------>
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color: <?= $color ?>;"><?= $title ?></span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="../UserProfile/UserProfile.php">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="Water.php">การให้น้ำ</a>
                                <span> > </span>
                                <a class="link-path link-active" id="detail2" href="" style="color: <?= $color ?>;"><?= $title ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-xl-6 col-12">
            <div class="card " style="height: 350px">
                <div class="card-body" height="166px" id="for_card">
                    <div class="row">
                        <?php
                        if ($INFOSUBFARM[1]['iconSubfarm'] == "default.png") {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/subfarm/0/defultFarm.png\" >";
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/subfarm/{$INFOSUBFARM[1]['FMID']}/{$INFOSUBFARM[1]['iconSubfarm']}\" >";
                        }
                        ?>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อสวนปาล์ม : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOSUBFARM[1]['NameFarm'] ?></span>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อแปลง : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOSUBFARM[1]['NameSubfarm'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card" style="height: 350px">
                <div class="card-body" height="166px" id="card_height">
                    <div class="row">
                        <?php
                        if ($INFOSUBFARM[1]['iconFarmmer'] == "default.png") {
                            if ($INFOSUBFARM[1]['Title'] == 1) {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/man.jpg\" >";
                            } else {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/woman.jpg\" >";
                            }
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/{$INFOSUBFARM[1]['UFID']}/{$INFOSUBFARM[1]['iconFarmmer']}\" >";
                        }
                        ?>
                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-4 col-3 text-right">
                            <span>เกษตรกร : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span> <?php echo $INFOSUBFARM[1]['FullName'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------ Start Calender ------------>
    <div class="row mt-3">
        <div class="col-xl-12 col-12">
            <div class="card">
                <!------------ Head ------------>
                <div class="card-header card-bg">

                    <span><?= $title2 ?></span>
                    <button id="btn-modal1" type="button" style="float:right;" class="btn btn-success typeDefualt type1" data-toggle="modal" data-target="#modal-1"><i class="fas fa-plus"></i> เพิ่มปริมาณฝนตก</button>
                    <button id="btn-modal2" type="button" style="float:right;" class="btn btn-success typeDefualt type2" data-toggle="modal" data-target="#modal-2"><i class="fas fa-plus"></i> เพิ่มระบบให้น้ำ</button>

                </div>
                <div class="card-body">

                    <!------------  Tab Bar ------------>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">ปฏิทินข้อมูล</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#table" role="tab" aria-controls="profile" aria-selected="false">ตารางข้อมูล</a>
                        </li>
                    </ul>

                    <!------------ Body ------------>
                    <div class="tab-content" id="myTabContent" style="margin-top:20px;">

                        <!------------ Start Calender ------------>
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                        </div>

                        <!------------ Start Table ------------>
                        <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="profile-tab">

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once("./WaterDetailModal.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>
<script>
    $('.typeDefualt').hide();
    $('.type<?= $type ?>').show();
</script>
<script src='./test.js'></script>
<!-- <script src='../Calendar/packages/core/main.js'></script>
<script src='../Calendar/packages/interaction/main.js'></script>
<script src='../Calendar/packages/daygrid/main.js'></script>
<script src='../Calendar/packages/timegrid/main.js'></script>
<script src='../Calendar/packages/list/main.js'></script>
<script src='../Calendar/packages/bootstrap/main.js'></script> -->