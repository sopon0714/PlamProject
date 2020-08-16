$(document).ready(function() {
    win_location = "PestControlDetail.php?farmID=";
    // console.log("y");
    $('.tt').tooltip();

    modify_check = 0; 
    
    $('#add').click(function() {
        // console.log('add');
        $("#addModal").modal();

    });
    $(document).on("click", ".btn-delete", function() {
      lid = $(this).attr('lid');
      fmid = $(this).attr('fmid');
      date= $(this).attr('date');
      subfarm = $(this).attr('subfarm');

      delfunction(lid,subfarm,date,fmid);

    });
    $(document).on("click", ".btn-detail", function() {
      $("#editModal").modal();
      lid = $(this).attr('lid');
      date = $(this).attr('date');
      farm = $(this).attr('farm');
      subfarm = $(this).attr('subfarm');
      o_farm = $(this).attr('o_farm');
      o_subfarm = $(this).attr('o_subfarm');
      pesttype_name = $(this).attr('pesttype_name');
      pesttype_id = $(this).attr('pesttype');
      pestalias = $(this).attr('pestalias');
      pest_id = $(this).attr('pest');
      note = $(this).attr('note');
      modify = $(this).attr('modify');
      modify_check = modify;

      $('#e_date').html(date);
      if(farm != o_farm){
        html =`<s>${o_farm}</s> ${farm}`;
      }else{
        html = farm;
      }
      $('#e_farm').html(html);
      if(subfarm != o_subfarm){
        html =`<s>${o_subfarm}</s> ${subfarm}`;
      }else{
        html = subfarm;
      }
      $('#e_subfarm').html(html);
      $('#e_pesttype').html(pesttype_name);
      $('#e_pest').html(pestalias);
      $('#e_note').html(note);
      $('#e_pestAlarmID').val(lid);

      path = `../../picture/activities/others/`;
      setImgEdit(lid, path);

    });

    $('#date').change(function(){
      date = $('#date').val();
      fmid = $(this).attr("fmid");
      // console.log('fmid = '+fmid);
      // console.log('date = '+date);
      idSetHtml = "#farm";
      selectFarm(idSetHtml,date,fmid);
    });

    $(document).on("click", ".btn-note", function() {
        $('#data').modal();
        note = $(this).attr('note');
        // console.log('note = ' + note);
        $('#n_note').html(note);
    });

    $(document).on("click", ".btn-photo", function() {
      $('#picture').modal();
      lid = $(this).attr("lid");
      path = "../../picture/activities/others/";
      loadPhoto_LogPestAlarm(path, lid);
    });

    $('#save').click(function() {
        let pic_sc = new Array()
        $('.pic-SC').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('#pic').val(pic_sc);

        var PIC_SC = $(".pic-SC");

        if (PIC_SC.length == 0) {
            // console.log('PIC_SC.length == 0');
            $("#pic-style-char").attr("required", "");
            $("#pic-style-char")[0].setCustomValidity('กรุณาเพิ่มรูป');

        } else {
            $("#pic-style-char").removeAttr("required");
            $("#pic-style-char")[0].setCustomValidity('');
        }

    });

    $('#edit').click(function() {
        let pic_sc = new Array()
        $('.pic-SC-edit').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('#pic-edit').val(pic_sc);

        var PIC_SC = $(".pic-SC-edit");

        if (PIC_SC.length == 0) {
            // console.log('PIC_SC.length == 0');
            $("#pic-style-char-edit").attr("required", "");
            $("#pic-style-char-edit")[0].setCustomValidity('กรุณาเพิ่มรูป');

        } else {
            $("#pic-style-char-edit").removeAttr("required");
            $("#pic-style-char-edit")[0].setCustomValidity('');
        }
        if (!check_dup_pic($('#pic-edit').attr('src'), $('#old_pic-edit').attr('src'))) return;
        // if (!check_duplicate(o_name, o_alias, o_charstyle, o_danger, name, alias, charstyle, danger)) return;

    });

    $(document).on("click", ".morelink", function() {
      // console.log('morelink');
      if ($(this).hasClass("less")) {
          $(this).removeClass("less");
          $(this).html(moretext);
      } else {
          $(this).addClass("less");
          $(this).html(lesstext);
      }
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
  
    });

});

function check_dup_pic(pic, old_pic) {
    if (pic == old_pic) {
        return false;
    }
    return true;
}

function setImgEdit(id,path) {
  // console.log('setIMG')
  $.post("manage.php", {request: 'scanDir',path: path ,pid: id}, function(result){
      //  console.log(result);
      arr = JSON.parse(result);

      // console.log(arr);
      var textPicChar = ''

        for (i = 0; i < arr.length; i++) {
            textPicChar += `<div class="card" width="70px" hight="70px">
                              <div class="card-body" style="padding:0;">
       `
                // console.log('arr[i] = '+arr[i]);
                // path = `../../picture/pest/${folder}/${type}/${pid}/${arr[i]}`
            textPicChar += `<img class="pic-SC-edit" src = "${path}${id}/${arr[i]}" id="img${i}-${(+new Date)}" width="100%" hight="100%" />`
            textPicChar += `</div>
      </div>`
        }
        $('#grid-pic-style-char-edit').html(textPicChar);


    });

}

function loadPhoto_LogPestAlarm(path,id) {
  // console.log(path);
  // console.log(id);

  $.post("manage.php", {request: "scanDir",path: path,pid: id}, function(result){
    // console.log(result);
    let data1 = JSON.parse(result);
    // console.log(data1);
        $num = 1;
        let text = "";
        PICS = path + id;
        for (i in data1) {
          if($num <= 4){
            style = "";
          }else{
            style = "margin-top:15px;";
          }
            text += `<a href="${PICS+"/"+data1[i]}" class="col-xl-3 col-3 margin-photo" 
                    style="${style}" target="_blank">
                    <img src="${PICS+"/"+data1[i]}"" class="img-gal">
                </a>`
            $num++;
        }
        $("#fetchPhoto").html(text);

    });
}
function selectFarm(idSetHtml,date,fmid){
  $.post("manage.php", {request: "selectFarm",date:date}, function(result){
    DATA_DB = JSON.parse(result);
    // console.log('set farm id = '+set)
    // console.log(DATA_DB);
    html = "<option value=''>เลือกสวน</option>";
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option ';
      // console.log('fmid = '+fmid);
      // console.log('db = '+DATA_DB[i]['dbID']);
      if(DATA_DB[i]['dbID'] == fmid){
        html += 'selected ';
      }
      html += 'value='+DATA_DB[i]['DIMfarmID']+'>'+DATA_DB[i]['Name']+'</option>';
    }
    $(idSetHtml).html(html);    

    dim_farm = $('#farm').val();
    // console.log('farm = '+$('#farm').attr("class"));
    // console.log('dim_farm = '+dim_farm);
    idSetHtml = "#subfarm";
    selectSubfarm(idSetHtml,dim_farm,date);
  });
}
function selectSubfarm(idSetHtml,id,date){
  $.post("manage.php", {request: "selectSubfarm",id: id,date: date}, function(result){
    // console.log(result);

    DATA_DB = JSON.parse(result);
    // console.log(DATA_DB);
    html = "<option selected value=''>เลือกแปลง</option>";
    
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option value='+DATA_DB[i]['DIMSubfID']+'>'+DATA_DB[i]['Name']+'</option>';
    }
    
    $(idSetHtml).html(html);
  });
}

function initMap() {
    var locations = [];
    var center = [0, 0];

    click_map = $('.click-map').html();
    // console.log(click_map);
    size = $('#size').attr('size');
    // console.log('size = '+size);
    size_check = size;

    for(i = 1 ; i < size ; i++){
      check = parseFloat($('#'+i).attr('check'));
      if(check == 1){
        namesubfarm = $('#'+i).attr('namesubfarm');
        // console.log('map i = '+i);
        la = $('#'+i).attr('la');
        long = $('#'+i).attr('long');
        laFloat = parseFloat($('#'+i).attr('la'));
        longFloat = parseFloat($('#'+i).attr('long'));
        dist = $('#'+i).attr('dist');
        pro = $('#'+i).attr('pro');
        owner = $('#'+i).attr('owner');
        center[0] += laFloat;
        center[1] += longFloat;
        data = [namesubfarm,la,long,dist,pro,owner];
        locations.push(data);
      }else{
        size_check--; 
      }
    }
    center[0] = center[0] / (size_check - 1);
    center[1] = center[1] / (size_check - 1);
    // console.log('size_check = '+size_check);

    if (size - 1 == 0 || size_check -1 == 0) {
        center[0] = 13.736717;
        center[1] = 100.523186;
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

                // console.log('i = ' + i)
                // console.log('la' + locations[i][1]+'long'+locations[i][2]);

                // console.log('locations[0][0] = '+locations[0][0]);
                // console.log($('.la' + locations[i][1]+'long'+locations[i][2]).attr("test"));
                $('.la'+locations[i][1]+'long'+locations[i][2]).each(function(){
                  // console.log("this");
                  // console.log($(this).attr("test"));
                });

                if (i != -1) {
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

function delfunction(_id, _subfarm, _name,_fmid) {
    swal({
            title: "คุณต้องการลบการล้างคอขวด",
            text: `วันที่ ${_name} ในแปลง ${_subfarm} หรือไม่ ?`,
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
                        delete_1(_id,_fmid)
                    }
                });
            } else {}
        });
}

function delete_1(_id,_fmid) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = win_location+_fmid;
            // alert(this.responseText);
        }
    };
    xhttp.open("POST", "manage.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`request=deleteDetail&id=${_id}`);

}

