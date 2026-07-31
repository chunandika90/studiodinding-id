
<div>
<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');

	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

  	include "mssql-dbnew.php";

	$kdprog = $_SESSION['program'];
	$login = $_SESSION['loginid'];
	$tgl = date('Y-m-d 23:59:59');

    $tkursLD = "select *, convert(varchar(10),m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam from msrate where m_kode = 'LD' and m_tanggal = ( select max(m_tanggal) from msrate where m_kode = 'LD' and  m_tanggal <= '".$tgl."' )";
    $stmtLD= sqlsrv_query( $con_dbnew, $tkursLD);
    $rowLD = sqlsrv_fetch_array( $stmtLD, SQLSRV_FETCH_ASSOC);
	
	
	//echo $tkursLD ;
    ?>
    <table width="100%" border="1" style="font-size:11px">
        <tr>
            <th rowspan="2" width="7%" style="background-color:#FFC"><div align="right">Chain (Yellow)</div></th>
            <th width="5%" align="center">Jual</th>
            <th width="5%" align="center" style="color:#009">Beli</th>
            <th rowspan="2" width="7%" style="background-color:#FFC"><div align="right">Chain (White)</div></th>
            <th width="5%" align="center" >Jual</th>
            <th width="5%" align="center" style="color:#009">Beli</th>
            <th rowspan="2" width="7%" style="background-color:#FFC"><div align="right">Non-Chain (Yellow)</div></th>
            <th width="5%" align="center" >Jual</th>
            <th width="5%" align="center" style="color:#009">Beli</th>
            <th rowspan="2" width="7%" style="background-color:#FFC"><div align="right">Non-Chain (White)</div></th>
            <th width="5%" align="center" >Jual</th>
            <th width="5%" align="center" style="color:#009">Beli</th>
            <th rowspan="2" width="7%" style="background-color:#FFC"><div align="right">Gold 99.99</div></th>
            <th width="5%" align="center" >Jual</th>
            <th width="5%" align="center" style="color:#009">Beli</th>
        </tr>
        <tr>
            <td align="center" ><?php echo number_format(ceil($rowLD['m_jual'] * 0.895 / 1000) * 1000, 0, '.', ',') ; ?></td>
            <td align="center" style="color:#009"><?php echo number_format(ceil($rowLD['m_jual'] * 0.72 / 1000) * 1000, 0, '.', ',') ; ?></td>
            
            <td align="center" ><?php echo number_format(ceil($rowLD['m_jual'] * 0.905 / 1000) * 1000, 0, '.', ',') ; ?></td>
            <td align="center" style="color:#009"><?php echo number_format(ceil($rowLD['m_jual'] * 0.73 / 1000) * 1000, 0, '.', ',') ; ?></td>
            
            <td align="center" ><?php echo number_format(ceil($rowLD['m_jual'] * 0.935 / 1000) * 1000, 0, '.', ',') ; ?></td>
            <td align="center" style="color:#009"><?php echo number_format(ceil($rowLD['m_jual'] * 0.72 / 1000) * 1000, 0, '.', ',') ; ?></td>
            
            <td align="center" ><?php echo number_format(ceil($rowLD['m_jual'] * 0.945 / 1000) * 1000, 0, '.', ',') ; ?></td>
            <td align="center" style="color:#009"><?php echo number_format(ceil($rowLD['m_jual'] * 0.73 / 1000) * 1000, 0, '.', ',') ; ?></td>
            
            <td align="center" ><?php echo number_format(ceil($rowLD['m_jual'] * 1.235 / 1000) * 1000, 0, '.', ',') ; ?></td>
            <td align="center" style="color:#009"><?php echo number_format(ceil($rowLD['m_jual'] * 0.97 / 1000) * 1000, 0, '.', ',') ; ?></td>
        </tr>
    </table>	
</div>
<div style="width:100%">
    <div style="font-size:11px;font-style:italic;color:#F00;float:left">
        <?php echo 'Last Update : '.$rowLD['co_tgl'].' '.$rowLD['co_jam']; ?>
    </div>
    <div style="font-size:11px;font-style:italic;color:#00F" align="right">
        <?php echo 'Login ID : ( '.$_SESSION['cabang'].' ) '.$_SESSION['loginid']; ?>
    </div>
</div>
