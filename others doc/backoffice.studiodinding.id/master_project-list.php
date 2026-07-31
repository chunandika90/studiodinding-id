<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	$tsql = "	select 	a.*
				from 	master_project a
				where 	a.m_kode is not null" ;
	if ($sctx != ''){ $tsql = $tsql." and a.m_nama like   '%".$sctx."%' "; }
	$tsql = $tsql." order by a.m_nama asc" ;
	$stmt = $con_dbnew->query($tsql);
?>
<div style="overflow:auto;overflow-x:hidden;max-height:800px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="6">
            	<div class="pull-left"><h4>MASTER ITEM</h4></div>
            </th>
        </tr>
        <tr>
            <th width="12%">Kode</th>
            <th width="17%">Nama</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = $stmt->fetch_assoc())
            {
				
                ?>
                <tr>
                    <td onclick="view_modal('<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>                    
                    <td><?php echo $row['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>