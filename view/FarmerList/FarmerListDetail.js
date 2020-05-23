
$( document ).ready(function() {
    console.log("y");
    $('.tt').tooltip();

});
function initMap() {
    var locations = [];
    var center = [0,0];
    click_map = $('.click-map').html();
    console.log(click_map);
    size = $('#size').attr('size');
    console.log(size);
    for(i = 1 ; i < size ; i++){
      subDistrinct = $('#'+i).attr('subDistrinct');
      console.log(subDistrinct);
      la = parseFloat($('#'+i).attr('la'));
      long = parseFloat($('#'+i).attr('long'));
      AD3ID = parseFloat($('#'+i).attr('AD3ID'));
      center[0] += la;
      center[1] += long;
      data = [subDistrinct,la,long,AD3ID];
      locations.push(data);

    }
    center[0] = center[0]/(size-1);
    center[1] = center[1]/(size-1);

    console.log(center);
    console.log(locations);

      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 9,
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
        console.log('i == '+i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            if(i != -1){
              subDistrinct = locations[i][0];
            }else{
              subDistrinct = $('#info').attr('subDistrinct');
            }

            infowindow.setContent(subDistrinct);
            infowindow.open(map, marker);

            console.log('i = '+i)
            console.log(locations)
            if(i != -1){
              for(j = 1 ; j < size ; j++){
                if(i+1 == j){
                  $('.'+j).show();
                }else{
                  $('.'+j).hide();
                }
              }
            }
            
          }
        })(marker, i));
    }

}