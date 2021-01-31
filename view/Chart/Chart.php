<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Chart";
include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$PROVINCE = getProvince();
$FARMER = getFarmerAll();
$YEAR = getYearAgriMap();
$MONTH = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

?>
<style>
.graph {
    width: 100px;
    height: 100px;
}

.sortable {
    list-style-type: none;
    list-style-position: inside;
    margin: 0px 12px 8px 0px;
    width: 80%;
    height: 200px;
    padding: 2px;
    border-width: 1px;
    border-style: solid;
    min-height: 100px;
    overflow: scroll;

}

.sortable li {
    margin: 3px 3px 3px 3px;
    font-size: 1em;
    height: 18px;
    padding-bottom: 30px;
    padding-left: 10px;
    border: 2px dashed #d3d3d3;
    background-color: #eee;
    cursor: pointer;
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
            <form onsubmit="return false;">
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
                                    <input type="radio" id="table" name="present" value="table" checked="checked">
                                </div>
                                <div class="row mb-2">
                                    <img class="graph" src="./chart/table2.png">
                                </div>
                                <div class="row mb-2">
                                    <label for="table">ตาราง</label>
                                </div>
                                <div class="row mb-2">
                                    <input type="radio" id="multibar" name="present" value="multi_bar">
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
                                    <input type="radio" id="complexbar" name="present" value="complex_bar">
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
                                    <input type="radio" id="multiline" name="present" value="multi_line">
                                </div>
                                <div class="row mb-2">
                                    <img class="graph" src="./chart/graph-21.png">
                                </div>
                                <div class="row mb-2">
                                    <label for="multiline">กราฟหลายเส้น</label>
                                </div>
                                <div class="row mb-2">
                                    <input type="radio" id="multiarea" name="present" value="multi_area">
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
                                    <input type="radio" id="radar" name="present" value="chart_radar">
                                </div>
                                <div class="row mb-2">
                                    <img class="graph" src="./chart/diagram.png">
                                </div>
                                <div class="row mb-2">
                                    <label for="radar">กราฟแมงมุม</label>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-2">
                            <div class="card-body">
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
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <span style="margin-left: 20px; color: <?= $color ?>;"
                                        id="chose_label_span1">เลือกหัวข้อหลัก</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    title="กรุณาเลือกหัวข้อ" id="chose_label1" required>
                                                    <option value="" code="0">กรุณาเลือกหัวข้อ</option>
                                                    <option name="province" id="province" value="Province" code="1">
                                                        จังหวัด
                                                    </option>
                                                    <option name="district" id="district" value="Distrinct" code="2">
                                                        อำเภอ
                                                    </option>
                                                    <option name="subdistrict" id="subdistrict" value="SubDistrinct"
                                                        code="3">ตำบล
                                                    </option>
                                                    <option name="farm" id="farm" value="F_name" code="4">สวน</option>
                                                    <option name="subfarm" id="subfarm" value="SF_name" code="5">แปลง
                                                    </option>
                                                    <option name="farmer" id="farmer" value="FM_name" code="6">เกษตรกร
                                                    </option>
                                                    <option name="year" id="year" value="Year2" code="7">ปี</option>
                                                    <option name="month" id="month" value="Month" code="8">เดือน
                                                    </option>
                                                    <option name="day" id="day" value="dd" code="9">วัน</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4" id="multi_chart">
                            <div class="row">
                                <div class="col-12">
                                    <span style="margin-left: 20px; color: <?= $color ?>;"
                                        id="chose_label_span2">เลือกหัวข้อย่อย</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    title="กรุณาเลือกหัวข้อ" id="chose_label2" required>
                                                    <option value="" code="0">กรุณาเลือกหัวข้อ</option>
                                                    <option name="province_2" id="province_2" value="Province" code="1">
                                                        จังหวัด</option>
                                                    <option name="district_2" id="district_2" value="Distrinct"
                                                        code="2">อำเภอ</option>
                                                    <option name="subdistrict_2" id="subdistrict_2" value="SubDistrinct"
                                                        code="3">ตำบล</option>
                                                    <option name="farm_2" id="farm_2" value="F_name" code="4">สวน
                                                    </option>
                                                    <option name="year_2" id="year_2" value="Year2" code="7">ปี</option>
                                                    <option name="month_2" id="month_2" value="Month" code="8">เดือน
                                                    </option>
                                                    <option name="day_2" id="day_2" value="dd" code="9">วัน</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" >
                        <div class="col-8" id="normal_chart">
                            <div class="row">
                                <div class="col-12">
                                    <span style="margin-left: 20px; color: <?= $color ?>;">เงื่อนไขหัวข้อ</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-4">
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="chose_cond" title="กรุณาเลือกหัวข้อ" style="width:246px;">
                                                    <option value="">ทั้งหมด</option>
                                                    <option name="max" id="max" value="DESC">มากที่สุด</option>
                                                    <option name="min" id="min" value="ASC">น้อยที่สุด</option>
                                                </select>
                                            </div>
                                            <div class="col-2 maxmin" style="margin-left:13px;">
                                                <input class="form-control" type="number" min="3" name="order"
                                                    id="order" value="3">
                                            </div>
                                            <div class="col-2 maxmin">
                                                <label for="order" style="margin-top:8px;">ลำดับ</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-8">
                            <div class="row">
                                <div class="col-12">
                                    <span style="margin-left: 20px; color: <?= $color ?>;">เงื่อนไขหัวข้อ</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-4">
                                                <select class="form-control selectpicker" data-live-search="true"
                                                    id="chose_cond2" title="กรุณาเลือกหัวข้อ" style="width:246px;">
                                                    <option value="">ทั้งหมด</option>
                                                    <option name="max2" id="max2" value="max">มากที่สุด</option>
                                                    <option name="min2" id="min2" value="min">น้อยที่สุด</option>
                                                </select>
                                            </div>
                                            <div class="col-2 maxmin2" style="margin-left:13px;">
                                                <input class="form-control" type="number" min="3" name="order2"
                                                    id="order2" value="3">
                                            </div>
                                            <div class="col-2 maxmin2">
                                                <label for="order2" style="margin-top:8px;">ลำดับ</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
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
                                            title="กรุณาเลือกหัวข้อ" id="chose_type" required>
                                            <option value="" code="0">กรุณาเลือกหน่วยวัด</option>
                                            <option name="water1" id="water1" value="water1" code="1">วันให้น้ำ</option>
                                            <option name="water2" id="water2" value="water2" code="2">วันขาดน้ำ</option>
                                            <!-- <option name="water3" id="water3" value="water3">ปริมาตรให้น้ำ</option> -->
                                            <option name="fertilize1" id="fertilize1" value="fertilize1" code="3">
                                                จำนวนครั้งให้ปุ๋ย</option>
                                            <option name="fertilize2" id="fertilize2" value="fertilize2" code="4">
                                                ปริมาณธาตุอาหาร (หลัก/รอง)
                                            </option>
                                            <option name="fertilize3" id="fertilize3" value="fertilize3" code="5">
                                                ปริมาณแต่ละธาตุอาหารหลัก
                                            </option>
                                            <option name="fertilize4" id="fertilize4" value="fertilize4" code="6">
                                                ปริมาณแต่ละธาตุอาหารรอง
                                            </option>
                                            <option name="cutbranch" id="cutbranch" value="cutbranch" code="7">ล้างคอขวด
                                            </option>
                                            <option name="pestcontrol" id="pestcontrol" value="pestcontrol" code="8">
                                                กำจัดวัชพืช
                                            </option>
                                            <option name="pest" id="pest" value="pest" code="9">ตรวจพบศัตรูพืช</option>
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
                                            title="กรุณาเลือกหัวข้อ" id="chose_cal" required>
                                            <option value="" code="0">กรุณาเลือกการคำนวณ</option>
                                            <option name="maximum" id="maximum" value="MAX" show="มากที่สุด" code="1">
                                                มากที่สุด (maximum)
                                            </option>
                                            <option name="minimum" id="minimum" value="MIN" show="น้อยที่สุด" code="2">
                                                น้อยที่สุด (minimum)
                                            </option>
                                            <option name="average" id="average" value="AVG" show="เฉลี่ย" code="3">
                                                เฉลี่ย (average)</option>
                                            <option name="summary" id="summary" value="SUM" show="ผลรวม" code="4">ผลรวม
                                                (summary)</option>
                                            <option name="sd" id="sd" value="STDDEV" show="ค่าส่วนเบี่ยงเบนมาตรฐาน"
                                                code="5">ค่าส่วนเบี่ยงเบนมาตรฐาน (SD)</option>
                                            <!-- <option name="var" id="var" value="VAR">ค่าความแปรปรวน (VAR) -->
                                            </option>
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
                            <input type="radio" id="pro1" name="s_pro" value="pro1" checked="checked">
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
                                    <ul class="province_list1 sortable " id="province_list1">
                                        <?php
										$ArrayInfo = getProvince();
										for ($i = 1; $i <= $ArrayInfo[0]['numrow']; $i++) {
											echo "<li Name='{$ArrayInfo[$i]['Province']}' id_attr='{$ArrayInfo[$i]['AD1ID']}'>{$ArrayInfo[$i]['Province']} </li>";
										}
										?>
                                    </ul>
                                </div>
                                <div class="col-sm-4 ">
                                    จังหวัดที่เลือก
                                    <br>
                                    <ul class="province_list2 sortable" id="province_list2">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row oneprovince">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectprovince" name="selectprovince" class="form-control "
                                style="width: 200px;">
                                <option selected value=0>เลือกจังหวัด</option>
                                <?php
                                for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                    echo '<option value="'.$PROVINCE[$i]["AD1ID"].'">' . $PROVINCE[$i]["Province"] . '</option>';
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
                            <input class="set_pro" type="radio" id="dist1" name="s_dist" value="dist1">
                            <label for="dist1">ทุกอำเภอ</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro" type="radio" id="dist2" name="s_dist" value="dist2">
                            <label for="dist2">หลายอำเภอ</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro" type="radio" id="dist3" name="s_dist" value="dist3">
                            <label for="dist3">เฉพาะอำเภอ</label><br>
                        </div>
                    </div>
                    <div class="row manydist">
                        <div class="col-12">
                            <div class="row mb-4" id="Infolevel2">
                                <div class="col-sm-3 ">
                                </div>
                                <div class="col-sm-3 ">
                                    อำเภอ
                                    <br>
                                    <ul class="dist_list1 sortable " id="dist_list1">
                                    </ul>
                                </div>
                                <div class="col-sm-4 ">
                                    อำเภอที่เลือก
                                    <br>
                                    <ul class="dist_list2 sortable" id="dist_list2">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row onedist">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectdist" name="selectdist" class="form-control " style="width: 200px;">
                                <option selected value=0>เลือกอำเภอ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <span style="margin-left: 60px;">ตำบล</span>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist" type="radio" id="subdist1" name="s_subdist"
                                value="subdist1">
                            <label for="subdist1">ทุกตำบล</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist" type="radio" id="subdist2" name="s_subdist"
                                value="subdist2">
                            <label for="subdist2">หลายตำบล</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist" type="radio" id="subdist3" name="s_subdist"
                                value="subdist3">
                            <label for="subdist3">เฉพาะตำบล</label><br>
                        </div>
                    </div>
                    <div class="row manysubdist">
                        <div class="col-12">
                            <div class="row mb-4" id="Infolevel2">
                                <div class="col-sm-3 ">
                                </div>
                                <div class="col-sm-3 ">
                                    ตำบล
                                    <br>
                                    <ul class="subdist_list1 sortable " id="subdist_list1">
                                    </ul>
                                </div>
                                <div class="col-sm-4 ">
                                    ตำบลที่เลือก
                                    <br>
                                    <ul class="subdist_list2 sortable" id="subdist_list2">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row onesubdist">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectsubdist" name="selectsubdist" class="form-control " style="width: 200px;">
                                <option selected value=0>เลือกตำบล</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <span style="margin-left: 60px;">สวน</span>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist set_subdist" type="radio" id="farm1" name="s_farm"
                                value="farm1">
                            <label for="farm1">ทุกสวน</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist set_subdist" type="radio" id="farm2" name="s_farm"
                                value="farm2">
                            <label for="farm2">หลายสวน</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist set_subdist" type="radio" id="farm3" name="s_farm"
                                value="farm3">
                            <label for="farm3">เฉพาะสวน</label><br>
                        </div>
                    </div>
                    <div class="row manyfarm">
                        <div class="col-12">
                            <div class="row mb-4" id="Infolevel2">
                                <div class="col-sm-3 ">
                                </div>
                                <div class="col-sm-3 ">
                                    สวน
                                    <br>
                                    <ul class="farm_list1 sortable " id="farm_list1">
                                    </ul>
                                </div>
                                <div class="col-sm-4 ">
                                    สวนที่เลือก
                                    <br>
                                    <ul class="dist_list2 sortable" id="dist_list2">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row onefarm">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectfarm" name="selectfarm" class="form-control " style="width: 200px;">
                                <option selected value=0>เลือกสวน</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <span style="margin-left: 60px;">แปลง</span>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist set_subdist set_farm" type="radio" id="subfarm1"
                                name="s_subfarm" value="subfarm1">
                            <label for="subfarm1">ทุกแปลง</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist set_subdist set_farm" type="radio" id="subfarm2"
                                name="s_subfarm" value="subfarm2">
                            <label for="subfarm2">หลายแปลง</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_pro set_dist set_subdist set_farm" type="radio" id="subfarm3"
                                name="s_subfarm" value="subfarm3">
                            <label for="subfarm3">เฉพาะแปลง</label><br>
                        </div>
                    </div>
                    <div class="row manysubfarm">
                        <div class="col-12">
                            <div class="row mb-4" id="Infolevel2">
                                <div class="col-sm-3 ">
                                </div>
                                <div class="col-sm-3 ">
                                    แปลง
                                    <br>
                                    <ul class="subfarm_list1 sortable " id="subfarm_list1">
                                    </ul>
                                </div>
                                <div class="col-sm-4 ">
                                    แปลงที่เลือก
                                    <br>
                                    <ul class="subfarm_list2 sortable" id="subfarm_list2">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row onesubfarm">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectsubfarm" name="selectsubfarm" class="form-control " style="width: 200px;">
                                <option selected value=0>เลือกแปลง</option>
                            </select>
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
                            <input type="radio" id="farmer1" name="s_farmer" value="farmer1" checked="checked">
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
                    <div class="row manyfarmer">
                        <div class="col-12">
                            <div class="row mb-4" id="Infolevel2">
                                <div class="col-sm-3 ">
                                </div>
                                <div class="col-sm-3 ">
                                    เกษตรกร
                                    <br>
                                    <ul class="farmer_list1 sortable " id="farmer_list1">
                                        <?php
										$ArrayInfo = getFarmerAll();
										for ($i = 1; $i <= $ArrayInfo[0]['numrow']; $i++) {
											echo "<li Name='{$ArrayInfo[$i]['FullName']}' id_attr='{$ArrayInfo[$i]['dbID']}'>{$ArrayInfo[$i]['FullName']} </li>";
										}
								?>
                                    </ul>
                                </div>
                                <div class="col-sm-4 ">
                                    เกษตรกรที่เลือก
                                    <br>
                                    <ul class="farmer_list2 sortable" id="farmer_list2">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row onefarmer">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectfarmer" name="selectfarmer" class="form-control " style="width: 200px;">
                                <option selected value=0>เลือกเกษตรกร</option>
                                <?php
                                for ($i = 1; $i < sizeof($FARMER); $i++) {
                                    echo '<option value="' . $FARMER[$i]["dbID"] . '">' . $FARMER[$i]["FullName"] . '</option>';
                                }
                            ?>
                            </select>
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
                            <input type="radio" id="year1" name="s_year" value="year1" checked="checked">
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
                    <div class="row manyyear">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-6 form-inline">
                            <label for="">ตั้งแต่ </label>
                            <select id="selectyear1" name="selectyear1" class="form-control "
                                style="margin-left: 5px; width: 150px;">
                                <!-- <option selected value=0>เลือกปี</option> -->
                                <?php
                                for ($i = 1; $i <= $YEAR[0]["numrow"]; $i++) {
                                    echo '<option value="' . $YEAR[$i]["Year2"] . '">' . $YEAR[$i]["Year2"] . '</option>';
                                }
                            ?>
                            </select>
                            <label for="" style="margin-left:5px;">ถึง </label>
                            <select id="selectyear2" name="selectyear2" class="form-control "
                                style="margin-left: 5px; width: 150px;">
                                <!-- <option selected value=0>เลือกปี</option> -->
                                <?php
                                for ($i = 2; $i <= $YEAR[0]["numrow"]; $i++) {
                                    echo '<option value="' . $YEAR[$i]["Year2"] . '">' . $YEAR[$i]["Year2"] . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="row oneyear">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectyear" name="selectyear" class="form-control " style="width: 200px;">
                                <!-- <option selected value=0>เลือกปี</option> -->
                                <?php
                                for ($i = 1; $i < sizeof($YEAR); $i++) {
                                    echo '<option value="' . $YEAR[$i]["Year2"] . '">' . $YEAR[$i]["Year2"] . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                        <label for="" id="minyear" hidden><?php echo $YEAR[1]["Year2"]; ?></label>
                        <label for="" id="maxyear" hidden><?php echo $YEAR[sizeof($YEAR)-1]["Year2"]; ?></label>

                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <span style="margin-left: 60px;">เดือน</span>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_year" type="radio" id="month1" name="s_month" value="month1">
                            <label for="month1">ทุกเดือน</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_year" type="radio" id="month2" name="s_month" value="month2">
                            <label for="month2">หลายเดือน</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_year" type="radio" id="month3" name="s_month" value="month3">
                            <label for="month3">เฉพาะเดือน</label><br>
                        </div>
                    </div>
                    <div class="row manymonth">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-6 form-inline">
                            <label for="">ตั้งแต่ </label>
                            <select id="selectmonth1" name="selectmonth1" class="form-control "
                                style="margin-left: 5px; width: 150px;">
                                <!-- <option selected value=0>เลือกเดือน</option> -->
                                <?php
                                for ($i = 0; $i < sizeof($MONTH); $i++) {
                                    echo '<option value="' . ($i+1). '">' . $MONTH[$i]. '</option>';
                                }
                            ?>
                            </select>
                            <label for="" style="margin-left:5px;">ถึง </label>
                            <select id="selectmonth2" name="selectmonth2" class="form-control "
                                style="margin-left: 5px; width: 150px;">
                                <!-- <option selected value=0>เลือกเดือน</option> -->
                                <?php
                                for ($i = 1; $i < sizeof($MONTH); $i++) {
                                    echo '<option value="' . ($i+1). '">' . $MONTH[$i]. '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="row onemonth">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <select id="selectmonth" name="selectmonth" class="form-control " style="width: 200px;">
                                <!-- <option selected value=0>เลือกเดือน</option> -->
                                <?php
                                for ($i = 0; $i < sizeof($MONTH); $i++) {
                                    echo '<option value="' . ($i+1). '">' . $MONTH[$i]. '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <span style="margin-left: 60px;">วัน</span>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_year set_month" type="radio" id="day1" name="s_day" value="day1">
                            <label for="day1">ทุกวัน</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_year set_month" type="radio" id="day2" name="s_day" value="day2">
                            <label for="day2">หลายวัน</label><br>
                        </div>
                        <div class="col-sm-3">
                            <input class="set_year set_month" type="radio" id="day3" name="s_day" value="day3">
                            <label for="day3">เฉพาะวัน</label><br>
                        </div>
                    </div>
                    <div class="row manyday">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-6 form-inline">
                            <label for="">ตั้งแต่ </label>
                            <input style="width: 100px; margin-left:5px;" class="form-control" type="number"
                                id="selectday1" name="selectday1" min="1" max="30" value="1">
                            <label for="" style="margin-left:5px;">ถึง </label>
                            <input style="width: 100px; margin-left:5px;" class="form-control" type="number"
                                id="selectday2" name="selectday2" min="1" max="30" value="2">
                        </div>
                    </div>
                    <div class="row oneday">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-3">
                            <input style="width: 100px;" class="form-control" type="number" id="selectday"
                                name="selectday" min="1" max="31" value="1">
                        </div>
                    </div>
                    </br>
                    <label style="color: #d9534f; text-align: center;">การประมวลผลอาจใช้เวลานาน</label>
                    <div class="col-12">
                        <div class="card-footer" align="center">
                            <button type="submit" id="setsubmit" name="setsubmit" class="btn"
                                style="background-color: <?= $color ?>; color:white; height:50px; width:100px;">นำเสนอ
                                <i class="fas fa-search"></i> </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 col-12 mb-6">
            <div id="show_chart">
                <div class="card shadow mb-4">
                    <div class="card-header card-header-table py-3">
                        <h6 class="m-0 headshow" style="color:#006633; text-align:center;" name="headshow"></h6>
                    </div>
                    <div class="card-body" id="yes_table">
                        <button class="btn btn-success" id="export">Export Excel</button>
                         <div class="table-responsive" style="margin-top:10px">
                            <table class="table table-bordered table-data" id="dataTable_table" style="table-layout: fixed;" width="100%" cellspacing="0"> 
                            </table>
                        </div>
                    </div>
                    <div class="card-body" id="no_table">
                        <div class="row">
                            <div class="col-7">
                                <canvas id="chartjs"><canvas>
                            </div>
                            <div class="col-5">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-data" id="dataTable" width="100%" cellspacing="0"> 
                                    </table>
                                </div>
                            </div>                            
                        </div>                         
                    </div>
                </div>
            </div>
            <div class="card-header card-bg " id="show_error" style="color: #d9534f; text-align: center;">
                คัดกรองข้อมูลไม่ถูกต้อง
            </div>
            <div class="card-header card-bg " id="show_nodata" style="color: #d9534f; text-align: center;">
                <div class="card">
                    <div class="card-header card-bg headshow " name="headshow" style="color: <?= $color ?>; text-align: center;">
                        
                    </div>
                </div>
                <div class="card">
                    ไม่มีข้อมูล
                </div>
            </div>
            <center class="form-control" id="show_loading" style="height: 110px;">
                <img src="./chart/loading.gif" alt="Loading..." style="width: 70px; height: 70px; "><br>
                <label for=""></label> กำลังโหลดข้อมูล...
            </center>
        </div>
    </div>
</div>

<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src='./../../js/excelexportjs.js'></script>
<script src="Chart.js"></script>