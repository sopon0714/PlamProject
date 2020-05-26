<?php
$PROVINCE = getProvince();
$DISTRINCT = getDistrinctInProvince($INFOFARM[1]['AD1ID']);
$SUBDISTRINCT = getSubDistrinctInDistrinct($INFOFARM[1]['AD2ID']);
$FARMER = getAllFarmer();
?>
<div class="modal fade" id="editDetailModal">
    <form action="./manage.php" method="post" enctype="multipart/form-data" id="form-edit-farm">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class=" modal-title">แก้ไขสวนปาล์ม</h4>
                </div>
                <div class="modal-body" id="addModalBody">
                    <form action="#" method="post">
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ชื่อสวนปาล์ม</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" name="namefarm" value=" <?= $INFOFARM[1]['Name'] ?>" required="" oninput="setCustomValidity('')" placeholder="กรุณากรอกชื่อสวนปาล์ม">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ชื่อย่อสวนปาล์ม</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" name="aliasfarm" value=" <?= $INFOFARM[1]['Alias'] ?>" required="" oninput="setCustomValidity('')" placeholder="กรุณากรอกชื่อย่อสวนปาล์ม">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ที่อยู่ </span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" name="addfarm" value=" <?= $INFOFARM[1]['Address'] ?>" required="" oninput="setCustomValidity('')" placeholder="กรุณากรอกที่อยู่สวนปาล์ม">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right">
                                <span>จังหวัด<span class="text-danger"> *</span></span>
                            </div>
                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <select id="province" name="province" class="form-control">
                                    <option selected value='0' disabled=""> เลือกจังหวัด</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                        echo   "<option value='{$PROVINCE[$i]['AD1ID']}'";
                                        if ($INFOFARM[1]['AD1ID'] == $PROVINCE[$i]['AD1ID']) {
                                            echo "selected";
                                        }
                                        echo ">  {$PROVINCE[$i]['Province']} </option>";
                                    }
                                    ?>
                                </select>

                            </div>

                        </div>

                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>อำเภอ</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select name="distrinct" class="form-control" id="distrinct">
                                    <option selected value=0 disabled="">เลือกอำเภอ</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($DISTRINCT); $i++) {
                                        echo   "<option value='{$DISTRINCT[$i]['AD2ID']}'";
                                        if ($INFOFARM[1]['AD2ID'] == $DISTRINCT[$i]['AD2ID']) {
                                            echo "selected";
                                        }
                                        echo ">  {$DISTRINCT[$i]['Distrinct']} </option>";
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ตำบล</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select name="subdistrinct" id="subdistrinct" class="form-control">
                                    <option selected value=0 disabled="">เลือกตำบล</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($SUBDISTRINCT); $i++) {
                                        echo   "<option value='{$SUBDISTRINCT[$i]['AD3ID']}'";
                                        if ($INFOFARM[1]['AD3ID'] == $SUBDISTRINCT[$i]['AD3ID']) {
                                            echo "selected";
                                        }
                                        echo ">  {$SUBDISTRINCT[$i]['subDistrinct']} </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>เจ้าของสวนปาล์ม</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select class="form-control" id="farmer" name="farmer">
                                    <option selected disabled="" value="0">เลือกเจ้าของสวน</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($FARMER); $i++) {
                                        echo "<option value=\"{$FARMER[$i]['UFID']}\" ";
                                        if ($INFOFARM[1]['UFID'] == $FARMER[$i]['UFID']) {
                                            echo "selected";
                                        }
                                        echo ">{$FARMER[$i]['FirstName']} {$FARMER[$i]['LastName']}</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="IDFarm" value="<?= $fmid ?>">
                        <input type="hidden" name="editFarm">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-md editFarm" style="float:right;">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>

            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
    <form method="post" id="formPhoto" name="formPhoto" action="./manage.php">
        <div class=" modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class="modal-title">เปลี่ยนรูปไอคอนสวนปาล์ม</h4>
                </div>
                <div class="modal-body" id="passModalBody">
                    <div class="form-group divHolder">
                        <div class="" align="center">
                            <div class="UI">
                                <input id='pic-logo' type='file' class='item-img file center-block' name='icon_insert' required />
                                <img id="img-insert" src="https://via.placeholder.com/200x200.png" alt="" width="200" height="200">
                                <!-- <div id="upload-demo" class="center-block"></div> -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group divCrop">

                        <center>
                            <div id="upload-demo" class="center-block"></div>
                        </center>
                    </div>
                    <input type="text" hidden class="form-control" name="FMID" id="FMID" value="<?= $fmid ?>">
                    <input type="hidden" id="request" name="action" value="changphotoFarm">
                    <input type="hidden" id="imagebase64" name="imagebase64">
                </div>
                <!-- end  body---------------------------------------------- -->
                <div class="modal-footer footer-insert">
                    <div class="buttonSubmit">
                        <button type="submit" class="btn btn-success waves-effect insertSubmit" id="add-data">ยืนยัน</button>
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">ยกเลิก</button>
                    </div>
                    <div class="buttonCrop">
                        <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
                        <button type="button" class="btn btn-default" id="cancelCrop">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="addSubFarmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="form-signin" method="POST" action='./manage.php'>
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class="modal-title">เพิ่มแปลง</h4>
                </div>
                <div class="modal-body" id="addModalBody">
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ชื่อแปลง</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="text" class="form-control" id="nameSubfarm" name="nameSubfarm" required="" oninput="setCustomValidity('')" placeholder="กรุณากรอกชื่อแปลง">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ชื่อย่อแปลง</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="text" class="form-control" id="initialsSubfarm" name="initialsSubfarm" required="" oninput="setCustomValidity('')" placeholder="กรุณากรอกชื่อย่อแปลง">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>พื้นที่</span>
                        </div>
                        <div class="col-xl-8 col-12">
                            <div class="row">
                                <div class="col-3">
                                    <input type="number" class="form-control text-right" id="AreaRai" name="AreaRai" min="0" value="0" required="" oninput="setCustomValidity('')">
                                </div>
                                <div class="col-3 mt-1">
                                    <span>ไร่</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-3">
                                    <input type="number" class="form-control text-right" id="AreaNgan" name="AreaNgan" min="0" value="0" required="" oninput="setCustomValidity('')">
                                </div>
                                <div class="col-3 mt-1">
                                    <span>งาน</span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-3">
                                    <input type="number" class="form-control text-right" id="AreaWa" name="AreaWa" min="0" value="0" required="" oninput="setCustomValidity('')">
                                </div>
                                <div class="col-3 mt-1">
                                    <span>วา</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ที่อยู่ </span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="text" class="form-control" name="addfarmSF" required="" oninput="setCustomValidity('')" placeholder="กรุณากรอกที่อยู่แปลง">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 text-right">
                            <span>จังหวัด</span>
                        </div>
                        <div class="col-lg-5 col-md-8 col-sm-12 col-xs-12">
                            <select id="provinceSF" name="provinceSF" class="form-control" required="" oninput="setCustomValidity('')">
                                <option selected value='0' disabled=""> เลือกจังหวัด</option>
                                <?php
                                for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                ?>
                                    <option value="<?php echo $PROVINCE[$i]["AD1ID"]; ?>"> <?php echo $PROVINCE[$i]["Province"]; ?> </option>
                                <?php
                                }
                                ?>
                            </select>

                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>อำเภอ</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <select name="distrinctSF" class="form-control" id="distrinctSF" required="" oninput="setCustomValidity('')">
                                <option selected value=0 disabled="">เลือกอำเภอ</option>

                            </select>

                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ตำบล</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <select name="subdistrinctSF" id="subdistrinctSF" class="form-control" required="" oninput="setCustomValidity('')">
                                <option selected value=0 disabled="">เลือกตำบล</option>

                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="fmid" value="<?= $fmid ?>">
                <input type="hidden" name="action" value="addSubFarm">
                <div class="modal-footer">
                    <button class="btn btn-success btn-md btn-add-subFarm" type="submit">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editMapModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal" style="background-color: <?= $color ?>">
                <h4 class=" modal-title">แก้ไขตำแหน่งสวน</h4>
            </div>
            <div class="modal-body" id="addModalBody">

                <div class="row mb-4">
                    <div id="map_area_edit" style="width:100%; height:300px;"></div>
                </div>
                <div class="row mb-4">
                    <div class="col-8 text-right">
                        <label style="color: red" id="erormap"> </label>

                    </div>
                </div>
                <input type="hidden" id="la" name="la" value="0">
                <input type="hidden" id="long" name="long" value="0">
                <input type="hidden" name="FMIDmap" id="FMIDmap" value="<?= $fmid ?>">
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn_submit_editMap">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>