<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.07.2017
 * Time: 5:57
 */
require_once  ABSPATH . '/wp-admin/includes/nav-menu.php';
class WPMenu {
    static private $instance = null;
    static function instance()
    {
        if (!self::$instance) self::$instance = new self;
        return self::$instance;
    }
    public function get($idOrTerm)
    {
        return wp_get_nav_menu_object($idOrTerm);
    }
    public function delete($menuTermOrId)
    {
        return wp_delete_nav_menu($menuTermOrId);
    }
    public function getId($term)
    {
        $menuObj = $this->get($term);
        return $menuObj->term_id;
    }
    public function items($menuId)
    {
        return new WPMenuItem($menuId);
    }
}
class WPMenuItem {
    private $menuId = null;
    public function __construct($menuId)
    {
        $this->menuId = $menuId;
    }
    public function get($itemId)
    {
        $items = $this->getItems();
        foreach ($items as $item) {
            if ($item['ID'] == $itemId) return $item;
        }
        return false;
    }
    public function getItems()
    {
        return wp_get_nav_menu_items( $this->menuId, array( 'orderby' => 'ID', 'output' => ARRAY_A, 'output_key' => 'ID', 'post_status' => 'draft,publish' ) );
    }
    public function addItem()
    {
        return new WPMenuAddItem($this->menuId);
    }
    public function delete($itemId)
    {
        return wp_delete_post($itemId);//yes yes..shitcode from wordpress - /wp-admin/includes/nav-menu.php 1056(WordPress 4.7.5)
    }
}
class WPMenuAddItem {
    private $params = array();
    private $menuId = null;
    function __construct($menuId)
    {
        $this->menuId = $menuId;
        return $this;
    }
    public function pageType()
    {
        $this->params['menu-item-object'] = 'page';
        $this->params['menu-item-type'] = 'post_type';
        return $this;
    }
    public function postType()
    {
        $this->params['menu-item-object'] = 'post';
        $this->params['menu-item-type'] = 'post_type';
        return $this;
    }
    public function customType()
    {
        $this->params['menu-item-object'] = '';
        $this->params['menu-item-type'] = 'custom';
        return $this;
    }
    public function objectId($id)
    {
        $this->params['menu-item-object-id'] = $id;
        return $this;
    }
    public function parentId($id)
    {
        $this->params['menu-item-parent-id'] = $id;
        return $this;
    }
    public function title($title)
    {
        $this->params['menu-item-title'] = $title;
        return $this;
    }
    public function url($url)
    {
        $this->params['menu-item-url'] = $url;
        return $this;
    }
    public function description($desc)
    {
        $this->params['menu-item-description'] = $desc;
        return $this;
    }
    public function classes($classes)
    {
        $this->params['menu-item-classes'] = $classes;
        return $this;
    }
    public function setParams($paramsAr)
    {
        $this->params = array_merge($this->params, $paramsAr);
        return $this;
    }
    public function add()
    {
        $default = array(
            'menu-item-object-id' => 0,
            'menu-item-object' => 'page',
            'menu-item-parent-id' => 0,
            'menu-item-type' => 'page',
            'menu-item-title' => 'noname',
            'menu-item-xfn' => '',
            'menu-item-description' => '',
            'menu-item-classes' => '',
            'menu-item-status' => 'publish',
        );
        $params = array_merge($default, $this->params);
        $itemId = current(wp_save_nav_menu_items($this->menuId, array($params)));
        return wp_update_nav_menu_item($this->menuId, $itemId, $params);
    }
}