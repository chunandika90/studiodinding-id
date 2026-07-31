<div style="font-size:12px;font:Arial, Helvetica, sans-serif;font-weight:bold">After Sales Policy / <i>Kebijakan Penjualan</i></div>
<?php
	if ($kdbrg == 'P0000004')
	{
		?>
        <table style="font-size:10px;font-family:Arial, Helvetica, sans-serif;border-collapse: collapse">
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="179" height="32">Merchandise Exchange</td>
                <td width="192" style="border-left:1px solid #000;">Trade - In PG to PG</td>
                <td width="191" style="border-left:1px solid #000;">Trade - In PG to DJ</td>
                <td width="230" style="border-left:1px solid #000;">Customer's option to resell</td>
            </tr>
            <tr align="center" valign="bottom">
                <td height="15" style="border-left:1px solid #000;">(<i>Penukaran Barang)</i></td>
                <td style="border-left:1px solid #000;">{<i>Tukar Tambah Plain Gold ke</i></td>
                <td style="border-left:1px solid #000;">{<i>Tukar Tambah Plain Gold ke</i></td>
                <td style="border-left:1px solid #000;border-right:1px solid #000">(<i>Opsi Penjualan Kembali)</i></td>
            </tr>
            <tr align="center" valign="top" style="border-bottom:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td height="15"></td>
                <td style="border-left:1px solid #000;"><i>Plain Gold</i>)</td>
                <td style="border-left:1px solid #000;"><i>Diamond Jewellery</i>)</td>
                <td style="border-left:1px solid #000;"></td>
            </tr>
            <tr align="center" style="border:1px solid #000;">
                <td height="23">Period - <i>Dalam waktu</i></td>
                <td style="border-left:1px solid #000;">Period - <i>Dalam waktu</i></td>
                <td style="border-left:1px solid #000;">Period - <i>Dalam waktu</i></td>
                <td style="border-left:1px solid #000;">Period - <i>Dalam waktu</i></td>
            </tr>
            <tr align="center" style="border:1px solid #000;">
                <td height="23"><= 2 weeks - <i>2 Minggu</i></td>
                <td style="border-left:1px solid #000;">> 2 weeks - <i>2 Minggu</i></td>
                <td style="border-left:1px solid #000;">At Anytime - <i>Kapan pun</i></td>
                <td style="border-left:1px solid #000;">At Anytime - <i>Kapan pun</i></td>
            </tr>
            <tr align="center" valign="bottom" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td height="15">100% Original Sales Amount -</td>
                <td style="border-left:1px solid #000;">Today Buying Price THE PALACE</td>
                <td style="border-left:1px solid #000;">Today Selling Price THE PALACE</td>
                <td style="border-left:1px solid #000;">Today Buying Price THE PALACE</td>
            </tr>
            <tr align="center" valign="top" style="border-bottom:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td height="15"><i>Nilai Penjualan Asal</i></td>
                <td style="border-left:1px solid #000;"></td>
                <td style="border-left:1px solid #000;"></td>
                <td style="border-left:1px solid #000;"></td>
            </tr>
            <tr>
                <td colspan="4" height="15" style="font-weight:bold" valign="bottom">*) Trade In dari Plain Gold ke Diamond Jewellery</td>
            </tr>
            <tr>
                <td colspan="4" height="15" valign="top"><i>Harga Plain Gold akan diperhitungan sesuai dengan harga Today Selling Price THE PALACE apabila Customer hendak menukarkannya dengan Diamond Jewellery</i></td>
            </tr>
        </table>
        <?php
	}
	else if (($kdbrg == 'J0000001') && ($dist == 'SOL') && ($crt < 0.9) && (($katg == 'SNI')||($katg == 'SI')||($katg == 'FAS')) )
	{
		?>
        <table style="font-size:10px;font-family:Arial, Helvetica, sans-serif;border-collapse: collapse">
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="130" height="15">Merchandise Exchange</td>
                <td style="border-left:1px solid #000;" colspan="3" rowspan="2">Trade - In</td>
                <td style="border-left:1px solid #000;border-right:1px solid #000;" colspan="2" rowspan="2">Customer's option to resell</td>
            </tr>
            <tr align="center">
                <td height="15" style="border-left:1px solid #000;">(<i>Penukaran Barang</i>)</td>
            </tr>
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="130" height="15">Period - <i>dalam waktu</i></td>
                <td style="border-left:1px solid #000;" colspan="3">Period - <i>Waktu</i></td>
                <td style="border-left:1px solid #000;" colspan="2">Period - <i>Waktu</i></td>
            </tr>
            <tr align="center" style="border-bottom:1px solid #000;">
                <td width="130" style="border-left:1px solid #000;" height="15">1 month - <i>1 bulan</i></td>
                <td width="130" style="border-left:1px solid #000;">1 year - <i>1 Tahun</i></td>
                <td width="130">< 2 year - <i>< 2 Tahun</i></td>
                <td width="130">> 2 year - <i>> 2 Tahun</i></td>
                <td width="130" style="border-left:1px solid #000;">1 year - <i>1 Tahun</i></td>
                <td width="130" style="border-right:1px solid #000;">> 1 year - > <i>1 Tahun</i></td>
            </tr>
            <tr align="center">
                <td width="130" style="border-left:1px solid #000;" height="20">100%</td>
                <td width="130" style="border-left:1px solid #000;">90 %</td>
                <td width="130">80%</td>
                <td width="130">THE PALACE</td>
                <td width="130" style="border-left:1px solid #000;">75%</td>
                <td width="130" style="border-right:1px solid #000;">THE PALACE</td>
            </tr>
            <tr align="center">
                <td width="130" style="border-left:1px solid #000;" height="20">Original Sales Amount</td>
                <td width="130" style="border-left:1px solid #000;">Original Sales Amount</td>
                <td width="130">Original Sales Amount</td>
                <td width="130">buying price</td>
                <td width="130" style="border-left:1px solid #000;">Original Sales Amount</td>
                <td width="130" style="border-right:1px solid #000;">The Palace buying price</td>
            </tr>
            <tr align="center"  style="border-bottom:1px solid #000;">
                <td width="130" style="border-left:1px solid #000;" height="20"><i>Nilai Penjualan Awal</i></td>
                <td width="130" style="border-left:1px solid #000;"><i>Nilai Penjualan Awal</i></td>
                <td width="130"><i>Nilai Penjualan Awal</i></td>
                <td width="130"><i>Harga Beli THE PALACE</i></td>
                <td width="130" style="border-left:1px solid #000;"><i>Nilai Penjualan Awal</i></td>
                <td width="130" style="border-right:1px solid #000;"><i>Harga Beli THE PALACE</i></td>
            </tr>
        </table>
        <?php
	}
	else if (($kdbrg == 'J0000001') && (($katg == 'SNI')||($katg == 'STI')||($katg == 'FAS')) )
	{
		?>
        <table style="font-size:10px;font-family:Arial, Helvetica, sans-serif;border-collapse: collapse">
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="130" height="15">Merchandise Exchange</td>
                <td style="border-left:1px solid #000;" colspan="3" rowspan="2">Trade - In</td>
                <td style="border-left:1px solid #000;border-right:1px solid #000;" colspan="2" rowspan="2">Customer's option to resell</td>
            </tr>
            <tr align="center">
                <td height="15" style="border-left:1px solid #000;">(<i>Penukaran Barang</i>)</td>
            </tr>
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="130" height="15">Period - <i>dalam waktu</i></td>
                <td style="border-left:1px solid #000;" colspan="3">Period - <i>Waktu</i></td>
                <td style="border-left:1px solid #000;" colspan="2">Period - <i>Waktu</i></td>
            </tr>
            <tr align="center" style="border-bottom:1px solid #000;">
                <td width="130" style="border-left:1px solid #000;" height="15">1 month - <i>1 bulan</i></td>
                <td width="130" style="border-left:1px solid #000;">1 year - <i>1 Tahun</i></td>
                <td width="130">< 2 year - <i>< 2 Tahun</i></td>
                <td width="130">> 2 year - <i>> 2 Tahun</i></td>
                <td width="130" style="border-left:1px solid #000;">1 year - <i>1 Tahun</i></td>
                <td width="130" style="border-right:1px solid #000;">> 1 year - > <i>1 Tahun</i></td>
            </tr>
            <tr align="center">
                <td width="130" style="border-left:1px solid #000;" height="20">100%</td>
                <td width="130" style="border-left:1px solid #000;">90 %</td>
                <td width="130">75%</td>
                <td width="130">THE PALACE</td>
                <td width="130" style="border-left:1px solid #000;">65%</td>
                <td width="130" style="border-right:1px solid #000;">THE PALACE</td>
            </tr>
            <tr align="center">
                <td width="130" style="border-left:1px solid #000;" height="20">Original Sales Amount</td>
                <td width="130" style="border-left:1px solid #000;">Original Sales Amount</td>
                <td width="130">Original Sales Amount</td>
                <td width="130">buying price</td>
                <td width="130" style="border-left:1px solid #000;">Original Sales Amount</td>
                <td width="130" style="border-right:1px solid #000;">buying price</td>
            </tr>
            <tr align="center"  style="border-bottom:1px solid #000;">
                <td width="130" style="border-left:1px solid #000;" height="20"><i>Nilai Penjualan Awal</i></td>
                <td width="130" style="border-left:1px solid #000;"><i>Nilai Penjualan Awal</i></td>
                <td width="130"><i>Nilai Penjualan Awal</i></td>
                <td width="130"><i>Harga Beli THE PALACE</i></td>
                <td width="130" style="border-left:1px solid #000;"><i>Nilai Penjualan Awal</i></td>
                <td width="130" style="border-right:1px solid #000;"><i>Harga Beli THE PALACE</i></td>
            </tr>
        </table>
        <?php
	}
	else if (($kdbrg == 'L0000001') && ($crt >= 0.9) && ($crt <= 2.99) )
	{
		?>
        <table style="font-size:10px;font-family:Arial, Helvetica, sans-serif;border-collapse: collapse">
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="130" height="15">Merchandise Exchange</td>
                <td style="border-left:1px solid #000;" colspan="2">Trade - In</td>
                <td style="border-left:1px solid #000;border-right:1px solid #000;" colspan="2">Customer's option to resell</td>
            </tr>
            <tr align="center">
                <td height="15" style="border-left:1px solid #000;">(<i>Penukaran Barang</i>)</td>
                <td height="15" style="border-left:1px solid #000;" colspan="2">(<i>Tukar Tambah</i>)</td>
                <td height="15" style="border-left:1px solid #000;border-right:1px solid #000" colspan="2">(<i>Opsi Penjualan Kembali</i>)</td>
            </tr>
            <tr align="center" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
                <td width="130" height="20">Period - <i>dalam waktu</i></td>
                <td style="border-left:1px solid #000;" colspan="2">Period - <i>dalam waktu</i></td>
                <td style="border-left:1px solid #000;" colspan="2">Period - <i>dalam waktu</i></td>
            </tr>
            <tr align="center" style="border-bottom:1px solid #000;">
                <td width="130" style="border-left:1px solid #000;" height="20">1 month - <i>1 bulan</i></td>
                <td width="130" style="border-left:1px solid #000;">< 2 year - <i>< 2 Tahun</i></td>
                <td width="130">> 2 year - <i>> 2 Tahun</i></td>
                <td width="130" style="border-left:1px solid #000;">1 year - <i>1 Tahun</i></td>
                <td width="130" style="border-right:1px solid #000;">> 1 year - > <i>1 Tahun</i></td>
            </tr>
            <tr align="center">
                <td width="130" style="border-left:1px solid #000;" height="15">100%</td>
                <td width="130" style="border-left:1px solid #000;">92.5%</td>
                <td width="130">THE PALACE</td>
                <td width="130" style="border-left:1px solid #000;">85%</td>
                <td width="130" style="border-right:1px solid #000;">THE PALACE</td>
            </tr>
            <tr align="center"  style="border-bottom:1px solid #000;">
                <td width="130" style="border-left:1px solid #000;" height="15">disc/prem as purchase</td>
                <td width="130" style="border-left:1px solid #000;">today market price</td>
                <td width="130">buying price</td>
                <td width="130" style="border-left:1px solid #000;">today market price</td>
                <td width="130" style="border-right:1px solid #000;">buying price</td>
            </tr>
        </table>
        <?php
	}
	else if (($kdbrg == 'L0000001') && ($crt >= 3.00) )
	{
		?>
        <table style="font-size:12px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;border:1px solid #000">
            <tr height="100">
                <td align="center" valign="middle" width="600">"THE PALACE buying price" - "<i>Harga beli THE PALACE</i>"</td>
            </tr>
        </table>
		<?php
	}
	else
	{
		?>
        <table style="font-size:12px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;border:1px solid #000">
            <tr height="100">
                <td align="center" valign="middle" width="600">"No Exchange No Resell" - "<i>Tidak dapat ditukar dan Tidak dapat dijual kembali</i>"</td>
            </tr>
        </table>
		<?php
	}	
?>
<table width="802">
	<tr style="font-size:10px;font:Arial, Helvetica, sans-serif">
    	<td width="75%">All Jewellery price are checked to be in good condition and accompanied by original certificate<br/><i>Perhiasan telah diperiksa dan dalam kondisi baik dan di sertai sertifikat asli</i></td>
        <td width="25%">Best Price Guarantee/<br/><i>Garansi Harga Terbaik</i></td>
    </tr>
</table>
<div>

</div>
