<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "FarmerList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$farmerID = $_GET['farmerID'];

$countOwnerFarm = getCountOwnerFarm($farmerID);  //จำนวนสวน
$countOwnerSubFarm = getCountOwnerSubFarm($farmerID);  //จำนวนแปลง
$countOwnerAreaRai = getCountOwnerAreaRai($farmerID);  //จำนวนพื้นที่ไร่
$countOwnerAreaNgan = getCountOwnerAreaNgan($farmerID); //จำนวนพื้นที่งาน
$countOwnerTree = getCountOwnerTree($farmerID); //จำนวนต้นไม้

$PROFILE = getProfile($farmerID);
$FARM = getOwnerFarmer($farmerID);

?>

<body>
    
    <div class="container">

        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div class="card">
                    <div class="card-header card-bg">
                        <div class="row">
                            <div class="col-12">
                                <span class="link-active font-weight-bold"  style="color:<?=$color?>;" >รายละเอียดเกษตรกร</span>
                                <span style="float:right;">
                                    <i class="fas fa-bookmark"></i>
                                    <a class="link-path" href="#">หน้าแรก</a>
                                    <span> > </span>
                                    <a class="link-path" href="FarmerList.php">รายชื่อเกษตรกร</a>
                                    <span> > </span>
                                    <a class="link-path link-active" href="#" style="color:<?=$color?>;" >รายละเอียดเกษตรกร</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row">

            <?php
            creatCard("card-color-one",   "จำนวนสวน", $countOwnerFarm . " สวน", "waves");
            creatCard("card-color-two",   "จำนวนแปลง", $countOwnerSubFarm . " แปลง", "group");
            creatCard("card-color-three",   "พื้นที่ทั้งหมด", $countOwnerAreaRai . " ไร่ ".$countOwnerAreaNgan." งาน", "dashboard");
            creatCard("card-color-four",   "จำนวนต้นไม้", $countOwnerTree . " ต้น", "format_size");
            ?>

        </div>

        <div class="row">
            <div class="col-xl-6 col-12 mb-4">
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <div class="card">
                            <div class="card-header card-bg" style="height: 55px;">
                                <div>
                                <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>; top: 19px; position: absolute;">โปรไฟล์</h6>

                                <a href='./../Chat/Chat.php?ufid=<?php echo $farmerID; ?>'>
                                <button style="float:right;" type='button' id='btn_warning' class="btn btn-warning btn-sm btn_edit tt" data-toggle="tooltip" title="แจ้งเตือน">
                                    <i class='fas fa-bell'></i>
                                </button></a>                     
                                </div> 
                                <div>
                                           
                                </div> 
                                
                            </div>
                            <div class="card-body" >
                                        
                                        <div align="center">
                                            
                                            <img src=<?php 
                                            if ($PROFILE[1]["Icon"] != NULL)
                                            echo $src = "../../icon/farmer/".$farmerID."/".$PROFILE[1]["Icon"]; 
                                            else
                                            echo $src = "../../picture/default.jpg" 
                                            ?> 
                                             alt="User" style="border-radius: 100%;width: 300px;height: 300px;">
                                        </div>

                                        <div class="row mb-4 mt-3">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>คำนำหน้า</span>
                                            </div>
                                            <div class="col-xl-9 col-12">
                                                <input type="text" class="form-control" id="rank" value="<?php echo $PROFILE[1]['Title'] ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>ชื่อ</span>
                                            </div>
                                            <div class="col-xl-9 col-12">

                                                <input type="text" class="form-control" id="firstname" value="<?php echo $PROFILE[1]["FirstName"] ?>" disabled>

                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>นามสกุล</span>
                                            </div>
                                            <div class="col-xl-9 col-12">
                                                <input type="text" class="form-control" id="lastname" value="<?php echo $PROFILE[1]["LastName"] ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>ที่อยู่</span>
                                            </div>
                                            <div class="col-xl-9 col-12">
                                                <input type="text" class="form-control" id="address" value="<?php echo $PROFILE[1]["Address"] ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>ตำบล</span>
                                            </div>
                                            <div class="col-xl-9 col-12">
                                                <input type="text" class="form-control" id="subdistrict" value="<?php echo $PROFILE[1]["subDistrinct"] ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>อำเภอ</span>
                                            </div>
                                            <div class="col-xl-9 col-12">
                                                <input type="text" class="form-control" id="district" value="<?php echo $PROFILE[1]["Distrinct"] ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3 col-12 text-right">
                                                <span>จังหวัด</span>
                                            </div>
                                            <div class="col-xl-9 col-12">
                                                <input type="text" class="form-control" id="province" value="<?php echo $PROFILE[1]["Province"] ?>" disabled>
                                            </div>
                                        </div>
                                       
                                        <label hidden id="info" la="<?php echo $PROFILE[1]["Latitude"]; ?>" long="<?php echo $PROFILE[1]["Longitude"]; ?>" 
                                        subDistrinct="<?php echo $PROFILE[1]["subDistrinct"] ?>"></label>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-header card-bg" style="height: 55px;">
                        <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>; top: 19px; position: absolute;">
                        ตำแหน่งที่อยู่และสวนของเกษตรกร</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-12 mb-2">
                                <div id="map" style="width:auto; height:742px"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg" style="color:<?=$color?>;">
                    <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>;">รายชื่อสวน</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ชื่อสวน</th>
                                    <th>จังหวัด</th>
                                    <th>อำเภอ</th>
                                    <th>จำนวนแปลง</th>
                                    <th>พื้นที่ปลูก</th>
                                    <th>จำนวนต้นไม้</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ชื่อสวน</th>
                                    <th>จังหวัด</th>
                                    <th>อำเภอ</th>
                                    <th>จำนวนแปลง</th>
                                    <th>พื้นที่ปลูก</th>
                                    <th>จำนวนต้นไม้</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <label id="size" hidden size ="<?php echo sizeof($FARM); ?>"></label>

                            <?php
                            if ($FARM != 0) {
                                for ($i = 1; $i < sizeof($FARM); $i++) {
                            ?>
                                    <tr class="<?php echo $i; ?>">
                                        <td><a href="./../OilPalmAreaList/OilPalmAreaListDetail.php?fmid=<?php echo $FARM[$i]["FMID"]; ?>"><?php echo $FARM[$i]['Name']; ?></a></td>
                                        <td><?php echo $FARM[$i]['Province']; ?></td>
                                        <td><?php echo $FARM[$i]['Distrinct']; ?></td>
                                        <td class = "text-right"><?php echo $FARM[$i]['NumSubFarm']; ?> แปลง</td>
                                        <td class = "text-right"><?php echo $FARM[$i]['AreaRai']; ?> ไร่ <?php echo $FARM[$i]['AreaNgan']; ?> งาน</td>
                                        <td class = "text-right"><?php echo $FARM[$i]['NumTree']; ?> ต้น</td>
                                        <label class="click-map" hidden id="<?php echo $i; ?>" subDistrinct="<?php echo $FARM[$i]["subDistrinct"]; ?>" 
                                        AD3ID="<?php echo $FARM[$i]["AD3ID"]; ?>" la="<?php echo $FARM[$i]["Latitude"]; ?>" 
                                        long="<?php echo $FARM[$i]["Longitude"]; ?>" ></label>
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
        </div>
    </div>

    <?php include_once("../layout/LayoutFooter.php"); ?>
    <script src="FarmerListDetail.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>
    

