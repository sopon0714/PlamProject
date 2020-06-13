$(document).ready(function() {
    console.log("y");
    $('.tt').tooltip();

});

function initMap() {
    //     icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
    //     // pink-dot.png
    //     // yellow-dot.png
    //     // purple-dot.png
    var locations = [];
    var center = [0, 0];
    click_map = $('.click-map').html();
    size = $('#size').attr('size');
    console.log(size);
    for (i = 0; i < size; i++) {
        la = parseFloat($('#' + i).attr('la'));
        long = parseFloat($('#' + i).attr('long'));
        owner = $('#' + i).attr('owner');
        pro = $('#' + i).attr('pro');
        dist = $('#' + i).attr('dist');

        center[0] += la;
        center[1] += long;
        data = [owner, la, long, dist, pro];
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
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"

        });
        console.log('i == ' + i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                content = "";
                content += locations[i][0];
                content += "<br> อ." + locations[i][3] + " จ." + locations[i][4];
                infowindow.setContent(content);
                infowindow.open(map, marker);

                console.log('i = ' + i)
                console.log(locations)

                for (j = 0; j < size; j++) {
                    if (i == j) {
                        $('.' + j).show();
                    } else {
                        $('.' + j).hide();
                    }
                }

            }
        })(marker, i));

    }

    $('#s_province').click(function() {

        var e = document.getElementById("s_province");
        var select_id = e.options[e.selectedIndex].value;
        // console.log(select_id);
        data_show(select_id, "s_distrinct", '');


    });
    // -------------------------------------------------------------
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

}