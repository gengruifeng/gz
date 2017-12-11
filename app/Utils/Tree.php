<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/27
 * Time: 9:51
 */
namespace App\Utils;

class Tree
{
    /**
     * 递归
     *
     * @param number $root_id
     *        	开始ID
     * @param array $lists
     *        	需要递归的数据
     * @param string $loop_id
     *        	ID
     * @return NULL Ambigous multitype:array, multitype:array >
     */
    public static function menu_tree($root_id, $lists = array(), $field = 'id', $parentid = 'pid') {
        $childs = self::find_child ( $lists, $root_id, $parentid );
        if (empty ( $childs )) {
            return null;
        }
        foreach ( $childs as $k => $v ) {
            $rescurTree = self::menu_tree ( $v [$field], $lists, $field, $parentid );
            if (null != $rescurTree) {
                $childs [$k] ['childs'] = $rescurTree;
            }
        }
        return $childs;
    }

    /**
     * 查询子类
     *
     * @param array $arr
     * @param number $id
     * @return multitype:array
     */
    public static function find_child($arr, $id, $parentid = 'parentid') {
        $childs = array ();
        if (! empty ( $arr )) {
            foreach ( $arr as $k => $v ) {
                if ($v [$parentid] == $id) {
                    $childs [] = $v;
                }
            }
        }
        return $childs;
    }

    /**
     * 获取菜单树
     *
     * @param unknown $menu
     */
    public static function get_menu_tree($menu = array(), $selected = array(), $field = 'menuid') {
        foreach ( $menu as $key => $value ) {
            echo '<li id="' . $value [$field] . '" ' . (! empty ( $selected ) && (in_array ( $value ['pin'], $selected ) || in_array ( $value [$field], $selected )) ? 'class="selected"' : "") . '>' . $value ['title'];
            if (isset ( $value ['childs'] ) && is_array ( $value ['childs'] )) {
                echo '<ul>';
                self::get_menu_tree ( $value ['childs'], $selected, $field );
                echo '</ul></li>';
            } else {
                echo '</li>';
            }
        }
    }
}