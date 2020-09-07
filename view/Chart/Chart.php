<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Chart";
include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$fpesttype = 0;

$PROVINCE = getProvince();
$YEAR = getYearAgriMap();
$PESTTYPE = getPestType();

?>
<style>
.graph {
    width: 100px;
    height: 100px;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-xl-12 col-12 mb-6">
            <div class="card">
                <div class="card-header card-bg" style="background-color: <?= $color ?>; color: white;">
                    <i class="fas fa-search"> รูปแบบการนำเสนอ</i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-2">
            <div class="card">
                <div class="row">
                    <div class="col-sm-4">
                        <br>
                        <span style="margin-left: 20px; color: <?= $color ?>;">รูปแบบการนำเสนอ</span>
                    </div>
                </div>
                <div class="row" style="margin-left: 5px;">
                    <div class="col-sm-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <input type="radio" id="table" name="present" value="table">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/table2.png">
                            </div>
                            <div class="row mb-2">
                                <label for="table">ตาราง</label>
                            </div>
                            <div class="row mb-2">
                                <input type="radio" id="multibar" name="present" value="multibar">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/graph-13.png">
                            </div>
                            <div class="row mb-2">
                                <label for="multibar">แผนภูมิหลายแท่ง</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <input type="radio" id="pie" name="present" value="pie">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/graph-18.png">
                            </div>
                            <div class="row mb-2">
                                <label for="pie">กราฟวงกลม</label>
                            </div>
                            <div class="row mb-2">
                                <input type="radio" id="complexbar" name="present" value="complexbar">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/bar-chart(2).png">
                            </div>
                            <div class="row mb-2">
                                <label for="complexbar">แผนภูมิแท่งเชิงซ้อน</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <input type="radio" id="line" name="present" value="line">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/graph.png">
                            </div>
                            <div class="row mb-2">
                                <label for="line">กราฟเส้น</label>
                            </div>
                            <div class="row mb-2">
                                <input type="radio" id="area" name="present" value="area">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/area-chart.png">
                            </div>
                            <div class="row mb-2">
                                <label for="area">กราฟพื้นที่</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <input type="radio" id="multiline" name="present" value="multiline">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/graph-21.png">
                            </div>
                            <div class="row mb-2">
                                <label for="multiline">กราฟหลายเส้น</label>
                            </div>
                            <div class="row mb-2">
                                <input type="radio" id="multiarea" name="present" value="multiarea">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/line-chart(1).png">
                            </div>
                            <div class="row mb-2">
                                <label for="multiarea">กราฟหลายพื้นที่</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <input type="radio" id="bar" name="present" value="bar">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/graph-29.png">
                            </div>
                            <div class="row mb-2">
                                <label for="bar">กราฟแท่ง</label>
                            </div>
                            <div class="row mb-2">
                                <input type="radio" id="mix" name="present" value="mix">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/graph-5.png">
                            </div>
                            <div class="row mb-2">
                                <label for="mix">กราฟผสม</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="card-body">
                            <div class="row mb-2">
                                <input type="radio" id="spider" name="present" value="spider">
                            </div>
                            <div class="row mb-2">
                                <img class="graph" src="./chart/diagram.png">
                            </div>
                            <div class="row mb-2">
                                <label for="spider">กราฟแมงมุม</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span style="margin-left: 20px; color: <?= $color ?>;">เลือกหัวข้อ</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <select class="form-control selectpicker" data-live-search="true"
                                        title="กรุณาเลือกหัวข้อ">
                                        <option value="">กรุณาเลือกหัวข้อสวน/แปลง</option>
                                        <option name="province" id="province" value="province">จังหวัด</option>
                                        <option name="district" id="district" value="district">อำเภอ</option>
                                        <option name="subdistrict" id="subdistrict" value="subdistrict">ตำบล</option>
                                        <option name="farm" id="farm" value="farm">สวน</option>
                                        <option name="subfarm" id="subfarm" value="subfarm">แปลง</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <select class="form-control selectpicker" data-live-search="true"
                                        title="กรุณาเลือกเกษตรกร">
                                        <option value="">กรุณาเลือกเกษตรกร</option>
                                        <option name="farmer" id="farmer" value="farmer">เกษตรกร</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <select class="form-control selectpicker" data-live-search="true"
                                        title="กรุณาเลือกหัวข้อ">
                                        <option value="">กรุณาเลือกหัวข้อเวลา</option>
                                        <option name="year" id="year" value="year">ปี</option>
                                        <option name="month" id="month" value="month">เดือน</option>
                                        <option name="day" id="day" value="day">วัน</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span style="margin-left: 20px; color: <?= $color ?>;">เงื่อนไขหัวข้อ</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-4">
                                    <select class="form-control selectpicker" data-live-search="true" id="condition"
                                        title="กรุณาเลือกหัวข้อ" style="width:307px;">
                                        <option value="">ทั้งหมด</option>
                                        <option name="max" id="max" value="max">มากที่สุด</option>
                                        <option name="min" id="min" value="min">น้อยที่สุด</option>
                                    </select>
                                </div>
                                <div class="col-2 maxmin" style="margin-left:13px;">
                                    <input class="form-control" type="number" min="3" name="order" id="order" value="3">
                                </div>
                                <div class="col-2 maxmin">
                                    <label for="order" style="margin-top:8px;">ลำดับ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span style="margin-left: 20px; color: <?= $color ?>;">เลือกหน่วยวัด</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <select class="form-control selectpicker" data-live-search="true"
                                        title="กรุณาเลือกหัวข้อ">
                                        <option value="">กรุณาเลือกหน่วยวัด</option>
                                        <option name="water" id="water" value="water">ให้น้ำ</option>
                                        <option name="fertilize" id="fertilize" value="fertilize">ให้ปุ๋ย</option>
                                        <option name="cutbranch" id="cutbranch" value="cutbranch">ล้างคอขวด</option>
                                        <option name="pestcontrol" id="pestcontrol" value="pestcontrol">กำจัดวัชพืช
                                        </option>
                                        <option name="pest" id="pest" value="pest">ตรวจพบศัตรูพืช</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <select class="form-control selectpicker" data-live-search="true"
                                        title="กรุณาเลือกหัวข้อ">
                                        <option value="">กรุณาเลือกการคำนวณ</option>
                                        <option name="maximum" id="maximum" value="maximum">มากที่สุด (maximum)</option>
                                        <option name="minimum" id="minimum" value="minimum">น้อยที่สุด (minimum)
                                        </option>
                                        <option name="average" id="average" value="average">เฉลี่ย (average)</option>
                                        <option name="summary" id="summary" value="summary">ผลรวม (summary)</option>
                                        <option name="sd" id="sd" value="sd">ค่าส่วนเบี่ยงเบนมาตรฐาน (SD)</option>
                                        <option name="var" id="var" value="var">ค่าความแปรปรวน (VAR)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span style="margin-left: 20px; color: <?= $color ?>;">คัดกรอง</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <span style="margin-left: 20px;">แปลง</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">จังหวัด</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="pro1" name="s_pro" value="pro1">
                        <label for="pro1">ทุกจังหวัด</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="pro2" name="s_pro" value="pro2">
                        <label for="pro2">หลายจังหวัด</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="pro3" name="s_pro" value="pro3">
                        <label for="pro3">เฉพาะจังหวัด</label><br>
                    </div>
                </div>
                <div class="row manyprovince">
                    <div class="col-12">
                        <div class="row mb-4" id="Infolevel2">
                            <div class="col-sm-3 ">
                            </div>
                            <div class="col-sm-3 ">
                                จังหวัด
                                <br>
                                <ul class="list1 sortable " id="list1">
                                    <?php
										$ArrayInfoFarmer = getAllFarmer();
										for ($i = 1; $i <= count($ArrayInfoFarmer); $i++) {
											echo "<li NameF='{$ArrayInfoFarmer[$i]['FirstName']} {$ArrayInfoFarmer[$i]['LastName']}'>$i) {$ArrayInfoFarmer[$i]['FirstName']} {$ArrayInfoFarmer[$i]['LastName']}</li>";
										}
										?>
                                </ul>
                            </div>
                            <div class="col-sm-4 ">
                                จังหวัดที่เลือก
                                <br>
                                <ul class="list2 sortable" id="list2">

                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row oneprovince">
                    <div class="col-sm-3">
                    </div>
                    <div class="col-sm-3">
                        <select id="selectprovince" name="selectprovince" class="form-control " style="width: 200px;">
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

                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">อำเภอ</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="dist1" name="s_dist" value="dist1">
                        <label for="dist1">ทุกอำเภอ</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="dist2" name="s_dist" value="dist2">
                        <label for="dist2">หลายอำเภอ</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="dist3" name="s_dist" value="dist3">
                        <label for="dist3">เฉพาะอำเภอ</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">ตำบล</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="subdist1" name="s_subdist" value="subdist1">
                        <label for="subdist1">ทุกตำบล</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="subdist2" name="s_subdist" value="subdist2">
                        <label for="subdist2">หลายตำบล</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="subdist3" name="s_subdist" value="subdist3">
                        <label for="subdist3">เฉพาะตำบล</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">สวน</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="farm1" name="s_farm" value="farm1">
                        <label for="farm1">ทุกสวน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="farm2" name="s_farm" value="farm2">
                        <label for="farm2">หลายสวน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="farm3" name="s_farm" value="farm3">
                        <label for="farm3">เฉพาะสวน</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">แปลง</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="subfarm1" name="s_subfarm" value="subfarm1">
                        <label for="subfarm1">ทุกแปลง</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="subfarm2" name="s_subfarm" value="subfarm2">
                        <label for="subfarm2">หลายแปลง</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="subfarm3" name="s_subfarm" value="subfarm3">
                        <label for="subfarm3">เฉพาะแปลง</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 20px;">เกษตรกร</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">เกษตรกร</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="farmer1" name="s_farmer" value="farmer1">
                        <label for="farmer1">ทุกคน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="farmer2" name="s_farmer" value="farmer2">
                        <label for="farmer2">หลายคน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="farmer3" name="s_farmer" value="farmer3">
                        <label for="farmer3">เฉพาะคน</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 20px;">ระยะเวลากิจกรรม</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">ปี</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="year1" name="s_year" value="year1">
                        <label for="year1">ทุกปี</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="year2" name="s_year" value="year2">
                        <label for="year2">หลายปี</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="year3" name="s_year" value="year3">
                        <label for="year3">เฉพาะปี</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">เดือน</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="month1" name="s_month" value="month1">
                        <label for="month1">ทุกเดือน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="month2" name="s_month" value="month2">
                        <label for="month2">หลายเดือน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="month3" name="s_month" value="month3">
                        <label for="month3">เฉพาะเดือน</label><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <span style="margin-left: 60px;">วัน</span>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="day1" name="s_day" value="day1">
                        <label for="day1">ทุกวัน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="day2" name="s_day" value="day2">
                        <label for="day2">หลายวัน</label><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="radio" id="day3" name="s_day" value="day3">
                        <label for="day3">เฉพาะวัน</label><br>
                    </div>
                </div>
                <br>
                <div class="col-12">
                    <div class="card-footer" align="center">
                        <button type="button" id="search" name="search" class="btn"
                            style="background-color: <?= $color ?>; color:white; height:50px; width:100px;">ค้นหา
                            <i class="fas fa-search"></i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="Chart.js"></script>