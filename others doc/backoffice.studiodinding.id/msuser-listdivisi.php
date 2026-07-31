<?php
  	include "mssql-dbnew.php";
	$dept = $_GET['dept'];
?>

<select name="kddiv" id="kddiv" class="input-medium" onChange="oc_div()">
    <option value="" >ALL</option>
<?php
	$tsqldiv = "select distinct a.m_divisi, b.m_nama from msuser a, msdivisi b where a.m_divisi = b.m_kode and a.m_dept = '".$dept."' order by a.m_divisi asc" ;
	$stmtdiv = sqlsrv_query( $con_dbnew, $tsqldiv);

    while( $rowdiv = sqlsrv_fetch_array( $stmtdiv, SQLSRV_FETCH_ASSOC))
    {
       ?>
		<option value="<?php echo $rowdiv['m_divisi']; ?>"><?php echo $rowdiv['m_nama']; ?></option>
       <?php
    }
?>                    
</select>            
