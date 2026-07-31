<?php
//	session_start();
//	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
//	{
//		header('Location: ./index.php');
//	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Upload Data Frank</title>
	<script type="text/javascript" src="js/myjs.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>
<body>
<?php
  	include "mssql-trading.php";

?>
	<h3>Update Image</h3>
	<form action="upload-frank3gj.php" method="post" enctype="multipart/form-data">
        <table class="biasa" id="tblheader">
			<tr>
                <td>Cabang/Store</td>
                <td><input type="text" name="cabang" id="cabang" value="" /></td>
            </tr>
			<tr>
                <td>Data Stock</td>
                <td><input type="file" name="file1" id="file1" /></td>
            </tr>
			<tr>
                <td>Data Sales</td>
                <td><input type="file" name="file2" id="file2" /></td>
            </tr>
        </table>
        <br />
        <input class="tombol" type="submit" name="submit1" id="submit1" value="Save" />
        <input class="tombol" type="reset" name="cancel1" id="cancel1" value="Cancel" />
	</form>

<?php
	sqlsrv_free_stmt( $stmt);
	sqlsrv_free_stmt( $stmtx);
	sqlsrv_close( $conn);	
?>
</body>
</html>