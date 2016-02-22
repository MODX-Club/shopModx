<?php
switch($modx->event->name){
    case 'OnManagerPageInit':
        if(!$assetsUrl = $modx->getOption('shopmodx.manager_url',null)){
            $assetsUrl = $modx->getOption('manager_url').'components/shopmodx/';
        }
        $cssFile = $assetsUrl.'css/style.css';
        
        $modx->regClientCSS($cssFile);
        $modx->regClientStartupScript($assetsUrl.'js/core/shopmodx.js');
        $modx->regClientStartupScript($assetsUrl.'js/widgets/combo/currencies.combo.js');
        $modx->regClientStartupScript($assetsUrl.'js/widgets/orders/orderstatus.combo.js');

        $attrs = $modx->user->getAttributes(array(),'', true);
        $policies = array();
        if(!empty($attrs['modAccessContext']['mgr'])){
            foreach($attrs['modAccessContext']['mgr'] as $attr){
                foreach($attr['policy'] as $policy => $value){
                    if(empty($policies[$policy])){
                        $policies[$policy] = $value;
                    }
                }
            }
        }
        
        $modx->regClientStartupScript('<script type="text/javascript">
            Shopmodx.policies = '. $modx->toJSON($policies).';
            Shopmodx.sudo = '. (int)$modx->user->sudo.';
        </script>', true);
        
        $modx->regClientStartupScript($assetsUrl .'js/widgets/groupedit/shopmodxgroupedit.js'); 
        
        $modx->regClientStartupScript($assetsUrl.'js/widgets/groupedit/grid.js'); 
                
        break;
}