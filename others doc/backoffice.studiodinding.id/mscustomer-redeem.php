<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kode = base64_decode($_GET['kd']);
	$kdstore = base64_decode($_GET['cb']);
	$kdsales  = base64_decode($_GET['sl']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm); 	
	$statedit = base64_decode($_GET['st']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Redeem Point</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbcmk.php" ;
		
		$tsql = "	select 	a.*, convert(varchar(10),a.m_tglmember,103) as co_tglmember, b.m_nama as costore, dbo.f_hitpoin('".$kode."') as copoin
					from 	mscustomer a, msstore b 
					where 	a.m_kode = '".$kode."' and 
							a.m_cabang = b.m_kode" ;
		$stmt = sqlsrv_query( $con_dbcmk, $tsql);
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

		$tglmember = $row['co_tglmember'] ;
		
		$nama = $row['m_nama'] ;
		$alamat = $row['m_alamat'] ;
		$kota = $row['m_kota'] ;
		$telepon1 = $row['m_telepon1'] ;
		$telepon2 = $row['m_telepon2'] ;
		$status = $row['m_status'] ;
		$cabang = $row['m_cabang'] ;
		$kodesales = $row['m_kodesales'] ;
		$member = $row['m_member'] ;
		$typemember = $row['m_typemember'] ;

		$tsqlstat = "select m_nama from msmaster where m_type = 'STATCUST' and m_kode = '".$status."'" ;
		$stmtstat = sqlsrv_query( $con_dbcmk, $tsqlstat);
		$rowstat = sqlsrv_fetch_array( $stmtstat, SQLSRV_FETCH_ASSOC);

		$tsqljr = "select m_kode, m_nama from mssales where m_kode = '".$kodesales."'" ;
		$stmtjr = sqlsrv_query( $con_dbcmk, $tsqljr);
		$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC) ;

		$tsqltier = "select m_kode, m_nama from msmembership where m_kode = '".$typemember."'" ;
		$stmttier = sqlsrv_query( $con_dbcmk, $tsqltier);
		$rowtier = sqlsrv_fetch_array( $stmttier, SQLSRV_FETCH_ASSOC);

    ?>
	<form class="form-horizontal" method="post" action="mscustomer-saveredeem.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo $kode ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Kode Cust</td>
                        <td>
                        	<input type="hidden" id="kdstore" name="kdstore" value="<?php echo $_GET['cb']; ?>" />
                            <input type="hidden" id="periode" name="kdsales" value="<?php echo $_GET['sl']; ?>" />
                            <input type="hidden" id="m_new" name="m_new" value="<?php echo  $kode; ?>" />
                            <input type="hidden" id="param" name="param" value="<?php echo  $prm; ?>" />
                            <input type="hidden" id="stedit" name="stedit" value="<?php echo  $statedit; ?>" />
                        	<input class="input-large" type="text" id="m_kode" name="m_kode" value="<?php echo $kode; ?>" readonly/>
                        </td>
                        <td>Status</td>
                        <td>
                            <input class="input-medium" type="text" id="m_typemember" name="m_typemember" value="<?php echo $rowtier['m_nama']; ?>" readonly/>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3"><input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td colspan="3"><textarea class="input-xxlarge" name="m_alamat" id="m_alamat" readonly><?php echo $alamat; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td colspan="3"><input class="input-medium" type="text" id="m_kota" name="m_kota" value="<?php echo $kota; ?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>Phone-1</td>
                        <td><input class="input-medium" type="text" id="m_telepon1" name="m_telepon1" value="<?php echo $telepon1; ?>" readonly/></td>
                        <td>Phone-2</td>
                        <td><input class="input-medium" type="text" id="m_telepon2" name="m_telepon2" value="<?php echo $telepon1; ?>" readonly/></td>                        
                    </tr>
                    <tr>
                        <td>Store</td>
                        <td><input class="input-medium" type="text" id="m_cabang" name="m_cabang" value="<?php echo $row['costore']; ?>" readonly/></td>
                        <td>JR</td>
                        <td><input class="input-medium" type="text" id="m_sales" name="m_sales" value="<?php echo $rowjr['m_nama']; ?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>No.Member</td>
                        <td><input class="input-medium" type="text" id="m_member" name="m_member" value="<?php echo $member; ?>" readonly/></td>
                        <td>Tgl.Member</td>
                        <td><input class="input-medium" data-format="dd/MM/yyyy" type="text" id="m_tglmember" name="m_tglmember" value="<?php echo $tglmember; ?>"  readonly /></td>
                    </tr>
                    <tr>
                    	<td>TOTAL POIN</td>
                    	<td colspan="3"><input class="input-small" type="text" id="m_totpoin" name="m_totpoin" value="<?php echo number_format($row['copoin'], 2, '.', ','); ?>" readonly/></td>
                    </tr>
                    <tr>
                    	<td>Jenis Voucher</td>
                    	<td colspan="3">
                            <select name="m_typevcr" id="m_typevcr" class="input-medium" onChange="oc_voucher()">
                            	<option value="I" >Internal Voucher</option>
                            	<option value="E" >External Voucher</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td>Redeem Poin</td>
                    	<td>
                        	<span id="listredeem">
                            <select name="m_point" id="m_point" class="input-large" onChange="oc_value()">
                                <option value="0-0" >-</option>
                                <?php
                                $tsqlred = "select 	m_point, m_value, m_value2 
											from 	msredemption 
											where 	m_kode = ( select max(m_kode) from msredemption ) and 
													m_point <= ".$row['copoin']."
								 			order by m_point asc" ;
                                $stmtred = sqlsrv_query( $con_dbcmk, $tsqlred);
                                while( $rowred = sqlsrv_fetch_array( $stmtred, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowred['m_point'].'-'.$rowred['m_value']; ?>" ><?php echo $rowred['m_point'].' poin ( Rp. '.number_format($rowred['m_value'], 0, '.', ',').' )'; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </span>
                        </td>
                        <td>Value Redeem</td>
                        <td><input class="input-medium" type="text" id="m_jumlah" name="m_jumlah" value="<?php echo number_format($row['m_jumlah'], 0, '.', ','); ?>" readonly/></td>
                    </tr>
                    <tr>
                    	<td>Keterangan</td>
                    	<td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value=""/></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8">
                        <div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save & Print Voucher" />
                                <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $kdsales; ?>')" />
                            </div>
                        </div>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>

	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			$('#datemember').datetimepicker({
				language: 'en',
				pickTime: false
			});

			$('#datelahir').datetimepicker({
				language: 'en',
				pickTime: false
			});
		});
  	
		function oc_voucher()
		{
			document.getElementById('m_jumlah').value = 0 ;
			var data={vr:$('#m_typevcr').val(), pt:$('#m_totpoin').val()};
			var fungsi=function(respon){
					$("#listredeem").html(respon);
				};
			$.get('report-listredeem.php',data,fungsi);
		}
	
		function oc_value()
		{
			var vpoin = $('#m_point').val();
			var vjumlah = vpoin.split("-");
			var nilai = Number(vjumlah[1].replace(/,/g,""));
			document.getElementById('m_jumlah').value = formatangka(nilai.toFixed().toString()) ;
		}
		
		function cancel_data(vparam,kdstore,kdsales)
		{
			window.open("mscustomer.php?st="+base64_encode(kdstore)+'&sl='+base64_encode(kdsales)+'&prm='+base64_encode(vparam),'_self');
		}

		function validasi()
		{
			var stedit = document.getElementById('stedit').value ;
			var nama  = document.getElementById('m_nama').value ;
			var kota = document.getElementById('m_kota').value ;
			var phone1 = document.getElementById('m_telepon1').value ;
			var agama = document.getElementById('m_agama').value ;
			var tmplahir = document.getElementById('m_tmplahir').value ;
			var tgllahir = document.getElementById('m_tgllahir').value ;
			var store = document.getElementById('m_cabang').value ;
			var tglmember = document.getElementById('m_tglmember').value ;
			var typemember = document.getElementById('m_typemember').value ;

			if ((nama == '')||(kota == '')||(phone1 == '')||(store == ''))
			{
				if (nama == ''){alert('NAMA Customer tidak boleh kosong !!!')};
				if (kota == ''){alert('KOTA tidak boleh kosong !!!')};
				if (phone1 == ''){alert('No.Telp tidak boleh kosong !!!')};
				if (store == ''){alert('STORE tidak boleh kosong !!!')};
				return false ;
			}
			else if ((agama == '')||(tmplahir == '')||(tgllahir == '')||(tgllahir == '01/01/1900')||(tglmember == '')||(tglmember == '01/01/1900')||(typemember == ''))
			{
				if (agama == ''){alert('AGAMA tidak boleh kosong !!!')};
				if (tmplahir == ''){alert('TMP.Lahir boleh kosong !!!')};
				if ((tgllahir == '')||(tgllahir == '01/01/1900')){alert('TGL.Lahir tidak boleh kosong !!!')};
				if ((tglmember == '')||(tglmember == '01/01/1900')){alert('TGL.Member tidak boleh kosong !!!')};
				if (typemember == ''){alert('TIER Member tidak boleh kosong !!!')};
				return false ;
			}
			else
			{
				return true ;
			}
		}


	</script>

    </body>
</html>