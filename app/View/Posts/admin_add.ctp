<?php $this->start('css')?>
	<?php echo $this->Html->css('post');?>
<?php $this->end()?>
<div class="post-admin-add form">
	<div class="box">
		<div class="box-header with-border">
		  <h3 class="box-title"><?=$title_for_layout?></h3>
		</div>
		<div class="box-body">
			<?php echo $this->element('flash-message'); ?>
			<?php echo $this->Form->create('Post', array('id' => 'post-validate', 'enctype'=>"multipart/form-data", 'inputDefaults' => array('label' => false, 'div'=>false))); ?>
			<div class="col-md-9">
				<div class="form-group">
					<label for="">タイトル</label>
					<?= $this->Form->input('title', array('class'=>"form-control confirmLeavePage", "maxlength"=>"100", 'id'=>"PostTitle", "required"=>"required"));?>
				</div>

				<div class="form-group">
					<label for="">短い説明</label>
					<?= $this->Form->input('short_description', array('class'=>"form-control confirmLeavePage", "maxlength"=>"255", 'id'=>"PostShortDescription", "required"=>"required", 'type'=>'textarea', "cols"=>"30", "rows"=>"6"));?>
				</div>

				<div class="form-group">
					<label for=""><?php echo $this->element('media'); ?></label>
					<?=$this->Form->input('description', array("class"=>"form-control confirmLeavePage", "id"=>"description", "cols"=>"30", "rows"=>"6" ));?>
				</div>
			</div>
			<div class="col-md-3 post-sidebar">
				<div class="box box-info">
					<div class="box-header with-border">
					  <h3 class="box-title">公開</h3>

					  <div class="box-tools pull-right">
					    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					    </button>
					  </div>
					</div>
					<div class="box-body">
					 	<input type="submit" name="status" class="btn btn-default btn-sm" value="下書きとして保存">
						<input type="submit" name="status" class="btn btn-primary btn-sm" value="公開">
					</div>
				</div>

				<?php echo $this->element('category'); ?>
				<div class="box box-info">
					<div class="box-header with-border">
					  <h3 class="box-title">アイキャッチ画像</h3>

					  <div class="box-tools pull-right">
					    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					    </button>
					  </div>
					</div>
					<div class="box-body">
					  <input type="hidden" class="form-control" name="data[Post][avatar]" id="file_avatar">
						<div id="avatar"></div>
						<div id="get-media"><?php echo $this->element('mediaGet'); ?></div>
						<div id="remove-image" style="display: none"><a href="javascript:void(0)">アイキャッチ画像を削除</a></div>
					</div>
				</div>
			</div>
			<div class="form-group"></div>
			<!-- </form> -->
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<?php $this->start('script')?>
	<?php echo $this->Html->script('/assets/components/tinymce/tinymce.min');?>
	<script>
	jQuery(document).ready(function($) {
		var formSubmitting = false;
	});
	tinymce.init({ 
		selector:'#description',
		height: 300,
		theme: 'modern',
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
		],
		toolbar1: 'bold italic | bullist numlist blockquote hr | alignleft aligncenter alignright | link unlink pagebreak image media code ',
		toolbar2: 'formatselect | table | underline alignjustify removeformat charmap | forecolor backcolor | outdent indent | undo redo ',
		templates: [
			{ title: 'Test template 1', content: 'Test 1' },
			{ title: 'Test template 2', content: 'Test 2' }
		],
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		],
		menubar: false,
		setup: function (editor) {
			$('#insert-into-post').on('click',  function(event) {
				jQuery("input[name='check_image[]']:checked").each(function(){
			      var src = jQuery(this).val();
			      var data = '<img src="'+'/../'+src+'" alt="..." class="img-thumbnail img-check">';
			      editor.insertContent(data);
			      jQuery(this).prop("checked", false);
			    });
			    $('#insert-into-post').attr('disabled', true);
      			$('#delete-media').attr('disabled', true);
      			$('#mediaModal').modal('hide');
			});

			editor.on('focus', function(e) {
        formSubmitting = true;
      });
		},
	});
	
	jQuery(document).ready(function($) {
		$(".confirmLeavePage:input").on('change keypress paste', function() {
		  formSubmitting = true;
		});
		$("#post-validate").submit(function( event ) {
		  formSubmitting = false;
		});
		$(window).bind("beforeunload", function () {
			if (formSubmitting) {
				$(window).unbind('beforeunload');
				return "\o/";
			}
	  });
	});
	
	// function stopNavigate() {
	//    $(window).unbind('beforeunload');
	// }
	</script>
<?php $this->end()?>