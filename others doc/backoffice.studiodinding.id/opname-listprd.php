<?php
  	include "mssql-dbnew.php";
	$kdcab = $_GET['kdcab'];
?>
<select name="periode" id="periode" class="input-medium" onChange="oc_periode()">
    <?php
    $tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_stockopname0 where m_status = 'A' and m_cabang = '".$kdcab."' order by co_periode desc" ;
    $stmtbulan = sqlsrv_query( $con_dbnew, $tsqlbulan);
    while( $rowbulan = sqlsrv_fetch_array( $stmtbulan, SQLSRV_FETCH_ASSOC))
    {
        ?>
        <option value="<?php echo $rowbulan['co_periode']; ?>" <?php if($rowbulan['co_periode'] == $periode){ ?> selected <?php } ?> ><?php echo $rowbulan['co_periode']; ?></option>
        <?php
    }
    ?>
</select>
