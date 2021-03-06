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
            // echo "maxCut = ".$maxcutbranch;

            $sql = "SELECT DISTINCT(t1.DIMsubFID)AS DIMsubFID FROM(
                SELECT * FROM(
                SELECT DISTINCT(Year2),`log-fertilising`.`DIMsubFID` FROM `log-fertilising` 
                JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
                WHERE `log-fertilising`.`isDelete` = 0 
                UNION
                SELECT DISTINCT(Year2),`log-harvest`.`DIMsubFID` FROM `log-harvest` 
                JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
                WHERE `log-harvest`.`isDelete` = 0 
                UNION
                SELECT DISTINCT(Year2),`log-watering`.`DIMsubFID` FROM `log-watering` 
                JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
                WHERE `log-watering`.`isDelete` = 0 
                UNION
                SELECT DISTINCT(Year2),`log-raining`.`DIMsubFID` FROM `log-raining` 
                JOIN `dim-time` ON `dim-time`.`ID` = `log-raining`.`DIMdateID`
                WHERE `log-raining`.`isDelete` = 0 
                UNION
                SELECT DISTINCT(Year2), `log-pestalarm`.`DIMsubFID` FROM `log-pestalarm` 
                JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
                WHERE `log-pestalarm`.`isDelete` = 0 
                UNION
                SELECT DISTINCT(Year2), `log-activity`.`DIMsubFID` FROM `log-activity` 
                JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                WHERE `log-activity`.`isDelete` = 0 
                )AS t0 
                WHERE 1";
            if($year != 0){ 
                $sql = $sql." AND t0.Year2 = $year)AS t1";
            }else { 
                $sql = $sqlAllSubfarm;
            }
            $data_year = selectData($sql);
            // print_r($data_year);
            $data_year = toDBID($data_year);

            // print_r($sql);
            print_r($data_year);

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

            print_r($sql);
            print_r($data_pro_dist);

            if($harvest == 0){
                $sql = $sqlAllSubfarm;
            }else if($harvest == 3){
                $sql = "SELECT t1.DIMSubfID AS DIMsubFID FROM (
                    SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    WHERE `dim-farm`.`IsFarm` = 0
                    GROUP BY `dim-farm`.`ID` )AS t1
                    WHERE t1.DIMsubFID
                    NOT IN 
                    (SELECT DISTINCT(`log-harvest`.`DIMsubFID`) FROM `log-harvest` 
                    WHERE `log-harvest`.`isDelete` = 0)";
            }
            else{
                $sql = "SELECT DISTINCT(`log-harvest`.`DIMsubFID`) FROM `log-harvest` 
                WHERE `log-harvest`.`isDelete` = 0 ";
                if($harvest == 1){
                    $sql = $sql . " AND `log-harvest`.`Weight` > $AVERAGE";
                }else if($harvest == 2){
                    $sql = $sql . " AND `log-harvest`.`Weight` < $AVERAGE";
                }
            }
            $data_harvest = selectData($sql);
            // print_r($data_harvest);
            $data_harvest = toDBID($data_harvest);

            // print_r($sql);

            // print_r($data_harvest);

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
            print_r($data_farmer);

            $sql = "SELECT DISTINCT(`log-pestalarm`.`DIMsubFID`) FROM `log-pestalarm`
            JOIN `dim-pest` ON `dim-pest`.`ID` = `log-pestalarm`.`DIMpestID`
            JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
            WHERE `log-pestalarm`.`isDelete` = 0 ";
            if($year != 0) $sql = $sql . " AND `dim-time`.`Year2`='$year'";
            if($pesttype != 0) $sql = $sql . " AND  `dim-pest`.`dbpestTID` = '$pesttype'";
            else $sql = $sqlAllSubfarm;
            $data_pesttype= selectData($sql);
            // print_r($data_pesttype);
            $data_pesttype = toDBID($data_pesttype);

            // print_r($sql);
            print_r($data_pesttype);

            if($mincutbranch == 0 && $maxcutbranch == 0 && $cutbranch != 0){
                $sql = "SELECT DISTINCT(t1.DIMSubfID) AS DIMsubFID FROM (
                    SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    WHERE `dim-farm`.`IsFarm` = 0
                    GROUP BY `dim-farm`.`ID` )AS t1
                    WHERE DIMsubFID NOT IN (
                    SELECT t2.DIMsubFID FROM (
                    SELECT COUNT(`dim-farm`.`dbID`) AS times,`log-activity`.`DIMsubFID` FROM `log-activity`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                    WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1";
                    if($year != 0) $sql = $sql . " AND `dim-time`.`Year2` = '$year'";
                    $sql = $sql." GROUP BY `dim-farm`.`dbID`)AS t2)";
            }else if($cutbranch != 0){
                $sql = "SELECT t1.DIMsubFID FROM (
                    SELECT COUNT(`dim-farm`.`dbID`) AS times,`log-activity`.`DIMsubFID`,
                    `dim-time`.`Year2` FROM `log-activity`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                    WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1
                    GROUP BY `dim-farm`.`dbID`)AS t1 ";
                if($mincutbranch == 0){
                    $sql = $sqlAllSubfarm." WHERE t1.DIMsubFID NOT IN (".$sql."  WHERE t1.times > '$maxcutbranch' " ;
                    if($year != 0) $sql = $sql . " AND `dim-time`.`Year2`='$year'";
                    $sql = $sql .")";
                }else{
                    $sql = $sql . " WHERE t1.times >= '$mincutbranch' AND t1.times <= '$maxcutbranch'";
                    if($year != 0) $sql = $sql . " AND `dim-time`.`Year2`='$year'";
                }
                
            } 
            
            if($cutbranch == 0){
                $sql = $sqlAllSubfarm;
            } 
            $data_minmax_cutbranch= selectData($sql);
            // print_r($data_minmax_cutbranch);
            $data_minmax_cutbranch = toDBID($data_minmax_cutbranch);

            // print_r($sql);
            print_r($data_minmax_cutbranch);

            if($lack == 1){
                $sql = "SELECT MAX(`dim-time`.`Date`) AS max_date,`dim-time`.`Year2`,`log-raining`.`DIMsubFID` FROM `log-raining` 
                JOIN `dim-time` ON `log-raining`.`DIMdateID` = `dim-time`.`ID`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-raining`.`DIMsubFID`
                GROUP BY `dim-farm`.`dbID` ORDER BY `log-raining`.`DIMsubFID` ASC";
                $raining = toArray(selectData($sql));
    
                // print_r($sql);
                // print_r("------------------------");
                // print_r($raining);
    
                $sql = "SELECT MAX(`dim-time`.`Date`) AS max_date,`dim-time`.`Year2`,`log-watering`.`DIMsubFID` FROM `log-watering` 
                JOIN `dim-time` ON `log-watering`.`DIMdateID` = `dim-time`.`ID`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-watering`.`DIMsubFID`
                GROUP BY `dim-farm`.`dbID` ORDER BY `log-watering`.`DIMsubFID` ASC";
                $watering = toArray(selectData($sql));
                // print_r($sql);
                // print_r($watering);
                // print_r("------------------------");
    
                $sql = "SELECT MAX(`dim-time`.`Date`) AS max_date,`dim-time`.`Year2`,`log-farm`.`DIMSubfID` AS DIMsubFID FROM `log-farm` 
                JOIN `dim-time` ON `log-farm`.`StartID` = `dim-time`.`ID`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                GROUP BY `dim-farm`.`dbID` ORDER BY `log-farm`.`DIMSubfID` ASC";
                $all = toArray(selectData($sql));
                // print_r($all);
    
                // print_r("------------------------");
                $arr = compare($all,$raining);
                $arr = compare($arr,$watering);
                
                // print_r($arr);
    
                $arr = countDay($arr);
                // print_r($arr);
                $data_minmax_lack = toDBID(checkDateMINMAX($arr,$minlack,$maxlack));
                // echo "date min max ";
            }else{
                $data_minmax_lack = toDBID(selectData($sqlAllSubfarm));
            }         
            print_r($data_minmax_lack);

            $sql = "SELECT `dim-time`.`Date`,`dim-time`.`Year2`,`dim-farm`.`dbID` FROM `fact-watering`
            JOIN `dim-time` ON `fact-watering`.`DIMdateID` = `dim-time`.`ID`  
            JOIN  `dim-farm` ON  `dim-farm`.`ID` =  `fact-watering`.`DIMsubFID`
            ORDER BY `dim-farm`.`dbID`,`dim-time`.`Date` ASC";
            $fact_watering = selectData($sql);
            $watering_subfarm = array();
            $day = 1;
            for($i=1;$i<=$fact_watering[0]['numrow'];$i++){
                if($i != $fact_watering[0]['numrow']){
                    if($fact_watering[$i]['dbID'] == $fact_watering[$i+1]['dbID']){
                        // print_r("DIMsub //// ");
                        // print_r($fact_watering[$i]['dbID']);
                        // print_r(" ");
                        // print_r($fact_watering[$i+1]['dbID']);
                        // print_r(" ------------------------------------------- ");
                        $datetomorrow = date('Y-m-d',strtotime('+1 day', strtotime($fact_watering[$i]['Date'])));
                        if($fact_watering[$i+1]['Date'] == $datetomorrow){
                            // print_r("if //// ");
                            // print_r("dbID = ");
                            // print_r($fact_watering[$i]['dbID'] );
                            // print_r(" date = ");
                            // print_r($fact_watering[$i+1]['Date']);
                            // print_r(" ");
                            // print_r($datetomorrow);
                            // print_r(" ");
                            $day++;
                            // print_r("day = ");
                            // print_r($day);
                            // print_r(" ");
                        }else{
                            // print_r("else //// ");
                            // print_r("dbID = ");
                            // print_r($fact_watering[$i]['dbID'] );
                            // print_r(" date = ");
                            // print_r($fact_watering[$i+1]['Date']);
                            // print_r(" ");
                            // print_r($datetomorrow);
                            // print_r(" ");
                            // print_r("day = ");
                            // print_r($day);
                            // print_r(" ");
                            $arr = array();
                            $arr['dbID'] = $fact_watering[$i]['dbID'];
                            $arr['year'] = $fact_watering[$i]['Year2'];
                            $arr['day'] = $day;

                            $watering_subfarm[] = $arr;
                            $day = 1;  
                        }
                    }else{
                        // print_r("else */// ");
                        // print_r("dbID = ");
                        // print_r($fact_watering[$i]['dbID'] );
                        // print_r(" date = ");
                        // print_r($fact_watering[$i+1]['Date']);
                        // print_r(" ");
                        // print_r($datetomorrow);
                        // print_r(" ");
                        // print_r("day = ");
                        // print_r($day);
                        // print_r(" ");
                        $arr = array();
                        $arr['dbID'] = $fact_watering[$i]['dbID'];
                        $arr['year'] = $fact_watering[$i]['Year2'];
                        $arr['day'] = $day;

                        $watering_subfarm[] = $arr;
                        $day = 1;  
                    }
                }else{
                    $arr = array();
                    $arr['dbID'] = $fact_watering[$i]['dbID'];
                    $arr['year'] = $fact_watering[$i]['Year2'];
                    $arr['day'] = $day;

                    $watering_subfarm[] = $arr;
                    $day = 1;  
                }
            }
            print_r("data water = ");
            print_r($watering_subfarm);

            $result = array_intersect($data_year,$data_pro_dist);
            $result = array_intersect($result,$data_pesttype);
            $result = array_intersect($result,$data_farmer);
            $result = array_intersect($result,$data_minmax_cutbranch);
            $result = array_intersect($result,$data_minmax_lack);

            print_r($result);

        break;
    }
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