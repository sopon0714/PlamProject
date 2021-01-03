<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "FertilisingList";
$fsid = $_GET['FSID'] ?? "";
$active = $_GET['Active'] ?? "1";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$INFOFERTILISING = getinfoFertilisingDetail($fsid);
$INFOSUBFARM =   getDetailLogSubFarm($fsid);
$YEAR = getYear($fsid, false);
$INFONUTR  = getInfoNutr();
$FERLIST = getFertilizerList();
$strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
// print_r($INFOSUBFARM);
?>
<style>
    textarea {
        overflow-y: scroll;
        height: 100px;
        resize: vertical;
    }

    .img-reletive input[type=file] {
        cursor: pointer;
        width: 100px;
        height: 100px;
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .croppie-container .cr-boundary {
        width: 350px;
        height: 220px;
    }

    .upload-demo2 {
        width: 350px;
        height: 250px;
    }

    .img-gal {
        width: 150px;
        height: 100px;
        z-index: 5;
    }

    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
</style>
<link href='../../Calendar/packages/core/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/daygrid/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/timegrid/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/list/main.css' rel='stylesheet' />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<div hidden id="FSID" fsid="<?= $fsid ?>"></div>
<div hidden id="NumTree" NumTree="<?= $INFOSUBFARM[1]['NumTree'] ?>"></div>
<div class="container">

    <!------------ Start Head ------------>
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color: <?= $color ?>;">รายละเอียดการให้ปุ๋ย</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="../UserProfile/UserProfile.php">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="FertilisingList.php">การให้ปุ๋ย</a>
                                <span> > </span>
                                <a class="link-path link-active" id="detail2" href="" style="color: <?= $color ?>;">รายละเอียดการให้ปุ๋ย</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="maxyear" value="<?php echo  $YEAR[1]['Year2']; ?>">
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
                    <span>ข้อมูลรายละเอียดการให้ปุ๋ย</span>
                </div>
                <div class="card-body">

                    <!------------  Tab Bar ------------>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link linkCalendar1 <?php if ($active == 1) echo "active"; ?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">ปฏิทินการให้ปุ๋ย</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($active == 3) echo "active"; ?>" id="profile-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">ตารางข้อมูลการให้ปุ๋ย</a>
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
                            <div class="row mt-4 ac ac3">
                                <div class="col-12">
                                    <button id="btn-modal1" type="button" style="float:right;" class="btn btn-success " data-toggle="modal" data-target="#modal-1"><i class="fas fa-plus"></i> เพิ่มการให้ปุ๋ย</button>
                                </div>
                            </div>
                            <div class="row mt-4 ac ac3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <!------- Start DataTable ------->
                                        <table id="example1" class="table table-bordered table-data tableSearch">
                                            <thead>
                                                <tr>
                                                    <th>วันที่ให้ปุ๋ย</th>
                                                    <th>ชนิดปุ๋ย</th>
                                                    <th>ปริมาณ</th>
                                                    <th>หน่วย</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>วันที่ให้ปุ๋ย</th>
                                                    <th>ชนิดปุ๋ย</th>
                                                    <th>ปริมาณ</th>
                                                    <th>หน่วย</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                for ($i = 1; $i <= $INFOFERTILISING[0]['numrow']; $i++) {
                                                    echo "  <tr>
                                                                <td class=\"text-center\">{$INFOFERTILISING[$i]['day']} " . $strMonthCut[$INFOFERTILISING[$i]['Month']] . " {$INFOFERTILISING[$i]['Year2']}</td>
                                                                <td class=\"text-left\">{$INFOFERTILISING[$i]['Name']}</td>
                                                                <td class=\"text-right\">{$INFOFERTILISING[$i]['Vol']}</td>
                                                                <td class=\"text-center\">{$INFOFERTILISING[$i]['Unit']}</td>
                                                                <td class=\"text-center\">
                                                                <button type=\"button\" class=\"btn btn-info btn-sm btn-photo tt \"  lid=\"{$INFOFERTILISING[$i]['ID']}\" title=\"รูปภาพ\"><i class=\"fas fa-images\"></i></button>
                                                                    <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete tt\" fer=\"{$INFOFERTILISING[$i]['Name']}\"   logid=\"{$INFOFERTILISING[$i]['ID']}\"    logdate=\"{$INFOFERTILISING[$i]['day']} {$strMonthCut[$INFOFERTILISING[$i]['Month']]} {$INFOFERTILISING[$i]['Year2']}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
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

    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-10">
                            <span>ปริมาณธาตุอาหารที่ใส่ต่อแปลง( <?php echo $INFOSUBFARM[1]['NumTree'] ?> ต้น) </span>
                        </div>
                        <div class="col-2">
                            <select id="year" name="year" class="form-control">
                                <?php
                                for ($i = 1; $i < count($YEAR); $i++) {
                                    echo "<option value=\"{$YEAR[$i]['Year2']}\">{$YEAR[$i]['Year2']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xl-12 col-12 PDM">
                            <canvas id="VolNutr" style="height:150px;"></canvas>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <!------- Start DataTable ------->
                                <table id="example2" class="table table-bordered table-data ">
                                    <thead>
                                        <tr>
                                            <th>ชื่อธาตุอาหาร</th>
                                            <th>ประเภท</th>
                                            <th>ปริมาณที่ต้องการ</th>
                                            <th>ปริมาณที่ใส่</th>
                                            <th>ปริมาณที่ต้องใส่เพิ่ม</th>
                                            <th>หน่วย</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ชื่อธาตุอาหาร</th>
                                            <th>ประเภท</th>
                                            <th>ปริมาณที่ต้องการ</th>
                                            <th>ปริมาณที่ใส่</th>
                                            <th>ปริมาณที่ต้องใส่เพิ่ม</th>
                                            <th>หน่วย</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="DetailTable">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-10">
                            <span>คำนวณการใส่ปุ๋ยปี <?php echo  $YEAR[1]['Year2']; ?> ( <?php echo $INFOSUBFARM[1]['NumTree'] ?> ต้น)</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-4">
                        <div class="col-12 ">
                            <div class="table-responsive ">
                                <!------- Start DataTable ------->
                                <table id="example3" class="table table-bordered table-data  ">

                                    <thead>
                                        <tr>
                                            <th width="20%">ชื่อปุ๋ย</th>
                                            <th width="20%">ปริมาณ</th>
                                            <th width="20%">หน่วย </th>
                                            <?php
                                            for ($i = 1; $i <= $INFONUTR[0]['numrow']; $i++) {
                                                echo "<th>{$INFONUTR[$i]['Name']}<br>({$INFONUTR[$i]['Unit']})</th>";
                                            }
                                            ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        for ($i = 1; $i <= $FERLIST[0]['numrow']; $i++) {
                                            echo "<tr>";
                                            echo "  <td >{$FERLIST[$i]['Name']}</td>";
                                            echo "  <td >
                                                        <input type=\"number\"  class=\"form-control text-right CalFerVol\"  id=\"VolF{$FERLIST[$i]['ID']}\" align=\"center\" style=\"width: 100px;\" value=\"0\" ferid=\"{$FERLIST[$i]['ID']}\" step=\"0.01\">
                                                    </td>";
                                            echo "  <td >
                                                        <select class=\"form-control CalFerUnit \" align=\"center\" ferid=\"{$FERLIST[$i]['ID']}\"  id=\"UnitF{$FERLIST[$i]['ID']}\" style=\"width: 80px;\">
                                                            <option  value=\"1\">Kg</option>
                                                            <option  value=\"2\">g</option>
                                                        </select>
                                                    </td>";

                                            for ($j = 1; $j <= $INFONUTR[0]['numrow']; $j++) {
                                                echo "<td class=\"text-right CalNutrID{$INFONUTR[$j]['NID']} CalFerID{$FERLIST[$i]['ID']}\" id=\"N{$INFONUTR[$j]['NID']}F{$FERLIST[$i]['ID']}\" ferid=\"{$FERLIST[$i]['ID']}\" nutrid=\"{$INFONUTR[$j]['NID']}\">0.00</td>";
                                            }
                                            echo "</tr>";
                                        }
                                        echo "<tr>";
                                        echo "  <td class=\"text-center \">ปริมาณที่พืชต้องการ</td>";
                                        echo "  <td colspan=\"2\" class=\"text-center SumAllVol\">0 Kg</td>";
                                        for ($j = 1; $j <= $INFONUTR[0]['numrow']; $j++) {
                                            echo "<td class=\"text-right  SumAllVolNutr\" id=\"SumAllVolN{$INFONUTR[$j]['NID']}\"  nutrid=\"{$INFONUTR[$j]['NID']}\" >0.00 {$INFONUTR[$j]['Unit']}</td>";
                                        }
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "  <td class=\"text-center \">ปริมาณที่พืชต้องการต่อต้น</td>";
                                        echo "  <td colspan=\"2\" class=\"text-center SumAllVolOfTree\">0 Kg</td>";
                                        for ($j = 1; $j <= $INFONUTR[0]['numrow']; $j++) {
                                            echo "<td class=\"text-right  \" id=\"SumOfTreeVolN{$INFONUTR[$j]['NID']}\" >0.00 {$INFONUTR[$j]['Unit']}</td>";
                                        }
                                        echo "</tr>";

                                        echo "<tr>";
                                        echo "  <td class=\"text-center \">ปริมาณที่ขาดต่อต้น</td>";
                                        echo "  <td colspan=\"2\" class=\"text-center \">-</td>";
                                        for ($j = 1; $j <= $INFONUTR[0]['numrow']; $j++) {
                                            echo "<td class=\"text-right  \" id=\"DeffOfTreeVolN{$INFONUTR[$j]['NID']}\" >0.00 {$INFONUTR[$j]['Unit']}</td>";
                                        }
                                        echo "</tr>";
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

<?php include_once("./FertilisingDetailModal.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("../../cropImage/cropImage.php"); ?>
<script src='../../Calendar/packages/core/main.js'></script>
<script src='../../Calendar/packages/interaction/main.js'></script>
<script src='../../Calendar/packages/daygrid/main.js'></script>
<script src='../../Calendar/packages/timegrid/main.js'></script>
<script src='../../Calendar/packages/list/main.js'></script>
<script src='../../Calendar/packages/bootstrap/main.js'></script>
<script src='../../Calendar/packages/core/locales-all.js'></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>
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
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                list: 'รายละเอียด'
            },
            eventRender: function(info) {
                $(info.el).tooltip({
                    title: info.event.title
                });
            },
            eventStartEditable: false,
            disableDragging: true,
            editable: false,
            defaultDate: thisday,
            navLinks: true,
            businessHours: true,
            events: <?php echo getTextEventFertilising($fsid) ?>
        });
        calendar1.render();


        /////////////////////////////////////////////////////
        $(".ac").hide();
        $(".ac<?php echo $active ?>").show();

        $(document).on("click", "#home-tab", function() {
            $(".ac").hide();
            $(".ac1").show();
        });
        $(document).on("click", "#profile-tab", function() {
            $(".ac").hide();
            $(".ac3").show();
        });
    });
</script>
<script src='./FertilisingDetail.js'></script>