<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Water";
$fsid = $_GET['FSID'] ?? "";
$active = $_GET['Active'] ?? "1";

include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$INFOSUBFARM =   getDetailLogSubFarm($fsid);
$INFOLOGRAIN = getLogRain($fsid);
$INFOLOGWATER = getLogWater($fsid);
$strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
// print_r($INFOSUBFARM);
?>

<link href='../../Calendar/packages/core/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/daygrid/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/timegrid/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/list/main.css' rel='stylesheet' />

<div hidden id="FSID" fsid="<?= $fsid ?>"></div>
<div class="container">

    <!------------ Start Head ------------>
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color: <?= $color ?>;">รายละเอียดการให้น้ำ</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="../UserProfile/UserProfile.php">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="Water.php">การให้น้ำ</a>
                                <span> > </span>
                                <a class="link-path link-active" id="detail2" href="" style="color: <?= $color ?>;">รายละเอียดการให้น้ำ</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-xl-6 col-12">
            <div class="card " style="height: 350px">
                <div class="card-body" height="166px" id="for_card">
                    <div class="row">
                        <?php
                        if ($INFOSUBFARM[1]['iconSubfarm'] == "default.png") {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/subfarm/0/defultSubFarm.png\" >";
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/subfarm/{$INFOSUBFARM[1]['FSID']}/{$INFOSUBFARM[1]['iconSubfarm']}\" >";
                        }
                        ?>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อสวนปาล์ม : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOSUBFARM[1]['NameFarm'] ?></span>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อแปลง : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOSUBFARM[1]['NameSubfarm'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card" style="height: 350px">
                <div class="card-body" height="166px" id="card_height">
                    <div class="row">
                        <?php
                        if ($INFOSUBFARM[1]['iconFarmmer'] == "default.jpg") {
                            if ($INFOSUBFARM[1]['Title'] == 1) {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/man.jpg\" >";
                            } else {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/woman.jpg\" >";
                            }
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/{$INFOSUBFARM[1]['UFID']}/{$INFOSUBFARM[1]['iconFarmmer']}\" >";
                        }
                        ?>
                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="row mb-3">

                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-4 col-3 text-right">
                            <span>เกษตรกร : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span> <?php echo $INFOSUBFARM[1]['FullName'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!------------ Start Calender ------------>
    <div class="row mt-3">
        <div class="col-xl-12 col-12">
            <div class="card">
                <!------------ Head ------------>
                <div class="card-header card-bg">
                    <span>ข้อมูลรายละเอียดการให้น้ำ</span>
                </div>
                <div class="card-body">

                    <!------------  Tab Bar ------------>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link linkCalendar1 <?php if ($active == 1) echo "active"; ?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">ปฏิทินการให้น้ำ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link linkCalendar2 <?php if ($active == 2) echo "active"; ?>" id="home-tab2" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">ปฏิทินการขาดน้ำ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($active == 3) echo "active"; ?>" id="profile-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">ตารางข้อมูลฝนตก</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($active == 4) echo "active"; ?>" id="profile-tab2" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">ตารางข้อมูลการรดน้ำ</a>
                        </li>
                    </ul>
                    <!------------ Body ------------>
                    <div class="tab-content" id="myTabContent" style="margin-top:20px;">

                        <!------------ Start Calender ------------>
                        <div class="tab-pane fade  show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class='row ac1 ac'>
                                <div class='col-12 mb-3' id="headcalendar1">
                                    <div id='calendar1' style=" margin: 0 auto; width: 100%;background-color: #FFFFFF;">
                                    </div>
                                </div>
                            </div>
                            <div class='row ac2 ac'>
                                <div class='col-12 mb-3' id="headcalendar2">
                                    <div id='calendar2' style=" margin: 0 auto; width: 100%;background-color: #FFFFFF;">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 ac ac3">
                                <div class="col-12">
                                    <button id="btn-modal1" type="button" style="float:right;" class="btn btn-success " data-toggle="modal" data-target="#modal-1"><i class="fas fa-plus"></i> เพิ่มปริมาณฝนตก</button>
                                </div>
                            </div>
                            <div class="row mt-4 ac ac3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <!------- Start DataTable ------->
                                        <table id="example1" class="table table-bordered table-data tableSearch">
                                            <thead>
                                                <tr>
                                                    <th>วันที่ฝนตก</th>
                                                    <th>ช่วงเวลาที่ฝนตก</th>
                                                    <th>ระยะเวลาที่ฝนตก (นาที)</th>
                                                    <th>ปริมาณฝน (มม.)</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>วันที่ฝนตก</th>
                                                    <th>ช่วงเวลาที่ฝนตก</th>
                                                    <th>ระยะเวลาที่ฝนตก (นาที)</th>
                                                    <th>ปริมาณฝน (มม.)</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                for ($i = 1; $i < count($INFOLOGRAIN); $i++) {
                                                    echo "  <tr>
                                                                <td class=\"text-center\">{$INFOLOGRAIN[$i]['dd']} " . $strMonthCut[$INFOLOGRAIN[$i]['Month']] . " {$INFOLOGRAIN[$i]['Year2']}</td>
                                                                <td class=\"text-center\">" . date("H:i", $INFOLOGRAIN[$i]['StartTime']) . " - " . date("H:i", $INFOLOGRAIN[$i]['StopTime']) . "</td>
                                                                <td class=\"text-right\">{$INFOLOGRAIN[$i]['Period']}</td>
                                                                <td class=\"text-right\">{$INFOLOGRAIN[$i]['Vol']}</td>
                                                                <td class=\"text-center\">
                                                                    <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete tt\"   logid=\"{$INFOLOGRAIN[$i]['LogID']}\"  info=\"ฝนตก\" typeid=\"3\"  logdate=\"{$INFOLOGRAIN[$i]['dd']} {$strMonthCut[$INFOLOGRAIN[$i]['Month']]} {$INFOLOGRAIN[$i]['Year2']}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                                                                </td>
                                                            </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 ac ac4">
                                <div class="col-12">
                                    <button id="btn-modal2" type="button" style="float:right;" class="btn btn-success " data-toggle="modal" data-target="#modal-2"><i class="fas fa-plus"></i> เพิ่มระบบให้น้ำ</button>
                                </div>
                            </div>
                            <div class="row mt-4 ac ac4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <!------- Start DataTable2 ------->
                                        <table id="example1" class="table table-bordered table-data tableSearch">
                                            <thead>
                                                <tr>
                                                    <th>วันที่รดน้ำ</th>
                                                    <th>ช่วงเวลาที่รดน้ำ</th>
                                                    <th>ระยะเวลาที่รดน้ำ (นาที)</th>
                                                    <th>ปริมาณที่รดน้ำ (ลิตร)</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>วันที่รดน้ำ</th>
                                                    <th>ช่วงเวลาที่รดน้ำ</th>
                                                    <th>ระยะเวลาที่รดน้ำ (นาที)</th>
                                                    <th>ปริมาณที่รดน้ำ (ลิตร)</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                for ($i = 1; $i < count($INFOLOGWATER); $i++) {
                                                    echo "  <tr>
                                                                <td class=\"text-center\">{$INFOLOGWATER[$i]['dd']} " . $strMonthCut[$INFOLOGWATER[$i]['Month']] . " {$INFOLOGWATER[$i]['Year2']}</td>
                                                                <td class=\"text-center\">" . date("H:i", $INFOLOGWATER[$i]['StartTime']) . " - " . date("H:i", $INFOLOGWATER[$i]['StopTime']) . "</td>
                                                                <td class=\"text-right\">{$INFOLOGWATER[$i]['Period']}</td>
                                                                <td class=\"text-right\">{$INFOLOGWATER[$i]['Vol']}</td>
                                                                <td class=\"text-center\">
                                                                    <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete tt\"   logid=\"{$INFOLOGWATER[$i]['LogID']}\"  info=\"การรดน้ำ\" typeid=\"4\"   logdate=\"{$INFOLOGWATER[$i]['dd']} {$strMonthCut[$INFOLOGWATER[$i]['Month']]} {$INFOLOGWATER[$i]['Year2']}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                                                                </td>
                                                            </tr>";
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

<?php include_once("./WaterDetailModal.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>
<script src='../../Calendar/packages/core/main.js'></script>
<script src='../../Calendar/packages/interaction/main.js'></script>
<script src='../../Calendar/packages/daygrid/main.js'></script>
<script src='../../Calendar/packages/timegrid/main.js'></script>
<script src='../../Calendar/packages/list/main.js'></script>
<script src='../../Calendar/packages/bootstrap/main.js'></script>
<script src='../../Calendar/packages/core/locales-all.js'></script>
<script>
    //////////////////////////////////////////////////////////  

    $(document).ready(function() {
        var thisday = new Date().toJSON().slice(0, 10);
        var calendarEl1 = document.getElementById('calendar1');
        var calendar1 = new FullCalendar.Calendar(calendarEl1, {
            disableResizing: true,
            locale: 'th',
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap'],
            themeSystem: 'bootstrap',
            timezone: "Asia/Bangkok",
            header: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: ' dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                list: 'รายละเอียด'
            },

            eventStartEditable: false,
            disableDragging: true,
            editable: false,
            defaultDate: thisday,
            navLinks: true,
            businessHours: true,
            events: <?php echo getTextEventWatering($fsid) ?>
        });
        calendar1.render();
        var calendarEl2 = document.getElementById('calendar2');
        var calendar2 = new FullCalendar.Calendar(calendarEl2, {
            disableResizing: true,
            locale: 'th',
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap'],
            themeSystem: 'bootstrap',
            timezone: "Asia/Bangkok",
            header: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: ' dayGridMonth,timeGridWeek'
            },
            buttonText: {
                list: 'รายละเอียด'
            },
            displayEventTime: false,
            eventStartEditable: false,
            disableDragging: true,
            editable: false,
            defaultDate: thisday,
            navLinks: true,
            businessHours: true,
            events: <?php echo getTextEventDry($fsid) ?>
        });
        calendar2.render();

        /////////////////////////////////////////////////////
        $(".ac").hide();
        $(".ac<?php echo $active ?>").show();

        $(document).on("click", "#home-tab", function() {
            $(".ac").hide();
            $(".ac1").show();
        });
        $(document).on("click", "#home-tab2", function() {
            $(".ac").hide();
            $(".ac2").show();
        });
        $(document).on("click", "#profile-tab", function() {
            $(".ac").hide();
            $(".ac3").show();
        });
        $(document).on("click", "#profile-tab2", function() {
            $(".ac").hide();
            $(".ac4").show();
        });
    });
</script>
<script src='./test.js'></script>