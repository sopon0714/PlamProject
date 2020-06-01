var dataFarm;
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
                console.log(this.responseText);
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
            console.log(dataFarm[i].Name);
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
            console.log(dataFarm[i].Alias);
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

        console.log(center);

        console.log(locations);

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
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                swal({

                    title: "ดำเนินการลบ",
                    text: "สวน " + _username + " เรียบร้อย",
                    type: "success",
                    showCancelButton: false,
                    showConfirmButton: false

                });
                delete_1(_uid)
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                swal({
                    title: "ยกเลิกการลบ !!",
                    text: "สวน " + _username,
                    type: "error",
                    showCancelButton: false,
                    showConfirmButton: false
                });
                setTimeout(function() {
                    swal.close();
                }, 2000);
            }
        });

}

function delete_1(_fid) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

        }
    };
    xhttp.open("POST", "manage.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`fid=${_fid}&delete=delete`);

}