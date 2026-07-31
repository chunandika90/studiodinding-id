<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$scby = $_GET['by'];
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	$tsql = "select a.*, b.m_nama as namacabang from mssales a, msmaster b where b.m_type = 'STORE' and a.m_aktif = 1 and a.m_cabang = b.m_kode " ;
	if ($kdcabang != ''){ $tsql = $tsql." and a.m_cabang = '".$kdcabang."' "; }
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'kode'){ $tsql = $tsql." and a.m_kode like '".$sctx."%' "; }
		if ($scby == 'nama'){ $tsql = $tsql." and a.m_nama like '".$sctx."%' "; }
	}
	$tsql = $tsql." order by a.m_cabang asc, a.m_nama asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="4">
            	<div class="pull-left"><h4>LIST JR</h4></div>
                <div class="container input-append pull-right" style="width: auto; padding: 0 10px;">
                    <input type="text" class="input-medium search-query" id="inputText" placeholder="Search Text" value="<?php echo $sctx ; ?>" onChange="oc_sales()" />
                    <select name="searchby" id="searchby" class="input-small">
                        <option value="kode" <?php if($scby == 'kode'){ ?> selected="selected" <?php } ?> >Kode</option>
                        <option value="nama" <?php if($scby == 'nama'){ ?> selected="selected" <?php } ?> >Nama</option>
                    </select>
                    <button class="btn" onClick="oc_sales('<?php echo $prm ; ?>')">Search</button>
					<?php
                    if (substr($xparam[3],0,1) == 'Y')
                    {
                        ?>
	                    <button class="btn" onClick="edit_modal('<?php echo $prm ; ?>','')">New Sales</button>
                        <?php
					}
					?>
                </div>
            </th>
        </tr>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Login-ID</th>
            <th>Cabang</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="view_modal('<?php echo $prm; ?>','<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['m_login']; ?></td>
                    <td><?php echo $row['namacabang']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>