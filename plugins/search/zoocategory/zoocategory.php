<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

require_once __DIR__ . '/model.categorysearch.php';

/**
 * Class plgSearchZooCategory
 */
class plgSearchZooCategory extends JPlugin
{
    /**
     * @var mixed
     */
    public $menu;

    /**
     * @var App
     */
    public $app;

    /**
     * @var ParameterData
     */
    public $_plgConf;

    /**
     * @var JDatabase|DatabaseHelper
     */
    protected $db;

    /**
     * @var Int
     */
    protected $_curCat;

    /**
     * @param $subject
     * @param $params
     */
    public function plgSearchZooCategory($subject, $params)
    {

        // make sure ZOO exists
        if (!JComponentHelper::getComponent('com_zoo', true)->enabled) {
            return;
        }

        parent::__construct($subject, $params);

        // load config
        jimport('joomla.filesystem.file');

        if (
            !JFile::exists(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php') ||
            !JComponentHelper::getComponent('com_zoo', true)->enabled
        ) {
            return;
        }

        require_once(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php');

        $this->app = App::getInstance('zoo');
        $this->db  = $this->app->database;

        $plugin         = JPluginHelper::getPlugin('search', 'zoocategory');
        $this->_plgConf = $this->app->parameter->create($plugin->params);

        $this->_curCat = self::getCategory();
        $this->_model  = JBModelModelCategorySearch::model();
    }

    /**
     * @return mixed
     */
    public function onSearchAreas()
    {
        static $areas = array();
        return $areas;
    }

    /**
     * @param string $text
     * @param string $phrase
     * @param string $ordering
     * @param null   $areas
     * @return array
     */
    public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
        // init vars
        $text = trim($text);

        // return empty array, if no search text provided
        if (empty($text)) {
            return array();
        }

        //$catList    = $this->_model->loadCategories($text, $phrase, $this->_curCat);
        //$categories = $this->_renderTree($catList);
        $items = $this->_model->loadItems($text, $phrase, $this->_curCat);

        $i = 0;
        foreach($items as $item) {
            $res[] = (object)array(
                'item_id'    => $item->id,
                'text'       => '',
                'created'    => '',
                'section'    => '',
                'browsernav' => '',
                'href'       => '',
                'catId'      => 42,
                'title'      => $item->name,
                'alias'      => $item->alias,
                'items'      => $idList,
            );
        }

        return $res;
    }

    /**
     * @param $categories
     * @return mixed
     */
    protected function _renderTree($categories)
    {

        $fullList = $application = $this->app->zoo->getApplication()->getCategoryTree();
        $list     = $this->app->tree->buildList(0, $fullList);

        foreach ($list as $catId => $category) {
            $id = $category->id;

            if ($id != 0 && !in_array($id, $categories)) {
                $category->isEmpty = true;
            }
        }

        return $list;
    }

    /**
     * @param array $items
     * @param array $categories
     * @return array
     */
    protected function _renderHtml($items, $categories)
    {
        $groupedItems = array();

        if (!empty($items)) {

            foreach ($categories as $category) {
                $groupedItems[$category->id] = (object)array(
                    'text'       => '',
                    'created'    => '',
                    'section'    => '',
                    'browsernav' => '',
                    'href'       => '',
                    'catId'      => $category->id,
                    'title'      => $category->name,
                    'alias'      => $category->alias,
                    'categories' => &$categories,
                    'items'      => array(),
                );
            }

            // @var Item $item
            foreach ($items as $item) {

                $categoryId = $item->category_id;
                if (!$categoryId) {
                    continue;
                }

                $groupedItems[$categoryId]->items[$item->id] = $item->id;
            }
        }

        return $groupedItems;
    }


    /**
     * @param $categoryId
     * @return string
     */
    public static function getCategoryLink($categoryId)
    {
        if (!$categoryId) {
            return null;
        }

        $app  = App::getInstance('zoo');
        $pUrl = new JUri($app->jbenv->getCurrentUrl());
        $pUrl->setVar('areas', array($categoryId));

        return $pUrl->toString();
    }

    public static function getCategory()
    {
        $app = App::getInstance('zoo');

        $areas = (array)$app->jbrequest->get('areas', array());
        if (count($areas)) {
            reset($areas);
            return current($areas);
        }

        return 0;
    }

    public static function getTotal(& $list)
    {
        $count = 0;
        if (!empty($list)) {
            foreach ($list as $category) {
                $count += count($category->items);
            }
        }

        return $count;
    }

    public static function createItems($idList)
    {
        return JBModelModelCategorySearch::model()->createItems($idList);
    }
}