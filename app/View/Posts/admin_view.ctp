<?php $this->start('css')?>
<?php echo $this->Html->css('post'); ?>
<?php $this->end()?>
<div class="posts-view">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title"><?php echo h($post['Post']['title']); ?></h3>

				   <div class="box-tools pull-right">
				  		<div class="Preview-date"><?php echo h($post['Post']['created']); ?></div>
				   </div>
				</div>
				<div class="box-body">
					<div class="post-content">
						<div class="short_description">
							<?php echo h($post['Post']['short_description']); ?>&nbsp;
						</div> 
						<div class="post-description">
							<?php echo $post['Post']['description']; ?>&nbsp;
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
