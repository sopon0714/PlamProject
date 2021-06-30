<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Pest";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$idformal = '';
$fullname = '';
$fyear = 0;
$ftype = 0;
$fpro = 0;
$fdist = 0;

// $DATA = getPest($idformal, $fullname, $fpro, $fdist, $fyear, $ftype,0,0,'','');
$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
$PESTTYPE = getPestType();
//pagination
$page = 1;
$limit = 10;
$start = (($page - 1) * $limit)+1;
$end = $start+$limit;

$times = getCountPestAlarm();
if($times < $limit) $end = $times+1;
$pages = ceil($times/$limit);
//end pagination
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

.set-button {
    width: 30px !important;
}
</style>
<!-- pagination -->
<div hidden id="data_pest" idformal="<?= $idformal ?>" fullname="<?= $fullname ?>" fpro="<?= $fpro ?>"
    fdist="<?= $fdist ?>" fyear="<?= $fyear ?>" ftype="<?= $ftype ?>"></div>
<!-- end pagination -->

<div class="container bg">
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold"
                                style="color:<?= $color ?>;">ตรวจพบศัตรูพืช</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#"
                                    style="color:<?= $color ?>;">ตรวจพบศัตรูพืช</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <?php
        creatCard("card-color-one",   "จำนวนครั้งพบศัตรูพืช", $times . " ครั้ง", "waves");
        creatCard("card-color-two",   "จำนวนสวน",  getAreaLogFarm()[1]["NumFarm"] . " สวน " . getAreaLogFarm()[1]["NumSubFarm"] . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getAreaLogFarm()[1]["AreaRai"] . " ไร่ " . getAreaLogFarm()[1]["AreaNgan"] . " งาน", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getAreaLogFarm()[1]['NumTree'] . " ต้น", "format_size");
        ?>

    </div>

    <form action="Pest.php?isSearch=1" method="post">
        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header collapsed" id="headingOne" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"
                            style="cursor:pointer; background-color: <?= $color ?>; color: white;">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" class="card collapse 
                <?php
                if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                    echo "show";
                else
                    echo "";
                ?> 
                " aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card-header card-bg">
                        ตำแหน่งแปลงการตรวจพบศัตรูพืช
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-12">
                                <div id="map" style="width:auto;height:60vh;"></div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <span>ปี</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_year" name="s_year" class="form-control ">
                                            <option selected value=0>เลือกปี</option>
                                            <?php
                                            $yearCurrent = date('Y') + 543;
                                            for ($i = 0; $i <= 2; $i++, $yearCurrent--) {
                                                if ($fyear == $yearCurrent)
                                                    echo '<option value="' . $yearCurrent . '" selected>' . $yearCurrent . '</option>';
                                                else
                                                    echo '<option value="' . $yearCurrent . '">' . $yearCurrent . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span>ชนิด</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_type" name="s_type" class="form-control ">
                                            <option selected value=0>เลือกชนิด</option>
                                            <?php
                                            for ($i = 1; $i < sizeof($PESTTYPE); $i++) {
                                                if ($ftype == $PESTTYPE[$i]["PTID"])
                                                    echo '<option value="' . $PESTTYPE[$i]["PTID"] . '" selected>' . $PESTTYPE[$i]["TypeTH"] . '</option>';
                                                else
                                                    echo '<option value="' . $PESTTYPE[$i]["PTID"] . '">' . $PESTTYPE[$i]["TypeTH"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span>จังหวัด</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_province" name="s_province" class="form-control ">
                                            <option selected value=0>เลือกจังหวัด</option>
                                            <?php
                                            for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                                if ($fpro == $PROVINCE[$i]["AD1ID"])
                                                    echo '<option value="' . $PROVINCE[$i]["AD1ID"] . '" selected>' . $PROVINCE[$i]["Province"] . '</option>';
                                                else
                                                    echo '<option value="' . $PROVINCE[$i]["AD1ID"] . '">' . $PROVINCE[$i]["Province"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row  mb-2">
                                    <div class="col-12">
                                        <span>อำเภอ</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_distrinct" name="s_distrinct" class="form-control">
                                            <option selected value=0>เลือกอำเภอ</option>>
                                            <?php
                                            if ($fpro != 0) {
                                                for ($i = 1; $i < sizeof($DISTRINCT_PROVINCE); $i++) {
                                                    if ($fdist == $DISTRINCT_PROVINCE[$i]["AD2ID"])
                                                        echo '<option value="' . $DISTRINCT_PROVINCE[$i]["AD2ID"] . '" selected>' . $DISTRINCT_PROVINCE[$i]["Distrinct"] . '</option>';
                                                    else
                                                        echo '<option value="' . $DISTRINCT_PROVINCE[$i]["AD2ID"] . '">' . $DISTRINCT_PROVINCE[$i]["Distrinct"] . '</option>';
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-11">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>ชื่อเกษตรกร</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="text" class="form-control" id="s_name" name="s_name"
                                            <?php if ($fullname != '') echo 'value="' . $fullname . '"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>หมายเลขบัตรประชาชน</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="password" class="form-control input-setting" id="s_formalid"
                                            name="s_formalid"
                                            <?php if ($idformal != '') echo 'value="' . $idformal . '"'; ?>>
                                        <i class="fa fa-eye-slash eye-setting" id="hide1"></i>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <button type="submit" id="btn_pass"
                                            class="btn btn-success btn-sm form-control">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header card-header-table py-3">
            <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>; top: 25px; position: absolute; float:left;">
                ศัตรูพืชสวนปาล์มน้ำมันในระบบ</h6>
            <button type="button" id="add" style="float:right;" class="btn btn-success" data-toggle="tooltip">
                <i class="fas fa-plus"></i>เพิ่มการตรวจพบศัตรูพืช</button>
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
                    <table class="table table-bordered table-data tableSearch1" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ชื่อเกษตรกร</th>
                                <th>ชื่อสวน</th>
                                <th>ชื่อแปลง</th>
                                <th width="10%">พื้นที่ปลูก</th>
                                <th width="10%">จำนวนต้น</th>
                                <th>ชนิด</th>
                                <th width="10%">วันที่พบ</th>
                                <th width="20%">จัดการ</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ชื่อเกษตรกร</th>
                                <th>ชื่อสวน</th>
                                <th>ชื่อแปลง</th>
                                <th>พื้นที่ปลูก</th>
                                <th>จำนวนต้น</th>
                                <th>ชนิด</th>
                                <th>วันที่พบ</th>
                                <th>จัดการ</th>
                            </tr>
                        </tfoot>
                        <tbody id="body">
<!-- pagination -->
                            <tr id="show_loading">
                                <td colspan="8">
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
                                <td style="display: none"></td>
                                <td style="display: none"></td>
<!-- end pagination -->
                            </tr>
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
                                        <?php if($pages == 1) echo "hidden"; ?> id="lastpage"><a
                                            href="#" id="page<?php echo $i;?>" aria-controls="dataTable"
                                            data-dt-idx="<?php echo $pages;?>" tabindex="0"
                                            class="page-link"><?php echo $pages;?></a></li>
                                    <li class="paginate_button page-item next <?php if($pages == 1) echo "disabled"; ?> "
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

    <div class="Modal">

    </div>

</div>


<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("PestModal.php"); ?>
<?php include_once("../../cropImage/cropImage.php"); ?>

<script src="Pest.js"></script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th"
    async defer></script>