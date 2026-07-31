<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcab = $_GET['cb'];
	$periode = $_GET['pr'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>GET STOCK - OPNAME</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		
		$tgl = date("d/m/Y") ;
		$jam = date("H:i:s") ;

		$nama = $_SESSION['loginid'];
		$keterangan= '';
		
    ?>
	<form class="form-horizontal" method="post" action="opname-getstock4.php">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <td>Tanggal</td>
                        <td colspan="3">
                        	<input type="hidden" id="kdstore" name="kdstore" value="<?php echo $_GET['cb']; ?>" />
                        	<input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                        	<input type="hidden" id="param" name="param" value="<?php echo $_GET['prm']; ?>" />
                        	<input type="text" id="m_cabang" name="m_cabang" value="<?php echo $kdcab ; ?>" readonly/>
                        	<input class="input-large" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly/>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3"><input class="input-large" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required /></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $keterangan; ?>" /></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8">
                        <div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                            </div>
                        </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>

    </body>
</html>