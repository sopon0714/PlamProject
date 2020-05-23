var mapdetail, mapcolor;


$("#card_height").css('height', $("#for_card").css('height'));

$("#btn_edit_detail1").click(function() {
    $("#editDetailModal").modal('');
});
$("#edit_photo").click(function() {
    console.log("photo");
    $("#photoModal").modal();
    var uid = $(this).attr('uid');
    $('#p_uid').val(uid);

});

$("#btn_edit_map").click(function() {
    $("body").append(editMapModalFun(mapdetail, mapcolor));
    $("#editMapModal").modal('show');

    var startLatLng = new google.maps.LatLng(13.736717, 100.523186);
    console.log(mapcolor);
    console.log(mapdetail.markers[0].getPosition().lng());

    var startLatLng = new google.maps.LatLng(<?= $latlong[1]['Latitude'] ?>, <?= $latlong[1]['Longitude'] ?>);

    var mapedit = new google.maps.Map(document.getElementById('map_area_edit'), {
        // center: { lat: 13.7244416, lng: 100.3529157 },
        center: startLatLng,
        zoom: 17,
        mapTypeId: 'satellite'
    });

    mapedit.markers = [];
    for (let i = 0; i < mapdetail.markers.length; i++) {
        let marker;
        if (i == 0) {

        } else {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(mapdetail.markers[i].getPosition().lat(), mapdetail.markers[i].getPosition().lng()),
                map: mapedit,
                title: "test",
                draggable: true,
            });
        }
        mapedit.markers.push(marker);
    }

    var latlng = [];
    var sumcoorlat = 0;
    var sumcoorlng = 0;
    var sumlat = 0;
    var sumlng = 0;
    var x = 0;
    google.maps.event.addListener(mapedit, 'click', function(event) {
        latlng.push(event.latLng.lat(), event.latLng.lng());

        placeMarker(event.latLng);
        sumlat = sumlat + event.latLng.lat();
        sumlng = sumlng + event.latLng.lng();
        x = x + 1;
        sumcoorlat = sumlat / x;
        sumcoorlng = sumlng / x;
        //console.table(sumcoorlat, sumcoorlng);
    });

    function placeMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: mapedit,
            draggable: true,
        });
        mapedit.markers.push(marker);

    }

    $("#btn_remove_mark").click(function() {
        for (let i = 0; i < mapedit.markers.length; i++) {
            if (i != 0) {
                mapedit.markers[i].setMap(null);
                for (let i = 0; i < latlng.length; i++) {
                    latlng[i] = 0;
                }
                sumlat = 0;
                sumlng = 0;
                x = 0;
            }
        }
    });

});

$("#btn_add_subgarden1").click(function() {
    $("body").append(addSubGardenModal);
    $("#addSubGardenModal").modal('show');
});

$("#btn_info").click(function() {
    console.log("testefe");
});
$(document).on('click', '.insertSubmit', function(e) { // insert submit
    console.log('sss');

    let icon = $("#pic-logo");

    if (!checkNull(icon)) return;

    // let form = new FormData($('#formPhoto')[0]);
    // form.append('imagebase64', $('#img-insert').attr('src'))
    // insertPh(form); // insert data
})
$(document).on('click', '#cropImageBtn', function(ev) {

    $('#upload-demo').croppie('result', {
            type: 'canvas',
            size: 'viewport'
        })
        .then(function(r) {
            $('.buttonSubmit').show()
            $('.divName').show()
            $('.buttonCrop').hide()
            $('.divHolder').show()
            $('#img-insert').attr('src', r);
            $('#imagebase64').val(r);
            $('.divCrop').hide()
        });
    $('#upload-demo').croppie('destroy')

});
$(document).on('click', '#cancelCrop', function() {
    $('#upload-demo').croppie('destroy')
    $('.divName').show()
    $('.divHolder').show()
    $('.divCrop').hide()
    $('.buttonCrop').hide()
    $('.buttonSubmit').show()
        // $('#img-insert').attr('src', "https://via.placeholder.com/200x200.png");
})


//  <!-- ส่วนที่ต้องเอาไปแทนในของอิง -->
document.getElementById("province1").addEventListener("load", loadProvince1());

let data;

// โหลดจังหวัด
function loadProvince1() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.responseText);
            let text = "";
            for (i in data) {
                text += ` <option value='${data[i].AD1ID}'>${data[i].Province}</option> `

            }
            $("#province1").append(text);

        }
    };
    xhttp.open("GET", "./loadProvince.php", true);
    xhttp.send();
}
// โหลดอำเภอ

$("#province1").on('change', function() {
    $("#amp1").empty();
    let x = document.getElementById("province1").value;
    let y = document.getElementById("province1");
    if (y.length == 78)
        y.remove(0);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.responseText);
            let text = "";
            for (i in data) {
                text += ` <option value ='${data[i].AD2ID}'>${data[i].Distrinct}</option> `
            }
            $("#amp1").append(text);
        }
    };
    xhttp.open("GET", "./loadDistrinct.php?id=" + x, true);
    xhttp.send();
});
// โหลดตำบล
$("#amp1").on('change', function() {
    $("#subamp").empty();
    let x = document.getElementById("amp1").value;
    let y = document.getElementById("amp1");
    if (y.length == 78)
        y.remove(0);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            data = JSON.parse(this.responseText);
            let text = "";
            for (i in data) {
                text += ` <option value ='${data[i].AD3ID}'>${data[i].subDistrinct}</option> `
            }

            $("#subamp").append(text);
        }
    };
    xhttp.open("GET", "./loadSubDistrinct.php?id=" + x, true);
    xhttp.send();
});
//  <!-- ส่วนที่ต้องเอาไปแทนในของอิง -->
//<!-- ส่วนแก้ไขสวน -->
document.getElementById("province2").addEventListener("load", loadProvince2());



// โหลดจังหวัด
function loadProvince2() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.responseText);
            let text = "<option value='0'>เลือกจังหวัด</option> ";
            for (i in data) {
                if (data[i].AD1ID == '<?= $DATAFarm[1]['
                    AD1ID '] ?>') {
                    text += ` <option value='${data[i].AD1ID}' selected>${data[i].Province}</option> `;
                } else {
                    text += ` <option value='${data[i].AD1ID}'>${data[i].Province}</option> `;
                }
            }
            $("#province2").append(text);
            loadDistrinct2();


        }
    };
    xhttp.open("GET", "./loadProvince.php", true);
    xhttp.send();
}

function loadDistrinct2() {
    $("#amp2").empty();
    let x = document.getElementById("province2").value;
    let y = document.getElementById("province2");
    if (y.length == 78)
        y.remove(0);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.responseText);
            let text = "<option value='0'>เลือกอำเภอ</option>";
            for (i in data) {
                if (data[i].AD2ID == '<?= $DATAFarm[1]['
                    AD2ID '] ?>') {
                    text += ` <option value ='${data[i].AD2ID}' selected>${data[i].Distrinct}</option> `
                } else {
                    text += ` <option value ='${data[i].AD2ID}'>${data[i].Distrinct}</option> `
                }
            }
            $("#amp2").append(text);
            loadSubDistrinct2();

        }
    };
    xhttp.open("GET", "./loadDistrinct.php?id=" + x, true);
    xhttp.send();
}

function loadSubDistrinct2() {
    $("#subamp2").empty();
    let x = document.getElementById("amp2").value;
    let y = document.getElementById("amp2");
    if (y.length == 78)
        y.remove(0);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            data = JSON.parse(this.responseText);
            let text = "<option value='0'>เลือกตำบล</option>";
            for (i in data) {
                if (data[i].AD3ID == '<?= $DATAFarm[1]['
                    AD3ID '] ?>') {
                    text += ` <option value ='${data[i].AD3ID}' selected>${data[i].subDistrinct}</option> `
                } else {
                    text += ` <option value ='${data[i].AD3ID}'>${data[i].subDistrinct}</option> `
                }
            }

            $("#subamp2").append(text);
        }
    };
    xhttp.open("GET", "./loadSubDistrinct.php?id=" + x, true);
    xhttp.send();
}
// โหลดอำเภอ

$("#province2").on('change', function() {
    $("#amp2").empty();
    let x = document.getElementById("province2").value;
    let y = document.getElementById("province2");
    if (y.length == 78)
        y.remove(0);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            data = JSON.parse(this.responseText);
            let text = "<option value='0'>เลือกอำเภอ</option>";
            for (i in data) {
                text += ` <option value ='${data[i].AD2ID}'>${data[i].Distrinct}</option> `
            }
            $("#amp2").append(text);
        }
    };
    xhttp.open("GET", "./loadDistrinct.php?id=" + x, true);
    xhttp.send();
});
// โหลดตำบล
$("#amp2").on('change', function() {
    $("#subamp2").empty();
    let x = document.getElementById("amp2").value;
    let y = document.getElementById("amp2");
    if (y.length == 78)
        y.remove(0);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            data = JSON.parse(this.responseText);
            let text = "<option value='0'>เลือกตำบล</option>";
            for (i in data) {
                text += ` <option value ='${data[i].AD3ID}'>${data[i].subDistrinct}</option> `
            }

            $("#subamp2").append(text);
        }
    };
    xhttp.open("GET", "./loadSubDistrinct.php?id=" + x, true);
    xhttp.send();
});
//  <!--แก้ไขฟาร์ม -->

$(document).ready(function() {
    let data;
    loadData();
    loadFarmer();

    function loadData() {

        $("#example1").DataTable().destroy();
        let logid = "<?php echo $logid; ?>"
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                data = JSON.parse(this.responseText);
                //console.table(data)
                let i;
                let text = "";
                for (i = 1; i <= data[0].numrow; i++) {
                    text += `<tr>
                            <td class="text-left">${data[i].Name}</td>
                            <td class="text-right">${data[i].AreaRai}</td>
                            <td class="text-right">${data[i].NumTree}</td>
                            <td class="text-right">${data[i].year}ปี ${data[i].month}เดือน ${data[i].day}วัน</td>
                            <td style='text-align:center;'>
                            <a href='OilPalmAreaListSubDetail.php?fmid=<?= $fmid ?>&FSID=${data[i].dbID}&nfarm=${data[i].nFarm}&nsubfarm=${data[i].Name}&farmer=${data[i].Alias}&logid=${data[i].ID}&numtree=${data[i].NumTree}'><button type='button' id='btn_info' class='btn btn-info btn-sm'><i class='fas fa-bars'></i></button></a>
                            <button type='button' id='btn_delete' class='btn btn-danger btn-sm' onclick="delfunction('${data[i].namesub}' , '${data[i].dbID}')"><i class='far fa-trash-alt'></i></button>   
                            </button>
                            </td>
                        </tr> `;

                }

                $("#getData").html(text);
                $('#example1').DataTable();
            }
        };
        xhttp.open("GET", "./getData2.php?id=" + logid, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
    }

    function loadFarmer() {
        $("#farmer2").empty;
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                data = JSON.parse(this.responseText);
                let text = "";
                for (i in data) {
                    if (data[i].UFID == '<?= $DATAFarm[1]['
                        UFID '] ?>') {
                        text += ` <option value="${data[i].UFID}" selected>${data[i].FirstName}</option> `;
                    } else {
                        text += ` <option value="${data[i].UFID}" >${data[i].FirstName}</option> `;
                    }
                }

                $("#farmer2").append(text);
            }
        };
        xhttp.open("GET", "./loadFarmer.php", true);
        xhttp.send();
    }

});

function setAddr() {

}

function initMap() {
    var startLatLng = new google.maps.LatLng(<?= $latlong[1]['Latitude'] ?>, <?= $latlong[1]['Longitude'] ?>);

    mapdetail = new google.maps.Map(document.getElementById('map'), {
        // center: { lat: 13.7244416, lng: 100.3529157 },
        center: startLatLng,
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    mapdetail.markers = [];
    <?php
        for ($i = 1; $i < count($manycoor); $i++) {
            echo "marker = new google.maps.Marker({
                position: new google.maps.LatLng(" . $manycoor[$i]['Latitude'] . "," . $manycoor[$i]['Longitude'] . "),
                map: mapdetail,
                title: \"test\"
                });
                
                //alert(marker.position);

                mapdetail.markers.push(marker);";
        }

        ?>



    // new map ///////////////////////////////////////////////////////////////////

    <?php
        $lat = 0;
        $long = 0;
        for ($i = 1; $i < count($coorsfarm); $i++) {
            $lat = $lat + $coorsfarm[$i]['Latitude'];
            $long = $long + $coorsfarm[$i]['Longitude'];
        } ?>
    var startLatLng = new google.maps.LatLng(<?= $lat / (count($coorsfarm) - 1) ?>, <?= $long / (count($coorsfarm) - 1) ?>);

    mapcolor = new google.maps.Map(document.getElementById('map2'), {
        // center: { lat: 13.7244416, lng: 100.3529157 },
        center: startLatLng,
        zoom: 16,
        mapTypeId: 'satellite'
    });

    mapcolor.markers = [];






    var triangleCoords = [

        <?php
            for ($i = 1; $i <= $numcoor[1]['count']; $i++)
                echo "
            {
                lat:  " . $coorsfarm[$i]['Latitude'] . "   ,
                lng: " . $coorsfarm[$i]['Longitude'] . "
            },";
            ?>


    ];
    <?php
        $k = 5;
        for ($i = 1; $i <= count($subfarm) - 2; $i++) {
            echo "
            var triangleCoords$i = [";

            for ($j = 1; $j <= $numcoor[$i + 1]['count']; $j++) {

                echo "
                    {
                        lat: " . $coorsfarm[$k]['Latitude'] . " ,
                        lng: " . $coorsfarm[$k]['Longitude'] . "
                    },";
                $k++;
            }



            echo "];
            ";
        }
        ?>



    console.log(triangleCoords);

    <?php
        for ($i = 0; $i <= count($subfarm); $i++) {
            if ($i == 0) {
                echo "
        var mapPoly = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        mapPoly.setMap(mapcolor);";
            } else {
                echo "
        var mapPoly$i = new google.maps.Polygon({
            paths: triangleCoords$i,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        mapPoly$i.setMap(mapcolor);";
            }
        }

        ?>



}

function delfunction(_username, _uid) {

    swal({
            title: "คุณต้องการลบ",
            text: `${_username} หรือไม่ ?`,
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
                console.log(_uid);
                swal({

                    title: "ลบข้อมูลสำเร็จ",
                    type: "success",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "ตกลง",
                    closeOnConfirm: false,

                }, function(isConfirm) {
                    if (isConfirm) {
                        deleteSub(_uid)
                    }

                });

            } else {

            }
        });

}

function deleteSub(_fid) {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = './OilPalmAreaListDetail.php';

        }
    };
    xhttp.open("POST", "manage.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`fsid=${_fid}&deleteSub=delete`);

}