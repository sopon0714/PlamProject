<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "OilPalmAreaList";
$USER = $_SESSION[md5('user')];
$fmid = $_GET['fmid'] ?? "";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$INFOFARM =  getDATAFarmByFMID($fmid);
$ADDRESSFARM = getAddress($fmid);
$INFOFARMER = getFarmerByUFID($INFOFARM[1]['UFID']);
$INFOAREAFARM = getAreatotalByIdFarm($fmid);
$INFOSUBFARM = getOilPalmAreaListDetailByIdFarm($fmid);
$INNFOCOORFRAM = getCoorsFarm($fmid);
$COUNTCOORFRAM = getCountCoor($fmid);
?>
<link href="./OilPalmAreaListDetail.css" rel="stylesheet" />

<div class="container">
    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active" style="color:<?= $color ?>;">รายละเอียดสวนปาล์มน้ำมัน</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="OilPalmAreaList.php">รายชื่อสวนปาล์มน้ำมัน</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">รายละเอียดสวนปาล์มน้ำมัน</a>
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
                        if ($INFOFARM[1]['Icon'] == "default.png") {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farm/0/defultFarm.png\" >";
                        } else {
                            echo "<img class=\"img-radius img-profile\" src=\"../../icon/farm/{$INFOFARM[1]['FMID']}/{$INFOFARM[1]['Icon']}\" >";
                        }
                        ?>

                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-12 col-12 text-center">
                            <!-- <input type="file" id="input_upload" style="display:none" /> -->
                            <button type="button" id="edit_photo" class="btn btn-primary btn-sm form-control mb-3" uid="<?php echo $fmid; ?>">
                                เปลี่ยนรูปสวนปาล์ม</button>
                        </div>
                    </div>

                    <div class="row mt-3 justify-content-center">
                        <div class="col-xl-5 col-3 text-right">
                            <span>ชื่อสวนปาล์ม : </span>
                        </div>
                        <div class="col-xl-6 col-3">
                            <span><?php echo $INFOFARM[1]['Name'] ?></span>
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
                            <span>เกษตรกร :</span>
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
                            <?php echo " <span> {$ADDRESSFARM[1]['Address']}  ต.{$ADDRESSFARM[1]['subDistrinct']}  อ. {$ADDRESSFARM[1]['Distrinct']}  จ. {$ADDRESSFARM[1]['Province']} </span>"; ?>

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-2 col-2">
                            <span>พื้นที่ทั้งหมด : </span>
                        </div>
                        <div class="col-xl-10 col-10">
                            <?php echo "<span>{$INFOAREAFARM[1]['AreaRai']} ไร่ {$INFOAREAFARM[1]['AreaNgan']} งาน {$INFOAREAFARM[1]['AreaWa']} วา</span>"; ?>

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 mb-3">
                            <button type="button" id="btn_edit_map" style="float:right;" class="btn btn-warning btn-sm">แก้ไขตำแหน่งสวน</button>
                            <button type="button" id="btn_edit_detail1" style="float:right; margin-right:10px;" class="btn btn-warning btn-sm">แก้ไขข้อมูลสวน</button>
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
                    <div>
                        <span>รายการแปลงปลูกปาล์มน้ำมัน</span>
                        <button type="button" id="btn_add_subfarm" style="float:right;" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> เพิ่มแปลง</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xl-3 col-12">
                            <button type="button" id="btn_comfirm" class="btn btn-outline-success btn-sm"><i class="fas fa-file-excel"></i> Excel</button>
                            <button type="button" id="btn_comfirm" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</button>

                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-data  tableSearch" width="100%">
                            <thead>
                                <tr>
                                    <th>ชื่อแปลง</th>
                                    <th>พื้นที่ปลูก</th>
                                    <th>จำนวนต้น</th>
                                    <th>อายุปาล์มน้ำมัน (วัน)</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ชื่อแปลง</th>
                                    <th>พื้นที่ปลูก</th>
                                    <th>จำนวนต้น</th>
                                    <th>อายุปาล์มน้ำมัน (วัน)</th>
                                    <th>จัดการ</th>
                                </tr>
                            </tfoot>
                            <tbody id="getData2">
                                <label id="size" hidden size="<?php echo sizeof($INFOSUBFARM); ?>"></label>
                                <?php
                                for ($i = 1; $i < count($INFOSUBFARM); $i++) {
                                    $old = " - ";
                                    if ($INFOSUBFARM[$i]['year'] != NULL) {
                                        $old = "{$INFOSUBFARM[$i]['year']}ปี {$INFOSUBFARM[$i]['month']}เดือน {$INFOSUBFARM[$i]['day']}วัน";
                                    }
                                    echo "<tr>
                                    <td class=\"text-left\">{$INFOSUBFARM[$i]['Name']}</td>
                                    <td class=\"text-right\">{$INFOSUBFARM[$i]['AreaRai']} ไร่ {$INFOSUBFARM[$i]['AreaNgan']} งาน</td>
                                    <td class=\"text-right\">{$INFOSUBFARM[$i]['NumTree']}</td>
                                    <td class=\"text-right\">$old</td>
                                    <td style='text-align:center;'>
                                    <a href='OilPalmAreaListSubDetail.php?FSID={$INFOSUBFARM[$i]['FSID']}&FMID=$fmid'><button type='button' id='btn_info{$INFOSUBFARM[$i]['FSID']}' class='btn btn-info btn-sm'><i class='fas fa-bars'></i></button></a>
                                    <button type='button' FSID='{$INFOSUBFARM[$i]['FSID']}' class='btn btn-danger btn-sm btnDel' ><i class='far fa-trash-alt'></i></button>   
                                    </button>
                                    </td>
                                    <label class=\"click-map\" hidden id=\"$i\" nameSubFarm=\"{$INFOSUBFARM[$i]['Name']}\"  la=\"{$INFOSUBFARM[$i]['Latitude']}\" long=\"{$INFOSUBFARM[$i]['Longitude']}\"></label>
                                </tr>";
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ส่วนที่ต้องเอาไปแทนในของอิง -->
    <div class="modal fade" id="addSubGardenModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="form-signin" method="POST" action='addData.php'>
                    <div class="modal-header header-modal">
                        <h4 class="modal-title">เพิ่มแปลง</h4>
                    </div>
                    <div class="modal-body" id="addModalBody">
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ชื่อแปลง</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" id="namefarm" name="namefarm">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ชื่อย่อแปลง</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" id="initials" name="initials">
                            </div>
                        </div>
                        <!-- <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>จำนวนพื้นที่</span>
                            </div>
                            <div class="col-xl-9 col-12">
                               ไร่<input class="form-control" type="text"   id="farm" name="farm">
                               งาน<input class="form-control" type="text"  id="work" name="work">
                               ตารางวา<input class="form-control" type="text"  id="wah" name="wah">
                            </div>
                        </div> -->
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>พื้นที่</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <div class="row">
                                    <div class="col-3">
                                        <input type="number" class="form-control" id="farm" name="farm" value="0">
                                    </div>
                                    <div class="col-3 mt-1">
                                        <span>ไร่</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-3">
                                        <input type="number" class="form-control" id="work" name="work" value="0">
                                    </div>
                                    <div class="col-3 mt-1">
                                        <span>งาน</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-3">
                                        <input type="number" class="form-control" id="wah" name="wah" value="0">
                                    </div>
                                    <div class="col-3 mt-1">
                                        <span>วา</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>จังหวัด</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select id="province1" class="form-control" name="province">
                                    <option disabled selected id="province_list">เลือกจังหวัด</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>อำเภอ</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select id="amp1" name="amphur" class="form-control">
                                    <option selected="" disabled="">เลือกอำเภอ</option>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ตำบล</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select id="subamp" name="subdistrinct" class="form-control">
                                    <option selected="" disabled="">เลือกตำบล</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="add">.
                    <input type="hidden" id="fmid" name="fmid" value="<?php echo $fmid ?>">
                    <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
                    <input type="hidden" id="fname" name="fname" value="<?php echo $fname ?>">
                    <input type="hidden" id="logid" name="logid" value="<?php echo $logid ?>">
                    <input type="hidden" id="StartT" name="StartT" value="<?php echo $StartT ?>">
                    <div class="modal-footer">
                        <button class="btn btn-success btn-md" type="submit">ยืนยัน</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>


<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("OilPalmAreaListDetailModal.php"); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>
<!-- <script src="../../lib/croppie/croppie.js"></script> -->
<script src="./test.js"></script>
<script>
    function initMap() {
        var locations = [];
        var center = [0, 0];
        click_map = $('.click-map').html();
        size = $('#size').attr('size');
        for (i = 1; i < size; i++) {
            nameSubFarm = $('#' + i).attr('nameSubFarm');
            la = parseFloat($('#' + i).attr('la'));
            long = parseFloat($('#' + i).attr('long'));
            center[0] += la;
            center[1] += long;
            data = [nameSubFarm, la, long];
            locations.push(data);
        }
        center[0] = center[0] / (size - 1);
        center[1] = center[1] / (size - 1);

        console.log(center);

        console.log(locations);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: new google.maps.LatLng(center[0], center[1]),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"

            });
        }
        mapcolor = new google.maps.Map(document.getElementById('map2'), {
            zoom: 15,
            center: new google.maps.LatLng(center[0], center[1]),
            mapTypeId: 'satellite'
        });

        mapcolor.markers = [];

        <?php
        for ($i = 1; $i <= count($COUNTCOORFRAM); $i++) {
            $LatLng = "";
            echo " var triangleCoords$i = [";
            for ($j = 1; $j <= count($INNFOCOORFRAM); $j++) {
                if ($INNFOCOORFRAM[$j]['FSID'] == $COUNTCOORFRAM[$i]['FSID']) {
                    $LatLng .= "{
                                    lat:{$INNFOCOORFRAM[$j]['Latitude']}   ,
                                    lng:{$INNFOCOORFRAM[$j]['Longitude']} 
                                },";
                }
            }
            echo substr($LatLng, 0, -1);
            echo "];";

            echo "  var mapPoly$i = new google.maps.Polygon({
                    paths: triangleCoords$i,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                });
                mapPoly$i.setMap(mapcolor);";
        }
        ?>
    }
</script>