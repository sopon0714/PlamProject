<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "OilPalmAreaList";
$fsid = $_GET['FSID'];
$fmid = $_GET['FMID'];

include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$INFOFARM =  getDATAFarmByFMID($fmid);
$INFOSUBFARM = getDataSubFarmByFSID($fsid);
$INFOFARMER = getFarmerByUFID($INFOFARM[1]['UFID']);
$ADDRESSSUBFARM = getAddressSubDetail($fsid);
$INFOAREASUBFARM = getAreatotalByIdSubFarm($fsid);
$INNFOCOORSUBFRAM = getCoorsSubFarm($fsid);
$OLDPALMSUBFARM = getOldPalmByIdSubFarm($fsid);
$LOGPLANTTING = getLogPlantingBySubfarmId($fsid);
$YEAR = getYear();
$sumng1 = 1;
$sumng2 = 0;
$sumdead = 0;
$sum = 0;
$sumng1Pers = 0;
$sumng2Pers = 0;
$sumdeadPers = 0;
?>
<link href="./OilPalmAreaListDetail.css" rel="stylesheet" />
<style>
    #map {
        width: 100%;
        height: 700px;
    }

    #find {
        max-width: 500px;
    }

    .scrollber {
        list-style-type: none;
        list-style-position: inside;
        margin: 0px 12px 8px 12px;
        height: 200px;

        border-width: 0px;
        border-style: solid;
        overflow-y: scroll;

    }
</style>

<div class="container">
    <!-- <?php echo "{$maxyear[1]['max']}" ?> -->
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color:<?= $color ?>;"> รายละเอียดแปลงปลูก</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="OilPalmAreaList.php">รายชื่อสวนปาล์มน้ำมัน</a>
                                <span> > </span>
                                <a class="link-path" href="OilPalmAreaListDetail.php?fmid=<?php echo $fmid ?>">รายละเอียดสวนปาล์มน้ำมัน</a>
                                <span> > </span>
                                <a class="link-path link-active" href="OilPalmAreaListSubDetail.php?FSID=<?php echo $fsid ?>&FMID=<?php echo $fmid ?>" style="color:<?= $color ?>;">รายละเอียดแปลงปลูก</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-6 col-12">
            <div class="card" style="height: 350px">
                <div class="card-body" id="for_card">
                    <div class="row">
                        <?php
                        if ($INFOSUBFARM[1]['Icon'] == "default.png") {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/subfarm/0/defultSubFarm.png\" >";
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/subfarm/{$INFOSUBFARM[1]['FSID']}/{$INFOSUBFARM[1]['Icon']}\" >";
                        }
                        ?>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-12 col-12 text-center">

                            <button type="button" id="edit_photo" class="btn btn-primary btn-sm form-control mb-3" uid="<?php echo $fsid; ?>">
                                เปลี่ยนรูปแปลง</button>
                        </div>
                    </div>
                    <div class="row mt-1 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อสวน : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOFARM[1]['Name'] ?></span>
                        </div>
                    </div>
                    <div class="row mt-1 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อแปลง : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOSUBFARM[1]['Name'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card" style="height: 350px">
                <div class="card-body" id="card_height">
                    <div class="row">
                        <?php
                        if ($INFOFARMER[1]['Icon'] == "default.png") {
                            if ($INFOFARMER[1]['Title2'] == "นาย") {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/man.jpg\" >";
                            } else {
                                echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/woman.jpg\" >";
                            }
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farmer/{$INFOFARMER[1]['UFID']}/{$INFOFARMER[1]['Icon']}\" >";
                        }
                        ?>
                    </div>
                    <div class="row mt-3">

                    </div>
                    <div class="row mt-3 justify-content-center">

                        <div class="col-xl-3 col-3 text-right">
                            <span>เกษตรกร : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span> <?php echo $INFOFARMER[1]['Title2'] . " " . $INFOFARMER[1]['FirstName'] . " " . $INFOFARMER[1]['LastName'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-xl-2 col-2">
                            <span>ที่อยู่ : </span>
                        </div>
                        <div class="col-xl-10 col-10">
                            <?php echo " <span> {$ADDRESSSUBFARM[1]['Address']}  ต.{$ADDRESSSUBFARM[1]['subDistrinct']}  อ. {$ADDRESSSUBFARM[1]['Distrinct']}  จ. {$ADDRESSSUBFARM[1]['Province']} </span>"; ?>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-2 col-2">
                            <span>พื้นที่แปลงปลูก : </span>
                        </div>
                        <div class="col-xl-10 col-10">
                            <?php echo "<span>{$INFOAREASUBFARM[1]['AreaRai']} ไร่ {$INFOAREASUBFARM[1]['AreaNgan']} งาน {$INFOAREASUBFARM[1]['AreaWa']} วา</span>"; ?>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 mb-3">

                            <button type="button" id="btn_edit_map" style="float:right" class="btn btn-warning btn-sm">แก้ไขตำแหน่งแปลง</button>
                            <button type="button" id="btn_edit_subfarm" style="float:right;margin-right: 10px" class="btn btn-warning btn-sm">แก้ไขข้อมูลแปลง</button>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div id="map" style="width:auto; height:400px;"></div>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div id="map2" style="width:auto; height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-12 col-12">
            <div class="card">

                <div class="card-header card-bg">
                    <button type="button" id="plantingmodal" style="float:right;" class="btn btn-success btn-sm">เพิ่มข้อมูลการปลูก</button>

                    <?php
                    if ($OLDPALMSUBFARM[0]['numrow'] == 0) {
                        echo "<h4>0 ต้น อายุ - ปี - เดือน - วัน</h4>";
                    } else {
                        echo "<h4>{$OLDPALMSUBFARM[1]['NumTree']} ต้น อายุ  {$OLDPALMSUBFARM[1]['year']} ปี {$OLDPALMSUBFARM[1]['month']} เดือน {$OLDPALMSUBFARM[1]['day']} วัน</h4>";
                    }
                    ?>


                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-5 col-12 scrollber">
                            <?php

                            if ($LOGPLANTTING[0]['numrow'] == 0) {
                                echo "ไม่มีข้อมูล";
                            } else {
                                $sumng1 = 0;
                                for ($i = 1; $i < count($LOGPLANTTING); $i++) {
                                    if ($LOGPLANTTING[$i]['NumGrowth1'] > 0) {
                                        $sumng1 += $LOGPLANTTING[$i]['NumGrowth1'];
                                        echo "  <div class=\"row mb-3\">
                                                    <div class=\"col-xl-3 \">
                                                        <span>ปลูก :  </span>
                                                    </div>
                                                    <div class=\"col-xl-4 \">
                                                        <span>{$LOGPLANTTING[$i]['Day']}/{$LOGPLANTTING[$i]['Month']}/{$LOGPLANTTING[$i]['Year2']}</span>
                                                    </div>
                                                    <div class=\"col-xl-3 \">
                                                        <span>{$LOGPLANTTING[$i]['NumGrowth1']} ต้น</span>
                                                    </div>
                                                </div>";
                                    } else if ($LOGPLANTTING[$i]['NumGrowth2'] > 0) {
                                        $sumng2 += $LOGPLANTTING[$i]['NumGrowth2'];
                                        echo "  <div class=\"row mb-3\">
                                        <div class=\"col-xl-3 \">
                                            <span>ปลูกซ่อม :  </span>
                                        </div>
                                        <div class=\"col-xl-4 \">
                                            <span>{$LOGPLANTTING[$i]['Day']}/{$LOGPLANTTING[$i]['Month']}/{$LOGPLANTTING[$i]['Year2']}</span>
                                        </div>
                                        <div class=\"col-xl-3 \">
                                            <span>{$LOGPLANTTING[$i]['NumGrowth2']} ต้น</span>
                                        </div>
                                    </div>";
                                    } else if ($LOGPLANTTING[$i]['NumDead'] > 0) {
                                        $sumdead += $LOGPLANTTING[$i]['NumDead'];
                                        echo "  <div class=\"row mb-3\">
                                       <div class=\"col-xl-3 \">
                                           <span>ตาย :  </span>
                                       </div>
                                       <div class=\"col-xl-4 \">
                                           <span>{$LOGPLANTTING[$i]['Day']}/{$LOGPLANTTING[$i]['Month']}/{$LOGPLANTTING[$i]['Year2']}</span>
                                       </div>
                                       <div class=\"col-xl-3 \">
                                           <span>{$LOGPLANTTING[$i]['NumDead']} ต้น</span>
                                       </div>
                                   </div>";
                                    }
                                }
                                $sum += $sumng1 + $sumng2 + $sumdead;
                                $sumng1Pers = round(($sumng1 / $sum) * 100, 1);
                                $sumng2Pers = round(($sumng2 / $sum) * 100, 1);
                                $sumdeadPers = round(($sumdead / $sum) * 100, 1);
                            }
                            ?>
                        </div>
                        <div class="col-xl-6 col-12">
                            <div class="row">
                                <div class="col-8">
                                    <canvas id="plantPie"></canvas>
                                </div>
                                <div class="col-4">
                                    <div class="row mt-2">
                                        <div class="col-3 mt-1">
                                            <div style="width: 30px; height: 15px; background-color: #00ce68; "></div>
                                        </div>
                                        <div class="col-9">
                                            <span>ปลูก <?= $sumng1Pers ?> %</span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-3 mt-1">
                                            <div style="width: 30px; height: 15px; background-color: #f6c23e; "></div>
                                        </div>
                                        <div class="col-9">
                                            <span>ซ่อม <?= $sumng2Pers ?> %</span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-3 mt-1">
                                            <div style="width: 30px; height: 15px; background-color: #e74a3b; "></div>
                                        </div>
                                        <div class="col-9">
                                            <span>ตาย <?= $sumdeadPers ?> %</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4 mb-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-6">
                            <a href="OilPalmAreaVolDetail.php" style="text-decoration: none;">
                                <h4>ผลผลิต</h4>
                            </a>
                        </div>
                        <div class="col-6">
                            <div id="maxyear" hidden maxyear="<?= $YEAR[1]['Year2'] ?>"></div>
                            <select id="year" class="form-control" style="width:20%; float:right;">
                                <?php
                                for ($i = 1; $i <= $YEAR[0]['numrow']; $i++) {
                                    echo "<option value='{$YEAR[$i]['Year2']}'>{$YEAR[$i]['Year2']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-12 PDY">
                            <canvas id="productYear" style="height:250px;"></canvas>
                        </div>
                        <div class="col-xl-6 col-12 PDM">
                            <canvas id="productMonth" style="height:250px;"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4 mb-4">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <h4>ปริมาณการใส่ปุ๋ย</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-12">
                            <div class="row">
                                <?php
                                if ($namevol[0]['numrow'] != 0) {
                                    for ($i = 1; $i <= $namevol[0]['numrow']; $i++) {
                                        echo "
                                <div class=\"col-4\">
                                    <canvas id=\"ferYear" . $i . "\" style=\"height:250px;\"></canvas>
                                </div>";
                                    }
                                } else {
                                    echo "<h4>ไม่มีข้อมูล</h4>";
                                }
                                ?>
                            </div>
                            <div class="row">
                                <?php
                                if ($namevol[0]['numrow'] != 0) {
                                    for ($i = 1; $i <= $namevol[0]['numrow']; $i++) {
                                        echo "
                                    <div class=\"col-4 text-center\">
                                    <span style=\"margin-left: 17%;\">" . $namevol[$i]['namevol'] . "</span>
                                </div>";
                                    }
                                }



                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("OilPalmAreaListSubDetailModal.php"); ?>
<script>
    var mapedit;
    var numCoor;
    $(document).ready(function() {
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

    });

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 16,
            center: new google.maps.LatLng(<?php echo $INFOSUBFARM[1]['Latitude'] ?>, <?php echo $INFOSUBFARM[1]['Longitude'] ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $INFOSUBFARM[1]['Latitude'] ?>, <?php echo $INFOSUBFARM[1]['Longitude'] ?>),
            map: map,
            icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"

        });
        mapcolor = new google.maps.Map(document.getElementById('map2'), {
            zoom: 16,
            center: new google.maps.LatLng(<?php echo $INFOSUBFARM[1]['Latitude'] ?>, <?php echo $INFOSUBFARM[1]['Longitude'] ?>),
            mapTypeId: 'satellite'
        });

        mapcolor.markers = [];
        <?php

        $LatLng = "";
        echo " var triangleCoords = [";
        for ($i = 1; $i < count($INNFOCOORSUBFRAM); $i++) {

            $LatLng .= "{
                    lat:{$INNFOCOORSUBFRAM[$i]['Latitude']}   ,
                    lng:{$INNFOCOORSUBFRAM[$i]['Longitude']} 
                },";
        }
        echo substr($LatLng, 0, -1);
        echo "];";

        echo "  var mapPoly = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35
        });
        mapPoly.setMap(mapcolor);";

        ?>
        mapedit = new google.maps.Map(document.getElementById('map_area_edit'), {
            zoom: 16,
            center: new google.maps.LatLng(<?php echo $INFOSUBFARM[1]['Latitude'] ?>, <?php echo $INFOSUBFARM[1]['Longitude'] ?>),
            mapTypeId: 'satellite'
        });
        mapedit.markers = [];
        var infowindow = [];

        numCoor = 0;
        google.maps.event.addListener(mapedit, 'click', function(event) {
            placeMarker(event.latLng);
        });


        function placeMarker(location) {
            var marker = new google.maps.Marker({
                position: location,
                map: mapedit,
                draggable: true,
            });
            infowindow[numCoor] = new google.maps.InfoWindow();
            infowindow[numCoor].setContent("ลำดับ " + (numCoor + 1));
            infowindow[numCoor].open(mapedit, marker);
            mapedit.markers.push(marker);
            numCoor = numCoor + 1;
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>
<script src="test.js"></script>