var dataFarm;
   // pagination
   idformal = $("#data_search").attr("idformal");
   fullname = $("#data_search").attr("fullname");
   fpro = $("#data_search").attr("fpro");
   fdist = $("#data_search").attr("fdist");
   //end pagination
$(document).ready(function() {
    updateInfoFarm();
    $('.tt').tooltip();

    $(document).on("click", "#add", function() {
        $("#addModal").modal();

    });

    $(document).on("change", "#s_province", function() {

        var e = document.getElementById("s_province");
        var select_id = e.options[e.selectedIndex].value;
        // console.log(select_id);
        data_show(select_id, "s_distrinct", '');


    });

    $(document).on("change", "#province", function() {

        var e = document.getElementById("province");
        var select_id = e.options[e.selectedIndex].value;
        data_show(select_id, "distrinct", '');
        $("#subdistrinct").html('<option selected value=0 disabled="">เลือกตำบล</option>');


    });

    $(document).on("change", "#distrinct", function() {

        var e = document.getElementById("distrinct");
        var select_id = e.options[e.selectedIndex].value;
        data_show(select_id, "subdistrinct", '');


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
    // -------------------------------------------------------------
    // create by โสภณ โตใหญ่
    function updateInfoFarm() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "data.php",
            data: {
                result: 'updateInfoFarm'

            },
            async: false,
            success: function(result) {
                dataFarm = result;

            }
        });
    }
    // หน้า แรก ของสวนปาล์ม ตรวจสอบการเพิ่มสวนpalm

    $(document).on("click", ".insertFarm", function() {
        var namefarm = $("input[name='namefarm']");
        var aliasfarm = $("input[name='aliasfarm']");
        var addfarm = $("input[name='addfarm']");
        var province = $("select[name='province']");
        var distrinct = $("select[name='distrinct']");
        var subdistrinct = $("select[name='subdistrinct']");
        var farmer = $("select[name='farmer']");
        let dataNull = [namefarm, aliasfarm, addfarm];
        let dataSelectNull = [province, distrinct, subdistrinct, farmer];
        //ตรวจสอบข้อมูลว่าเป็นช่องว่างหรือไม่
        if (!checkNull(dataNull)) return;
        if (!checkSelectNull(dataSelectNull)) return;
        //ตรวจสอบว่ามีชื่อซ้ำกันหรือไม่
        if (!checkSameName(namefarm, -1)) return;
        if (!checkSameAlias(aliasfarm, -1)) return;
    });

    function checkNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == '') {
                selecter[i][0].setCustomValidity('กรุณากรอกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkSelectNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == null) {
                selecter[i][0].setCustomValidity('กรุณาเลือกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkSameName(name, id) { // check same name

        for (i in dataFarm) {
            // console.log(dataFarm[i].Name);
            if (name.val().trim().replace(/\s\s+/g, ' ') == dataFarm[i].Name && dataFarm[i].FMID != id) {
                name[0].setCustomValidity('ชื่อนี้ถูกใช้งานแล้ว');
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }

    function checkSameAlias(name, id) { // check same Alias

        for (i in dataFarm) {
            // console.log(dataFarm[i].Alias);
            if (name.val().trim().replace(/\s\s+/g, ' ') == dataFarm[i].Alias && dataFarm[i].FMID != id) {
                name[0].setCustomValidity('ชื่อนี้ถูกใช้งานแล้ว')
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }

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
function initMap() {
    var locations = [];
    var center = [0, 0];
    size = $('#size').attr('size');
    if (size == 1) {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: new google.maps.LatLng(10.667028, 99.201250),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    } else {
        click_map = $('.click-map').html();
        for (i = 1; i < size; i++) {
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
        center[0] = center[0] / (size - 1);
        center[1] = center[1] / (size - 1);

        // console.log(center);

        // console.log(locations);

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

function delfunction(_username, _uid) {
    swal({
            title: "คุณต้องการลบ",
            text: `สวน ${_username} หรือไม่ ?`,
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
                        delete_1(_uid)
                    } else {}
                });
            } else {}
        });
}

function delete_1(_fid) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            window.location = './OilPalmAreaList.php';
        }
    };
    xhttp.open("POST", "manage.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`fid=${_fid}&delete=delete`);

}