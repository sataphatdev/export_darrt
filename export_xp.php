<meta charset="utf-8">
<?php
// close debug error
ini_set("display_errors", 0);
/// check file config is not exists
$filename = 'config.php';
if (!file_exists($filename)) {
 echo '<font color="red"><b>ไฟล์การติดตั้งยังไม่ถูกสร้าง.</b></font>';
 echo"<meta http-equiv=\"refresh\" content=\"3; url=preconfig.php\">";
}
// require config file
require_once("config.php");
// connect db
//$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, 3306);
$db_type = DBMS_TYPE;
$host = DB_HOST;
$db = DB_NAME;
$username = DB_USER;
$password = DB_PASSWORD;

switch ($db_type) {
    case "1":
    //Mysql
    $dsn = "mysql:host=$host; port=3306; dbname=$db";
    try{
     // create a PDO connection with the configuration data
     $conn = new PDO($dsn, $username, $password);
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     // display a message if connected to database successfully
     if($conn){
     //echo "Connected to the <strong>$db</strong> database successfully!";
     $status = 1;
            }
  }catch (PDOException $e){
    // report error message
  echo  "ERROR :".$e->getMessage();
  echo "<hr><a href='preconfig.php'>แก้ไขไฟล์ติดตั้ง</a>";
  }
        break;
    case "2":
        // PostgreSQL
        $dsn = "pgsql:host=$host port=5432 dbname=$db";
        try{
         // create a PDO connection with the configuration data
         $conn = new PDO($dsn, $username, $password);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // display a message if connected to database successfully
           if($conn){
           //echo "Connected to the <strong>$db</strong> database successfully!";
           $status = 1;
                  }
        }catch (PDOException $e){
          // report error message
          echo  "ERROR :".$e->getMessage();
          echo "<hr><a href='preconfig.php'>แก้ไขไฟล์ติดตั้ง</a>";
        }
        break;
        case "3":
            // MSSQL
            $dsn = "sqlsrv::host=$host dbname=$db";
            try{
             // create a PDO connection with the configuration data
             $conn = new PDO($dsn, $username, $password);
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             // display a message if connected to database successfully
               if($conn){
               //echo "Connected to the <strong>$db</strong> database successfully!";
               $status = 1;
                      }
            }catch (PDOException $e){
              // report error message
              echo  "ERROR :".$e->getMessage();
              echo "<hr><a href='preconfig.php'>แก้ไขไฟล์ติดตั้ง</a>";
            }
            break;
    default:
      //Mysql
      $dsn = "mysql:host=$host; port=3306; dbname=$db";
      try{
       // create a PDO connection with the configuration data
       $conn = new PDO($dsn, $username, $password);
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // display a message if connected to database successfully
       if($conn){
       //echo "Connected to the <strong>$db</strong> database successfully!";
       $status = 1;
              }
    }catch (PDOException $e){
      // report error message
      echo  "ERROR :".$e->getMessage();
      echo "<hr><a href='preconfig.php'>แก้ไขไฟล์ติดตั้ง</a>";
    }

}

// prevent unreadable characters in many languages
$conn->exec("SET CHARACTER SET utf8");



// check if POST from FORM
if($_POST){

  	// file and folder for export
    // new file name
  	$filename = time().'.txt';
    if(!is_dir('files')) { mkdir('files');}
    // path keep file export
  	$file = 'files/'.$filename;
  	// Open in write mode
  	$f = fopen($file, 'w');
  	// begin query

  	$sql = "SELECT icd10 AS DISEASE,v.hn AS HN,IF(v.VN IS NULL,2,1) AS PATIENT_TYPE,p.pname AS TITLE,p.fname AS FNAME";
    $sql .= ",p.lname AS LNAME,v.sex AS SEX,v.age_y AS AGEY,p.nationality AS NATION,p.citizenship AS RACE";
    $sql .= ",p.occupation AS OCCUPAT,p.addrpart AS ADDRESS,concat(p.chwpart,p.amppart,p.tmbpart) AS ADDRCODE_PAT,p.moopart AS ADDRCODE_MOO";
    $sql .= ",p.hometel AS PHONE,ov.vstdate AS DATEDEFINE,p.informaddr AS C_ADDRESS,'' AS C_ADDRESS_PAT,'' AS C_ADDRESS_MOO,p.informtel AS C_PHONE";
    $sql .= " FROM ovstdiag ov left outer join vn_stat v on v.vn=ov.vn left outer join patient p on p.hn=v.hn where ov.vstdate between DATE_SUB(CURDATE(),INTERVAL 60 day) and CURDATE()";
    $sql .= " and ov.icd10 IN ('J00','J029','J069','J09','J10','J100','J101','J108','J11','J110','J111','J118','J12','J120','J121','J122','J128','J129','J13','J14','J15'";
    $sql .= ",'J150','J151','J152','J157','J158','J1581','J159','J16','J160','J168','J170','J171','J18','J180','J181','J182','J188','J189','J851')";
    $sql .= " UNION ";
    $sql .= "select ida.icd10 AS DISEASE,a.hn AS HN,IF(i.AN IS NULL,1,2) AS PATIENT_TYPE,p.pname AS TITLE,p.fname AS FNAME,p.lname AS LNAME,a.sex AS SEX";
    $sql .= ",a.age_y AS AGEY,p.nationality AS NATION,p.citizenship AS RACE,p.occupation AS OCCUPAT,p.addrpart AS ADDRESS,concat(p.chwpart,p.amppart,p.tmbpart) AS ADDRCODE_PAT";
    $sql .= ",p.moopart AS ADDRCODE_MOO,p.hometel AS PHONE,a.admdate AS DATEDEFINE,p.informaddr AS C_ADDRESS,'' AS C_ADDRESS_PAT,'' AS C_ADDRESS_MOO,p.informtel AS C_PHONE";
    $sql .= " from iptdiag ida left outer join an_stat a on a.an=ida.an left outer join ipt i on i.an=ida.an left outer join patient p on p.hn=a.hn";
    $sql .= " where i.regdate between DATE_SUB(CURDATE(),INTERVAL 60 day) and CURDATE() and ida.icd10 in ('J00','J029','J069','J09','J10','J100','J101','J108','J11','J110','J111','J118','J12','J120','J121','J122','J128','J129','J13','J14','J15'";
    $sql .= ",'J150','J151','J152','J157','J158','J1581','J159','J16','J160','J168','J170','J171','J18','J180','J181','J182','J188','J189','J851')";

    // query demo test
    //$sql = "SELECT * FROM lastest_report";
    // get data
    $result = $conn->query($sql);

    	foreach($result as $row){
    		// row select
    		$disease = $row['DISEASE'];
    		$hn = $row['HN'];
    		$patient_type =$row['PATIENT_TYPE'];
    		$title = $row['TITLE'];
    		$fname = $row['FNAME'];
    		$lname = $row['LNAME'];
    		$sex = $row['SEX'];
    		$agey = $row['AGEY'];
    		$nation = $row['NATION'];
    		$race = $row['RACE'];
    		$occupat = $row['OCCUPAT'];
    		$address = $row['ADDRESS'];
    		$addresscode_pat = $row['ADDRCODE_PAT'];
    		$addresscode_moo = $row['ADDRCODE_MOO'];
    		$phone = $row['PHONE'];
    		$datedefine = $row['DATEDEFINE'];
    		$c_address = $row['C_ADDRESS'];
    		$c_address_pat = $row['C_ADDRESS_PAT'];
    		$c_address_moo = $row['C_ADDRESS_MOO'];
    		$c_phone = $row['C_PHONE'];

    		// text write to text file from sql query
    		$content = $disease."|".$hn."|".$patient_type."|".$title."|".$fname."|".$lname."|".$sex."|".$agey."|".$nation."|";
    		$content .= $race."|".$occupat."|".$address."|".$addresscode_pat."|".$addresscode_moo."|".$phone."|".$datedefine."|";
    		$content .= $c_address."|".$c_address_pat."|".$c_address_moo."|".$c_phone."\r\n";
    	 	// write file
    		$file_export = fwrite($f, $content);
        unset($result);
    	}
  	// close write file
  	fclose($f);
    // close connection
    $conn = null;
  	// show msg after click export btn
  	if($file_export){
  	$msg = "<a href=".$file." download>คลิ๊กเพื่อดาวน์โหลดไฟล์</a>";
  	}else{
  		$msg = "ไม่สามารถนำออกข้อมูลได้";
  	}
}
?>
<html>
<head>
<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <style type="text/css">
.loader {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url('350.GIF') 50% 50% no-repeat;
}
</style>
</head>


<body>

<form method="post" action="export_xp.php">
	<h1>ส่งออกข้อมูล</h1>
	<table class="form-table">
		<tr>
			<th scope="row">สถานะฐานข้อมูล</th>
			<td><?php

			if ($status!=1) {
    				//echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
					echo $status." <img src='indicator-dark-gray.gif' title='ไม่สามารถติดต่อฐานข้อมูลได้'> <a href='preconfig.php'>แก้ไขไฟล์ติดตั้ง</a>";
					$chk_connect = 0;
			}else{
					echo "<img src='online.gif' title='ติดต่อฐานข้อมูลได้'> | <a href='preconfig.php'>แก้ไขไฟล์ติดตั้ง</a>";
					$chk_connect = 1;
			}
			?></td>
			<td></td>
		</tr>

	</table>
	<p class="step"><input id="myBtn" name="submit" type="submit" value="นำออก" <?php if($chk_connect==0){ echo "disabled"; } ?> class="button button-large" /></p>
<input type="hidden" name="export" value="export">
</form>
<?php
if(isset($msg)){ echo $msg;}


?>

<script src="../js/jquery-1.11.3.js"></script>
<script type="text/javascript">
	// loading icon indicator
   $(window).load(function() {
        $(".loader").fadeOut('300');
   });

</script>

</body>
</html>
