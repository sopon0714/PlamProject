<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "AgriMap";
include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$fpesttype = 0;

$PROVINCE = getProvince();
$YEAR = getYearAgriMap();
$PESTTYPE = getPestType();
$NUTR = getNutr();

?>

<style>
    #map {
        width: 100%;
        height: 600px;
    }

    #find {
        max-width: 500px;
    }
</style>
<div class="loader hidden">
    <img src="./../../icon/loading/loading.gif" alt="Loading...">Loading...
</div>
<div id="body_data" hidden>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-12 mb-6">
                <div class="card">
                    <div class="card-header card-bg" style="background-color: <?= $color ?>; color: white;">
                        <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-2">
                <div class="card">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card-body">
                                <br>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <span>ปีที่จัดการสวน</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="year" name="year" class="form-control ">
                                            <option selected value=0>เลือกทั้งหมด</option>
                                            <?php
                                            for ($i = 1; $i < sizeof($YEAR); $i++) {
                                                if ($fyear == $YEAR[$i]["Year2"])
                                                    echo '<option value="' . $YEAR[$i]["Year2"] . '" selected>' . $YEAR[$i]["Year2"] . '</option>';
                                                else
                                                    echo '<option value="' . $YEAR[$i]["Year2"] . '">' . $YEAR[$i]["Year2"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <span>จังหวัด</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="province" name="province" class="form-control ">
                                            <option selected value=0>เลือกทั้งหมด</option>
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
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <span>อำเภอ</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="distrinct" name="distrinct" disabled class="form-control selectpicker" data-live-search="true" title="กรุณาเลือกอำเภอ">
                                            <option value="" hidden>เลือกทั้งหมด</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card-body">
                                <br>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <span>ชื่อเกษตรกร</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" id="farmer" placeholder="กรอกชื่อเกษตรกร">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="checkbox" name="check" id="check1">
                                        <span>ผลผลิต</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="harvest" class="form-control" disabled>
                                            <option value=0 selected>ทั้งหมด</option>
                                            <option value=1>เกินค่าเฉลี่ย</option>
                                            <option value=2>ไม่เกินค่าเฉลี่ย</option>
                                            <option value=3>ไม่มีผลผลิต</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="checkbox" name="check2" id="check2">
                                        <span>ใส่ปุ๋ย</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="NutrList" class="form-control" disabled>
                                            <?php
                                            for ($i = 1; $i <= $NUTR[0]['numrow']; $i++) {
                                                echo "<option value=\"{$NUTR[$i]['NID']}\">{$NUTR[$i]['Name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="fertilizer" class="form-control" disabled>
                                            <option value=0>ใส่ครบ</option>
                                            <option value=1>ใส่ไม่ครบ</option>
                                            <option value=2>ไม่ใส่</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="checkbox" name="check3" id="check3">
                                        <span>ให้น้ำ (วัน)</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <input type="text" id="water" value="" />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="checkbox" name="check4" id="check4">
                                        <span>ขาดน้ำ (วัน)</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <input type="text" id="waterlack" value="" />
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="checkbox" name="check5" id="check5">
                                        <span>ล้างคอขวด (ครั้ง)</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <input type="text" id="wash" value="" />
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="checkbox" name="check6" id="check6">
                                        <span>ศัตรูพืช</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <select id="pesttype" name="pesttype" disabled class="form-control ">
                                            <option selected value=0>เลือกทั้งหมด</option>
                                            <?php
                                            for ($i = 1; $i < sizeof($PESTTYPE); $i++) {
                                                if ($fpesttype == $PESTTYPE[$i]["PTID"])
                                                    echo '<option value="' . $PESTTYPE[$i]["PTID"] . '" selected>' . $PESTTYPE[$i]["TypeTH"] . '</option>';
                                                else
                                                    echo '<option value="' . $PESTTYPE[$i]["PTID"] . '">' . $PESTTYPE[$i]["TypeTH"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-footer" align="center">
                            <button type="button" id="search" name="search" class="btn" style="background-color: <?= $color ?>; color:white; height:50px; width:100px;">ค้นหา
                                <i class="fas fa-search"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header card-bg">
                <span class="link-active font-weight-bold" style="color:<?= $color ?>;">ตำแหน่งแปลงของสวนปาล์ม</span>
            </div>
            <div class="card-body">
                <div class="col-12 mb-2">
                    <div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="AgriMap.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>