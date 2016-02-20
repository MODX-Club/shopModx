<?php


class shopModxGroupEditMgrManagerController extends modExtraManagerController{
    
    function __construct(modX &$modx, $config = array()) {
        parent::__construct($modx, $config);
        
        $namespace = "shopmodxgroupedit";
        $this->config['assets'] = $modx->getOption("{$namespace}.manager_url", null, $modx->getOption('manager_url')."components/{$namespace}/");
        $this->config['connectors_url'] = $this->config['assets'].'connectors/';
        $this->config['connector_url'] = $this->config['connectors_url'].'connector.php';
         
    }
    
    public function getOption($key, $options = null, $default = null, $skipEmpty = false){
        $options = array_merge($this->config, (array)$options);
        return $this->modx->getOption($key, $options, $default, $skipEmpty);
    }

    public function getLanguageTopics() {
        return array('shopmodxgroupedit:default');
    }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        
        $assets_url = $this->getOption('assets');
        
        $this->addJavascript($assets_url.'js/core/shopmodxgroupedit.js'); 
        
        $this->addHtml('<script type="text/javascript">
            shopModxGroupEdit.config = '. $this->modx->toJSON($this->config).';
        </script>');
        
        return;
    }
    
    public function getTemplatesPaths($coreOnly = false) {
        $paths = parent::getTemplatesPaths($coreOnly);
        $paths[] = $this->config['namespace_path']."templates/default/";
        return $paths;
    }
    
    # public function getTemplateFile() {
    #     return 'index.tpl';
    # }
}


class ControllersMgrManagerController extends shopModxGroupEditMgrManagerController{
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $className = __CLASS__;
        return new $className($modx, $config);
    }
    
    public static function getInstanceDeprecated(modX &$modx, $className, array $config = array()) {
        return self::getInstance($modx, $className, $config);
    }
    
    public function getPageTitle() {
        return $this->modx->lexicon('shopmodxgroupedit');
    }
    
    public function process(array $scriptProperties = array()) {
        
        $assets_url = $this->getOption('assets');
        
    }
    
    
    public function loadCustomCssJs() {
        
        parent::loadCustomCssJs();
        
        $assets_url = $this->getOption('assets');
         
        $this->addCss("{$assets_url}css/mgr.css");
        $this->addJavascript("{$assets_url}js/widgets/panel.js");
        $this->addJavascript("{$assets_url}js/widgets/tabs.js");
        $this->addJavascript("{$assets_url}js/widgets/grid.js");
        
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function(){MODx.add("shopmodxgroupedit-panel-mainpanel")});
        </script>');
        
        //$this->addJavascript($this->config['jsUrl'].'group_edit.js');
        /*$this->addJavascript($this->modx->getOption('manager_url').'assets/modext/util/datetime.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/resource/update.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/resource/create.js');*/
        
        /*$this->addJavascript($this->config['jsUrl'].'widgets/resources.grid.js');
        $this->addJavascript($this->config['jsUrl'].'shop/widgets/resources.grid.js');
        $this->addJavascript($this->config['jsUrl'].'widgets/home.panel.js');
        $this->addJavascript($this->config['jsUrl'].'widgets/modx.panel.resource.js');
        $this->addJavascript($this->config['jsUrl'].'sections/index.js');*/
        
        
    }
}
?>
