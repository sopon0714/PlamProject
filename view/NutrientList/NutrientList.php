<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "NutrientList";
include_once("./../../query/query.php");
?>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

<?php include_once("../layout/LayoutHeader.php"); ?>
<link href="../../lib/croppie/croppie.css" rel="stylesheet" type="text/css">

<!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
<link rel="stylesheet" href="style.css">

<style>
    .header-fertilizer {
        background-color: <?= $color ?>;
    }

    .head-link {
        color: #AEBD18;
    }

    .card-color-one {
        border-left-color: #E91E63 !important;
        color: #E91E63 !important;
    }

    .card-color-four {
        border-left-color: #00BCD4 !important;
        color: #00BCD4 !important;
    }

    .header-modal {
        background-color: <?= $color ?>;
        color: white;
    }

    #card1,
    #header-card {
        background-color: <?= $color ?>;
    }

    .head-link {
        color: <?= $color ?>;
    }
</style>
<div class="container bg">

    <?php require("headF.php"); ?>
    <?php require("bodyF.php"); ?>
    <?php require("modalInsert.php"); ?>
    <?php require("modalUpdate.php"); ?>

</div>

<?php include_once("../layout/LayoutFooter.php"); ?>

<html>

<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <div id="upload-demo" class="center-block"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>

</html>
<script>
    $(document).ready(function() {

        $('.tt').tooltip();

    });
</script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<!-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script> -->

<!-- BS JavaScript -->
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

<!-- jQuery -->

<script src="../../lib/croppie/croppie.js"></script>
<script src="edit_Nutrient.js"></script>