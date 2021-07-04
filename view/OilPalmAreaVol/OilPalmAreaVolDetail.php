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
$LOGHarvest = getLogHarvest($fmid,0,0);
$Check = CheckHaveFarm($fmid);
// pagination
$page = 1;
$limit = 10;
$times = $LOGHarvest[0]['numrow'];
if($times == 0) $start = 0;
$start = (($page - 1) * $limit)+1;
$end = $start+$limit;
if($times < $limit) $end = $times+1;
$pages = ceil($times/$limit);
if($times == 0){
    $start = 0;
    $pages = 1;
}
// end pagination
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
<div hidden id="data_search" fmid="<?= $fmid ?>" ></div>
<div class="container bg">
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายละเอียดผลผลิตสวนปาล์มน้ำมัน</span>
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
            <div class="card" style="height: 350px">
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
            <div class="card" style="height: 350px">
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
                    <span class="link-active font-weight-bold" style="color:<?= $color ?>;">ผลผลิตต่อปี</span>
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
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายละเอียดผลผลิตปี : </span>
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
                                                <div class="col-4 text-right">
                                                    <span>ผลผลิตทั้งหมด : </span>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <span id="sumweight"></span>
                                                </div>
                                                <div class="col-2 text-right">
                                                    <span>กิโลกรัม</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 text-right" style=" margin-top: 20px;">
                                                    <span>รายได้ทั้งหมด : </span>
                                                </div>
                                                <div class="col-3 text-right" style=" margin-top: 20px;">
                                                    <span id="sumprice"></span>
                                                </div>
                                                <div class="col-2 text-right" style=" margin-top: 20px;">
                                                    <span>บาท</span>
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
                                                <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายการเก็บผลผลิตต่อแปลง</span>
                                                <button type="button" id="btn_add_product" style="float:right;" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> เพิ่มผลผลิต</button>
                                            </div>
                                        </div>
                                         <!-- pagination -->
                                        <div id="size" hidden size="<?php echo $times; ?>"></div>
                                        <div id="CurrentPage" hidden CurrentPage="1"></div>
                                        <div id="pages" hidden pages="<?php echo $pages; ?>"></div>
                                        <!-- end pagination -->
                                        <div class="card-body">
                                            <!-- pagination add div -->
                                            <div>
                                                <!-- pagination -->
                                                <div class="col-12 table-responsive">
                                                    <div class="row" style="list-style: none !important;">
                                                        <div style="margin-top:5px;">Show</div>
                                                        <div style="margin-left:3px;">
                                                            <select name="dataTable_length" id="dataTable_length" aria-controls="dataTable"
                                                                class="custom-select custom-select-sm form-control form-control-sm">
                                                                <option value="10">10</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                                <option value="500">500</option>
                                                                <option value="1000">1,000</option>
                                                            </select>
                                                        </div>
                                                        <div style="margin-left:3px; margin-top:5px;">entries</div>
                                                    </div>
                                                </div>
                                                <!-- end pagination -->    
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-data  tableSearch1 " width="100%">
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
                                                        <tbody id="body">
                                                            <!-- pagination -->
                                                            <tr id="show_loading">
                                                                <td colspan="6">
                                                                    <center class="form-control" style="height: 110px; border: white;">
                                                                        <img src="./../Chart/chart/loading.gif" alt="Loading..."
                                                                            style="width: 70px; height: 70px; "><br>
                                                                        <label for="" style="font-size: small;">กำลังโหลดข้อมูล...</label>
                                                                    </center>
                                                                </td>
                                                                <td style="display: none"></td>
                                                                <td style="display: none"></td>
                                                                <td style="display: none"></td>
                                                                <td style="display: none"></td>
                                                                <td style="display: none"></td>
                                                            </tr>
                                                            <!-- end pagination -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                                 <!-- pagination -->
                                                <div class="col-12 table-responsive">
                                                    <div class="row" id="page_change">
                                                        <div class="col-sm-12 col-md-5" style="padding: inherit;">
                                                            <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                                                                <?php echo "Showing ".$start." to ".($end-1)." of ".$times." entries"?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-7" style="padding: inherit;">
                                                            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate"
                                                                style="float:right;">
                                                                <ul class="pagination">
                                                                    <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a
                                                                            href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0"
                                                                            class="page-link">Previous</a></li>
                                                                    <li class="paginate_button pagination_li page-use page-item active" id="page_1"
                                                                        page="1"><a href="#" aria-controls="dataTable" id="page1" data-dt-idx="1"
                                                                            tabindex="0" class="page-link">1</a></li>
                                                                    <li class="paginate_button page-item disabled" hidden id="dataTable_ellipsis1"><a
                                                                            href="#" aria-controls="dataTable" data-dt-idx="-1" tabindex="0"
                                                                            class="page-link">…</a></li>
                                                                    <?php
                                                                    for($i=2;$i<$pages;$i++){
                                                                        if($i < $pages){?>
                                                                    <li class="paginate_button pagination_li page-use page-item"
                                                                        <?php if($i > 5) echo "hidden"; ?> id="page_<?php echo $i;?>"
                                                                        page="<?php echo $i;?>"><a href="#" aria-controls="dataTable"
                                                                            id="page<?php echo $i;?>" data-dt-idx="<?php echo $i;?>" tabindex="0"
                                                                            class="page-link"><?php echo $i;?></a></li>

                                                                    <?php
                                                                        }
                                                                    } ?>
                                                                    <li class="paginate_button page-item disabled"
                                                                        <?php if($pages < 7) echo "hidden"; ?> id="dataTable_ellipsis2"><a href="#"
                                                                            aria-controls="dataTable" data-dt-idx="-2" tabindex="0"
                                                                            class="page-link">…</a></li>
                                                                    <li class="paginate_button page-item pagination_li" page="<?php echo $pages;?>"
                                                                        <?php if($pages == 1 || $pages == 0) echo "hidden"; ?> id="lastpage"><a
                                                                            href="#" id="page<?php echo $i;?>" aria-controls="dataTable"
                                                                            data-dt-idx="<?php echo $pages;?>" tabindex="0"
                                                                            class="page-link"><?php echo $pages;?></a></li>
                                                                    <li class="paginate_button page-item next <?php if($pages == 1 || $pages == 0) echo "disabled"; ?> "
                                                                        id="dataTable_next"><a href="#" aria-controls="dataTable" data-dt-idx="8"
                                                                            tabindex="0" class="page-link">Next</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end pagination -->
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

</div>



<?php include_once("./OilPalmAreaVolDetailModel.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("../../cropImage/cropImage.php"); ?>

<script src="./OilPalmAreaVolDetail.js"></script>