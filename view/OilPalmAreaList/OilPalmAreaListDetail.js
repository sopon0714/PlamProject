var dataFarm;
var dataSubFarm;
$(document).ready(function() {
    $('.tt').tooltip();
    updateInfoFarm();
    updateInfoSubFarm();

    $(document).on("click", "#btn_edit_detail1", function() {
        $("#editDetailModal").modal('show');
    });

    $(document).on("click", "#edit_photo", function() {
        $("#photoModal").modal('show');
    });

    $(document).on("click", "#btn_add_subfarm", function() {
        $("#addSubFarmModal").modal('show');
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
    ////////////////////////////////////////////

    $(document).on("change", "#provinceSF", function() {

        var e = document.getElementById("provinceSF");
        var select_id = e.options[e.selectedIndex].value;
        data_show(select_id, "distrinctSF", '');
        $("#subdistrinctSF").html('<option selected value=0 disabled="">เลือกตำบล</option>');


    });

    $(document).on("change", "#distrinctSF", function() {

        var e = document.getElementById("distrinctSF");
        var select_id = e.options[e.selectedIndex].value;
        data_show(select_id, "subdistrinctSF", '');

    });

    $(document).on("click", ".btn-add-subFarm", function() {

        var nameSubfarm = $("#addSubFarmModal input[name='nameSubfarm']");
        var initialsSubfarm = $("#addSubFarmModal input[name='initialsSubfarm']");
        var AreaRai = $("#addSubFarmModal input[name='AreaRai']");
        var AreaNgan = $("#addSubFarmModal input[name='AreaNgan']");
        var AreaWa = $("#addSubFarmModal input[name='AreaWa']");
        var addfarmSF = $("#addSubFarmModal input[name='addfarmSF']");
        var provinceSF = $("#addSubFarmModal select[name='provinceSF']");
        var distrinctSF = $("#addSubFarmModal select[name='distrinctSF']");
        var subdistrinctSF = $("#addSubFarmModal select[name='subdistrinctSF']");

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
        if (!checkSameNameSF(nameSubfarm, -1)) return;
        if (!checkSameAliasSF(initialsSubfarm, -1)) return;
    });


    $(document).on("click", ".editFarm", function() {

        var IDFarm = $("#form-edit-farm input[name='IDFarm']");
        var namefarm = $("#form-edit-farm input[name='namefarm']");
        var aliasfarm = $("#form-edit-farm input[name='aliasfarm']");
        var addfarm = $("#form-edit-farm input[name='addfarm']");
        var province = $("#form-edit-farm select[name='province']");
        var distrinct = $("#form-edit-farm select[name='distrinct']");
        var subdistrinct = $("#form-edit-farm select[name='subdistrinct']");
        var farmer = $("#form-edit-farm select[name='farmer']");
        let dataNull = [namefarm, aliasfarm, addfarm];
        let dataSelectNull = [province, distrinct, subdistrinct, farmer];
        //ตรวจสอบข้อมูลว่าเป็นช่องว่างหรือไม่
        if (!checkNull(dataNull)) return;
        if (!checkSelectNull(dataSelectNull)) return;
        //ตรวจสอบว่ามีชื่อซ้ำกันหรือไม่
        if (!checkSameName(namefarm, IDFarm.val())) return;
        if (!checkSameAlias(aliasfarm, IDFarm.val())) return;
    });



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
                console.log(this.responseText);
                // console.log(result);
                document.getElementById(result).innerHTML = xhttp.responseText;

            };
        }
        xhttp.open("POST", "data.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`select_id=${select_id}&result=${result}&point_id=${point_id}`);
    }

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

    function checkSameNameSF(name, id) { // check same name

        for (i in dataSubFarm) {
            console.log(dataSubFarm[i].Name);
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
            console.log(dataSubFarm[i].Alias);
            if (name.val().trim().replace(/\s\s+/g, ' ') == dataSubFarm[i].Alias && dataSubFarm[i].FSID != id) {
                name[0].setCustomValidity('ชื่อนี้ถูกใช้งานแล้ว')
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }

    // --------------------------------------- crop photo ------------------------------------------------------

    $(document).on('change', '.item-img', function() {
        readFile(this);
    });

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
        // console.log('sss');
        // console.log($("#img-insert").attr('src'));

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
        })
        ////////////////////////////////////////////////////////////////////////////////////////////


    $(document).on("click", "#btn_edit_map", function() {
        $("#editMapModal").modal('show');
    });

    $(document).on("click", "#btn_submit_editMap", function() {
        var FMIDmap = $('#FMIDmap').val();
        var la = $('#la').val();
        var long = $('#long').val();
        if (la == 0) {
            $('#erormap').html("***กรุณามาร์คตำแหน่งจุดสวนปาล์มบนพื้นที่***");
        } else {
            $('#editMapModal').modal('hide');
            $('#erormap').html(" ");
            $.ajax({
                type: "POST",
                url: "./manage.php",
                data: {
                    lat: la,
                    lng: long,
                    fmid: FMIDmap,
                    action: "editLatLngMapFarm"
                },
                async: false,
                success: function(result) {
                    swal({

                        title: "ดำเนินการแก้",
                        text: "ตำแหน่งสวนปาล์มเรียบร้อย",
                        type: "success",
                        showCancelButton: false,
                        showConfirmButton: false

                    });
                    setTimeout(function() {

                        window.location = './OilPalmAreaListDetail.php?fmid=' + FMIDmap;
                    }, 2000);

                }
            });
        }
    });

    $(document).on("click", ".btnDel", function() {
        var username = $(this).attr('NameSubfarm');
        var fsid = $(this).attr('fsid');
        var FMIDmap = $('#FMIDmap').val();

        swal({
                title: "คุณต้องการลบ",
                text: `แปลง ${username} หรือไม่ ?`,
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
                            delete_1(fsid, FMIDmap)
                        } else {}
                    });
                } else {}
            });


    });

    function delete_1(fsid, FMIDmap) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                window.location = './OilPalmAreaListDetail.php?fmid=' + FMIDmap;
            }
        };
        xhttp.open("POST", "manage.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`fsid=${fsid}&action=deleteSubFarm`);

    }



});