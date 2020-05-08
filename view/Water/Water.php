<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Water";
?>

<?php include_once("../layout/LayoutHeader.php");
require_once("../../dbConnect.php");
$totalFarm = selectDataOne("SELECT COUNT(`db-farm`.`FMID`) AS totalFarm FROM `db-farm` ");
$totalSubFarm = selectDataOne("SELECT COUNT(`db-subfarm`.`FSID`) AS totalSubFarm FROM `db-subfarm` ");
$totalAreaRai = selectDataOne("SELECT SUM(`db-subfarm`.`AreaRai`) AS totalAreaRai FROM `db-subfarm` ");
$totalPalm = selectDataOne("SELECT (SUM(`log-planting`.`NumGrowth1`)+SUM(`log-planting`.`NumGrowth2`))-SUM(`log-planting`.`NumDead`) AS totalPalm FROM `log-planting` WHERE `log-planting`.`isDelete` = 0");

?>

<style>
    /* .customSetPosition-Icon {
        position: absolute;
        right: 4%;
        top: 23%;
        font-size: 21px;
    } */

    .padding {
        padding-top: 10px;
    }
</style>

<div class="container">

    <!----------- Start Head ------------>
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color: <?= $color ?>;">การให้น้ำ</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="../UserProfile/UserProfile.php">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="" style="color: <?= $color ?>;">การให้น้ำ</a>
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
                            <div class="font-weight-bold  text-uppercase mb-1">ค่าเฉลี่ยการให้น้ำสะสม</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="cardAVGRain"></span></div>
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
                    ตำแหน่งการให้น้ำสวนปาล์มน้ำมัน
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-12">
                            <div id="map" style="width:auto; height:75vh;"></div>
                        </div>
                        <div class="col-xl-6 col-12" id="forMap">

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
                                <div class="col-xl-12 col-12">
                                    <div>
                                        <b>จำนวนวันให้น้ำ (วัน)</b>
                                        <input class="js-range-slider" type="text" id="palmvolsilder" value="" />
                                    </div>
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
                                        <option disabled selected>เลือกจังหวัด</option>
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
                                        <option disabled selected>เลือกอำเภอ</option>
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
                        <span>การให้น้ำสวนปาล์มน้ำมันในระบบ</span>
                        <span style="float:right;">ปี <?php echo date('Y') + 543; ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="raining-tab" data-toggle="tab" href="#raining" role="tab" aria-controls="raining" aria-selected="true">ปริมาณฝน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="water-tab" data-toggle="tab" href="#water" role="tab" aria-controls="water" aria-selected="false">ระบบให้น้ำ</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent" style="margin-top:20px;">
                        <div class="tab-pane fade show active" id="raining" role="tabpanel" aria-labelledby="raining-tab">
                            <div class="table-responsive">
                                <button id="btn-modal1" type="button" style="float:right;" class="btn btn-success" data-toggle="modal" data-target="#modal-1"><i class="fas fa-plus"></i> เพิ่มปริมาณฝนตก</button>
                                <!------- Start DataTable ------->
                                <table id="example1" class="table table-bordered table-striped table-hover table-data">
                                    <thead>
                                        <tr>
                                            <th>ชื่อเกษตรกร</th>
                                            <th>ชื่อสวน</th>
                                            <th>ชื่อแปลง</th>
                                            <th>พื้นที่ปลูก</th>
                                            <th>จำนวนต้น</th>
                                            <th>จำนวนวันที่ฝนตก</th>
                                            <th>จำนวนวันที่ฝนไม่ตก</th>
                                            <th>ฝนทิ้งช่วงมากสุด</th>
                                            <th>ปริมาณฝนสะสม (ลบ.ม.)</th>
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
                                            <th>จำนวนวันที่ฝนตก</th>
                                            <th>จำนวนวันที่ฝนไม่ตก</th>
                                            <th>ฝนทิ้งช่วงมากสุด</th>
                                            <th>ปริมาณฝนสะสม (ลบ.ม.)</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="fetchDatatable1" TYPE="1">

                                    </tbody>
                                </table>
                                <!------- Stop DataTable ------->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="water" role="tabpanel" aria-labelledby="water-tab">
                            <div class="table-responsive">
                                <button id="btn-modal2" type="button" style="float:right;" class="btn btn-success" data-toggle="modal" data-target="#modal-2"><i class="fas fa-plus"></i> เพิ่มระบบให้น้ำ</button>
                                <table id="example2" class="table table-bordered table-striped table-hover table-data">
                                    <thead>
                                        <tr>
                                            <th>ชื่อเกษตรกร</th>
                                            <th>ชื่อสวน</th>
                                            <th>ชื่อแปลง</th>
                                            <th>พื้นที่ปลูก</th>
                                            <th>จำนวนต้น</th>
                                            <th>วันที่</th>
                                            <th>ช่วงเวลาให้น้ำ</th>
                                            <th>ระยะเวลาให้น้ำรวม (นาที)</th>
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
                                            <th>วันที่</th>
                                            <th>ช่วงเวลาให้น้ำ</th>
                                            <th>ระยะเวลาให้น้ำรวม (นาที)</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="fetchDatatable2" TYPE="2">


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

<!------------ Start Modal ------------>

<div class="modal" id="modal-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal">
                <h4 class="modal-title">เพิ่มปริมาณฝนตก</h4>
            </div>
            <div class="modal-body" id="addModalBody1">

                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>วันที่</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <div class="input-group">
                            <input type="text" class="form-control" data-toggle="datepicker" id="r_date1" name="r_date1">
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
                        <select class="js-example-basic-single" id="r_farm1">
                            <option disabled selected>เลือกสวน</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>จากแปลง</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="js-example-basic-single" id="r_subfarm1">
                            <option disabled selected>เลือกแปลง</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่ฝนเริ่มตก</span>
                    </div>
                    <div class="col-xl-9 col-12 ">
                        <input type="text" id="r1_timepicker1" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่ฝนหยุดตก</span>
                    </div>
                    <div class="col-xl-9 col-12 timepicker">
                        <input type="text" id="r2_timepicker1" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>ระดับฝนตก</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="form-control" id="r_rank1">
                            <option disabled selected value="null">เลือกระดับปริมาณฝน</option>
                            <option value="1">เบา (ปริมาณ 0.1 มม. ถึง 10.0 มม.)</option>
                            <option value="2">ปานกลาง (ปริมาณ 10.1 มม. ถึง 35.0 มม.)</option>
                            <option value="3">หนัก (ปริมาณ 35.1 มม. ถึง 90.0 มม.)</option>
                            <option value="4">หนักมาก (ปริมาณ 90.1 มม. ขึ้นไป)</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>ปริมาณน้ำฝน / ลิตร</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <input type="number" class="form-control" id="r_raining1">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="m_success1" type="button" class="btn btn-success" data-dismiss="modal">ยืนยัน</button>
                <button id="m_not_success1" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal">
                <h4 class="modal-title">เพิ่มระบบการให้น้ำ</h4>
            </div>
            <div class="modal-body" id="addModalBody2">

                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>วันที่</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <div class="input-group">
                            <input type="text" class="form-control" data-toggle="datepicker" id="r_date2" name="r_date2">
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
                        <select class="js-example-basic-single" id="r_farm2">
                            <option disabled selected>เลือกสวน</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>จากแปลง</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="js-example-basic-single" id="r_subfarm2">
                            <option disabled selected>เลือกแปลง</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่เริ่มให้น้ำ</span>
                    </div>
                    <div class="col-xl-9 col-12  timepicker">
                        <input type="text" id="r1_timepicker2" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่หยุดให้น้ำ</span>
                    </div>
                    <div class="col-xl-9 col-12  timepicker">
                        <input type="text" id="r2_timepicker2" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <!-- <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>ปริมาณการให้น้ำ / ลิตร</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <input type="number" class="form-control" id="r_raining2">
                    </div>
                </div> -->

            </div>
            <div class="modal-footer">
                <button id="m_success2" type="button" class="btn btn-success" data-dismiss="modal">ยืนยัน</button>
                <button id="m_not_success2" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>




<?php include_once("../layout/LayoutFooter.php"); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&language=th" async defer></script>

<script>
    $(document).ready(function() {

        $('.js-example-basic-single').select2();
        $('.js-example-basic-single').on('select2:open', function(e) {
            $(this).next().addClass("border-from-control");
        });
        $('.js-example-basic-single').on('select2:close', function(e) {
            $(this).next().removeClass("border-from-control");
        });


        $('#r_date1').datepicker({
            autoHide: true,
            zIndex: 2048,
            language: 'th-TH',
            format: 'dd-mm-yyyy'
        });
        $('#r_date2').datepicker({
            autoHide: true,
            zIndex: 2048,
            language: 'th-TH',
            format: 'dd-mm-yyyy'
        });

        $('#r1_timepicker1').mdtimepicker();
        $('#r2_timepicker1').mdtimepicker();
        $('#r1_timepicker2').mdtimepicker();
        $('#r2_timepicker2').mdtimepicker();

        // $('#r1_timepicker1').mdtimepicker().on('timechanged', function(e) {
        //     console.log(e.value); // gets the input value
        //     console.log(e.time); // gets the data-time value
        // });

        loadProvince();
        loadData_1();
        loadData_2();

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

    let factRain = "";
    let factWater = "";
    let dataRain = [];
    let dropRain = [];

    let year = null;
    let score_From = 0;
    let score_To = 0;
    let name = null;
    let passport = null;

    /*<! ----------------------------------------------------- Function Mixs All  ----------------------------------------------------------- !>*/
    // Format NUmber
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    // LoadMap
    function initMap(data, type) {
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
        for (i = 0; i < data.length; i++) {
            // console.log("SET MAP ");
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(data[i].Latitude, data[i].Longitude),
                title: type,
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
    function loadFarm(id) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                dataFarm = JSON.parse(this.responseText);
                // console.log(dataFarm)
                let text = "<option disabled selected value='null'>เลือกสวน</option>";
                for (i in dataFarm) {
                    text += ` <option value="${dataFarm[i].FMID}">${dataFarm[i].Name}</option> `
                }
                $(id).html(text);
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
                let text = "<option value='null' disabled selected>เลือกแปลง</option>";
                for (i in dataSubFarm) {
                    text += ` <option value="${dataSubFarm[i].FSID}">${dataSubFarm[i].Name}</option> `
                }
                $(ID).html(text);
            }
        };
        xhttp.open("GET", "./loadSubFarm.php?farm=" + farm, true);
        xhttp.send();
    }
    // โหลด Datatable 1 
    function loadData_1() {
        let xhttp = new XMLHttpRequest();
        $('#example1').DataTable().destroy();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let count = 0;
                let id = 0;
                dataRain = [];
                dropRain = [];
                let count_Drop_Rain = [];
                factRain = JSON.parse(this.responseText);
                // console.log("Load 1");
                // console.log(factRain);
                // Start Create Data in Array
                if (factRain != "") {
                    dataRain[0] = {
                        FID: factRain[0].FID,
                        SFID: factRain[0].SFID,
                        DSFID: factRain[0].DSFID,
                        UID: factRain[0].UID,
                        Name: factRain[0].Name,
                        FName: factRain[0].FName,
                        subFName: factRain[0].subFName,
                        SumArea: factRain[0].SumArea,
                        SumNumTree: factRain[0].SumNumTree,
                        CountRain: 0,
                        CountNotRain: 0,
                        DropRain: 0,
                        SumRain: factRain[0].RainPeriod,
                        Latitude: factRain[0].Latitude,
                        Longitude: factRain[0].Longitude
                    };
                    if (factRain[0].RainPeriod > 0)
                        dataRain[0].CountRain++;
                    else {
                        dataRain[0].CountNotRain++;
                        count_Drop_Rain[0] = 1;
                    }
                    for (i in factRain)
                        count++;
                    for (i = 1, j = 0; i < count; i++) {
                        if (factRain[i].SFID != dataRain[id].SFID) {
                            id++;
                            dataRain[id] = {
                                FID: factRain[i].FID,
                                SFID: factRain[i].SFID,
                                DSFID: factRain[i].DSFID,
                                UID: factRain[i].UID,
                                Name: factRain[i].Name,
                                FName: factRain[i].FName,
                                subFName: factRain[i].subFName,
                                SumArea: factRain[i].SumArea,
                                SumNumTree: factRain[i].SumNumTree,
                                CountRain: 0,
                                CountNotRain: 0,
                                DropRain: 0,
                                SumRain: factRain[i].RainPeriod,
                                Latitude: factRain[i].Latitude,
                                Longitude: factRain[i].Longitude
                            };
                            j = id - 1;
                            dropRain[j] = Math.max.apply(null, count_Drop_Rain);
                            count_Drop_Rain = count_Drop_Rain.slice(0, 1);
                            count_Drop_Rain[0] = 0;
                        }
                        if (factRain[i].RainPeriod > 0) {
                            dataRain[id].CountRain++;
                            count_Drop_Rain[++j] = 0;
                        } else {
                            dataRain[id].CountNotRain++;
                            count_Drop_Rain[j]++;
                        }
                        dataRain[id].SumRain = Number(dataRain[id].SumRain) + Number(factRain[i].RainPeriod);
                    }
                    dropRain[id] = Math.max.apply(null, count_Drop_Rain);
                    for (i = 0; i < dropRain.length; i++)
                        dataRain[i].DropRain = dropRain[i];
                }

                // Auto Fetch DataTable Rain
                let text = "";
                for (i in dataRain) {
                    text += `<tr>
                            <th class="text-left">${dataRain[i].Name}</th>
                            <th class="text-left">${dataRain[i].FName}</th>
                            <th class="text-left">${dataRain[i].subFName}</th>
                            <th class="text-right">${formatNumber(dataRain[i].SumArea)}</th>
                            <th class="text-right">${formatNumber(dataRain[i].SumNumTree)}</th>
                            <th class="text-right">${dataRain[i].CountRain}</th>
                            <th class="text-right">${dataRain[i].CountNotRain}</th>
                            <th class="text-right">${dataRain[i].DropRain}</th>
                            <th class="text-right">${formatNumber(dataRain[i].SumRain)}</th>
                            <th style="text-align:center;">
                                <button id='${i}' FID='${dataRain[i].FID}' SFID='${dataRain[i].SFID}' UID='${dataRain[i].UID}' DSFID=${dataRain[i].DSFID}'  type="button" class="btn btn-info btn-sm btn-detail">
                                    <i class="fas fa-bars"></i>
                                </button>
                            </th>
                        </tr>`
                }
                $("#fetchDatatable1").html(text);
                setOption_DataTable("example1", "PDF", "EXCEL", 1, 0, 8);
                setAVGRain();
                initMap(dataRain, "ฝนตก");
            }
        };
        xhttp.open("GET", "./loadFactWater.php", true);
        xhttp.send();
    }
    // Search And Fetch Datatable 1
    function search_Fetch_Datatable1() {
        let xhttp = new XMLHttpRequest();
        $('#example1').DataTable().destroy();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let count = 0;
                let id = 0;
                dataRain = [];
                dropRain = [];
                let count_Drop_Rain = [];
                factRain = JSON.parse(this.responseText);
                console.log("Search 1");
                console.log(factRain);

                // Start Create Data in Array
                if (factRain != "") {
                    dataRain[0] = {
                        FID: factRain[0].FID,
                        SFID: factRain[0].SFID,
                        DSFID: factRain[0].DSFID,
                        UID: factRain[0].UID,
                        Name: factRain[0].Name,
                        FName: factRain[0].FName,
                        subFName: factRain[0].subFName,
                        SumArea: factRain[0].SumArea,
                        SumNumTree: factRain[0].SumNumTree,
                        CountRain: 0,
                        CountNotRain: 0,
                        DropRain: 0,
                        SumRain: factRain[0].RainPeriod,
                        Latitude: factRain[0].Latitude,
                        Longitude: factRain[0].Longitude
                    };
                    if (factRain[0].RainPeriod > 0)
                        dataRain[0].CountRain++;
                    else {
                        dataRain[0].CountNotRain++;
                        count_Drop_Rain[0] = 1;
                    }
                    for (i in factRain)
                        count++;
                    for (i = 1, j = 0; i < count; i++) {
                        if (factRain[i].SFID != dataRain[id].SFID) {
                            id++;
                            dataRain[id] = {
                                FID: factRain[i].FID,
                                SFID: factRain[i].SFID,
                                DSFID: factRain[i].DSFID,
                                UID: factRain[i].UID,
                                Name: factRain[i].Name,
                                FName: factRain[i].FName,
                                subFName: factRain[i].subFName,
                                SumArea: factRain[i].SumArea,
                                SumNumTree: factRain[i].SumNumTree,
                                CountRain: 0,
                                CountNotRain: 0,
                                DropRain: 0,
                                SumRain: factRain[i].RainPeriod,
                                Latitude: factRain[i].Latitude,
                                Longitude: factRain[i].Longitude
                            };
                            j = id - 1;
                            dropRain[j] = Math.max.apply(null, count_Drop_Rain);
                            count_Drop_Rain = count_Drop_Rain.slice(0, 1);
                            count_Drop_Rain[0] = 0;
                        }
                        if (factRain[i].RainPeriod > 0) {
                            dataRain[id].CountRain++;
                            count_Drop_Rain[++j] = 0;
                        } else {
                            dataRain[id].CountNotRain++;
                            count_Drop_Rain[j]++;
                        }
                        dataRain[id].SumRain = Number(dataRain[id].SumRain) + Number(factRain[i].RainPeriod);
                    }
                    dropRain[id] = Math.max.apply(null, count_Drop_Rain);
                    for (i = 0; i < dropRain.length; i++)
                        dataRain[i].DropRain = dropRain[i];
                }

                // Auto Fetch DataTable Rain
                let text = "";
                for (i in dataRain) {
                    text += `<tr>
                            <th class="text-left">${dataRain[i].Name}</th>
                            <th class="text-left">${dataRain[i].FName}</th>
                            <th class="text-left">${dataRain[i].subFName}</th>
                            <th class="text-right">${formatNumber(dataRain[i].SumArea)}</th>
                            <th class="text-right">${formatNumber(dataRain[i].SumNumTree)}</th>
                            <th class="text-right">${dataRain[i].CountRain}</th>
                            <th class="text-right">${dataRain[i].CountNotRain}</th>
                            <th class="text-right">${dataRain[i].DropRain}</th>
                            <th class="text-right">${formatNumber(dataRain[i].SumRain)}</th>
                            <th style="text-align:center;">
                                <button id='${i}' FID='${dataRain[i].FID}' SFID='${dataRain[i].SFID}' UID='${dataRain[i].UID}' DSFID=${dataRain[i].DSFID}'  type="button" class="btn btn-info btn-sm btn-detail">
                                    <i class="fas fa-bars"></i>
                                </button>
                            </th>
                        </tr>`
                }
                $("#fetchDatatable1").html(text);
                $('#example1').DataTable({
                    dom: '<"row"<"col-sm-6"B>>' +
                        '<"row"<"col-sm-6 mar"l><"col-sm-6 mar"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    buttons: [{
                            extend: 'excel',
                            title: 'ปริมาณฝนของสวนปาล์มน้ำมัน/แปลง',
                            text: '<i class="fas fa-file-excel"> <font> Excel</font> </i>',
                            className: 'btn btn-outline-success btn-sm export-button'
                        },
                        {
                            extend: 'pdf',
                            title: 'ปริมาณฝนของสวนปาล์มน้ำมัน/แปลง',
                            text: '<i class="fas fa-file-pdf"> <font> PDF</font> </i>',
                            className: 'btn btn-outline-danger btn-sm export-button',
                            pageSize: 'A4',
                            customize: function(doc) {
                                doc.defaultStyle = {
                                    font: 'THSarabun',
                                    fontSize: 16
                                };
                            }
                        }
                    ],
                    language: {
                        emptyTable: "ไม่พบข้อมูลที่ต้องการค้นหา !!"
                    }
                });
                setAVGRain();
                initMap(dataRain, "ฝนตก");
            }
        };
        xhttp.open("POST", "./search_Fetch_Datatable1.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`year=${year}&ID_Province=${ID_Province}&ID_Distrinct=${ID_Distrinct}&name=${name}&passport=${passport}`);

    }
    // โหลด Datatable 2 
    function loadData_2() {
        let xhttp = new XMLHttpRequest();
        $('#example2').DataTable().destroy();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let timeObj, timeObj2;
                factWater = JSON.parse(this.responseText);
                // console.log("Load 2");
                // console.log(factWater);
                let text = "";
                for (i in factWater) {
                    timeObj = new Date(factWater[i].mStime * 1000);
                    timeObj2 = new Date(factWater[i].MStime * 1000);
                    text += `<tr>
                            <th class="text-left">${factWater[i].Name}</th>
                            <th class="text-left">${factWater[i].FName}</th>
                            <th class="text-left">${factWater[i].subFName}</th>
                            <th class="text-right">${factWater[i].SumArea}</th>
                            <th class="text-right">${factWater[i].SumNumTree}</th>
                            <th class="text-right">${factWater[i].Date}</th>
                            <th style="text-align:center;">${timeObj.getUTCHours().toString().padStart(2, '0') + '.' + timeObj.getUTCMinutes().toString().padStart(2, '0')} - 
                                                           ${timeObj2.getUTCHours().toString().padStart(2, '0') + '.' + timeObj2.getUTCMinutes().toString().padStart(2, '0')}</th>
                            <th class="text-right">${factWater[i].SUMTIME}</th>
                            <th style="text-align:center;">
                                <button id='${i}' FID='${factWater[i].FID}' SFID='${factWater[i].SFID}' UID='${factWater[i].UID}' DSFID=${factWater[i].DSFID}' date=${factWater[i].Date} type="button" class="btn btn-info btn-sm btn-detail">
                                    <i class="fas fa-bars"></i>
                                </button>
                            </th>
                        </tr>`
                }
                $("#fetchDatatable2").html(text);
                setOption_DataTable("example2", "PDF", "EXCEL", 1, 0, 7);
                initMap(factWater, "การให้น้ำ");
            }
        };
        xhttp.open("GET", "./loadSumLogWater.php", true);
        xhttp.send();
    }
    // Search And Fetch Datatable 2
    function search_Fetch_Datatable2() {
        let xhttp = new XMLHttpRequest();
        $('#example2').DataTable().destroy();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let timeObj, timeObj2;
                factWater = JSON.parse(this.responseText);
                console.log("Search 2");
                console.log(factWater);
                let text = "";
                for (i in factWater) {
                    timeObj = new Date(factWater[i].mStime * 1000);
                    timeObj2 = new Date(factWater[i].MStime * 1000);
                    text += `<tr>
                            <th class="text-left">${factWater[i].Name}</th>
                            <th class="text-left">${factWater[i].FName}</th>
                            <th class="text-left">${factWater[i].subFName}</th>
                            <th class="text-right">${factWater[i].SumArea}</th>
                            <th class="text-right">${factWater[i].SumNumTree}</th>
                            <th class="text-right">${factWater[i].Date}</th>
                            <th style="text-align:center;">${timeObj.getUTCHours().toString().padStart(2, '0') + '.' + timeObj.getUTCMinutes().toString().padStart(2, '0')} - 
                                                           ${timeObj2.getUTCHours().toString().padStart(2, '0') + '.' + timeObj2.getUTCMinutes().toString().padStart(2, '0')}</th>
                            <th class="text-right">${factWater[i].SUMTIME}</th>
                            <th style="text-align:center;">
                                <button id='${i}' FID='${factWater[i].FID}' SFID='${factWater[i].SFID}' UID='${factWater[i].UID}' DSFID=${factWater[i].DSFID}' date=${factWater[i].Date} type="button" class="btn btn-info btn-sm btn-detail">
                                    <i class="fas fa-bars"></i>
                                </button>
                            </th>
                        </tr>`
                }
                $("#fetchDatatable2").html(text);
                $('#example2').DataTable({
                    dom: '<"row"<"col-sm-6"B>>' +
                        '<"row"<"col-sm-6 mar"l><"col-sm-6 mar"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    buttons: [{
                            extend: 'excel',
                            title: 'ระบบให้น้ำของสวนปาล์มน้ำมัน/แปลง',
                            text: '<i class="fas fa-file-excel"> <font> Excel</font> </i>',
                            className: 'btn btn-outline-success btn-sm export-button'
                        },
                        {
                            extend: 'pdf',
                            title: 'ระบบให้น้ำของสวนปาล์มน้ำมัน/แปลง',
                            text: '<i class="fas fa-file-pdf"> <font> PDF</font> </i>',
                            className: 'btn btn-outline-danger btn-sm export-button',
                            pageSize: 'A4',
                            customize: function(doc) {
                                doc.defaultStyle = {
                                    font: 'THSarabun',
                                    fontSize: 16
                                };
                            }
                        }
                    ],
                    language: {
                        emptyTable: "ไม่พบข้อมูลที่ต้องการค้นหา !!"
                    }
                });
                initMap(factWater, "การให้น้ำ");
            }
        };
        xhttp.open("POST", "./search_Fetch_Datatable2.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`year=${year}&ID_Province=${ID_Province}&ID_Distrinct=${ID_Distrinct}&name=${name}&passport=${passport}`);

    }
    // โหลด AddLogRain 
    function addLogRain(date, StartTime, StopTime, rank, vol) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };
        xhttp.open("POST", "./addLogRain.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`date=${date}&ID_Farm=${ID_Farm}&ID_SubFarm=${ID_SubFarm}&StartTime=${StartTime}&StopTime=${StopTime}&rank=${rank}&vol=${vol}`);
    }
    // โหลด AddLogWater 
    function addLogWater(date, StartTime, StopTime) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };
        xhttp.open("POST", "./addLogWater.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`date=${date}&ID_Farm=${ID_Farm}&ID_SubFarm=${ID_SubFarm}&StartTime=${StartTime}&StopTime=${StopTime}`);
    }
    // Set Data Card AVGRain
    function setAVGRain() {
        <?php $dataRain = selectAll("SELECT lr.DIMfarmID,lr.DIMsubFID,SUM(lr.Vol) AS vol FROM `log-raining` AS lr WHERE lr.isDelete = 0 GROUP BY lr.DIMsubFID");
        $totalRain = 0;
        foreach ($dataRain as $data)
            $totalRain = $totalRain + $data['vol'];
        $totalRain = $totalRain / sizeof($dataRain); ?>
        let AVGRain = <?php echo $totalRain ?>;
        document.getElementById("cardAVGRain").textContent = formatNumber(AVGRain.toFixed(2)) + " (ลบ.ม.)";
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
    // Start Range Water
    $(".js-range-slider").ionRangeSlider({
        type: "double",
        from: 0,
        to: 0,
        step: 1,
        min: 0,
        max: 100,
        grid: true,
        grid_num: 10,
        grid_snap: false,
        skin: "big",
        onFinish: function(data) {
            score_From = data.from;
            score_To = data.to;
            console.log(score_From + " " + score_To);
        }
    });
    // Start Event Select_สวน 1
    $("#r_farm1").on('change', function() {
        $("#r_subfarm1").empty();
        let x = document.getElementById("r_farm1").value;
        ID_Farm = x;
        loadSubFarm(x, "#r_subfarm1");
    });
    // Start Event Select_แปลง 1
    $("#r_subfarm1").on('change', function() {
        let x = document.getElementById("r_subfarm1").value;
        ID_SubFarm = x;
    });
    //Start Event Select_สวน 2
    $("#r_farm2").on('change', function() {
        $("#r_subfarm2").empty();
        let x = document.getElementById("r_farm2").value;
        ID_Farm = x;
        loadSubFarm(x, "#r_subfarm2");
    });
    // Start Event Select_แปลง 2
    $("#r_subfarm2").on('change', function() {
        let x = document.getElementById("r_subfarm2").value;
        ID_SubFarm = x;
    });


    //
    $("#btn-modal1").on('click', function() {
        $('#r_date1').datepicker("setDate", "pick");
        let d = new Date();
        let start_time = d.getHours() + ":" + d.getMinutes();
        let st_minutes = d.getMinutes() + 1 < 60 ? d.getMinutes() + 1 : "00";
        let st_hours = st_minutes == "00" ? (d.getHours() + 1) % 24 : d.getHours();
        $('#r1_timepicker1').val(start_time);
        $('#r2_timepicker1').val(st_hours + ":" + st_minutes);

        loadFarm('#r_farm1');
        $('#r_subfarm1').html("<option disabled selected>เลือกแปลง</option>");
        $("#r_raining1").val(0);

    });
    //
    $("#btn-modal2").on('click', function() {
        $('#r_date2').datepicker("setDate", "pick");
        let d = new Date();
        let start_time = d.getHours() + ":" + d.getMinutes();
        let st_minutes = d.getMinutes() + 1 < 60 ? d.getMinutes() + 1 : "00";
        let st_hours = st_minutes == "00" ? (d.getHours() + 1) % 24 : d.getHours();
        $('#r1_timepicker2').val(start_time);
        $('#r2_timepicker2').val(st_hours + ":" + st_minutes);

        loadFarm('#r_farm2');
        $('#r_subfarm2').html("<option disabled selected>เลือกแปลง</option>");
    });


    $("#m_success1").on('click', function() {
        let date = $('#r_date1').val();
        ID_Farm = document.getElementById("r_farm1").value;
        ID_SubFarm = document.getElementById("r_subfarm1").value;
        let StartTime = $('#r1_timepicker1').val();
        let StopTime = $('#r2_timepicker1').val();

        let arrayDate = date.split("-");
        let arrayStartTime = StartTime.split(":");
        let arrayStopTime = StopTime.split(":");
        StartTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStartTime[0], arrayStartTime[1], 0)).getTime() / 1000;
        StopTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStopTime[0], arrayStopTime[1], 0)).getTime() / 1000;

        let rank = document.getElementById("r_rank1").value;
        let vol = document.getElementById("r_raining1").value;

        addLogRain(date, StartTime, StopTime, rank, vol);
        location.reload();
    });
    $("#m_success2").on('click', function() {
        let date = $('#r_date2').val();
        ID_Farm = document.getElementById("r_farm2").value;
        ID_SubFarm = document.getElementById("r_subfarm2").value;
        let StartTime = $('#r1_timepicker2').val();
        let StopTime = $('#r2_timepicker2').val();

        let arrayDate = date.split("-");
        let arrayStartTime = StartTime.split(":");
        let arrayStopTime = StopTime.split(":");
        StartTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStartTime[0], arrayStartTime[1], 0)).getTime() / 1000;
        StopTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStopTime[0], arrayStopTime[1], 0)).getTime() / 1000;

        // let vol = document.getElementById("r_raining2").value;

        addLogWater(date, StartTime, StopTime);
        location.reload();
    });

    //
    $(document).on('click', '.btn-detail', function() {
        let FID = $(this).attr('FID');
        let SFID = $(this).attr('SFID');
        let UID = $(this).attr('UID');
        let DSFID = $(this).attr('DSFID');

        localStorage.setItem("DSFID", DSFID);
        localStorage.setItem("FID", FID);
        localStorage.setItem("SFID", SFID);
        localStorage.setItem("UID", UID);

        if ($(this).parent().parent().parent().attr("TYPE") == 1)
            window.open('./WaterDetail.php?type=1', '_self');
        else {
            localStorage.setItem("date", $(this).attr("date"));
            window.open('./WaterDetail.php?type=2', '_self');
        }
    });

    /*<! ----------------------------------------------------- Function && Event All Searching ----------------------------------------------------------- !>*/

    // Start Search
    $("#btn_search").on('click', function() {
        year = document.getElementById("year").value;
        ID_Distrinct = document.getElementById("amp").value;
        name = document.getElementById("name").value;
        passport = document.getElementById("idcard").value;

        search_Fetch_Datatable1();
        search_Fetch_Datatable2();

        // $("#collapseOne").children().children().addClass("collapsed");
        // document.getElementById("headingOne").setAttribute("aria-expanded", "false");
        // $("#collapseOne").removeClass("show");

    });
</script>