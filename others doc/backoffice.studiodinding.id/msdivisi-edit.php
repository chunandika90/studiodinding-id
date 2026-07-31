<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$divisi =  base64_decode($_GET['kd']);
	$dept =  base64_decode($_GET['dp']);
	$prm  =	 base64_decode($_GET['prm']);
	
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>POS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		include "menu-pos2.php";
	
		if ($kode <> '')
		{
			$tsql0 = " select m_kode, m_nama, m_head from msdivisi where m_kode = '".$divisi."'  and m_dept = '".$dept."'"  ;
			//echo $tsql0;
			$stmt0 = sqlsrv_query($con_dbnew, $tsql0);
			$row0 = sqlsrv_fetch_array( $stmt0, SQLSRV_FETCH_ASSOC);
		
		}
		
    ?>
	<form class="form-horizontal" method="post" action="msdivisi-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 70%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="2"><h4><?php echo 'Edit Divisi - Sub Divisi ( '.$kdcab.' '.$Nama.' )' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                        <td width="20">Dept</td>
                        <td width="20">
                        	<select name="kddept" id="kddept" class="input-medium" >
								<?php
								$tsqldept = "select * from msdept a" ;
								if ($dept != '')
								{
									$tsqldept = $tsqldept." where m_kode = '".$dept."' order by a.m_kode asc" ;
								}
								else
								{
									$tsqldept = $tsqldept."  order by a.m_kode asc" ;
								}
								$stmtdept = sqlsrv_query( $con_dbnew, $tsqldept);
                                while( $rowdept = sqlsrv_fetch_array( $stmtdept, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowdept['m_kode']; ?>" <?php if($rowdept['m_kode'] == $dept){ ?> selected <?php } ?> ><?php echo $rowdept['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="20">Kode Divisi</td>
                        <td width="20">
                        	<input class="input-medium" type="text" id="m_kode" name="m_kode" value="<?php echo $row0['m_kode']; ?>" readonly/>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Divisi</td>
                        <td >
                            <div id="divinputcust" class="input-append">
                               <input class="input-large" type="text" id="m_nama" name="m_nama" value="<?php echo $row0['m_nama']; ?>"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Division Head</td>
                        <td>
                            <div id="divinputcust" class="input-append">
                               <input class="input-large" type="text" id="m_head" name="m_head" value="<?php echo $row0['m_head']; ?>"/>
                            </div>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 30%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama SubDivisi</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
						
						if ($kode != '')
						{
							$tsql = " select * from msdivisi2 where m_divisi = '".$divisi."' order by m_kode asc" ;
				
							$stmt = sqlsrv_query($con_dbnew, $tsql);
							while( $row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								?>
								<tr>
									<td>
										<input type="text" id="m_kode<?php echo $i; ?>" name="m_kode<?php echo $i; ?>" value="<?php echo $row2['m_kode']; ?>" readonly/></td>
									<td><input type="text" id="m_nama<?php echo $i; ?>" name="m_nama<?php echo $i; ?>" value="<?php echo $row2['m_nama']; ?>" /></td>
                                    <td>
                                    	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
                                    </td>
								</tr>
								<?php
							}
						}
						else
						{
							$addrow = 1 ;
							while( $addrow <= 3 )
							{
								$addrow = $addrow + 1 ;
								$i = $i + 1 ;
								?>
								<tr>
									<td><input class="input-small" type="text" id="m_kode<?php echo $i; ?>" name="m_kode<?php echo $i; ?>" value="" readonly/></td>
									<td><input class="input-large" type="text" id="m_nama<?php echo $i; ?>" name="m_nama<?php echo $i; ?>" value="" /></td>
                                    <td>
										<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
										<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
									</td>
								</tr>
								<?php
							}
						}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="12">
                        <div>
                            <div class="pull-left" >
                                <input type="button" class="btn btn-success" id="bt_tambah" value="Add Row" onclick="add_data()" />
                            </div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                                
                            	<input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                                <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $periode; ?>')" />
                            </div>
                        </div>
                        </th>
                    </tr>
                </tfoot>
            </table>        
		</div>
    </form>

    <div id="tempdata" class="hide">
        <span id="dataplu">
            <input type="text" id="cek_kodebarang" name="cek_kodebarang" value="" />
            <input type="text" id="cek_noplu" name="cek_noplu" value="" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_group" name="cek_group" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="0" />
            <input type="text" id="cek_karet" name="cek_karet" value="0" />
        </span>
    </div>         

    <div id="dialog-listcust">
        <span id="datacust">
        </span>
    </div>
    
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			$('#datetimepicker1').datetimepicker({
				language: 'en',
				pickTime: false
			});
			
		$(function() {
			$( "#dialog-listcust" ).dialog({
				autoOpen: false,
				height:500,
				width:1100,
				modal: true,
				buttons: {
					"Close": function() {
						$( this ).dialog( "close" );
						}
			}
			});
		});
			
		});
  	
		function cancel_data(vparam, kdstore,periode)
		{
			window.open("pos.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}


		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			document.getElementById('jumrow').value = jumrow;
			
			if (kodesales == '') 
			{
				alert('Kode sales belum di isi !!!');
				return false ;
			}
			else
			{
				return true ;
			}
			
		}

		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 1;
		  var row = tbl.insertRow(lastRow - 1);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_kode'+iteration+'" name="m_kode'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_nama'+iteration+'" name="m_nama'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_productid'+iteration).focus();
		}
	</script>

    </body>
</html>