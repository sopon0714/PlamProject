<?php
$active = $_GET['active'] ?? 1;
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "FertilisingList";
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
$INFOSUBFARM = getTableAllFertilising($year, $idformal, $fullname, $fpro, $fdist);

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

<div class="container bg">

    <!----------- Start Head ------------>
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">การให้ปุ๋ย</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="../UserProfile/UserProfile.php">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="" style="color: <?= $color ?>;">การให้ปุ๋ย</a>
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
        creatCard("card-color-one",   "ปริมาณใส่ปุ๋ยสะสมเฉลี่ย ปี " . $year, number_format(getAvgFertilising($year), 2, '.', ',') . " ครั้ง", "panorama_vertical");
        creatCard("card-color-two",   "จำนวนสวน",  getAreaLogFarm()[1]["NumFarm"] . " สวน " . getAreaLogFarm()[1]["NumSubFarm"] . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getAreaLogFarm()[1]["AreaRai"] . " ไร่ " . getAreaLogFarm()[1]["AreaNgan"] . " งาน", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getAreaLogFarm()[1]['NumTree'] . " ต้น", "format_size");
        ?>
    </div>

    <!------------ Start Serch ------------>
    <form action="FertilisingList.php?isSearch=1" method="post">
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
                                <div id="map" style="width:auto; height:40vh;"></div>
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
                        <span class="link-active font-weight-bold" style="color:<?= $color ?>;">การให้ปุ๋ยสวนปาล์มน้ำมันในระบบ (ทั้งแปลง)</span>
                        <span style="float:right;">ปี <?php echo  $year ?></span>
                    </div>
                </div>
                <div class="card-body">


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
                                            <th>ใส่ปุ๋ย<br>(ครั้ง)</th>
                                            <th>N</th>
                                            <th>P</th>
                                            <th>K</th>
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
                                            <th>ใส่ปุ๋ย<br>(ครั้ง)</th>
                                            <th>N</th>
                                            <th>P</th>
                                            <th>K</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <label id="size" hidden size="<?php echo $size; ?>"></label>
                                        <?php
                                        if ($INFOSUBFARM != null) {
                                            $i = 1;
                                            foreach ($INFOSUBFARM as $SUBDATA) {
                                                $lati = str_replace('.', '-', $SUBDATA["Latitude"]);
                                                $longi = str_replace('.', '-', $SUBDATA["Longitude"]);
                                                echo "  <tr  class=\"defualtlatlog la{$lati}long{$longi}\">
                                                                    <td>{$SUBDATA['FullName']}</td>
                                                                    <td>{$SUBDATA['NameFarm']}</td>
                                                                    <td>{$SUBDATA['NameSubfarm']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['AreaRai']} ไร่ {$SUBDATA['AreaNgan']} งาน</td>
                                                                    <td class=\"text-right\">{$SUBDATA['NumTree']} ต้น</td>
                                                                    <td class=\"text-right\">{$SUBDATA['countFertilising']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['N']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['P']}</td>
                                                                    <td class=\"text-right\">{$SUBDATA['K']}</td>
                                                                    <td class=\"text-center\">
                                                                    <a href=\"./FertilisingDetail.php?FSID={$SUBDATA['FSID']}\"><button  class=\"btn btn-info btn-sm tt\" data-toggle=\"tooltip\" title=\"รายละเอียด\"><i class=\"fas fa-bars\"></i></button></a>
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
<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="FertilisingList.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>