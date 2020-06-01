<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "OilPalmAreaVol";
$currentYear = date("Y") + 543;

include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$fmid = $_GET['FMID'] ?? "";
$INFOFARM =  getDetailLogFarm($fmid);
$YEAR = getYear($fmid, true);
$LOGHarvest = getLogHarvest($fmid);
$Check = CheckHaveFarm($fmid)
?>
<link rel="stylesheet" href="../../css/insect admin/readmore.css">
<link rel="stylesheet" href="../../css/insect admin/stylePest.css">
<style>
    textarea {
        overflow-y: scroll;
        height: 100px;
        resize: vertical;
    }

    .img-reletive input[type=file] {
        cursor: pointer;
        width: 100px;
        height: 100px;
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .croppie-container .cr-boundary {
        width: 350px;
        height: 220px;
    }

    .upload-demo2 {
        width: 350px;
        height: 250px;
    }

    .img-gal {
        width: 150px;
        height: 100px;
        z-index: 5;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color:<?= $color ?>">รายละเอียดผลผลิตสวนปาล์มน้ำมัน</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="./OilPalmAreaVol.php">ผลผลิตสวนปาล์มน้ำมัน</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>">รายละเอียดผลผลิตสวนปาล์มน้ำมัน</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-xl-6 col-12">
            <div class="card">
                <div class="card-body" id="for_card">
                    <div class="row">
                        <?php
                        if ($INFOFARM[1]['iconFarm'] == "default.png") {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farm/0/defultFarm.png\" >";
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farm/{$INFOFARM[1]['FMID']}/{$INFOFARM[1]['iconFarm']}\" >";
                        }
                        ?>

                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อสวนปาล์ม : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOFARM[1]['NameFarm'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card">
                <div class="card-body" id="card_height">
                    <div class="row">
                        <?php
                        if ($INFOFARM[1]['iconFarmmer'] == "default.png") {
                            if ($INFOFARM[1]['Title'] == 1) {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/man.jpg\" >";
                            } else {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/woman.jpg\" >";
                            }
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/{$INFOFARM[1]['UFID']}/{$INFOFARM[1]['iconFarmmer']}\" >";
                        }
                        ?>

                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-3 col-3 text-right">
                            <span>เกษตรกร :</span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span> <?php echo $INFOFARM[1]['FullName'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <?php
        creatCard("card-color-one",   "จำนวนแปลง ", $INFOFARM[1]['NumSubFarm'] . " แปลง", "waves", 4);
        creatCard("card-color-two",   "จำนวนต้น", $INFOFARM[1]['NumTree']  . " ต้น", "dashboard", 4);
        creatCard("card-color-three",   "พื้นที่ทั้งหมด",  $INFOFARM[1]['AreaRai'] . " ไร่ " . $INFOFARM[1]['AreaNgan'] . " งาน " . $INFOFARM[1]['AreaWa'] . " วา ", "format_size", 4);
        ?>
    </div>
    <div class="row mt-4">
        <div id="maxyear" hidden maxyear="<?= $YEAR[1]['Year2'] ?>"></div>
        <div id="FMID" hidden FMID="<?= $fmid ?>"></div>
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <span>ผลผลิตต่อปี</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-12 PDY">
                            <canvas id="productYear" style="height:250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ผลผลิตรายปี -->
    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-10">
                            <span>รายละเอียดผลผลิตปี : </span>
                        </div>
                        <div class="col-2">
                            <select id="year" name="year" class="form-control">
                                <?php
                                for ($i = 1; $i < count($YEAR); $i++) {
                                    echo "<option value=\"{$YEAR[$i]['Year2']}\">{$YEAR[$i]['Year2']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body" id="card-productPerYear">
                    <div class="row">
                        <div class="col-xl-12 col-12">
                            <div class="row mb-2">
                                <div class="col-xl-6 col-12 PDM">
                                    <canvas id="productMonth" style="height:150px;"></canvas>
                                </div>
                                <div class="col-xl-6 col-12">
                                    <div class="card" style="height:250px;">
                                        <div class="card-header card-bg">
                                            <span>สรุปผลผลิตปี </span><span id="thisYear"><?php echo $YEAR[1]['Year2'] ?></span>
                                        </div>
                                        <div class="card-body" style=" margin-top: 20px;">
                                            <div class="row" style=" margin-top: 10px;">
                                                <div class="col-12">
                                                    <span>ผลผลิตทั้งหมด : <span id="sumweight"></span> กิโลกรัม</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12" style=" margin-top: 20px;">
                                                    <span>รายได้ทั้งหมด : <span id="sumprice"></span> บาท</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header card-bg">
                                            <div>
                                                <span>รายการเก็บผลผลิตต่อแปลง</span>
                                                <?php
                                                if ($Check) {
                                                    echo "<button type=\"button\" id=\"btn_add_product\" style=\"float:right;\" class=\"btn btn-success btn-sm\"><i class=\"fas fa-plus\"></i> เพิ่มผลผลิต</button>";
                                                }
                                                ?>

                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="col-xl-3 col-12">
                                                    <button type="button" id="btn_comfirm" class="btn btn-outline-success btn-sm"><i class="fas fa-file-excel"></i> Excel</button>
                                                    <button type="button" id="btn_comfirm" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</button>

                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-data  tableSearch " width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ชื่อแปลง</th>
                                                            <th>วันที่เก็บผลผลิต</th>
                                                            <th>ผลผลิต (ก.ก.)</th>
                                                            <th>ราคาต่อหน่วย(บาท)</th>
                                                            <th>ราคาสุทธิ์ (บาท)</th>
                                                            <th>จัดการ</th>

                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>ชื่อแปลง</th>
                                                            <th>วันที่เก็บผลผลิต</th>
                                                            <th>ผลผลิต (ก.ก.)</th>
                                                            <th>ราคาต่อหน่วย (บาท)</th>
                                                            <th>ราคาสุทธิ์ (บาท)</th>
                                                            <th>จัดการ</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        $strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
                                                        for ($i = 1; $i < count($LOGHarvest); $i++) {
                                                            echo "  <tr>
                                                                        <td>{$LOGHarvest[$i]['Name']}</td>
                                                                        <td class=\"text-right\">{$LOGHarvest[$i]['dd']} {$strMonthCut[$LOGHarvest[$i]['Month']]} {$LOGHarvest[$i]['Year2']}</td>
                                                                        <td class=\"text-right\">" . number_format($LOGHarvest[$i]['Weight'], 2, '.', ',') . "</td>
                                                                        <td class=\"text-right\">" . number_format($LOGHarvest[$i]['UnitPrice'], 2, '.', ',') . "</td>
                                                                        <td class=\"text-right\">" . number_format($LOGHarvest[$i]['TotalPrice'], 2, '.', ',') . "</td>
                                                                        <td style=\"text-align:center;\">
                                                                            <button type=\"button\" class=\"btn btn-info btn-sm btn-photo tt \"  lid=\"{$LOGHarvest[$i]['ID']}\" title=\"รูปภาพ\"><i class=\"fas fa-images\"></i></button>
                                                                            <button type=\"button\" class=\"btn btn-danger btn-sm delete tt\"   lid=\"{$LOGHarvest[$i]['ID']}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                                                                        </td>
                                                                    </tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- ผลผลิตรายปี -->
    </div>
    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <span>ผลผลิตต่อปี / ปริมาณปุ๋ยที่ใส่ต่อต้น</span>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-xl-12 col-12">
                            <canvas id="tradFer" style="height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include_once("./OilPalmAreaVolDetailModel.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("../../cropImage/cropImage.php"); ?>
<script src="./OilPalmAreaVolDetail.js"></script>