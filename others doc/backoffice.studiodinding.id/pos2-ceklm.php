<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$rowke = $_GET['rk'];
	$tsql = "select * from msmaster where m_type = 'ITEM' and left(m_kode2,2) = 'LM' order by m_kode asc " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="30%">Type LM</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$sqlharga = "select m_beli, m_jual from mshargalm2 where m_kode = '".$row['m_kode']."' and m_nomor = (select max(m_nomor) from mshargalm2 where m_kode = '".$row['m_kode']."') " ;
				$stmtharga = sqlsrv_query( $con_dbnew, $sqlharga);
				$rowharga = sqlsrv_fetch_array( $stmtharga, SQLSRV_FETCH_ASSOC);
				$dumb = explode('-',$row['m_kode2']);
                ?>
                <tr>
                    <td onClick="selectlm('<?php echo $rowke; ?>','<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>','<?php echo $dumb[1]; ?>','<?php echo $rowharga['m_beli']; ?>','<?php echo $rowharga['m_jual']; ?>')" style="cursor:pointer"><?php echo $row['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


