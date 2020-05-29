$(document).ready(function() {
    $("#card_height").css('height', $("#for_card").css('height'));

    $("#btn_add_subgarden").click(function() {
        $("body").append(addSubGardenModal);
        $("#addSubGardenModal").modal('show');
    });
    $("#btn_add_subgarden").click(function() {
        $("body").append(addSubGardenModal);
        $("#addSubGardenModal").modal('show');
    });
    $("#btn_add_map").click(function() {
        $("body").append(editMapModalFun(mapdetail, mapcolor));
        $("#addMapModal").modal('show');
    });
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
    $("#btn_edit_subdetail1").click(function() {
        $("body").append(editSubDetailModal);
        $("#editSubDetailModal1").modal('show');
    });
    $("#plantingmodal").click(function() {
        $("body").append(addplant);
        $("#addplant").modal('show');
    });

    $("#btn_add_map").click(function() {
        $("body").append(addMapModal);
        $("#addMapModal").modal('show');
    });

    $("#btn_info").click(function() {
        console.log("testefe");
    });

    $("#btn_delete").click(function() {
        swal({
            title: "ยืนยันการลบข้อมูล",
            icon: "warning",
            buttons: ["ยกเลิก", "ยืนยัน"],
        });
    });
    // ส่วนของกราฟ////////////////////////////////////////////////////////////////////////////////////////////////

    $("#year").change(function() {

        var year = $(this).val();
        var nsubfarm = "<?= $nsubfarm ?>"
        if (year != '') {
            load_year(year, nsubfarm);
            load_month(year, nsubfarm);
        }

    });

    load_year("<?php echo $maxyear[1]['max'] ?>", "<?= $nsubfarm ?>")
    load_month("<?php echo $maxyear[1]['max'] ?>", "<?= $nsubfarm ?>")

    function load_year(year, nsubfarm) {
        $.ajax({
            url: "loadproduct.php",
            method: "POST",
            data: {
                year: year,
                nsubfarm: nsubfarm
            },
            dataType: "JSON",
            success: function(data) {

                chartyear(data);
                // chartyear(year)
            }
        });
        console.log(year + "555555555555")
    }

    function load_month(year, nsubfarm) {
        $.ajax({
            url: "loadMproduct.php",
            method: "POST",
            data: {
                year: year,
                nsubfarm: nsubfarm
            },
            dataType: "JSON",
            success: function(data) {

                chartmonth(data);
                // chartyear(year)
            }
        });
    }
    / ผลผลิตต่อเดือน/ ///////////////////////////////////////////////////////
    function chartmonth(chart_data) {
        $('.PDM').empty()
        $('.PDM').html(` <canvas id="productMonth" style="height:250px;"></canvas>`)
        console.table(chart_data);
        let data2 = []
        var i, j = 0;

        for (i = 0; i < 12; i++) {
            if (chart_data[j] != null) {
                if (i == chart_data[j].Month - 1) {
                    console.log("5");
                    data2.push(chart_data[j].s)
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
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'รายเดือน'
                    },
                    gridLines: {
                        display: false
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
        $('.PDY').empty()
        $('.PDY').html(` <canvas id="productYear" style="height:250px;"></canvas>`)
        alert(chart_data);
        var jsonData = chart_data;
        let labelData = []
        let data2 = []
        for (i in chart_data) {

            labelData.push(chart_data[i].Year2)
            data2.push(chart_data[i].s)


        }
        // alert(labelData + "jjjjjjj" + data2)


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
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'รายปี'
                    },
                    gridLines: {
                        display: false
                    }
                }],
            }
        };
        var speedData = {
            labels: labelData,
            datasets: [{
                label: "Demo Data 1",
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


    // ปลูกซ่อมตาย/////////////////////////////////////////////////////////

    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 50,
                fontColor: 'black'
            }
        },
    };

    var speedData = {
        labels: ["ปลูก", "ซ่อม", "ตาย"],
        datasets: [{
            label: "Demo Data 1",
            data: [<?= $sumng1 ?>, <?= $sumng2 ?>, <?= $sumdead ?>],
            backgroundColor: ["#00ce68", "#f6c23e", "#e74a3b"]
        }]
    };

    var ctx = $("#plantPie");
    var plantPie = new Chart(ctx, {
        type: 'pie',
        data: speedData,
        options: chartOptions
    });



    //Fer section////////////////////////////////////////////////////////
    <?php




    for ($i = 1; $i <= $namevol[0]['numrow']; $i++) {
        echo "var chartOptions = {
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
                        labelString: 'ปริมาณปุ๋ย (ก.ก.)'
                    },
                    gridLines: {
                        display: true
                    },
                    ticks: {
                        min: 0
                        
                    }
                }]
                ,
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'รายปี'
                    },
                    gridLines: {
                        display: false
                    }
                }],
            }
        };
    
        var speedData = {
            labels: [\"2561\", \"2562\", \"2563\"],
            datasets: [
                {
                    label: \"Demo Data 1\",
                    data: $vol,
                    backgroundColor: '#05acd3'
                }
            ]
        };
    
        var ctx = $(\"#ferYear$i\");
        var plantPie = new Chart(ctx, {
            type: 'bar',
            data: speedData,
            options: chartOptions
        });";
    }
    ?>
});


function initMap() {
    var startLatLng = new google.maps.LatLng(<?= $latlong[1]['Latitude'] ?>, <?= $latlong[1]['Longitude'] ?>);

    mapdetail = new google.maps.Map(document.getElementById('map'), {
        // center: { lat: 13.7244416, lng: 100.3529157 },
        center: startLatLng,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    mapdetail.markers = [];
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(<?= $latlong[1]['Latitude'] ?>, <?= $latlong[1]['Longitude'] ?>),
        map: mapdetail,
        title: "test"
    });
    mapdetail.markers.push(marker);



    // new map ///////////////////////////////////////////////////////////////////



    var startLatLng = new google.maps.LatLng(<?= $latlong[1]['Latitude'] ?>, <?= $latlong[1]['Longitude'] ?>);

    mapcolor = new google.maps.Map(document.getElementById('map2'), {
        // center: { lat: 13.7244416, lng: 100.3529157 },
        center: startLatLng,
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });


    var triangleCoords = [{
            lat: <?= $latlong[1]['Latitude'] ?> + 0.1,
            lng: <?= $latlong[1]['Longitude'] ?> - 0.3
        },
        {
            lat: <?= $latlong[1]['Latitude'] ?> + 0.2,
            lng: <?= $latlong[1]['Longitude'] ?> + 0.2
        },
        {
            lat: <?= $latlong[1]['Latitude'] ?> - 0.4,
            lng: <?= $latlong[1]['Longitude'] ?> + 0.6
        },
        {
            lat: <?= $latlong[1]['Latitude'] ?> - 0.4,
            lng: <?= $latlong[1]['Longitude'] ?> + 0.3555
        },
    ];


    // Construct the polygon.
    var mapPoly = new google.maps.Polygon({
        paths: triangleCoords,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35
    });
    mapPoly.setMap(mapcolor);



}

function placeMarkerAndPanTo(latLng, map) {
    map.markers.push(marker);
    var marker = new google.maps.Marker({
        position: latLng,
        map: map
    });
    var triangleCoords = [{
            lat: 13.814029,
            lng: 100.037292
        },
        {
            lat: 13.5338601,
            lng: 100.54962158
        },
        {
            lat: 13.361143,
            lng: 100.984673
        },
        {
            lat: 14.31761484,
            lng: 100.6072998
        },
    ];
    console.table(triangleCoords);
    var bermudaTriangle = new google.maps.Polygon({
        paths: triangleCoords,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35
    });
    map.panTo(latLng);

}