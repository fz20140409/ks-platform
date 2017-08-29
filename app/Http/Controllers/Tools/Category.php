<?php

namespace App\Http\Controllers\Tools;


class Category
{
    //一维 array
    static function toLevel($cates, $pid = 0, $delimiter = '--', $level = 0)
    {
        $data = array();
        foreach ($cates as $cate) {
            if ($cate['pid'] == $pid) {
                $cate['level'] = $level + 1;
                $cate['delimiter'] = str_repeat($delimiter, $level * 2);
                $data[] = $cate;
                $data = array_merge($data, self::toLevel($cates, $cate['id'], $delimiter, $cate['level']));
            }

        }
        return $data;
    }

    //组成多维数组
    static function toLayer($cates, $name = 'child', $pid = 0)
    {

        $arr = array();
        foreach ($cates as $cate) {
            if ($cate['pid'] == $pid) {
                $cate[$name] = self::toLayer($cates, $name, $cate['id']);
                $arr[] = $cate;
            }
        }

        return $arr;
    }

    static function proMenu($layer, $name = 'child')
    {
        $html = '';
        $url = '';
        foreach ($layer as $t) {
            if (!empty($t['url'])) {
                //todo
                //正则校验
                list($fun, $route, $params) = explode('#', $t['url']);
                if (!empty($params)) {
                    $params = json_decode($params, true);
                }
                switch ($fun) {
                    case 'url':
                        !empty($params) ? $url = url($route, $params) : $url = url($route);

                        break;
                    case 'route':
                        !empty($params) ? $url = route($route, $params) : $url = route($route);
                        break;
                    case 'action':
                        !empty($params) ? $url = action($route, $params) : $url = action($route);
                        break;
                }
            } else {
                $url = '';
            }
            //没有子菜单
            if (empty($t[$name])) {
                if($t['icon']){
                    $html .= ' <li><a href="' . $url . '"><i class="'.$t['icon'].'"></i> <span>' . $t['display_name'] . '</span></a></li>';
                }else{
                    $html .= ' <li><a href="' . $url . '"><i class="fa fa-circle-o text-red"></i> <span>' . $t['display_name'] . '</span></a></li>';
                }

            } else {
                //子菜单
                $html .= '<li class="treeview"><a href="' . $url . '"><i class="'.$t['icon'].'"></i> <span>' . $t['display_name'] . '</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">';
                $html .= self::proMenu($t[$name], $name);
                $html .= '</ul></li>';
            }
        }
        return $html;
    }

    // 子集找父集【一维数组】
    static function getParents ($data, $childId, $is_processed = false) {
        $tmp = array();
        if (!$is_processed) {
            $data = array_column($data,null, 'id');
        }
        $parentId = $data[$childId]['pid'];
        if (isset($data[$parentId])) {
            array_unshift($tmp, $data[$parentId]);
            $tmp = array_merge(self::getParents($data, $parentId, true), $tmp);
        }

        return $tmp;
    }

}
