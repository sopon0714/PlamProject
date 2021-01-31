<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Calendar";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$YEAR = getYearAll();
$PROVINCE = getProvince();
$currentYear = date("Y");
if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1) {
  $year = $_POST['year'];
  $fpro = $_POST['s_province'];
  $fdist = $_POST['s_distrinct'];
  $fullname = $_POST['s_name'];
  if (isset($_POST['event'])) {
    $event = $_POST['event'];
  } else {
    $event = [];
  }
  if (isset($_POST['total_check'])) {
    $showAll = true;
  } else {
    $showAll = false;
  }
  if ($year == 0) {
    $showYear = $currentYear;
  } else {
    $showYear = $year - 543;
  }
} else {
  $year = 0;
  $fpro = 0;
  $fdist = 0;
  $fullname = '';
  $showYear = $currentYear;
  $event = ['ขาดน้ำ'];
  $showAll = false;
}
$checkbox = array("เก็บเกี่ยว" => 0, "ให้ปุ๋ย" => 0, "ให้น้ำ" => 0, "ขาดน้ำ" => 0, "ล้างคอขวด" => 0, "พบศัตรูพืช" => 0);
for ($i = 0; $i < count($event); $i++) {
  $checkbox[$event[$i]] = 1;
}
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);

?>
<link href='../../Calendar/packages/core/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/daygrid/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/timegrid/main.css' rel='stylesheet' />
<link href='../../Calendar/packages/list/main.css' rel='stylesheet' />
<style>
  #card-detail {
    color: white;
    background-color: #E91E63;
  }

  input[type=checkbox] {
    background-color: #F44336;
    color: #F44336;
  }

  #calendar {
    max-width: 950px;
    margin: 0 auto;
    background-color: white;
    color: black;
  }

  .fc-month-view span.fc-title {
    white-space: normal;
  }

  .myCalendar {
    cursor: pointer;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-12 col-xl-3 row justify-content-center">
      <div class="card">
        <div class="card-header" id="card-detail" style="cursor:pointer; background-color: <?= $color ?>; color: white;">
          ค้นหา
        </div>
        <div class="card-body" id="check">
          <form action="Calendar.php?isSearch=1" method="post">
            <div class="row">
              <div class="col-12">
                <span>ปี</span>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12">
                <select id="year" name="year" class="form-control">
                  <option value='0'>ทุกปี </option>
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
                  <option selected value=0>เลือกอำเภอ</option>
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
            <div class="row mb-2">
              <div class="col-12">
                <span style="text-decoration:underline;">กิจกรรม</span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <input type="checkbox" class="total_check" name="total_check" <?php if ($showAll) echo "checked"; ?> value="ทั้งหมด"> ทั้งหมด<br>
                <input type="checkbox" class="checkmark" name="event[]" <?php if ($checkbox["เก็บเกี่ยว"] == 1) echo "checked"; ?> value="เก็บเกี่ยว"> เก็บเกี่ยว<br>
                <input type="checkbox" class="checkmark" name="event[]" <?php if ($checkbox["ให้ปุ๋ย"] == 1) echo "checked"; ?> value="ให้ปุ๋ย"> ให้ปุ๋ย<br>
                <input type="checkbox" class="checkmark" name="event[]" <?php if ($checkbox["ให้น้ำ"] == 1) echo "checked"; ?> value="ให้น้ำ"> ให้น้ำ<br>
                <input type="checkbox" class="checkmark" name="event[]" <?php if ($checkbox["ขาดน้ำ"] == 1) echo "checked"; ?> value="ขาดน้ำ"> ขาดน้ำ<br>
                <input type="checkbox" class="checkmark" name="event[]" <?php if ($checkbox["ล้างคอขวด"] == 1) echo "checked"; ?> value="ล้างคอขวด"> ล้างคอขวด<br>
                <input type="checkbox" class="checkmark" name="event[]" <?php if ($checkbox["พบศัตรูพืช"] == 1) echo "checked"; ?> value="พบศัตรูพืช"> พบศัตรูพืช<br>
              </div>
            </div>

            <div class="row">
              <button type="submit" class="btn" style="cursor:pointer; background-color: <?= $color ?>; color: white;margin:auto; height:40px;width:90px;">ค้นหา <i class="fas fa-search"></i> </button>
            </div>
          </form>
        </div>
      </div>

    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
      <div class="card">
        <div class="card-body">
          <div id='calendar' style="cursor:pointer;"></div>
        </div>
      </div>
    </div>

  </div>
</div>
<?php include_once("./CalendarModal.php"); ?>
<?php include_once("../layout/LayoutFooter.php"); ?>

<script src='../../Calendar/packages/core/main.js'></script>
<script src='../../Calendar/packages/interaction/main.js'></script>
<script src='../../Calendar/packages/daygrid/main.js'></script>
<script src='../../Calendar/packages/timegrid/main.js'></script>
<script src='../../Calendar/packages/list/main.js'></script>
<script src='../../Calendar/packages/bootstrap/main.js'></script>
<script src='../../Calendar/packages/core/locales-all.js'></script>
<script src="Calendar.js"></script>
<script>
  function getTextInFo(fpro, fdist, fullname, status, date) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // console.log(xhttp.responseText);
        document.getElementById('InfoData').innerHTML = xhttp.responseText;
      }
    }
    xhttp.open("POST", "data.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`fpro=${fpro}&result=getTextInFo&fdist=${fdist}&fullname=${fullname}&status=${status}&date=${date}`);
  }
  var calendarEl1 = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl1, {
    disableResizing: true,
    locale: 'th',
    plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap'],
    themeSystem: 'bootstrap',
    timezone: "Asia/Bangkok",
    header: {
      left: 'prevYear,prev,next,nextYear today',
      center: 'title',
      right: 'dayGridMonth,listMonth'
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
    defaultDate: '<?php echo $showYear . "-" . date("m-d") ?>',
    navLinks: true,
    businessHours: true,

    events: <?php echo getTextCalendar($year, $fpro, $fdist, $fullname, $checkbox); ?>,
    eventClick: function(info) {
      var dateStr = info.event.extendedProps['date'];
      var dArr = dateStr.split("-");
      var fpro = $('#s_province').val();
      var fdist = $('#s_distrinct').val();
      var fullname = $('#s_name').val();
      var status = info.event.extendedProps['status'];
      dateStr = dArr[2] + "/" + dArr[1] + "/" + (Number(dArr[0]) + 543);
      $('#headertitle').html("ข้อมูล " + info.event.extendedProps['status'] + " : วันที่  " + dateStr);
      $('#headermodal').css("background-color", info.event.extendedProps['color']);
      getTextInFo(fpro, fdist, fullname, status, info.event.extendedProps['date']);
      $("#modalInfo").modal('show');
    }
  });
  calendar.render();
</script>