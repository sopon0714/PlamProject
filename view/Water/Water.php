<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Water";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$idformal = '';
$fullname = '';
$fpro = 0;
$fdist = 0;
$year = 2561; //date('Y') + 543;
$INFOSUBFARM = getTableAllRain($year);
$INFOSUBFARM2 = getTableAllWater($year);
?>

<style>
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
        <?php
        creatCard("card-color-one",   "ปริมาณน้ำฝนสะสมเฉลี่ย", number_format(getAvgWater($year), 2, '.', ',') . " (ลบ.ม.)", "panorama_vertical");
        creatCard("card-color-two",   "จำนวนสวน",  getAreaLogFarm()[1]["NumFarm"] . " สวน " . getAreaLogFarm()[1]["NumSubFarm"] . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getAreaLogFarm()[1]["AreaRai"] . " ไร่ " . getAreaLogFarm()[1]["AreaNgan"] . " งาน", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getAreaLogFarm()[1]['NumTree'] . " ต้น", "format_size");
        ?>
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
                        <span style="float:right;">ปี <?php echo  $year ?></span>
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
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button id="btn-modal1" type="button" style="float:right;" class="btn btn-success" data-toggle="modal" data-target="#modal-1"><i class="fas fa-plus"></i> เพิ่มปริมาณฝนตก</button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <!------- Start DataTable ------->
                                        <table id="example1" class="table table-bordered table-data tableSearch">
                                            <thead>
                                                <tr>
                                                    <th>ชื่อเกษตรกร</th>
                                                    <th>ชื่อสวน</th>
                                                    <th>ชื่อแปลง</th>
                                                    <th>พื้นที่ปลูก</th>
                                                    <th>จำนวนต้น</th>
                                                    <th>จำนวนวัน<br>ที่ฝนตก</th>
                                                    <th>จำนวนวัน<br>ที่ฝนไม่ตก</th>
                                                    <th>ฝนทิ้งช่วง<br>มากสุด</th>
                                                    <th>ปริมาณฝน<br>สะสม(ลบ.ม.)</th>
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
                                                    <th>จำนวนวัน<br>ที่ฝนตก</th>
                                                    <th>จำนวนวัน<br>ที่ฝนไม่ตก</th>
                                                    <th>ฝนทิ้งช่วง<br>มากสุด</th>
                                                    <th>ปริมาณฝน<br>สะสม (ลบ.ม.)</th>
                                                    <th class="text-right">จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                if ($INFOSUBFARM != null) {
                                                    foreach ($INFOSUBFARM as $SUBDATA) {
                                                        echo "  <tr>
                                                                    <td>{$SUBDATA['FullName']}</td>
                                                                    <td>{$SUBDATA['NameFarm']}</td>
                                                                    <td>{$SUBDATA['NameSubfarm']}</td>
                                                                    <td>{$SUBDATA['AreaRai']} ไร่ {$SUBDATA['AreaNgan']} งาน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['NumTree']} ต้น</td>
                                                                    <td class=\"text-right\">{$SUBDATA['rainDay']} วัน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['notrainDay']} วัน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['longDay']} วัน</td>
                                                                    <td class=\"text-right\">" . number_format($SUBDATA['totalVol'], 2, '.', ',') . "</td>
                                                                    <td>
                                                                        <button  class=\"btn btn-info btn-sm\" data-toggle=\"tooltip\" title=\"รายละเอียด\"><i class=\"fas fa-bars\"></i></button>
                                                                    </td>
                                                                </tr>";
                                                    }
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="water" role="tabpanel" aria-labelledby="water-tab">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button id="btn-modal2" type="button" style="float:right;" class="btn btn-success" data-toggle="modal" data-target="#modal-2"><i class="fas fa-plus"></i> เพิ่มระบบให้น้ำ</button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">

                                        <table id="example2" class="table table-bordered table-data tableSearch">
                                            <colgroup>
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="10%">
                                                <col width="10%">
                                                <col width="10%">
                                                <col width="10%">
                                                <col width="10%">
                                                <col width="5%">

                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th>ชื่อเกษตรกร</th>
                                                    <th>ชื่อสวน</th>
                                                    <th>ชื่อแปลง</th>
                                                    <th>พื้นที่ปลูก</th>
                                                    <th>จำนวนต้น</th>
                                                    <th>วันที่ให้น้ำล่าสุด</th>
                                                    <th>ปริมาณให้น้ำล่าสุด(ลิตร)</th>
                                                    <th>ปริมาณน้ำสะสม(ลิตร)</th>
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
                                                    <th>วันที่ให้น้ำล่าสุด</th>
                                                    <th>ปริมาณให้น้ำล่าสุด(ลิตร)</th>
                                                    <th>ปริมาณน้ำสะสม(ลิตร)</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                if ($INFOSUBFARM2 != null) {
                                                    foreach ($INFOSUBFARM2 as $SUBDATA) {
                                                        echo "  <tr>
                                                                    <td>{$SUBDATA['FullName']}</td>
                                                                    <td>{$SUBDATA['NameFarm']}</td>
                                                                    <td>{$SUBDATA['NameSubfarm']}</td>
                                                                    <td>{$SUBDATA['AreaRai']} ไร่ {$SUBDATA['AreaNgan']} งาน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['NumTree']} ต้น</td>
                                                                    <td class=\"text-right\">{$SUBDATA['lastDate']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['lastVol']}</td>
                                                                    <td class=\"text-right\">" . number_format($SUBDATA['totalVol'], 2, '.', ',') . "</td>
                                                                    <td>
                                                                        <button  class=\"btn btn-info btn-sm\" data-toggle=\"tooltip\" title=\"รายละเอียด\"><i class=\"fas fa-bars\"></i></button>
                                                                    </td>
                                                                </tr>";
                                                    }
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

<?php include_once("../layout/LayoutFooter.php"); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&language=th" async defer></script>