<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "FertilizerList";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$INFOFertilizer = getFertilizer();
?>
<style>
    .showDetailNutr:hover {
        color: #3651D0;
        cursor: pointer;
    }
</style>
<div class="container">

    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อปุ๋ย</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">รายชื่อปุ๋ย</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <?php
        creatCard("card-color-one",   "จำนวนปุ๋ย", getCountFertilizer() . " ชนิด", "waves");
        ?>

        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-four shadow h-100 py-2" id="addFer" style="cursor:pointer;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">เพิ่มชนิดปุ๋ย</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">+1 ชนิด</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">add_location</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- <form action="OtherUsersList.php?isSearch=1" method="post">
        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseOne" <?php
                                                                                                                                if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                                                                                                                                    echo 'aria-expanded="true"';
                                                                                                                                else
                                                                                                                                    echo 'aria-expanded="false"';
                                                                                                                                ?> aria-controls="collapseOne" style="cursor:pointer; background-color: <?= $color ?>; color: white;">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" <?php
                                        if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                                            echo 'class="collapse show"';
                                        else
                                            echo 'class="collapse"';
                                        ?> aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card-body" style="background-color: white;">


                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                            </div>
                            <div class="col-xl-6 col-12">
                                <button type="submit" id="btn_pass" class="btn btn-success btn-sm form-control">ค้นหา</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header card-header-table py-3">
            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อปุ๋ยในระบบ</span>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ชื่อปุ๋ย</th>
                            <th>ชื่อย่อ</th>
                            <th>ธาตุอาหารหลัก</th>
                            <th>ธาตุอาหารรอง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ชื่อปุ๋ย</th>
                            <th>ชื่อย่อ</th>
                            <th>ธาตุอาหารหลัก</th>
                            <th>ธาตุอาหารรอง</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        foreach ($INFOFertilizer as $Fertilizer) {
                            echo " <tr>";
                            echo "   <td>{$Fertilizer['Name']}</td>
                                     <td>{$Fertilizer['Alias']}</td>";
                            if (count($Fertilizer['composition']['หลัก']) == 0) {
                                echo "   <td class=\"text-center\">-</td>";
                            } else {
                                $i = 0;
                                $text = "";
                                $numNutrMain = count($Fertilizer['composition']['หลัก']);
                                foreach ($Fertilizer['composition']['หลัก'] as $Nutr) {
                                    if ($i == 3) {
                                        break;
                                    }
                                    $text .= $Nutr . ",";
                                    $i++;
                                }
                                if ($numNutrMain <= 3) {
                                    $text = substr("$text", 0, -1);
                                } else {
                                    $text .= "  . . .";
                                }
                                echo "   <td class=\"text-center showDetailNutr\" FID=\"{$Fertilizer['FID']}\" TypeNutr=\"หลัก\">$text</td>";
                            }
                            if (count($Fertilizer['composition']['รอง']) == 0) {
                                echo "   <td class=\"text-center\">-</td>";
                            } else {
                                $i = 0;
                                $text = "";
                                $numNutrSub = count($Fertilizer['composition']['รอง']);
                                foreach ($Fertilizer['composition']['รอง'] as $Nutr) {
                                    if ($i == 3) {
                                        break;
                                    }
                                    $text .= $Nutr . ",";
                                    $i++;
                                }
                                if ($numNutrSub <= 3) {
                                    $text = substr("$text", 0, -1);
                                } else {
                                    $text .= "  . . .";
                                }
                                echo "   <td class=\"text-center showDetailNutr\" FID=\"{$Fertilizer['FID']}\" TypeNutr=\"รอง\">$text</td>";
                            }
                            echo "   <td class=\"text-center\"> 
                                        <button type=\"button\" class=\"btn btn-danger btn-sm tt btndel\" data-toggle=\"tooltip\" title=\"ลบ\"  FID=\"{$Fertilizer['FID']}\" FName=\"{$Fertilizer['Name']}\">
                                        <i class=\"fas fa-trash-alt\"></i></button>
                                     </td>";
                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("FertilizerListModal.php"); ?>
<script src="FertilizerList.js"></script>