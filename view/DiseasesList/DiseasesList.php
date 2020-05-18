<?php

session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "DiseasesList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$page = "DiseasesList.php";
$modal = "DiseasesListModal.php";

$DATAPEST = getDiseases();
$num = getCountDiseases();

$str_title1 = "รายชื่อโรคพืช";
$str_num = "จำนวนโรคพืช";
$str_add = "เพิ่มโรคพืช";
$str_title2 = "รายละเอียดโรคพืช";
$str_danger = "อันตรายของโรคพืช";

$path_style = "../../picture/pest/disease/style/";
$path_danger = "../../picture/pest/disease/danger/";


?>

<head>
    <link rel="stylesheet" href="../../css/insect admin/readmore.css">
    <link rel="stylesheet" href="../../css/insect admin/stylePest.css">
    <style>
        textarea {
            overflow-y: scroll;
            height: 100px;
            resize: none; 
        }
        .img-reletive input[type=file] {
            cursor: pointer;
            width: 100px;
            height: 100px;
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;"><?php echo $str_title1; ?></span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="padding-top:20px;color:#006664"><?php echo $str_title1; ?></a>
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
                                <div class="font-weight-bold  text-uppercase mb-1"><?php echo $str_num; ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $num; ?> ชนิด</div>
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
                                <div class="font-weight-bold  text-uppercase mb-1"><?php echo $str_add; ?></div>
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
                <h6 class="m-0 font-weight-bold" style="color:#006664"><?php echo $str_title2; ?></h6>
                <div class="dropdown no-arrow" align="right">
                    <!-- manage button -->
                    <button type="button" class="btn btn-warning btn-sm btn_edit" pid="<?= $DATAPEST['info'][1]["PID"]; ?>" name="<?= $DATAPEST['info'][1]["Name"]; ?>" alias="<?= $DATAPEST['info'][1]["Alias"]; ?>" charstyle="<?= $DATAPEST['info'][1]["Charactor"]; ?>" dangerstyle="<?= $DATAPEST['info'][1]["Danger"]; ?>" data-icon="<?= $DATAPEST['info'][1]["Icon"]; ?>" numPicChar="<?= $DATAPEST['info'][1]["NumPicChar"]; ?>" numPicDanger="<?= $DATAPEST['info'][1]["NumPicDanger"]; ?>">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="delfunction('<?= $DATAPEST['info'][1]["PID"]; ?>' , '<?= $DATAPEST['info'][1]["Alias"]; ?>')">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <div class="body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 " style="text-align: center;">
                            <div style="text-align: center;">
                                <img src='<?php echo "../../icon/pest/" . $DATAPEST['info'][1]["PID"] . "/" . $DATAPEST['info'][1]["Icon"]; ?>' width="120" height="120" alt="User" style="border-radius: 100px;">
                                <br><br>
                            </div>
                            <h6 class="m-0 font-weight-bold" style="color:#006664">ชื่อ : <?= $DATAPEST['info'][1]["Alias"]; ?></h6>
                            <h6 class="m-0 font-weight-bold" style="color:#006664">ชื่อทางการ : <?= $DATAPEST['info'][1]["Name"]; ?> </h6>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h6 class="m-0 font-weight-bold" style="color:#006664">ลักษณะทั่วไป</h6>
                            <span class="more">
                                <?= $DATAPEST['info'][1]["Charactor"]; ?>
                            </span>
                            <br><br>
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" id="silder">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <?php   $folder = $path_style.$DATAPEST['info'][1]["PID"];;
                                                $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
                                                $arr = array();
                                                foreach($objScan as $obj){
                                                    $type= strrchr($obj,".");
                                                    if($type == '.png' || $type == '.jpg' ){            
                                                        $arr[]= $obj;
                                                    }
                                                } 
                                        ?>
                                            <img class="d-block w-100" src='<?php echo $path_style . $DATAPEST['info'][1]["PID"] . "/". $arr[0]; ?>' style="height:200px;">

                                    </div>
                                    <?php for ($i = 1; $i < $DATAPEST['info'][1]["NumPicChar"]; $i++) { ?>
                                        <div class="carousel-item">
                                        
                                            <img class="d-block w-100" src='<?php echo $path_style . $DATAPEST['info'][1]["PID"] . "/" . $arr[$i]; ?>' style="height:200px;">
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
                            <h6 class="m-0 font-weight-bold" style="color:#006664"><?php echo $str_danger; ?></h6>
                            <span class="more">
                                <?= $DATAPEST['info'][1]["Danger"]; ?>
                            </span>
                            <br><br>
                            <div id="carouselExampleControls2" class="carousel slide" data-ride="carousel" id="silder">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <?php   $folder = $path_danger.$DATAPEST['info'][1]["PID"];;
                                                $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
                                                $arr = array();
                                                foreach($objScan as $obj){
                                                    $type= strrchr($obj,".");
                                                    if($type == '.png' || $type == '.jpg' ){            
                                                        $arr[]= $obj;
                                                    }
                                                } 
                                        ?>
                                        <img class="d-block w-100" src='<?php echo $path_danger . $DATAPEST['info'][1]["PID"] . "/". $arr[0]; ?> ' style="height:200px;">
                                    </div>
                                    <?php for ($i = 1; $i < $DATAPEST['info'][1]["NumPicDanger"]; $i++) { ?>
                                        <div class="carousel-item">
                                        
                                            <img class="d-block w-100" src='<?php echo $path_danger . $DATAPEST['info'][1]["PID"] . "/" . $arr[$i]; ?>' style="height:200px;">
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
                for ($i = 1; $i <= $DATAPEST['data'][0]['numrow']; $i++) {
                    $url = "window.location.href='./".$page."?id=" . $DATAPEST['data'][$i]["PID"] . "'";
                    if ($DATAPEST['selectedID'] == $DATAPEST['data'][$i]["PID"]) {
                        $cardStyle = 'style="background-color:#006664;"';
                        $fontStyle = 'style="color:#ffffff"';
                    } else {
                        $cardStyle = '';
                        $fontStyle = 'style="color:#006664"';
                    }
                ?>

                    <div class="col-xl-3 col-12 mb-4">
                        <div class="card border-left-primary card-color-three shadow h-100 py-2" <?php echo $cardStyle; ?> onclick="<?php echo $url; ?>" style="cursor:pointer;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="text-align: center;">
                                        <img src=<?php echo $src = "../../icon/pest/" . $DATAPEST['data'][$i]["PID"] . "/" . $DATAPEST['data'][$i]["Icon"]; ?> width="100" height="100" alt="User" style="border-radius: 100px;">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-pest" id="changeInsect" style="text-align: center;">
                                        <h6 <?php echo $fontStyle; ?>>
                                            <br><?php echo $DATAPEST['data'][$i]["Alias"]; ?><br>
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
    <?php require($modal); ?>

    <script src="./PestList.js"></script>

    <!-- <script src="InsectListModal.js"></script> -->

    <script>
        $("#looks").val("xxxxxxxxxxx")
        $('#danger').val("xxxxxxxxxxx")
    </script>

</body>

</html>