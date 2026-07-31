<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kode = $_GET['vkode'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	//echo $prm ."<br>";
	
	$tsql = "select *
			 from master_upholstery_category where  m_kode = '".$kode."' " ;
	$stmt = $con_dbnew->query($tsql);
	$row = $stmt->fetch_assoc()
	//echo $tsql;


?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $row['m_nama'] ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="tanggal">Kode</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_kode" value="<?php echo $row['m_kode']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Nama</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_nama" value="<?php echo $row['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Nama</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_harga" value="<?php echo $row['m_harga']; ?>" disabled />
            </div>
        </div>
    </form>				
</div>
<div class="modal-footer">
	<?php
    if(( substr($xparam[3],1,1) == 'Y' ))
    {
        ?>
    	<button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $kode ; ?>')">Edit</button>
    	<?php
	}
	if(( substr($xparam[3],2,1) == 'Y' ))
	{
		?>
	    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $kode ; ?>')">Hapus</button>
        <?php
	}
	?>
    <button class="btn" data-dismiss="modal">Close</button>
</div>
