<?php
  	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdsales = $_GET['sl'];
	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdsales ==''){$kdsales = 'ALL';}
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'PRODUCT';}
	
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';


	if ($kdby == 'PRODUCT')
			{
            	$tsql = "select * from dbo.f_reportpos('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdsales."')" ;
			}
			else if ($kdby == 'JR')
			{
            	$tsql = "select * from dbo.f_reportpos2('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdsales."')" ;
			}
            $stmt = sqlsrv_query( $con_dbnew, $tsql);


?>


<style type="text/css" media="print, screen">
thead
{
	display:table-header-group;	
}
tbody
{
	display:table-row-group;	
}
</style>

	<table style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    	<thead>
        	<tr>
            	<th colspan="13" align="left"><h2>LAPORAN Penjualan (<?php echo $kdcabang;?>)</h2></th>
            </tr>
        	<tr>
            	<th colspan="13" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>       
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th style="border:1px solid #000" colspan="4">Keterangan</th>
            	<th width="50" style="border:1px solid #000">Qty</th>
            	<th width="80" style="border:1px solid #000">Disc.Reguler</th>
            	<th width="80" style="border:1px solid #000">Disc.VIP</th>
            	<th width="50" style="border:1px solid #000">Disc.Promo</th>
            	<th width="80" style="border:1px solid #000">Pembulatan</th>
            	<th width="50" style="border:1px solid #000">Total Disc</th>
            	<th width="50" style="border:1px solid #000">% Disc</th>
            	<th width="50" style="border:1px solid #000">Net Sales</th>
            </tr>
        </thead>
        <tbody >
            <?php
			 $i = 0 ;


			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				$i = $i + 1 ;
				$totdisc = ($row['vf_discount']+$row['vf_discount2']+$row['vf_discount3']+$row['vf_discount4']);
				$pctdisc = ( $totdisc / $row['vf_total'] ) * 100 ;
				$netsales = $row['vf_total'] - $totdisc ;
				
				$tampilanfont = '';
				if ($row['vf_level']=='1')
				{
					$tampilanfont = 'style="font-weight:bold"';
				}
				else if ($row['vf_level']=='2')
				{
					$tampilanfont = 'style="font-weight:bold;font-style:italic"';
				}
				else if ($row['vf_level']=='3')
				{
					$tampilanfont = 'style="font-style:italic"';
				}


				
				
                ?>
                <tr height="25" <?php echo $tampilanfont; ?> data-level="<?php echo $row['vf_level']; ?>" id="rowke<?php echo $i; ?>">
					<?php
                    if ($row['vf_level']=='1')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" align="left" colspan="4" width="350" ><?php echo $row['vf_nama']; ?></td>
                    <?php
                    }
                    else if ($row['vf_level']=='2')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" width="75"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000" align="left" colspan="3" width="275"><?php echo $row['vf_nama']; ?></td>
                    <?php
                    }
                    else if ($row['vf_level']=='3')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" colspan="2" width="150"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000" align="left" colspan="2" width="200"><?php echo $row['vf_nama']; ?></td>
                    <?php
                    }
                    else if ($row['vf_level']=='4')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" colspan="3" width="225"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000"" align="left" width="125"><?php echo $row['vf_nama']; ?></td>						
                    <?php
                    }
                    ?> 
                    <td style="border-left:1px solid #000;border:1px solid #000" align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_discount'], 0, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_discount2'], 0, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_discount3'], 0, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_discount4'], 0, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($pctdisc, 2, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($netsales, 0, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        	
        </tbody>
	</table>

