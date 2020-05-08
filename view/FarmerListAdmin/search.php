<?php

$idformal = '';
$fullname ='';
$fpro = 0;
$fdist = 0;

if( isset($_POST['s_formalid']))  $idformal = rtrim($_POST['s_formalid']);
if( isset($_POST['s_province']))  $fpro     = $_POST['s_province'];
if( isset($_POST['s_distrinct'])) $fdist    = $_POST['s_distrinct'];
if( isset($_POST['s_name'])){
  $fullname = rtrim($_POST['s_name']); 
  $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
  $namef = explode(" ",$fullname);
  if(isset($namef[1])){
      $fnamef =$namef[0];
      $lnamef = $namef[1];
  }else{
      $fnamef =$fullname;
      $lnamef= $fullname;
  } 
}

$sql = "SELECT UFID,Title,FirstName,LastName,FormalID,Icon,`Address`,`db-farmer`.`AD3ID`,IsBlock,`db-farmer`.`ModifyDT`,`db-distrinct`.AD2ID,`db-distrinct`.AD1ID,subDistrinct,Distrinct,Province FROM `db-farmer` 
                INNER JOIN `db-subdistrinct` ON `db-farmer`.`AD3ID`=  `db-subdistrinct`.AD3ID
                INNER JOIN `db-distrinct` ON `db-subdistrinct`.`AD2ID`=  `db-distrinct`.AD2ID
                INNER JOIN `db-province` ON `db-distrinct`.`AD1ID`=  `db-province`.AD1ID
                WHERE 1 ";

if($idformal!='') $sql = $sql." AND FormalID LIKE '%".$idformal."%' ";
if($fullname!='') $sql = $sql." AND (FirstName LIKE '%".$fnamef."%' OR LastName LIKE '%".$lnamef."%') ";
if($fpro    !=0)  $sql = $sql." AND `db-distrinct`.AD1ID = '".$fpro."' ";
if($fdist   !=0)  $sql = $sql." AND `db-distrinct`.AD2ID = '".$fdist."' ";

$FARMER = selectdata($sql);

?>