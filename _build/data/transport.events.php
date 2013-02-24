<?php

$events = array();

$event = $modx->newObject('modEvent', array(
    'service'   => 1,
    'groupname' => 'shopModx',
)); 
$event->set('name', 'OnShopModxSetResourcesCreateRules');
$events[] = $event;

return $events;

