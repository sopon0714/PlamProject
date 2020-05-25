
$( document ).ready(function() {
    console.log("y");
    $('.tt').tooltip();

    // $('#date').datepicker({
    //   autoHide: true,
    //   zIndex: 2048,
    //   language: 'th-TH',
    //   format: 'dd-mm-yyyy'
    // });

    $('#add').click(function() {
      console.log('add');      
      $("#addModal").modal();

    });

    $('.btn-edit').click(function(){
      $("#editModal").modal();
      date = $(this).attr('date');
      farm_id = $(this).attr('farm');
      subfarm_id = $(this).attr('subfarm');
      pesttype_id = $(this).attr('pesttype');
      pest_id = $(this).attr('pest');
      note = $(this).attr('note');

      $('#e_date').val(date);
      $('#e_farm').val(farm_id);
      $('#e_pesttype').val(pesttype_id);
      $('#e_note').val(note);

      idSetHtml = '#e_subfarm';
      selectSubfarm(idSetHtml,farm_id,subfarm_id);
      idSetHtml = '#e_pest';
      console.log('pestid = '+pest_id);
      selectPest(idSetHtml,pesttype_id,pest_id);

    });

    $('#farm').click(function() {
      fmid = $('#farm').val();
      idSetHtml = '#subfarm';
      selectSubfarm(idSetHtml,fmid,-1);
    });
    $('#pesttype').click(function() {
      ptid = $('#pesttype').val();
      idSetHtml = '#pest';
      selectPest(idSetHtml,ptid,-1);
    });

    $('#e_farm').click(function() {
      fmid = $('#e_farm').val();
      idSetHtml = '#e_subfarm';
      selectSubfarm(idSetHtml,fmid,-1);
    });
    $('#e_pesttype').click(function() {
      ptid = $('#e_pesttype').val();
      idSetHtml = '#e_pest';
      selectPest(idSetHtml,ptid,-1);
    });
    
    $('.btn-note').click(function(){
      $('#data').modal();
      note = $(this).attr('note');
      console.log('note = '+note);
      $('#n_note').html(note);
    });

    $('.btn-pest').click(function(){
      $('#pest_data').modal();
      pid = $(this).attr('pest');
      $.post("manage.php", {request: "selectPestByPID",pid: pid}, function(result){
        DATA_DB = JSON.parse(result);
        console.log(DATA_DB)
        path = "../../icon/pest/" + DATA_DB[1]['PID'] + "/" + DATA_DB[1]['Icon'];
        $('#data_icon').attr('src',path);
        $('#data_name').html('ชื่อ : '+DATA_DB[1]["Name"]);
        $('#data_name').html('ชื่อทางการ : '+DATA_DB[1]["Alias"]);
      });
    });

});

function selectSubfarm(idSetHtml,fmid,set){
  $.post("manage.php", {request: "selectSubfarm",fmid: fmid}, function(result){
    DATA_DB = JSON.parse(result);
    html = "<option selected value=''>เลือกแปลง</option>";
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option value='+DATA_DB[i]['fsid']+'>'+DATA_DB[i]['namesub']+'</option>';
    }
    $(idSetHtml).html(html);
    if(set != -1){
      $('#e_subfarm').val(set);
    }
  });
}
function selectPest(idSetHtml,ptid,set){
  $.post("manage.php", {request: "selectPest",ptid: ptid}, function(result){
    DATA_DB = JSON.parse(result);
    html = "<option selected value=''>เลือกศัตรูพืช</option>";
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option value='+DATA_DB[i]['PID']+'>'+DATA_DB[i]['Alias']+'</option>';
    }
    $(idSetHtml).html(html);
    if(set != -1){
      $('#e_pest').val(set);
    }
  });
}
function initMap() {
    //     icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
    //     // pink-dot.png
    //     // yellow-dot.png
    //     // purple-dot.png
    var locations = [];
    var center = [0,0];

    click_map = $('.click-map').html();
    // console.log(click_map);
    size = $('#size').attr('size');
    console.log(size);

    for(i = 1 ; i < size ; i++){
      namesubfarm = $('#'+i).attr('namesubfarm');
      console.log(namesubfarm);
      la = parseFloat($('#'+i).attr('la'));
      long = parseFloat($('#'+i).attr('long'));
      AD3ID = parseFloat($('#'+i).attr('AD3ID'));
      center[0] += la;
      center[1] += long;
      data = [namesubfarm,la,long,AD3ID];
      locations.push(data);

    }
    center[0] = center[0]/(size-1);
    center[1] = center[1]/(size-1);

    if(size-1 == 0){
      center[0] = 13.736717;
      center[1] = 100.523186;
    }

    console.log(center);

    console.log(locations);
  
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
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
        console.log('i == '+i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            infowindow.setContent(locations[i][0]);
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
function delfunction(_id, _subfarm,_name) {
  // alert(_did);
  swal({
          title: "คุณต้องการลบการพบศัตรูพืช",
          text: `${_name} ในแปลง ${_subfarm} หรือไม่ ?`,
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          cancelButtonClass: "btn-secondary",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
          closeOnConfirm: false,
          closeOnCancel: function() {
              $('[data-toggle=tooltip]').tooltip({
                  boundary: 'window',
                  trigger: 'hover'
              });
              return true;
          }
      },
      function(isConfirm) {
          if (isConfirm) {
              // console.log(1)
              swal({
                  title: "ลบข้อมูลสำเร็จ",
                  type: "success",
                  confirmButtonClass: "btn-danger",
                  confirmButtonText: "ตกลง",
                  closeOnConfirm: false,
              }, function(isConfirm) {
                  if (isConfirm) {
                      delete_1(_id)
                  }
              });
          } else {
          }
      });
}

function delete_1(_id) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          window.location.href = "Pest.php";
          // alert(this.responseText);
      }
  };
  xhttp.open("POST", "manage.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(`request=delete&id=${_id}`);

}