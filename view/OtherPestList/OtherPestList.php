<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "InsectList";
include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$OTHERPEST = getOhterPest();
?>

<head>
    <link rel="stylesheet" href="../../css/insect admin/readmore.css">
    <link rel="stylesheet" href="../../css/insect admin/stylePest.css">
</head>

<body>
    <div class="container">

        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อศัตรูพืชอื่นๆ</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="padding-top:20px;color:#006664">รายชื่อศัตรูพืชอื่นๆ</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-3 col-12 mb-4">
                <div class="card border-left-primary card-color-one shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold  text-uppercase mb-1">จำนวนศัตรูพืช</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php print(getCountOhterPest()); ?> ชนิด</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bug fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-12 mb-4">
                <div class="card border-left-primary card-color-four shadow h-100 py-2" id="addInsect" style="cursor:pointer;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center" role="button" id="addInsect" data-toggle="modal" data-target="#insert" aria-haspopup="true" aria-expanded="false">
                            <div class="col mr-2">
                                <div class="font-weight-bold  text-uppercase mb-1">เพิ่มศัตรูพืช</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">+1 ชนิด</div>
                            </div>
                            <div class="col-auto">
                                <!-- <i class="material-icons icon-big">add_location</i> -->
                                <i class="fas fa-plus-square fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow mb-4" id="card-pest">

            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold" style="color:#006664">รายละเอียดศัตรูพืช</h6>
                <div class="dropdown no-arrow" align="right">
                    <!-- manage button -->
                    <button type="button" class="btn btn-warning btn-sm btn_edit" pid="<?= $OTHERPEST['info'][1]["PID"]; ?>" name="<?= $OTHERPEST['info'][1]["Name"]; ?>" alias="<?= $OTHERPEST['info'][1]["Alias"]; ?>" charstyle="<?= $OTHERPEST['info'][1]["Charactor"]; ?>" dangerstyle="<?= $OTHERPEST['info'][1]["Danger"]; ?>" data-icon="<?= $OTHERPEST['info'][1]["Icon"]; ?>" numPicChar="<?= $OTHERPEST['info'][1]["NumPicChar"]; ?>" numPicDanger="<?= $OTHERPEST['info'][1]["NumPicDanger"]; ?>">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button type="button" class="btn btn-danger btn-sm" onclick="delfunction('<?= $OTHERPEST['info'][1]["PID"]; ?>' , '<?= $OTHERPEST['info'][1]["Alias"]; ?>')">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <div class="body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 " style="text-align: center;">
                            <div style="text-align: center;">
                                <img src='<?php echo "../../icon/pest/" . $OTHERPEST['info'][1]["PID"] . "/" . $OTHERPEST['info'][1]["Icon"]; ?>' width="120" height="120" alt="User" style="border-radius: 100px;">
                                <br><br>
                            </div>
                            <h6 class="m-0 font-weight-bold" style="color:#006664">ชื่อ : <?= $OTHERPEST['info'][1]["Alias"]; ?></h6>
                            <h6 class="m-0 font-weight-bold" style="color:#006664">ชื่อทางการ : <?= $OTHERPEST['info'][1]["Name"]; ?> </h6>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h6 class="m-0 font-weight-bold" style="color:#006664">ลักษณะทั่วไป</h6>
                            <span class="more">
                                <?= $OTHERPEST['info'][1]["Charactor"]; ?>
                            </span>
                            <br>
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" id="silder">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="d-block w-100" src='<?php echo "../../picture/Pest/other/style/" . $OTHERPEST['info'][1]["PID"] . "/" . $OTHERPEST['info'][1]["Icon"]; ?>' style="height:200px;">

                                    </div>
                                    <?php for ($style_index = 0; $style_index < $OTHERPEST['info'][1]["NumPicChar"] - 1; $style_index++) { ?>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src='<?php echo "../../picture/Pest/other/style/" . $OTHERPEST['info'][1]["PID"] . "/" . $style_index . "_" . $OTHERPEST['info'][1]["Icon"]; ?> ' style="height:200px;">
                                        </div>
                                    <?php } ?>
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
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h6 class="m-0 font-weight-bold" style="color:#006664">อันตรายของศัตรูพืช</h6>
                            <span class="more">
                                <?= $OTHERPEST['info'][1]["Danger"]; ?>
                            </span>
                            <br>
                            <div id="carouselExampleControls2" class="carousel slide" data-ride="carousel" id="silder">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="d-block w-100" src='<?php echo "../../picture/Pest/other/danger/" . $OTHERPEST['info'][1]["PID"] . "/" . $OTHERPEST['info'][1]["Icon"]; ?> ' style="height:200px;">

                                    </div>
                                    <?php for ($danger_index = 0; $danger_index < $OTHERPEST['info'][1]["NumPicDanger"] - 1; $danger_index++) { ?>
                                        <div class="carousel-item">
                                            <img class="d-block w-100" src='<?php echo "../../picture/Pest/other/danger/" . $OTHERPEST['info'][1]["PID"] . "/" . $danger_index . "_" . $OTHERPEST['info'][1]["Icon"]; ?>' style="height:200px;">
                                        </div>
                                    <?php } ?>
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
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div>
            <div class="row">
                <?php
                for ($i = 1; $i <= $OTHERPEST['data'][0]['numrow']; $i++) {
                    $url = "window.location.href='./OtherPestList.php?id=" . $OTHERPEST['data'][$i]["PID"] . "'";
                    if ($OTHERPEST['selectedID'] == $OTHERPEST['data'][$i]["PID"]) {
                        $cardStyle = 'style="background-color:#006664;"';
                        $fontStyle = 'style="color:#ffffff"';
                    } else {
                        $cardStyle = '';
                        $fontStyle = 'style="color:#006664"';
                    }
                ?>

                    <div class="col-xl-3 col-12 mb-4">
                        <div class="card border-left-primary card-color-three shadow h-100 py-2" onclick="<?php echo $url; ?>" style="cursor:pointer;">
                            <div class="card-body" <?php echo $cardStyle; ?>>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="text-align: center;">
                                        <img src=<?php echo $src = "../../icon/pest/" . $OTHERPEST['data'][$i]["PID"] . "/" . $OTHERPEST['data'][$i]["Icon"]; ?> width="100" height="100" alt="User" style="border-radius: 100px;">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-pest" id="changeInsect" style="text-align: center;">
                                        <h6 <?php echo $fontStyle; ?>>
                                            <br><?php echo $OTHERPEST['data'][$i]["Alias"]; ?><br>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>

        <div class="Modal"> </div>

    </div>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

    <?php include_once("../layout/LayoutFooter.php"); ?>
    <?php require("otherPestListModal.php"); ?>

    <script src="./PestList.js"></script>


    <script>
        $("#looks").val("xxxxxxxxxxx")
        $('#danger').val("xxxxxxxxxxx")
    </script>

</body>

</html>