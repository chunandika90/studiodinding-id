<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$sctx = $_GET['tx'];

	if ( $sctx != '' )
	{
		$tsql = "select a.m_kode, a.m_nama, a.m_alamat, a.m_kota, a.m_telepon1, a.m_telepon2 from mscustomer a where a.m_nama like '%".$sctx."%'" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
	}
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20%">Nama</th>
            <th width="30%">Alamat</th>
            <th width="20%">Telepon</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="selectcust('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>','<?php echo $row['m_alamat']; ?>','<?php echo $row['m_kota']; ?>','<?php echo $row['m_telepon1']; ?>','<?php echo $row['m_telepon2']; ?>')" style="cursor:pointer"><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['m_alamat'].'<br/>'.$row['m_kota']; ?></td>
                    <td><?php echo $row['m_telepon1'].' - '.$row['m_telepon2']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


