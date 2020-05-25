<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "FarmerList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$idformal = '';
$fullname = '';
$fpro = 0;
$fdist = 0;

$FARMER = getFarmer($idformal, $fullname, $fpro, $fdist);
$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);

// print_r($FARMER);
?>

<div class="container">

    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อเกษตรกร</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">รายชื่อเกษตรกร</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <?php
        creatCard("card-color-one",   "จำนวนเกษตรกร", getcountFarmer() . " คน", "waves");
        creatCard("card-color-two",   "จำนวนสวน",  getCountFarm() . " สวน " . getCountSubfarm() . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getCountArea() . " ไร่", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getCountTree() . " ต้น", "format_size");
        ?>

    </div>

    <form action="FarmerList.php?isSearch=1" method="post">
        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="cursor:pointer; background-color: <?= $color ?>; color: white;">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" class="card collapse 
                <?php
                if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                    echo "show";
                else
                    echo "";
                ?> 
                " aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card-header card-bg">
                        ตำแหน่งสวนปาล์มน้ำมัน
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-12">
                                <div id="map" style="width:auto;height:60vh;"></div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <span>จังหวัด</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_province" name="s_province" class="form-control ">
                                            <option selected value=0>เลือกจังหวัด</option>
                                            <?php
                                            for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                                if ($fpro == $PROVINCE[$i]["AD1ID"])
                                                    echo '<option value="' . $PROVINCE[$i]["AD1ID"] . '" selected>' . $PROVINCE[$i]["Province"] . '</option>';
                                                else
                                                    echo '<option value="' . $PROVINCE[$i]["AD1ID"] . '">' . $PROVINCE[$i]["Province"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row  mb-2">
                                    <div class="col-12">
                                        <span>อำเภอ</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_distrinct" name="s_distrinct" class="form-control">
                                            <option selected value=0>เลือกอำเภอ</option>>
                                            <?php
                                            if ($fpro != 0) {
                                                for ($i = 1; $i < sizeof($DISTRINCT_PROVINCE); $i++) {
                                                    if ($fdist == $DISTRINCT_PROVINCE[$i]["AD2ID"])
                                                        echo '<option value="' . $DISTRINCT_PROVINCE[$i]["AD2ID"] . '" selected>' . $DISTRINCT_PROVINCE[$i]["Distrinct"] . '</option>';
                                                    else
                                                        echo '<option value="' . $DISTRINCT_PROVINCE[$i]["AD2ID"] . '">' . $DISTRINCT_PROVINCE[$i]["Distrinct"] . '</option>';
                                                }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-11">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>ชื่อเกษตรกร</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="text" class="form-control" id="s_name" name="s_name" <?php if ($fullname != '') echo 'value="' . $fullname . '"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>หมายเลขบัตรประชาชน</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="password" class="form-control input-setting" id="s_formalid" name="s_formalid" <?php if ($idformal != '') echo 'value="' . $idformal . '"'; ?>>
                                        <i class="fa fa-eye-slash eye-setting" id="hide1"></i>
                                    </div>
                                </div>
                                <div class="row mb-2 padding">
                                    <div class="col-12">
                                        <button type="summit" id="btn_search" class="btn btn-success btn-sm form-control">ค้นหา</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header card-header-table py-3">
            <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>;">รายชื่อเกษตรกร</h6>

        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ชื่อ-นามสกุล</th>
                            <th>จังหวัด</th>
                            <th>อำเภอ</th>
                            <th>จำนวนสวน</th>
                            <th>จำนวนแปลง</th>
                            <th>พื้นที่ปลูก</th>
                            <th>จำนวนต้น</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>ชื่อ-นามสกุล</th>
                            <th>จังหวัด</th>
                            <th>อำเภอ</th>
                            <th>จำนวนสวน</th>
                            <th>จำนวนแปลง</th>
                            <th>พื้นที่ปลูก</th>
                            <th>จำนวนต้น</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody id="body">
<<<<<<< HEAD
                        <label id="size" hidden size ="<?php if($FARMER == 0) echo 0; else echo sizeof($FARMER); ?>"></label>
=======
                        <label id="size" hidden size="<?php echo sizeof($FARMER); ?>"></label>
>>>>>>> 0c23c3d805ce6f103e4bedd11f947352aa060438
                        <?php
                        if ($FARMER != 0) {
                            for ($i = 0; $i < sizeof($FARMER); $i++) {
                        ?>
                                <tr class="<?php echo $i; ?>" test="test<?php echo $i; ?>">
                                    <td><?php echo $FARMER[$i]['FullName']; ?></td>
                                    <td><?php echo $FARMER[$i]["Province"] ?></td>
                                    <td><?php echo $FARMER[$i]["Distrinct"] ?></td>

                                    <td class="text-right"><?php echo $FARMER[$i]['numFarm']; ?> สวน</td>
                                    <td class="text-right"><?php echo $FARMER[$i]['numSubFarm'];; ?> แปลง</td>
                                    <td class="text-right"><?php echo $FARMER[$i]['numArea1']; ?> ไร่
                                        <?php echo $FARMER[$i]['numArea2']; ?> งาน</td>
                                    <td class="text-right"><?php echo $FARMER[$i]['numTree']; ?> ต้น</td>

                                    <td style="text-align:center;">
                                        <a href='FarmerListDetail.php?farmerID=<?php echo $FARMER[$i]['dbID']; ?>'>
                                            <button type='button' id='btn_info' class="btn btn-info btn-sm btn_edit tt" data-toggle="tooltip" title="รายละเอียดข้อมูลเกษตรกร">
                                                <i class='fas fa-bars'></i>
                                            </button>
                                        </a>
                                    </td>
                                    <label class="click-map" hidden id="<?php echo $i; ?>" subDistrinct="<?php echo $FARMER[$i]["subDistrinct"]; ?>" AD3ID="<?php echo $FARMER[$i]["AD3ID"]; ?>" la="<?php echo $FARMER[$i]["Latitude"]; ?>" long="<?php echo $FARMER[$i]["Longitude"]; ?>"></label>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="Modal">

    </div>

</div>


<?php include_once("../layout/LayoutFooter.php"); ?>
<script src="FarmerList.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>