
// pagination
idformal = $("#data_search").attr("idformal");
fullname = $("#data_search").attr("fullname");
fpro = $("#data_search").attr("fpro");
fdist = $("#data_search").attr("fdist");

//end pagination
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
// pagination
function getDataSetTable(){
    $.post("manage.php", {action: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,start: start,limit: limit}, function(result){
        DATA = JSON.parse(result);
        setTableBody(DATA);
    });
}
function setTableBody(DATA){
    html = ``;
    $i = 0;
    for (const [key, value] of Object.entries(DATA)) {
        html += `<tr class="la${value["Latitude"]} long${value["Longitude"]} table-set" test="test${i}}">
                    <td>${value["FullName"]}</td>
                    <td>${value["NameFarm"]}</td>
                    <td style="text-align:right;">${value["NumSubFarm"]} แปลง</td>
                    <td style="text-align:right;">${value["AreaRai"]} ไร่ ${value["AreaNgan"]} วา</td>
                    <td style="text-align:right;">${value["NumTree"]} ต้น</td>
                    <td style="text-align:right;">${parseFloat(value["VolHarvest"]).toFixed(2)} ก.ก.</td>
                    <td style="text-align:center;">
                        <form method="post"  name="formID" action="./OilPalmAreaVolDetail.php?FMID=${value["FMID"]}">
                            <button type="submit"  class="btn btn-info btn-sm" data-toggle="tooltip" title="รายละเอียด"><i class="fas fa-bars"></i></button></a>
                        </form>
                    </td>
                    <label class="click-map" hidden id="${i++}"
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
    $.post("manage.php", {action: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,start: 0,limit: 0}, function(result){
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
