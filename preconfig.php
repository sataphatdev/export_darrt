<?php
ini_set("display_errors", 0);
$filename = 'config.php';

// get configuration values from config.php file
if(file_exists($filename)) {
	require_once("config.php");
	$r_dbname = DB_NAME;
	$r_uname = DB_USER;
	$r_pwd = DB_PASSWORD;
	$r_dbhost = DB_HOST;
	$r_dbms = DBMS_TYPE;
}

if($_POST){

echo '<h1>ตั้งค่าการเชื่อมต่อฐานข้อมูล.</h1>';
	//POST
	$dbname = (empty($_REQUEST['dbname'])) ? 'test' : $_REQUEST['dbname'];
	$uname = (empty($_REQUEST['uname'])) ? 'root' : $_REQUEST['uname'];
	$pwd = (empty($_REQUEST['pwd'])) ? '1234' : $_REQUEST['pwd'];
	$dbhost = (empty($_REQUEST['dbhost'])) ? 'localhost' : $_REQUEST['dbhost'];
	$dbms = (empty($_REQUEST['dbms'])) ? '1' : $_REQUEST['dbms'];

	//write value to Configuration file
	$tag_open = "<?php\r\n";
	$a0 = "define('DB_NAME', '$dbname');\r\n";
	$a1 = "define('DB_USER', '$uname');\r\n";
  $a2 = "define('DB_PASSWORD', '$pwd');\r\n";
  $a3 = "define('DB_HOST', '$dbhost');\r\n";
	$a4 = "define('DBMS_TYPE', '$dbms');\r\n";
	$tag_close = "?>\r\n";

	// array value configuration
	$arr_config = array($tag_open,$a0,$a1,$a2,$a3,$a4,$tag_close);

	$handle = fopen('config.php', 'w' );
		foreach( $arr_config as $line ) {

			$create=fwrite( $handle, $line );
		}
		fclose( $handle );


	if($create){
		$msg = '<font color="green"><b>ไฟล์การติดตั้งถูกสร้าง/บันทึกแล้ว</b></font> <a href="export_xp.php">ไปยังหน้าส่งออกข้อมูล</a>';
	}else{
		$msg = '<font color="red"><b>Failed : ไฟล์การติดตั้งยังไม่ถูกสร้าง.</b></font> กรุณาลองอีกครั้ง.';
	}

}
?>

<html>
<head>
<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<form method="post" action="preconfig.php">
	<p>กำหนดค่าการติดตั้ง</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname">ประเภทฐานข้อมูล</label></th>
			<td>
					<input type="radio" name="dbms" <?php echo ($r_dbms=='1')?'checked':'' ?> value="1">Mysql
					<input type="radio" name="dbms" <?php echo ($r_dbms=='2')?'checked':'' ?> value="2">PostgreSQL
					<input type="radio" name="dbms" <?php echo ($r_dbms=='3')?'checked':'' ?> value="3">MSSQL</td>
			<td>*ค่าพื้นฐาน MySQL</td>
		</tr>
		<tr>
			<th scope="row"><label for="dbname">ชื่อฐานข้อมูล</label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" placeholder="wordpress" <?php if(isset($r_dbname)){ echo "value='".$r_dbname."'";} ?> /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="uname">ชื่อผู้ใช้งาน(ฐานข้อมูล)</label></th>
			<td><input name="uname" id="uname" type="text" size="25" placeholder="example username" <?php if(isset($r_uname)){ echo "value='".$r_uname."'";} ?> /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd">รหัสผ่าน(ฐานข้อมูล)</label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" placeholder="example password" autocomplete="off" <?php if(isset($r_pwd)){ echo "value='".$r_pwd."'";} ?> /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost">Database Host</label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" placeholder="localhost" <?php if(isset($r_dbhost)){ echo "value='".$r_dbhost."'";} ?> /></td>
			<td> เช่น localhost , 192.168.1.1</td>
		</tr>

	</table>
	<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>
</form>
<?php
if(isset($msg)){ echo $msg;}


?>
</body>
</html>
