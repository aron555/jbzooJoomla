<?php
/**
 * JBZoo App is universal Joomla CCK, application for YooTheme Zoo component
 * @package     jbzoo
 * @version     2.x Pro
 * @author      JBZoo App http://jbzoo.com
 * @copyright   Copyright (C) JBZoo.com,  All rights reserved.
 * @coder       Denis Smetannikov <denis@jbzoo.com>
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


if (file_exists( dirname(__FILE__) . '/class.jbdump.php')) { @require_once dirname(__FILE__) . '/class.jbdump.php'; }

/**
 * Class JBModelModelCategorySearch
 */
class JBModelModelCategorySearch extends JBModel
{

    const LIMIT_CATEGORIES = 200;
    const LIMIT_ITEMS      = 10000;

    /**
     * Create and return self instance
     * @return JBModelModelCategorySearch
     */
    public static function model()
    {
        return new self();
    }

    /**
     * @param $text
     * @param $phrase
     * @param $curCat
     * @return mixed
     */
    public function loadCategories($text, $phrase, $curCat)
    {
        $words = $this->_morphTrimmer($text);
        if (empty($words)) {
            return array();
        }

        $select = $this->_getSelect()
            ->select('DISTINCT tCategory.id')
            ->from(ZOO_TABLE_ITEM, 'tItem')
            ->leftJoin(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategoryItem ON tItem.id = tCategoryItem.item_id')
            ->leftJoin(ZOO_TABLE_CATEGORY . ' AS tCategory ON tCategoryItem.category_id = tCategory.id')
            ->where('tItem.' . $this->app->user->getDBAccessString())
            ->where('tItem.state = ?', 1)
            ->where('(tItem.publish_up = ' . $this->_dbNull . ' OR tItem.publish_up <= ' . $this->_dbNow . ')')
            ->where('(tItem.publish_down = ' . $this->_dbNull . ' OR tItem.publish_down >= ' . $this->_dbNow . ')')
            ->limit(self::LIMIT_CATEGORIES);

        if ($curCat) {
            $catList = JBModelCategory::model()->getNestedCategories($curCat);
            $catList[] = $curCat;
            $catList = array_filter($catList);
            if (!empty($catList)) {
                $select->where('tCategoryItem.category_id IN (' . implode(',', $catList) . ')');
            } else {
                $select->where('tCategory.id > ?', 0);
            }
        } else {
            $select->where('tCategory.id > ?', 0);
        }

        $wheres = array();
        foreach ($words as $word) {
            $word = $this->_db->Quote('%' . $this->_db->escape($word, true) . '%', false);

            $like = array(
                'tItem.name LIKE ' . $word,

                'EXISTS (SELECT value FROM ' . ZOO_TABLE_SEARCH
                . ' WHERE tItem.id = item_id AND value LIKE ' . $word . ' AND ' . $this->_getAccessExclude() . ')',

                'EXISTS (SELECT name FROM ' . ZOO_TABLE_TAG . ' WHERE tItem.id = item_id AND name LIKE ' . $word . ')',
            );

            $wheres[] = implode(' OR ', $like);
        }

        $where = '(' . implode(') OR (', $wheres) . ')';
        $select->where('(' . $where . ')');

        //jbdump::sql($select);

        return $this->fetchList($select);
    }

    /**
     * @param $text
     * @param $phrase
     * @param $curCat
     * @return mixed
     */
    public function loadItems($text, $phrase, $curCat)
    {
        $places = array('tag', 'index', 'category', 'sku');

        //dump($text, 0);

        $words       = $this->_morphTrimmer($text);
        $noTrimWords = $this->_morphTrimmer($text, false);

        if (empty($words)) {
            return array();
        }

        $selects = array();

        foreach ($noTrimWords as $word) {
            $word = trim($word);
            if (!$word) {
                continue;
            }

            $select = $this->_getItemSubSelect();
            $select->where('tItem.name LIKE ' . $this->_db->Quote('%' . $this->_db->escape($word, true) . '%', false));
            $selects[] = $select->__toString();
        }

        foreach ($places as $place) {

            foreach ($words as $word) {
                $word = trim($word);
                if (!$word) {
                    continue;
                }

                $word = $this->_db->Quote('%' . $this->_db->escape($word, true) . '%', false);

                if ($place == 'tag') {

                    $subSelect = $this->_getSelect()
                        ->select('name')
                        ->from(ZOO_TABLE_TAG . ' AS tTag')
                        ->where('tItem.id = tTag.item_id')
                        ->where('tTag.name LIKE ' . $word);

                    $select->where('EXISTS (' . $subSelect . ')', null, 'OR');

                } else if ($place == 'category') {

                    $subSelect = $this->_getSelect()
                        ->select('name')
                        ->from(ZOO_TABLE_CATEGORY . ' AS tCategory')
                        ->leftJoin(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategoryItem ON tCategory.id = tCategoryItem.category_id')
                        ->where('tItem.id = tCategoryItem.item_id')
                        ->where('tCategory.name LIKE ' . $word);

                    $select->where('EXISTS (' . $subSelect . ')', null, 'OR');

                } else if ($place == 'index') {

                    $word = str_replace("'%", "'% ", $word);

                    $subSelect = $this->_getSelect()
                        ->select('value')
                        ->from(ZOO_TABLE_SEARCH . ' AS tIndex')
                        ->where('tItem.id = tIndex.item_id')
                        ->where('tIndex.value LIKE ' . $word)
                        ->where($this->_getAccessExclude());

                    $select->where('EXISTS (' . $subSelect . ')', null, 'OR');

                } else if ($place == 'sku') {

                    $word = str_replace("'%", "'%", $word);

                    $subSelect = $this->_getSelect()
                        ->select('value_s')
                        ->from(ZOO_TABLE_JBZOO_SKU . ' AS tSku')
                        ->where('tItem.id = tSku.item_id')
                        ->where('tSku.value_s LIKE ' . $word)
                        ->where($this->_getAccessExclude());

                    $select->where('EXISTS (' . $subSelect . ')', null, 'OR');
                }

                $selects[] = $select->__toString();
            }
        }

        if (isset($_GET['d'])) {
           print_r($selects);
        }

        $union = '(' . implode(') UNION ALL (', $selects) . ')';

        $allSelect = $this->_getSelect()
            ->select('tAll.id')
            ->select('COUNT(tAll.id) AS count')
            ->from('(' . $union . ') AS tAll')
            ->group('tAll.id');


        $main = $this->_getSelect()
            ->select(array(
                'tWrap.id',
                'tWrap.count',
                'tCategoryItem.category_id',
                '(' . implode(' + ', array(     // Где жажда победы?! Где огонь в глазах?! Больше ахня!!! (c) Смешарики.

                    'IF(tItem.name = "' . $this->_db->escape($text, true) . '", 5, 0)',                                             // x5 - полное совпадение

                    'IF(tItem.name = "'         . implode('", 3, 0)    + IF(tItem.name = "',        $noTrimWords) . '", 3, 0)',     // x3 - полное совпадение (deprecated)

                    'IF(tItem.name REGEXP "'    . implode('$" , 2, 0)  + IF(tItem.name REGEXP "',   $noTrimWords) . '$", 2, 0)',    // x2 - конец строки
                    'IF(tItem.name REGEXP " '   . implode('$" , 2, 0)  + IF(tItem.name REGEXP " ',  $noTrimWords) . '$", 2, 0)',    // x2 - конец строки и пробел слева

                    'IF(tItem.name REGEXP "^'   . implode('" , 2, 0)   + IF(tItem.name REGEXP "^',  $noTrimWords) . '", 2, 0)',     // x2 - начало строки
                    'IF(tItem.name REGEXP "^'   . implode(' " , 2, 0)  + IF(tItem.name REGEXP "^',  $noTrimWords)  . ' ", 2, 0)',   // x2 - начало строки и пробел справа

                    'IF(tItem.name REGEXP "'    . implode('\-" , 1, 0) + IF(tItem.name REGEXP "',   $noTrimWords) . '\-", 1, 0)',   // x1 - перед минусом
                    'IF(tItem.name REGEXP " '   . implode('\-" , 1, 0) + IF(tItem.name REGEXP " ',  $noTrimWords) . '\-", 1, 0)',   // x1 - слева пробел, справа минус

                    'IF(tItem.name REGEXP "\-'  . implode('" , 1, 0)   + IF(tItem.name REGEXP "\-', $noTrimWords) . '", 1, 0)',     // x1 - после минусом
                    'IF(tItem.name REGEXP "\-'  . implode(' " , 1, 0)  + IF(tItem.name REGEXP "\-', $noTrimWords) . ' ", 1, 0)',    // x1 - слева пробел, справа минус

                    // прочие лайки...
                    'IF(tItem.name LIKE "% '    . implode(' %", 1, 0)  + IF(tItem.name LIKE "% ',   $noTrimWords) . ' %", 1, 0)',   // x1 - % слово %
                    'IF(tItem.name LIKE "% '    . implode('%" , 1, 0)  + IF(tItem.name LIKE "%',    $noTrimWords) . '%", 1, 0)',    // x1 - % слово%
                    'IF(tItem.name LIKE "%'     . implode(' %", 1, 0)  + IF(tItem.name LIKE "%',    $noTrimWords) . ' %", 1, 0)',   // x1 - %слово %
                    'IF(tItem.name LIKE "'      . implode(' %", 1, 0)  + IF(tItem.name LIKE "',     $noTrimWords) . ' %", 1, 0)',   // x1 - слово %
                    'IF(tItem.name LIKE "'      . implode('%", 1, 0)   + IF(tItem.name LIKE "',     $noTrimWords) . '%", 1, 0)',    // x1 - слово%
                    'IF(tItem.name LIKE "%'     . implode('%", 1, 0)   + IF(tItem.name LIKE "%',    $noTrimWords) . '%", 1, 0)',    // x1 - %слово%
                )) . ') AS namematch',
                'tItem.name'
            ))
            ->from('(' . $allSelect . ') AS tWrap')
            ->leftJoin(ZOO_TABLE_ITEM . ' AS tItem ON tWrap.id = tItem.id')
            ->leftJoin(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategoryItem ON tWrap.id = tCategoryItem.item_id')
            ->where('tCategoryItem.category_id > ?', 0)
            ->where('tWrap.count >= ?', count($words) - 1)
            //->having('namematch > ?', 0)
            ->order('namematch DESC')
            ->order('tWrap.count DESC')
            ->order('tItem.name')
        ;

        //jbdump::sql($main);

        if ($curCat) {
            $catList = JBModelCategory::model()->getNestedCategories($curCat);
            $catList[] = $curCat;
            $catList = array_filter($catList);
            if (!empty($catList)) {
                $main->where('tCategoryItem.category_id IN (' . implode(',', $catList) . ')');
            }
        }

        return $this->fetchAll($main);
    }

    /**
     * @return JBDatabaseQuery
     */
    private function _getItemSubSelect()
    {
        $select = $this->_getSelect()
            ->select('DISTINCT tItem.id')
            ->from(ZOO_TABLE_ITEM, 'tItem')
            ->where('tItem.' . $this->app->user->getDBAccessString())
            ->where('tItem.state = ?', 1)
            ->where('(tItem.publish_up = ' . $this->_dbNull . ' OR tItem.publish_up <= ' . $this->_dbNow . ')')
            ->where('(tItem.publish_down = ' . $this->_dbNull . ' OR tItem.publish_down >= ' . $this->_dbNow . ')')
            ->limit(self::LIMIT_ITEMS);

        return $select;
    }

    /**
     * @return string
     */
    protected function _getAccessExclude()
    {
        $elements = array();

        /** @var Application $application */
        foreach ($this->app->application->groups() as $application) {

            /** @var Type $type */
            foreach ($application->getTypes() as $type) {

                /** @var Element $element */
                foreach ($type->getElements() as $element) {
                    if (!$element->canAccess()) {
                        $elements[] = $this->_db->Quote($element->identifier);
                    }
                }
            }
        }

        $access = $elements ? 'NOT element_id in (' . implode(',', $elements) . ')' : '1';

        return $access;
    }

    /**
     * @param $idList
     * @return array
     */
    public function createItems($idList)
    {
        $items = $this->getZooItemsByIds($idList);
        $items = $this->app->jbarray->sortByArray($items, $idList);
        return $items;
    }


    /**
     * @param $word
     * @return mixed
     */
    protected function _dropBackWords($word)
    {
        $reg  = "/(ый|ой|ая|ое|ые|ому|а|о|у|ого|ему|ство|ых|ох|ия|ий|ь|я|он|ют|ат|ы)$/iu";
        $word = preg_replace($reg, '', $word);
        return $word;
    }

    /**
     * @param $query
     * @return mixed
     */
    protected function _stopWords($query)
    {
        $reg   = "/\s(под|много|что|когда|где|или|поэтому|все|будем|как)\s/iu";
        $query = preg_replace($reg, ' ', ' ' . $query . ' ');
        $query = JString::trim($query);
        return $query;
    }

    /**
     * @param $query
     * @return array
     */
    protected function _morphTrimmer($query, $isEndTrim = true)
    {
        $query = str_replace(array( '/', '\\', ':', '_', ','), ' ', $query);
        $words = $this->_stopWords($query);
        $words = explode(" ", $this->_stopWords($query));

        $keywords = array();
        foreach ($words as $word) {

            $word = JString::strtoupper(JString::trim($word));

            if (JString::strlen($word) < 1) {
                continue;

            } else {
                if ($isEndTrim) {
                    $keywords[] = $this->_dropBackWords($word);
                } else {
                    $keywords[] = $word;
                }
            }
        }

        return $keywords;
    }

}