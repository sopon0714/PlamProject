$(document).ready(function() {
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
                console.log(this.responseText);
                // console.log(result);
                document.getElementById(result).innerHTML = xhttp.responseText;

            };
        }
        xhttp.open("POST", "data.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`select_id=${select_id}&result=${result}&point_id=${point_id}`);
    }


});

function initMap() {
    var locations = [];
    var center = [0, 0];
    size = $('#size').attr('size');
    console.log("size:" + size)
    if (size == 0) {
        center[0] = 13.736717;
        center[1] = 100.523186;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: new google.maps.LatLng(13.736717, 100.523186),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    } else {
        for (i = 1; i <= size; i++) {
            namesubfarm = $('#' + i).attr('namesubfarm');
            la = $('#' + i).attr('la');
            long = $('#' + i).attr('long');
            laFloat = parseFloat($('#' + i).attr('la'));
            longFloat = parseFloat($('#' + i).attr('long'));
            dist = $('#' + i).attr('distrinct');
            pro = $('#' + i).attr('province');
            center[0] += laFloat;
            center[1] += longFloat;
            data = [namesubfarm, la, long, dist, pro];
            locations.push(data);
        }
        center[0] = center[0] / size;
        center[1] = center[1] / size;
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
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    content = locations[i][0];
                    content += "<br> อ." + locations[i][3] + " จ." + locations[i][4];
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                    $('.defualtlatlog').hide();
                    $className = '.la' + locations[i][1].replace('.', '-') + 'long' + locations[i][2].replace('.', '-');
                    $($className).show();
                }
            })(marker, i));

        }
    }

}