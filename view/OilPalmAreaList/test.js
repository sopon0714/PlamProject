var dataFarm;
$(document).ready(function() {
    $('.tt').tooltip();
    updateInfoFarm();
    $('#btn_edit_detail1').click(function() {
        $("#editDetailModal").modal('show');
    });
    $('#edit_photo').click(function() {
        $("#photoModal").modal('show');
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

    $('.editFarm').click(function() {

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
    })

    $(document).on('click', '.insertSubmit', function(e) { // insert submit


        let icon = $("#pic-logo");

        if (!checkNullPic(icon)) return;
    })

    function checkNullPic(icon) {
        if ($('#img-insert').attr('src') == "https://via.placeholder.com/200x200.png") {

            return false;

        }

        return true;

    }

});