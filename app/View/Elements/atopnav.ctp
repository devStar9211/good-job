<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php if (empty($user['Employee']['id'])): ?>
                       <?php if (!empty($user['Admin']['id'])): ?>
                             <?php echo $this->Html->image('../'.AVATAR_PATH.$user['Admin']['avatar'], array('alt' => 'Timeset', 'class' => 'user-image'));?>
                        <?php else: ?>
                             <?php echo $this->Html->image('../'.AVATAR_PATH.'default-avatar.png', array('alt' => 'Timeset', 'class' => 'user-image'));?>
                        <?php endif ?>
                    <?php else: ?>
                        <?php if (!empty($user['Employee']['id'])): ?>
                            <?php echo $this->Html->image('../'.AVATAR_PATH.$user['Employee']['avatar'], array('alt' => 'Timeset', 'class' => 'user-image'));?>
                        <?php else: ?>
                            <?php echo $this->Html->image('../'.AVATAR_PATH.'default-avatar.png', array('alt' => 'Timeset', 'class' => 'user-image'));?>
                        <?php endif ?>
                    <?php endif ?>
                    <span><?php echo $user['username']; ?> <i class="caret"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <p><?php echo $user['username']; ?>
                        </p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                            <a class="btn btn-default btn-flat" title="パスワード変更" href="<?php echo $this->Html->url(array('controller' => 'accounts', 'action' => 'edit_profile', 'admin' => false, 'plugin' => false), true); ?>"><?php echo __('Edit profile')?></a>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-default btn-flat" href="<?php echo $this->Html->url(array('controller' => 'accounts', 'action' => 'logout', 'admin' => false, 'plugin' => false), true); ?>"><?php echo __('Logout')?></a>
                        </div>
                        <div class="clearfix-r"></div>
                        <div style="padding-top: 10px;"><a class="btn btn-default btn-flat" href="/">ホームへ移動</a></div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>