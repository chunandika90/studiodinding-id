<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');

  	include "mssql-dbnew.php";

	$kdprog = $_SESSION['program'];
	$login = $_SESSION['loginid'];
	$tgl = date('Y-m-d 23:59:59');

    $tkursLD = "select *, convert(varchar(10),m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam from msrate where m_kode = 'LD' and m_tanggal = ( select max(m_tanggal) from msrate where m_kode = 'LD' and  m_tanggal <= '".$tgl."' )";
    $stmtLD= sqlsrv_query( $con_dbnew, $tkursLD);
    $rowLD = sqlsrv_fetch_array( $stmtLD, SQLSRV_FETCH_ASSOC);
?>
<table class="table table-bordered table-striped table-condensed">
    <thead>
        <tr>
            <th width="25%"></td>
            <th width="10%"><div align="center">Jual</div></td>
            <th width="10%"><div align="center">Beli</div></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Chain ( Yellow )</td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.895 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.72 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
        </tr>
        <tr>
            <td>Chain ( White )</td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.905 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.73 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
        </tr>
        <tr>
            <td>Non-Chain ( Yellow )</td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.935 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.72 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
        </tr>
        <tr>
            <td>Non-Chain ( White )</td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.945 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.73 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
        </tr>
        <tr>
            <td>Gold ( 99.99 )</td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 1.235 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
            <td align="center"><div align="center"><?php echo number_format(ceil($rowLD['m_jual'] * 0.97 / 1000) * 1000, 0, '.', ',') ; ?></div></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="font-size:11px;font-style:italic;color:#F00">
                <?php echo 'Last Update : '.$rowLD['co_tgl'].' '.$rowLD['co_jam']; ?>
            </th>
        </tr>
    </tfoot>
</table>
