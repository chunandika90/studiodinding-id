<?php
  	include "mssql-dbnew.php";
	$kdcab = $_GET['kdcab'];
	$periode = $_GET['periode'];
?>

<select name="soid" id="soid" class="input-medium" onChange="oc_opname()">
    <?php
    $dumb = explode('-',$periode);
    $tsqlsoid = "select distinct m_cabang, m_nomor from t_stockopname0 where m_cabang = '".$kdcab."' and year(m_tanggal) = ".$dumb[0]." and month(m_tanggal) = ".$dumb[1]." order by m_nomor desc" ;
    $stmtsoid = sqlsrv_query( $con_dbnew, $tsqlsoid);
	echo $tsqlsoid ;
    while( $rowsoid = sqlsrv_fetch_array( $stmtsoid, SQLSRV_FETCH_ASSOC))
    {
        ?>
        <option value="<?php echo $rowsoid['m_nomor']; ?>" <?php if($rowsoid['m_nomor'] == $soid){ ?> selected <?php } ?> ><?php echo $rowsoid['m_nomor']; ?></option>
        <?php
    }
    ?>
</select>
