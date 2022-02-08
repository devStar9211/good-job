<div class="container">
    <div class="">
        <div class="navbar-header">
            <button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container"
                    type="button" class="navbar-toggle pull-left">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span>
            </button>
            <h1 class="header-logo">
                <?php echo $this->Html->image('/img/logo_goodjob.png', array('alt' => 'Timeset', 'url' => array('controller' => 'frontend', 'action' => 'index'))); ?>
            </h1>
        </div>
        <div class="navbar-inverse side-collapse in">
            <nav role="navigation" class="navbar-collapse">
                <div class="mnu-current-info">
                    <?php
                    if (empty($user['Employee']['id'])) {
                        if (!empty($user['Admin']['id'])) {
                            $image_url = $this->Html->image('/' . AVATAR_PATH . $user['Admin']['avatar'], array('alt' => ''));
                            $username = $user['Admin']['name'];
                        } else {
                            $image_url = $this->Html->image('/' . AVATAR_PATH . 'default-avatar.png', array('alt' => ''));
                            $username = $user['username'];
                        }
                    } else {
                        $image_url = $this->Html->image('/' . AVATAR_PATH . $user['Employee']['avatar'], array('alt' => ''));
                        $username = $user['Employee']['name'];
                    }

                    $new_username = mb_strlen($username, 'UTF-8') > 4 ? mb_substr($username, 0, 4, 'UTF-8') . '...' : $username . 'さん';
                    ?>
                    <?php echo $image_url ?>
                    <div class="">
                        <p>こんにちは。</p>
                        <p> <?php echo $username.'さん'; ?></p>
                    </div>
                </div>
                <ul class="nav navbar-nav">
                    <li class="dropdown settlement mnu-home">
                        <i class="fa fa-fw fa-home"></i>
                        <a href="/" class=" dropdown-toggle"><?php echo __('HOME'); ?></a>
                    </li>
                    <li class="dropdown settlement">
                        <i class="img-icon img-bt-nichiji"></i>
                        <a href="/daily_settlement" class="dropdown-toggle"><?php echo __('Dự toán hàng ngày'); ?></a>
                    </li>

                    <li class="dropdown ranking-menu-1 ">
                        <i class="img-icon icon-ranking"></i>
                        <a href="/ranking" class="dropdown-toggle" role="button" aria-haspopup="true"
                           aria-expanded="false"><?php echo __('Ranking'); ?></a>
                    </li>
                    <li class="dropdown ranking-menu-1 ">
                        <i class="img-icon img-bt-calendar"></i>
                        <a href="/calendar" class="dropdown-toggle" role="button" aria-haspopup="true"
                           aria-expanded="false"><?php echo __('calendar'); ?></a>
                    </li>

                    <li class="dropdown ranking-menu-2 ">
                        <i class="img-icon icon-shift"></i>
                        <a href="https://shift.good-job.online/" target="_blank" class="dropdown-toggle" role="button" aria-haspopup="true"
                           aria-expanded="false"><?php echo __('Shift Management'); ?></a>
                    </li>

                    <li class="dropdown post ">
                        <i class="fa fa-newspaper-o"></i>
                        <a href="/posts" class="dropdown-toggle" role="button" aria-haspopup="true"
                           aria-expanded="false"><?php echo __('Post'); ?></a>
                    </li>
                    <li class="dropdown post ">
                        <i class="img-icon img-bt-test"></i>
                        <a href="https://test.good-job.online/" target="_blank" class="dropdown-toggle"
                           role="button" aria-haspopup="true" aria-expanded="false"><?php echo __('検定'); ?></a>
                    </li>
                    <li class="dropdown post ">
                        <i class="img-icon img-user-page"></i>
                        <a href="/my_page" class="dropdown-toggle" role="button" aria-haspopup="true"
                           aria-expanded="false"><?php echo __('マイページ'); ?></a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- header_user -->
        <?php if (isset($user)): ?>
            <section class="header-language dropdown">
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false">

                    <?php echo $image_url; ?>
                    <span> <?php echo $new_username; ?></span>
                </a>
                <ul class="dropdown-menu language-switching" role="menu">
                    <?php $adminPermissionArr = Configure::check('AdminPermission') ? Configure::read("AdminPermission") : array(); ?>
                    <?php if (in_array($user['GroupPermission']['id'], $adminPermissionArr)) : ?>
                        <li class="language_item language-en "><?php echo $this->Html->link(__('Dashboard'), '/admin'); ?></li>
                    <?php endif ?>
                    <li class="language_item language-en "><?php echo $this->Html->link(__('Edit profile'), '/edit_profile'); ?></li>
                    <li class="language_item language-en "><?php echo $this->Html->link(__('My page'), '/my_page'); ?></li>
                    <li class="language_item language-ja ">
                        <?php echo $this->Html->link(__('Logout'), '/logout'); ?>
                    </li>
                </ul>
            </section>
        <?php endif ?>
    </div>
</div>

