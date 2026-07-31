<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
   include "mssql-dbnew.php" ;
	$dept = $_GET['dept'];
	$kode = $_GET['kd'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	$tsqldept = " select m_kode, m_nama from msdept where m_kode = '".$dept."' "  ;
	$stmtdept = sqlsrv_query($con_dbnew, $tsqldept);
	if( $stmtdept === false)
	{
		 echo "Error in query preparation/execution.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	$rowdept = sqlsrv_fetch_array( $stmtdept, SQLSRV_FETCH_ASSOC);
	
	$tsql0 = " select m_kode, m_nama, m_head from msdivisi where m_kode = '".$kode."' "  ;
	$stmt0 = sqlsrv_query($con_dbnew, $tsql0);
	if( $stmt0 === false)
	{
		 echo "Error in query preparation/execution.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	$row0 = sqlsrv_fetch_array( $stmt0, SQLSRV_FETCH_ASSOC);

 	$tsql = " select m_kode, m_nama from msdivisi2 where m_divisi = '".$kode."' order by m_kode asc" ;
	$stmt = sqlsrv_query($con_dbnew, $tsql);
	if( $stmt === false)
	{
		 echo "Error in query preparation/execution.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	
?>
<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="15%">Dept</td>
            <td width="40%"><b><?php echo $rowdept['m_nama']; ?></b></td>
        </tr>
        <tr>
            <td width="15%">Kode</td>
            <td width="40%"><b><?php echo $row0['m_kode']; ?></b></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td colspan="3"><?php echo $row0['m_nama']; ?></td>
        </tr>
        <tr>
            <td>Head</td>
            <td colspan="3"><?php echo $row0['m_head']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20">Kode SubDivisi</th>
            <th width="40">Nama SubDivisi</th>
        </tr>
    </thead>
    <tbody>
        <?php
			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                
                ?>
                <tr>
                    <td><?php echo $row['m_kode']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                   
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="10">
			<div>            
            <?php
			if($row['m_status'] != 'B')
			{
				?>
                <div class="pull-left" >
                <?php
				if(( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $dept; ?>','<?php echo $kode; ?>')">Edit</button>
                    <?php
				}
				
				?>
                </div>
               
                 <div class="pull-right" >
                <?php
				if(( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
                     
               		<button class="btn btn-danger" onclick="batal_msdivisi('<?php echo $prm; ?>','<?php echo $dept; ?>','<?php echo $kode; ?>')">Hapus</button>
                    <?php
				}
				
				?>
                </div>
				<?php
			}
			?>
			</div>
            </th>
        </tr>
    </tfoot>
</table>        