<?php
  	include "mssql-dbnew.php";
	$cabang = $_GET['cb'];

	$tsqljr = "select distinct a.m_kode, a.m_nama from mssales a where a.m_cabang = '".$cabang."' and m_aktif = 1 order by a.m_nama asc" ;
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
?>

<select name="kdsales" id="kdsales" class="input-medium">
    <option value="" >ALL</option>
<?php
    while( $rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC))
    {
       ?>
		<option value="<?php echo $rowjr['m_kode']; ?>"><?php echo $rowjr['m_nama']; ?></option>
       <?php
    }
?>                    
</select>            
