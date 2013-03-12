<?php
/*
 * Worning! Use this request-class only with memory cache-providers like Memcached
 */


class ShopmodxRequest extends modRequest{
    public function handleRequest() {
        $this->loadErrorHandler();

        $this->sanitizeRequest();
        $this->modx->invokeEvent('OnHandleRequest');
        if (!$this->modx->checkSiteStatus()) {
            header('HTTP/1.1 503 Service Unavailable');
            if (!$this->modx->getOption('site_unavailable_page',null,1)) {
                $this->modx->resource = $this->modx->newObject('modDocument');
                $this->modx->resource->template = 0;
                $this->modx->resource->content = $this->modx->getOption('site_unavailable_message');
            } else {
                $this->modx->resourceMethod = "id";
                $this->modx->resourceIdentifier = $this->modx->getOption('site_unavailable_page',null,1);
            }
        } else {
            $this->checkPublishStatus();
            $this->modx->resourceMethod = $this->getResourceMethod();
            $this->modx->resourceIdentifier = $this->getResourceIdentifier($this->modx->resourceMethod);
            if ($this->modx->resourceMethod == 'id' && $this->modx->getOption('friendly_urls', null, false) && !$this->modx->getOption('request_method_strict', null, false)) {
                $uri = $this->modx->shopModx->makeUrl($this->modx->resourceIdentifier);
                if (!empty($uri)) {
                    if ($this->modx->resourceIdentifier == $this->modx->getOption('site_start', null, 1)) {
                        $url = $this->modx->getOption('site_url', null, MODX_SITE_URL);
                    } else {
                        $url = $this->modx->getOption('site_url', null, MODX_SITE_URL) . $uri;
                    }
                    $this->modx->sendRedirect($url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
                }
            }
        }
        if (empty ($this->modx->resourceMethod)) {
            $this->modx->resourceMethod = "id";
        }
        if ($this->modx->resourceMethod == "alias") {
            $this->modx->resourceIdentifier = $this->_cleanResourceIdentifier($this->modx->resourceIdentifier);
        }
        if ($this->modx->resourceMethod == "alias") {
            if ($this->getResourceID()) {
                $this->modx->resourceMethod = 'id';
            } else {
                $this->modx->sendErrorPage();
            }
        }
        $this->modx->beforeRequest();
        $this->modx->invokeEvent("OnWebPageInit");

        if (!is_object($this->modx->resource)) {
            if (!$this->modx->resource = $this->getResource($this->modx->resourceMethod, $this->modx->resourceIdentifier)) {
                $this->modx->sendErrorPage();
                return true;
            }
        }

        return $this->prepareResponse();
    }
    

    public function _cleanResourceIdentifier($identifier) {
        if (empty ($identifier)) {
            if ($this->modx->getOption('base_url', null, MODX_BASE_URL) !== $_SERVER['REQUEST_URI']) {
                $this->modx->sendRedirect($this->modx->getOption('site_url', null, MODX_SITE_URL), array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
            }
            $identifier = $this->modx->getOption('site_start', null, 1);
            $this->modx->resourceMethod = 'id';
        }
        elseif ($this->modx->getOption('friendly_urls', null, false) && $this->modx->resourceMethod == 'alias') {
            if ($this->modx->getOption('friendly_urls_strict', null, false)) {
                $requestUri = $_SERVER['REQUEST_URI'];
                $qsPos = strpos($requestUri, '?');
                if ($qsPos !== false) $requestUri = substr($requestUri, 0, $qsPos);
                $fullId = $this->modx->getOption('base_url', null, MODX_BASE_URL) . $identifier;
                $requestUri = urldecode($requestUri);
                if ($fullId !== $requestUri && strpos($requestUri, $fullId) !== 0) {
                    $parameters = $this->getParameters();
                    unset($parameters[$this->modx->getOption('request_param_alias')]);
                    $url = $this->modx->shopModx->makeUrl($this->modx->aliasMap[$identifier], '', $parameters, 'full');
                    $this->modx->sendRedirect($url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
                }
            }
            $this->modx->resourceMethod = 'alias';
        } else {
            $this->modx->resourceMethod = 'id';
        }
        return $identifier;
    }    
    
    protected function getResourceID() {
        $key = 'uri/'.$this->modx->context->key.'/'.$this->modx->resourceIdentifier;
        if(!$id = $this->modx->cacheManager->get($key)){
            $q = $this->modx->newQuery('modResource', array(
                'uri' => $this->modx->resourceIdentifier,
                'deleted' => 0,
            ));
            $q->select(array('modResource.id'));
            $q->limit(1);
            
            if($q->prepare() AND $q->stmt->execute() AND $row = $q->stmt->fetch(PDO::FETCH_ASSOC) AND $id = $row['id']){
                $this->modx->cacheManager->set($key, $id);
            }
        }
        if($id){
            // Check for base_url
            $site_start = $this->modx->getOption('site_start', null, 1);
            if($id == $site_start){
                $base_url = $this->modx->getOption('base_url', null, '/');
                if($this->modx->resourceIdentifier != $base_url){
                    $this->modx->sendRedirect($base_url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
                    return;
                }
            }
            
            $this->modx->resourceIdentifier = $id;
            return true;
        }
        return false;
    }
}
?>
