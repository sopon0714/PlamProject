<?php
require_once("../../dbConnect.php");
connectDB();
session_start(); 
require_once("../../set-log-login.php");
include_once("./../../query/query.php");
$folder = "../../picture/activities/others/";
$folder_use = "picture/activities/others/";
$loglogin = $_SESSION[md5('LOG_LOGIN')];
$loglogin_id = $loglogin[1]['ID'];
$startID = $loglogin[1]['StartID'];
$DBactID = 2;
$header1 = "location:PestControl.php";
$header2 = "location:PestControlDetail.php?farmID=";

if(isset($_POST['request'])){
    $request = $_POST['request'];
    $sql ='';

    switch($request){
        case 'activity': 
            $idformal = $_POST['idformal'];
            $fullname = $_POST['fullname'];
            $fpro = $_POST['fpro'];
            $fdist = $_POST['fdist'];
            $fyear = $_POST['fyear'];
            $fmin = $_POST['fmin'];
            $fmax = $_POST['fmax'];
            $start = $_POST['start'];
            $limit = $_POST['limit'];
            $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
	          $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';
            print_r(json_encode(getActivity($idformal, $fullname, $fpro, $fdist ,$fyear ,$fmin ,$fmax,2,$start,$limit,$latitude,$longitude)));

        break;
        case 'selectFarm' :
            $date = $_POST['date'];
            $modify = strtotime($date);
          print_r(json_encode(getFarmByModify($modify)));
        break;
        case 'selectSubfarm' :
              $date = $_POST['date'];
              $modify = strtotime($date);           
              $dim_farm = $_POST['id'];
              print_r(json_encode(getSubfarmByModify($dim_farm,$modify)));
              
            break;
        case 'selectPest' :
              $type_id = $_POST['type_id'];
              $date = $_POST['date'];
              $modify = strtotime($date); 
              print_r(json_encode(getPestByModify($type_id,$modify)));
            break;
        case 'selectPestByPID';
              $dim_pest = $_POST['dim_pest'];
              print_r(json_encode(getPestLogByDIMpestID($dim_pest)));
          break;
        case 'scanDir';
            $pid = $_POST['pid'];
            $path = $_POST['path'];
            $folder = $path.$pid;
            $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
            $arr = array();
            foreach($objScan as $obj){
                $type= strrchr($obj,".");
                if($type == '.png' || $type == '.jpg' ){            
                    $arr[]= $obj;
                }
            } 
            print_r(json_encode($arr));
            break;
        case 'delete' ;
            $farmID = $_POST['id'];
            // echo $farmID;
            $dimFarm = getIDDIMfarmBydbID($farmID);
            // print_r($dimFarm);
            for($i = 1; $i<=$dimFarm[0]['numrow'] ; $i++){
              $dfid = $dimFarm[$i]['ID'];
              // echo $dfid;
              $sql="UPDATE `log-activity` 
              SET isDelete='1'
              WHERE DIMfarmID='$dfid' ";
              $o_did = updateData($sql);
            }
                     
            break;
        case 'deleteDetail' ;
            $id = $_POST['id'];
            echo 'id = '.$id;
            $sql="UPDATE `log-activity` 
                        SET isDelete='1'
                        WHERE ID='$id' ";
            $o_did = updateData($sql);
          
            break;
            
        case 'insert':
            $typePage = $_POST['typePage'];
            $date = $_POST['date'];
            $dim_farm_id = $_POST['farm'];
            $dim_subfarm_id = $_POST['subfarm'];
            $note = $_POST['note'];
            if(isset($_POST['fmid'])){
              $fmid = $_POST['fmid'];
            }
            
            $dim_owner_id = getDIMOwner($dim_farm_id);            

            $dataPic = explode('manu20', $_POST['pic']);
            $countfiles = sizeof($dataPic) - 1;
            $extension = ".png";

            echo 'date = '.$date.'<br>';
            $time = time();
            $id_t =  getDIMDate($date)[1]['ID'];

            // $id_owner = getIDowner($farm);
            // $dim_owner_id = getIDDIMfarmer($id_owner);
            // $dim_farm_id = getIDDIMfarm($farm);
            // $dim_subfarm_id = getIDDIMsubfarm($subfarm);
            // $dim_pest_id = getIDDIMpest($pest);

            // echo '$id_owner = '.$id_owner."<br>";
            // echo '$dim_owner_id = '.$dim_owner_id."<br>";

            // echo '$dim_farm_id = '.$dim_farm_id."<br>";
            // echo '$dim_subfarm_id = '.$dim_subfarm_id."<br>";
            // echo '$dim_pest_id = '.$dim_pest_id."<br>";

            $sql = "INSERT INTO `log-activity` (`isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, 
            `DIMownerID`, `DIMfarmID`, `DIMsubFID` , `DBactID`, `Note`)
            VALUES ('0','$time','$loglogin_id','$id_t',
            '$dim_owner_id','$dim_farm_id','$dim_subfarm_id','$DBactID','$note')";
            echo $sql;
            $id = addinsertData($sql);
            echo 'add id = '.$id.'<br>';

            $path = $folder_use.$id;

            $sql="UPDATE `log-activity` 
            SET PICs='$path'
            WHERE `log-activity`.ID = '$id'";
            updateData($sql);

            $path = $folder.$id;
            $time = time();
            if (!file_exists($folder.$id)) {
              mkdir($path);
            }
            if ($countfiles > 0) {
              for ($i = 0; $i < $countfiles; $i++) {
                echo '$extension = '.$extension.'<br>';
                $Pic = getImgPest($dataPic[$i]);
                file_put_contents($folder.$id."/".$i."-".$time.$extension, $Pic);
              }
            }
            if($typePage == '1'){
              header("$header1");
            }else{
              header("$header2".$fmid);
            }

            break;        
          
    }
  }
  function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 
function getIDDIMfarmBydbID($farmID){
  $sql = "SELECT * FROM `dim-farm` WHERE `dim-farm`.`dbID` = '$farmID' AND IsFarm = 1";
  $data = selectData($sql);
  return $data;
}
function getIDDIMfarmerByDIMfarmID($DIMfarmID){
  $sql = "SELECT * FROM `log-farm` WHERE `log-farm`.DIMfarmID = '$DIMfarmID' ";
  $data = selectData($sql)[1]["DIMownerID"];
  return $data; 
}
  function getIDDIMfarmer($id){
    $sql = "SELECT  MAX(`log-farmer`.`ID`) AS ID FROM `dim-user`
    JOIN `log-farmer` ON  `log-farmer`.`DIMuserID` = `dim-user`.`ID`
    WHERE `dim-user`.`dbID` = '$id'";
    $data = selectData($sql)[1]["ID"];

    $sql = "SELECT `log-farmer`.`DIMuserID`  FROM `log-farmer` 
    WHERE `log-farmer`.`ID` = $data";
    
    $data = selectData($sql)[1]["DIMuserID"];
    return $data; 
  }

  function getDIMOwner($dim_farm){
    $sql = "SELECT `log-farm`.`DIMownerID` FROM(
      SELECT MAX(`log-farm`.`ID`)  AS ID FROM `log-farm` 
      WHERE `log-farm`.`DIMfarmID`  = '$dim_farm')AS t1
      JOIN `log-farm` ON `log-farm`.`ID` = t1.ID";

      $data = selectData($sql)[1]['DIMownerID'];
      return $data;
  }

  function getIDDIMfarm($id){
    $sql = "SELECT  MAX(`log-farm`.`ID`) AS ID FROM `dim-farm`
    JOIN `log-farm` ON  `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
    WHERE `dim-farm`.`dbID` = '$id' AND `dim-farm`.`IsFarm` = 1";
    $data = selectData($sql)[1]["ID"];

    $sql = "SELECT `log-farm`.`DIMfarmID`  FROM `log-farm` 
    WHERE `log-farm`.`ID` = $data";
    
    $data = selectData($sql)[1]["DIMfarmID"];
    return $data; 
  }
  function getIDDIMsubfarm($id){
    $sql = "SELECT  MAX(`log-farm`.`ID`) AS ID FROM `dim-farm`
    JOIN `log-farm` ON  `log-farm`.`DIMsubFID` = `dim-farm`.`ID`
    WHERE `dim-farm`.`dbID` = '$id' AND `dim-farm`.`IsFarm` = 0";
    $data = selectData($sql)[1]["ID"];

    $sql = "SELECT `log-farm`.`DIMsubFID`  FROM `log-farm` 
    WHERE `log-farm`.`ID` = $data";
    
    $data = selectData($sql)[1]["DIMsubFID"];
    return $data; 
  }
  function getIDDIMpest($id){
    $sql = "SELECT  MAX(`log-pest`.`ID`) AS ID FROM `dim-pest`
    JOIN `log-pest` ON  `log-pest`.`DIMpestID` = `dim-pest`.`ID`
    WHERE `dim-pest`.`dbpestLID` = '$id'";
    $data = selectData($sql)[1]["ID"];

    $sql = "SELECT `log-pest`.`DIMpestID`  FROM `log-pest` 
    WHERE `log-pest`.`ID` = $data";
    
    $data = selectData($sql)[1]["DIMpestID"];
    return $data; 
  }

  function getIDowner($fid){
    $sql = "SELECT  `db-farm`.`UFID` FROM `db-farm` WHERE `db-farm`.`FMID` = $fid";
    $data = selectData($sql)[1]["UFID"];
    return $data; 
  }