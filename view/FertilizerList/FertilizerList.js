var data;
updateInfo();

function updateInfo() {
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "data.php",
        data: {
            result: 'updateInfo'

        },
        async: false,
        success: function(result) {
            data = result;

        }
    });
}
$(document).ready(function() {
    $('.tt').tooltip();
    $(document).on('click', '#addFer', function() {
        $("#insert").modal('show');
    });
    $(document).on("click", ".insertNutr", function() {
        var name = $("input[name='name']");
        var alias = $("input[name='alias']");
        var check = $("input[name='subID']");
        let dataNull = [name, alias];
        let Vol = $(".VolCheck");
        //ตรวจสอบข้อมูลว่าเป็นช่องว่างหรือไม่
        if (!checkNull(dataNull)) return;
        //ตรวจสอบว่ามีชื่อซ้ำกันหรือไม่
        if (!checkSameName(name, -1)) return;
        if (!checkSameAlias(alias, -1)) return;
        let sum = 0;
        for (i = 0; i < Vol.length; i++) {
            sum += Number($(Vol[i]).val());
            if (sum > 100) {
                $(Vol[i])[0].setCustomValidity('ผลรวมมากกว่า 100 %');
                return;
            } else {
                $(Vol[i])[0].setCustomValidity('');
            }

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



    function checkSameName(name, id) { // check same name

        for (i in data) {
            if (name.val().trim().replace(/\s\s+/g, ' ') == data[i].Name && data[i].FMID != id) {
                name[0].setCustomValidity('ชื่อนี้เคยถูกใช้งานแล้ว');
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }

    function checkSameAlias(name, id) { // check same Alias

        for (i in data) {
            console.log(data[i].Alias);
            if (name.val().trim().replace(/\s\s+/g, ' ') == data[i].Alias && data[i].FMID != id) {
                name[0].setCustomValidity('ชื่อนี้เคยถูกใช้งานแล้ว')
                return false;
            } else {
                name[0].setCustomValidity('');
            }
        }
        return true;

    }

    $(document).on('click', '.showDetailNutr', function() {
        var FID = $(this).attr("FID");
        var Type = $(this).attr("TypeNutr");
        $("#titleDetail").html("ธาตุอาหาร" + Type);
        $.ajax({
            type: "POST",
            url: "./data.php",
            data: {
                FID: FID,
                Type: Type,
                result: 'getDataDetail'

            },
            async: false,
            success: function(result) {
                $("#bodyDetailNutr").html(result);
                $("#detailNutrModel").modal('show');
            }
        });

    });
    $(document).on('click', '.btndel', function() {
        var FID = $(this).attr("FID");
        var FName = $(this).attr("FName");
        swal({
                title: "ยืนยันการลบ",
                text: `ต้องการลบปุ๋ย ${FName} หรือไม่ ?`,
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
                        window.location.href = "FertilizerList.php";
                    });
                    $.ajax({
                        type: "POST",
                        url: "./data.php",
                        data: {
                            FID: FID,
                            result: 'delete'
                        },
                        async: false,
                        success: function(result) {

                        }
                    });

                } else {}
            });
    });
});