var FMID = $('#FMID').attr("FMID");
var maxyear = $('#maxyear').attr("maxyear");
$(document).ready(function() {
    $('.tt').tooltip();
    load_year(maxyear, FMID);
    load_month(maxyear, FMID);
    load_infoHarvest(maxyear, FMID)
    $("#year").change(function() {
        var year = $("#year").val();
        if (year != '') {
            load_month(year, FMID);
            $("#thisYear").html(year);
            load_infoHarvest(year, FMID);
        }
    });

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

                if (data[1].Weight === null) {
                    $("#sumweight").html("0.00");
                    $("#sumprice").html("0.00");
                } else {
                    $("#sumweight").html(parseFloat(data[1].Weight).toFixed(2));
                    $("#sumprice").html(parseFloat(data[1].TotalPrice).toFixed(2));

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
        var year = maxyear;
        var thisYear;
        var j = 0;
        for (i = 0; i < 8; i++) {
            j = 0;
            thisYear = year - 7 + i;

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
        console.table(chart_data);
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



});