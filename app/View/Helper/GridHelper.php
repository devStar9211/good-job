<?php
App::uses('FormHelper', 'View/Helper', 'PaginatorHelper');

class GridHelper extends AppHelper
{
    public $helpers = array('Paginator', 'Html', 'Form');

    public function create($data, $model = null, $options = array())
    {
        $defaultOptions = array(
            'title' => 'Products',
            'action' => 'Actions'
        );

        $options = array_merge($defaultOptions, $options);

        $str = '';
        $str .= '<div class="row products form"><div class="datagrid dataTables_paginate paging_bootstrap col-xs-12">';
        if ($this->Paginator->counter('{:pages}') > 1) {
            $str .= '<ul class="pagination">';
            if ($this->Paginator->hasPrev()) {
                $str .= $this->Paginator->prev('プレビュー' . __(''), array('tag' => 'li'), null, array('class' => 'prev disabled'));
            }
            $str .= $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
            if ($this->Paginator->hasNext()) {
                $str .= $this->Paginator->next(__('') . '次へ', array('tag' => 'li'));
            }
            $str .= '</ul>';
        }
        $str .= '</div><div class="col-xs-12 col-sm-4 col-sm-offset-4 row-space pull-right">';
        $str .= $this->Paginator->counter(array(
            'format' => __('<div id="rows_info_pag_demo_grid1" class="pull-right margin-bottom10">{:count}件中{:start}-{:end}件表示(ページ{:page}/{:pages})</div>')
        ));
        $str .= ' </div></div>';
        $str .= '<div class="row"><div class="col-xs-12">
                    <div class="table-responsive"><table  class="table table-bordered table-striped dataTable table-hover "><thead><tr>';
        foreach ($options['col'] as $key => $col) {
            $style='';
            if (isset($col['style']) &&  !is_null($col['style'])) {
                $style = 'style="'.$col['style'].'"';
            }
            if (!is_null($col['key_tab'])) {
                if ($col['option_tab'] == 'sort') {
                    $str .= '<th '. $style .'>' . $this->Paginator->sort($col['key_tab'], $col['title_tab']) . '</th>';
                } else {
                    $str .= '<th '. $style .' class="actions">' . $col['title_tab'] . '</th>';
                }

            } else {
                $str .= '<th '. $style .' class="actions">' . $col['title_tab'] . '</th>';
            }

        }
        $str .= '</tr></thead><tbody>';

        return $str;
    } /* create */
    public function end_table($data, $model = null, $options = array())
    {
        $str = '';
        $str .= '</tbody><tfoot><tr>';
        foreach ($options['col'] as $key => $col) {
            if (!is_null($col['key_tab'])) {
                if ($col['option_tab'] == 'sort') {
                    $str .= '<th>' . $this->Paginator->sort($col['key_tab'], $col['title_tab']) . '</th>';
                } else {
                    $str .= '<th class="">' . $col['title_tab'] . '</th>';
                }

            } else {
                $str .= '<th class="actions">' . $col['title_tab'] . '</th>';
            }

        }
        $str .= '</tr></tfoot>';
        $str .= '</table></div></div></div>';
        $str .= '<div class=" row products form">';
        if ($this->Paginator->counter('{:pages}') > 1) {
            $str .= '<div class="datagrid dataTables_paginate paging_bootstrap col-xs-12"><ul class="pagination">';
            if ($this->Paginator->hasPrev()) {
                $str .= $this->Paginator->prev('プレビュー' . __(''), array('tag' => 'li'), null, array('class' => 'prev disabled'));
            }
            $str .= $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
            if ($this->Paginator->hasNext()) {
                $str .= $this->Paginator->next(__('') . '次へ', array('tag' => 'li'));
            }
            $str .= '</ul></div>';
        }
        $str .= '<div class="col-xs-12 col-sm-4 col-sm-offset-4 row-space pull-right">';
        $str .= $this->Paginator->counter(array(
            'format' => __('<div id="rows_info_pag_demo_grid1" class="pull-right margin-bottom10">{:count}件中{:start}-{:end}件表示(ページ{:page}/{:pages})</div>')
        ));
        $str .= ' </div></div>';
        return $str;
    }
}

?>
