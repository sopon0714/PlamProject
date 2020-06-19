    <?php

    $FARM = getFarm();
    $PESTTYPE = getPestType();
    ?>

    <!------------ add Modal ------------>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <form method="post" id="formAdd" name="formAdd" action="manage.php">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                        <h4 class="modal-title">เพิ่มการ<?php echo $head; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="main">
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>วันที่<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">

                                    <div class="input-group">
                                        <input type="date" class="form-control" data-toggle="datepicker" id="date"
                                        fmid=<?php echo $DATA[0]['FMID']; ?>    
                                        name="date" required>
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-4" hidden>
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากสวน<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="farm" name="farm" required>
                                        <option selected value=''>เลือกสวน</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากแปลง<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="subfarm" name="subfarm" required>
                                        <option selected value=''>เลือกแปลง</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>หมายเหตุ</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <textarea name="note" class="form-control" id="note" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>รูปภาพ<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <div class="grid-img-multiple" id="grid-pic-style-char">
                                        <div class="img-reletive">
                                            <img width="100px" height="100px"
                                                src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg"
                                                width="50px" height="50px" alt="">
                                            <input type="file" id="pic-style-char" name="picstyle_insert[]"
                                                accept=".jpg,.png" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="pestAlarmID" id="pestAlarmID" value="0" />
                            <!-- <input type="hidden" name="farm" id="farm" value="<?php echo $DATA[0]['DIMfarmID']; ?>" /> -->
                            <input type="hidden" name="typePage" id="typePage" value="2" />
                            <input type="hidden" name="fmid" id="fmid" value="<?php echo $farmID; ?>" />

                        </div>
                        <div class="crop-img">
                            <center>
                                <div class="center-block upload-demo2"></div>
                            </center>
                        </div>
                        <input type="hidden" id="pic" name="pic" value="">

                        <input type="hidden" id="request" name="request" value="insert" />
                        <div class="modal-footer normal-button">
                            <button id="save" name="save" type="submit" class="btn btn-success">ยืนยัน</button>
                            <button id="cancel" type="button" class="btn btn-danger"
                                data-dismiss="modal">ยกเลิก</button>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <form method="post" id="formEdit" name="formEdit" action="manage.php">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                        <h4 class="modal-title">รายละเอียดการ<?php echo $head; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="main-edit">
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>วันที่</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <label for="" id="e_date" name="e_date"></label>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากสวน</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <label for="" id="e_farm" name="e_farm"></label>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากแปลง</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <label for="" id="e_subfarm" name="e_subfarm"></label>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>หมายเหตุ</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <p name="e_note" id="e_note"></p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>รูปภาพ</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <div class="grid-img-multiple" id="grid-pic-style-char-edit">
                                        <div class="img-reletive">
                                            <img width="100px" height="100px"
                                                src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg"
                                                width="50px" height="50px" alt="">
                                            <input type="file" id="pic-style-char-edit" name="picstyle_insert[]"
                                                accept=".jpg,.png" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="crop-img-edit">
                            <center>
                                <div class="center-block upload-demo2-edit"></div>
                            </center>
                        </div>
                        <input type="hidden" id="request" name="request" value="update" />
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
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
                    <h4 class="modal-title">รูปภาพการ<?php echo $head; ?></h4>
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
