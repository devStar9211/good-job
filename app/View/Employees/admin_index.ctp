<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/css/g_css.css'
	]);
	$this->end();

	$this->start('script');
	echo $this->Html->script([
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_back.js'
	]);
	$this->end();
?>
<div class="revenue-budget-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo __('従業員一覧') ?></h3>

			<div class="pull-right box-tools">
				<div class="btn-group">
					<?php
						echo $this->Html->link(__('新規追加'), array('controller' => 'Employees', 'action' => 'admin_add'), array('class' => 'btn btn-primary', 'onclick' => 'check_employee_add_url(event,\'#employee-list-form\')'));
						echo $this->Html->link(__('CSVアップロード'), array('controller' => 'Employees', 'action' => 'admin_csv_import'), array('class' => 'btn btn-primary'));
						echo $this->Html->link(__('CSVダウンロード'), array('controller' => 'Employees', 'action' => 'export'), array('class' => 'btn btn-primary'));
					?>
				</div>
			</div>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('Employee', array(
					'url' => array(
						'controller' => 'Employees',
						'action' => 'admin_index'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'id' => 'employee-list-form',
					'data-source' => $this->Html->url(array('controller' => 'Employees', 'action' => 'admin_generate_list_employee'))
				));
			?>
			<?php echo $this->element('flash-message') ?>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'filter_employee(this)', 'data-office-node' => '#office_id', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id')) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('office', $offices, array('empty' => __('事業所'), 'default' => (isset($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'id' => 'office_id', 'role' => 'office_id', 'onchange' => 'filter_employee(this)')) ?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->end() ?>
			<div class="table-wrap clearfix mh55" role="tb-wrap">
				<div class="loader"><div class="text-loader">Loading</div></div>
				<div class="clearfix">
					<?php
						$option = array();
						$option['title'] = 'Office list';
						$option['col'] = array(
							array(
								'key_tab' => 'id',
								'title_tab' => __('#'),
								'option_tab' => 'sort',
								'style' => 'width: 40px;'
							),
							array(
								'key_tab' => 'username',
								'title_tab' => __('username'),
								'option_tab' => 'sort'
							),
							array(
								'key_tab' => 'name',
								'title_tab' => __('name'),
								'option_tab' => 'sort'
							),
							array(
								'key_tab' => 'office',
								'title_tab' => __('office'),
								'option_tab' => 'sort'
							),
							array(
								'key_tab' => 'company',
								'title_tab' => __('company'),
								'option_tab' => 'sort'
							),
							array(
								'key_tab' => '',
								'title_tab' => __('Action'),
								'option_tab' => ''
							),
						);
					?>

					<?php echo $this->grid->create($employees, null, $option); ?>

					<?php foreach ($employees as $employee): ?>
						<tr>

							<td><?php echo h($employee['Employee']['id']) ?></td>
							<td><?php echo h($employee['Account']['username']) ?></td>
							<td>
								<?php
									echo $this->Html->link(h($employee['Employee']['name']), array('controller' => 'Employees', 'action' => 'admin_edit', $employee['Employee']['id']), array());
								?>
							</td>
							<td><?php echo !empty($employee['Office']) ? h($employee['Office']['name']) : '' ?></td>
							<td><?php echo !empty($employee['Company']) ? h($employee['Company']['name']) : '' ?></td>
							<td class="actions">
								<?php
									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
										array(
											'controller' => 'Employees',
											'action' => 'admin_edit',
											$employee['Employee']['id']
										),
										array(
											'escape' => false,
											'class' => 'btn btn-success btn-sm',
											'title' => __('Edit')
										)
									);
								?>
								&nbsp;
								<?php
									echo $this->Form->button(
										$this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white')),
										array(
											'escape' => false,
											'class' => 'btn btn-danger btn-sm btn-cat-cancel',
											'title' => __('Delete'),
											'onclick' => ('need_confirm(
												\'redirect\',
												\'danger\',
												\''.__('do you want to delete this employee?').'\',
												\''.$this->Html->url(
													array(
														'controller' => 'Employees',
														'action' => 'admin_delete',
														$employee['Employee']['id'],
														'?' => array(
															'company' => isset($data['company_id']) ? $data['company_id'] : '',
															'office' => isset($data['office_id']) ? $data['office_id'] : '',
															'sort' => isset($_GET['sort']) ? $_GET['sort'] : '',
															'direction' => isset($_GET['direction']) ? $_GET['direction'] : '',
															'page' => isset($_GET['page']) ? $_GET['page'] : '',
														)
													)
												).'\'
											)')
										)
									);
								?>
							</td>
						</tr>
					<?php endforeach ?>
							
					<?php echo $this->grid->end_table($employees, null, $option); ?>
				</div>
			</div>
		</div>
	</div>
</div>