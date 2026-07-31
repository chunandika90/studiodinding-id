<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "../mssql-dbcmk.php";
	
	include "../mssql-dbprocurement.php";
	
	$akses = $_GET['aks'];
	$kode = $_GET['kd'];
	$menuid = $_GET['mn'];
	$modul = $_GET['modul'];
	if ($kode != '')
	{
		$tsql = "	select 	a.m_nomor, a.m_keterangan, a.m_nodoc, convert(varchar(10),a.m_tanggal,103) as co_tanggal, a.m_declare, 
							convert(varchar(10),a.m_duedate,103) as co_duedate, convert(varchar(10),a.m_tgldoc,103) as co_tgldoc, 
							a.m_itemcode as jenisbarang, b.*
					from 	t_ttb a, mssupplier b
					where 	a.m_kode = b.m_kode and
							a.m_nomor = '".$kode."' " ;
		$stmt = sqlsrv_query( $con_dbprocurement, $tsql);
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

		$tgl = $row['co_tanggal'];
		$due = $row['co_duedate'];
		$tgldoc = $row['co_tgldoc'];

	}
	else
	{
		$tgl = date('d/m/Y');
		$due = date('d/m/Y');
		$tgldoc = date('d/m/Y');
	}
	//echo $tsql.'<br/>';
	?>
    <form method="post" action="ttb-simpan.php"  onsubmit="return validasi()" style="font-size:12px" onkeypress="return disableEnterKey(event,this.id)"  >
        <fieldset>
        	<div id="data-order">
            	<h3>Data TTB</h3>
                <div>
                	<table class="table" style="font-size:12px">
                    	<tr>
                        	<td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">No.TTB</span>
                                    <input class="form-control" type="text" id="m_nomor" name="m_nomor" value="<?php echo $row['m_nomor']; ?>" placeholder="Nomor" readonly >
                                    
                        <input type="hidden" id="jumrow" name="jumrow" value="0" />
						<input type="hidden" id="m_mn" name="m_mn" value="<?php echo $menuid; ?>">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">Tanggal</span>
                                    <div id="tgl1"><?php echo $tgl ; ?></div>
                                    <input type="hidden" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl ; ?>" >
                                    <?php
									if ($row['m_nomor'] == '')
									{
										?>
                                        <input type="hidden" id="m_tgl1" name="m_tgl1" value="<?php echo $tgl ; ?>" onchange="f_cektanggal('m_tgl1')" >
                                        <?php
									}
									?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">Item</span>
                                    <?php
									if ($row['m_nomor'] == '')
									{
										?>
                                        <select class="form-control" name="m_itemcode" id="m_itemcode">
                                            <option value="" >-</option>
                                            <?php
                                            $tsqlc = "	select 	m_kode, m_nama
                                                            from 	msbarang2
                                                            order by m_kode asc" ;
                                            $stmtc = sqlsrv_query( $con_dbprocurement, $tsqlc);
                                            while( $rowc = sqlsrv_fetch_array( $stmtc, SQLSRV_FETCH_ASSOC))
                                            {
                                                ?>
                                                <option value="<?php echo $rowc['m_kode']; ?>" <?php if ($rowc['m_kode'] == $row['jenisbarang']) { ?> selected="selected" <?php } ?> ><?php echo $rowc['m_nama']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>                                    
                                        <?php
									}
									else
									{
										?>
                                        <input class="form-control" type="text" id="m_itemcode" name="m_itemcode" value="<?php echo $row['jenisbarang']; ?>" readonly >
                                        <?php
									}
									?>
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">No.Doc Supl.</span>
                                    <input class="form-control" type="text" id="m_nodoc" name="m_nodoc" value="<?php echo $row['m_nodoc']; ?>" placeholder="No.Document Supplier" >
                                </div>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">Delivery Date</span>
                                    <div id="tgl2"><?php echo $due ; ?></div>
                                    <input type="hidden" id="m_duedate" name="m_duedate" value="<?php echo $due ; ?>" >
                                    <input type="hidden" id="m_tgl2" name="m_tgl2" value="<?php echo $due ; ?>" onchange="f_cektanggal('m_tgl2')" >
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">Tgl.Doc Supl.</span>
                                    <div id="tgl3"><?php echo $tgldoc ; ?></div>
                                    <input type="hidden" id="m_tgldoc" name="m_tgldoc" value="<?php echo $tgldoc ; ?>" >
                                    <input type="hidden" id="m_tgl3" name="m_tgl13" value="<?php echo $tgldoc ; ?>" onchange="f_cektanggal('m_tgl3')" >
                                </div>
                            </td>
                        </tr>
                        <tr>
                        	<td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">Keterangan</span>
                                    <input class="form-control" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $row['m_keterangan']; ?>" placeholder="Keterangan" >
                                </div>
                            </td>
                        	<td>
                                <div class="input-group col-sm-2">
                                    <span class="input-group">Status</span>
                                        <select class="form-control" name="m_declare" id="m_declare">
                                            <option value="D" <?php if ($row['m_declare'] == 'D') { ?> selected="selected" <?php } ?> >D</option>
                                            <option value="N" <?php if ($row['m_declare'] == 'N') { ?> selected="selected" <?php } ?> >N</option>
                                        </select>                                    
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-4">
                                    <span class="input-group">Kode Supl.<span style="color:#F00"> *</span></span>
                                    <input class="form-control" type="text" id="m_kode" name="m_kode" value="<?php echo $row['m_kode']; ?>" placeholder="Kode Supl." onclick="f_searchsupl()" style="cursor:pointer" readonly >                        
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-6">
                                    <span class="input-group">Supplier</span>
                                    <input class="form-control" type="text" id="m_abbrev" name="m_abbrev" value="<?php echo $row['m_abbrev']; ?>" readonly >
                                    <input type="hidden" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>" readonly >
                                </div>
                            </td>
                        </tr>
                        <tr>
                        	<td colspan="2">
                            <div class="input-group col-sm-4">
                                <span class="input-group">Lokasi</span>
                                <select class="form-control" name="m_lokasi" id="m_lokasi" onchange="f_oclokasi()">
                                    <option value="" >-</option>
                                    <?php
                                    $tsqlc = "	select 	m_kode, m_nama
                                                from 	mslokasi
                                                order by m_kode asc" ;
                                    $stmtc = sqlsrv_query( $con_dbprocurement, $tsqlc);
                                    while( $rowc = sqlsrv_fetch_array( $stmtc, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $rowc['m_kode']; ?>" <?php if ($rowc['m_kode'] == $lokasi) { ?> selected="selected" <?php } ?> ><?php echo $rowc['m_nama']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            </td>
                        </tr>
                    </table>
                    <section id="sec-search2">
                    <div class="input-group panel-heading" id="div-search2" style="display:none">
                        <div class="panel-body">

                            <div class="btn-group">
                                <span class="btn-xs">Nama : </span><input type="text" class="btn-xs" id="l_nama" name="l_nama" value=""  placeholder="Nama Supl." style="font-size:12px" onchange="f_search()">
                            </div>
                            <div class="btn-group">
                                <span class="btn-xs">Alamat : </span><input type="text" class="btn-xs" id="l_alamat" name="l_alamat" value=""  placeholder="Alamat" style="font-size:12px" onchange="f_search()">
                            </div>
                            <div class="btn-group">
                                <span class="btn-xs">Telp : </span><input type="text" class="btn-xs" id="l_telepon" name="l_telepon" value=""  placeholder="Telepon" style="font-size:12px" onchange="f_search()">
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs" id="search-record" onClick="f_search()">
                                    <label class="btn-xs">Search</label>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs" id="close-record" onClick="f_closesch()">
                                    <label class="btn-xs">Close</label>
                                </button>
                            </div>

                            <div class="panel-body">
                                <span id="span-hasilsearch2">
    
                                </span>
                            </div>

                        </div>
                    </div>
                    </section>
				</div>

                <h3>Data Item</h3>
                <div>
                    <section id="sec-searchpo">
                    <div class="input-group panel-heading" id="div-searchpo" style="display:none">
                        <div class="panel-body">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs" id="close-record" onClick="f_closeschpo()">
                                    <label class="btn-xs">Close</label>
                                </button>
                            </div>

                            <div class="panel-body">
                                <span id="span-hasilsearchpo">
    
                                </span>
                            </div>

                        </div>
                    </div>
                    </section>
                    <br/>
                    
                	<span id="span-dataitemttb">
                    <table id="table_data" class="table table-bordered table-hover table-condensed" style="font-size:10px">
                        <thead>
                            <tr>
                                <th style="cursor:pointer" onclick="f_searchpo()"><u>No.Order</u></th>
                                <th>Desc</th>
                                <th>Shape</th>
                                <th>Size</th>
                                <th>Colour</th>
                                <th>Clarity</th>
                                <th><div align="center">Carat</div></th>
                                <th><div align="right">Harga/ct</div></th>
                                <th><div align="right">Jumlah</div></th>
                                <th>Lokasi</th>
                                <th>Parcel</th>
                                <th>del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 0 ;
                                $tjumlah = 0 ;
                                $tcarat = 0 ;
								$tsql2 = "	select 	b.*, c.Nama as nmcolour, d.Nama as nmclarity, e.Nama as nmshape, f.m_nama as nmsize, g.Nama as nmbrand
											from 	t_ttb2 b 
													left join mondial_db_real.dbo.Parcel103 c on b.m_colour = c.NamaKode 
													left join mondial_db_real.dbo.Parcel104 d on b.m_clarity = d.NamaKode
													left join mondial_db_real.dbo.Parcel101 e on b.m_shape = e.NamaKode
													left join mondial_db_real.dbo.Parcel106 g on b.m_brand = g.NamaKode
													left join mskode f on f.m_type = 'SIZE' and f.m_kode2 = b.m_shape and b.m_size = f.m_kode
											where 	b.m_nomor = '".$kode."'  " ;
											
                                $stmt2 = sqlsrv_query( $con_dbprocurement, $tsql2);
                                while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
                                {
                                    $i = $i + 1 ;
                                    $tcarat = $tcarat + $row2['m_carat'] ;
                                    $tjumlah = $tjumlah + ( $row2['m_carat'] * $row2['m_harga'] ) ;
									
									$desc = $row2['m_desc'] ;
									if ($row2['m_brand'] != '') {$desc = $desc.' '.$row2['nmbrand']; }
									if ($row2['m_gia'] != '') {$desc = $desc.' '.$row2['m_gia']; }
									$size = $row2['nmsize'] ;
									if ($row2['m_itemcode'] == 'Stone>0.3')
									{
										$size = $row2['m_size'].' - '.$row2['m_size2'].' x '.$row2['m_size3'];
									}
                                    ?>
                                    <tr>
                                        <td><div id="sp-nopo<?php echo $i; ?>" align="center"><?php echo $row2['m_nopo']; ?></div></td>
                                        <td><div id="sp-desc<?php echo $i; ?>"><?php echo $desc; ?></div></td>
                                        <td onclick="f_additem('<?php echo $i; ?>')"><div id="sp-shape<?php echo $i; ?>" style="cursor:pointer" align="center"><?php echo $row2['nmshape']; ?></div></td>
                                        <td><div id="sp-size<?php echo $i; ?>" align="center"><?php echo $size; ?></div></td>
                                        <td><div id="sp-colour<?php echo $i; ?>" align="center"><?php echo $row2['nmcolour']; ?></div></td>
                                        <td><div id="sp-clarity<?php echo $i; ?>" align="center"><?php echo $row2['nmclarity']; ?></div></td>
                                        <td><div id="sp-carat<?php echo $i; ?>" align="center"><?php echo number_format($row2['m_carat'], 3, '.', ','); ?></div></td>
                                        <td><div id="sp-harga<?php echo $i; ?>" align="right"><?php echo $row2['m_kurs'].' '.number_format($row2['m_harga'], 2, '.', ','); ?></div></td>
                                        <td><div id="sp-jumlah<?php echo $i; ?>" align="right"><?php echo number_format($row2['m_carat'] * $row2['m_harga'], 2, '.', ','); ?></div></td>
                                        <td><div id="sp-lokasi<?php echo $i; ?>" align="center"><?php echo $row2['m_lokasi']; ?></div></td>
                                        <td><div id="sp-parcel<?php echo $i; ?>" align="center"><?php echo $row2['m_parcel']; ?></div></td>
                                        <td><div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
                                        	<input type="hidden" id="m_stat<?php echo $i; ?>" name="m_stat<?php echo $i; ?>" value="T" >
                                        	<input type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $row2['m_no']; ?>" >
                                        	<input type="hidden" id="m_nopo<?php echo $i; ?>" name="m_nopo<?php echo $i; ?>" value="<?php echo $row2['m_nopo']; ?>" >
                                        	<input type="hidden" id="m_nourutpo<?php echo $i; ?>" name="m_nourutpo<?php echo $i; ?>" value="<?php echo $row2['m_nourutpo']; ?>" >
                                            <input type="hidden" id="m_itemcode<?php echo $i; ?>" name="m_itemcode<?php echo $i; ?>" value="<?php echo $row2['m_itemcode']; ?>" >
                                            <input type="hidden" id="m_desc<?php echo $i; ?>" name="m_desc<?php echo $i; ?>" value="<?php echo $row2['m_desc']; ?>" >
                                            <input type="hidden" id="m_shape<?php echo $i; ?>" name="m_shape<?php echo $i; ?>" value="<?php echo $row2['m_shape']; ?>" >                                            
                                            <input type="hidden" id="m_size<?php echo $i; ?>" name="m_size<?php echo $i; ?>" value="<?php echo $row2['m_size']; ?>" >
                                            <input type="hidden" id="m_sizeb<?php echo $i; ?>" name="m_sizeb<?php echo $i; ?>" value="<?php echo $row2['m_size2']; ?>" >
                                            <input type="hidden" id="m_sizec<?php echo $i; ?>" name="m_sizec<?php echo $i; ?>" value="<?php echo $row2['m_size3']; ?>" >
                                            <input type="hidden" id="m_colour<?php echo $i; ?>" name="m_colour<?php echo $i; ?>" value="<?php echo $row2['m_colour']; ?>" >
                                            <input type="hidden" id="m_clarity<?php echo $i; ?>" name="m_clarity<?php echo $i; ?>" value="<?php echo $row2['m_clarity']; ?>" >
                                            <input type="hidden" id="m_brand<?php echo $i; ?>" name="m_brand<?php echo $i; ?>" value="<?php echo $row2['m_brand']; ?>" >
                                            <input type="hidden" id="m_gia<?php echo $i; ?>" name="m_gia<?php echo $i; ?>" value="<?php echo $row2['m_gia']; ?>" >
                                            <input type="hidden" id="m_carat<?php echo $i; ?>" name="m_carat<?php echo $i; ?>" value="<?php echo $row2['m_carat']; ?>" >
                                            <input type="hidden" id="m_kurs<?php echo $i; ?>" name="m_kurs<?php echo $i; ?>" value="<?php echo $row2['m_kurs']; ?>" >
                                            <input type="hidden" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="<?php echo $row2['m_harga']; ?>" >
                                            <input type="hidden" id="m_lokasi<?php echo $i; ?>" name="m_lokasi<?php echo $i; ?>" value="<?php echo $row2['m_lokasi']; ?>" >
                                            <input type="hidden" id="m_parcel<?php echo $i; ?>" name="m_parcel<?php echo $i; ?>" value="<?php echo $row2['m_parcel']; ?>" >
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>                            
                            <tr>
                                <th colspan="6"></th>
                                <th><div id="sp-totcarat" align="center"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
                                <th></th>
                                <th><div id="sp-totjumlah" align="right"><?php echo number_format($tjumlah, 2, '.', ','); ?></div></th>
                                <th colspan="3"></th>
                            </tr>
                        </tbody>
                    </table>
                    </span>              
                	<div>
                        <button type="button" class="btn btn-info btn-sm" id="add-item" onClick="f_additem('0')">Add Item</button>
                    </div>
                    
                    <section id="sec-additem">
                    <div class="panel-heading" id="div-additem" style="display:none">
                        <div class="panel-body">
                            <span id="span-item">
                            </span>
                            <div>
                                <input type="hidden" id="rowke" name="rowke" value="0" >
                                <button type="button" class="btn btn-warning btn-sm" id="add-additem" onClick="f_add()">Update</button>
                                <button type="button" class="btn btn-primary btn-sm" id="close-additem" onClick="f_close()">Close</button>
                            </div>
                        </div>
                    </div>
                    </section>
                </div>
                
			</div>
            <br/>
            <div class="input-group">                                        
                <button type="submit" class="btn btn-success btn-sm" id="save-record">Save</button>
                <button type="button" class="btn btn-danger btn-sm" id="hapus-record" onClick="f_hapus('<?php echo $kode; ?>')">Hapus</button>

                <button type="button" class="btn btn-warning btn-sm" id="batal-record" onClick="f_batal()">Batal</button>
            </div><!-- /input-group -->
        </fieldset>
    </form>
	<script type="text/javascript">
		$(function() {
			$( "#data-order" ).accordion({
				collapsible: true,
				heightStyle: "content"
			});
			
			$( "#m_tgl1" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: true,
					showOn: "button",
					buttonImage: "../images/calendar.gif",
					buttonImageOnly: true,
					buttonText: "Select date"					
				});
			$( "#m_tgl1" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
			
			$( "#m_tgl2" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: true,
					showOn: "button",
					buttonImage: "../images/calendar.gif",
					buttonImageOnly: true,
					buttonText: "Select date"					
				});
			$( "#m_tgl2" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
			
			$( "#m_tgl3" ).datepicker({
					showOtherMonths: true,
					selectOtherMonths: true,
					showOn: "button",
					buttonImage: "../images/calendar.gif",
					buttonImageOnly: true,
					buttonText: "Select date"					
				});
			$( "#m_tgl3" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
			
		});

		function f_additem(rowke)
		{
			
			document.getElementById('rowke').value = rowke ;
			
			var itemcode = document.getElementById('m_itemcode').value ;
			
			
			if ( itemcode == '' )
			{
				alert('Pilih jenis ITEM yang akan diterima dahulu !!!');
			}
			
			
			else
			{
				
			
				if (rowke  == '0')
				{
					var nopo = 'NOORDER' ;
					var desc = '' ;
					var shape = '' ;
					var size = '' ;
					var size2 = '' ;
					var size3 = '' ;
					var colour = '' ;
					var clarity = '' ;
					var brand = '' ;
					var gia = '' ;
					var kurs = 'USD' ;
					var carat = 0 ;
					var harga = 0 ;			
					var lokasi = '' ;
					var stat = 'Y' ;
				}
				else
				{
					var stat = document.getElementById('m_stat'+rowke).value ;
					var nopo = document.getElementById('m_nopo'+rowke).value ;
					var desc = document.getElementById('m_desc'+rowke).value ;
					var shape = document.getElementById('m_shape'+rowke).value ;
					var size = document.getElementById('m_size'+rowke).value ;
					var size2 = document.getElementById('m_sizeb'+rowke).value ;
					var size3 = document.getElementById('m_sizec'+rowke).value ;
					var colour = document.getElementById('m_colour'+rowke).value ;
					var clarity = document.getElementById('m_clarity'+rowke).value ;
					var brand = document.getElementById('m_brand'+rowke).value ;
					var gia = document.getElementById('m_gia'+rowke).value ;
					var kurs = document.getElementById('m_kurs'+rowke).value ;
					var carat = Number(document.getElementById('m_carat'+rowke).value.replace(/,/g,"")) ;
					var harga = Number(document.getElementById('m_harga'+rowke).value.replace(/,/g,""))
				}
				
				
				
				var data={itemcode:itemcode,nopo:nopo,desc:desc,shape:shape,size:size,size2:size2,size3:size3,colour:colour,clarity:clarity,brand:brand,gia:gia,kurs:kurs,carat:carat,harga:harga,lokasi:lokasi,stat:stat};
				var fungsi=function(respon){
						$("#span-item").html(respon);
						
						$('#div-additem').show();
						$('html, body').animate({
							scrollTop: $("#sec-additem").offset().top
							}, 500);
					};
				if (itemcode == 'Stone<0.3')
				{
					$.get('ttb-itemform.php',data,fungsi);
				}
				else if (itemcode == 'Stone>0.3')
				{
					$.get('ttb-itemform2.php',data,fungsi);
				}
			}			
		}
		
		function f_add()
		{
			var rowke = document.getElementById('rowke').value ;
			var itemcode = document.getElementById('m_itemcode').value ;
			if (( rowke == '0' ) )
			{
				add_data() ;
			}
			else
			{
				var harga = Number(document.getElementById('m_harga').value.replace(/,/g,""));
				var carat = Number(document.getElementById('m_carat').value.replace(/,/g,""));
				var size = document.getElementById('m_size').value ;
				if (itemcode == 'Stone>0.3')
				{
					size = document.getElementById('m_size').value + ' - ' +document.getElementById('m_sizeb').value + ' x ' +document.getElementById('m_sizec').value ;
				}

				$("#sp-nopo"+rowke).html(document.getElementById('m_nopo').value);
				$("#sp-desc"+rowke).html(document.getElementById('m_desc').value+' '+$( "#m_brand option:selected" ).text()+' '+document.getElementById('m_gia').value);
				$("#sp-shape"+rowke).html($( "#m_shape option:selected" ).text());
				$("#sp-size"+rowke).html(size);
				$("#sp-colour"+rowke).html($( "#m_colour option:selected" ).text());
				$("#sp-clarity"+rowke).html($( "#m_clarity option:selected" ).text());
				$("#sp-carat"+rowke).html(formatangka(carat.toFixed(3).toString()));
				$("#sp-harga"+rowke).html(document.getElementById('m_kurs').value+' '+formatangka(harga.toFixed(2).toString()));
				$("#sp-jumlah"+rowke).html(formatangka((harga*carat).toFixed(2).toString()));

				document.getElementById('m_nopo'+rowke).value = document.getElementById('m_nopo').value  ;
				document.getElementById('m_desc'+rowke).value = document.getElementById('m_desc').value  ;
				document.getElementById('m_shape'+rowke).value = document.getElementById('m_shape').value  ;
				document.getElementById('m_size'+rowke).value = document.getElementById('m_size').value  ;
				document.getElementById('m_sizeb'+rowke).value = document.getElementById('m_sizeb').value  ;
				document.getElementById('m_sizec'+rowke).value = document.getElementById('m_sizec').value  ;
				document.getElementById('m_colour'+rowke).value = document.getElementById('m_colour').value  ;
				document.getElementById('m_clarity'+rowke).value = document.getElementById('m_clarity').value  ;
				document.getElementById('m_brand'+rowke).value = document.getElementById('m_brand').value  ;
				document.getElementById('m_gia'+rowke).value = document.getElementById('m_gia').value  ;				
				document.getElementById('m_carat'+rowke).value = formatangka(carat.toFixed(3).toString()) ;
				document.getElementById('m_kurs'+rowke).value = document.getElementById('m_kurs').value  ;
				document.getElementById('m_harga'+rowke).value = formatangka(harga.toFixed(2).toString()) ;
				$('#div-additem').hide();
			}
			recalc() ;
		}

		function f_close()
		{
			$('#div-additem').hide();
		}
		
		function add_data()
		{
alert();
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
			var double = 'T' ;
			var itemcode = document.getElementById('m_itemcode').value ;
			
			if ( double == 'T' )
			{
				var iteration = lastRow - 1;
				var row = tbl.insertRow(lastRow - 1);
				
				var harga = Number(document.getElementById('m_harga').value.replace(/,/g,""));
				var carat = Number(document.getElementById('m_carat').value.replace(/,/g,""));
				var size = document.getElementById('m_size').value ;
				if (itemcode == 'Stone>0.3')
				{
					size = document.getElementById('m_size').value + ' - ' +document.getElementById('m_sizeb').value + ' x ' +document.getElementById('m_sizec').value ;
				}

				var cellno = row.insertCell(0);
				cellno.innerHTML='<td><div id="sp-nopo'+iteration+'" align="center">'+document.getElementById('m_nopo').value+'</div></td>';
				
				var cellno = row.insertCell(1);
				cellno.innerHTML='<td><div id="sp-desc'+iteration+'">'+document.getElementById('m_desc').value+' '+$( "#m_brand option:selected" ).text()+' '+document.getElementById('m_gia').value+'</div></td>';
				
				var cellno = row.insertCell(2);
				cellno.innerHTML='<td><div id="sp-shape'+iteration+'" onclick="f_additem('+iteration+')" style="cursor:pointer" align="center">'+$( "#m_shape option:selected" ).text()+'</div></td>';
				
				var cellno = row.insertCell(3);
				cellno.innerHTML='<td><div id="sp-size'+iteration+'" align="center">'+size+'</div></td>';
				
				var cellno = row.insertCell(4);
				cellno.innerHTML='<td><div id="sp-colour'+iteration+'" align="center">'+$( "#m_colour option:selected" ).text()+'</div></td>';
				
				var cellno = row.insertCell(5);
				cellno.innerHTML='<td><div id="sp-clarity'+iteration+'" align="center">'+$( "#m_clarity option:selected" ).text()+'</div></td>';
				
				var cellno = row.insertCell(6);
				cellno.innerHTML='<td><div id="sp-carat'+iteration+'" align="center">'+formatangka(carat.toFixed(3).toString())+'</div></td>';
				
				var cellno = row.insertCell(7);
				cellno.innerHTML='<td><div id="sp-harga'+iteration+'" align="right">'+document.getElementById('m_kurs').value+' '+formatangka(harga.toFixed(2).toString())+'</div></td>';
				
				var cellno = row.insertCell(8);
				cellno.innerHTML='<td><div id="sp-jumlah'+iteration+'" align="right">'+formatangka((carat*harga).toFixed(2).toString())+'</div></td>';     

				var cellno = row.insertCell(9);
				cellno.innerHTML='<td><div id="sp-lokasi'+iteration+'" align="center">'+document.getElementById('m_lokasi').value+'</div></td>';
                                        				
				var cellno = row.insertCell(10);
				cellno.innerHTML='<td><div id="sp-lokasi'+iteration+'" align="center"><input type="text" id="m_parcel'+iteration+'" name="m_parcel'+iteration+'" value="" ></div></td>';										
														
				var cellno = row.insertCell(11);
				cellno.innerHTML='<td><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div><input type="hidden" id="m_stat'+iteration+'" name="m_stat'+iteration+'" value="Y" ><input type="hidden" id="m_no'+iteration+'" name="m_no'+iteration+'" value="" ><input type="hidden" id="m_nopo'+iteration+'" name="m_nopo'+iteration+'" value="'+document.getElementById('m_nopo').value+'" ><input type="hidden" id="m_nourutpo'+iteration+'" name="m_nourutpo'+iteration+'" value="" ><input type="hidden" id="m_desc'+iteration+'" name="m_desc'+iteration+'" value="'+document.getElementById('m_desc').value+'" ><input type="hidden" id="m_shape'+iteration+'" name="m_shape'+iteration+'" value="'+document.getElementById('m_shape').value+'" ><input type="hidden" id="m_size'+iteration+'" name="m_size'+iteration+'" value="'+document.getElementById('m_size').value+'" ><input type="hidden" id="m_sizeb'+iteration+'" name="m_sizeb'+iteration+'" value="'+document.getElementById('m_sizeb').value+'" ><input type="hidden" id="m_sizec'+iteration+'" name="m_sizec'+iteration+'" value="'+document.getElementById('m_sizec').value+'" ><input type="hidden" id="m_colour'+iteration+'" name="m_colour'+iteration+'" value="'+document.getElementById('m_colour').value+'" ><input type="hidden" id="m_clarity'+iteration+'" name="m_clarity'+iteration+'" value="'+document.getElementById('m_clarity').value+'" ><input type="hidden" id="m_brand'+iteration+'" name="m_brand'+iteration+'" value="'+document.getElementById('m_brand').value+'" ><input type="hidden" id="m_gia'+iteration+'" name="m_gia'+iteration+'" value="'+document.getElementById('m_gia').value+'" ><input type="hidden" id="m_carat'+iteration+'" name="m_carat'+iteration+'" value="'+document.getElementById('m_carat').value+'" ><input type="hidden" id="m_kurs'+iteration+'" name="m_kurs'+iteration+'" value="'+document.getElementById('m_kurs').value+'" ><input type="hidden" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="'+document.getElementById('m_harga').value+'" ><input type="hidden" id="m_lokasi'+iteration+'" name="m_lokasi'+iteration+'" value="'+document.getElementById('m_lokasi').value+'" ></td>';

				$('#div-additem').hide();
			}
			else
			{
				alert('ITEM CODE tidak boleh double');
			}
		  
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			var hasil = true ;

			document.getElementById('jumrow').value = jumrow;
			document.getElementById('rowke').value = jumrow;
			
			if (document.getElementById('m_nama').value == '') 
			{
				alert('Supplier belum di isi !!!');
				hasil = false ;
			}
				return hasil
			
		}

		function recalc()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  
		  var jumlah = 0 ;
		  var carat = 0 ;

		  for(var i = 1; i <= lastRow - 2; i++) 
		  {	
			  var jumlah = jumlah + ( Number(document.getElementById('m_harga'+i).value.replace(/,/g,"")) * Number(document.getElementById('m_carat'+i).value.replace(/,/g,"")) );
			  var carat = carat + Number(document.getElementById('m_carat'+i).value.replace(/,/g,""));
		  }
		  $("#sp-totcarat").html(formatangka((carat).toFixed(3).toString()));
		  $("#sp-totjumlah").html(formatangka((jumlah).toFixed(2).toString()));
		}

		function f_searchsupl()
		{
			$("#span-hasilsearch2").html('');
			$('#div-search2').show();
			$('#l_nama').focus();
		}
		
		function f_closesch()
		{
			$("#span-hasilsearch2").html('');
			$('#div-search2').hide();
		}
		
		function f_search()
		{

			var nama=document.getElementById('l_nama').value ;
			var alamat=document.getElementById('l_alamat').value ;
			var telepon=document.getElementById('l_telepon').value ;
			var aks=document.getElementById('aks').value ;
			var statcek = 'Y' ;
			if (statcek == 'Y')
			{
				$("#span-search").html('<div class="text-center"><img src="../images/loading.gif"/></div>');

				var data={aks:aks,nm:nama,alm:alamat,tlp:telepon};	
				var fungsi=function(respon){
						$("#span-hasilsearch2").html(respon);
					};
				$.get('po-searchsupl.php',data,fungsi);
			}			
		}

		function f_selectsupl(kd,nm,ab)
		{
			$('#div-search').hide();
			
			document.getElementById('m_kode').value = kd ;
			document.getElementById('m_nama').value = nm ;
			document.getElementById('m_abbrev').value = ab ;
			
			$('#div-search2').hide();			
		}
		
		
		function cekall(vcek)
		{
			var tbl = document.getElementById('table_listpo');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

		
			var oldcek = document.getElementById(vcek).value ;
			
			
			var newcek = true;
			var stat = '0';
			if (oldcek == 'Y'){ newcek = false; oldcek = 'T'} else {oldcek = 'Y' ;}
			
			for(var i=1; i <= jumrow; i++) 
			{	
				document.getElementById(vcek + i).checked = newcek ; 
			}
			document.getElementById(vcek).value = oldcek ;
			return true ;
		}
		
		function f_closeschpo()
		{
			$("#span-hasilsearchpo").html('');
			$('#div-searchpo').hide();
		}
		
		function f_searchpo()
		{
			var kodesupl=document.getElementById('m_kode').value ;
			var itemcode=document.getElementById('m_itemcode').value ;
			if ((kodesupl == '') || (itemcode == ''))
			{
				alert('Supplier / Jenis ITEM harap di pilih dahulu !!!');
			}
			else
			{
				$("#span-hasilsearchpo").html('');
				$('#div-searchpo').show();

				$("#span-search").html('<div class="text-center"><img src="../images/loading.gif"/></div>');
	
				var data={kodesupl:kodesupl,itemcode:itemcode};	
				var fungsi=function(respon){
						$("#span-hasilsearchpo").html(respon);
					};
				$.get('ttb-searchpo.php',data,fungsi);
			}
		}
		
		function f_updatepo()
		{
			var tbl = document.getElementById('table_listpo');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

			for(var i = 1; i <= jumrow; i++) 
			{	
				if ( document.getElementById('po_check'+i).checked )
				{
					var nopo = document.getElementById('po_nomor'+i).value ;
					var nourutpo = document.getElementById('po_no'+i).value ;
					var itemcode = document.getElementById('po_itemcode'+i).value ;
					var desc = document.getElementById('po_desc'+i).value ;
					var shape = document.getElementById('po_shape'+i).value ;
					var size = document.getElementById('po_size'+i).value ;
					var size2 = document.getElementById('po_sizeb'+i).value ;
					var size3 = document.getElementById('po_sizec'+i).value ;
					var colour = document.getElementById('po_colour'+i).value ;
					var clarity = document.getElementById('po_clarity'+i).value ;
					var brand = document.getElementById('po_brand'+i).value ;
					var gia = document.getElementById('po_gia'+i).value ;
					var parcel = document.getElementById('po_parcel'+i).value ;
					var kurs = document.getElementById('po_kurs'+i).value ;
					var carat = Number(document.getElementById('po_carat'+i).value.replace(/,/g,""));
					var harga = Number(document.getElementById('po_harga'+i).value.replace(/,/g,""));
					
					
					var nmshape = document.getElementById('po_nmshape'+i).value ;
					var nmcolour = document.getElementById('po_nmcolour'+i).value ;
					var nmclarity = document.getElementById('po_nmclarity'+i).value ;
					var nmbrand = document.getElementById('po_nmbrand'+i).value ;
					var double = 'T' ;

					var tbl2 = document.getElementById('table_data');
					var lastRow2 = tbl2.rows.length;
					//alert(lastRow2);
					for(var j = 1; j <= lastRow2 - 2; j++) 
					{	
						if (( nopo == document.getElementById('m_nopo'+j).value ) && ( nourutpo == document.getElementById('m_nourutpo'+j).value ))
						{
						   double = 'Y' ;
						}
					}

					if ( double == 'T' )
					{
						var iteration = lastRow2 - 1;
						var row = tbl2.insertRow(lastRow2 - 1);

						var txsize = size ;
						if (itemcode == 'Stone>0.3')
						{
							txsize = size + ' - ' +size2 + ' x ' +size3 ;
						}
						if (itemcode == 'PST')
						{
							txsize = size + ' - ' +size2 + ' x ' +size3 ;
						}
						
						var cellno = row.insertCell(0);
						cellno.innerHTML='<td><div id="sp-nopo'+iteration+'" align="center">'+nopo+'</div></td>';
						
						var cellno = row.insertCell(1);
						cellno.innerHTML='<td><div id="sp-desc'+iteration+'">'+desc+' '+brand+' '+gia+'</div></td>';
						
						var cellno = row.insertCell(2);
						cellno.innerHTML='<td><div id="sp-shape'+iteration+'" onclick="f_additem('+iteration+')" style="cursor:pointer" align="center">'+nmshape+'</div></td>';
						
						var cellno = row.insertCell(3);
						cellno.innerHTML='<td><div id="sp-size'+iteration+'" align="center">'+txsize+'</div></td>';
						
						var cellno = row.insertCell(4);
						cellno.innerHTML='<td><div id="sp-colour'+iteration+'" align="center">'+nmcolour+'</div></td>';
						
						var cellno = row.insertCell(5);
						cellno.innerHTML='<td><div id="sp-clarity'+iteration+'" align="center">'+nmclarity+'</div></td>';
						
						var cellno = row.insertCell(6);
						cellno.innerHTML='<td><div id="sp-carat'+iteration+'" align="center"><input type="text" id="m_carat'+iteration+'" name="m_carat'+iteration+'" value="'+formatangka(carat.toFixed(3).toString())+'" style="text-align:center" onchange = "recalc()" ></div></td>';
						
						var cellno = row.insertCell(7);
						cellno.innerHTML='<td><div id="sp-harga'+iteration+'" align="right">'+kurs+' '+formatangka(harga.toFixed(2).toString())+'</div></td>';
						
						var cellno = row.insertCell(8);
						cellno.innerHTML='<td><div id="sp-jumlah'+iteration+'" align="right">'+formatangka((carat*harga).toFixed(2).toString())+'</div></td>';
		
						var cellno = row.insertCell(9);
						cellno.innerHTML='<td><div id="sp-lokasi'+iteration+'" align="center"></div></td>';
						
									
						var cellno = row.insertCell(10);
						cellno.innerHTML='<td><div id="sp-lokasi'+iteration+'" align="center"><input type="text" id="m_parcel'+iteration+'" name="m_parcel'+iteration+'" value="'+parcel+'" style = "text-align:center" ></div></td>';				
																
						var cellno = row.insertCell(11);
						cellno.innerHTML='<td><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div><input type="hidden" id="m_stat'+iteration+'" name="m_stat'+iteration+'" value="Y" ><input type="hidden" id="m_no'+iteration+'" name="m_no'+iteration+'" value="" ><input type="hidden" id="m_nopo'+iteration+'" name="m_nopo'+iteration+'" value="'+nopo+'" ><input type="hidden" id="m_nourutpo'+iteration+'" name="m_nourutpo'+iteration+'" value="'+nourutpo+'" ><input type="hidden" id="m_desc'+iteration+'" name="m_desc'+iteration+'" value="'+desc+'" ><input type="hidden" id="m_shape'+iteration+'" name="m_shape'+iteration+'" value="'+shape+'" ><input type="hidden" id="m_size'+iteration+'" name="m_size'+iteration+'" value="'+size+'" ><input type="hidden" id="m_sizeb'+iteration+'" name="m_sizeb'+iteration+'" value="'+size2+'" ><input type="hidden" id="m_sizec'+iteration+'" name="m_sizec'+iteration+'" value="'+size3+'" ><input type="hidden" id="m_colour'+iteration+'" name="m_colour'+iteration+'" value="'+colour+'" ><input type="hidden" id="m_clarity'+iteration+'" name="m_clarity'+iteration+'" value="'+clarity+'" ><input type="hidden" id="m_brand'+iteration+'" name="m_brand'+iteration+'" value="'+brand+'" ><input type="hidden" id="m_gia'+iteration+'" name="m_gia'+iteration+'" value="'+gia+'" ><input type="hidden" id="m_kurs'+iteration+'" name="m_kurs'+iteration+'" value="'+kurs+'" ><input type="hidden" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="'+harga+'" ></td>';
		
								
						lastRow2 = lastRow2 + 1 ;
					}
				}
			}			
			f_closeschpo() ;
			recalc() ;
		}
		

		
		
		function f_cektanggal(el)
		{
			var tanggal = document.getElementById(el).value ;
			if ( el == 'm_tgl1') 
			{
				$("#tgl1").html(tanggal);
				document.getElementById('m_tanggal').value = tanggal ;
			}
			else if ( el == 'm_tgl2') 
			{
				$("#tgl2").html(tanggal);
				document.getElementById('m_duedate').value = tanggal ;
			}
			else if ( el == 'm_tgl3') 
			{
				$("#tgl3").html(tanggal);
				document.getElementById('m_tgldoc').value = tanggal ;
			}
		}
		
		
		function disableEnterKey(e,elid)
		{
			var key;
			if(window.event)
				key = window.event.keyCode; //IE
			else
				key = e.which; //firefox
			
			if ((key == 13) && (elid == ''))
			{
				
				return false;
			}
		}

	</script>