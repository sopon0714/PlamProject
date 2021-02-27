<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "OilPalmAreaVol";


include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$idformal = '';
$fullname = '';
$fpro = 0;
$fdist = 0;

$currentYear = date("Y") + 543;
$backYear = date("Y") + 543 - 1;

$PROVINCE = getProvince();
$OILPALMAREAVOL = getTableAllHarvest($idformal, $fullname, $fpro, $fdist);
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
$AREA = getAreaLogFarm();


?>
    <div class="container bg">

        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div class="card">
                    <div class="card-header card-bg">
                        <div class="row">
                            <div class="col-12">
                                <span class="link-active font-weight-bold" style="color:<?= $color ?>;">ผลผลิตสวนปาล์มน้ำมัน</span>
                                <span style="float:right;">
                                    <i class="fas fa-bookmark"></i>
                                    <a class="link-path" href="#">หน้าแรก</a>
                                    <span> > </span>
                                    <a class="link-path link-active" href="#" style="color:<?= $color ?>;">ผลผลิตสวนปาล์มน้ำมัน</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <?php
            creatCard("card-color-one",   "ผลผลิตปี " . $currentYear, number_format(getHarvestYear($currentYear), 2, '.', ',') . " ก.ก.", "waves");
            creatCard("card-color-two",   "ผลผลิตปี" . $backYear, number_format(getHarvestYear($backYear), 2, '.', ',') . " ก.ก.", "waves");
            creatCard("card-color-three",   "พื้นที่ทั้งหมด", "{$AREA[1]['AreaRai']} ไร่ {$AREA[1]['AreaNgan']} งาน", "dashboard");
            creatCard("card-color-four", "จำนวนต้นไม้", "{$AREA[1]['NumTree']} ต้น", "format_size");
            ?>
        </div>

        <form action="OilPalmAreaVol.php?isSearch=1" method="post">
            <div class="row">
                <div class="col-xl-12 col-12 mb-4">
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
                            ตำแหน่งสวนปาล์มน้ำมัน
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-12">
                                    <div id="map" style="width:auto;height:60vh;"></div>
                                </div>
                                <div class="col-xl-6 col-12">
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

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header card-header-table py-3">
                <span class="link-active font-weight-bold" style="color:<?= $color ?>;">ผลผลิตสวนปาล์มน้ำมันในระบบ</span>
                <span class="link-active font-weight-bold " style="color:<?= $color ?>;float:right;">ปี <?php echo $currentYear; ?></span>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ชื่อเกษตรกร</th>
                                <th>ชื่อสวน</th>
                                <th>จำนวนแปลง</th>
                                <th>พื้นที่ปลูก</th>
                                <th>จำนวนต้น</th>
                                <th>ผลผลิต</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ชื่อเกษตรกร</th>
                                <th>ชื่อสวน</th>
                                <th>จำนวนแปลง</th>
                                <th>พื้นที่ปลูก</th>
                                <th>จำนวนต้น</th>
                                <th>ผลผลิต</th>
                                <th>จัดการ</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <label id="size" hidden size="<?php echo sizeof($OILPALMAREAVOL); ?>"></label>
                            <?php
                            $i = 1;
                            if ($OILPALMAREAVOL != null) {
                                foreach ($OILPALMAREAVOL as $SUBDATA) {

                            ?>
                                    <tr class="<?php echo $i - 1 ?>">
                                        <td><?php echo $SUBDATA["FullName"]; ?></td>
                                        <td><?php echo $SUBDATA["NameFarm"]; ?></td>
                                        <td style="text-align:right;"><?php echo $SUBDATA["NumSubFarm"]; ?> แปลง</td>
                                        <td style="text-align:right;"><?php echo $SUBDATA["AreaRai"]; ?> ไร่ <?php echo $SUBDATA["AreaNgan"]; ?> วา</td>
                                        <td style="text-align:right;"><?php echo $SUBDATA["NumTree"]; ?> ต้น</td>
                                        <td style="text-align:right;"><?php echo  number_format($SUBDATA["VolHarvest"], 2, '.', ','); ?> ก.ก.</td>
                                        <td style="text-align:center;">
                                            <form method="post" id="ID" name="formID" action="./OilPalmAreaVolDetail.php?FMID=<?php echo $SUBDATA["FMID"]; ?>">
                                                <button type="submit" id="btn_info" class="btn btn-info btn-sm" data-toggle="tooltip" title="รายละเอียด"><i class="fas fa-bars"></i></button></a>
                                            </form>
                                        </td>
                                        <label class="click-map" id="<?php echo $i ?>" distrinct="<?php echo $SUBDATA["Distrinct"]; ?>" province="<?php echo $SUBDATA["Province"]; ?>" nameFarm="<?php echo $SUBDATA["NameFarm"]; ?>" la="<?php echo $SUBDATA["Latitude"]; ?>" long="<?php echo $SUBDATA["Longitude"]; ?>"></label>
                                    </tr>
                            <?php
                                    $i++;
                                }
                            }

                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="Modal">

        </div>

    </div>

<?php include_once("../layout/LayoutFooter.php"); ?>

<script src="OilPalmAreaVol.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>