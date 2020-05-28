var dataSubFarm;
var FSID = $('#FSIDmap').val();
var maxyear = $('#maxyear').attr("maxyear");
$(document).ready(function() {
    $("#btn_remove_mark").click(function() {
        for (let i = 0; i < mapedit.markers.length; i++) {
            mapedit.markers[i].setMap(null);
        }
        numCoor = 0;
        mapedit.markers = [];
        $('#erormap').html(" ")
    });
    $("#btn_submit_editMap").click(function() {
        if (mapedit.markers.length <= 2) {
            $('#erormap').html("กรุณามาค์จุดอย่างน้อย 3 ตำแหน่งรอบพื้นที่แปลง")
        } else {
            var lat = [];
            var lng = [];
            var id = $('#FSIDmap').val();
            var idf = $('#FMIDmap').val();
            for (i = 0; i < mapedit.markers.length; i++) {
                lat.push(mapedit.markers[i].position.lat())
                lng.push(mapedit.markers[i].position.lng())
            }
            console.log(JSON.stringify(lat));
            $.ajax({
                type: "POST",
                url: "./manage.php",
                data: {
                    resultlat: JSON.stringify(lat),
                    resultlng: JSON.stringify(lng),
                    FSID: id,
                    action: "editlocationMap"

                },
                async: false,
                success: function(result) {
                    window.location = "./OilPalmAreaListSubDetail.php?FSID=" + id + "&FMID=" + idf;
                }
            });
        }
    });
    $("#map_area_edit").click(function() {
        $('#erormap').html("")
    });
    updateInfoSubFarm();
    $("#plantingmodal").click(function() {
        $("#addplant").modal('show');
    });

    $('#edit_photo').click(function() {
        $("#photoModal").modal('show');
    });
    $('#btn_edit_subfarm').click(function() {
        $("#editSubFarmModal").modal('show');
    });
    $(document).on('change', '.item-img', function() {
        readFile(this);
    });
    $("#btn_edit_map").click(function() {
        $("#editMapModal").modal('show');
    });
    $('#province').change(function() {
        var e = document.getElementById("province");
        var select_id = e.options[e.selectedIndex].value;
        data_show(select_id, "distrinct", '');
        $("#subdistrinct").html('<option selected value=0 disabled="">เลือกตำบล</option>');


    });
    $('#distrinct').change(function() {

        var e = document.getElementById("distrinct");
        var select_id = e.options[e.selectedIndex].value;
        data_show(select_id, "subdistrinct", '');

    });

    function updateInfoSubFarm() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "data.php",
            data: {
                result: 'updateInfoSubFarm'

            },
            async: false,
            success: function(result) {
                dataSubFarm = result;

            }
        });
    }

    function data_show(select_id, result, point_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(result).innerHTML = xhttp.responseText;

            };
        }
        xhttp.open("POST", "data.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`select_id=${select_id}&result=${result}&point_id=${point_id}`);
    }

    ////////////////////////////////////////////
    $('.btn-edit-subFarm').click(function() {

        var fsid = $("#editSubFarmModal input[name='fsid']");
        var nameSubfarm = $("#editSubFarmModal input[name='nameSubfarm']");
        var initialsSubfarm = $("#editSubFarmModal input[name='initialsSubfarm']");
        var AreaRai = $("#editSubFarmModal input[name='AreaRai']");
        var AreaNgan = $("#editSubFarmModal input[name='AreaNgan']");
        var AreaWa = $("#editSubFarmModal input[name='AreaWa']");
        var addfarmSF = $("#editSubFarmModal input[name='addfarmSF']");
        var provinceSF = $("#editSubFarmModal select[name='province']");
        var distrinctSF = $("#editSubFarmModal select[name='distrinct']");
        var subdistrinctSF = $("#editSubFarmModal select[name='subdistrinct']");
        let dataNull = [nameSubfarm, initialsSubfarm];
        let dataNull2 = [addfarmSF];
        let dataNumNull = [AreaRai, AreaNgan, AreaWa];
        let dataSelectNull = [provinceSF, distrinctSF, subdistrinctSF];
        //ตรวจสอบข้อมูลว่าเป็นช่องว่างหรือไม่
        if (!checkNull(dataNull)) return;

        if (!checkNumNull(dataNumNull)) return;

        if (!checkNull(dataNull2)) return;

        if (!checkSelectNull(dataSelectNull)) return;


        //ตรวจสอบว่ามีชื่อซ้ำกันหรือไม่
        if (!checkSameNameSF(nameSubfarm, fsid.val.val())) return;
        if (!checkSameAliasSF(initialsSubfarm, fsid.val())) return;

    });
    $('.btn-submit-plantting').click(function() {

        var TypePlantting = $("#addplant select[name='TypePlantting']");
        var dateActive = $("#addplant input[name='dateActive']");
        var PalmTree = $("#addplant input[name='PalmTree']");
        let dataSelectNull = [TypePlantting];
        var varDate = new Date(dateActive.val()); //dd-mm-YYYY
        var today = new Date();
        // console.log(varDate);
        //ตรวจสอบข้อมูลว่าเป็นช่องว่างหรือไม่
        if (!checkSelectNull(dataSelectNull)) return;
        if (dateActive.val() == "") {
            dateActive[0].setCustomValidity('กรุณาเลิอกวันที่')
            return;
        } else {
            dateActive[0].setCustomValidity('');
        }
        if (PalmTree.val() == "0") {
            PalmTree[0].setCustomValidity('จำนวนต้นไม้ห้ามเป็น 0 ต้น')
            return;
        } else {
            PalmTree[0].setCustomValidity('');
        }

        if (varDate > today) {
            dateActive[0].setCustomValidity('ไม่สามารถเลือกวันที่นี้ได้')
            return;
        } else {
            dateActive[0].setCustomValidity('');
        }


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

    function checkNumNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() != '0') {

                selecter[0][0].setCustomValidity('');

                return true;
            }
        }
        selecter[0][0].setCustomValidity('ขนาดพื้นที่ห้ามเป็น 0 ไร่ 0 งาน 0 วา');
        return false;
    }

    function checkSelectNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == null || selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('กรุณาเลือกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function checkSameNameSF(name, id) { // check same name
        for (i in dataSubFarm) {
            if (name.val().trim().replace(/\s\s+/g, ' ') == dataSubFarm[i].Name && dataSubFarm[i].FSID != id) {
                name[0].setCustomValidity('ชื่อนี้ถูกใช้งานแล้ว');
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }

    function checkSameAliasSF(name, id) { // check same Alias

        for (i in dataSubFarm) {
            if (name.val().trim().replace(/\s\s+/g, ' ') == dataSubFarm[i].Alias && dataSubFarm[i].FSID != id) {
                // alert(name.val().trim().replace(/\s\s+/g, ' ') + "->" + dataSubFarm[i].Alias + "/" + dataSubFarm[i].FSID + "->" + id);
                name[0].setCustomValidity('ชื่อนี้ถูกใช้งานแล้ว')
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }


    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cropImagePop').addClass('show');
                rawImg = e.target.result;
                loadIm();
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            //    swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $('.divCrop').hide()
    $('.buttonCrop').hide()

    function loadIm() {
        $('.divName').hide()
        $('.divHolder').hide()
        $('.divCrop').show()
        $('.buttonCrop').show()
        $('.buttonSubmit').hide()
        $uploadCrop = $('#upload-demo').croppie({
            viewport: {
                width: 200,
                height: 200,
                type: 'circle'
            },
            enforceBoundary: false,
            enableExif: true
        });
        $uploadCrop.croppie('bind', {
            url: rawImg
        }).then(function() {});
        $('.item-img').val('');
    }

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
        $('#img-insert').attr('src', "https://via.placeholder.com/200x200.png");
    });
    $(document).on('click', '.insertSubmit', function(e) { // insert submit


        if ($('#img-insert').attr('src') == 'https://via.placeholder.com/200x200.png') {
            $("#pic-logo").attr("required", "");
            $("#pic-logo")[0].setCustomValidity('กรุณาเพิ่มรูป');
        } else {
            $("#pic-logo").removeAttr("required");
            $("#pic-logo")[0].setCustomValidity('');
        }
    })
    $('#photoModal').on('hidden.bs.modal', function() {
        $('#upload-demo').croppie('destroy')
        $('.divName').show()
        $('.divHolder').show()
        $('.divCrop').hide()
        $('.buttonCrop').hide()
        $('.buttonSubmit').show()
        $('#img-insert').attr('src', "https://via.placeholder.com/200x200.png");
    });
    // ส่วนของกราฟ////////////////////////////////////////////////////////////////////////////////////////////////
    load_year(maxyear, FSID);
    load_month(maxyear, FSID);
    $("#year").change(function() {

        var year = $("#year").val();

        if (year != '') {
            load_year(year, FSID);
            load_month(year, FSID);
        }
    });

    function load_year(year, fsid) {
        $.ajax({
            url: "data.php",
            method: "POST",
            data: {
                year: year,
                fsid: fsid,
                result: "getYearProdect"
            },
            dataType: "JSON",
            success: function(data) {
                chartyear(data);
            }
        });

    }

    function load_month(year, fsid) {
        $.ajax({
            url: "data.php",
            method: "POST",
            data: {
                year: year,
                fsid: fsid,
                result: "getMProdect"
            },
            dataType: "JSON",
            success: function(data) {
                chartmonth(data);
            }
        });
    }
    // ผลผลิตต่อเดือน/ ///////////////////////////////////////////////////////
    function chartmonth(chart_data) {
        $('.PDM').empty()
        $('.PDM').html(` <canvas id="productMonth" style="height:250px;"></canvas>`)
        console.table(chart_data);
        let data2 = []
        var i, j = 0;

        for (i = 0; i < 12; i++) {
            if (chart_data[j] != null) {
                if (i == chart_data[j].Month - 1) {
                    data2.push(parseFloat(chart_data[j].Weight).toFixed(4))
                    j++;
                } else {
                    data2.push(0)
                }
            } else {
                data2.push(0)
            }
        }


        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
                labels: {
                    boxWidth: 80,
                    fontColor: 'black'
                }
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'ผลผลิต (ก.ก.)'
                    },
                    gridLines: {
                        display: true
                    },
                    ticks: {
                        min: 0

                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'รายเดือน'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        min: 0

                    }
                }],
            }
        };
        var speedData = {
            labels: ["ม.ค.", "ก.พ.", "มี.ค.",
                "เม.ย", "พ.ค.", "มิ.ย.",
                "ก.ค.", "ส.ค.", "ก.ย.",
                "ต.ค.", "พ.ย.", "ธ.ค."
            ],
            datasets: [{
                data: data2,
                backgroundColor: '#05acd3'
            }]
        };

        var ctx = $("#productMonth");
        var plantPie = new Chart(ctx, {
            type: 'bar',
            data: speedData,
            options: chartOptions
        });


    }
    // ผลผลิตต่ปี///////////////////////////////////////////////////
    function chartyear(chart_data) {
        $('.PDY').empty();
        $('.PDY').html(` <canvas id="productYear" style="height:250px;"></canvas>`);
        let labelData = [];
        let data2 = [];
        var year = $("#year").val();
        var thisYear;
        var j = 0;
        for (i = 0; i < 3; i++) {
            j = 0;
            thisYear = year - 2 + i;
            for (j = 0; j < chart_data.length; j++) {
                if (chart_data[j].Year2 == thisYear) {
                    labelData.push(chart_data[j].Year2);
                    data2.push(parseFloat(chart_data[j].Weight).toFixed(4));
                    break;
                }
            }
            if (j == chart_data.length) {
                if (thisYear < year) {
                    continue;
                }
                labelData.push(thisYear);
                data2.push(0);
            }
        }
        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
                labels: {
                    boxWidth: 60,
                    fontColor: 'black'
                }
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'ผลผลิต (ก.ก.)'
                    },
                    gridLines: {
                        display: true
                    },
                    ticks: {
                        min: 0

                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'รายปี'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        min: 0

                    }
                }],
            }
        };
        var speedData = {
            labels: labelData,
            datasets: [{
                data: data2,
                backgroundColor: '#00ce68'
            }]
        };

        var ctx = $("#productYear");
        var plantPie = new Chart(ctx, {
            type: 'bar',
            data: speedData,
            options: chartOptions
        });
    }




});