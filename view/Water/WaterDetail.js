let type = "<?php echo $type; ?>"
let UID = parseInt(localStorage.getItem('UID'));
let FID = parseInt(localStorage.getItem('FID'));
let SFID = parseInt(localStorage.getItem('SFID'));
let DSFID = parseInt(localStorage.getItem('DSFID'));
let date;

let dataSubFarm_S;
let dataFarmer;
let dataRain;
let dataWater;

let clenderRain1, clenderRain2, clenderRain3;
let calendar1, calendar2, calendar3;


$(document).ready(function() {

    $('.js-example-basic-single').select2();
    $('.js-example-basic-single').on('select2:open', function(e) {
        $(this).next().addClass("border-from-control");
    });
    $('.js-example-basic-single').on('select2:close', function(e) {
        $(this).next().removeClass("border-from-control");
    });

    $('#r_date1').datepicker({
        autoHide: true,
        zIndex: 2048,
        language: 'th-TH',
        format: 'dd-mm-yyyy'
    });
    $('#r_date2').datepicker({
        autoHide: true,
        zIndex: 2048,
        language: 'th-TH',
        format: 'dd-mm-yyyy'
    });


    $('#r1_timepicker1').mdtimepicker();
    $('#r2_timepicker1').mdtimepicker();
    $('#r1_timepicker2').mdtimepicker();
    $('#r2_timepicker2').mdtimepicker();


    loadSubfarm_S(SFID);
    loadFarmer(UID);
});

// Format NUmber
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
// โหลด Subfarm Small
function loadSubfarm_S(id) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            dataSubFarm_S = JSON.parse(this.responseText);
            console.log(dataSubFarm_S)
            document.getElementById('span_name1').innerHTML = "ชื่อแปลง : ";
            document.getElementById('span_name2').innerHTML = dataSubFarm_S[0].Name;
            document.getElementById("subfarm-img").src = "../../icon/subfarm/" + dataSubFarm_S[0].ID + "/" + dataSubFarm_S[0].Icon;
        }
    };
    xhttp.open("GET", "./loadSubFarm_S.php?SFID=" + id, true);
    xhttp.send();
}
// โหลด User
function loadFarmer(id) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            dataFarmer = JSON.parse(this.responseText);
            console.log(dataFarmer)
            document.getElementById('Fname').innerHTML = dataFarmer[0].FName + " " + dataFarmer[0].LName;
            document.getElementById("farmer-img").src = "../../icon/farmer/" + dataFarmer[0].ID + "/" + dataFarmer[0].Icon;
        }
    };
    xhttp.open("GET", "./loadFarmer.php?UID=" + id, true);
    xhttp.send();
}
// โหลด Raining
function loadRaining() {
    $('#dataTable').DataTable().destroy();
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let timeObj, timeObj2;
            let arrayDate;
            dataRain = JSON.parse(this.responseText);
            console.log(dataRain)
            let text = "";
            for (i in dataRain) {
                arrayDate = dataRain[i].Date.split("-");
                timeObj = new Date(dataRain[i].StartTime * 1000);
                timeObj2 = new Date(dataRain[i].StopTime * 1000);
                text += `<tr>
                                <th style="text-align:center;">${dataRain[i].Date}</th>
                                <th style="text-align:center;">${timeObj.getUTCHours().toString().padStart(2, '0') + '.' + timeObj.getUTCMinutes().toString().padStart(2, '0')}</th>
                                <th style="text-align:center;">${timeObj2.getUTCHours().toString().padStart(2, '0') + '.' + timeObj2.getUTCMinutes().toString().padStart(2, '0')}</th>
                                <th class="text-right">${dataRain[i].Period}</th>
                                <th class="text-right">${dataRain[i].Vol} (ลบ.ม.)</th>
                                <th style="text-align:center;"> 
                                    <button type="button" id='${i}' rid='${dataRain[i].ID}' class="btn btn-danger btn-sm btn-delete">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </th>
                            </tr>`;
            }
            $("#fetchdata1").html(text);
            setOption_DataTable("dataTable", "PDF", "EXCEL", 1, 0, 4);
        }
    };
    xhttp.open("GET", "./loadRaining.php?id=" + DSFID, true);
    xhttp.send();
}
// โหลด Watering
function loadWatering() {
    $('#dataTable').DataTable().destroy();
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let timeObj, timeObj2;
            dataWater = JSON.parse(this.responseText);
            console.log(dataWater);
            let text = "";
            for (i in dataWater) {
                timeObj = new Date(dataWater[i].StartTime * 1000);
                timeObj2 = new Date(dataWater[i].StopTime * 1000);
                text += `<tr>
                                <th style="text-align:center;">${dataWater[i].Date}</th>
                                <th style="text-align:center;">${timeObj.getUTCHours().toString().padStart(2, '0') + '.' + timeObj.getUTCMinutes().toString().padStart(2, '0')}</th>
                                <th style="text-align:center;">${timeObj2.getUTCHours().toString().padStart(2, '0') + '.' + timeObj2.getUTCMinutes().toString().padStart(2, '0')}</th>
                                <th class="text-right">${dataWater[i].Period}</th>
                                <th style="text-align:center;"> 
                                    <button type="button" id='${i}' rid='${dataWater[i].ID}' class="btn btn-danger btn-sm btn-delete">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </th>
                            </tr>`;
            }
            $("#fetchdata1").html(text);
            setOption_DataTable("dataTable", "PDF", "EXCEL", 1, 0, 3);
        }
    };
    xhttp.open("POST", "./loadWatering.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`date=${date}&DSFID=${DSFID}`);
}
// โหลด AddLogRain 
function addLogRain(date, StartTime, StopTime, rank, vol) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

        }
    };
    xhttp.open("POST", "./addLogRain.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`date=${date}&ID_Farm=${FID}&ID_SubFarm=${SFID}&StartTime=${StartTime}&StopTime=${StopTime}&rank=${rank}&vol=${vol}`);
}
// โหลด AddLogWater 
function addLogWater(StartTime, StopTime) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText + "*-*-*");
            loadWatering();
        }
    };
    xhttp.open("POST", "./addLogWater.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`date=${date}&ID_Farm=${FID}&ID_SubFarm=${SFID}&StartTime=${StartTime}&StopTime=${StopTime}`);
}
// โหลด Event in Clender1
function setEventRain1() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let timeObj, timeObj2;
            clenderRain1 = JSON.parse(this.responseText);
            console.log(clenderRain1);
        }
    };
    xhttp.open("GET", "./loadClender1.php?DSFID=" + DSFID, true);
    xhttp.send();
}

if (type == 1) {
    setEventRain1();
    $("#home").html(`<div class='row'>
                            <div class='col-12 mb-3'>
                                <h4>ปฏิทินข้อมูล - ช่วงเวลาที่ฝนตก</h4>
                                <div id='calendar1' style="        
                                    margin: 0 auto;
                                    width: 100%;
                                    background-color: #FFFFFF;" >
                                </div>
                            </div>
                        </div><br>
                        <div class='row'>
                            <div class='col-12 mb-3'>
                                <h4>ปฏิทินข้อมูล - ช่วงเวลาที่ฝนทิ้งช่วง</h4>
                                <div id='calendar2' style="        
                                    margin: 0 auto;
                                    width: 100%;
                                    background-color: #FFFFFF;" >
                                </div>
                            </div>
                        </div>`);


    $('#table').html(`<div class='table-responsive'>
                            <table id="dataTable" class='table table-bordered table-striped table-hover table-data' width='100%'>
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>ช่วงเวลาฝนเริ่มตก</th>
                                        <th>ช่วงเวลาฝนหยุดตก</th>
                                        <th>ระยะเวลาฝนตก</th>
                                        <th>ปริมาณฝน (ลบ.ม.)</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>ช่วงเวลาฝนเริ่มตก</th>
                                        <th>ช่วงเวลาฝนหยุดตก</th>
                                        <th>ระยะเวลาฝนตก</th>
                                        <th>ปริมาณฝน (ลบ.ม.)</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </tfoot>
                                <tbody id="fetchdata1">
                                    
                                </tbody>
                            </table>
                        </div>`);
    loadRaining();
    let calendarEl1 = document.getElementById('calendar1');
    calendar1 = new FullCalendar.Calendar(calendarEl1, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap'],
        themeSystem: 'bootstrap',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            week: 'สัปดาห์',
            day: 'วัน',
            list: 'รายการ',
        },
        locale: 'th',
        navLinks: true, // can click day/week names to navigate views
        businessHours: true, // display business hours
        editable: false,
        eventOverlap: true,
        events: clenderRain1,
        timeZone: 'local'
    });
    calendar1.render();
    let calendarEl2 = document.getElementById('calendar2');
    calendar2 = new FullCalendar.Calendar(calendarEl2, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap'],
        themeSystem: 'bootstrap',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            week: 'สัปดาห์',
            day: 'วัน',
            list: 'รายการ',
        },
        locale: 'th',
        navLinks: true, // can click day/week names to navigate views
        businessHours: true, // display business hours
        editable: false,
        eventOverlap: true,
        events: [],
        timeZone: 'local'
    });
    calendar2.render();


} else {
    date = localStorage.getItem('date');
    $("#home").html(`<div class='row'>
                            <div class='col-12 mb-3'>
                                <h4>ปฏิทินข้อมูล - ช่วงเวลาการให้น้ำ</h4>
                                <div id='calendar3' style='        
                                    margin: 0 auto;
                                    width: 100%;
                                    background-color: #FFFFFF;' >
                                </div>
                            </div>
                        </div>`);
    let calendarEl3 = document.getElementById('calendar3');
    calendar3 = new FullCalendar.Calendar(calendarEl3, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'bootstrap'],
        themeSystem: 'bootstrap',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            week: 'สัปดาห์',
            day: 'วัน',
            list: 'รายการ',
        },
        locale: 'th',
        navLinks: true, // can click day/week names to navigate views
        businessHours: true, // display business hours
        editable: false,
        eventOverlap: true,
        events: [],
        timeZone: 'local'
    });
    calendar3.render();
    $('#table').html(`<div class='table-responsive'>
                            <table id="dataTable" class='table table-bordered table-striped table-hover table-data' width='100%'>
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>เวลาให้น้ำ</th>
                                        <th>เวลาหยุดให้น้ำ</th>
                                        <th>ระยะเวลาให้น้ำ (นาที)</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>เวลาให้น้ำ</th>
                                        <th>เวลาหยุดให้น้ำ</th>
                                        <th>ระยะเวลาให้น้ำ (นาที)</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </tfoot>
                                <tbody id="fetchdata1">
                                    
                                </tbody>
                            </table>
                        </div>`);
    loadWatering();
}


//
$("#btn-modal1").on('click', function() {
    let d = new Date();
    let start_time = d.getHours() + ":" + d.getMinutes();
    let st_minutes = d.getMinutes() + 1 < 60 ? d.getMinutes() + 1 : "00";
    let st_hours = st_minutes == "00" ? (d.getHours() + 1) % 24 : d.getHours();
    $('#r1_timepicker1').val(start_time);
    $('#r2_timepicker1').val(st_hours + ":" + st_minutes);
    $('#r_date1').datepicker("setDate", "pick");
    $("#r_raining1").val(0);
});
//
$("#btn-modal2").on('click', function() {
    let d = new Date();
    let start_time = d.getHours() + ":" + d.getMinutes();
    let st_minutes = d.getMinutes() + 1 < 60 ? d.getMinutes() + 1 : "00";
    let st_hours = st_minutes == "00" ? (d.getHours() + 1) % 24 : d.getHours();
    $('#r1_timepicker2').val(start_time);
    $('#r2_timepicker2').val(st_hours + ":" + st_minutes);

});


$("#m_success1").on('click', function() {
    let date = $('#r_date1').val();
    let StartTime = $('#r1_timepicker1').val();
    let StopTime = $('#r2_timepicker1').val();

    let arrayDate = date.split("-");
    let arrayStartTime = StartTime.split(":");
    let arrayStopTime = StopTime.split(":");
    StartTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStartTime[0], arrayStartTime[1], 0)).getTime() / 1000;
    StopTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStopTime[0], arrayStopTime[1], 0)).getTime() / 1000;

    let rank = document.getElementById("r_rank1").value;
    let vol = document.getElementById("r_raining1").value;

    addLogRain(date, StartTime, StopTime, rank, vol);
    loadRaining();
});
$("#m_success2").on('click', function() {
    let StartTime = $('#r1_timepicker2').val();
    let StopTime = $('#r2_timepicker2').val();

    let arrayDate = date.split("-");
    let arrayStartTime = StartTime.split(":");
    let arrayStopTime = StopTime.split(":");
    StartTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStartTime[0], arrayStartTime[1], 0)).getTime() / 1000;
    StopTime = new Date(Date.UTC(arrayDate[2], arrayDate[1] - 1, arrayDate[0], arrayStopTime[0], arrayStopTime[1], 0)).getTime() / 1000;

    addLogWater(StartTime, StopTime);
});

$(document).on('click', '.btn-delete', function() {
    let id = $(this).attr('id');
    let rid = $(this).attr('rid');
    swal({
            title: "ยืนยันการลบข้อมูล",
            // text: `Id_diary : ${id} ?`,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
            closeOnConfirm: false,
        },
        function(isConfirm) {
            if (isConfirm) {
                swal({
                    title: "ลบข้อมูลสำเร็จ",
                    type: "success",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "ตกลง",
                }, function(isConfirm) {
                    if (type == 1 && isConfirm) {
                        $('#dataTable').DataTable().destroy();
                        let xhttp = new XMLHttpRequest();
                        let timeObj, timeObj2;
                        let arrayDate;
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                dataRain.splice(id, 1);
                                let text = "";
                                for (i in dataRain) {
                                    arrayDate = dataRain[i].Date.split("-");
                                    timeObj = new Date(dataRain[i].StartTime * 1000);
                                    timeObj2 = new Date(dataRain[i].StopTime * 1000);
                                    text += `<tr>
                                                <th class="text-right">${dataRain[i].Date}</th>
                                                <th style="text-align:center;">${timeObj.getUTCHours().toString().padStart(2, '0') + '.' + timeObj.getUTCMinutes().toString().padStart(2, '0')}</th>
                                                <th style="text-align:center;">${timeObj2.getUTCHours().toString().padStart(2, '0') + '.' + timeObj2.getUTCMinutes().toString().padStart(2, '0')}</th>
                                                <th class="text-right">${dataRain[i].Period}</th>
                                                <th class="text-right">${dataRain[i].Vol} (ลบ.ม.)</th>
                                                <th style="text-align:center;"> 
                                                    <button type="button" id='${i}' rid='${dataRain[i].ID}' class="btn btn-danger btn-sm btn-delete">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </th>
                                            </tr>`;
                                }
                                $("#fetchdata1").html(text);
                                setOption_DataTable("dataTable", "PDF", "EXCEL", 1, 0, 4);
                            }
                        };
                        xhttp.open("POST", "./deleteRain.php", true);
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhttp.send(`ID=${rid}`);
                    } else if (type == 2 && isConfirm) {
                        $('#dataTable').DataTable().destroy();
                        let xhttp = new XMLHttpRequest();
                        let timeObj, timeObj2;
                        let arrayDate;
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                dataWater.splice(id, 1);
                                let text = "";
                                for (i in dataWater) {
                                    arrayDate = dataWater[i].Date.split("-");
                                    timeObj = new Date(dataWater[i].StartTime * 1000);
                                    timeObj2 = new Date(dataWater[i].StopTime * 1000);
                                    text += `<tr>
                                                    <th style="text-align:center;">${dataWater[i].Date}</th>
                                                    <th style="text-align:center;">${timeObj.getUTCHours().toString().padStart(2, '0') + '.' + timeObj.getUTCMinutes().toString().padStart(2, '0')}</th>
                                                    <th style="text-align:center;">${timeObj2.getUTCHours().toString().padStart(2, '0') + '.' + timeObj2.getUTCMinutes().toString().padStart(2, '0')}</th>
                                                    <th class="text-right">${dataWater[i].Period}</th>
                                                    <th style="text-align:center;"> 
                                                        <button type="button" id='${i}' rid='${dataWater[i].ID}' class="btn btn-danger btn-sm btn-delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </th>
                                                </tr>`;
                                }
                                $("#fetchdata1").html(text);
                                setOption_DataTable("dataTable", "PDF", "EXCEL", 1, 0, 3);
                            }
                        };
                        xhttp.open("POST", "./deleteWater.php", true);
                        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhttp.send(`ID=${rid}`);
                    }
                });
            } else {

            }
        });
});