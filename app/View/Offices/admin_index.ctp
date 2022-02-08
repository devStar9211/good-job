<div class="box office-list">
    <div class="nav-tabs-custom">
        <div class="container-fluid">
            <section class="content-header">
                <div class="row">
                    <div class="col-xs-12 no-padding clearfix">
                        <legend>
                            <?php echo $title_for_layout; ?>
                            <div class="pull-right hidden"><a id="search-button" data-toggle="collapse"
                                                              href="#search-box"
                                                              aria-expanded="false"
                                                              class="collapsed slide-button btn btn-default"><span
                                            class="glyphicon-collapse glyphicon glyphicon-chevron-down"
                                            aria-hidden="true"></span></a></div>
                            <div class="btn-group pull-right">
                                <!--register user-->
                                <?php echo $this->Html->link(__('Thêm office'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
                            </div>
                        </legend>
                    </div>
                </div>
            </section>
            <?php echo $this->element('flash-message'); ?>
            <div class="tab-content no-padding">
                <div class="row">
                    <div class="col-sx-12 panel-collapse  <?php echo empty($is_search) ? '' : 'in' ?>"
                         id="search-box" aria-expanded="<?php echo empty($is_search) ? 'false' : 'true' ?>"
                         style="<?php echo empty($is_search) ? 'style: 0px' : '' ?>">
                        <form name="form_site" method="get" id="form_site" action="" class="container-fluid">

                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <div class="row">
                                        <?php
                                        echo $this->Form->input("company_id", array('name' => 'company_id', 'div' => 'pull-left col-lg-10 col-md-10', "type" => "select", 'class' => 'form-control select2', "id" => "company_id", "options" => $companies, 'required' => false, 'label' => false, 'default' => $company_default, 'onchange'=>'$(this).closest(\'form\').submit();'));
                                        ?>
                                         
                                    </div>
                                </div>

                                <div class="col-xs-6 col-sm-6">
                                    <div class="search-box pull-right" style="width: 250px;">
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="<?php echo __('Office Name') ?>"
                                                   class="form-control"
                                                   autofocus="true"
                                                   value="<?php echo $search_name_default ?>"
                                            >
                                            <span class="input-group-btn">
						                        <button class="btn btn-default" type="submit"><span
                                                            class="glyphicon glyphicon-search"></span></button>
						                    </span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <?php
                $option = array();
                $option['title'] = 'Office list';
                $option['col'] = array(

                    0 => array('key_tab' => 'id', 'title_tab' => __('#'), 'option_tab' => 'sort', 'style' => 'width: 40px;'),
                    1 => array('key_tab' => 'name', 'title_tab' => __('Office name'), 'option_tab' => 'sort'),
                    2 => array('key_tab' => 'company_id', 'title_tab' => __('Company'), 'option_tab' => ''),
                    3 => array('key_tab' => 'division_id', 'title_tab' => __('Division'), 'option_tab' => ''),
                    4 => array('key_tab' => 'office_group', 'title_tab' => __('Office Group'), 'option_tab' => ''),
                    5 => array('key_tab' => '', 'title_tab' => __('Action'), 'option_tab' => ''),
                );
                echo $this->grid->create($accounts, null, $option);
                ?>
                <?php foreach ($accounts as $account): ?>
                    <tr>

                        <td><?php echo h($account['Office']['id']); ?>&nbsp;</td>
                        <td>

                            <?php echo $this->Html->link($account['Office']['name'], array('action' => 'edit', $account['Office']['id']), array('escape' => false)); ?>


                        </td>
                        <td><?php echo h($account['Company']['name']); ?>&nbsp;</td>
                        <td><?php echo h($account['Division']['name']); ?>&nbsp;</td>
                        <td><?php echo h($account['OfficeGroup']['name']); ?>&nbsp;</td>
                        <td class="actions">
                            <?php echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
                                array('action' => 'edit', $account['Office']['id']),
                                array('escape' => false, 'class' => 'btn btn-success btn-sm', 'title' => 'Edit')

                            );
                            ?>
                            <a href="#" class="btn btn-danger btn-sm btn-cat-cancel" title="Delete"
                               onclick="check_delete('<?php echo $this->webroot . 'admin/offices/delete/' . $account['Office']['id'] ?>', event)"><i
                                        class="glyphicon glyphicon-remove icon-white"></i></a>

                        </td>
                    </tr>
                <?php endforeach;
                echo $this->grid->end_table($accounts, null, $option);
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->start('css');
echo $this->Html->css([

    '/assets/css/v_css.css',
    '/assets/css/g_css.css',
]);
$this->end();

$this->start('script');
echo $this->Html->script([
    // Time picker
    '/assets/js/v_script/v_script.js'
]);
$this->end();
?>