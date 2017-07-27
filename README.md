# wordpress-wpmenu
Simple class helps to work with wordpress menu

#Examples
''
//delete menu
WPMenu::instance()->delete('top_menu');

//get object detail info menu
$topMenu = WPMenu::instance()->get('top_menu');

//get menu id
$menuId = WPMenu::instance()->getId('top_menu');

//add menu new item
$res = WPMenu::instance()->items($menuId)->addItem()->pageType()->objectId($pageId)->parentId(69)->title('Документы')->add();

//get menu items
$res = WPMenu::instance()->items($menuId)->getItems();
$itemId = $res[0]['ID'];
//get menu item
$res = WPMenu::instance()->items($menuId)->get($itemId);
//delete menu item
$res = WPMenu::instance()->items($menuId)->delete($itemId);
''
