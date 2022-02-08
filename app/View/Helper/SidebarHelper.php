<?php

class SidebarHelper extends AppHelper {
    public $helpers = array('Html');

    public function generate_main_nav(){
        $controller = strtolower(str_replace('_', '', $this->params['controller']));
        $action = strtolower(str_replace('_', '', $this->params['action']));
        $menu = Configure::read('admin_menu') ? Configure::read('admin_menu') : array();
        $blankUrl = '#';
        $lis = '';

        foreach ($menu as $key => $item) {
            if($item['is_display']) {
                $active = '';

                $pUrl = $blankUrl;
                if(!empty($item['url'])) {
                    $pUrl = strtolower($this->Html->url($item['url']));
                    $pController = strtolower(
                        str_replace('_', '',
                            !empty($item['url']['controller'])
                                ? $item['url']['controller']
                                : '#'
                        ));
                    $pAction = strtolower(
                        str_replace('_', '',
                            !empty($item['url']['action'])
                                ? $item['url']['action']
                                : '#'
                        ));
                }

                if(
                    $pUrl != $blankUrl
                    && $controller == $pController
                    && $action == $pAction
                ) { $active = 'active'; }
                if(empty($active) && !empty($item['modules_active'])) {
                    foreach ($item['modules_active'] as $k => $v) {
                        if(
                            $controller == strtolower(
                                str_replace('_', '',
                                    !empty($v['controller'])
                                        ? $v['controller']
                                        : '#'
                                ))
                            && $action == strtolower(
                                str_replace('_', '',
                                    !empty($v['action'])
                                        ? $v['action']
                                        : '#'
                                ))
                        ) {
                            $active = 'active';
                            break;
                        }
                    }
                }

                $ch = '';
                if(!empty($item['childs'])) {
                    foreach ($item['childs'] as $k => $child) {
                        if($child['is_display']) {
                            $childActive = false;

                            $cUrl = $blankUrl;
                            if(!empty($child['url'])) {
                                $cUrl = strtolower($this->Html->url($child['url']));
                                $cController = strtolower(
                                    str_replace('_', '',
                                        !empty($child['url']['controller'])
                                            ? $child['url']['controller']
                                            : '#'
                                    ));
                                $cAction = strtolower(
                                    str_replace('_', '',
                                        !empty($child['url']['action'])
                                            ? $child['url']['action']
                                            : '#'
                                    ));
                            }

                            if(
                                $cUrl != $blankUrl
                                && $controller == $cController
                                && $action == $cAction
                            ) {
                                $childActive = true;
                                if(empty($active)) { $active = 'active'; }
                            } else if(!empty($child['modules_active'])) {
                                foreach ($child['modules_active'] as $z => $v) {
                                    if(
                                        $controller == strtolower(
                                            str_replace('_', '',
                                                !empty($v['controller'])
                                                    ? $v['controller']
                                                    : '#'
                                            ))
                                        && $action == strtolower(
                                            str_replace('_', '',
                                                !empty($v['action'])
                                                    ? $v['action']
                                                    : '#'
                                            ))
                                    ) {
                                        $childActive = true;
                                        if(empty($active)) { $active = 'active'; }
                                        break;
                                    }
                                }
                            }
                            if($childActive) {
                                $ch .= '
                                    <li class="active">
                                        <a href="'. $cUrl .'">
                                            <i class="fa '. $child['icon'] .'"></i>
                                            '. $child['title'] .'
                                        </a>
                                    </li>
                                ';
                            } else {
                                $ch .= '
                                    <li>
                                        <a href="'. $cUrl .'">
                                            <i class="fa '. $child['icon'] .'"></i>
                                            <span>'. $child['title'] .'</span>
                                        </a>
                                    </li>
                                ';
                            }
                        }
                    }
                }
                $lis .= '<li class="treeview '. $active .'">';
                $lis .= '
                    <a href="'. $pUrl .'">
                        <i class="fa ' . $item['icon'] . '"></i>
                        <span>' . $item['title'] . '</span>
                        '. (!empty($ch) ? '<i class="fa fa-angle-left pull-right"></i>' : '') .'
                    </a>
                ';
                if(!empty($ch)) {
                    $lis .= '
                        <ul class="treeview-menu">
                            '. $ch .'
                        </ul>
                    ';
                }
                $lis .= '</li>';
            }

        }

        return $lis;
    }

    public function frontend_main_nav(){
        $controller = $this->params['controller'];
        $action = $this->params['action'];
        $url = strtolower($this->Html->url(array('controller' => $controller, 'action' => $action)));
        $menu = Configure::read('frontend_menu') ? Configure::read('frontend_menu') : array();
        $blankUrl = '#';
        $lis = '';

        foreach ($menu as $key => $item) {
            if($item['is_display']) {
                $active = '';

                $pUrl = $blankUrl;
                if(!empty($item['url'])) {
                    $pUrl = strtolower($this->Html->url($item['url']));
                }

                if($url == $pUrl) { $active = 'active'; }

                if(empty($active) && !empty($item['modules_active'])) {
                    foreach ($item['modules_active'] as $k => $v) {
                        if($url == strtolower($this->Html->url($v))) {
                            $active = 'active';
                            break;
                        }
                    }
                }

                $ch = '';
                if(!empty($item['childs'])) {
                    foreach ($item['childs'] as $k => $child) {
                        if($child['is_display']) {
                            $childActive = false;
                            $cUrl = $blankUrl;

                            if(!empty($child['url'])) {
                                $cUrl = strtolower($this->Html->url($child['url']));
                            }

                            if($url == $cUrl) {
                                $childActive = true;
                                if(empty($active)) { $active = 'active'; }
                            } else if(!empty($child['modules_active'])) {
                                foreach ($child['modules_active'] as $z => $v) {
                                    if($url == strtolower($this->Html->url($v))) {
                                        $childActive = true;
                                        if(empty($active)) { $active = 'active'; }
                                        break;
                                    }
                                }
                            }

                            if($childActive) {
                                $ch .= '
                                    <li class="active">
                                        <a href="'. $cUrl .'">
                                            '. $child['title'] .'
                                        </a>
                                    </li>
                                ';
                            } else {
                                $ch .= '
                                    <li>
                                        <a href="'. $cUrl .'">
                                            '. $child['title'] .'
                                        </a>
                                    </li>
                                ';
                            }
                        }
                    }
                }

                $lis .= '<li class="treeview '. $active .'">';
                $lis .= '
                    <a href="'. $pUrl .'">
                        ' . $item['title'] . '
                        '. (!empty($ch) ? '<i class="fa fa-angle-left pull-right"></i>' : '') .'
                    </a>
                ';
                if(!empty($ch)) {
                    $lis .= '
                        <ul class="treeview-menu">
                            '. $ch .'
                        </ul>
                    ';
                }
                $lis .= '</li>';
            }
        }

        return $lis;
    }
}