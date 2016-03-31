<?php 
print '<pre>';
ini_set('display_errors', 1);
$modx->switchContext('web');
$modx->setLogLevel(3);
$modx->setLogTarget('HTML');
 
$namespace = 'shopmodx';        // Неймспейс комопонента

$modx->user = $modx->newObject('modUser');

$params = array(
    "MerchantLogin" => "ShopModxBox",
    "OutSum"    => "40560.00",      // Важно передавать именно строку с нулями, иначе MD5 будет косячить
    "InvId"     => "1627271284",
    "SignatureValue"    => "ECE02B8050CA813198CE2209F6928ECC",
    
    // При тестировании в робокассе надо указывать эти параметры
    "shp_aid"   => 1,
    "shp_order"   => 1,
    "shp_trff"   => 1,
    "shp_uid"   => 1,
);

parse_str('out_summ=14260&OutSum=14260&inv_id=1952861011&InvId=1952861011&crc=5568BA6A0B30359A889E27674F4E30C0&SignatureValue=5568BA6A0B30359A889E27674F4E30C0&PaymentMethod=OceanBank&IncSum=15258.20&IncCurrLabel=BANKOCEAN3R&IsTest=1&shp_aid=&shp_order=4&shp_trff=&shp_uid=44', $params);

// print_r($params);

// global $site_id;
// print $site_id;
// print $modx->getOption('site_id');
// return;

// Проверка оплаты через сам сниппет
// print $modx->runSnippet('robokassa.payResult', $params);
// return;

if(!$response = $modx->runProcessor('shopmodx/payments/robokassa/create',
$params
, array(
'processors_path' => $modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
))){
print "Не удалось выполнить процессор";
return;
}
 
$memory = round(memory_get_usage(true)/1024/1024, 4).' Mb';
print "<div>Memory: {$memory}</div>";
$totalTime= (microtime(true) - $modx->startTime);
$queryTime= $modx->queryTime;
$queryTime= sprintf("%2.4f s", $queryTime);
$queries= isset ($modx->executedQueries) ? $modx->executedQueries : 0;
$totalTime= sprintf("%2.4f s", $totalTime);
$phpTime= $totalTime - $queryTime;
$phpTime= sprintf("%2.4f s", $phpTime);
print "<div>TotalTime: {$totalTime}</div>";

print_r($response->getResponse());

// $objects = $response->getObject();
// foreach($objects as $object){
// }