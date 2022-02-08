<div class="login-box">
    <div class="login-logo">
        <?php echo $this->Html->image('/img/logo_goodjob.png', array('style'=>'width: 200px;', 'class'=>'logo', 'alt' => 'Timeset', 'url' => array('controller' => 'dashboard', 'action' => 'admin_index'))); ?>
    </div><!-- /.login-logo -->

    <div class="login-box-body">
        <p class="login-box-msg">サインインしてください</p>
        <?php echo $this->Session->flash("error"); ?>
        <form action="" id="UserAdminLoginForm" method="post" accept-charset="utf-8">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="data[Account][username]" id="UserUsername"
                       placeholder="ユーザー名" value="<?php echo $account['username']; ?>">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="data[Account][password]" id="UserPassword"
                       placeholder="パスワード" value="<?php echo $account['password']; ?>">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="data[Account][remember]"<?php echo isset($account['remember']) && $account['remember'] == 1 ? ' checked' : ''; ?> value="1">
                            ユーザー名とパスワードを保存
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-12 col-sm-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">利用規約に同意してサインイン</button>
                </div><!-- /.col -->
            </div>
            <br>
            <p><?php echo $this->Html->link(__('Forgot password'), '/forgot_password'); ?></p>
            <p><?php echo $this->Html->link(__('Term and condition'), '/terms.html'); ?></p>
            <p><?php echo $this->Html->link(__('privacy policy'), '/privacy.html'); ?></p>

    </div><!-- /.login-box-body -->
</div>

