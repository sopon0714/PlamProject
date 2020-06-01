<!-- modal -->

<?php
$SUBFARM = getNameSubfarm($fmid);
?>
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
    <form method="post" id="formAdd" name="formAdd" action="manage.php">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>">
                    <h4 class="modal-title">เพิ่มผลผลิต</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="addModalBody">
                    <div class="main">
                        <div class="row mb-4 ">
                            <div class="col-xl-4 col-12 text-right">
                                <span>ชื่อแปลง</span>

                            </div>
                            <div class="col-xl-5 col-12">
                                <select id="sub" name="SubFarmID" class="form-control" required="" oninput="setCustomValidity('')">
                                    <option value="0" selected>เลือกแปลง</option>
                                    <?php
                                    for ($i = 1; $i < count($SUBFARM); $i++) {
                                        echo "<option value=\"{$SUBFARM[$i]['FSID']}\" >{$SUBFARM[$i]['Name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>วันที่เก็บผลผลิต</span>
                            </div>
                            <div class="col-xl-5 col-12">
                                <input type="date" name="date" class="form-control" id="date" required="" oninput="setCustomValidity('')">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>ผลผลิต</span>
                            </div>
                            <div class="col-xl-2 col-12">
                                <input type="number" name="weight" step="0.01" class="form-control text-right" id="weight" required="" value="0" min="0" oninput="setCustomValidity('')">
                            </div>
                            <div class="col-3 mt-1">
                                <span>กิโลกรัม</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>ราคาต่อกิโลกรัม</span>
                            </div>
                            <div class="col-xl-2 col-12 ">
                                <input type="number" name="UnitPrice" step="0.01" class="form-control text-right" id="UnitPrice" value="0" min="0" required="" oninput="setCustomValidity('')">
                                <input type="text" hidden class="form-control" name="action" value="insert">
                                <input type="text" hidden class="form-control" name="FMID" value="<?php echo $fmid; ?>">
                            </div>
                            <div class="col-3 mt-1">
                                <span>บาท</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>รูปภาพ</span>
                            </div>
                            <div class="col-xl-8 col-10">
                                <div class="grid-img-multiple" id="grid-pic-style-char">
                                    <div class="img-reletive">
                                        <img width="100px" height="100px" src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
                                        <input type="file" id="pic-style-char" name="picstyle_insert[]" accept=".jpg,.png" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="crop-img">
                        <center>
                            <div class="center-block upload-demo2"></div>
                        </center>
                    </div>
                    <input type="hidden" id="pic" name="pic" value="">
                    <div class="modal-footer normal-button ">
                        <button type="submit" name="save" id="save" class="btn btn-success">ยืนยัน</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                    </div>
                    <div class="modal-footer crop-button">
                        <button type="button" class="btn btn-success btn-crop">ยืนยัน</button>
                        <button type="button" class="btn btn-danger btn-cancel-crop">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="picture" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                <h4 class="modal-title">รูปภาพผลผลิต</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row margin-gal" id="fetchPhoto">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>