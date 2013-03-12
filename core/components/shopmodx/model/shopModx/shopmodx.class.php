<?php

class shopModx extends MODx{
    
    public $modx = null;
    
    function __construct(modX &$modx) {
        $this->modx= & $modx;
    }
    
    public function makeUrl($id, $context= '', $args= '', $scheme= -1, array $options= array()){
        $url= '';
        if ($validid = intval($id)) {
            if($validid == $this->modx->getOption('site_start', null)){
                $url= $this->modx->getOption('base_url', null, '/');
            }
            else{
                $url = $this->getUrl($validid);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '`' . $id . '` is not a valid integer and may not be passed to makeUrl()');
        }
        return $url;
    }
    
    protected function getUrl($id){
        $url = '';
        $key = $this->modx->context->key."/url/{$id}";
        if(!$url = $this->modx->cacheManager->get($key)){
            $q = $this->modx->newQuery('modResource', $id);
            $q->select(array('uri'));
            $q->limit(1);
            if($q->prepare() && $q->stmt->execute() && $row = $q->stmt->fetch(PDO::FETCH_ASSOC)){
                $url = $row['uri'];
            }
            if (!empty($url)) {
                if($this->modx->getOption('xhtml_urls', $options, false)){
                    $url= preg_replace("/&(?!amp;)/","&amp;", $url);
                }
                $this->modx->cacheManager->set($key, $url);
            }
        }
        return $url;
    }
    
}