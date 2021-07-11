// pagination
idformal = $("#data_search").attr("idformal");
fullname = $("#data_search").attr("fullname");
fpro = $("#data_search").attr("fpro");
fdist = $("#data_search").attr("fdist");
score_From = $("#data_search").attr("score_From");
score_To = $("#data_search").attr("score_To");
year = $("#data_search").attr("year");
//end pagination
$(document).ready(function() {
    $('.show2').hide();
    var score_From = $('#score_From').val();
    var score_To = $('#score_To').val();
    $('.tt').tooltip();
    $(document).on("change", "#s_province", function() {

        var e = document.getElementById("s_province");
        var select_id = e.options[e.selectedIndex].value;
        // console.log(select_id);
        data_show(select_id, "s_distrinct", '');


    });

    function data_show(select_id, result, point_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
                // console.log(result);
                document.getElementById(result).innerHTML = xhttp.responseText;

            };
        }
        xhttp.open("POST", "data.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`select_id=${select_id}&result=${result}&point_id=${point_id}`);
    }
    $(".js-range-slider").ionRangeSlider({
        type: "double",
        from: score_From,
        to: score_To,
        step: 1,
        min: 0,
        max: 100,
        grid: true,
        grid_num: 10,
        grid_snap: false,
        skin: "flat",
        onFinish: function(data) {
            $('#score_From').val(data.from);
            $('#score_To').val(data.to);

        }
    });
    $(document).on("click", "#btn-modal1", function() {
        $("#modal-1").modal('show');
    });
    $(document).on('change', '#dateRian', function() {
        let date = $("#dateRian").val();
        setSelectFarm(date, "FarmIDRian");
    });
    $(document).on('change', '#FarmIDRian', function() {
        let date = $("#dateRian").val();
        let FIMD = $("#FarmIDRian").val();
        setSelectSubfarm(FIMD, date, "SubFarmIDRian");
    });

    $(document).on("click", "#btn-modal2", function() {
        $("#modal-2").modal('show');
    });
    $(document).on('change', '#dateWater', function() {
        let date = $("#dateWater").val();
        setSelectFarm(date, "FarmIDWater");
    });
    $(document).on('change', '#FarmIDWater', function() {
        let date = $("#dateWater").val();
        let FIMD = $("#FarmIDWater").val();
        setSelectSubfarm(FIMD, date, "SubFarmIDWater");
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
    $(document).on('click', '#btn-submitRain', function() {
        var date = $("#formAddRain input[name='dateRain']");
        var FarmID = $("#formAddRain select[name='FarmIDRian']");
        var subFarmID = $("#formAddRain select[name='SubFarmIDRian']");
        var timeStrat = $("#formAddRain input[name='timeStratRian']");
        var timeEnd = $("#formAddRain input[name='timeEndRian']");
        var Type = $("#formAddRain input[name='Type']:checked");
        var rank = $("#formAddRain select[name='rankRain']");
        var Vol = $("#formAddRain input[name='rainVol']");
        //console.log("date" + date.val() + "\nFarmID" + FarmID.val() + "\nsubFarmID" + subFarmID.val() + "\ntimeStrat" + timeStrat.val() + "\ntimeEnd" + timeEnd.val() + "\nType" + Type.val() + "\nrank" + rank.val() + "\nVol" + Vol.val())
        let dataNull = [date];
        let dataSelectNull = [FarmID, subFarmID];
        let dataNull2 = [timeStrat, timeEnd];
        if (Type.val() == 1) {
            let dataSelectNull2 = [rank];
            if (!checkNull(dataNull)) return;
            if (!checkSelectNull(dataSelectNull)) return;
            if (!checkNull(dataNull2)) return;
            Vol[0].setCustomValidity('');
            if (!checkSelectNull(dataSelectNull2)) return;
        } else {
            let dataNumNull = [Vol];
            if (!checkNull(dataNull)) return;
            if (!checkSelectNull(dataSelectNull)) return;
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
    $(document).on('click', '#btn-submitWater', function() {
        var date = $("#formAddWater input[name='dateWater']");
        var FarmID = $("#formAddWater select[name='FarmIDWater']");
        var subFarmID = $("#formAddWater select[name='SubFarmIDWater']");
        var timeStrat = $("#formAddWater input[name='timeStratWater']");
        var timeEnd = $("#formAddWater input[name='timeEndWater']");
        var Vol = $("#formAddWater input[name='waterVol']");
        let dataNull = [date];
        let dataSelectNull = [FarmID, subFarmID];
        let dataNull2 = [timeStrat, timeEnd];
        let dataNumNull = [Vol];
        if (!checkNull(dataNull)) return;
        if (!checkSelectNull(dataSelectNull)) return;
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

    function setSelectFarm(date, id) {
        $.ajax({
            url: "manage.php",
            method: "POST",
            data: {
                date: date,
                action: "setSelectFarm"
            },
            success: function(data) {
                $("#" + id).html(data);
            }
        });
    }

    function setSelectSubfarm(FIMD, date, id) {
        $.ajax({
            url: "manage.php",
            method: "POST",
            data: {
                FIMD: FIMD,
                date: date,
                action: "setSelectSubfarm"
            },
            success: function(data) {
                $("#" + id).html(data);
            }
        });
    }

});
// pagination 
function getDataSetTable(){
    $.post("manage.php", {action: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,score_From: score_From,score_To: score_To,year: year,start: start,limit: limit}, function(result){
        DATA = JSON.parse(result);
        console.log()
        setTableBody(DATA);
    });
}
function setTableBody(DATA){
    html = ``;
    i = 0;
    for (const [key, value] of Object.entries(DATA)) {
        html += `<tr class="la${value["Latitude"]} long${value["Longitude"]} table-set" test="test${i}">
                    <td>${value["FullName"]}</td>
                    <td>${value["NameFarm"]}</td>
                    <td>${value["NameSubfarm"]}</td>
                    <td class=\"text-right\">${value["AreaRai"]} ไร่ ${value["AreaNgan"]} งาน</td>
                    <td class=\"text-right\">${value["NumTree"]} ต้น</td>
                    <td class=\"text-right\">${value["rainDay"]} วัน</td>
                    <td class=\"text-right\">${value["notrainDay"]} วัน</td>
                    <td class=\"text-right\">${value["longDay"]} วัน</td>
                    <td class=\"text-right\">${parseFloat(value["totalVol"]).toFixed(2)}</td>
                    <td class=\"text-center\">
                    <a href=\"./WaterDetail.php?FSID=${value["FSID"]}\"><button  class=\"btn btn-info btn-sm tt\" data-toggle=\"tooltip\" title=\"รายละเอียด\"><i class=\"fas fa-bars\"></i></button></a>
                    </td>
                    <label class="click-map" hidden 
                    namesubfarm="${value["NameFarm"]}"
                    la="${value["Latitude"]}" long="${value["Longitude"]}"
                    dist="${value["Distrinct"]}" pro="${value["Province"]}" 
                    owner="${value["FullName"]}"></label>
                </tr>`;
     }
      
    $("#body").html(html);
}
// pagination
function initMap() {
    var locations = [];
    var center = [0, 0];
    // pagination
    fade = false;
    
    $.post("manage.php", {action: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,score_From: score_From,score_To: score_To,year: year,start: 0,limit: 0}, function(result){
   
    DATA = JSON.parse(result);
    
    getDataSetTable();
    $(".loader-container").fadeOut(500);
    // console.log(DATA);
    // console.log("init map numrow data = "+DATA[0]["numrow"]);
    size = Object.keys(DATA).length;
    for (const [key, value] of Object.entries(DATA)) {
        namefarm = value['NameFarm'];
        la = value["Latitude"];
        long = value["Longitude"];
        laFloat = parseFloat(value["Latitude"]);
        longFloat = parseFloat(value["Longitude"]);
        dist = value["Distrinct"];
        pro = value["Province"];
        owner = value["FullName"];
        center[0] += laFloat;
        center[1] += longFloat;
        data = [namefarm,la,long,dist,pro,owner];
        locations.push(data);
    }
    if (size == 0) {
        center[0] = 13.736717;
        center[1] = 100.523186;
    }else{
        center[0] = center[0] / size;
        center[1] = center[1] / size;
    }
    

    

    // console.log(center);
    // console.log("locations = ");

    // console.log(locations);

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: new google.maps.LatLng(center[0], center[1]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"

        });
        // console.log('i == ' + i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                content = "";
                content += locations[i][5];
                content += "<br>" +locations[i][0];
                content += "<br> อ." + locations[i][3] + " จ." + locations[i][4];
                infowindow.setContent(content);
                infowindow.open(map, marker);

                if (i != -1) {
                $.post("manage.php", {action: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,start: 0,limit: 0,latitude: locations[i][1],longitude: locations[i][2]}, function(result){
                    DATA = JSON.parse(result);
                    
                    setTableBody(DATA);
                });
                }

            }
        })(marker, i));

    }

  });


}