<?php

    switch ($request) {
      case 'select' :
        $sql = "SELECT * FROM `db-pestlist`";

        print_r(json_encode(select($sql)));
        break;
      case 'style':
        $pid = $_POST['pid'];
        // echo 'pid = '.$pid.'<br>';
        $folder = $folderStyle.$pid;
        $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
        $string = '';
        foreach($objScan as $obj){
          $type= strrchr($obj,".");
          if($type == '.png' || $type == '.jpg' ){            
            $string .= ','.$obj;
          }
        }
        
        $string = substr($string,1);
        print_r($string);
      break;

      case 'danger':
        $pid = $_POST['pid'];
        // echo 'pid = '.$pid.'<br>';
        $folder = $folderDanger.$pid;
        $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
        $string = '';
        foreach($objScan as $obj){
          $type= strrchr($obj,".");
          if($type == '.png' || $type == '.jpg' ){            
            $string .= ','.$obj;
          }
        }
        
        $string = substr($string,1);
        print_r($string);

      break;
      case 'select':
        $sql = "SELECT * FROM `db-pestlist`";

        print_r(json_encode(select($sql)));
        break;

      case 'insert2':
        $a = explode('manu20', $_POST['pic2']);
        $b = sizeof($a) - 1;
        for ($i = 0; $i < $b; $i++) {
          echo $a[$i];
        }
        break;

      case 'insert':
        $Name = preg_replace('/[[:space:]]+/', ' ', trim($_POST['name_insert']));
        $Alias = preg_replace('/[[:space:]]+/', ' ',trim($_POST['alias_insert']));
        $Charactor = $_POST['charactor_insert'];
        $Danger = $_POST['danger_insert'];
        
        $time = time();

        $logo = $_POST['pic1'];
        $dataLogo = getImgPest($logo);
        $extension = ".png";

        $nameImg1 = null;
        if ($dataLogo != null) {
          $nameImg1 = time().$extension;
        }
        echo 'pic1 = '.$_POST['pic1'].'<br>';
        echo 'pic2 = '.$_POST['pic2'].'<br>';
        echo 'pic3 = '.$_POST['pic3'].'<br>';

        echo 'nameImg1 = '.$nameImg1.'<br>';

        $dataPic2 = explode('manu20', $_POST['pic2']);
        $countfiles_style = sizeof($dataPic2) - 1;

        $dataPic3 = explode('manu20', $_POST['pic3']);
        $countfiles_danger = sizeof($dataPic3) - 1;


        $DATA = select_dimPest();
        $i = 1;
        $check_dim = 1;
        for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
          if ($DATA[$i]['dbpestTID'] == $dptid && $DATA[$i]['Name'] == $Name && $DATA[$i]['Alias'] == $Alias  && $DATA[$i]['Charactor'] == $Charactor && $DATA[$i]['Danger'] == $Danger && $DATA[$i]['TypeTH'] == $type_pest) {
            $check_dim = 0; //dup
            $add_pid = $DATA[$i]['dbpestLID'];
            $id_d = getID_DIM($Name,$Alias,$Charactor,$Danger,$dptid,$type_pest); 
            break;
          }
        }
        echo  'check dim = '.$check_dim.'<br>'; 

        if($check_dim){
          $add_pid = '';
        }
        $sql = "INSERT INTO `db-pestlist` (`PID`, `Name`, `Alias`, `PTID`, `Charactor`, `Danger`, `Icon` , `NumPicChar`, `NumPicDanger`)
              VALUES ('$add_pid','$Name','$Alias','$dptid ','$Charactor','$Danger','$nameImg1','$countfiles_style','$countfiles_danger')";
        //echo $sql;
        $pid = addinsertData($sql);
        echo 'pid = '.$pid.'<br>';
        //-------------------------------------------------------- photo --------------------------------------------------------
        $path = $folderIcon.$pid;
        if (!file_exists($path)) {
          mkdir($path);
        }
        $path = $folderStyle.$pid;
        if (!file_exists($folderDanger.$pid)) {
          mkdir($path);
        }
        $path = $folderDanger.$pid;
        if (!file_exists($folderDanger.$pid)) {
          mkdir($path);
        }

        if ($dataLogo != null) {
          file_put_contents($folderIcon.$pid."/".$nameImg1, $dataLogo);
        }

        if ($countfiles_style > 0) {
          for ($i = 0; $i < $countfiles_style; $i++) {
            // $extension = explode('/', $dataPic2[$i]);
            // $extension = explode(';', $extension[1]);
            // $extension = '.'.$extension[0];

            echo '$extension style = '.$extension.'<br>';
            $Pic2 = getImgPest($dataPic2[$i]);
            file_put_contents($folderStyle.$pid."/".$i.$extension, $Pic2);
          }
        }

        if ($countfiles_danger > 0) {
          for ($i = 0; $i < $countfiles_danger; $i++) {
            $p = $dataPic3[$i];
            echo 'p danger = '.$p.'<br>';
            echo '$extension danger = '.$extension.'<br>';
            $Pic3 = getImgPest($dataPic3[$i]);
            file_put_contents($folderDanger.$pid."/".$i.$extension, $Pic3);
          }
        }

       //-------------------------------------------------------- log and dim --------------------------------------------------------

        if ($check_dim) {
         
          $sql = "INSERT INTO `dim-pest` (`ID`,`dbpestLID`,`dbpestTID`,`Name`,`Alias`,`Charactor`,`Danger`,`TypeTH`) 
                  VALUES ('','$pid','$dptid','$Name','$Alias','$Charactor','$Danger','$type_pest')";
          $id_d = addinsertData($sql);
         
        }
        
        $data_t =  getDIMDate();

        $sql = "INSERT INTO `log-pest` (`ID`,`DIMpestID`,`LOGloginID`,`StartT`,`StartID`,`EndT`,`EndID`,`NumPicChar`,`NumPicDanger`) 
        VALUES ('','$id_d','$loglogin_id','$time','$startID',NULL,NULL,'$countfiles_style','$countfiles_danger')";
        $log_id = addinsertData($sql);
        echo $log_id;
        $path = "icon/pest/$pid";
        
        $sql = "INSERT INTO `log-icon` (`ID`,LOGloginID,StartT,StartID,DIMiconID,`Type`,`FileName`,`Path`) 
        VALUES ('','$loglogin_id','$time','$startID','$id_d','1','$nameImg1','$path')";

        addinsertData($sql);

        header($location.$pid);

        break;

      case 'delete';
        $pid = $_POST['pid'];
        echo 'pid = '.$pid.'<br>';
        
        $sql = "SELECT * FROM `db-pestlist` WHERE `PID`='" . $pid . "'";
        $PESTLIST = selectData($sql); //get old data

        $get_idDim = getID_DIM($PESTLIST[1]['Name'],$PESTLIST[1]['Alias'],$PESTLIST[1]['Charactor'],
        $PESTLIST[1]['Danger'],$dptid,$type_pest);

        echo 'get_idDim = '.$get_idDim.'<br>';

        $time = time();
            $data_t =  getDIMDate();
        $id_t = $data_t[1]['ID'];

        $sql="UPDATE `log-pest` 
        SET EndT='$time', EndID='$id_t'
        WHERE DIMpestID='$get_idDim' AND EndT IS NULL ";
        echo $sql;
        $o_did = updateData($sql);
        echo 'o_id = '.$o_did;
        $sql="UPDATE `log-icon` 
        SET EndT='$time', EndID='$id_t'
        WHERE DIMiconID='$get_idDim' AND EndT IS NULL ";
        echo $sql;

        $o_did = updateData($sql);
        echo 'o_id = '.$o_did;


        $sql = "DELETE FROM `db-pestlist` WHERE `PID`='" . $pid . "'";
        delete($sql);

        //delete all file and folder icon pid
        $folder = $folderIcon.$pid;
        delAllFileInfolder($folder);
        if (is_dir($folder)&&$folder!='') {
          rmdir($folder);
        }
        //delete all file and folder style pid
        $folder = $folderStyle.$pid;
        delAllFileInfolder($folder);
        if (is_dir($folder)&&$folder!='') {
          rmdir($folder);
        }
        //delete all file and folder danger pid
        $folder = $folderDanger.$pid;
        delAllFileInfolder($folder);
        if (is_dir($folder)&&$folder!='') {
          rmdir($folder);
        }

        break;

      case 'update':
        $pid = $_POST['e_pid'];
        echo $pid.'<br>';
        $Name = preg_replace('/[[:space:]]+/', ' ', trim($_POST['e_name']));
        $Alias = preg_replace('/[[:space:]]+/', ' ',trim($_POST['e_alias']));
        $Charactor = trim($_POST['e_charactor']);
        $Danger = trim($_POST['e_danger']);
        $logo = $_POST['e_pic1'];

        $o_name = trim($_POST['e_o_name']);
        $o_alias = trim($_POST['e_o_alias']);
        $o_charstyle = trim($_POST['e_o_charcator']);
        $o_danger = trim($_POST['e_o_danger']);

        $sql = "SELECT * FROM `db-pestlist` WHERE `PID`='" . $pid . "'";
        $PESTLIST = selectData($sql); //get old data

        // print_r($PESTLIST[1]['Icon']);
        ////////////////////
        $time = time();

        $extension = ".png";
        
        print_r($_POST['e_pic1']);
        echo '<br>';
        echo $folderIcon.$pid."/".$PESTLIST[1]['Icon'].'<br>';
        //if old icon
        if($_POST['e_pic1'] !=$folderIcon.$pid."/".$PESTLIST[1]['Icon']){
          echo 'not eq';
          $dataLogo = getImgPest($logo);
        }else{
          $dataLogo = null;
        }

        $nameImg1 = null;
        if ($dataLogo != null) {
          $nameImg1 = time().$extension;
        }

        print_r($_POST['e_pic2']);
        echo '<br>';
        print_r($_POST['e_pic3']);
        echo '<br>';

        $dataPic2 = explode('manu20', $_POST['e_pic2']);
        $countfiles_style = sizeof($dataPic2) - 1;
        print_r($dataPic2);
        echo '<br>';
        echo "num pic2 = ".$countfiles_style."<br>";
        $dataPic3 = explode('manu20', $_POST['e_pic3']);
        $countfiles_danger = sizeof($dataPic3) - 1;
        echo "num pic3 = ".$countfiles_danger."<br>";
        
        $folder = $folderStyle.$pid;
        $checkPic2 = check_Pic($folder,$dataPic2);
      
        print_r($checkPic2);
        echo '<br>';

        del_Pic($folder,$checkPic2); //del pic

        $folder =$folderDanger.$pid;
        $checkPic3 = check_Pic($folder,$dataPic3);
      
        print_r($checkPic3);
        echo '<br>';

        del_Pic($folder,$checkPic3); //del pic
        
        if ($dataLogo != null) {
          echo 'push icon <br>';
          file_put_contents($folderIcon.$pid."/".$nameImg1, $dataLogo);
        }else{
          $nameImg1 = $PESTLIST[1]['Icon'];
        }

        echo '<br>';
        print_r($dataPic2);
        echo '<br>';
        if ($countfiles_style > 0) {
          for ($j = $countfiles_style-1; $j >= 0; $j--) {
            echo 'j = '.$j.'<br>';

            if($dataPic2[$j] != ''){

              echo 'dataPic2 j '.$j.'= '.$dataPic2[$j].'<br>';
              $pic = substr($dataPic2[$j], 31);
              $pic= strrchr($pic,"/");
              $pic= substr($pic,1);
              echo 'pic = '.$pic.'<br>';

              if(!isset($checkPic2[$pic]) || $checkPic2[$pic] == 0){
                  for ($i = 0; $i < $countfiles_style; $i++) {
                    $check_dup_pic = check_dup_name_picture($folderStyle.$pid,$i.$extension);
                    if(!$check_dup_pic){
                        echo 'push style i = '.$i.'<br>';
                        $Pic2 = getImgPest($dataPic2[$j]);
                        echo $folderStyle.$pid."/".$i.$extension.'<br>';
                        file_put_contents($folderStyle.$pid."/".$i.$extension, $Pic2);
                        break;
                    }
                  }
              }
            }
          }
        }

        print_r($dataPic3);
        echo '<br>';
        if ($countfiles_danger > 0) {
          for ($j = $countfiles_danger-1; $j >= 0; $j--) {
            echo 'j = '.$j.'<br>';

            if($dataPic3[$j] != ''){

              echo 'dataPic3 j '.$j.'= '.$dataPic3[$j].'<br>';
              $pic = substr($dataPic3[$j], 31);
              $pic= strrchr($pic,"/");
              $pic= substr($pic,1);
              echo $pic.'<br>';
              if(!isset($checkPic3[$pic]) || $checkPic3[$pic] == 0){
                  for ($i = 0; $i < $countfiles_danger; $i++) {
                    $check_dup_pic = check_dup_name_picture($folderDanger.$pid,$i.$extension);
                    if(!$check_dup_pic){
                        echo 'push danger i = '.$i.'<br>';
                        $Pic3 = getImgPest($dataPic3[$j]);
                        file_put_contents($folderDanger.$pid."/".$i.$extension, $Pic3);
                        break;
                    }
                  }
              }
            }
          }
        }
        ////////////////////

        $id_dim_old = getID_DIM($PESTLIST[1]['Name'],$PESTLIST[1]['Alias'],$PESTLIST[1]['Charactor'],
        $PESTLIST[1]['Danger'],$dptid,$type_pest);

        echo $pid."<br>";
        $sql =  "UPDATE `db-pestlist` 
                SET `Name`='$Name', `Alias`='$Alias', `Charactor`='$Charactor' , `Danger`='$Danger',
                `Icon`='$nameImg1',`NumPicChar`='$countfiles_style',`NumPicDanger`='$countfiles_danger' WHERE `PID`='$pid'";
        echo $sql;
        $re = updateData($sql);
        echo $re;

        // ------------------------------------- DIM AND LOG -------------------------------------
        $DATA = select_dimPest();

        $i = 1;
        $check_dim = 1;
        for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
          if ($DATA[$i]['dbpestTID'] == $dptid && $DATA[$i]['Name'] == $Name && $DATA[$i]['Alias'] == $Alias 
           && $DATA[$i]['Charactor'] == $Charactor && $DATA[$i]['Danger'] == $Danger && $DATA[$i]['TypeTH'] == $type_pest) {
            $check_dim = 0; //dup
            $id_d = getID_DIM($Name,$Alias,$Charactor,$Danger,$dptid,$type_pest); 
            break;
          }
        }
        echo 'check dim = '.$check_dim.'<br>';
        // ------------------------------------- if DIM don't duplicated -------------------------------------
        if ($check_dim) {
          $sql = "INSERT INTO `dim-pest` (`ID`,`dbpestLID`,`dbpestTID`,`Name`,`Alias`,`Charactor`,`Danger`,`TypeTH`) 
                  VALUES ('','$pid','$dptid','$Name','$Alias','$Charactor','$Danger','$type_pest')";
          $id_d = addinsertData($sql);
          $data_t =  getDIMDate();
        }
          echo 'id_d = '.$id_d.'<br>';
          echo 'id_dim_old = '.$id_dim_old.'<br>';

          $time = time();
          $data_t =  getDIMDate();
          $id_t = $data_t[1]['ID'];

          echo 'img1 = '.$nameImg1.'<br>';
          print_r($PESTLIST);
          if($PESTLIST[1]['Name'] == $Name && $PESTLIST[1]['Alias'] == $Alias && $PESTLIST[1]['Charactor'] == $Charactor && $PESTLIST[1]['Danger'] == $Danger
          && $PESTLIST[1]['Icon'] == $nameImg1 && $PESTLIST[1]['NumPicChar'] == $countfiles_style && $PESTLIST[1]['NumPicDanger'] == $countfiles_danger){

          }else{

            $LOG = getLog($id_dim_old );
            print_r($LOG);
            $o_log_id = $LOG[1]['ID'];     
            echo $o_log_id;

            $sql="UPDATE `log-pest` 
            SET EndT='$time', EndID='$id_t'
            WHERE ID='$o_log_id' AND EndT IS NULL";
    
            echo $sql.'<br>';
            $o_did = updateData($sql);
            echo 'update log = '.$o_did.'<br>';
  
            $sql = "INSERT INTO `log-pest` (`ID`,`DIMpestID`,`LOGloginID`,`StartT`,`StartID`,`EndT`,`EndID`,`NumPicChar`,`NumPicDanger`) 
            VALUES ('','$id_d','$loglogin_id','$time','$startID',NULL,NULL,'$countfiles_style','$countfiles_danger')";
            $log_id = addinsertData($sql);
            echo 'insert log pest'.$log_id;

            if($id_d != $id_dim_old || $PESTLIST[1]['Icon'] != $nameImg1 ){
              echo 'id_d dim = '.$id_d.'<br>';
              $LOG = getLogIcon($id_dim_old );
              print_r($LOG);
              $o_log_id = $LOG[1]['ID'];     
              echo $o_log_id;

              $sql="UPDATE `log-icon` 
              SET EndT='$time', EndID='$id_t'
              WHERE ID='$o_log_id' AND EndT IS NULL";
      
              $o_did = updateData($sql);
    
              $path = "icon/pest/$pid";
        
              $sql = "INSERT INTO `log-icon` (`ID`,LOGloginID,StartT,StartID,DIMiconID,`Type`,`FileName`,`Path`) 
              VALUES ('','$loglogin_id','$time','$startID','$id_d','1','$nameImg1','$path')";
      
              addinsertData($sql);
            }
          }

        header($location.$pid);
        ob_end_flush();

        // header("refresh: 0; url=".$location.$pid);
        break;
    }

    function getID_DIM($name, $alias, $char, $dang, $tid, $type)
    {
      $sql = "SELECT ID FROM `dim-pest` WHERE `Name`='$name' AND `Alias`='$alias' 
                    AND `Charactor`='$char' AND `Danger`='$dang' AND `dbpestTID`='$tid' AND `TypeTH`='$type'";

      $DATA = selectData($sql);
      return $DATA[1]['ID'];
    }

    function getLog($id_dim)
    {
      $sql = "SELECT * FROM `log-pest` WHERE `DIMpestID`='$id_dim' AND `EndT` IS NULL";

      $DATA = selectData($sql);
      return $DATA;
    }
    function getLogIcon($id_dim)
    {
      $sql = "SELECT * FROM `log-icon` WHERE `DIMiconID`='$id_dim' AND `EndT` IS NULL";

      $DATA = selectData($sql);
      return $DATA;
    }
    function delAllFileInfolder($folder=''){
      if (is_dir($folder)&&$folder!='') {
        //Get a list of all of the file names in the folder.
        $files = glob($folder . '/*');
         
        //Loop through the file list.
        foreach($files as $file){
          //Make sure that this is a file and not a directory.
          if(is_file($file)){
            //Use the unlink function to delete the file.
            unlink($file);
          }
        }
      }
    }
    function check_Pic($folder,$dataPic){
      $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
      print_r($objScan);

      $checkPic = array();
      foreach($objScan as $obj){
        $type= strrchr($obj,".");
        if($type == '.png' || $type == '.jpg' ){
          $checkPic[$obj] = 0;
        }
      }

      foreach($objScan as $obj){
        $type= strrchr($obj,".");
        echo 'type ='.$type;
        if($type == '.png' || $type == '.jpg' ){
          foreach($dataPic as $pic){
            echo 'pic  = '.$pic.'<br>';
            if($folder."/".$obj == $pic || ",".$folder."/".$obj == $pic ){
              $checkPic[$obj]++;
            }
          }
        }
      }
      return $checkPic;

    }
    function del_Pic($folder,$checkPic){
      $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
      foreach($objScan as $obj){
        $type= strrchr($obj,".");
        if($type == '.png' || $type == '.jpg' ){            
          if($checkPic[$obj] == 0){
            echo 'del pho'.$obj;
            unlink($folder."/".$obj);
          }
        }
      }
    }

    function check_dup_name_picture($folder,$namePic){
      $objScan = scandir($folder); // Scan folder ว่ามีไฟล์อะไรบ้าง
      foreach($objScan as $obj){
        $type= strrchr($obj,".");
        if($type == '.png' || $type == '.jpg' ){            
          if($obj == $namePic){
            return true;
          }
        }
      }
      return false;
    }

