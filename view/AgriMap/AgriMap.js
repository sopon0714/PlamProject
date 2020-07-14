minlack = 1;
maxlack = 5;

minwater = 1;
maxwater = 5;

mincutbranch = 1;
maxcutbranch = 5;

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
        minwater= 0;
        maxwater = 0;
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
        mincutbranch= 0;
        maxcutbranch = 0;
    }else{
        cutbranch = 1;
    }

    $.post("manage.php", {request: "search", year:year, province:province, distrinct:distrinct,
    farmer:farmer, harvest:harvest, fertilizer:fertilizer, minwater:minwater, maxwater:maxwater, 
    minlack:minlack, maxlack:maxlack, pesttype:pesttype,
    mincutbranch:mincutbranch, maxcutbranch:maxcutbranch,
    lack:lack, water:water, cutbranch:cutbranch}, function(result){
        console.log(result);

        // console.log('maxc = '+maxcutbranch);
        // DATA_DB = JSON.parse(result);
        // console.log(DATA_DB);
    });
});
function initMap(data) {
    //The location of Uluru
    if (data) {

        var th = {
            lat: 15.8700,
            lng: 100.9925
        };

        var map = new google.maps.Map(
            document.getElementById('map'), {
                zoom: 4,
                center: th
            });

        var location = JSON.parse(data);

        var marker, i, info;

        //console.log("dododod");



        for (i = 0; i < location.length; i++) {
            console.log(location[i].name, location[i].lat, location[i].lng);
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(location[i].lat, location[i].lng),
                title: location[i].name,
                map: map
            });

            info = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    info.setContent(location[i].name + "<br/>" + location[i].address + "<br/>" +
                        "ปริมาณผลผลิต " + location[i].harvest + " กิโลกรัม" + "<br/>" +
                        '<a href = "../OilPalmAreaVol/OilPalmAreaVol.php">เพิ่มเติม</a>');
                    info.open(map, marker);
                }
            })(marker, i));

        }

    } else {
        var th = {
            lat: 15.8700,
            lng: 100.9925
        };
        // The map, centered at Uluru
        var map = new google.maps.Map(
            document.getElementById('map'), {
                zoom: 4,
                center: th
            });
        // The marker, positioned at Uluru
    }

    //location.length=0;
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
    min: 0,
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
    min: 0,
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