<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'frontend', 'action' => 'index'));

//posts
Router::connect('/posts', array('controller' => 'frontend', 'action' => 'list_post'));
Router::connect('/posts/loadPost', array('controller' => 'frontend', 'action' => 'loadPost'));
Router::connect('/posts/:post_title-:id', array('controller' => 'frontend', 'action' => 'post_view'), array('pass' => array('post_title', 'id')));

//daily_settlement
Router::connect('/daily_settlement', array('controller' => 'frontend', 'action' => 'daily_settlement'));
Router::connect('/DailySettlement/get_daily_data', array('controller' => 'frontend', 'action' => 'get_daily_data'));

//budget_ranking
Router::connect('/budget_ranking', array('controller' => 'frontend', 'action' => 'budget_ranking'));
Router::connect('/BudgetRanking/get_budget_ranking_data', array('controller' => 'frontend', 'action' => 'get_budget_ranking_data'));

//budget_ranking
Router::connect('/quarter_budget_ranking', array('controller' => 'frontend', 'action' => 'quarter_budget_ranking'));


// budget_sale
Router::connect('/budget_sale', array('controller' => 'frontend', 'action' => 'budget_sale'));
Router::connect('/comparison/get_last_year_comparison_data', array('controller' => 'frontend', 'action' => 'get_budget_ranking_data'));


//user_page
Router::connect('/user_page', array('controller' => 'frontend', 'action' => 'user_page'));
Router::connect('/UserPage', array('controller' => 'frontend', 'action' => 'get_data_prize'));
Router::connect('/my_page', array('controller' => 'frontend', 'action' => 'my_page'));
Router::connect('/all_point', array('controller' => 'frontend', 'action' => 'all_point'));
Router::connect('/point_quarter', array('controller' => 'frontend', 'action' => 'point_quarter'));
Router::connect('/point_bonus', array('controller' => 'frontend', 'action' => 'point_bonus'));

Router::connect('/calendar', array('controller' => 'frontend', 'action' => 'calendar'));
Router::connect('/ranking', array('controller' => 'frontend', 'action' => 'ranking'));

Router::connect('/admin', array('controller' => 'dashboard', 'action' => 'index', 'admin' => true));

// Router::connect('/admin/*', array('plugin' => 'AclManager'));
Router::connect('/admin/acl/permissions', array('plugin' => 'AclManager', 'controller' => 'acl', 'action' => 'permissions', 'admin' => true));
/**
 * admin route
 */
Router::connect('/logout', array('controller' => 'accounts', 'action' => 'logout', 'admin' => false));
Router::connect('/login', array('controller' => 'accounts', 'action' => 'login', 'admin' => false));
Router::connect('/edit_profile', array('controller' => 'accounts', 'action' => 'edit_profile', 'admin' => false));
Router::connect('/edit_email', array('controller' => 'accounts', 'action' => 'edit_email', 'admin' => false));
Router::connect('/forgot_password', array('controller' => 'accounts', 'action' => 'forgot_password', 'admin' => false));
Router::connect('/active', array('controller' => 'frontend', 'action' => 'active', 'admin' => false));
Router::connect('/salary_detail', array('controller' => 'frontend', 'action' => 'salary_detail', 'admin' => false));

Configure::write('Routing.prefixes', array('admin'));
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
