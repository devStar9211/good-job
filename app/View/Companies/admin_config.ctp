<?php $grid = Configure::read('Grid'); ?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
	</div>
	<div class="box-body">
		<?php echo $this->Form->create('Config', array('id' => 'config-validate')); ?>
		<?php echo $this->element('flash-message'); ?>
		<?php
		?>
		<div class="row">
			<?php
			// $companies pass from Company Controller
			foreach ($companieGroups as $id => $name): ?>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label class="company-name" style="font-size: 20px"><?php echo $name; ?></label>

					<select id="c-<?php echo $id; ?>" name="Config[<?php echo $id; ?>][]" class="form-control select2" multiple="multiple" data-placeholder="<?php __('列を選択');?>" style="width: 100%;">
						<?php foreach ($grid as $key => $cl): ?>
							<option value="<?php echo $key;?>"<?php echo isset($gridConfig[$id]) && in_array($key, $gridConfig[$id]) ? ' selected' : '' ; ?>><?php echo __($cl); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<?php endforeach; ?>
			<div class="col-xs-12">
				<?php echo $this->Form->submit(__('Submit'), array('after' => ''));?>
			</div>

		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<?php $this->start('css')?>
<?php echo $this->Html->css('/assets/css/g_css');?>
<?php $this->end()?>