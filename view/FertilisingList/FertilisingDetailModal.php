<!------------ Start Modal ------------>
<div class="modal fade" id="modal-1">
    <form method="post" id="formAdd" name="formAdd" action="manage.php">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>">
                    <h4 class="modal-title">เพิ่มการใส่ปุ๋ย</h4>
                </div>
                <div class="modal-body" id="addModalBody1">
                    <div class="main">
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>วันที่</span>
                            </div>
                            <div class="col-xl-5 col-12">
                                <input type="date" name="date" class="form-control" id="date" required="" oninput="setCustomValidity('')">
                            </div>
                        </div>
                        <div class="row mb-4 show1">
                            <div class="col-xl-4 col-12 text-right">
                                <span>ชนิดปุ๋ย</span>
                            </div>
                            <div class="col-xl-5 col-12">
                                <select class="form-control" id="ferID" name="ferID" required="" oninput="setCustomValidity('')">
                                    <option selected value="0">เลือกชนิดปุ๋ย</option>
                                    <?php
                                    for ($i = 1; $i <= $FERLIST[0]['numrow']; $i++) {
                                        echo "<option value=\"{$FERLIST[$i]['ID']}\">{$FERLIST[$i]['Name']}({$FERLIST[$i]['Alias']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>หน่วย</span>
                            </div>
                            <div class="col-xl-5 col-12 ">
                                <input type="radio" id="Type" name="unit" class="radioType" checked value="1">
                                <label for="male">กิโลกรัม</label><br>
                                <input type="radio" name="unit" class="radioType" value="2">
                                <label for="female">กรัม</label><br>
                            </div>
                        </div>

                        <div class="row mb-4 show2">
                            <div class="col-xl-4 col-12 text-right">
                                <span>ปริมาณ</span>
                            </div>
                            <div class="col-xl-5 col-12">
                                <input type="number" id="Vol" name="Vol" class="form-control text-right" required="" oninput="setCustomValidity('')" min="0" step="0.01" value="0">
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
                        <input type="hidden" name="action" value="addFertilising">
                        <input type="hidden" name="FSID" value="<?php echo $fsid ?>">
                    </div>
                    <div class="crop-img">
                        <center>
                            <div class="center-block upload-demo2"></div>
                        </center>
                    </div>
                    <input type="hidden" id="pic" name="pic" value="">
                    <div class="modal-footer normal-button ">
                        <button type="submit" name="save" id="btn-submit" class="btn btn-success">ยืนยัน</button>
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
                <h4 class="modal-title">รูปภาพการใส่ปุ๋ย</h4>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>