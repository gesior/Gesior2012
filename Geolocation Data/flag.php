<?PHP
$t = microtime(true);
$lines = file('GeoIPCountryWhois.csv');
// Get from: http://www.maxmind.com/en/geolite
// Get 'GeoLite Country' [not city] in '.csv' format (in .zip, unzip before you run PHP script).
$lastIP = 0;
$lastCountry = '';
$fileC = -1;
$data = array();
foreach ($lines as $line_num => $line)
{
	$info = explode(",", $line, 6);
	$nIP = trim($info[0], '"'); // from IP human format
	$iIP = trim($info[2], '"'); // from IP long
	$iC = trim($info[4], '"'); // country code
	$startOfIP = explode('.', $nIP);
	if($startOfIP[0] != $fileC)
	{
		$fileC = $startOfIP[0];
		$data[$fileC][$lastIP] = $lastCountry;
	}
	$data[$fileC][$iIP] = strtolower($iC);
	$lastIP = $iIP;
	$lastCountry = strtolower($iC);

}
foreach($data as $i => $dat)
{
	file_put_contents('flags/flag' . $i, serialize($dat));
}
echo (microtime(true) - $t);
?>