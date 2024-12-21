<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use YOOtheme\Builder\Joomla\Zoo\StrHelper;
use YOOtheme\Builder\Source;
use Joomla\CMS\Language\Text;

class CategoryType
{
    /**
     * @param Source       $source
     * @param \Application $application
     * @param string       $type
     *
     * @return array
     */
    public static function config(Source $source, $application, $type)
    {
        $fields = [

            'name' => [
                'type' => 'String',
                'metadata' => [
                    'label' => Text::_('Name'),
                    'filters' => ['limit'],
                ],
            ],

            'description' => [
                'type' => 'String',
                'metadata' => [
                    'label' => Text::_('Description'),
                    'filters' => ['limit'],
                ],
            ],

            'children' => [
                'type' => [
                    'listOf' => $type,
                ],
                'metadata' => [
                    'label' => Text::_('Child Categories'),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::children',
                ],
            ],

            'parent' => [
                'type' => $type,
                'metadata' => [
                    'label' => Text::_('Parent Category'),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::parent',
                ],
            ],

            'link' => [
                'type' => 'String',
                'metadata' => [
                    'label' => Text::_('Link'),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::link',
                ],
            ],

            'itemCount' => [
                'type' => 'String',
                'metadata' => [
                    'label' => Text::_('Item Count'),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::itemCount',
                ],
            ],

            'totalItemCount' => [
                'type' => 'String',
                'metadata' => [
                    'label' => Text::_('Total Item Count'),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::totalItemCount',
                ],
            ],

        ];

        $content = ParamsContentType::config($application->getParamsForm()->getXML('category-content'), $application);

        if (!empty($content['fields'])) {

            $fields['content'] = [
                'type' => "{$type}Content",
                'metadata' => [
                    'label' => Text::_('Content'),
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::content',
                ],
            ];

            $source->objectType("{$type}Content", $content);
        }

        $metadata = [
            'type' => true,
            'label' => Text::_('Category'),
        ];

        return compact('fields', 'metadata');
    }

    public static function children(\Category $category)
    {
        if ($category->hasChildren()) {
            return $category->getChildren();
        }

        return $category->app->table->category->all([
            'conditions' => ['parent = ? AND application_id = ? AND published = 1', $category->id, $category->application_id],
        ]);
    }

    public static function parent(\Category $category)
    {
        if ($category->parent == 0) {
            return;
        }

        return $category->app->table->category->first([
            'conditions' => ['id = ? AND published = 1', $category->parent],
        ]);
    }

    public static function link(\Category $category)
    {
        return $category->app->route->category($category);
    }

    public static function itemCount(\Category $category)
    {
        return $category->itemCount() ?: $category->app->table->item->getItemCountFromCategory($category->application_id, $category->id, true);
    }

    public static function totalItemCount(\Category $category)
    {
        $categories = $category->getApplication()->getCategoryTree(true, $category->app->user->get(), true);

        if (!empty($categories[$category->id])) {
            return $categories[$category->id]->totalItemCount();
        }
    }

    public static function content(\Category $category)
    {
        $result = [];
        $content = $category->getParams('site')->get('content.', []);

        foreach ($content as $key => $value) {
            $result[StrHelper::toFieldName($key)] = $value;
        }

        return $result;
    }
}
