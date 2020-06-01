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
                        <h4 class="modal-title">เพิ่มการตรวจพบศัตรูพืช</h4>
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
                                        <input type="date" class="form-control" data-toggle="datepicker" id="date" name="date" required>
                                        <!-- <div class="input-group-append">
                                            <button type="button"
                                                class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </button>
                                        </div> -->
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากสวน<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="farm" name="farm" required>
                                        <option selected value=''>เลือกสวน</option>
                                        <?php
                                        for ($i = 1; $i < sizeof($FARM); $i++) {  ?>
                                            <option value=<?php echo $FARM[$i]["FMID"]; ?>><?php echo $FARM[$i]["Name"]; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
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
                                    <span>ชนิดศัตรูพืช<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="pesttype" name="pesttype" required>
                                        <option selected value=''>เลือกชนิดศัตรูพืช</option>
                                        <?php
                                        for ($i = 1; $i < sizeof($PESTTYPE); $i++) {  ?>
                                            <option value=<?php echo $PESTTYPE[$i]["PTID"]; ?>>
                                                <?php echo $PESTTYPE[$i]["TypeTH"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 tex t-right">
                                    <span>ศัตรูพืช<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="pest" name="pest" required>
                                        <option selected value=''>เลือกศัตรูพืช</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ลักษณะ</span>
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
                                            <img width="100px" height="100px" src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
                                            <input type="file" id="pic-style-char" name="picstyle_insert[]" accept=".jpg,.png" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="pestAlarmID" id="pestAlarmID" value="0" />
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
                            <button id="cancel" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
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
                        <h4 class="modal-title">แก้ไขการตรวจพบศัตรูพืช</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="main-edit">
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>วันที่<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">

                                    <div class="input-group">
                                        <input type="date" class="form-control" data-toggle="datepicker" id="e_date" name="e_date" required>
                                        <!-- <div class="input-group-append">
                                            <button type="button"
                                                class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </button>
                                        </div> -->
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากสวน<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="e_farm" name="e_farm" required>
                                        <option selected value=''>เลือกสวน</option>
                                        <?php
                                        for ($i = 1; $i < sizeof($FARM); $i++) {  ?>
                                            <option value=<?php echo $FARM[$i]["FMID"]; ?>><?php echo $FARM[$i]["Name"]; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>จากแปลง<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="e_subfarm" name="e_subfarm" required>
                                        <option selected value=''>เลือกแปลง</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ชนิดศัตรูพืช<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="e_pesttype" name="e_pesttype" required>
                                        <option selected value=''>เลือกชนิดศัตรูพืช</option>
                                        <?php
                                        for ($i = 1; $i < sizeof($PESTTYPE); $i++) {  ?>
                                            <option value=<?php echo $PESTTYPE[$i]["PTID"]; ?>>
                                                <?php echo $PESTTYPE[$i]["TypeTH"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ศัตรูพืช<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <select class="form-control" id="e_pest" name="e_pest" required>
                                        <option selected value=''>เลือกศัตรูพืช</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>ลักษณะ</span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <textarea name="e_note" class="form-control" id="e_note" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-xl-3 col-12 text-right">
                                    <span>รูปภาพ<span class="text-danger"> *</span></span>
                                </div>
                                <div class="col-xl-8 col-10">
                                    <div class="grid-img-multiple" id="grid-pic-style-char-edit">
                                        <div class="img-reletive">
                                            <img width="100px" height="100px" src="https://ast.kaidee.com/blackpearl/v6.18.0/_next/static/images/gallery-filled-48x48-p30-6477f4477287e770745b82b7f1793745.svg" width="50px" height="50px" alt="">
                                            <input type="file" id="pic-style-char-edit" name="picstyle_insert[]" accept=".jpg,.png" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="pic-edit" name="pic-edit" value="">
                            <input type="hidden" id="old_pic-edit" name="old_pic-edit" value="">
                            <input type="hidden" name="e_pestAlarmID" id="e_pestAlarmID" value="0" />
                            <!-- <input type="hidden" name="old_date" id="old_date" value="" />
                            <input type="hidden" name="old_farm" id="old_farm" value="" />
                            <input type="hidden" name="old_subfarm" id="old_subfarm" value="" />
                            <input type="hidden" name="old_note" id="old_note" value="" />
                            <input type="hidden" name="old_pest" id="old_pest" value="" />
                            <input type="hidden" name="old_pesttype" id="old_pesttype" value="" /> -->

                        </div>
                        <div class="crop-img-edit">
                            <center>
                                <div class="center-block upload-demo2-edit"></div>
                            </center>
                        </div>
                        <input type="hidden" id="request" name="request" value="update" />
                        <div class="modal-footer normal-button-edit">
                            <button id="edit" type="submit" class="btn btn-success">ยืนยัน</button>
                            <button id="e_cancel" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        </div>
                        <div class="modal-footer crop-button-edit">
                            <button type="button" class="btn btn-success btn-crop-edit">ยืนยัน</button>
                            <button type="button" class="btn btn-danger btn-cancel-crop-edit">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="pest_data" role="dialog">
        <div class="modal-dialog modal-xl " role="document">
            <!-- modal-dialog-scrollable -->
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class="modal-title">ข้อมูลลักษณะทั่วไปของศัตรูพืช</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="infoModalBody">
                    <div class="body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 " style="text-align: center;">
                                    <div style="text-align: center;">
                                        <img id='data_icon' src='' width="120" height="120" alt="User" style="border-radius: 100px;">
                                        <br><br>
                                    </div>
                                    <h6 id="data_name" class="m-0 font-weight-bold" style="color:#006664"></h6>
                                    <h6 id="data_alias" class="m-0 font-weight-bold" style="color:#006664"></h6>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <!-- <h6 class="m-0 font-weight-bold" style="color:#006664">ลักษณะทั่วไป</h6> -->
                                    <!-- <br> -->
                                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" id="silder">
                                        <div id="data_char1" class="carousel-inner">

                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    <br>
                                    <h6 class="m-0 font-weight-bold" style="color:#006664">ลักษณะทั่วไป</h6><br>
                                    <span id="data_char2" class="more">
                                    </span>
                                    <br>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div id="carouselExampleControls2" class="carousel slide" data-ride="carousel" id="silder">
                                        <div id="data_dang1" class="carousel-inner">

                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleControls2" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleControls2" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    <br>
                                    <h6 class="m-0 font-weight-bold" style="color:#006664">อันตราย
                                    </h6><br>
                                    <span id="data_dang2" class="more">
                                    </span>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="picture" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class="modal-title">รูปภาพศัตรูพืช</h4>
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

    <div class="modal fade" id="data" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class="modal-title">ข้อมูลสำคัญของศัตรูพืช</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="noteModalBody">
                    <span id="n_note"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>