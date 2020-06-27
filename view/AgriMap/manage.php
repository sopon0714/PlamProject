<?php 
if(isset($_POST['request'])){
    $request = $_POST['request'];

    switch($request){
        case 'search' :
            $year = $_POST['year'];
            $province = $_POST['province'];
            $distrinct = $_POST['distrinct'];
            $farmer = $_POST['farmer'];
            $product = $_POST['product'];
            $fertilizer = $_POST['fertilizer'];
            $water = $_POST['water'];
            $waterlack = $_POST['waterlack'];
            $wash = $_POST['wash'];
            $pesttype = $_POST['pesttype'];

            // echo "year = ".$year;
            // echo "province = ".$province;
            // echo "distrinct = ".$distrinct;
            // echo "farmer = ".$farmer;
            // echo "product = ".$product;
            // echo "fertilizer = ".$fertilizer;
            // echo "water = ".$water;
            // echo "waterlack = ".$waterlack;
            // echo "wash = ".$wash;
            // echo "pesttype = ".$pesttype;

            

        break;
    }
}
?>