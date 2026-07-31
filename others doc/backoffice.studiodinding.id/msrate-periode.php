<?php
  	include "mssql-dbnew.php";
	$kdkurs = $_GET['kd'];

	$tsqljr = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from msrate where m_kode = '".$kdkurs."' order by co_periode desc" ;
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
?>

<select name="periode" id="periode" class="input-medium">
<?php
    while( $rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC))
    {
       ?>
        <option value="<?php echo $rowjr['co_periode']; ?>" ><?php echo $rowjr['co_periode']; ?></option>
       <?php
    }
?>                    
</select>            
