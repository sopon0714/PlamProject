fsid = $("#data_search").attr("fsid");
$(document).ready(function() {
    var FSID = $('#FSID').attr('fsid');
    $('.show1').show();
    $('.show2').hide();
    $('.tt').tooltip();
    getDataSetTable();
    getDataSetTable2();
    $(document).on("click", ".btn-delete", function() {
        let date = $(this).attr("logdate");
        let logid = $(this).attr("logid");
        let info = $(this).attr("info");
        let typeid = $(this).attr("typeid");
        swal({
                title: "คุณต้องการลบข้อมูล" + info,
                text: `วันที่ ${date} หรือไม่ ?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-secondary",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false,
                closeOnCancel: function() {
                    $('[data-toggle=tooltip]').tooltip({
                        boundary: 'window',
                        trigger: 'hover'
                    });
                    return true;
                }
            },
            function(isConfirm) {
                if (isConfirm) {
                    // console.log(1)
                    swal({
                        title: "ลบข้อมูลสำเร็จ",
                        type: "success",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "ตกลง",
                        closeOnConfirm: false,
                    }, function(isConfirm) {
                        if (isConfirm) {
                            delete_1(logid, typeid)
                        } else {}
                    });
                } else {}
            });
    });

    function delete_1(logid, typeid) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                window.location.href = "./WaterDetail.php?FSID=" + FSID + "&Active=" + typeid;
            }
        };
        xhttp.open("POST", "manage.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`action=deleteLog&logid=${logid}&typeid=${typeid}`);

    }
    $(document).on("click", "#btn-modal1", function() {
        $("#modal-1").modal('show');
    });
    $(document).on("click", "#btn-modal2", function() {
        $("#modal-2").modal('show');
    });
    $(document).on('click', '#btn-submitRain', function() {
        var date = $("#formAddRain input[name='dateRain']");
        var timeStrat = $("#formAddRain input[name='timeStratRian']");
        var timeEnd = $("#formAddRain input[name='timeEndRian']");
        var Type = $("#formAddRain input[name='Type']:checked");
        var rank = $("#formAddRain select[name='rankRain']");
        var Vol = $("#formAddRain input[name='rainVol']");
        //console.log("date" + date.val() + "\ntimeStrat" + timeStrat.val() + "\ntimeEnd" + timeEnd.val() + "\nType" + Type.val() + "\nrank" + rank.val() + "\nVol" + Vol.val())
        let dataNull = [date];
        let dataNull2 = [timeStrat, timeEnd];
        if (Type.val() == 1) {
            let dataSelectNull2 = [rank];
            if (!checkNull(dataNull)) return;
            if (!checkNull(dataNull2)) return;
            Vol[0].setCustomValidity('');
            if (!checkSelectNull(dataSelectNull2)) return;
        } else {
            let dataNumNull = [Vol];
            if (!checkNull(dataNull)) return;
            if (!checkNull(dataNull2)) return;
            rank[0].setCustomValidity('');
            if (!checkNumNull(dataNumNull)) return;
        }
        if (timeStrat.val() >= timeEnd.val()) {
            timeStrat[0].setCustomValidity('กรุณรากรอกช่วงเวลาให้ถูกต้อง');
            return;
        } else {
            timeStrat[0].setCustomValidity('');
        }
    });
    $(document).on('click', '.radioType', function() {


        let Type = $(this).val();

        if (Type == 1) {
            $('.show1').show();
            $('.show2').hide();
            $("#rainVol").val("0");
        } else {
            $('.show2').show();
            $('.show1').hide();
            $("#rankRain").val("0"); 
        }

    })
    $(document).on('click', '#btn-submitWater', function() {
        var date = $("#formAddWater input[name='dateWater']");
        var timeStrat = $("#formAddWater input[name='timeStratWater']");
        var timeEnd = $("#formAddWater input[name='timeEndWater']");
        var Vol = $("#formAddWater input[name='waterVol']");
        let dataNull = [date];
        let dataNull2 = [timeStrat, timeEnd];
        let dataNumNull = [Vol];
        if (!checkNull(dataNull)) return;
        if (!checkNull(dataNull2)) return;
        if (!checkNumNull(dataNumNull)) return;
        if (timeStrat.val() >= timeEnd.val()) {
            timeStrat[0].setCustomValidity('กรุณรากรอกช่วงเวลาให้ถูกต้อง');
            return;
        } else {
            timeStrat[0].setCustomValidity('');
        }
    });

    function checkSelectNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == null || selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('กรุณาเลือกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == '') {
                selecter[i][0].setCustomValidity('กรุณากรอกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkNumNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('ห้ามเป็น 0');
                return false;
            } else {
                selecter[i][0].setCustomValidity('');
            }
        }

        return true;
    }

});
// pagination
function getDataSetTable(){
    console.log("2s"+start+":l"+limit);
    $.post("manage.php", {action: "pagination3",fsid: fsid,start: start,limit: limit}, function(result){
        DATA = JSON.parse(result); 
        setTableBody(DATA);
    });
}
// pagination
function setTableBody(DATA){
    html = ``;
    strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    for (i = 1; i <= DATA[0]['numrow']; i++) {
        var StartTime = new Date(parseFloat(DATA[i]["StartTime"])*1000);
        var StopTime = new Date(parseFloat(DATA[i]["StopTime"])*1000);
        var dateStart = ("0" + StartTime.getHours()).slice(-2)+":"+("0" + StartTime.getMinutes()).slice(-2);
        var dateStop = ("0" + StopTime.getHours()).slice(-2)+":"+("0" + StopTime.getMinutes()).slice(-2);
        html += `<tr>
                    <td class=\"text-center\">${DATA[i]["dd"]} ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}</td>
                    <td class=\"text-center\">${dateStart} - ${dateStop}</td>
                    <td class=\"text-right\">${DATA[i]["Period"]}</td>
                    <td class=\"text-right\">${DATA[i]["Vol"]}</td>
                    <td class=\"text-center\">
                        <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete tt\"   logid=\"${DATA[i]["LogID"]}\"  info=\"ฝนตก\" typeid=\"3\"  logdate=\"${DATA[i]["dd"]} ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                    </td>
                </tr>`;
        }
                    

    $("#body").html(html);
}
function getDataSetTable2(){
    console.log("2s"+start2+":l"+limit2);
    $.post("manage.php", {action: "pagination4",fsid: fsid,start: start2,limit: limit2}, function(result){
        
        DATA = JSON.parse(result); 
        setTableBody2(DATA);
    });
}
// pagination
function setTableBody2(DATA){
    html = ``;
    strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    for (i = 1; i <= DATA[0]['numrow']; i++) {
        var StartTime = new Date(parseFloat(DATA[i]["StartTime"])*1000);
        var StopTime = new Date(parseFloat(DATA[i]["StopTime"])*1000);
        var dateStart = ("0" + StartTime.getHours()).slice(-2)+":"+("0" + StartTime.getMinutes()).slice(-2);
        var dateStop = ("0" + StopTime.getHours()).slice(-2)+":"+("0" + StopTime.getMinutes()).slice(-2);
        html += `<tr>
                    <td class=\"text-center\">${DATA[i]["dd"]} ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}</td>
                    <td class=\"text-center\">${dateStart} - ${dateStop}</td>
                    <td class=\"text-right\">${DATA[i]["Period"]}</td>
                    <td class=\"text-right\">${DATA[i]["Vol"]}</td>
                    <td class=\"text-center\">
                        <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete tt\"   logid=\"${DATA[i]["LogID"]}\"  info=\"การรดน้ำ\" typeid=\"4\"  logdate=\"${DATA[i]["dd"]} ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                    </td>
                </tr>`;
    }
   
            

    $("#body2").html(html);
}