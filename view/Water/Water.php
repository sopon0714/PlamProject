<?php
$active = $_GET['active'] ?? 1;
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Water";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$YEAR = getYearAll();
$idformal = '';
$fullname = '';
$fpro = 0;
$fdist = 0;
$score_From = 0;
$score_To = 0;
$PROVINCE = getProvince();
$year = $YEAR[1]['Year2'];
$INFOSUBFARM = getTableAllRain($year, $idformal, $fullname, $fpro, $fdist, $score_From, $score_To);
$INFOSUBFARM2 = getTableAllWater($year, $idformal, $fullname, $fpro, $fdist, $score_From, $score_To);
if ($INFOSUBFARM == NULL) {
    $size = 0;
} else {
    $size = sizeof($INFOSUBFARM);
}
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);

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
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">การให้น้ำ</span>
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
        creatCard("card-color-one",   "ปริมาณน้ำฝนสะสมเฉลี่ย ปี " . $year, number_format(getAvgWater($year), 2, '.', ',') . " (มม.)", "panorama_vertical");
        creatCard("card-color-two",   "จำนวนสวน",  getAreaLogFarm()[1]["NumFarm"] . " สวน " . getAreaLogFarm()[1]["NumSubFarm"] . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getAreaLogFarm()[1]["AreaRai"] . " ไร่ " . getAreaLogFarm()[1]["AreaNgan"] . " งาน", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getAreaLogFarm()[1]['NumTree'] . " ต้น", "format_size");
        ?>
    </div>

    <!------------ Start Serch ------------>
    <form action="Water.php?isSearch=1" method="post">
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
                <div id="collapseOne" class="card collapse 
                <?php
                if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                    echo "show";
                else
                    echo "";
                ?> 
                 " aria-labelledby="headingOne" data-parent="#accordion">
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
                                        <select id="year" name="year" class="form-control">
                                            <?php

                                            for ($i = 1; $i < count($YEAR); $i++) {
                                                if ($YEAR[$i]['Year2'] == $year) {
                                                    echo "<option value='{$YEAR[$i]['Year2']}' selected>{$YEAR[$i]['Year2']}</option>";
                                                } else {
                                                    echo "<option value='{$YEAR[$i]['Year2']}'>{$YEAR[$i]['Year2']}</option>";
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <input type="text" hidden name="score_From" id="score_From" value="<?= $score_From ?>" />
                                <input type="text" hidden name="score_To" id="score_To" value="<?= $score_To ?>" />
                                <div class="row">
                                    <div class="col-xl-12 col-12">
                                        <div>
                                            <b>จำนวนวันที่ขาดน้ำ (วัน)</b>
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
                                        <select id="s_province" name="s_province" class="form-control">
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
                                <div class="row">
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
                                <div class="row">
                                    <div class="col-11">
                                        <span>ชื่อเกษตรกร</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="text" class="form-control" id="s_name" name="s_name" <?php if ($fullname != '') echo 'value="' . $fullname . '"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>หมายเลขบัตรประชาชน</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="password" class="form-control input-setting" id="s_formalid" name="s_formalid" <?php if ($idformal != '') echo 'value="' . $idformal . '"'; ?>>
                                        <i class="fa fa-eye-slash eye-setting" id="hide1"></i>
                                    </div>
                                </div>
                                <div class="row mb-2 padding">
                                    <div class="col-12">
                                        <button type="summit" id="btn_search" class="btn btn-success btn-sm form-control">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!------------ Start Table ------------>
    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div>
                        <span class="link-active font-weight-bold" style="color:<?= $color ?>;">การให้น้ำสวนปาล์มน้ำมันในระบบ</span>
                        <span style="float:right;">ปี <?php echo  $year ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link <?php if ($active == 1) echo "active"; ?>" id="raining-tab" data-toggle="tab" href="#raining" role="tab" aria-controls="raining" aria-selected="true">ปริมาณฝน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?php if ($active != 1) echo "active"; ?>" id="water-tab" data-toggle="tab" href="#water" role="tab" aria-controls="water" aria-selected="false">ระบบให้น้ำ</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent" style="margin-top:20px;">
                        <div class="tab-pane fade  <?php if ($active == 1) echo "show active"; ?>" id="raining" role="tabpanel" aria-labelledby="raining-tab">
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
                                                    <th>ปริมาณฝน<br>สะสม (มม.)</th>
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
                                                    <th>ปริมาณฝน<br>สะสม (มม.)</th>
                                                    <th class="text-right">จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <label id="size" hidden size="<?php echo $size; ?>"></label>
                                                <?php
                                                if ($INFOSUBFARM != null) {
                                                    foreach ($INFOSUBFARM as $SUBDATA) {
                                                        $lati = str_replace('.', '-', $SUBDATA["Latitude"]);
                                                        $longi = str_replace('.', '-', $SUBDATA["Longitude"]);
                                                        echo "  <tr  class=\"defualtlatlog la{$lati}long{$longi}\">
                                                                    <td>{$SUBDATA['FullName']}</td>
                                                                    <td>{$SUBDATA['NameFarm']}</td>
                                                                    <td>{$SUBDATA['NameSubfarm']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['AreaRai']} ไร่ {$SUBDATA['AreaNgan']} งาน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['NumTree']} ต้น</td>
                                                                    <td class=\"text-right\">{$SUBDATA['rainDay']} วัน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['notrainDay']} วัน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['longDay']} วัน</td>
                                                                    <td class=\"text-right\">" . number_format($SUBDATA['totalVol'], 2, '.', ',') . "</td>
                                                                    <td class=\"text-center\">
                                                                    <a href=\"./WaterDetail.php?FSID={$SUBDATA['FSID']}\"><button  class=\"btn btn-info btn-sm tt\" data-toggle=\"tooltip\" title=\"รายละเอียด\"><i class=\"fas fa-bars\"></i></button></a>
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
                        <div class="tab-pane fade <?php if ($active != 1) echo "show active"; ?>" id="water" role="tabpanel" aria-labelledby="water-tab">
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
                                                <col width="19%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="10%">
                                                <col width="7%">
                                                <col width="12%">
                                                <col width="12%">
                                                <col width="5%">
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
                                                    <th>ปริมาณให้<br>น้ำล่าสุด(ลิตร)</th>
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
                                                    <th>ปริมาณให้<br>น้ำล่าสุด(ลิตร)</th>
                                                    <th>ปริมาณน้ำสะสม(ลิตร)</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                if ($INFOSUBFARM2 != null) {
                                                    $i = 1;
                                                    foreach ($INFOSUBFARM2 as $SUBDATA) {
                                                        $lati = str_replace('.', '-', $SUBDATA["Latitude"]);
                                                        $longi = str_replace('.', '-', $SUBDATA["Longitude"]);
                                                        echo "  <tr  class=\"defualtlatlog la{$lati}long{$longi}\">
                                                                    <td>{$SUBDATA['FullName']}</td>
                                                                    <td>{$SUBDATA['NameFarm']}</td>
                                                                    <td>{$SUBDATA['NameSubfarm']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['AreaRai']} ไร่ {$SUBDATA['AreaNgan']} งาน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['NumTree']} ต้น</td>
                                                                    <td class=\"text-center\">{$SUBDATA['lastDate']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['lastVol']}</td>
                                                                    <td class=\"text-right\">" . number_format($SUBDATA['totalVol'], 2, '.', ',') . "</td>
                                                                    <td class=\"text-center\">
                                                                    <a href=\"./WaterDetail.php?FSID={$SUBDATA['FSID']}\"><button  class=\"btn btn-info btn-sm tt\" data-toggle=\"tooltip\" title=\"รายละเอียด\"><i class=\"fas fa-bars\"></i></button></a>
                                                                    </td>
                                                                </tr>";
                                                        echo  "<label  id=\"$i\" hidden distrinct=\"{$SUBDATA['Distrinct']}\" province=\"{$SUBDATA['Province']}\" namesubfarm=\"{$SUBDATA['NameSubfarm']}\" la=\"{$SUBDATA['Latitude']}\" long=\"{$SUBDATA['Longitude']}\"></label>";
                                                        $i++;
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
<?php include_once("./WaterModal.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="Water.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>