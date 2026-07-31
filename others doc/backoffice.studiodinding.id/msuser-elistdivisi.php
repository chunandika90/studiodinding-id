<?php
  	include "mssql-dbnew.php";
	$dept = $_GET['dept'];
?>

<select name="m_divisi" id="m_divisi" class="input-xlarge" onChange="oc_ediv()">
    <option value="" >ALL</option>
<?php
	$tsqldiv = "select m_kode, m_nama from msdivisi where m_dept = '".$dept."' order by m_kode asc" ;
	$stmtdiv = sqlsrv_query( $con_dbnew, $tsqldiv);
    while( $rowdiv = sqlsrv_fetch_array( $stmtdiv, SQLSRV_FETCH_ASSOC))
    {
       ?>
		<option value="<?php echo $rowdiv['m_kode']; ?>"><?php echo $rowdiv['m_nama']; ?></option>
       <?php
    }
?>                    
</select>            
