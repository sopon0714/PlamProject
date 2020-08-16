minlack = 1;
maxlack = 5;

minwater = 1;
maxwater = 5;

mincutbranch = 1;
maxcutbranch = 5;

DATA_DB = Array();
$(document).ready(function() {

    $("#search").trigger("click");

});

$("#search").click(function(){
    console.log('search');
    year = $('#year').val();
    province = $('#province').val();
    distrinct = $('#distrinct').val();
    farmer = $('#farmer').val();
    harvest = $('#harvest').val();
    fertilizer = $('#fertilizer').val();
    water = $('#water').val();
    waterlack = $('#waterlack').val();
    wash = $('#wash').val();
    pesttype = $('#pesttype').val();
    if($('#check3').is(':checked') == false){
        water = 0;
    }else{
        water = 1;
    }
    if($('#check4').is(':checked') == false){
        lack = 0;
    }else{
        lack = 1;
    }
    if($('#check5').is(':checked') == false){
        cutbranch = 0;
    }else{
        cutbranch = 1;
    }

    $.post("manage.php", {request: "search", year:year, province:province, distrinct:distrinct,
    farmer:farmer, harvest:harvest, fertilizer:fertilizer, minwater:minwater, maxwater:maxwater, 
    minlack:minlack, maxlack:maxlack, pesttype:pesttype,
    mincutbranch:mincutbranch, maxcutbranch:maxcutbranch,
    lack:lack, water:water, cutbranch:cutbranch}, function(result){
        // console.log(result);
        DATA_DB = JSON.parse(result);
        console.log(DATA_DB);
        initMap();
    });
});
function initMap() {
    //The location of Uluru
    console.log("init map");
    console.log(DATA_DB);

    var locations = [];
    var center = [0, 0];
    size = DATA_DB.length;
    console.log(size);
    for (i = 0; i < size; i++) {
        la = DATA_DB[i]['Latitude'];
        long = DATA_DB[i]['Longitude'];
        nameSub = DATA_DB[i]['Name'];
        FSID = DATA_DB[i]['FSID'];
        FMID = DATA_DB[i]['FMID'];
        laFloat = parseFloat(la);
        longFloat = parseFloat(long);
        dist = DATA_DB[i]['Distrinct'];
        pro = DATA_DB[i]['Province'];
        if(DATA_DB[i]['EndT'] == 0){
            color = "green";
        }else{
            color = "red";
        }
        center[0] += laFloat;
        center[1] += longFloat;
        data = [la, long, dist, pro, color,nameSub, FSID, FMID];
        locations.push(data);

    }
    center[0] = center[0] / size;
    center[1] = center[1] / size;

    if(size == 0){
      center[0] = 13.736717;
      center[1] = 100.523186;
    }

    console.log(center);

    console.log(locations);

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: new google.maps.LatLng(center[0], center[1]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][0], locations[i][1]),
            map: map,
            icon: "http://maps.google.com/mapfiles/ms/icons/"+locations[i][4]+"-dot.png"

        });
        console.log('i == ' + i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                content = "";
                if(locations[i][4] == "green"){
                    content += "<a href='http://127.0.0.1/PalmProject/view/OilPalmAreaList/OilPalmAreaListSubDetail.php?FSID="+
                    locations[i][6]+"&FMID="+locations[i][7]+"'>"+locations[i][5]+"</a>";
                }else{
                    content += locations[i][5];
                }
                content += "<br> อ." + locations[i][2] + " จ." + locations[i][3];
                infowindow.setContent(content);
                infowindow.open(map, marker);

                console.log('i = ' + i)
                console.log(locations)
                console.log(locations[i][1])

            }
        })(marker, i));

    }

}

$("#province").change(function(){
    // console.log('pro = '+$("#province").val());
    select_id = $('#province').val();
    if(select_id == 0){
        $("#distrinct").val("0");
        $("#distrinct").attr("disabled", "disabled");
    }else{
        $("#distrinct").removeAttr("disabled");
        data_show(select_id, "distrinct", '');
    }
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

$("#check1").click(function() {
    $("#harvest").attr("disabled", !this.checked);
    $("#harvest").val(0);
});

$("#check2").click(function() {
    $("#fertilizer").attr("disabled", !this.checked);
    $("#fertilizer").val(0);
});

$("#check3").change(function() {
    $("#water").data("ionRangeSlider").update({
        "disable": !$(this).is(':checked')
    });
});

$("#check4").change(function() {
    $("#waterlack").data("ionRangeSlider").update({
        "disable": !$(this).is(':checked')
    });
});

$("#check5").change(function() {
    $("#wash").data("ionRangeSlider").update({
        "disable": !$(this).is(':checked')
    });
});

$("#check6").click(function() {
    $("#pesttype").attr("disabled", !this.checked);
    $("#pesttype").val(0);
});

$("#water").ionRangeSlider({
    type: "double",
    from: 1,
    to: 5,
    step: 1,
    min: 1,
    max: 365,
    grid: true,
    grid_num: 5,
    grid_snap: false,
    disable: true,
    postfix: " วัน",
    onFinish: function(data) {
        console.log(data.to + " " + data.from);
        minwater = data.from;
        maxwater = data.to;
    }
});
$("#waterlack").ionRangeSlider({
    type: "double",
    from: 1,
    to: 5,
    step: 1,
    min: 1,
    max: 365,
    grid: true,
    grid_num: 10,
    grid_snap: false,
    disable: true,
    postfix: " วัน",
    onFinish: function(data) {
        console.log(data.to + " " + data.from);
        minlack = data.from;
        maxlack = data.to;
    }
});
$("#wash").ionRangeSlider({
    type: "double",
    from: 1,
    to: 5,
    step: 1,
    min: 0,
    max: 30,
    grid: true,
    grid_num: 5,
    grid_snap: false,
    disable: true,
    postfix: " ครั้ง",
    onFinish: function(data) {
        console.log(data.to + " " + data.from);
        console.log('check5 = '+$('#check5').is(':checked'));
        mincutbranch= data.from;
        maxcutbranch = data.to;
        
    }
});