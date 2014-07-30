<?php

require __DIR__ . '/vendor/autoload.php';

use Omerta\Ponionoo\Classes\OPCOnionoo;
$opc = new OPCOnionoo();

$ip = filter_input(INPUT_GET, 'ip', FILTER_SANITIZE_STRING);


if (empty($ip)) {
	$ip = $opc->getRealIp();
}

$result = $opc->getTorInformationByIp($ip);

echo '<pre>';
print_r("Searched IP: {$ip}");
echo '</pre>';

echo '<pre>';
print_r("Is tor enabled: ". ($opc->isTorEnabled($ip) ? 'Yes' : 'No'));
echo '</pre>';

echo '<pre>';
print_r("Api calls ");
print_r($opc->api_calls);
echo '</pre>';

echo '<pre>';
print_r("dump result: ");
print_r($result);
echo '</pre>';





