<?php

$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Firefox/76.0';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';

echo "J&T Tracking - By @PierreAji\n";
echo "Masukan Nomor Resi: ";
$resi = trim(fgets(STDIN));

$cek = curl('https://jet.co.id/index/router/index.html', "method=app.findTrack&data[billcode]=".$resi."&data[lang]=en&data[source]=3", $headers);
if (strpos($cek[1], '"success":true')) {
	$dat = json_decode($cek[1])->data;
	$data = json_decode($dat);
	echo "\n";
	for ($i=0; $i < count($data->details); $i++) {
		$no = $i+1;
		$tgl = $data->details[$i]->scantime;
		$type = $data->details[$i]->scanstatus;
		$site = $data->details[$i]->siteName;
		$city = $data->details[$i]->city;
		$desc = $data->details[$i]->desc;
		echo "$no. [$tgl] | $type - $site - $city | $desc\n";
	}
} else {
	die($cek[1]);
}

function curl($url,$post,$headers)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
		  parse_str($item, $cookie);
		  $cookies = array_merge($cookies, $cookie);
		}
		return array (
		$header,
		$body,
		$cookies
		);
	}
