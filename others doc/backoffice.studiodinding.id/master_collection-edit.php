<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$vkode = $_GET['vkode'];
	$prm = $_GET['prm'];
	
	//echo $kode;
	
	if ($vkode == '')
	{
		$vkode = '' ;
		$vnama = '' ;
		$vitem = '' ;
		$tnew = 'Y';
	}
	else
	{
		
		$tsql = "select  a.m_kode, a.m_nama , a.m_kode_item from master_collection a where m_kode = '".$vkode."'   " ;
		$stmt = $con_dbnew->query($tsql);
		//echo $tsql ."<br>";
	
		$tnew = 'T';
		
		if ($stmt && $row = $stmt->fetch_assoc()) {
		$tnew = 'T';
		$vkode = $row['m_kode'];
		$vnama = $row['m_nama'];
		$vitem = $row['m_kode_item'];
		} else {
			// fallback jika query gagal atau data tidak ditemukan
			$tnew = 'Y';
			$vkode = '';
			$vnama = '';
			$vitem = '';
		}
	}
?>

<form class="form-horizontal" method="post" action="master_collection-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $vnama ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="tanggal">Kode</label>
            <div class="controls">
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $tnew; ?>" />
            <input class="input-medium" type="text" id="m_kode" name="m_kode" value="<?php echo $vkode; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nama</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_nama" name="m_nama" value="<?php echo $vnama; ?>" required />
            </div>
        </div>
		<div>
            <label class="control-label" for="kursbeli">Item</label>
            <div class="controls">
				<select name="m_item" id="m_item" class="input-medium">
					<?php
					$tsqlagama = "select m_kode, m_nama from master_item order by m_kode asc" ;
					$stmtagama = $con_dbnew->query($tsqlagama);
                    while($rowagama = $stmtagama->fetch_assoc())
					{
						?>
						<option value="<?php echo $rowagama['m_kode']; ?>" <?php if ($rowagama['m_kode'] == $vitem){ ?> selected="selected" <?php }   ?> ><?php echo $rowagama['m_nama']; ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
      <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
