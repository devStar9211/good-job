<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo __('Quyền xem')?></h3>
	</div>
	
	<div class="box-body" id="table-list">
		<div class="form">
			<div class="permission-paginator">
				<?php //$this->element('paginator')?>
			</div>
			<?php echo $this->Form->create('Perms'); ?>
			<div class="col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped table-hover  table-bordered  dataTable ">
						<thead>
							<tr>
								<th><?php echo __('Action') ?></th>
								<?php foreach ($aros as $aro): ?>
								<?php $aro = array_shift($aro); ?>
									<?php //pr($aro);?>

									<?php $adminPermissionArr = Configure::check('AdminPermission') ? Configure::read("AdminPermission") : array(); ?>
									<?php if (!in_array($aro['id'], $adminPermissionArr)): ?>
										<th><?php echo h($aro[$aroDisplayField]); ?></th>
									<?php endif ?>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
						<?php
						$uglyIdent = Configure::read('AclManager.uglyIdent'); 
						$lastIdent = null;
						foreach ($acos as $id => $aco) {
							$action = $aco['Action'];
							if ($action != 'get_daily_data' && $action != 'get_budget_ranking_data' && $action != 'loadPost' && $action != 'isAuthorized' && $action != 'get_data_prize' && $action != 'post_view' && $action != 'get_last_year_comparison_data') {

								$alias = $aco['Aco']['alias'];
		
								switch ($alias) {
									case 'index':
										$alias = __('Home');
										break;
									case 'daily_settlement':
										$alias = __('Quyết toán hàng ngày');
										break;
									case 'budget_ranking':
										$alias = __('Ranking dự toán đạt được');
										break;
									case 'budget_sale':
										$alias = __('Ranking so với năm ngoái');
										break;
									case 'user_page':
										$alias = __('My page');
										break;
									case 'list_post':
										$alias = __('Post');
										break;
								}
								$ident = substr_count($action, '/');
								if ($ident <= $lastIdent && !is_null($lastIdent)) {
									for ($i = 0; $i <= ($lastIdent - $ident); $i++) {
										?></tr><?php
									}
								}
								if ($ident != $lastIdent) { ?>
									<tr class='aclmanager-ident-<?php echo $ident; ?>'>
								<?php
								}
								?>
									<td>
										<?php echo ($ident == 1 ? "<strong>" : "" ) . ($uglyIdent ? str_repeat("&nbsp;&nbsp;", $ident) : "") . h($alias) . ($ident == 1 ? "</strong>" : "" ); ?>
									</td>
								<?php foreach ($aros as $aro):
									if (!in_array($aro['GroupPermission']['id'], $adminPermissionArr)){
										$inherit = $this->Form->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}-allow");
										$allowed = $this->Form->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}"); 
										$value = $allowed ? 'allow' : 'deny'; 
	
										$icon = $this->Html->image(($allowed ? 'test-pass-icon.png' : 'test-fail-icon.png'));
										?>
										<td>
											<?php echo $icon . " " . $this->Form->select("Perms." . 'controllers:Frontend:'.str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}", array(array(__('allow') => __('Allow'), 'deny' => __('Deny'))), array('empty' => false,'value' => $value)); ?>
										</td>
										<?php
										}
									 ?>
								<?php endforeach; ?>
							<?php 
								$lastIdent = $ident;
								
							}
							for ($i = 0; $i <= $lastIdent; $i++) {
								?>
								</tr>
							<?php
							}
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="permission-submit" style="text-align: center">
				<?php echo $this->Form->submit(__('Save'));?>
			</div>
			<?php echo $this->Form->end(); ?>
			<div class="permission-paginator">
				<?php //echo $this->element('sql_dump')?>
			</div>
		</div>
	</div>
</div>