<?php
session_start();
require_once('SwaggerClient-php/autoload.php');
require_once('vendor/autoload.php');
require_once('provider.php');



function token_refresh()//refresh access token if expired
{
	global $provider;
	if(unserialize($_SESSION['accesstoken-obj'])->hasExpired()){//get new access token if expired
		$newAccessToken=$provider->getAccessToken('refresh_token', [
			'refresh_token' => unserialize($_SESSION['accesstoken-obj'])->getRefreshToken()
		]);
		$_SESSION['accesstoken-obj']=serialize($newAccessToken);
		// echo "access token refreshed";
	}

	//DEBUG
/*	$expires_in = unserialize($_SESSION['accesstoken-obj'])->getExpires()-time();
	echo "expires in: " . $expires_in . "<br>";
	echo "access token: " . unserialize($_SESSION['accesstoken-obj'])->getToken() . "<br>";
	echo "refresh token: " . unserialize($_SESSION['accesstoken-obj'])->getRefreshToken() . "<br>";*/
}

function token(){
	return unserialize($_SESSION['accesstoken-obj'])->getToken();
}

function charid(){
	return $_SESSION['charinfo']['CharacterID'];
}

function getcontract($charid,$token){
	$url="http://api.eveonline.com/char/Contracts.xml.aspx?characterID=".$charid."&accessToken=".$token;
	print "<p>".$url."</p>";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-from-urlencoded'));
	// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)');
	$raw=curl_exec($ch);
	curl_close($ch);

	return $raw;
}


?>
