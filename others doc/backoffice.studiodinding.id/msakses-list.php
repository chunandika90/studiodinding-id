<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$login = $_GET['lg'];
	$prm = $_GET['prm'];

	$tsql = "select a.*, b.m_nama, b.m_status, b.m_urutan from msakses a, msmenu b where a.m_login = '".$login."' and a.m_kode = b.m_kode order by a.m_kode asc" ;
	//echo $tsql;
	$stmt = $con_dbnew->query($tsql);

?>

<form class="form-horizontal" method="post" action="msakses-simpan.php"  onsubmit="return validasi()">
<table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="7">
            	<div class="pull-left"><?php echo 'AKSES MENU - ( '.$login.' )' ?></div>
                <div class="container input-append pull-right" style="width: auto; padding: 0 10px;">
                    <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                    <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $login; ?>')" />
                    <input type="button" class="btn" id="bt_sync" value="Sync Menu" onClick="sync_modal('<?php echo $prm; ?>','<?php echo $login; ?>')" />
                </div>
            </th>
        </tr>
        <tr>
            <th width="10%"><div align="center">Kode</div></th>
            <th>Nama Menu</th>
            <th width="10%" style="cursor:pointer" onclick="cekall('m_akses')"><div align="center">Akses</div>
            	<input type="hidden" id="m_akses" name="m_akses" value="T"/>
				<input type="hidden" id="jumrow" name="jumrow" value="0"  />
				<input type="hidden" id="param" name="param" value="<?php echo $prm; ?>"  />
                <input type="hidden" id="m_login" name="m_login" value="<?php echo $login; ?>"  />                
            </th>
            <th width="10%" style="cursor:pointer" onclick="cekall('m_add')"><div align="center">Add</div>
            	<input type="hidden" id="m_add" name="m_add" value="T"/>
            </th>
            <th width="10%" style="cursor:pointer" onclick="cekall('m_edit')"><div align="center">Edit</div>
            	<input type="hidden" id="m_edit" name="m_edit" value="T"/>
            </th>
            <th width="10%" style="cursor:pointer" onclick="cekall('m_delete')"><div align="center">Delete</div>
            	<input type="hidden" id="m_delete" name="m_delete" value="T"/>
            </th>
            <th width="10%" style="cursor:pointer" onclick="cekall('m_print')"><div align="center">Print</div>
            	<input type="hidden" id="m_print" name="m_print" value="T"/>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            while( $row = $stmt->fetch_assoc())
            {
				$i = $i + 1 ;
				$style = '';
				$style2 = '';
				$disabled = '' ;
				if (substr($row['m_kode'],-5)=='00000')
				{
					$style = 'style="font-weight:bold"';
					$style2 = 'style="font-weight:bold"';
				}
				else if ($row['m_status'] == '2')
				{
					$style2 = 'style="font-weight:bold;font-size:11px"';
					$disabled = 'disabled="disabled"' ;
				}
                ?>
                <tr>
                    <td <?php echo $style; ?>><div align="center"><?php echo $row['m_kode']; ?></div>
                        <input type="hidden" id="m_kode<?php echo $i; ?>" name="m_kode<?php echo $i; ?>" value="<?php echo $row['m_kode']; ?>"  />
                        <input type="hidden" id="m_status<?php echo $i; ?>" name="m_status<?php echo $i; ?>" value="<?php echo $row['m_status']; ?>"  />                        
                    </td>
                    <td <?php echo $style2; ?>><?php echo $row['m_nama']; ?></td>
                    <td><div align="center"><input type="checkbox" id="m_akses<?php echo $i; ?>" name="m_akses<?php echo $i; ?>" <?php if($row['m_akses']=='Y'){ ?> checked <?php } ?> <?php echo $disabled; ?> /></div></td>
                    <td><div align="center"><input type="checkbox" id="m_add<?php echo $i; ?>" name="m_add<?php echo $i; ?>" <?php if($row['m_add']=='Y'){ ?> checked <?php } ?> <?php echo $disabled; ?>/></div></td>
                    <td><div align="center"><input type="checkbox" id="m_edit<?php echo $i; ?>" name="m_edit<?php echo $i; ?>" <?php if($row['m_edit']=='Y'){ ?> checked <?php } ?> <?php echo $disabled; ?>/></div></td>
                    <td><div align="center"><input type="checkbox" id="m_delete<?php echo $i; ?>" name="m_delete<?php echo $i; ?>" <?php if($row['m_delete']=='Y'){ ?> checked <?php } ?> <?php echo $disabled; ?>/></div></td>
                    <td><div align="center"><input type="checkbox" id="m_print<?php echo $i; ?>" name="m_print<?php echo $i; ?>" <?php if($row['m_print']=='Y'){ ?> checked <?php } ?> <?php echo $disabled; ?>/></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</form>
