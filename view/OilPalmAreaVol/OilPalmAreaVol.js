var dataFarm;
$(document).ready(function() {
    //  console.log("y");
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
    console.log(size);
    if (size == 0) {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: new google.maps.LatLng(10.667028, 99.201250),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    } else {
        click_map = $('.click-map').html();
        for (i = 1; i <= size; i++) {
            nameFarm = $('#' + i).attr('nameFarm');

            la = parseFloat($('#' + i).attr('la'));
            long = parseFloat($('#' + i).attr('long'));
            distrinct = $('#' + i).attr('distrinct');
            province = $('#' + i).attr('province');


            center[0] += la;
            center[1] += long;
            data = [nameFarm, la, long, distrinct, province];

            locations.push(data);

        }
        center[0] = center[0] / size;
        center[1] = center[1] / size;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
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


            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    content = "";
                    content += locations[i][0];
                    content += "<br> อ." + locations[i][3] + " จ." + locations[i][4];
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
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

    }

}