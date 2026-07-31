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

	$tsql = "select a.* from msuser a where a.m_status = 'A' " ;
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'login'){ $tsql = $tsql." and a.m_login like '".$sctx."%' "; }
		if ($scby == 'nama'){ $tsql = $tsql." and a.m_nama like '".$sctx."%' "; }
	}
	$tsql = $tsql." order by  a.m_group asc " ;
	$stmt = $con_dbnew->query($tsql);
	
	//echo $tsql."<br>";
	
?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="1"><h4>USER - LIST</h4></th>
            <th colspan="2">
                <div class="container input-append pull-right" style="width: auto; padding: 0 10px;">
                    <input type="text" class="input-medium search-query" id="inputText" placeholder="Search Text" value="<?php echo $sctx ; ?>" onChange="oc_search()" />
                    <select name="searchby" id="searchby" class="input-small">
                        <option value="login" <?php if ($scby == 'login') { ?> selected="selected" <?php } ?> >login</option>
                        <option value="nama" <?php if ($scby == 'nama') { ?> selected="selected" <?php } ?> >nama</option>
                    </select>
                    <button class="btn" onClick="oc_search()">Search</button>
					<?php
                    if (substr($xparam[3],0,1) == 'Y')
                    {
                        ?>
                        <button class="btn" onClick="edituser_modal('')">New User</button>
                    	<?php
					}
					?>
                </div>
            </th>
        </tr>
        <tr>
            <th>Login-ID</th>
            <th>Nama</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = $stmt->fetch_assoc())
            {
                ?>
                <tr>
					<td onClick="viewuser_modal('<?php echo $prm; ?>','<?php echo $row['m_login']; ?>')" style="cursor:pointer"><?php echo $row['m_login']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>