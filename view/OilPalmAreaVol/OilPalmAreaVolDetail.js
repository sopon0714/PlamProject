var FMID = $('#FMID').attr("FMID");
var maxyear = $('#maxyear').attr("maxyear");
fmid = $("#data_search").attr("fmid");
function delfunction(_id, _subfarm, _date) {
    // alert(_did);
    swal({
            title: "คุณต้องการลบผลผลิต", 
            text: `แปลง ${_subfarm} วันที่ ${_date} หรือไม่ ?`,
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
                    } else {}
                });
            } else {}
        });
}

function delete_1(_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = "OilPalmAreaVolDetail.php?FMID=" + FMID;
        }
    };
    xhttp.open("POST", "manage.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`action=delete&id=${_id}`);

}
$(document).ready(function() {
    $('.tt').tooltip();
    load_year(maxyear, FMID);
    load_month(maxyear, FMID);
    load_infoHarvest(maxyear, FMID);
    getDataSetTable();
    $("#year").change(function() {
        var year = $("#year").val();
        if (year != '') {
            load_month(year, FMID);
            $("#thisYear").html(year);
            load_infoHarvest(year, FMID);
        }
    });
    $(document).on('change', '#date', function() {
        let date = $("#date").val();
        setSelectSubfarm(FMID, date);
    });
    $(document).on('click', '#btn_add_product', function() {
        $("#addProductModal").modal('show');
    });
    $(document).on('click', '#weight', function() {
        $("#weight").select();
    });
    $(document).on('click', '#UnitPrice', function() {
        $("#UnitPrice").select();
    });
    $(document).on("click", ".btn-photo", function() {
        $('#picture').modal();
        lid = $(this).attr("lid");
        path = "../../picture/activities/harvest/";
        loadPhoto_LogHarvest(path, lid);
    });

    function loadPhoto_LogHarvest(path, id) {
        $.post("manage.php", { action: "scanDir", path: path, lid: id }, function(result) {
            let data1 = JSON.parse(result);
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
    $('#save').click(function() {
        var subFarmID = $("#addProductModal select[name='SubFarmID']");
        var date = $("#addProductModal input[name='date']");
        var weight = $("#addProductModal input[name='weight']");
        var UnitPrice = $("#addProductModal input[name='UnitPrice']");
        let dataSelectNull = [subFarmID];
        let pic_sc = new Array()
        $('.pic-SC').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('#pic').val(pic_sc);

        var PIC_SC = $(".pic-SC");
        if (!checkSelectNull(dataSelectNull)) return;
        if (date.val() == "") {
            date[0].setCustomValidity('กรุณาเลือกวันที่')
            return;
        } else {
            date[0].setCustomValidity('');
        }
        if (weight.val() == "0") {
            weight[0].setCustomValidity('ผลผลิตห้ามเป็น 0 กิโลกรัม')
            return;
        } else {
            weight[0].setCustomValidity('');
        }
        if (UnitPrice.val() == "0") {
            UnitPrice[0].setCustomValidity('ราคาต่อหน่วยห้ามเป็น 0 บาท')
            return;
        } else {
            UnitPrice[0].setCustomValidity('');
        }
        if (PIC_SC.length == 0) {
            // console.log('PIC_SC.length == 0');
            $("#pic-style-char").attr("required", "");
            $("#pic-style-char")[0].setCustomValidity('กรุณาเพิ่มรูป');

        } else {
            $("#pic-style-char").removeAttr("required");
            $("#pic-style-char")[0].setCustomValidity('');
        }

    });

    function checkSelectNull(selecter) {
        for (i in selecter) {
            if (selecter[i].val() == null || selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('กรุณาเลือกข้อมูล');
                return false;
            } else selecter[i][0].setCustomValidity('');
        }
        return true;
    }

    function load_infoHarvest(year, FMID) {
        $.ajax({
            url: "data.php",
            method: "POST",
            data: {
                year: year,
                FMID: FMID,
                result: "getInfoHarvest"
            },
            dataType: "JSON",
            success: function(data) {
                var nf = new Intl.NumberFormat();
                if (data[1].Weight === null) {
                    $("#sumweight").html("0");
                    $("#sumprice").html("0");
                } else {
                    $("#sumweight").html(nf.format(parseInt(data[1].Weight)));
                    $("#sumprice").html(nf.format(parseInt(data[1].TotalPrice)));

                }

            }
        });
    }

    function load_year(year, FMID) {
        $.ajax({
            url: "data.php",
            method: "POST",
            data: {
                year: year,
                FMID: FMID,
                result: "getYearProdect"
            },
            dataType: "JSON",
            success: function(data) {
                chartyear(data);
            }
        });
    }

    function load_month(year, FMID) {
        $.ajax({
            url: "data.php",
            method: "POST",
            data: {
                year: year,
                FMID: FMID,
                result: "getMProdect"
            },
            dataType: "JSON",
            success: function(data) {
                chartmonth(data);
            }
        });
    }
    // ผลผลิตต่ปี///////////////////////////////////////////////////
    function chartyear(chart_data) {

        $('.PDY').empty();
        $('.PDY').html(` <canvas id="productYear" style="height:150px;"></canvas>`);
        let labelData = [];
        let data2 = [];
        let year = maxyear;
        let thisYear;
        let j = 0;
        for (i = 0; i < 20; i++) {
            j = 0;
            thisYear = year - 19 + i;

            for (j = 0; j < chart_data.length; j++) {
                if (chart_data[j].Year2 == thisYear) {
                    labelData.push(chart_data[j].Year2);
                    data2.push(parseFloat(chart_data[j].Weight).toFixed(2));
                    break;
                }
            }

            if (j == chart_data.length && j != 0) {
                if (thisYear < year && thisYear < chart_data[0].Year2) {
                    continue;
                }

                labelData.push(thisYear);
                data2.push(0);
            }
            if (chart_data.length == 0) {
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

    function chartmonth(chart_data) {

        $('.PDM').empty()
        $('.PDM').html(` <canvas id="productMonth" style="height:250px;"></canvas>`)
        let data2 = []
        var i, j = 0;

        for (i = 0; i < 12; i++) {
            if (chart_data[j] != null) {
                if (i == chart_data[j].Month - 1) {
                    data2.push(parseFloat(chart_data[j].Weight).toFixed(2))
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



    function setSelectSubfarm(FIMD, date) {
        $.ajax({
            url: "manage.php",
            method: "POST",
            data: {
                FIMD: FIMD,
                date: date,
                action: "setSelectSubfarm"
            },
            success: function(data) {
                $("#SubFarmID").html(data);
            }
        });
    }




});
// pagination
function getDataSetTable(){
    $.post("manage.php", {action: "pagination2",fmid: fmid,start: start,limit: limit}, function(result){
        DATA = JSON.parse(result); 
        setTableBody(DATA);
    });
}
// pagination
function setTableBody(DATA){
    html = ``;
    strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
    for (i = 1; i <= DATA[0]['numrow']; i++) {
        
        html += `<tr>
                    <td>${DATA[i]["Name"]}</td>
                    <td class=\"text-right\">${DATA[i]["dd"]} ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}</td>
                    <td class=\"text-right\">${parseFloat(DATA[i]["Weight"]).toFixed(2)}</td>
                    <td class=\"text-right\">${parseFloat(DATA[i]["UnitPrice"]).toFixed(2)}</td>
                    <td class=\"text-right\">${parseFloat(DATA[i]["TotalPrice"]).toFixed(2)}</td>
                    <td style=\"text-align:center;\">
                        <button type=\"button\" class=\"btn btn-info btn-sm btn-photo tt \"  lid=\"${DATA[i]["ID"]}\" title=\"รูปภาพ\"><i class=\"fas fa-images\"></i></button>
                        <button type=\"button\" class=\"btn btn-danger btn-sm delete tt\"   onclick=\"delfunction('${DATA[i]["ID"]}','${DATA[i]["Name"]}','${DATA[i]["dd"]} ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}')\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                    </td>
                </tr>`;
        }
                    

    $("#body").html(html);
}
