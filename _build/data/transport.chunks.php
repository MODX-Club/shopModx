<?php

$chunks = array();


$chunk = $modx->newObject('modChunk', array(
    'name'          =>  'samplepackage.chunk',
    'description'   => 'chunk description',
    'snippet'       => getSnippetContent($sources['source_core'].'/elements/chunks/chunk.html'),
));
$chunks[] = $chunk;
 

return $chunks;
