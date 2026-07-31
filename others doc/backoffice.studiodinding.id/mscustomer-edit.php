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
        <title>Data Customer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		
		if ($kode == '')
		{
			$tgllahir = '';
			$tglmember = '';
			
			$nama = '';
			$alamat = '';
			$kota = '';
			$telepon1 = '';
			$telepon2 = '';
			$fax = '';			
			$pinbb = '';			
			$email = '';
			$status = '';
			$tmplahir = '';
			$agama = '';
			$cabang = $kdstore;
			$kodesales = $kdsales;
			$member = '';
			$typemember = '' ;
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tgllahir,103) as co_tgllahir, convert(varchar(10),a.m_tglmember,103) as co_tglmember from mscustomer a where a.m_kode = '".$kode."' " ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
			
			$tgllahir = $row['co_tgllahir'] ;
			$tglmember = $row['co_tglmember'] ;
			
			$nama = $row['m_nama'] ;
			$alamat = $row['m_alamat'] ;
			$kota = $row['m_kota'] ;
			$telepon1 = $row['m_telepon1'] ;
			$telepon2 = $row['m_telepon2'] ;
			$pinbb = $row['m_pinbb'] ;			
			$fax = $row['m_fax'] ;			
			$email = $row['m_email'] ;
			$status = $row['m_status'] ;
			$tmplahir = $row['m_tmplahir'] ;
			$agama = $row['m_agama'] ;
			$cabang = $row['m_cabang'] ;
			$kodesales = $row['m_kodesales'] ;
			$member = $row['m_member'] ;
			$typemember = $row['m_typemember'] ;
		}
    ?>
	<form class="form-horizontal" method="post" action="mscustomer-simpan.php"  onsubmit="return validasi()">
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
                       
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3"><input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required /></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td colspan="3"><textarea class="input-xxlarge" name="m_alamat" id="m_alamat" ><?php echo $alamat; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td><input class="input-medium" type="text" id="m_kota" name="m_kota" value="<?php echo $kota; ?>"/></td>
                        <td>Fax</td>
                        <td><input class="input-medium" type="text" id="m_fax" name="m_fax" value="<?php echo $fax; ?>"/></td>
                    </tr>
                    <tr>
                        <td>Phone-1</td>
                        <td><input class="input-medium" type="text" id="m_telepon1" name="m_telepon1" value="<?php echo $telepon1; ?>"/></td>
                        <td>Phone-2</td>
                        <td><input class="input-medium" type="text" id="m_telepon2" name="m_telepon2" value="<?php echo $telepon1; ?>"/></td>                        
                    </tr>
                    <tr>
                        <td>Pin-BB</td>
                        <td><input class="input-medium" type="text" id="m_pinbb" name="m_pinbb" value="<?php echo $pinbb; ?>"/></td>
                        <td>Email</td>
                        <td><input class="input-large" type="text" id="m_email" name="m_email" value="<?php echo $email; ?>"/></td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td colspan="3">
                            <select name="m_agama" id="m_agama" class="input-medium">
                                <?php
                                $tsqlagama = "select m_kode, m_nama from msmaster where m_type = 'AGAMA' order by m_kode asc" ;
                                $stmtagama = sqlsrv_query( $con_dbnew, $tsqlagama);
                                while( $rowagama = sqlsrv_fetch_array( $stmtagama, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowagama['m_kode']; ?>" <?php if ($rowagama['m_kode'] == $agama){ ?> selected="selected" <?php }   ?> ><?php echo $rowagama['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Tmp.Lahir</td>
                        <td>
                            <input class="input-medium" type="text" id="m_tmplahir" name="m_tmplahir" value="<?php echo $tmplahir; ?>"/>
                        </td>
                        <td>Tgl.Lahir</td>
                        <td>                            
                        	<div id="datelahir" class="input-append date">
                            	<input class="input-medium" data-format="dd/MM/yyyy" type="text" id="m_tgllahir" name="m_tgllahir" value="<?php echo $tgllahir; ?>" />
                                <span class="add-on">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                </span>
                            </div>
						</td>                        
                    </tr>
                    <tr>
                        <td>Store</td>
                        <td>
                            <select name="m_cabang" id="m_cabang" class="input-large">
                                <?php
                                $tsqlcabang = "select m_kode, m_nama from msmaster where m_type = 'STORE' order by m_kode asc" ;
                                $stmtcabang = sqlsrv_query( $con_dbnew, $tsqlcabang);
                                while( $rowcabang = sqlsrv_fetch_array( $stmtcabang, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowcabang['m_kode']; ?>" <?php if ($rowcabang['m_kode'] == $cabang){ ?> selected="selected" <?php }   ?> ><?php echo $rowcabang['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>JR</td>
                        <td>
                            <select name="m_kodesales" id="m_kodesales" class="input-large">
                                <?php
                                $tsqljr = "select m_kode, m_nama, m_cabang from mssales order by m_cabang asc, m_nama asc" ;
                                $stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
                                while( $rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowjr['m_kode']; ?>" <?php if ($rowjr['m_kode'] == $kodesales){ ?> selected="selected" <?php }   ?> ><?php echo $rowjr['m_cabang'].'-'.$rowjr['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8">
                        <div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
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