<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Pest";
// $idUTLOG = $_SESSION[md5('LOG_LOGIN')];
?>

<?php include_once("../layout/LayoutHeader.php");
require_once("../../dbConnect.php");
$totalFarm = selectDataOne("SELECT COUNT(`db-farm`.`FMID`) AS totalFarm FROM `db-farm` ");
$totalSubFarm = selectDataOne("SELECT COUNT(`db-subfarm`.`FSID`) AS totalSubFarm FROM `db-subfarm` ");
$totalAreaRai = selectDataOne("SELECT SUM(`db-subfarm`.`AreaRai`) AS totalAreaRai FROM `db-subfarm` ");
$totalPalm = selectDataOne("SELECT (SUM(`log-planting`.`NumGrowth1`)+SUM(`log-planting`.`NumGrowth2`))-SUM(`log-planting`.`NumDead`) AS totalPalm FROM `log-planting` WHERE `log-planting`.`isDelete` = 0");
$totalPestAlarm = selectDataOne("SELECT COUNT(lp.isDelete) AS totalPestAlarm FROM `log-pestalarm` AS lp WHERE lp.isDelete = 0");

?>

<style>
    /* set Photo Pest */
    .set-images {
        width: 100%;
        height: 250px;
    }

    /* set margin photo Pestalarm */
    .margin-photo {
        margin-top: 25px;
    }

    /* padding button search */
    .padding {
        padding-top: 10px;
    }
</style>

<div class="container">

    <!------------ Start Head ------------>
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color:<?= $color ?>;">ศัตรูพืช</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="" style="color:<?= $color ?>;"> ศัตรูพืช</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------ Start Card ------------>
    <div class="row">
        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-one shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">จำนวนครั้งพบศัตรูพืช</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="cardPestAlarm"><?php echo number_format($totalPestAlarm['totalPestAlarm']) . " ครั้ง" ?></span></div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">panorama_vertical</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-two shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">จำนวนสวน/แปลง</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalFarm['totalFarm'] . " สวน / " . $totalSubFarm['totalSubFarm'] . " แปลง"; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">filter_vintage</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-three shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">พื้นที่ทั้งหมด</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($totalAreaRai['totalAreaRai']); ?> ไร่</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">dashboard</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-four shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">จำนวนต้นไม้ทั้งหทด</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($totalPalm['totalPalm']); ?> ต้น</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">format_size</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------ Start Serch ------------>
    <div class="row">
        <div class="col-xl-12 col-12">
            <div id="accordion">
                <div class="card">
                    <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="cursor:pointer; background-color: <?= $color ?>; color: white;">
                        <div class="row">
                            <div class="col-3">
                                <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="collapseOne" class="card collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-header card-bg">
                    ตำแหน่งศัตรูพืชสวนปาล์มน้ำมัน
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-12">
                            <div id="map" style="width:auto; height:75vh;"></div>
                        </div>

                        <div class="col-xl-6 col-12">

                            <div class="row">
                                <div class="col-12">
                                    <span>ปี</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <select id="year" class="form-control">
                                        <?php
                                        $yearCurrent = date('Y') + 543;
                                        echo "<option value='$yearCurrent' selected>$yearCurrent</option>";
                                        for ($i = 0, $yearCurrent--; $i < 2; $i++, $yearCurrent--)
                                            echo "<option value='$yearCurrent'>$yearCurrent</option>";
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
                                    <select id="pest" class="js-example-basic-single">
                                        <option disabled selected value='null'>เลือกชนิดศัตรูพืช</option>
                                        <option value="1">แมลงศัตรูพืช</option>
                                        <option value="2">โรคพืช</option>
                                        <option value="3">วัชพืช</option>
                                        <option value="4">ศัตรูพืชอื่นๆ</option>
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
                                    <select id="province" class="js-example-basic-single">
                                        <option disabled selected value="null">เลือกจังหวัด</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <span>อำเภอ</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <select id="amp" class="js-example-basic-single">
                                        <option disabled selected value="null">เลือกอำเภอ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <span>ชื่อเกษตรกร</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <input type="text" class="form-control" id="name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <span>หมายเลขบัตรประชาชน</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <input type="password" class="form-control input-setting" id="idcard">
                                    <i class="far fa-eye-slash eye-setting"></i>
                                </div>
                            </div>

                            <div class="row mb-2 padding">
                                <div class="col-12">
                                    <button type="button" id="btn_search" class="btn btn-success btn-sm form-control">ค้นหา</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------ Start Table ------------>
    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div>
                        <span>ศัตรูพืชสวนปาล์มน้ำมันในระบบ</span>
                        <span style="float:right;">ปี <?php echo date('Y') + 543; ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <button type="button" id="btn-modal4" style="float:right;" class="btn btn-success" data-toggle="modal" data-target="#modal-4"><i class="fas fa-plus"></i>เพิ่มการตรวจพบศัตรูพืช</button>
                        <table id="example" class="table table-bordered table-striped table-hover table-data">
                            <thead>
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
                            <tbody id="fetchDataTable">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------ Start Modal ------------>

    <div class="modal fade" id="modal-1" role="dialog">
        <div class="modal-dialog modal-xl " role="document">
            <!-- modal-dialog-scrollable -->
            <div class="modal-content">
                <div class="modal-header header-modal">
                    <h4 class="modal-title">ข้อมูลลักษณะทั่วไปของศัตรูพืช</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="infoModalBody">
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: center;">
                            <div style="text-align: center;">
                                <img id="img-icon" class="img-radius" height="180px" width="180px" />
                            </div>
                            <hr>
                            <h4 id="PAlias"></h4>
                            <h4 id="PName"></h4>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <h4>ลักษณะ</h4>
                            <textarea id="Charactor" rows="10" cols="40" style="margin-bottom:20px; max-width: 270px;" readonly></textarea>
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <h4>อันตราย</h4>
                            <textarea id="Danger" rows="10" cols="40" style="margin-bottom:20px; max-width: 270px;" readonly>ข้อมูลอันตราย</textarea>
                            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-2" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal">
                    <h4 class="modal-title">รูปภาพศัตรูพืช</h4>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row margin-gal" id="fetchPhoto">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-3" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal">
                    <h4 class="modal-title">ข้อมูลสำคัญของศัตรูพืช</h4>
                </div>
                <div class="modal-body" id="noteModalBody">
                    <span id="Note"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-4" role="dialog">
        <form method="post" enctype="multipart/form-data" id="form">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header header-modal">
                        <h4 class="modal-title">เพิ่มการตรวจพบศัตรูพืช</h4>
                    </div>
                    <div class="modal-body">
                        <div class="main">
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>วันที่</span>
                                </div>
                                <div class="col-xl-9 col-12">

                                    <div class="input-group">
                                        <input type="text" class="form-control" data-toggle="datepicker" id="p_date" name="p_date">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากสวน</span>
                                </div>
                                <div class="col-xl-9 col-12">
                                    <select class="js-example-basic-single" id="p_farm" name="p_farm">

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากแปลง</span>
                                </div>
                                <div class="col-xl-9 col-12">
                                    <select class="js-example-basic-single" id="p_subfarm" name="p_subfarm">

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ชนิดศัตรูพืช</span>
                                </div>
                                <div class="col-xl-9 col-12">
                                    <select class="js-example-basic-single" id="p_rank" name="p_rank">

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ศัตรูพืช</span>
                                </div>
                                <div class="col-xl-9 col-12">
                                    <select class="js-example-basic-single" id="p_pest" name="p_pest">

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ลักษณะ</span>
                                </div>
                                <div class="col-xl-9 col-12">
                                    <textarea name="p_note" class="form-control" id="p_note" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>รูปภาพ</span>
                                </div>
                                <div class="col-xl-9 col-12">
                                    <div class="grid-img-multiple" id="p_insert_img">

                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="pestAlarmID" id="pestAlarmID" value="0" />
                        </div>
                        <div class="crop-img">
                            <center>
                                <div id="upload-demo" class="center-block"></div>
                            </center>
                        </div>
                        <input type="hidden" id="hidden_id" name="request" value="insert" />
                        <div class="modal-footer normal-button">
                            <button id="m_success" type="button" class="btn btn-success">ยืนยัน</button>
                            <button id="m_not_success" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        </div>
                        <div class="modal-footer crop-button">
                            <button type="button" class="btn btn-success btn-crop">ยืนยัน</button>
                            <button type="button" class="btn btn-danger btn-cancel-crop">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<?php include_once("../layout/LayoutFooter.php"); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&language=th" async defer></script>

<script>
    $(document).ready(function() {

        $('#p_date').datepicker({
            autoHide: true,
            zIndex: 2048,
            language: 'th-TH',
            format: 'dd-mm-yyyy'
        });

        $('.js-example-basic-single').select2();
        $('.js-example-basic-single').on('select2:open', function(e) {
            $(this).next().addClass("border-from-control");
        });
        $('.js-example-basic-single').on('select2:close', function(e) {
            $(this).next().removeClass("border-from-control");
        });

        loadProvince();
        loadData();
        loadFarm();
    });

    let dataProvince;
    let dataDistrinct;
    let numProvince = 0;
    let ID_Province = null;
    let ID_Distrinct = null;

    let dataFarm;
    let dataSubFarm;
    let ID_Farm = null;
    let ID_SubFarm = null;

    let dataPest;
    let ID_TypePest = null;
    let ID_Pest = null;
    let type = ["insect", "disease", "weed", "other"];
    let sumPestAlarm = <?php echo $totalPestAlarm['totalPestAlarm']; ?>;

    let data;

    let year = null;
    let name = null;
    let passport = null;

    let wait_time = 0;
    let counter = 0;
    let timer;

    /*<! ----------------------------------------------------- Function Mixs All  ----------------------------------------------------------- !>*/
    // Format NUmber
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    // Start count countdown
    function startCounting() {
        timer = window.setTimeout("countDown()", 500);
        window.status = counter; // show the initial value
    }
    // Start countdown && Remove Modal and HoldOn
    function countDown() {
        window.status = --counter;
        if (counter == 0) {
            window.clearTimeout(timer);
            timer = null;
            $('#modal-4').modal('toggle');
            HoldOn.close();
            wait_time = 0;
        } else {
            if ((wait_time - counter) % 2 == 0)
                $('#holdon-message').children().text("กำลังดำเนินการ จะเสร็จสิ้นภายใน " + counter / 2 + " วิ")
            // console.log(+" *-*\n")
            timer = window.setTimeout("countDown()", 500);
        }
    }
    // LoadMap
    function initMap() {
        // The location of Uluru
        //alert(coordinate[0].lat);
        var marker = {
            lat: 12.815300,
            lng: 101.490997
        };

        // The map, centered at Uluru
        var map = new google.maps.Map(
            document.getElementById('map'), {
                zoom: 5.5,
                center: marker
            });
        // Construct the polygon.
        var area = new google.maps.Polygon({
            // paths: zone,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });


        let i, info;
        // console.log("*-*-*-* " + data);
        for (i = 0; i < data.length; i++) {
            // console.log("SET MAP ");
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(data[i].Latitude, data[i].Longitude),
                title: data[i].subFName,
                map: map
            });
            info = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    info.setContent("ชื่อแปลง : " + data[i].subFName);
                    info.open(map, marker);
                }
            })(marker, i));
        }
        area.setMap(map);
    }
    // โหลดจังหวัด
    function loadProvince() {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataProvince = JSON.parse(this.responseText);
                let text = "";
                for (i in dataProvince) {
                    text += ` <option value="${dataProvince[i].AD1ID}">${dataProvince[i].Province}</option> `
                    numProvince++;
                }
                $("#province").append(text);
            }
        };
        xhttp.open("GET", "./loadProvince.php", true);
        xhttp.send();
    }
    // โหลดอำเภอ
    function loadDistrinct(id) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataDistrinct = JSON.parse(this.responseText);
                let text = "<option disabled selected value='null'>เลือกอำเภอ</option>";
                for (i in dataDistrinct) {
                    text += ` <option value="${dataDistrinct[i].AD2ID}">${dataDistrinct[i].Distrinct}</option> `
                }
                $("#amp").append(text);
            }
        };
        xhttp.open("GET", "./loadDistrinct.php?id=" + id, true);
        xhttp.send();
    }
    // โหลด Farm
    function loadFarm() {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataFarm = JSON.parse(this.responseText);
                let text = "<option disabled selected value='-1'>เลือกสวน</option>";
                for (i in dataFarm) {
                    text += ` <option value="${dataFarm[i].FMID}">${dataFarm[i].Name}</option> `
                }
                $("#p_farm").html(text);
            }
        };
        xhttp.open("GET", "./loadFarm.php", true);
        xhttp.send();
    }
    // โหลด SubFarm
    function loadSubFarm(farm, ID) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataSubFarm = JSON.parse(this.responseText);
                let text = "<option value='-1' disabled selected>เลือกแปลง</option>";
                for (i in dataSubFarm) {
                    text += ` <option value="${dataSubFarm[i].FSID}">${dataSubFarm[i].Name}</option> `
                }
                $(ID).html(text);
            }
        };
        xhttp.open("GET", "./loadSubFarm.php?farm=" + farm, true);
        xhttp.send();
    }
    // โหลด Pest
    function loadPest(path, id, ID, TEXT) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataPest = JSON.parse(this.responseText);
                let text = "<option value='-1' disabled>เลือกศัตรูพืช</option>";
                for (i in dataPest) {
                    text += ` <option value="${dataPest[i].ID}">${dataPest[i].Name}</option> `
                }
                $(ID).html(text);
                if (TEXT == "edit")
                    $(ID).val(data[id].dbpestLID).trigger('change');
                else
                    $(ID).val(-1).trigger('change');
            }
        };
        xhttp.open("GET", "./loadPest.php?id=" + path, true);
        xhttp.send();
    }
    // โหลด Photo [log-pest]
    function loadPhoto_LogPest(PID, TYPE1, TYPE2, ID, numPIC) {
        let path = "picture/Pest/" + TYPE1 + "/" + TYPE2 + "/" + PID;
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let data1 = '';
                if (this.responseText != '')
                    data1 = JSON.parse(this.responseText);

                let text = `<ol class="carousel-indicators">
                                <li data-target="${ID}" data-slide-to="0" class="active"></li>`;
                for (i = 1; i < numPIC; i++)
                    text += `<li data-target="${ID}" data-slide-to="${i}"></li>`;
                text += `</ol>`;
                text += `<div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="set-images" src="${"../../"+path+"/"+data1[0]}">
                                    </div>`;
                for (i = 1; i < numPIC; i++)
                    text += `<div class="carousel-item">
                                <img class="set-images" src="${"../../"+path+"/"+data1[i]}">
                             </div>`
                text += `</div>
                        <a class="carousel-control-prev" href="${ID}" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="${ID}" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>`;
                $(ID).html(text);
            }
        };
        xhttp.open("POST", "./scanDir.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`path=${path}`);
    }
    // โหลด Photo Gallary [log-pestAlarm] -> PICS
    function loadPhoto_LogPestAlarm(PICS) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let data1 = JSON.parse(this.responseText);
                let text = "";
                for (i in data1) {
                    text += `<a href="${"../../"+PICS+"/"+data1[i]}" class="col-xl-3 col-3 margin-photo" target="_blank">
                                <img src="${"../../"+PICS+"/"+data1[i]}"" class="img-gal">
                            </a>`
                }
                $("#fetchPhoto").html(text);
            }
        };
        xhttp.open("POST", "./scanDir.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`path=${PICS}`);
    }
    // โหลด Photo Edit [log-pestAlarm] -> PICS
    function loadPhoto_LogPestAlarm2(PICS, id) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let data1 = '';
                if (this.responseText != '')
                    data1 = JSON.parse(this.responseText);
                let text = ``;
                for (i in data1) {
                    text += `<div class="card" width="70px" hight="70px">
                                    <div class="card-body" style="padding:0;">
                                        <img class="img_scan" src = "${"../../"+PICS+"/"+data1[i]}" id="${data1[i].slice(0,12)}" width="100%" hight="100%" />
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-warning edit-img">แก้ไข</button>
                                        <button type="button" class="btn btn-danger delete-img">ลบ</button>
                                    </div>
                                </div>`
                    if (data1[i].slice(0, 1) > count)
                        count = data1[i].slice(0, 1);
                }
                text += `<div class="img-reletive">
                            <img src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
                            <input type="file" class="form-control" id="p_photo" name="p_photo[]" accept=".jpg,.png" multiple>
                        </div>`;
                $(id).html(text);
                console.log(count)
            }
        };
        xhttp.open("POST", "./scanDir.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`path=${PICS}`);
    }
    // โหลด Datatable 
    function loadData() {
        let xhttp = new XMLHttpRequest();
        $('#example').DataTable().destroy();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                data = JSON.parse(this.responseText);
                let text = "";
                for (i in data) {
                    text += `<tr>
                            <td class="text-left">${data[i].Name}</td>
                            <td class="text-left">${data[i].FName}</td>
                            <td class="text-left">${data[i].subFName}</td>
                            <td class="text-right">${formatNumber(data[i].SumArea)}</td>
                            <td class="text-right">${formatNumber(data[i].SumNumTree)}</td>
                            <td style="text-align:center;">${data[i].TypeTH}</td>
                            <td class="text-right">${data[i].Date}</td>
                            <td style="text-align:center;">
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-warning btn-sm btn-edit" data-toggle="modal" data-target="#modal-4"><i class="fas fa-edit"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-success btn-sm btn-Pest" data-toggle="modal" data-target="#modal-1"><i class="fas fa-bars"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-info btn-sm btn-photo" data-toggle="modal" data-target="#modal-2"><i class="far fa-images"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-primary btn-sm btn-note" data-toggle="modal" data-target="#modal-3"><i class="far fa-sticky-note"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-danger btn-sm btn-delete"><i class="far fa-trash-alt"></i></button>
                            </td>
                        </tr>`;
                }
                $("#fetchDataTable").html(text);
                setOption_DataTable("example", "PDF", "EXCEL", 1, 0, 6);
                initMap();
            }
        };
        xhttp.open("GET", "./loadPestAlarm.php", true);
        xhttp.send();
    }
    // Search And Fetch Datatable 
    function search_Fetch_Datatable() {
        let xhttp = new XMLHttpRequest();
        $('#example').DataTable().destroy();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                data = JSON.parse(this.responseText);
                let text = "";
                for (i in data) {
                    text += `<tr>
                            <td class="text-left">${data[i].Name}</td>
                            <td class="text-left">${data[i].FName}</td>
                            <td class="text-left">${data[i].subFName}</td>
                            <td class="text-right">${formatNumber(data[i].SumArea)}</td>
                            <td class="text-right">${formatNumber(data[i].SumNumTree)}</td>
                            <td style="text-align:center;">${data[i].TypeTH}</td>
                            <td class="text-right">${data[i].Date}</td>
                            <td style="text-align:center;">
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-warning btn-sm btn-edit" data-toggle="modal" data-target="#modal-4"><i class="fas fa-edit"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-success btn-sm btn-Pest" data-toggle="modal" data-target="#modal-1"><i class="fas fa-bars"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-info btn-sm btn-photo" data-toggle="modal" data-target="#modal-2"><i class="far fa-images"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-primary btn-sm btn-note" data-toggle="modal" data-target="#modal-3"><i class="far fa-sticky-note"></i></button>
                                <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-danger btn-sm btn-delete"><i class="far fa-trash-alt"></i></button>
                            </td>
                        </tr>`;
                }
                $("#fetchDataTable").html(text);
                setOption_DataTable("example", "PDF", "EXCEL", 1, 0, 6);
                initMap();
            }
        };
        xhttp.open("POST", "./search_Fetch_Datatable.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`year=${year}&ID_Pest=${ID_Pest}&ID_Province=${ID_Province}&ID_Distrinct=${ID_Distrinct}&name=${name}&passport=${passport}`);

    }


    /*<! ----------------------------------------------------- Event Mixs All ----------------------------------------------------------- !>*/


    // Start Event Select_จังหวัด 
    $("#province").on('change', function() {
        $("#amp").empty();
        let x = document.getElementById("province").value;
        for (let i = 0; i < numProvince; i++)
            if (dataProvince[i].AD1ID == x) {
                ID_Province = x;
                ID_Distrinct = null;
                loadDistrinct(dataProvince[i].AD1ID);
            }
    });
    // Start Event Select_สวน
    $("#p_farm").on('change', function() {
        $("#p_subfarm").empty();
        let x = document.getElementById("p_farm").value;
        ID_Farm = x;
        loadSubFarm(x, "#p_subfarm");
    });
    // Start Event Select_แปลง
    $("#p_subfarm").on('change', function() {
        let ID_SubFarm = document.getElementById("p_subfarm").value;
    });
    // Start Event Select_TypePest
    $("#p_rank").on('change', function() {
        $("#p_pest").empty();
        let x = document.getElementById("p_rank").value;
        ID_TypePest = x;
        loadPest(x, 0, "#p_pest", "");
    });



    // Start Event Create Modal && LoadFarm
    $("#btn-modal4").on('click', function() {
        count = 0;
        $('#p_date').datepicker("setDate", "pick");
        // let current_datetime = new Date()
        // let formatted_date = (current_datetime.getDate() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getFullYear());
        // $('#p_date').val(formatted_date);
        loadFarm();

        $('#p_subfarm').html("<option disabled selected>เลือกแปลง</option>");
        $('#p_rank').html(`<option disabled selected>เลือกชนิดศัตรูพืช</option>
                            <option value="1">แมลงศัตรูพืช</option>
                            <option value="2">โรคพืช</option>
                            <option value="3">วัชพืช</option>
                            <option value="4">ศัตรูพืชอื่นๆ</option>`);
        $('#p_pest').html("<option disabled selected>เลือกศัตรูพืช</option>");
        document.getElementById("p_note").value = "";
        $('#p_insert_img').html(`<div class="img-reletive">
                                    <img src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
                                    <input type="file" class="form-control" id="p_photo" name="p_photo[]" accept=".jpg,.png" multiple>
                                </div>`);
        $('#hidden_id').attr('value', "insert");

    });
    // Start Submit Create Modal
    $(document).on('click', '#m_success', function() {
        let form = new FormData($('#form')[0]);
        let request = $(this).parent().prev().attr('value');
        let status;

        // Save Data in Sql
        $.ajax({
            type: "POST",
            data: form,
            url: "insert_edit.php",
            cache: false,
            async: false,
            contentType: false,
            processData: false,
            success: function(result) {
                // location.reload();
                status = result;
                console.log(result)
                loadData();
                document.getElementById("cardPestAlarm").textContent = ++sumPestAlarm + " ครั้ง";
            }
        });

        // Find loop count img base64
        $('.img_scan').each(function(i, obj) {
            let base64 = $(this).attr('src');
            if (base64.slice(0, 10) == "data:image")
                wait_time++;
        });

        // Save Image in directory
        if (status != -1) {

            // Create Loader
            if (wait_time != 0) {
                counter = wait_time;
                HoldOn.open({
                    theme: 'sk-fading-circle',
                    message: "<h4> กำลังดำเนินการ จะเสร็จสิ้นภายใน " + wait_time / 2 + " วิ</h4>"
                });
                startCounting();
            }

            $('.img_scan').each(function(i, obj) {
                let form2 = new FormData();
                let base64 = $(this).attr('src');
                let src_id = $(this).attr('id');
                form2.append('base64', base64);
                form2.append('request', request);
                form2.append('idCurrent', status);
                form2.append('idPhoto', src_id);
                $.ajax({
                    type: "POST",
                    data: form2,
                    url: "save_Photo.php",
                    // async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        console.log(result)
                    }
                });
                // sleep(100);
            });

            if (wait_time == 0)
                $('#modal-4').modal('toggle');
        }

    });


    // Start Edit Botton
    $(document).on('click', '.btn-edit', function() {
        count = 0;
        let id = $(this).attr('id');
        let text = "";
        let date = data[id].Date.split("-")
        $('#p_date').val(date[0] + "-" + date[1] + "-" + (parseInt(date[2]) + 543));

        for (i in dataFarm)
            text += ` <option value="${dataFarm[i].FMID}">${dataFarm[i].Name}</option> `;
        $("#p_farm").html(text);
        $('#p_farm').val(data[id].FID).trigger('change');

        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataSubFarm = JSON.parse(this.responseText);
                let text = "";
                for (i in dataSubFarm)
                    text += ` <option value="${dataSubFarm[i].FSID}">${dataSubFarm[i].Name}</option> `
                $("#p_subfarm").html(text);
                $('#p_subfarm').val(data[id].SFID).trigger('change');
            }
        };
        xhttp.open("GET", "./loadSubFarm.php?farm=" + data[id].FID, true);
        xhttp.send();

        $('#p_rank').html(`<option value="1">แมลงศัตรูพืช</option>
                            <option value="2">โรคพืช</option>
                            <option value="3">วัชพืช</option>
                            <option value="4">ศัตรูพืชอื่นๆ</option>`);
        $('#p_rank').val(data[id].dbpestTID).trigger('change');

        loadPest(data[id].dbpestTID, id, "#p_pest", "edit");

        document.getElementById("p_note").value = data[id].Note;

        loadPhoto_LogPestAlarm2(data[id].PICS, "#p_insert_img");

        $('#hidden_id').attr('value', "edit");
        $('#pestAlarmID').attr('value', data[id].ID);
    });
    // Start Detail Pest Botton
    $(document).on('click', '.btn-Pest', function() {
        let id = $(this).attr('id');
        let nameType = type[data[id].dbpestTID - 1];
        document.getElementById("PAlias").innerHTML = "ชื่อ : " + data[id].PAlias;
        document.getElementById("PName").innerHTML = "ชื่อทางการ : " + data[id].PName;
        document.getElementById("Charactor").innerHTML = data[id].Charactor;
        document.getElementById("Danger").innerHTML = data[id].Danger;
        document.getElementById("img-icon").src = "../../icon/pest/" + data[id].dbpestLID + "/" + data[id].Icon;

        loadPhoto_LogPest(data[id].dbpestLID, nameType, "danger", "#carouselExampleIndicators", data[id].NumPicDanger);
        loadPhoto_LogPest(data[id].dbpestLID, nameType, "style", "#carouselExampleIndicators2", data[id].NumPicChar);
    });
    // Start Photo PestAlarm Botton
    $(document).on('click', '.btn-photo', function() {
        let id = $(this).attr('id');
        loadPhoto_LogPestAlarm(data[id].PICS);
    });
    // Start Note Botton
    $(document).on('click', '.btn-note', function() {
        let id = $(this).attr('id');
        document.getElementById("Note").innerHTML = data[id].Note;
    });
    // Start Delete Botton
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).attr('id');
        let pid = $(this).attr('Pid');
        swal({
                title: "ยืนยันการลบข้อมูล",
                // text: `Id_diary : ${id} ?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-secondary",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
            },
            function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: "ลบข้อมูลสำเร็จ",
                        type: "success",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "ตกลง",
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $('#example').DataTable().destroy();
                            let xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    data.splice(id, 1);
                                    let text = "";
                                    for (i in data) {
                                        text += `<tr>
                                                <td class="text-left">${data[i].Name}</td>
                                                <td class="text-left">${data[i].FName}</td>
                                                <td class="text-left">${data[i].subFName}</td>
                                                <td class="text-right">${formatNumber(data[i].SumArea)}</td>
                                                <td class="text-right">${formatNumber(data[i].SumNumTree)}</td>
                                                <td style="text-align:center;">${data[i].TypeTH}</td>
                                                <td class="text-right">${data[i].Date}</td>
                                                <td style="text-align:center;">
                                                    <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-warning btn-sm btn-edit" data-toggle="modal" data-target="#modal-4"><i class="fas fa-edit"></i></button>
                                                    <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-success btn-sm btn-Pest" data-toggle="modal" data-target="#modal-1"><i class="fas fa-bars"></i></button>
                                                    <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-info btn-sm btn-photo" data-toggle="modal" data-target="#modal-2"><i class="far fa-images"></i></button>
                                                    <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-primary btn-sm btn-note" data-toggle="modal" data-target="#modal-3"><i class="far fa-sticky-note"></i></button>
                                                    <button type="button" id='${i}' Pid='${data[i].ID}' class="btn btn-danger btn-sm btn-delete"><i class="far fa-trash-alt"></i></button>
                                                </td>
                                            </tr>`;
                                    }
                                    $("#fetchDataTable").html(text);
                                    setOption_DataTable("example", "PDF", "EXCEL", 1, 0, 6);
                                    document.getElementById("cardPestAlarm").textContent = --sumPestAlarm + " ครั้ง";
                                    initMap();
                                }
                            };
                            xhttp.open("POST", "./deletePest.php", true);
                            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhttp.send(`ID=${pid}`);
                        }
                    });
                } else {

                }
            });
    });


    /*<! ----------------------------------------------------- Function && Event All Photo ----------------------------------------------------------- !>*/


    var count = 0;
    var idImg;
    $('.crop-img').hide()
    $('.crop-button').hide()
    // Start Insert Photo
    $(document).on('change', '#p_photo', function() {
        img_Preview_Upload(this, '#p_insert_img');
    });
    // Show Preview Photo --> After Insert
    function img_Preview_Upload(input, Target) {
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                // console.log((input.files[i].size / 1024) + " KB == " + (input.files[i].size / 1024 * 8 / 3) + " KB\n")
                var reader = new FileReader();
                let srcEncoded;
                reader.onload = function(event) {

                    let img = new Image();
                    img.src = event.target.result;
                    img.onload = function(e) {

                        let elem = document.createElement('canvas')
                        elem.width = e.target.naturalWidth - (e.target.naturalWidth * 0 / 100);
                        elem.height = e.target.naturalHeight - (e.target.naturalHeight * 0 / 100);
                        let ctx = elem.getContext('2d');
                        ctx.drawImage(e.target, 0, 0, elem.width, elem.height);
                        srcEncoded = ctx.canvas.toDataURL('image/jpeg');
                        $(Target).prepend(`<div class="card" width="70px" hight="70px">
                                            <div class="card-body" style="padding:0;">
                                                <img class="img_scan" src = "${srcEncoded}" id = "${++count}_CropPhoto" width="100%" hight="100%" />
                                            </div>
                                            <div class="card-footer">
                                                <button type="button" class="btn btn-warning edit-img">แก้ไข</button>
                                                <button type="button" class="btn btn-danger delete-img">ลบ</button>
                                            </div>
                                        </div>`)

                    }
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
        $(input).val('');
    }
    // Start Delete Photo
    $(document).on('click', '.delete-img', function() {
        // $(this).parent().parent().remove()
        $(this).parent().parent().attr('style', 'display:none')
        $(this).parent().prev().children().attr('src', '')
    });
    // Start Edit-Crop Photo
    $(document).on('click', '.edit-img', function() {
        let me = $(this).parent().prev().children().attr('src');
        let img = new Image();
        idImg = $(this).parent().prev().children().attr('id');
        img.src = document.getElementById(idImg).src;
        $('.main').hide();
        $('.normal-button').hide();
        $('.crop-img').show();
        $('.crop-button').show();

        // console.log(img.width + " x " + img.height + "\n");

        let UC = $('#upload-demo').croppie({
            viewport: {
                width: 200,
                height: img.height * (200 / img.width),
            },
            boundary: {
                width: 250,
                height: img.height * (250 / img.width),
            },
            enableResize: true,
            enableExif: true
        });
        UC.croppie('bind', {
            url: me
        }).then(function() {
            // console.log('jQuery bind complete');
        });
    });
    // Start Submit Crop Photo
    $(document).on('click', '.btn-crop', function(ev) {
        $('#upload-demo').croppie('result', {
                type: 'canvas',
                size: 'original',
                format: 'jpeg'
            })
            .then(function(r) {
                $('.main').show()
                $('.normal-button').show()
                $('.crop-img').hide()
                $('.crop-button').hide()
                $("#" + idImg).attr('src', r);
                // console.log(idImg + " *-* ");
            });
        $('#upload-demo').croppie('destroy');
    });
    // Start Cancel Crop Photo
    $(document).on('click', '.btn-cancel-crop', function(ev) {
        $('.main').show();
        $('.normal-button').show();
        $('.crop-img').hide();
        $('.crop-button').hide();
        $('#upload-demo').croppie('destroy');
    });


    /*<! ----------------------------------------------------- Function && Event All Searching ----------------------------------------------------------- !>*/

    // Start Search
    $("#btn_search").on('click', function() {
        year = document.getElementById("year").value;
        ID_Pest = document.getElementById("pest").value;
        ID_Distrinct = document.getElementById("amp").value;
        name = document.getElementById("name").value;
        passport = document.getElementById("idcard").value;
        // console.log(" [ " + year + " " + ID_Pest + " " + ID_Province + " " + ID_Distrinct + " " + name + " " + passport + " ] ");

        search_Fetch_Datatable();

        $("#collapseOne").children().children().addClass("collapsed");
        document.getElementById("headingOne").setAttribute("aria-expanded", "false");
        $("#collapseOne").removeClass("show");

    });
</script>