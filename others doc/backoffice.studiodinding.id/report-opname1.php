<?php
	session_start();
	include "mssql-dbnew.php" ;

	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdstock = $_GET['kdst'];
	$kdby = $_GET['kdby'];
	$periode  = $_GET['pr'];
	$soid = $_GET['so'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdstock ==''){$kdstock = 'ALL';}

?>
	<table>
    	<tr>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">Entry Opname</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname1('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);
                
                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                           <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','1','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
            <td width="10"></td>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">Data Stock</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname2('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);
                
                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                           <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','2','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
            <td width="10"></td>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">SO Ada, Stock Tidak ada</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname3('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);
                
                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                            <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','3','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
            <td width="10"></td>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">SO Tidak Ada, Stock ada</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname4('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);
                
                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                            <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','4','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">Tidak ada Gambar</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname5('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);

                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                            <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','5','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
            <td width="10"></td>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">Beda Gambar</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname6('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);
                
                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                            <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','6','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
            <td width="10"></td>
        	<td valign="top">
                <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th colspan="2">Beda Bandrol</th>
                        </tr>                
                        <tr>
                            <th width="175"><div align="center">Ket.</div></th>
                            <th width="50"><div align="right">Qty</div></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tsql = "select * from dbo.f_reportopname7('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."', '".$soid."', '".$kdby."')" ;
                    $stmt = sqlsrv_query( $con_dbnew, $tsql);
                
                    $i = 0 ;
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {	
                        $i = $i + 1 ;
                        ?>
                        <tr height="25px">
                            <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                            <td><div align="center" style="cursor:pointer" onclick="oc_detail('<?php echo $prm; ?>','7','<?php echo $row['vf_kode']; ?>')"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>


