<?php

	function money2($nilai)
	{
		$aSatuan = array(
		1 => "satu", 
		2 => "dua", 
		3 => "tiga", 
		4 => "empat", 
		5 => "lima", 
		6 => "enam",
		7 => "tujuh", 
		8 => "delapan", 
		9 => "sembilan", 
		10 => "sepuluh", 
		11 => "sebelas", 
		12 => "dua belas", 
		13 => "tiga belas", 
		14 => "empat belas", 
		15 => "lima belas", 
		16 => "enam belas", 
		17 => "tujuh belas", 
		18 => "delapan belas", 
		19 => "sembilan belas" ) ;
		
		$amount = str_replace(",","",$nilai);
		$hasil = '' ;
		
		if ( $amount >= 100 )
		{
			$nRatus = floor($amount/100.00) ;
			if ($nRatus == 1)
			{ 
				$hasil = $hasil."seratus " ;
			}
			else
			{
				$hasil = $hasil.$aSatuan[$nRatus]." ratus " ;
			}
			if ( $amount > 0 ) { $amount = $amount - ($nRatus * 100) ; }
	
		}
		
		if ( $amount >= 20 )
		{
			$nPuluh = floor($amount/10.00) ;
			if ($nPuluh == 1)
			{ 
				$hasil = $hasil."sepuluh " ;
			}
			else
			{
				$hasil = $hasil.$aSatuan[$nPuluh]." puluh " ;
			}
			$amount = $amount - ($nPuluh * 10) ;		
			if ( $amount > 0 ) { $hasil = $hasil.$aSatuan[$amount] ; }
	
		}
		else
		{
			if ( $amount > 0 ) { $hasil = $hasil.$aSatuan[$amount] ; }
		}
		return $hasil ;		

	}
	

	function money1($nilai)
	{
		$amount = str_replace(",","",$nilai);	
		$hasil = '' ;
		$temp = 0 ;
		
		if ( $amount < 0 ) { $hasil = ''; }
		if ( $amount < 1 ) { $hasil = 'nol'; }
	
		if ( $amount >= 1000000000000.00 ) 
		{ 
			$temp = floor($amount/1000000000000.00) ;
			$hasil = $hasil.money2($temp)." trilyun " ;
			$amount = $amount - ($temp * 1000000000000.00) ;
		}
	
		if ( $amount >= 1000000000.00 ) 
		{ 
			$temp = floor($amount/1000000000.00) ;
			$hasil = $hasil.money2($temp)." milyar " ;
			$amount = $amount - ($temp * 1000000000.00) ;
		}
	
		if ( $amount >= 1000000.00 ) 
		{ 
			$temp = floor($amount/1000000.00) ;
			$hasil = $hasil.money2($temp)." juta " ;
			$amount = $amount - ($temp * 1000000.00) ;
		}

		if ( $amount >= 1000.00 ) 
		{ 
			$temp = floor($amount/1000.00) ;
			if ($temp == 1)
			{
				$hasil = $hasil." seribu ";
			}
			else
			{
				$hasil = $hasil.money2($temp)." ribu " ;
			}
			$amount = $amount - ($temp * 1000.00) ;
		}

		$temp = floor($amount) ;
		$hasil = $hasil.money2($temp) ;
		
		$amount = $amount - $temp ;
		if ( $amount > 0 ) 
		{
			$amount = $amount * 100 ;
			$hasil = $hasil." dan ".money2($amount) ;
		}

		return $hasil ; 	

	}
	
?>