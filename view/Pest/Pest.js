$(document).ready(function() {
    console.log("y");
    $('.tt').tooltip();

    modify_check = 0; 

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

    $(document).on("click", ".btn-edit", function() {
      $("#editModal").modal();
      lid = $(this).attr('lid');
      date = $(this).attr('date');
      farm_id = $(this).attr('farm');
      subfarm_id = $(this).attr('subfarm');
      pesttype_id = $(this).attr('pesttype');
      pest_id = $(this).attr('pest');
      note = $(this).attr('note');
      modify = $(this).attr('modify');
      modify_check = modify;

      $('#e_date').val(date);
      // $('#e_farm').val(farm_id);
      $('#e_pesttype').val(pesttype_id);
      $('#e_note').val(note);
      $('#e_pestAlarmID').val(lid);
      idSetHtml = '#e_farm';
      selectFarm(idSetHtml, modify,farm_id);
      idSetHtml = '#e_subfarm';
      selectSubfarm(idSetHtml,farm_id,subfarm_id,modify_check);
      idSetHtml = '#e_pest';
      selectPest(idSetHtml,pesttype_id,pest_id,modify_check);
      console.log('lid = '+lid);

      footer = `<div class="img-reletive">
      <img width="100px" height="100px" src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
      <input type="file" id="pic-style-char-edit" name="picstyle_insert-edit[]" accept=".jpg,.png" multiple>
      </div>`

        path = `../../picture/activities/pest/`;
        setImgEdit(lid, footer, path);

    });

    $('#farm').click(function() {
      fmid = $('#farm').val();
      idSetHtml = '#subfarm';
      selectSubfarm(idSetHtml,fmid,-1,0);
    });

    $('#pesttype').click(function() {
      ptid = $('#pesttype').val();
      idSetHtml = '#pest';
      selectPest(idSetHtml,ptid,-1,0);
    });

    $('#e_farm').click(function() {
      console.log('modify_check = '+modify_check);
      DIMfarmID = $('#e_farm').val();
      idSetHtml = '#e_subfarm';
      selectSubfarm(idSetHtml,DIMfarmID,-1,modify_check);
    });

    $('#e_pesttype').click(function() {
      ptid = $('#e_pesttype').val();
      idSetHtml = '#e_pest';
      selectPest(idSetHtml,ptid,-1,modify_check);
    });

    $(document).on("click", ".btn-note", function() {
        $('#data').modal();
        note = $(this).attr('note');
        console.log('note = ' + note);
        $('#n_note').html(note);
    });
    
    $(document).on("click", ".btn-pest", function() {
        $('#pest_data').modal();
        DATA_DB = [];
        arr = [];

        pid = $(this).attr('pest');
        dpid = $(this).attr('dimpest');
        ptid = $(this).attr('pesttype');
        $.post("manage.php", { request: "selectPestByPID", dpid: dpid }, function(result) {
            // console.log(result)

            DATA_DB = JSON.parse(result);
            // console.log(DATA_DB)
            path = "../../icon/pest/" + DATA_DB[1]['dbpestLID'] + "/" + DATA_DB[1]['Icon'];
            $('#data_icon').attr('src', path);
            $('#data_name').html('ชื่อ : ' + DATA_DB[1]["Name"]);
            $('#data_alias').html('ชื่อทางการ : ' + DATA_DB[1]["Alias"]);

            if (ptid == 1) {
                subpath = "insect";
            } else if (ptid == 2) {
                subpath = "disease";
            } else if (ptid == 3) {
                subpath = "weed";
            } else if (ptid == 4) {
                subpath = "other";
            }
            path_style = "../../picture/pest/" + subpath + "/style/";
            path_danger = "../../picture/pest/" + subpath + "/danger/";

            $.post("manage.php", { request: "scanDir", pid: pid, path: path_style }, function(result1) {
                // console.log('pid = '+pid)
                // console.log(result1)

                arr = JSON.parse(result1);
                // console.log(arr)

                html = "<div class='carousel-item active'>" +
                    "<img class='d-block w-100'" +
                    "src='" + path_style + pid + "/" + arr[0] + "'" +
                    "style='height:200px;'>" +
                    "</div>";
                for (i = 1; i < DATA_DB[1]["NumPicChar"]; i++) {
                    html += "<div class='carousel-item'>" +
                        "<img class='d-block w-100'" +
                        "src='" + path_style + pid + "/" + arr[i] + "'" +
                        "style='height:200px;'>" +
                        "</div>";
                }

                $('#data_char1').html(html);
                $('#data_char2').html(DATA_DB[1]["Charactor"]);
            });
            $.post("manage.php", { request: "scanDir", pid: pid, path: path_danger }, function(result1) {
                arr = JSON.parse(result1);
                // console.log(arr)

                html = "<div class='carousel-item active'>" +
                    "<img class='d-block w-100'" +
                    "src='" + path_danger + pid + "/" + arr[0] + "'" +
                    "style='height:200px;'>" +
                    "</div>";
                for (i = 1; i < DATA_DB[1]["NumPicDanger"]; i++) {
                    html += "<div class='carousel-item'>" +
                        "<img class='d-block w-100'" +
                        "src='" + path_danger + pid + "/" + arr[i] + "'" +
                        "style='height:200px;'>" +
                        "</div>";
                }

      pid = $(this).attr('pest');
      dim_pest = $(this).attr('dimpest');
      ptid = $(this).attr('pesttype');
      $.post("manage.php", {request: "selectPestByPID",dim_pest: dim_pest}, function(result){
        // console.log(result)

        DATA_DB = JSON.parse(result);
        // console.log(DATA_DB)
        path = "../../icon/pest/" + DATA_DB[1]['dbpestLID'] + "/" + DATA_DB[1]['FileName'];
        $('#data_icon').attr('src',path);
        $('#data_name').html('ชื่อ : '+DATA_DB[1]["Name"]);
        $('#data_alias').html('ชื่อทางการ : '+DATA_DB[1]["Alias"]);

        if(ptid == 1){
          subpath = "insect";
        }else if(ptid == 2){
          subpath = "disease";
        }else if(ptid == 3){
          subpath = "weed";
        }else if(ptid == 4){
          subpath = "other";
        }
        path_style = "../../picture/pest/"+subpath+"/style/";
        path_danger = "../../picture/pest/"+subpath+"/danger/";

        // pid = DATA_DB[1]["dbpestLID"];
        $('#data_char2').html(DATA_DB[1]["Charactor"]);
        $('#data_dang2').html(DATA_DB[1]["Danger"]);

        $.post("manage.php", {request: "scanDir",pid: pid ,path:path_style}, function(result1){
          // console.log('pid = '+pid)
          // console.log(result1)

          arr = JSON.parse(result1);
          // console.log(arr)
  
          html = "<div class='carousel-item active'>"+
                    "<img class='d-block w-100'"+
                    "src='"+path_style+pid+"/"+ arr[0]+"'"+
                    "style='height:200px;'>"+
                  "</div>";
          for (i = 1; i < DATA_DB[1]["NumPicChar"]; i++) {
            html += "<div class='carousel-item'>"+
              "<img class='d-block w-100'"+
              "src='"+path_style+pid+"/"+ arr[i]+"'"+
              "style='height:200px;'>"+
              "</div>";
          }
  
          $('#data_char1').html(html);
        });
        $.post("manage.php", {request: "scanDir",pid: pid ,path:path_danger}, function(result1){
          arr = JSON.parse(result1);
          // console.log(arr)
  
          html = "<div class='carousel-item active'>"+
                    "<img class='d-block w-100'"+
                    "src='"+path_danger+pid+"/"+ arr[0]+"'"+
                    "style='height:200px;'>"+
                  "</div>";
          for (i = 1; i < DATA_DB[1]["NumPicDanger"]; i++) {
            html += "<div class='carousel-item'>"+
              "<img class='d-block w-100'"+
              "src='"+path_danger+pid+"/"+ arr[i]+"'"+
              "style='height:200px;'>"+
              "</div>";
          }
  
          $('#data_dang1').html(html);
          showmore();


    });
    $(document).on("click", ".btn-photo", function() {
        $('#picture').modal();
        lid = $(this).attr("lid");
        path = "../../picture/activities/pest/";
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
// function check_duplicate(o_name, o_alias, o_charstyle, o_danger, name, alias, charstyle, danger) {
//   if (o_name == name && o_alias == alias && o_charstyle == charstyle && o_danger == danger) {
//       return false;
//   }
//   return true;
// }
function check_dup_pic(pic, old_pic) {
    if (pic == old_pic) {
        return false;
    }
    return true;
}

function setImgEdit(id, footer,path) {
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
          <div class="card-footer">
              <button  type="button" class="btn btn-warning edit-img-edit">แก้ไข</button>
              <button  type="button" class="btn btn-danger delete-img">ลบ</button>
          </div>
      </div>`
        }
        textPicChar += footer
        $('#grid-pic-style-char-edit').html(textPicChar);

        $('#pic-style-char-edit').on('change', function() {
            // alert('change')
            imagesPreview(this, '#grid-pic-style-char-edit', 'pic-style-char-edit', 'pic-SC-edit', 'edit-img-edit');
        });

        $(document).on('click', '.edit-img-edit', function() {
            let url = $(this).parent().prev().children().attr('src')
                // console.log('url = '.url);
            idImg = $(this).parent().prev().children().attr('id')
            cropImgEdit(url, 'square')
        })

        function cropImgEdit(url, type) {
            // console.log('url = '+url);
            // console.log('type = '+type);
            // console.log('crop-img-edit');
            $('.main-edit').hide()
            $('.normal-button-edit').hide()
            $('.crop-img-edit').show()
            $('.crop-button-edit').show()

            let UC = $('.upload-demo2-edit').croppie({
                viewport: {
                    width: 300,
                    height: 200,
                    type: type
                },
                enforceBoundary: false,
                enableExif: true
            });
            UC.croppie('bind', {
                url: url
            }).then(function() {
                // console.log('jQuery bind edit complete');
            });
        }

        $('.crop-img-edit').hide()
        $('.crop-button-edit').hide()

        let pic_sc = new Array()
        $('.pic-SC-edit').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('#old_pic-edit').val(pic_sc);

    });

}

function showmore(){
  // Configure/customize these variables.
  console.log('showmore');
  var showChar = 180; // How many characters are shown by default
  var ellipsestext = "...";
  var moretext = "Show more";
  var lesstext = "Show less";
  $('.more').each(function() {
      var content = $(this).html();
      content = content.trim();
      content += ' ';
      html = '';
      // console.log(content);
      // console.log('lenght = '+content.length);

      for(i = showChar ; i<content.length; i++){
          if(content[i] == ' '){
              showChar = i;
              break;
          }
      }

      if ((content.length-1) > showChar) {
          console.log('set more text');
          var c = content.substr(0, showChar);
          var h = content.substr(showChar, content.length - showChar);

          var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h +
              '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
          
          $(this).html(html);
          // console.log(html);
      }

  });

}

function loadPhoto_LogPestAlarm(path,id) {
  // console.log(path);
  // console.log(id);

  $.post("manage.php", {request: "scanDir",path: path,pid: id}, function(result){
    // console.log(result);
    let data1 = JSON.parse(result);
    // console.log(data1);

        let text = "";
        PICS = path + id;
        for (i in data1) {
            text += `<a href="${PICS+"/"+data1[i]}" class="col-xl-3 col-3 margin-photo" target="_blank">
                    <img src="${PICS+"/"+data1[i]}"" class="img-gal">
                </a>`
        }
        $("#fetchPhoto").html(text);

    });
}
function selectFarm(idSetHtml,modify,set){
  $.post("manage.php", {request: "selectFarm",modify: modify}, function(result){
    DATA_DB = JSON.parse(result);
    // console.log('set farm id = '+set)
    // console.log(DATA_DB);
    html = "<option selected value=''>เลือกสวน</option>";
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option value='+DATA_DB[i]['DIMfarmID']+'>'+DATA_DB[i]['Name']+'</option>';
    }
    $(idSetHtml).html(html);
    if(set != -1){
      $('#e_farm').val(set);
    }
  });
}
function selectSubfarm(idSetHtml,id,set,modify_num){
  $.post("manage.php", {request: "selectSubfarm",id: id,modify: modify_num}, function(result){
    DATA_DB = JSON.parse(result);
    // console.log('set subfarm id = '+set)
    // console.log(DATA_DB);
    html = "<option selected value=''>เลือกแปลง</option>";
    if(modify_num == 0){
      for (i = 1; i <= DATA_DB[0]['numrow']; i++) {
        html += '<option value=' + DATA_DB[i]['fsid'] + '>' + DATA_DB[i]['namesub'] + '</option>';
      }
    }else{
      for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
        html +='<option value='+DATA_DB[i]['DIMSubfID']+'>'+DATA_DB[i]['Name']+'</option>';
      }
    }
    $(idSetHtml).html(html);
    if(set != -1){
      $('#e_subfarm').val(set);
    }
  });
}
function selectPest(idSetHtml,type_id,set,modify_num){
  $.post("manage.php", {request: "selectPest",type_id: type_id,modify:modify_num}, function(result){
    DATA_DB = JSON.parse(result);
    html = "<option selected value=''>เลือกศัตรูพืช</option>";
    if(modify_num == 0){
      for (i = 1; i <= DATA_DB[0]['numrow']; i++) {
        html += '<option value=' + DATA_DB[i]['PID'] + '>' + DATA_DB[i]['Alias'] + '</option>';
      }
    }else{
      for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
        html +='<option value='+DATA_DB[i]['DIMpestID']+'>'+DATA_DB[i]['Alias']+'</option>';
      }
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
    var center = [0, 0];

    click_map = $('.click-map').html();
    // console.log(click_map);
    size = $('#size').attr('size');
    // console.log(size);

    for(i = 1 ; i < size ; i++){
      namesubfarm = $('#'+i).attr('namesubfarm');
      // console.log(namesubfarm);
      la = parseFloat($('#'+i).attr('la'));
      long = parseFloat($('#'+i).attr('long'));
      AD3ID = parseFloat($('#'+i).attr('AD3ID'));
      center[0] += la;
      center[1] += long;
      data = [namesubfarm,la,long,AD3ID];
      locations.push(data);

    }
    center[0] = center[0] / (size - 1);
    center[1] = center[1] / (size - 1);

    if (size - 1 == 0) {
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
        console.log('i == ' + i)
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);

                console.log('i = ' + i)
                console.log(locations)

                if (i != -1) {
                    for (j = 1; j < size; j++) {
                        if (i + 1 == j) {
                            $('.' + j).show();
                        } else {
                            $('.' + j).hide();
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

function delfunction(_id, _subfarm, _name) {
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
            } else {}
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