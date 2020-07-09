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
            WHERE `log-pestalarm`.`isDelete` = 0 ";
            if($pesttype != 0) $sql = $sql . " AND  `dim-pest`.`dbpestTID` = '$pesttype'";
            else $sql = $sqlAllSubfarm;
            $data_pesttype= selectData($sql);
            // print_r($data_pesttype);
            $data_pesttype = toDBID($data_pesttype);

            // print_r($sql);
            print_r($data_pesttype);

            $sql = "SELECT t1.DIMsubFID FROM (
                SELECT COUNT(`dim-farm`.`dbID`) AS times,`log-activity`.`DIMsubFID` FROM `log-activity`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
                WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1
                GROUP BY `dim-farm`.`dbID`)AS t1 ";
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
                    WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1
                    GROUP BY `dim-farm`.`dbID`)AS t2)";
            }else if($cutbranch != 0){
                $sql = $sql . " WHERE t1.times >= '$mincutbranch' AND t1.times <= '$maxcutbranch'";
            } 
            
            if($cutbranch == 0){
                $sql = $sqlAllSubfarm;
            }
            $data_minmax_cutbranch= selectData($sql);
            // print_r($data_minmax_cutbranch);
            $data_minmax_cutbranch = toDBID($data_minmax_cutbranch);

            // print_r($sql);
            print_r($data_minmax_cutbranch);

            $result = array_intersect($data_year,$data_pro_dist);
            $result = array_intersect($result,$data_pesttype);
            $result = array_intersect($result,$data_farmer);
            $result = array_intersect($result,$data_minmax_cutbranch);

            print_r($result);

        break;
    }
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