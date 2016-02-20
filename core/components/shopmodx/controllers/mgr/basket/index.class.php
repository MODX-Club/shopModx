<?php

require_once dirname(dirname(__FILE__)) . '/index.class.php';

class ShopmodxControllersMgrBasketManagerController extends ShopmodxControllersMgrManagerController{
    
    # function __construct(modX &$modx, $config = array()) {
    #     parent::__construct($modx, $config);
    #     $namespace = "basket";
    #     $this->config['namespace_assets_path'] = $modx->call('modNamespace','translatePath',array(&$modx, $this->config['namespace_assets_path']));
    #     $this->config['manager_url'] = 
    #     $this->config['assets'] = 
    #     $this->config['assets_url'] = 
    #     $modx->getOption("{$namespace}.manager_url", null, $modx->getOption('manager_url')."components/{$namespace}/");
    #     $this->config['connector_url'] = $this->config['manager_url'].'connectors/';
    # }
    
    # public static function getInstance(modX &$modx, $className, array $config = array()) {
    #     $className = __CLASS__;
    #     return new $className($modx, $config);
    # }
    # 
    # public static function getInstanceDeprecated(modX &$modx, $className, array $config = array()) {
    #     return self::getInstance($modx, $className, $config);
    # }
    # 
    # public function getOption($key, $options = null, $default = null, $skipEmpty = false){
    #     $options = array_merge($this->config, (array)$options);
    #     return $this->modx->getOption($key, $options, $default, $skipEmpty);
    # }

    # public function getLanguageTopics() {
    #     return array('basket:default');
    # }

    # function loadCustomCssJs(){
    #     parent::loadCustomCssJs();
    #     
    #     $attrs = $this->modx->user->getAttributes(array(),'', true);
    #     $policies = array();
    #     if(!empty($attrs['modAccessContext']['mgr'])){
    #         foreach($attrs['modAccessContext']['mgr'] as $attr){
    #             foreach($attr['policy'] as $policy => $value){
    #                 if(empty($policies[$policy])){
    #                     $policies[$policy] = $value;
    #                 }
    #             }
    #         }
    #     }
    #     
    #     $this->modx->regClientStartupScript('<script type="text/javascript">
    #         Shop.policies = '. $this->modx->toJSON($policies).';
    #     </script>', true);
    #     
    #     /*$this->addJavascript($this->getOption('assets_url').'js/shop.js'); 
    #     
    #     
    #     
    #     $this->addHtml('<script type="text/javascript">
    #         Shop.config = '. $this->modx->toJSON($this->config).';
    #     </script>');*/
    #     
    #     return;
    # }
    
    # public function getTemplatesPaths($coreOnly = false) {
    #     $paths = parent::getTemplatesPaths($coreOnly);
    #     $paths[] = $this->config['namespace_path']."templates/default/";
    #     return $paths;
    # }
    
    # public function getTemplateFile() {
    #     return 'index.tpl';
    # }
}
