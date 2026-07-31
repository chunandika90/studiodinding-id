<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kode = $_GET['kode'];

	$tsql = "select a.*, convert(varchar(10),a.m_tglmember,103) as co_tglmember, convert(varchar(10),a.m_tgllahir,103) as co_tgllahir, b.m_nama as namacabang from mscustomer a, msmaster b where b.m_type = 'STORE' and a.m_cabang = b.m_kode and a.m_kode = '".$kode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

	$tsqljr = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'" ;
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
	$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC);

	$tsqlstat =  "select m_nama from msmaster where m_type = 'STATCUST' and  m_kode = '".$row['m_status']."'" ;
	$stmtstat = sqlsrv_query( $con_dbnew, $tsqlstat);
	$rowstat = sqlsrv_fetch_array( $stmtstat, SQLSRV_FETCH_ASSOC);

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $kode ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <table class="table table-condensed">
            <tbody>
                <tr>
                    <td>Nama</td>
                    <td colspan="3"><input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>" readonly/></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td colspan="3"><textarea class="input-xlarge" name="m_alamat" id="m_alamat" cols="150" rows="2" disabled="disabled"><?php echo $row['m_alamat']; ?></textarea></td>
                </tr>
                <tr>
                    <td>Kota</td>
                    <td colspan="3"><input class="input-medium" type="text" id="m_kota" name="m_kota" value="<?php echo $row['m_kota']; ?>" readonly/></td>
                </tr>
                <tr>
                    <td>No.Telepon</td>
                    <td colspan="3">
                    	<input class="input-medium" type="text" id="m_telepon1" name="m_telepon1" value="<?php echo $row['m_telepon1']; ?>" readonly/>
                    	<input class="input-medium" type="text" id="m_telepon2" name="m_telepon2" value="<?php echo $row['m_telepon2']; ?>" readonly/>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td colspan="3"><input class="input-large" type="text" id="m_email" name="m_email" value="<?php echo $row['m_email']; ?>" readonly/></td>
                </tr>
                <tr>
                    <td>Store</td>
                    <td><input class="input-medium" type="text" id="m_cabang" name="m_cabang" value="<?php echo $row['namacabang']; ?>" readonly/></td>
                    <td>JR</td>
                    <td><input class="input-medium" type="text" id="m_kodesales" name="m_kodesales" value="<?php echo $rowjr['m_nama']; ?>" readonly/></td>
                </tr>
                <tr>
                    <td>Member</td>
                    <td><input class="input-medium" type="text" id="m_member" name="m_member" value="<?php echo $row['m_member']; ?>" readonly/></td>
                    <td>Tgl.Member</td>
                    <td><input class="input-medium" type="text" id="m_tglmember" name="m_tglmember" value="<?php echo $row['co_tglmember']; ?>" readonly/></td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td><input class="input-medium" type="text" id="m_agama" name="m_agama" value="<?php echo $row['m_agama']; ?>" readonly/></td>
                    <td>Status</td>
                    <td><input class="input-small" type="text" id="m_status" name="m_status" value="<?php echo $rowstat['m_nama']; ?>" readonly/></td>
                </tr>
                <tr>
                    <td>Tmp/Tgl.Lahir</td>
                    <td colspan="3">
                    	<input class="input-medium" type="text" id="m_tmplahir" name="m_tmplahir" value="<?php echo $row['m_tmplahir']; ?>" readonly/>
                    	<input class="input-medium" type="text" id="m_tgllahir" name="m_tgllahir" value="<?php echo $row['co_tgllahir']; ?>" readonly/>
					</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
<div class="modal-footer">
  <button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $kode ; ?>')">Edit</button>
  <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $kode ; ?>')">Hapus</button>
  <button class="btn" data-dismiss="modal">Close</button>
</div>
