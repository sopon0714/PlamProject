// pagination
idformal = $("#data_search").attr("idformal");
fullname = $("#data_search").attr("fullname");
fpro = $("#data_search").attr("fpro");
fdist = $("#data_search").attr("fdist");
fyear = $("#data_search").attr("fyear");
ftype = $("#data_search").attr("ftype");
//end pagination
$(document).ready(function() {
    // console.log("y");
    $('.tt').tooltip();

    modify_check = 0;
    showChar = 180; // How many characters are shown by default
    ellipsestext = "...";
    moretext = "Show more";
    lesstext = "Show less";

    $('#add').click(function() {
        // console.log('add');
        $("#addModal").modal();

    });
    $(document).on("click", ".btn-delete", function() {
      lid = $(this).attr('lid');
      pestalias= $(this).attr('pestalias');
      subfarm = $(this).attr('subfarm');

      delfunction(lid,subfarm,pestalias);

    });
    $(document).on("click", ".btn-edit", function() {
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

      path = `../../picture/activities/pest/`;
      setImgEdit(lid, path);

    });

    $('#date').change(function(){
      date = $('#date').val();
      // console.log(date);
      idSetHtml = "#farm";
      selectFarm(idSetHtml,date);
      $('#pesttype').val('');
      $('#pest').val('');

    });

    $('#farm').change(function(){
      dim_farm = $(this).val();
      date = $('#date').val();
      idSetHtml = "#subfarm";
      selectSubfarm(idSetHtml,dim_farm,date);
      // function selectSubfarm(idSetHtml,id,modify_num){

    });

    $('#pesttype').change(function() {
      ptid = $('#pesttype').val();
      idSetHtml = '#pest';
      date = $('#date').val();
      selectPest(idSetHtml,ptid,-1,date);
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

    $(document).on("click", ".btn-pest", function() {
        $('#pest_data').modal();
        DATA_DB = [];
        arr = [];

        pid = $(this).attr('pest');
        dim_pest = $(this).attr('dimpest');
        ptid = $(this).attr('pesttype');
      $.post("manage.php", {request: "selectPestByPID",dim_pest: dim_pest}, function(result){
        // console.log(result)

        DATA_DB = JSON.parse(result);
        // console.log(DATA_DB)
        path = "../../icon/pest/" + DATA_DB[1]['dbpestLID'] + "/" + DATA_DB[1]['FileName'];
        $('#data_icon').attr('src',path);
        $('#data_name').html('ชื่อ : '+DATA_DB[1]["Alias"]);
        $('#data_alias').html('ชื่อทางการ : '+DATA_DB[1]["Name"]);

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
        });
        showmore();

      });

    });

});
// pagination
function getDataSetTable(){
  $.post("manage.php", {request: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,fyear: fyear,ftype: ftype,start: start,limit: limit,latitude: latitude,longitude: longitude}, function(result){
    DATA = JSON.parse(result);
    // console.log(result);
    setTableBody(DATA);
    // clickMarkOnMap(DATA[0]["numrow"]);
  });
}
// pagination
function setTableBody(DATA){
      html = ``;
                        for (i = 1; i <= DATA[0]['numrow']; i++) {
                            if (DATA[i]['check_show'] == 1) {
                              html += `<tr class="la${DATA[i]["Latitude"]} long${DATA[i]["Longitude"]} table-set"
                            test="test${i}}">
                            <td>${DATA[i]["OwnerName"]}</td>
                            <td>${DATA[i]['Namefarm']}</td>`;
                            if (DATA[i]["EndT_sub"] != null) {
                              html += `<td>${DATA[i]["Namesubfarm"]}</td>`;
                            } else {
                              html += `<td><a
                                    href="./../OilPalmAreaList/OilPalmAreaListSubDetail.php?FSID=${DATA[i]["FSID"]}&FMID=${DATA[i]["FMID"]}">
                                    ${DATA[i]["Namesubfarm"]}</a></td>`;
                            }
                            html += `<td class="text-right">${DATA[i]['AreaRai']} ไร่
                                ${DATA[i]['AreaNgan']} งาน</td>
                            <td class="text-right">${DATA[i]['NumTree']} ต้น</td>
                            <td class="text-center">${DATA[i]['TypeTH']}</td>
                            <td class="text-center">${DATA[i]['Date']}</td>

                            <td style="text-align:center;">
                                <button type="button" id='edit${i}'
                                    class="btn btn-warning btn-sm btn-edit tt set-button" data-toggle="tooltip"
                                    title="รายละเอียด" farm="${DATA[i]['Namefarm']}"
                                    subfarm="${DATA[i]['Namesubfarm']}" date="${DATA[i]['Date']}"
                                    o_farm="${DATA[i]['NameFarm_old']}"
                                    modify="${DATA[i]['Modify']}"
                                    o_subfarm="${DATA[i]['NamesubFarm_old']}"
                                    pesttype_name="${DATA[i]['TypeTH']}"
                                    pesttype="${DATA[i]['dbpestTID']}"
                                    pestalias="${DATA[i]['PestAlias']}"
                                    pest="${DATA[i]['DIMpestID']}" note="${DATA[i]['Note']}"
                                    lid="${DATA[i]['ID']}">
                                    <i class="far fa-file"></i></button>
                                <button type="button" class="btn btn-success btn-sm btn-pest tt set-button"
                                    dimpest="${DATA[i]['dim_pest']}"
                                    pest="${DATA[i]['dbpestLID']}"
                                    pesttype="${DATA[i]['dbpestTID']}" data-toggle="tooltip"
                                    title="ลักษณะศัตรูพืช"><i class="fas fa-bars"></i></button>
                                <button type="button" class="btn btn-info btn-sm btn-photo tt set-button"
                                    lid="${DATA[i]['ID']}" data-toggle="tooltip" title="รูปภาพศัตรูพืช"><i
                                        class="far fa-images"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-note tt set-button"
                                    note="${DATA[i]['Note']}" data-toggle="tooltip"
                                    title="ข้อมูลสำคัญของศัตรูพืช"><i class="far fa-sticky-note"></i></button>
                                <button type="button" class="btn btn-danger btn-sm btn-delete tt set-button"
                                    lid="${DATA[i]['ID']}"
                                    subfarm="${DATA[i]['Namesubfarm']}"
                                    pestalias='${DATA[i]['PestAlias']}' data-toggle="tooltip"
                                    title="ลบ"><i class="far fa-trash-alt"></i></button>
                            </td>
                            <label class="click-map" hidden id="${i}"
                                namesubfarm="${DATA[i]["Namesubfarm"]}"
                                dim_subfarm=" ${DATA[i]["dim_subfarm"]}"
                                la="${DATA[i]["Latitude"]}" long="${DATA[i]["Longitude"]}"
                                check="${DATA[i]["check_show"]}"
                                dist="${DATA[i]["Distrinct"]}" pro="${DATA[i]["Province"]}"
                                owner="${DATA[i]["OwnerName"]}"></label>
                        </tr>`;
                            }
                        }

          $("#body").html(html);
}
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

function showmore(){
  // Configure/customize these variables.
  // console.log('showmore');

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
          // console.log('set more text');
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
function selectFarm(idSetHtml,date){
  $.post("manage.php", {request: "selectFarm",date:date}, function(result){
    DATA_DB = JSON.parse(result);
    // console.log('set farm id = '+set)
    // console.log(DATA_DB);
    html = "<option value=''>เลือกสวน</option>";
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option value='+DATA_DB[i]['DIMfarmID']+'>'+DATA_DB[i]['Name']+'</option>';
    }
    $(idSetHtml).html(html);    
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
function selectPest(idSetHtml,type_id,set,date){
  $.post("manage.php", {request: "selectPest",type_id: type_id,date:date}, function(result){
    DATA_DB = JSON.parse(result);
    if(set == -1){
      html = "<option selected value=''>เลือกศัตรูพืช</option>";
    }
    for(i = 1 ; i <= DATA_DB[0]['numrow'] ; i++){
      html +='<option value='+DATA_DB[i]['DIMpestID']+'>'+DATA_DB[i]['Alias']+'</option>';
      if(set == DATA_DB[i]['DIMpestID']){
        html = DATA_DB[i]['DIMpestID'];
      }
    }

    $(idSetHtml).html(html);

  });
}
function initMap() {
    var locations = [];
    var center = [0, 0];
    // pagination
    fade = false;
    $.post("manage.php", {request: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,fyear: fyear,ftype: ftype,start: 0,limit: 0}, function(result){
      DATA = JSON.parse(result);
      getDataSetTable();
      $(".loader-container").fadeOut(500);
      // console.log(DATA);
      // console.log("init map numrow data = "+DATA[0]["numrow"]);
      size = DATA[0]['numrow'];

      for(i = 1 ; i <= size ; i++){
        check = parseFloat(DATA[i]['check_show']);
          namesubfarm = DATA[i]['Namesubfarm'];
          // console.log('map i = '+i);
          la = DATA[i]["Latitude"];
          long = DATA[i]["Longitude"];
          laFloat = parseFloat(DATA[i]["Latitude"]);
          longFloat = parseFloat(DATA[i]["Longitude"]);
          dist = DATA[i]["Distrinct"];
          pro = DATA[i]["Province"];
          owner = DATA[i]["OwnerName"];
          center[0] += laFloat;
          center[1] += longFloat;
          data = [namesubfarm,la,long,dist,pro,owner];
          locations.push(data);
          // console.log("la = "+la);
          // console.log("laFloat = "+laFloat);
          // console.log("longFloat = "+longFloat);
      }
      center[0] = center[0] / size;
      center[1] = center[1] / size;

      if (size == 0) {
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
                  // $('.la'+locations[i][1]+'long'+locations[i][2]).each(function(){
                    // console.log("this");
                    // console.log($(this).attr("test"));
                  // });

                  if (i != -1) {
                    // for (j = 0; j < size-1; j++) {

                    //   lati1 = locations[i][1].replace('.','-');
                    //   longi1 = locations[i][2].replace('.','-');
                    //   lati2 = locations[j][1].replace('.','-');
                    //   longi2 = locations[j][2].replace('.','-');
                    //   if (lati1 == lati2 && longi1 == longi2) {
                    //       $('.la' + lati1+'long'+longi1).show();
                    //   } else {
                    //       $('.la' + lati2+'long'+longi2).hide();
                    //   }
                    // }

                    // pagination
                    latitude =  locations[i][1];
                    longitude = locations[i][2];
                    start = 0;
                    $.post("manage.php", {request: "pagination",idformal: idformal,fullname: fullname,fpro: fpro,fdist: fdist,fyear: fyear,ftype: ftype,start: 0,limit: 0,latitude: latitude,longitude: longitude}, function(result){
                      DATA = JSON.parse(result);
                      // console.log(result);
                      // console.log(DATA[0]["numrow"]);
                      $("#size").attr("size",DATA[0]["numrow"]);
                      getDataSetTable();
                      clickMarkOnMap();
                    });
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
  });


}

function delfunction(_id, _subfarm, _name) {
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

