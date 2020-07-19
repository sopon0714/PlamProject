<?php 
require_once("../../dbConnect.php");

if(isset($_POST['request'])){
    $request = $_POST['request'];

    switch($request){
        case 'search' :

            $AVERAGE = 70;
            $year = $_POST['year'];
            $province = $_POST['province'];
            $distrinct = $_POST['distrinct'];
            $farmer = $_POST['farmer'];
            $harvest = $_POST['harvest'];
            $fertilizer = $_POST['fertilizer'];
            $minwater = $_POST['minwater'];
            $maxwater = $_POST['maxwater'];
            $minlack = $_POST['minlack'];
            $maxlack = $_POST['maxlack'];
            $mincutbranch = $_POST['mincutbranch'];
            $maxcutbranch = $_POST['maxcutbranch'];
            $pesttype = $_POST['pesttype'];

            $lack = $_POST['lack'];
            $water = $_POST['water'];
            $cutbranch = $_POST['cutbranch'];

            $sqlAllSubfarm = " SELECT DISTINCT(t1.DIMSubfID) AS DIMsubFID FROM (
                SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                WHERE `dim-farm`.`IsFarm` = 0
                GROUP BY `dim-farm`.`dbID` )AS t1";

            $sql = "SELECT DISTINCT(t1.DIMSubfID) AS DIMsubFID FROM (
                SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                JOIN  `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                WHERE `dim-farm`.`IsFarm` = 0 ";
            if($province != 0) $sql = $sql." AND `dim-address`.dbprovID = $province";
            if($distrinct != 0) $sql = $sql." AND `dim-address`.dbDistID = $distrinct";

            $sql = $sql . " GROUP BY `dim-farm`.`ID` )AS t1";
            $data_pro_dist = selectData($sql);
            // print_r($data_pro_dist);
            $data_pro_dist = toDBID($data_pro_dist);

            // print_r($sql);
            // print_r($data_pro_dist);

            $sql = "SELECT t1.DIMSubfID AS DIMsubFID FROM (
                SELECT MAX(`log-farm`.`ID`)AS lid, `log-farm`.`DIMSubfID` FROM `log-farm` 
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
                WHERE `dim-user`.`Type` = 'F' ";
            if(trim($farmer) != '') $sql = $sql . " AND `dim-user`.`FullName` LIKE '%".$farmer."%'";
                $sql = $sql." GROUP BY `dim-farm`.`ID`)AS t1";
            if(trim($farmer) == '')  $sql = $sqlAllSubfarm;
            $data_farmer= selectData($sql);
            // print_r($data_farmer);
            $data_farmer = toDBID($data_farmer);

            // print_r($sql);
            // print_r($data_farmer);

            if($year == 0 && $pesttype == 0){
                $sql = $sqlAllSubfarm;
            }else{
                $sql = "SELECT DISTINCT(`log-pestalarm`.`DIMsubFID`) FROM `log-pestalarm`
                JOIN `dim-pest` ON `dim-pest`.`ID` = `log-pestalarm`.`DIMpestID`
                JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
                WHERE `log-pestalarm`.`isDelete` = 0 ";
                if($year != 0) $sql = $sql . " AND `dim-time`.`Year2`='$year'";
                if($pesttype != 0) $sql = $sql . " AND  `dim-pest`.`dbpestTID` = '$pesttype'";
            }
            $data_pesttype= selectData($sql);
            // print_r($data_pesttype);
            $data_pesttype = toDBID($data_pesttype);
            // print_r($sql);
            // print_r($data_pesttype);
            
            $result = array_intersect($data_pro_dist,$data_farmer);
            $result = array_intersect($result,$data_pesttype);

            // print_r($result);

            $datamap = dataForMap($result);

            print_r(json_encode($datamap));
        break;
        
    }
}
function dataForMap($array){
    $datamap = array();
    $size = key(array_slice($array, -1, 1, true));

    for($i = 0 ; $i<=$size ; $i++){

        if(isset($array[$i])){
            $dbID = $array[$i];
            $sql = "SELECT `log-farm`.`ID`, `dim-farm`.`dbID`,`log-farm`.`Latitude`,`log-farm`.`Longitude`,
            `dim-farm`.`Name`,`dim-address`.`Distrinct`,`dim-address`.`Province`,
            if(`log-farm`.`EndT`IS  NULL,0,`log-farm`.`EndT` )AS EndT
            FROM `log-farm`
            JOIN `dim-farm` ON   `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE `log-farm`.`ID` IN 
            (SELECT MAX(`log-farm`.`ID`)AS ID FROM `log-farm` 
            JOIN `dim-farm` ON   `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
            WHERE `dim-farm`.`dbID`= '$dbID' )";
    
            $data = selectData($sql);

            $sql = "SELECT `db-subfarm`.`FSID`,`db-subfarm`.`FMID` FROM `db-subfarm` 
            WHERE `db-subfarm`.`FSID` = '$dbID'";
            $data1 = selectData($sql);
            if($data1[0]['numrow'] != 0){
                $data[1]['FSID'] = $data1[1]['FSID'];
                $data[1]['FMID'] = $data1[1]['FMID'];
            }

            $datamap[] = $data[1];
        }
        
    }
    return $datamap;

}
function checkDateMINMAX($arr,$min,$max){
    // echo "min ".$min;
    // echo "/max ".$max;
    $k = 1;
    $array = array();
    $array[0]['numrow'] = 0;
    $size = key(array_slice($arr, -1, 1, true));
    for($i=1;$i<=$size;$i++){
        if(isset($arr[$i])){
            // echo "arr ".$arr[$i];

            if($arr[$i] >= $min && $arr[$i] <= $max){
                $array[$k]['DIMsubFID'] = $i;
                $array[0]['numrow']++;
                $k++;
            }
        }
    }
    return $array;
}
function countDay($arr){
    $array = array();
    $today = date("Y-m-d");
    $size = key(array_slice($arr, -1, 1, true));
    for($i=1;$i<=$size;$i++){
        if(isset($arr[$i])){
            $array[$i] = datediff($today,$arr[$i]); 
        }
    }
    return $array;
}
function datediff($start, $end ) {
    // echo 'start = '.$start.'//';
    // echo 'end = '.$end.'//';

    $datediff = strtotime($start) - strtotime($end);
    return floor($datediff / (60 * 60 * 24));
}
function toArray($array1){
    $array = array();
    for($i=1;$i<=$array1[0]['numrow'];$i++){
        $array[$array1[$i]['DIMsubFID']] = $array1[$i]['max_date'];
    }
    return $array;
}
function compare($array1,$array2){
    $array = array();
    $size = key(array_slice($array1, -1, 1, true));
    // echo 'size = '.$size.'//';
    for($i=1;$i<=$size;$i++){
        if(isset($array1[$i]) && isset($array2[$i])){
            // echo $array1[$i]."+++";
            // echo $array2[$i]."+++";

            $max = max($array1[$i],$array2[$i]);
            $array[$i] = $max;
        }

        if(isset($array1[$i]) && !isset($array2[$i])){
            $array[$i] = $array1[$i];
        }
    }
    return $array;
}
function toDBID($array){
    $arr = array();
 
    for($i = 1;$i <= $array[0]['numrow'] ;$i++){
        $subFID = $array[$i]['DIMsubFID'];
        $sql = "SELECT  `dim-farm`.`dbID` FROM  `dim-farm` WHERE  `dim-farm`.`ID` = '$subFID'";
        $data = selectData($sql);
        $arr[] = $data[1]['dbID'];
    }
    return array_unique($arr);

}
?>