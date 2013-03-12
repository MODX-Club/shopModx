<?php

$this->loadClass('modParser', MODX_CORE_PATH.'model/modx/', true, true);

class ShopmodxLinkTag extends modLinkTag{
    
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            if (is_numeric($this->_content)) {
                $this->_output = $this->modx->shopModx->makeUrl($this->_content);
            }
            if (!empty($this->_output)) {
                $this->filterOutput();
                $this->cache();
                $this->_processed= true;
            }
        }
        /* finally, return the processed element content */
        return $this->_output;
    }    
    
}

class ShopmodxParser extends modParser{
    
    public function processTag($tag, $processUncacheable = true) {
        $this->_processingTag = true;
        $element= null;
        $elementOutput= null;

        $outerTag= $tag[0];
        $innerTag= $tag[1];

        /* Avoid all processing for comment tags, e.g. [[- comments here]] */
        if (substr($innerTag, 0, 1) === '-') {
            return "";
        }

        /* collect any nested element tags in the innerTag and process them */
        $this->processElementTags($outerTag, $innerTag, $processUncacheable);
        $this->_processingTag = true;
        $outerTag= '[[' . $innerTag . ']]';

        $tagParts= xPDO :: escSplit('?', $innerTag, '`', 2);
        $tagName= trim($tagParts[0]);
        $tagPropString= null;
        if (isset ($tagParts[1])) {
            $tagPropString= trim($tagParts[1]);
        }
        $token= substr($tagName, 0, 1);
        $tokenOffset= 0;
        $cacheable= true;
        if ($token === '!') {
            if (!$processUncacheable) {
                $this->_processingTag = false;
                return $outerTag;
            }
            $cacheable= false;
            $tokenOffset++;
            $token= substr($tagName, $tokenOffset, 1);
        }
        if ($cacheable && $token !== '+') {
            $elementOutput= $this->loadFromCache($outerTag);
        }
        if ($elementOutput === null) {
            switch ($token) {
                case '+':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modPlaceholderTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '%':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modLexiconTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
                    $element->setCacheable($cacheable);
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '~':
                    // replace modLinkTag with ShopmodxLinkTag
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new ShopmodxLinkTag($this->modx);
                    $element->set('name', $tagName);
                    $element->setTag($outerTag);
                    $element->setCacheable($cacheable);
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '$':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    if ($element= $this->getElement('modChunk', $tagName)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                case '*':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $nextToken= substr($tagName, 0, 1);
                    if ($nextToken === '#') {
                        $tagName= substr($tagName, 1);
                    }
                    if (is_array($this->modx->resource->_fieldMeta) && in_array($this->realname($tagName), array_keys($this->modx->resource->_fieldMeta))) {
                        $element= new modFieldTag($this->modx);
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    elseif ($element= $this->getElement('modTemplateVar', $tagName)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                default:
                    $tagName= substr($tagName, $tokenOffset);
                    if ($element= $this->getElement('modSnippet', $tagName)) {
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput= $element->process($tagPropString);
                    }
            }
        }
        if (($elementOutput === null || $elementOutput === false) && $outerTag !== $tag[0]) {
            $elementOutput = $outerTag;
        }
        if ($this->modx->getDebug() === true) {
            $this->modx->log(xPDO::LOG_LEVEL_DEBUG, "Processing {$outerTag} as {$innerTag} using tagname {$tagName}:\n" . print_r($elementOutput, 1) . "\n\n");
            /* $this->modx->cacheManager->writeFile(MODX_BASE_PATH . 'parser.log', "Processing {$outerTag} as {$innerTag}:\n" . print_r($elementOutput, 1) . "\n\n", 'a'); */
        }
        $this->_processingTag = false;
        return $elementOutput;
    }
    
}