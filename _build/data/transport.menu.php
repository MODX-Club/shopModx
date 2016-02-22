<?php

$menus = array();

$menuindex = 0;

$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => NAMESPACE_NAME,
    'parent' => 'components',
    'description' => NAMESPACE_NAME.'.desc',
    # 'icon' => 'images/icons/plugin.gif',
    'action'      => '',
    'params'      => '',
    'handler'     => '',
    'menuindex'   => $menuindex++,
    'permissions' => NAMESPACE_NAME,
    'namespace'   => NAMESPACE_NAME,
),'',true,true);

$menus[] = $menu;


    $menu = $modx->newObject('modMenu',array(
        # 'text'          => NAMESPACE_NAME . ".orders",
        'parent'        => NAMESPACE_NAME,
        'description'   => NAMESPACE_NAME . ".orders.desc",
        'action'        => 'controllers/mgr/basket/orders/list',
        'params'        => '',
        'handler'       => '',
        'menuindex'     => $menuindex++,
        'permissions'   => NAMESPACE_NAME . 'orders',
        'namespace'     => NAMESPACE_NAME,
    ));
    $menu->set("text", NAMESPACE_NAME . ".orders");
    $menus[] = $menu;


    $menu = $modx->newObject('modMenu',array(
        # 'text'          => NAMESPACE_NAME . ".statuses",
        'parent'        => NAMESPACE_NAME,
        'description'   => NAMESPACE_NAME . ".statuses.desc",
        'action'        => 'controllers/mgr/basket/orderstatuses/',
        'params'        => '',
        'handler'       => '',
        'menuindex'     => $menuindex++,
        'permissions'   => NAMESPACE_NAME . 'statuses',
        'namespace'     => NAMESPACE_NAME,
    ));
    $menu->set("text", NAMESPACE_NAME . ".statuses");
    $menus[] = $menu;




return $menus;