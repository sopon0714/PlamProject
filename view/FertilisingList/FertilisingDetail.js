fsid = $("#data_search").attr("fsid");
$(document).ready(function() {
    var FSID = $('#FSID').attr('fsid');
    var NumTree = $('#NumTree').attr('NumTree');
    var maxyear = $('#maxyear').val();
    load_Nutr(maxyear, FSID);
    loadDataNutrDetail(FSID, maxyear);
    var DATACAL = loadDATACal(FSID, maxyear);
    getDataSetTable();
    setTimeout(function() { setSumVolofNutr(); }, 2000);
    $('.tt').tooltip();
    $(document).on('change', '.CalFerVol', function() {
        setVolNutrOffer(this)
        setSumVolAll();
        setSumVolofNutr();
    });
    $(document).on('change', '.CalFerUnit', function() {
        setVolNutrOffer(this)
        setSumVolAll();
        setSumVolofNutr();
    });

    function SetDeffVol() {
        // console.log(DATACAL);
    }

    function setVolNutrOffer(elem) {
        let FerID = $(elem).attr('ferid');
        let Vol = $('#VolF' + FerID).val();
        let unit = $('#UnitF' + FerID).val();
        let nutrid;
        let Volcal;
        // console.log(Vol);
        // console.log(unit);
        $(".CalFerID" + FerID).each(function(index) {
            Volcal = Vol;
            nutrid = $(this).attr('nutrid');
            if (typeof DATACAL['Fer'][FerID][nutrid] !== 'undefined') {
                if (unit == 1 && Number(DATACAL['Nutr'][nutrid]['UnitNum']) == 2) {
                    Volcal = Vol * 1000.00;
                } else if (unit == 2 && Number(DATACAL['Nutr'][nutrid]['UnitNum']) == 1) {
                    Volcal = Vol / 1000.00;
                }
                Volcal = (Volcal * Number(DATACAL['Fer'][FerID][nutrid]['Percent'])).toFixed(2);
                $('#N' + nutrid + 'F' + FerID).html(Volcal);
            }
        });
    }

    function setSumVolAll() {
        let Vol = 0;
        let FerID;
        let unit;
        $(".CalFerVol").each(function(index) {
            FerID = $(this).attr('ferid');
            unit = $('#UnitF' + FerID).val();
            if (unit == 1) {
                Vol = Vol + Number($(this).val() * 1000);
            } else {
                Vol = Vol + Number($(this).val());
            }
        });
        let Kg = Math.floor(Vol / 1000.00);
        let g = Vol - (Math.floor(Vol / 1000) * 1000);

        let text = "";
        if (Kg > 0) {
            text += Kg + " Kg";
        }
        if (g > 0) {
            text += " " + g + " g";
        }
        if (Vol == 0) {
            text += "0 Kg";
        }

        $('.SumAllVol').html(text);
        Vol = Vol / NumTree;
        Kg = Math.floor(Vol / 1000.00);
        g = Vol - (Math.floor(Vol / 1000) * 1000);

        text = "";
        if (Kg > 0) {
            text += Kg + " Kg";
        }
        if (g > 0) {
            text += " " + g + " g";
        }
        if (Vol == 0) {
            text += "0 Kg";
        }

        $('.SumAllVolOfTree').html(text);

    }

    function setSumVolofNutr() {
        let NID;
        let Sum = 0;
        let text = "";
        let diff;
        $(".SumAllVolNutr").each(function(index) {
            NID = $(this).attr('nutrid');
            text = "";
            Sum = getSumAllNutr(NID)
            Sum = Sum.toFixed(2);
            text = Sum + " " + DATACAL['Nutr'][NID]['Unit'];
            $('#SumAllVolN' + NID).html(text);

            text = "";
            diff = (Number(DATACAL['Nutr'][NID]['diff']) - Sum) / NumTree;
            diff = diff.toFixed(2);
            text = diff + " " + DATACAL['Nutr'][NID]['Unit'];
            $('#DeffOfTreeVolN' + NID).html(text);

            text = "";
            Sum = Sum / NumTree;
            Sum = Sum.toFixed(2);
            text = Sum + " " + DATACAL['Nutr'][NID]['Unit'];
            $('#SumOfTreeVolN' + NID).html(text);


        });

    }

    function getSumAllNutr(NID) {
        let Sum = 0; 
        $(".CalNutrID" + NID).each(function(index) {
            Sum = Number(Sum) + Number($(this).html());
            // console.log(Sum);
        });
        return Sum;

    }

    function loadDATACal(FSID, maxyear) {
        $.post("data.php", { result: "loadDATACal", year: maxyear, FSID: FSID }, function(result) {
            DATACAL = JSON.parse(result);
            // console.log(DATACAL);
            // console.log(DATACAL['Fer'][2][1]['Percent']);
        });
    }

    function load_Nutr(year, FSID) {
        $.post("data.php", { result: "chartVolNutr", year: year, FSID: FSID }, function(result) {
            DATA = JSON.parse(result);
            // console.log(DATA);

            chartNutr(DATA);
        });
    }
    $(document).on("click", ".btn-delete", function() {
        let date = $(this).attr("logdate");
        let logid = $(this).attr("logid");
        let fer = $(this).attr("fer");

        swal({
                title: "คุณต้องการลบ ",
                text: `การใส่ปุ๋ย ${fer} วันที่ ${date} หรือไม่ ?`,
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
                            delete_1(logid)
                        } else {}
                    });
                } else {}
            });
    });

    function delete_1(logid, typeid) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                window.location.href = "./FertilisingDetail.php?FSID=" + FSID + "&Active=3";
            }
        };
        xhttp.open("POST", "manage.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`action=deleteLog&logid=${logid}`);

    }
    $(document).on("click", "#btn-modal1", function() {
        $("#modal-1").modal('show');
    });
    $(document).on("click", ".btn-photo", function() {
        $('#picture').modal();
        lid = $(this).attr("lid");
        path = "../../picture/activities/fertilising/";
        $("#fetchPhoto").html('<div></div>');
        loadPhoto_LogFertilising(path, lid);
    });

    function loadPhoto_LogFertilising(path, id) {
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

    $(document).on('click', '#btn-submit', function() {
        var date = $("input[name='date']");
        var ferID = $("select[name='ferID']");
        var Vol = $("input[name='Vol']");
        //console.log("date" + date.val() + "\ntimeStrat" + timeStrat.val() + "\ntimeEnd" + timeEnd.val() + "\nType" + Type.val() + "\nrank" + rank.val() + "\nVol" + Vol.val())
        let dataNull = [date];
        let dataNull2 = [ferID];
        let dataNumNull = [Vol];
        let pic_sc = new Array()
        $('.pic-SC').each(function(i, obj) {
            pic_sc.push($(this).attr('src') + 'manu20')
        });
        $('#pic').val(pic_sc);

        var PIC_SC = $(".pic-SC");
        if (!checkNull(dataNull)) return;
        if (!checkSelectNull(dataNull2)) return;
        if (!checkNumNull(dataNumNull)) return;
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
            if (selecter[i].val() == '0') {
                selecter[i][0].setCustomValidity('ห้ามเป็น 0');
                return false;
            } else {
                selecter[i][0].setCustomValidity('');
            }
        }

        return true;
    }
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
    $("#year").change(function() {
        var year = $("#year").val();
        //console.log(year);
        loadDataNutrDetail(FSID, year);
        load_Nutr(year, FSID);
    });

    function loadDataNutrDetail(fsid, year) {
        //console.log(fsid + " " + year);
        $.post("manage.php", { action: "loadData", fsid: fsid, year: year }, function(result) {
            $('#DetailTable').html(result);
        });
    }

    function chartNutr(DATA) {
        $('.PDM').empty()
        $('.PDM').html(` <canvas id="VolNutr" style="height:250px;"></canvas>`)
        $('.PDM2').empty()
        $('.PDM2').html(` <canvas id="VolNutr2" style="height:250px;"></canvas>`)
        let data2 = []
        var i, j = 0;
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
                        labelString: 'ปริมาณที่ใส่'
                    },
                    gridLines: {
                        display: true
                    },
                    type: 'logarithmic',
                    ticks: {
                        min: 0,
                        max: 100000,
                        callback: function(value, index, values) {
                            // if (value === 1000000) return "1M";
                            // if (value === 100000) return "100k";
                            if (value === 10000) return "10k";
                            if (value === 1000) return "1k";
                            if (value === 100) return "100";
                            if (value === 10) return "10";
                            if (value === 1) return "1";
                            if (value === 0) return "0";


                            return null;
                        }

                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'ธาตุอาหาร'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        min: 0

                    },
                    stacked: true
                }],
            }
        };
        var speedData = {
            labels: DATA.speedData,
            datasets: [{
                    label: 'ต้องการ',
                    data: DATA.diff,
                    backgroundColor: '#CF2626'
                },
                {
                    label: 'ที่ใส่แล้ว',
                    data: DATA.Vol,
                    backgroundColor: '#4CAB50'
                }

            ]
        };

        var ctx = $("#VolNutr");
        var plantPie = new Chart(ctx, {
            type: 'bar',
            data: speedData,
            options: chartOptions
        });


    }

});
// pagination
function getDataSetTable(){
    $.post("manage.php", {action: "pagination2",fsid: fsid,start: start,limit: limit}, function(result){
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
                    <td class=\"text-center\">${DATA[i]["day"]}  ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}</td>
                    <td class=\"text-left\">${DATA[i]["Name"]}</td>
                    <td class=\"text-right\">${DATA[i]["Vol"]}</td>
                    <td class=\"text-center\">${DATA[i]["Unit"]}</td>
                    <td class=\"text-center\">
                    <button type=\"button\" class=\"btn btn-info btn-sm btn-photo tt \"  lid=\"${DATA[i]["ID"]}\" title=\"รูปภาพ\"><i class=\"fas fa-images\"></i></button>
                        <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete tt\" fer=\"${DATA[i]["Name"]}\"   logid=\"${DATA[i]["dd"]}{$INFOFERTILISING[$i]['ID']}\"    logdate=\"${DATA[i]["day"]}  ${strMonthCut[DATA[i]["Month"]]} ${DATA[i]["Year2"]}\" title=\"ลบ\"><i class=\"far fa-trash-alt\"></i></button>
                    </td>
                </tr>`;
        }
                    

    $("#body").html(html);
}