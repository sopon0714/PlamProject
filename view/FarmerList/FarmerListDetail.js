
$( document ).ready(function() {
    // console.log("y");
    $('.tt').tooltip();

});
function initMap() {
    var locations = [];
    var center = [0,0];
    click_map = $('.click-map').html();
    // console.log(click_map);
    size = $('#size').attr('size');
    // console.log('size='+size);
    for(i = 1 ; i < size ; i++){

      la = $('#'+i).attr('la');
      long = $('#'+i).attr('long');
      laFloat = parseFloat($('#'+i).attr('la'));
      longFloat = parseFloat($('#'+i).attr('long'));
      farm = $('#' + i).attr('farm');
      pro = $('#' + i).attr('pro');
      dist = $('#' + i).attr('dist'); 
      center[0] += laFloat;
      center[1] += longFloat;
      data = [farm, la, long, dist, pro];
      locations.push(data);
      // console.log(laFloat);
      // console.log(longFloat);
    }
    center[0] = center[0]/(size-1);
    center[1] = center[1]/(size-1);

    // console.log(center);
    // console.log(locations);

    if(size-1 == 0){
      center[0] = $('#info').attr('la');
      center[1] = $('#info').attr('long');
    }

    // console.log(center);

      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        center: new google.maps.LatLng(center[0], center[1]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
  
      var infowindow = new google.maps.InfoWindow();
  
      for (i = -1; i < locations.length; i++) {  
        if(i != -1){
          la = locations[i][1];
          long = locations[i][2];
          icon = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
        }else{
          la = $('#info').attr('la');
          long = $('#info').attr('long');
          icon = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
        }
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(la, long),
          map: map,
          icon: icon,
        });
        // console.log('i == '+i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            if(i != -1){
              content = "";
              content += locations[i][0];
              content += "<br> อ." + locations[i][3] + " จ." + locations[i][4];
            }else{
              content = "";
              content += $('#info').attr('owner');
              content += "<br> อ." + $('#info').attr('dist') + " จ." + $('#info').attr('pro');
            }

            infowindow.setContent(content);
            infowindow.open(map, marker);

            // console.log('i = '+i)
            // console.log(locations)
            if(i != -1){
              
              for (j = 0; j < size-1; j++) {

                lati1 = locations[i][1].replace('.','-');
                longi1 = locations[i][2].replace('.','-');
                lati2 = locations[j][1].replace('.','-');
                longi2 = locations[j][2].replace('.','-');
                if (lati1 == lati2 && longi1 == longi2) {
                    $('.la' + lati1+'long'+longi1).show();
                } else {
                    $('.la' + lati2+'long'+longi2).hide();
                }
            }
            }
            
          }
        })(marker, i));
    }

}