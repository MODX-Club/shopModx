<?php

$snippets = array();


$snippet= $modx->newObject('modSnippet');
$snippet->fromArray(array(
    'name' => 'samplepackage.snippet',
    'description' => 'snippet description',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.php'),
),'',true,true);
$snippets[] = $snippet;


return $snippets;