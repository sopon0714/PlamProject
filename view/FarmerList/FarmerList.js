$(document).ready(function() {
    // console.log("y");
    $('.tt').tooltip();

    // pagination
    idformal = $("#data_search").attr("idformal");
    fullname = $("#data_search").attr("fullname");
    fpro = $("#data_search").attr("fpro");
    fdist = $("#data_search").attr("fdist");
    
    //end pagination

});
// pagination
function getDataSetTable(){
    $.post("manage.php", {request: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,start: start,limit: limit}, function(result){
        DATA = JSON.parse(result);
        // console.log(result);
        // console.log(DATA[0]["numrow"]);
        setTableBody(DATA);
    });
}
// pagination
function setTableBody(DATA){
    html = ``;
        for (i = 1; i <= DATA[0]["numrow"]; i++) {
                html += `<tr class="la${DATA[i]["Latitude"]} long${DATA[i]["Longitude"]} table-set"
                                test="test${i}}">
                    <td>${DATA[i]['FullName']}</td>
                    <td>${DATA[i]["Province"]}</td>
                    <td>${DATA[i]["Distrinct"]}</td>

                    <td class="text-right">${DATA[i]['numFarm']} สวน</td>
                    <td class="text-right">${DATA[i]['numSubFarm']} แปลง</td>
                    <td class="text-right">${DATA[i]['numArea1']} ไร่
                    ${DATA[i]['numArea2']} งาน</td>
                    <td class="text-right">${DATA[i]['numTree']} ต้น</td>

                    <td style="text-align:center;">
                        <a href='FarmerListDetail.php?farmerID=${DATA[i]['dbID']}'>
                        <button type='button' id='btn_info' class="btn btn-info btn-sm btn_edit tt"
                            data-toggle="tooltip" title="รายละเอียดข้อมูลเกษตรกร">
                            <i class='fas fa-bars'></i>
                        </button>
                        </a>
                    </td>
                    <label class="click-map" hidden id="${i}"
                        pro="${DATA[i]["Province"]}"
                        dist="${DATA[i]["Distrinct"]}"
                        owner="${DATA[i]["FullName"]}"
                        check="${DATA[i]["check_show"]}"
                        la="${DATA[i]["Latitude"]}"
                        long="${DATA[i]["Longitude"]}"></label>
            </tr>`;
        }
        $("#body").html(html);
}
function initMap() {
    var locations = [];
    var center = [0, 0];
    // pagination
    $(".loader-container").fadeIn(0);
    $(".loader").fadeIn(0);
    idformal = $("#data_search").attr("idformal");
    fullname = $("#data_search").attr("fullname");
    fpro = $("#data_search").attr("fpro");
    fdist = $("#data_search").attr("fdist");
    fade = false;
    $.post("manage.php", {request: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,start: 0,limit: 0}, function(result){
        // console.log(result);
        DATA = JSON.parse(result);
        getDataSetTable();
        $(".loader-container").fadeOut(500);
        // console.log(DATA);
        // console.log("init map numrow data = "+DATA[0]["numrow"]);
        size = DATA[0]["numrow"];
        for (i = 1; i <= size; i++) {
            la = DATA[i]["Latitude"];
            long = DATA[i]["Longitude"];
            laFloat = parseFloat(DATA[i]["Latitude"]);
            longFloat = parseFloat(DATA[i]["Longitude"]);
            dist = DATA[i]["Distrinct"];
            pro = DATA[i]["Province"];
            owner = DATA[i]["FullName"];
            center[0] += laFloat;
            center[1] += longFloat;
            data = [owner, la, long, dist, pro];
            locations.push(data);
    
        }
        center[0] = center[0] / size;
        center[1] = center[1] / size;
    
        if(size == 0){
          center[0] = 13.736717;
          center[1] = 100.523186;
        }
    
        // console.log(center);
    
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
                icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
    
            });
            // console.log('i == ' + i)
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    content = "";
                    content += locations[i][0];
                    content += "<br> อ." + locations[i][3] + " จ." + locations[i][4];
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
    
                    // console.log('i = ' + i)
                    // console.log(locations)
                    // console.log(locations[i][1])

                    // for (j = 0; j < size; j++) {
    
                    //     lati1 = locations[i][1].replace('.','-');
                    //     longi1 = locations[i][2].replace('.','-');
                    //     lati2 = locations[j][1].replace('.','-');
                    //     longi2 = locations[j][2].replace('.','-');
                    //     if (lati1 == lati2 && longi1 == longi2) {
                    //         $('.la' + lati1+'long'+longi1).show();
                    //     } else {
                    //         $('.la' + lati2+'long'+longi2).hide();
                    //     }
                    // }
                    
                    $.post("manage.php", {request: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,start: 0,limit: 0,latitude: locations[i][1],longitude: locations[i][2]}, function(result){
                        DATA = JSON.parse(result);
                        // console.log(result);
                        // console.log(DATA[0]["numrow"]);
                        setTableBody(DATA);
                    });
    
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
    
    });

}