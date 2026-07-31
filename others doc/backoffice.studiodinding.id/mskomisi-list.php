<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$prm = $_GET['prm'];

	$tsql = "select a.*, b.m_nama as namacabang from mskomisi a, msmaster b where b.m_type = 'STORE' and a.m_cabang = b.m_kode and a.m_cabang = '".$kdcabang."' order by a.m_periode desc " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="8">
            	<div class="pull-left"><h4>TABEL KOMISI</h4></div>
                <div class="container input-append pull-right" style="width: auto; padding: 0 10px;">
                    <button class="btn" onClick="edit_modal('<?php echo $prm ; ?>','')">New Komisi</button>
                </div>
            </th>
        </tr>
        <tr>
            <th>Periode</th>
            <th>Target</th>
            <th>Target/Pacing</th>
            <th>Incentive</th>
            <th>Kompensasi</th>
            <th>Full</th>
            <th>AE</th>
            <th>Nilai Point</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_periode']; ?></td>
                    <td><?php echo $row['m_point']; ?></td>
                    <td><?php echo 'PC-1 : '.$row['m_pct1'].'%<br/>'.'PC-2 : '.$row['m_pct2'].'%<br/>'.'PC-3 : '.$row['m_pct3'].'%<br/>'.'PC-4 : '.$row['m_pct4'].'%'; ?></td>
                    <td><?php echo 'Inc-1 : '.number_format($row['m_ict1'], 0, '.', ',').'<br/>'.'Inc-2 : '.number_format($row['m_ict2'], 0, '.', ',').'<br/>'.'Inc-3 : '.number_format($row['m_ict3'], 0, '.', ',').'<br/>'.'Inc-4 : '.number_format($row['m_ict4'], 0, '.', ','); ?></td>
                    <td><?php echo '<br/><br/>'.'Kompensasi-1 : '.number_format($row['m_kom1'], 0, '.', ',').'<br/>'.'Kompensasi-2 : '.number_format($row['m_kom2'], 0, '.', ',') ; ?></td>
                    <td><?php echo  number_format($row['m_full'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row['m_ae'] * 100, 2, '.', ',').'%'; ?></td>
                    <td><?php echo 'DJ : '.number_format($row['m_pointdj'], 0, '.', ',').'<br/>'.'PG : '.number_format($row['m_pointpg'], 0, '.', ',').'<br/>'.'LM : '.number_format($row['m_pointlm'], 0, '.', ',') ; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
