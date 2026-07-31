<?php
  	include "mssql-dbnew.php";
	
	$kdcabang = base64_decode($_GET['cb']);
	$kdcara = base64_decode($_GET['cr']);
	$kdedc = base64_decode($_GET['ed']);
	$kdbank = base64_decode($_GET['bn']);
	$kdkartu = base64_decode($_GET['jn']);
	$kdcicil = base64_decode($_GET['cl']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);

	$tgl1 = base64_decode($_GET['tg1']);
	$tgl2 = base64_decode($_GET['tg2']);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdcara ==''){$kdcara = 'ALL';}
	if ($kdedc ==''){$kdedc = 'ALL';}
	if ($kdbank ==''){$kdbank = 'ALL';}
	if ($kdkartu ==''){$kdkartu = 'ALL';}
	if ($kdcicil ==''){$kdcicil = 'ALL';}
	
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
			
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

            $tsql = "select * from dbo.f_reportpembayaran('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdcara."', '".$kdedc."', '".$kdbank."', '".$kdkartu."', '".$kdcicil."')" ;
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
            	<th colspan="12" align="left"><h2>LAPORAN TRADE IN (<?php echo $kdcabang;  ?>)</h2></th>
            </tr>
        	<tr>
            	<th colspan="12" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>       
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th style="border:1px solid #000" colspan="4">Keterangan</th>
            	<th width="75" style="border:1px solid #000">Total</th>
            	<th width="75" style="border:1px solid #000">MDR</th>
            </tr>
        </thead>
        <tbody >
            <?php
			 $i = 0 ;

			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				$i = $i + 1 ;
				$depr = (( $row['vf_asal'] - $row['vf_total'] ) / $row['vf_asal'] ) * 100 ;
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
					else if ($row['vf_level']=='5')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" colspan="3" width="225"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000"" align="left" width="125"><?php echo $row['vf_nama']; ?></td>	
                        					
                    <?php
                    }
					else if ($row['vf_level']=='6')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" colspan="3" width="225"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000"" align="left" width="125"><?php echo $row['vf_nama']; ?></td>	
                        					
                    <?php
                    }
                    ?> 
                   <td style="border-left:1px solid #000;border:1px solid #000" align="center"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></td>
                   <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_mdr'], 0, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        	
        </tbody>
	</table>

