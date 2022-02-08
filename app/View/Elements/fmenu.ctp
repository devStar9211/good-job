<nav class="gtco-nav" role="navigation">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 text-left menu-1 main-nav">
                <ul>
                    <?php echo $this->Sidebar->frontend_main_nav() ?>
                </ul>
            </div>
            <div class="col-sm-2 col-xs-12">
                <div id="gtco-logo">
                	<div class="dropdown user user-menu">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                    <img src="/img/person-flat.png" alt="">
		                    <span>admin <i class="caret"></i></span>
		                </a>
		                <ul class="dropdown-menu">
		                    <!-- User image -->
		                    <li class="user-header">
		                        <p>admin  </p>
		                    </li>
		                    <!-- Menu Footer-->
		                    <li class="user-footer">
		                        <div class="pull-left">
		                            <a class="btn btn-default btn-flat" title="パスワード変更" href="/users/change_password">Change Password</a>
		                        </div>
		                        <div class="pull-right">
		                            <a class="btn btn-default btn-flat" href="/logout">Logout</a>
		                        </div>
		                    </li>
		                </ul>
		            </div>
                </div>
            </div>
        </div>
    </div>
</nav>