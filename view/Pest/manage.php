<?php
require_once("../../dbConnect.php");
connectDB();
session_start(); 
require_once("../../set-log-login.php");
include_once("./../../query/query.php");
$folder = "../../picture/activities/pest/";
$folder_use = "picture/activities/pest/";
$loglogin = $_SESSION[md5('LOG_LOGIN')];
$loglogin_id = $loglogin[1]['ID'];
$startID = $loglogin[1]['StartID'];

if(isset($_POST['request'])){
    $request = $_POST['request'];
    $sql ='';

    switch($request){
        case 'pest' :
            $idformal = $_POST['idformal'];
            $fullname = $_POST['fullname'];
            $fpro = $_POST['fpro'];
            $fdist = $_POST['fdist'];
            $fyear = $_POST['fyear'];
            $ftype = $_POST['ftype'];
            $start = $_POST['start'];
            $limit = $_POST['limit'];
	          $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
	          $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

            print_r(json_encode(getPest($idformal, $fullname, $fpro, $fdist, $fyear, $ftype,$start,$limit,$latitude,$longitude)));
            // print_r(getPest($idformal, $fullname, $fpro, $fdist, $fyear, $ftype,$start,$limit,$latitude,$longitude));

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
            $id = $_POST['id'];
            echo 'id = '.$id;
            $sql="UPDATE `log-pestalarm` 
                        SET isDelete='1'
                        WHERE ID='$id' ";
            $o_did = updateData($sql);
          
            break;
            
        case 'insert':
            $date = $_POST['date'];
            $dim_farm_id = $_POST['farm'];
            $dim_subfarm_id = $_POST['subfarm'];
            $pesttype = $_POST['pesttype'];
            $dim_pest_id = $_POST['pest'];
            $note = $_POST['note'];
            
            $dim_owner_id = getDIMOwner($dim_farm_id);            

            $dataPic = explode('manu20', $_POST['pic']);
            $countfiles = sizeof($dataPic) - 1;
            $extension = ".png";

            echo 'date = '.$date.'<br>';
            // echo 'farm = '.$farm.'<br>';
            // echo 'subfarm = '.$subfarm.'<br>';
            // echo 'pesttype = '.$pesttype.'<br>';
            // echo 'pest = '.$pest.'<br>';
            // echo 'note = '.$note.'<br>';
            // echo 'dataPic = <br>';
            // print_r($dataPic);
            // echo 'countfiles = '.$countfiles.'<br>';
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

            $sql = "INSERT INTO `log-pestalarm` (`isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, 
            `DIMownerID`, `DIMfarmID`, `DIMsubFID` , `DIMpestID`, `Note`)
            VALUES ('0','$time','$loglogin_id','$id_t',
            '$dim_owner_id','$dim_farm_id','$dim_subfarm_id','$dim_pest_id','$note')";
            //echo $sql;
            $id = addinsertData($sql);
            echo 'add id = '.$id.'<br>';

            $path = $folder_use.$id;

            $sql="UPDATE `log-pestalarm` 
            SET PICs='$path'
            WHERE `log-pestalarm`.ID = '$id'";
            updateData($sql);

            $path = $folder.$id;

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
            header("location:Pest.php");

            break;
        
        case 'update':
          $id = $_POST['e_pestAlarmID'];
          $date = $_POST['e_date'];
          $dim_farm_id = $_POST['e_farm'];
          $dim_subfarm_id = $_POST['e_subfarm'];
          $pesttype = $_POST['e_pesttype'];
          $dim_pest_id = $_POST['e_pest'];
          $note = $_POST['e_note'];

          $sql = "SELECT * FROM `log-pestalarm` WHERE `ID`='" . $id . "'";
          $LOGPESTALARM = selectData($sql); //get old data
          echo 'date = '.$date.'<br>';

          echo 'subfarm = '.$subfarm.'<br>';
          $pic_edit = $_POST["pic-edit"];
          $old_pic_edit = $_POST["old_pic-edit"];

          $dataPic = explode('manu20', $_POST['pic-edit']);
          $countfiles = sizeof($dataPic) - 1;

          $extension = ".png";
          $time = time();
          $id_t =  getDIMDate($date)[1]["ID"];
          echo 'dim date = '.$id_t.'<br>';

          $dim_owner_id = getIDDIMfarmerByDIMfarmID($dim_farm_id);

          // $id_owner = getIDowner($farm);
          // $dim_owner_id = getIDDIMfarmer($id_owner);
          // $dim_farm_id = getIDDIMfarm($farm);
          // $dim_subfarm_id = getIDDIMsubfarm($subfarm);
          // $dim_pest_id = getIDDIMpest($pest);

          if($LOGPESTALARM[1]['ID'] == $id && $LOGPESTALARM[1]['DIMdateID'] == $id_t &&
          $LOGPESTALARM[1]['DIMfarmID'] == $dim_farm_id && $LOGPESTALARM[1]['DIMsubFID'] == $dim_subfarm_id && 
          $LOGPESTALARM[1]['DIMpestID'] == $dim_pest_id && $LOGPESTALARM[1]['Note'] == $note && $pic_edit == $old_pic_edit ){

          }else{
            $old_path = $folder.$id;

            $sql="UPDATE `log-pestalarm` 
              SET isDelete='1'
              WHERE `log-pestalarm`.ID = '$id'";
            updateData($sql);
  
            $sql = "INSERT INTO `log-pestalarm` (`isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, 
            `DIMownerID`, `DIMfarmID`, `DIMsubFID` , `DIMpestID`, `Note`)
            VALUES ('0','$time','$loglogin_id','$id_t',
            '$dim_owner_id','$dim_farm_id','$dim_subfarm_id','$dim_pest_id','$note')";
            //echo $sql;
            $id = addinsertData($sql);
            echo 'add id = '.$id.'<br>';
            
            $path = $folder_use.$id;
  
            $sql="UPDATE `log-pestalarm` 
            SET PICs='$path'
            WHERE `log-pestalarm`.ID = '$id'";
            updateData($sql);
            
            $new_path = $folder.$id;
            $copy = recurse_copy($old_path,$new_path);
            echo 'copy = ';
            print_r($copy);
            echo '<br>';          
            $checkPic = check_Pic($old_path,$dataPic);
            echo 'checkPic = ';
            print_r($checkPic);
            echo '<br>';
  
            del_Pic($new_path,$checkPic); //del pic
  
            if ($countfiles > 0) {
              for ($j = $countfiles-1; $j >= 0; $j--) {
                echo 'j = '.$j.'<br>';
    
                if($dataPic[$j] != ''){
    
                  echo 'dataPic j '.$j.'= '.$dataPic[$j].'<br>';
                  $pic = substr($dataPic[$j], 31);
                  $pic= strrchr($pic,"/");
                  $pic= substr($pic,1);
                  echo 'pic = '.$pic.'<br>';
    
                  if(!isset($checkPic[$pic]) || $checkPic[$pic] == 0){
                      for ($i = 0; $i < $countfiles; $i++) {
                        $check_dup_pic = check_dup_name_picture($folder.$id,$i."-".$time.$extension);
                        if(!$check_dup_pic){
                            echo 'push i = '.$i.'<br>';
                            $Pic2 = getImgPest($dataPic[$j]);
                            echo $folder.$id."/".$i."-".$time.$extension.'<br>';
                            file_put_contents($folder.$id."/".$i."-".$time.$extension, $Pic2);
                            break;
                        }
                      }
                  }
                }
              }
            }
  
          }

          header("location:Pest.php");

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

