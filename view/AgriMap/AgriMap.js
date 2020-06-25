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
                        "ปริมาณผลผลิต " + location[i].product + " กิโลกรัม" + "<br/>" +
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

$("#check1").click(function() {
    $("#product").attr("disabled", !this.checked);
});

$("#check2").click(function() {
    $("#fertilizer").attr("disabled", !this.checked);
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
    $("#s_pesttype").attr("disabled", !this.checked);
});


$("#water").ionRangeSlider({
    type: "double",
    from: 1,
    to: 1,
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
    }
});
$("#waterlack").ionRangeSlider({
    type: "double",
    from: 1,
    to: 5,
    step: 1,
    min: 0,
    max: 60,
    grid: true,
    grid_num: 10,
    grid_snap: false,
    disable: true,
    postfix: " วัน",
    onFinish: function(data) {
        console.log(data.to + " " + data.from);
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
    }
});