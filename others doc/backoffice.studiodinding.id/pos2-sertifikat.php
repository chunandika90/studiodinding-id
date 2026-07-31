<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Certificate</title>
</head>

<body>
<?php
	include "phpfunction.php";
    include "mssql-dbnew.php" ;
	$kdstore = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);
	$kdbrg = base64_decode($_GET['kdbrg']);
	$productid = base64_decode($_GET['productid']);
		
	$tsql = "select a.* from t_stockdata a where a.m_kodebarang = '".$kdbrg."' and a.m_productid = '".$productid."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

	$tsqlitem = "select m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."' " ;
	$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
	$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC) ;

	$dumb = explode('-',$row['m_rubberid']);
	$kadar = $row['m_kadar'] ;
	if ( $kadar < 99.00) { $kadar = 0.75 ;}
	
?>

<table style="margin:0px">
	<tr>
    	<td valign="center">
            <img src="product-image.php?kd=<?php echo $kdbrg; ?>&kr=<?php echo $dumb[0]; ?>" width="90" height="90">
        </td>
        <td valign="top">
        	<table>
            	<tr>
                	<td colspan="5" style="font-size:16px; font-family:'Monotype Corsiva';font-style:italic;font-weight:bold"><?php echo $productid;  ?></td>
            	</tr>
            	<tr>
                	<td colspan="5" style="font-size:12px;font-family:'Monotype Corsiva';font-style:italic"><?php echo (number_format($kadar*100, 2, '.', ',')).'% '.number_format($row['m_netweight'], 2, '.', ',').'gr' ;  ?></td>
            	</tr>
                <?php
				$tsqld = "select a.* from t_stockdetail a	where a.m_kodebarang = '".$kdbrg."' and m_productid = '".$productid."'" ;
				$stmtd = sqlsrv_query( $con_dbnew, $tsqld);
				while( $rowd = sqlsrv_fetch_array( $stmtd, SQLSRV_FETCH_ASSOC))
				{
					$color = '' ;
					$clarity = '' ;
					if ($rowd['m_carat'] <= 0.3) 
					{ 
					
						if (substr($rowd['m_parcel'],-1) == '4')
						{
							$color = 'F';
							$nmclarity = 'VS';
						}
						else
						{
							$color = 'F';
							$nmclarity = 'VVS';
						}
					}
					?>
                    <tr style="font-size:6px;font-family:'Comic Sans MS', cursive">
                        <td><?php echo (number_format($rowd['m_butir'], 0, '.', ',')) ;  ?></td>
                        <td><?php echo ': '.(number_format($rowd['m_carat'], 3, '.', ',')) ;  ?></td>
                        <td><?php echo $rowd['m_shape'] ;  ?></td>
                        <td><?php echo $color ;  ?></td>
                        <td><?php echo $nmclarity;  ?></td>      
                    </tr>
                    <?php
				}
				?>
        	</table>
        </td>
    </tr>
    <tr style="margin-top:0px">
    	<td colspan="2" align="center" valign="top" style="font-family:'Monotype Corsiva';font-size:16px;line-height:70%">
        <?php echo $rowitem['m_nama'];  ?>
        </td>
    </tr>
    <tr>
    	<td colspan="2" align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif;font-size:8px;line-height:70%">
        <?php echo $row['m_rubberid'];  ?>
        </td>
    </tr>
</table>

</body>
</html>