<div class="row">
	<div class="col-sx-12 panel-collapse collapse <?php echo empty($is_search) ? '' : 'in'?>" id="search-box" aria-expanded="<?php echo empty($is_search) ? 'false' : 'true'?>" style="<?php echo empty($is_search) ? 'style: 0px' : ''?>">
		<form name="form_site" method="get" id="form_site" action="">
			<div class="col-xs-12">
				<div class="form-group col-xs-12 col-sm-6">
					<label for="AccountUsername">ユーザー名</label>
					<div class="clearfix"></div>
					<input type="text" name="username" placeholder="ユーザー名" class="form-control" value="<?php echo isset($search['username']) ? $search['username'] : ''?>">
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<label for="AccountEmail">メール</label>
					<div class="clearfix"></div>
					<input type="text" name="email" placeholder="メールアドレス" class="form-control" value="<?php echo isset($search['email']) ? $search['email'] : ''?>">
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<label for="Status">ステータス</label>
					<div class="clearfix"></div>
                    <?php $status = !isset($search['status']) || $search['status'] === '' ?  -1 : $search['status']?>
					<select class="form-control"  name="status">
						<option value="" selected>-- 選択してください --</option>
                        <option value="<?php echo Configure::read('User.active'); ?>" <?php echo ($status == Configure::read('User.active'))? 'selected' : ''?>>アクティブ</option>
						<option value="<?php echo Configure::read('User.inactive'); ?>" <?php echo ($status == Configure::read('User.inactive'))? 'selected' : ''?>>非アクション</option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>

			<div class="row-fluid text-center" style="margin: 15px 0">
				<input type="submit" class="btn btn-primary" name="search" value="検索">
				<input type="reset" class="btn btn-default" id="reset" value="リセット">
			</div>
		</form>
	</div>
</div>
	
        <?php
                  $option = array();
                  $option['title'] = '管理者アカウント管理 ';
                  $option['col'] =array(
                      0 => array('key_tab' =>'id','title_tab' => 'ID','option_tab' => 'sort'),
                      1 => array('key_tab' =>'username','title_tab' => 'ユーザー名','option_tab' => 'sort'),
                      2 => array('key_tab' =>'full_name','title_tab' => '名前','option_tab' => 'sort'),
                      3 => array('key_tab' =>'email','title_tab' => 'メール','option_tab' => 'sort'),
                      5 => array('key_tab' =>'status','title_tab' => 'ステータス','option_tab' => 'sort'),
                      7 => array('key_tab' =>'updated','title_tab' => '最終更新','option_tab' => 'sort'),
                      8 => array('key_tab' =>'','title_tab' => '操作','option_tab' => ''),
                  );
                  echo $this->grid->create($accounts,null,$option);
                  ?>
				<?php foreach ($accounts as $account): ?>
					<tr>
                        <td><?php echo h($account['User']['id']); ?>&nbsp;</td>
						<td><?php echo h($account['User']['username']); ?>&nbsp;</td>
                        <td><?php echo h($account['User']['full_name']); ?>&nbsp;</td>
                        <td>
                            <?php if($account['User']['email']) :?>
                            <a href="mailto:<?php echo $account['User']['email']; ?>"><?php echo $account['User']['email']; ?></a>
                            <?php else: ?>
                                &nbsp;
                            <?php endif;?>
                        </td>
                        <?php
                        $status = '';
                        switch($account['User']['status']){
                            case Configure::read('User.active'):
                                $status = '<span class="label label-success">アクション</span>';
                                break;
                            case Configure::read('User.inactive'):
                                $status = '<span class="label label-danger">非アクティブ</span>';
                                break;
                        }
                        ?>
                        <td><?php echo $status; ?></td>
                        <td><?php echo h(date('Y-m-d H:i:s',strtotime($account['User']['updated']))); ?>&nbsp;</td>
						<td class="actions">
                            <?php echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
                                array('action' => 'edit', $account['User']['id']),
                                array('escape'=>false, 'class' => 'btn btn-success btn-sm', 'title' => '編集する')

                            );
                            ?>
                            <?php echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-lock icon-white')),
                                array('action' => 'change_password', $account['User']['id']),
                                array('escape'=>false, 'class' => 'btn btn-warning btn-sm', 'title' => 'パスワード変更')

                            );
                            ?>
                            <?php echo $this->Form->postLink(
                                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white')),
                                array('action' => 'delete', $account['User']['id']),
                                array('escape'=>false, 'class' => 'btn btn-danger btn-sm btn-cat-cancel', 'title' => '削除する'),
                                __('%sを本当に削除しますか。', $account['User']['id'])

                            );
                            ?>
						</td>
					</tr>
			<?php endforeach; 
                  echo $this->grid->end_table($accounts,null,$option);
		?>
	
<?php $this->start('action_bar');
?>
<div class="pull-right"><a id="search-button" data-toggle="collapse" href="#search-box" aria-expanded="false" class="collapsed slide-button btn btn-default"><span class="glyphicon-collapse glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a></div>
<div class="btn-group pull-right">
	<!--register user-->
	<?php echo $this->Html->link(__('新規登録'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>

</div>
<?php $this->end(); ?>
