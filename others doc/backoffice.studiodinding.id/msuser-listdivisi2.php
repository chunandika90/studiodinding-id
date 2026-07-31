<?php
  	include "mssql-dbnew.php";
	$dept = $_GET['dept'];
	$div = $_GET['div'];
?>

<select name="kddiv2" id="kddiv2" class="input-medium" onChange="oc_div2()">
    <option value="" >ALL</option>
<?php
	$tsqldiv2 = "select distinct a.m_divisi2, b.m_nama from msuser a, msdivisi2 b where a.m_divisi2 = b.m_kode and a.m_dept = '".$dept."' and a.m_divisi = '".$div."' order by a.m_divisi2 asc" ;
	$stmtdiv2 = sqlsrv_query( $con_dbnew, $tsqldiv2);

    while( $rowdiv2 = sqlsrv_fetch_array( $stmtdiv2, SQLSRV_FETCH_ASSOC))
    {
       ?>
		<option value="<?php echo $rowdiv2['m_divisi2']; ?>"><?php echo $rowdiv2['m_nama']; ?></option>
       <?php
    }
?>                    
</select>            
