
$( document ).ready(function() {
    // console.log("y");
    $('.tt').tooltip();

});
function initMap() {
    var startLatLng = new google.maps.LatLng(13.736717, 100.523186);

    mapdetail = new google.maps.Map(document.getElementById('map'), {
        // center: { lat: 13.7244416, lng: 100.3529157 },
        center: startLatLng,
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    mapdetail.markers = [];
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(13.736717, 100.523186),
        //icon: "http://maps.google.com/mapfiles/kml/paddle/grn-circle.png",
        map: mapdetail,
        title: "test"
    });
    $('#s_province').click(function(){

        var e = document.getElementById("s_province");
        var select_id = e.options[e.selectedIndex].value;
        // console.log(select_id);
        data_show(select_id,"s_distrinct",'');
            

    });
    // -------------------------------------------------------------
    function data_show(select_id,result,point_id){
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