<?php

//FINPAY FUNCTION
function curl_post($url, $postdata, $timeout=0){
	$sentdata = '';
	foreach($postdata as $name=>$value){
		$sentdata .= $name.'='.$value.'&';
	}
	$sentdata = rtrim($sentdata,'&');
	$ssl_active = false;
	if(strtolower(substr($url,0,5))=="https"){
		$ssl_active = true;
	}
	$channel = curl_init($url);
	curl_setopt ($channel, CURLOPT_HEADER, false);
	curl_setopt ($channel, CURLINFO_HEADER_OUT, false);
	curl_setopt	($channel, CURLOPT_POST, 1);
	curl_setopt	($channel, CURLOPT_POSTFIELDS, $sentdata);
	curl_setopt	($channel, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt ($channel, CURLOPT_ENCODING, "");
    curl_setopt ($channel, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($channel, CURLOPT_AUTOREFERER, 1);
	curl_setopt ($channel, CURLOPT_URL, $url);
	if($ssl_active==true){
		//curl_setopt ($channel, CURLOPT_PORT , 443);
		curl_setopt ($channel, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($channel, CURLOPT_SSL_VERIFYHOST, 0);
	}
	if($timeout>0){
		curl_setopt ($channel, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt ($channel, CURLOPT_TIMEOUT, $timeout );
	}
    curl_setopt ($channel, CURLOPT_MAXREDIRS, 10);
    curl_setopt ($channel, CURLOPT_VERBOSE, 1);
	$output = curl_exec($channel);
	curl_close 	($channel);
	return $output;
}

function mer_signature($array){
	$output = '';
	foreach($array as $key=>$val){
		if(!empty($val)){
			$output .= $val.'%';
		}
	}
	return strtoupper($output);
}

function check_mer_signature($mer_signature,$array,$password){
	$comparator = mer_signature($array).$password;
	if(strtoupper($mer_signature)==strtoupper(hash256($comparator))){
		return true;
	}else{
		return false;
	}
}

function hash256($input){
	return hash("sha256",$input);
}

function writeLog($text,$prefix='195log'){
	$fileurl = public_path().'/'.$prefix.'_'.date('Ymd').'.txt';
	if(file_exists($fileurl)){
		if (!$handle = fopen($fileurl, 'a+')) {
			echo 'Cannot open file ('.$fileurl.')';
			exit;
		}
	}else{
		if (!$handle = fopen($fileurl, 'w')) {
			echo 'Cannot create file ('.$fileurl.')';
			exit;
		}
		@chmod($fileurl,0775);
	}
	if (fwrite($handle, $text."") === FALSE) {
		echo 'Cannot write to file ('.$fileurl.')';
		exit;
	}
	fclose($handle);
}

////end finpay
function price_format($a,$status=true){ // masuk 500000 ,, keluar jadi Rp. 500.000
	$pengaturan = Pengaturan::where('akunId','=',Session::get('akunid'))->remember(1)->first();
	if($pengaturan->checkoutType!=2){
		$string = $a . "";
		$tempKoma = "";
		if(strpos($string,".")!=false){
			$posKoma = strpos($string,".");
			$tempKoma = substr($string,$posKoma);
			$tempKoma = str_replace(".",",",$tempKoma);
			$tempKoma = substr($tempKoma,0,3);
			$string = substr($string,0,strpos($string,"."));
		}
		
		$jumDot = intval(strlen($string)/3);
		if(strlen($string) % 3 == 0){
			$jumDot = $jumDot-1;
		}
		$aha = 0;
		for($i=0; $i<$jumDot;$i++){
			$part[$i] = substr($string,strlen($string)-3);
			$string = substr($string,0,strlen($string)-3);
			$aha++;
		}
		
		$temp = $string;
		$string = "";
		for($i=0;$i<$jumDot;$i++){
			$string = "." . $part[$i] . $string;
		}
		$currencies = Currencies::remember(1)->find($pengaturan->mataUang);
		$string = ucfirst($currencies->symbol).' '. $temp . $string ;
		if($status==true){
			if($string != ucfirst($currencies->symbol)." 0"){
				return $string;
			}else{
				$string = ucfirst($currencies->symbol).' 0';
				return $string;
			}
		}else if($status==false){
			return '';
		}
	}else{
		return '';
	}
}

function get_upload_folder($akun){
	$upload = '-upload';
	return $akun->namaToko.$upload;	
}