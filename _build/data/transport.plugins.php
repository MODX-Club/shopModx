<?php

$plugins = array();

/*
 * New plugin
 */
$plugin = $modx->newObject('modPlugin');
$plugin->set('id', 1);
$plugin->set('name', 'mypackage');
$plugin->set('description', '');
$plugin->set('plugincode', getSnippetContent($sources['source_core'].'/elements/plugins/plugin.php'));


/* add plugin events */
$events = array(); 
$events['OnHandleRequest']= $modx->newObject('modPluginEvent');
$events['OnHandleRequest']->fromArray(array(
    'event' => 'OnHandleRequest',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);
$plugin->addMany($events, 'PluginEvents');
$modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events.'); flush();
 
$plugins[] = $plugin;
/*
 * 
 */

return $plugins;

