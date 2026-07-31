<?php
  	include "mssql-dbnew.php";
	$dept = $_GET['dept'];
	$div = $_GET['div'];
?>

<select name="m_divisi2" id="m_divisi2" class="input-xlarge" onChange="oc_ediv2()">
    <option value="" >ALL</option>
<?php
	$tsqldiv2 = "select a.m_kode, a.m_nama from msdivisi2 a, msdivisi b where a.m_divisi = b.m_kode and b.m_dept = '".$dept."' and a.m_divisi = '".$div."' order by a.m_kode asc" ;
	$stmtdiv2 = sqlsrv_query( $con_dbnew, $tsqldiv2);

    while( $rowdiv2 = sqlsrv_fetch_array( $stmtdiv2, SQLSRV_FETCH_ASSOC))
    {
       ?>
		<option value="<?php echo $rowdiv2['m_kode']; ?>"><?php echo $rowdiv2['m_nama']; ?></option>
       <?php
    }
?>                    
</select>            
