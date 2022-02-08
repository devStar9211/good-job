<div class="container">
    <div class="">
        <div class="navbar-header">
            <button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container"
                    type="button" class="navbar-toggle pull-left">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span>
            </button>
            <h1 class="header-logo">
                <?php echo $this->Html->image('/img/logo_goodjob.png', array('alt' => 'Timeset', 'url' => false)); ?>
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
                    <li class="language_item language-ja ">
                        <?php echo $this->Html->link(__('Logout'), '/logout'); ?>
                    </li>
                </ul>
            </section>
        <?php endif ?>
    </div>
</div>