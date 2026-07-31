<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$kdcab = $_GET['cb'];
	$nomor = $_GET['nm'];
	$periode = $_GET['pr'];
	$prm = $_GET['prm'];
	
	// Cek dulu sisa yg belum dibayar
	$tsqlcek = "select isnull(sum((m_qty * m_harga) - m_discount - m_discount2 - m_discount3 - m_discount4),0) as cototal from t_pos2 where m_cabang = '".$kdcab."' and m_nomor = '".$nomor."'";
	$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
	$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC);

//	echo $tsqlcek ;
	// Cek dulu sisa yg belum dibayar
	$tsqlcek2 = "select isnull(sum(m_jumlah),0) as cobayar from t_pos3 where m_cabang = '".$kdcab."' and m_nomor = '".$nomor."'";
	$stmtcek2 = sqlsrv_query( $con_dbnew, $tsqlcek2);
	$rowcek2 = sqlsrv_fetch_array( $stmtcek2, SQLSRV_FETCH_ASSOC);
	
	$sisa = $rowcek['cototal'] - $rowcek2['cobayar'];
	if ($sisa < 0){$sisa = 0 ;}
?>

<form class="form-horizontal" method="post" action="pos-simpan2.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $kdcab.'-'.$nomor ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="carabayar">Cara Bayar</label>
            <div class="controls">
            <input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
            <input type="hidden" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" />
            <input type="hidden" id="periode" name="periode" value="<?php echo $periode; ?>" />
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <select name="m_carabayar" id="m_carabayar" class="input-medium">
                <?php
                $tsqlcara = "select m_kode, m_nama from msmaster where m_type = 'CARABAYAR' and m_status = 'A' order by m_kode asc" ;
                $stmtcara = sqlsrv_query( $con_dbnew, $tsqlcara);
                while( $rowcara = sqlsrv_fetch_array( $stmtcara, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowcara['m_kode']; ?>" ><?php echo $rowcara['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="edc">EDC</label>
            <div class="controls">
            <select name="m_edc" id="m_edc" class="input-medium">
				<option value="">-</option>
                <?php
                $tsqledc = "select m_kode, m_nama from msmaster where m_type = 'BANK' and m_status = 'A' order by m_kode asc" ;
                $stmtedc = sqlsrv_query( $con_dbnew, $tsqledc);
                while( $rowedc= sqlsrv_fetch_array( $stmtedc, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowedc['m_kode']; ?>"><?php echo $rowedc['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="bank">BANK</label>
            <div class="controls">
            <select name="m_bank" id="m_bank" class="input-medium">
				<option value="">-</option>
                <?php
                $stmtbank = sqlsrv_query( $con_dbnew, $tsqledc);
                while( $rowbank= sqlsrv_fetch_array( $stmtbank, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowbank['m_kode']; ?>" ><?php echo $rowbank['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nokartu">No.Kartu</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_nokartu" name="m_nokartu" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nmkartu">Nama Kartu</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_nmkartu" name="m_nmkartu" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nokartu">Jenis Kartu</label>
            <div class="controls">
            <select name="m_jnkartu" id="m_jnkartu" class="input-medium">
				<option value="">-</option>
                <?php
                $tsqljns = "select m_kode, m_nama from msmaster where m_type = 'JENISKARTU' and m_status = 'A' order by m_kode asc" ;
                $stmtjns = sqlsrv_query( $con_dbnew, $tsqljns);
                while( $rowjns = sqlsrv_fetch_array( $stmtjns, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowjns['m_kode']; ?>" ><?php echo $rowjns['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nokartu">Jenis Cicilan</label>
            <div class="controls">
            <select name="m_cclkartu" id="m_cclkartu" class="input-medium">
				<option value="">-</option>
                <?php
                $tsqlccl = "select m_kode, m_nama from msmaster where m_type = 'CICILKARTU' and m_status = 'A' order by m_kode asc" ;
                $stmtccl = sqlsrv_query( $con_dbnew, $tsqlccl);
                while( $rowccl = sqlsrv_fetch_array( $stmtccl, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowccl['m_kode']; ?>" ><?php echo $rowccl['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Jumlah</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_jumlah" name="m_jumlah" value="<?php echo number_format($sisa, 0, '.', ',') ; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Lain-2</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_mdr" name="m_mdr" value="0" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveu" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
