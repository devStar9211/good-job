<?php

define('AVATAR_PATH', 'assets/images/avatar/');
define('DEFAULT_AVATAR', 'default-avatar.png');

// csv format
$config['CSV'] = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv'];

/*=======================================================
=            Config sidebar left for backend            =
=======================================================*/

$config['admin_menu'] = array(
	'Posts' => array(
		'is_display' => 1,
		'childs' => array(
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'posts',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'posts',
						'action' => 'admin_edit',
						'plugin' => null,
						'admin' => true
					),
					array(
						'controller' => 'posts',
						'action' => 'view',
						'plugin' => null,
						'admin' => true
					)
				),
				'icon' => 'fa-angle-right',
				'title'=> __('List bài viết'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'posts',
					'action' => 'admin_add',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Tạo bài viết'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'categories',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'categories',
						'action' => 'admin_edit',
						'plugin' => null,
						'admin' => true
					),
					array(
						'controller' => 'categories',
						'action' => 'admin_add',
						'plugin' => null,
						'admin' => true
					)
				),
				'icon' => 'fa-angle-right',
				'title' => __('Article category'),
			),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'posts',
                    'action' => 'admin_config',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title' => __('Blog article display count'),
            ),
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Post thông tin mới đến-NEWS'),
	),
	'BudgetSales' => array(
		'is_display' => 1,
		'childs' => array(
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'budget_sales',
					'action' => 'admin_revenue_budget',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'budget_sales',
						'action' => 'admin_budget_csv_import',
						'plugin' => null,
						'admin' => true
					)
				),
				'icon' => 'fa-angle-right',
				'title'=> __('Nhập dự toán'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'budget_sales',
					'action' => 'admin_revenue_sales',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'budget_sales',
						'action' => 'admin_sales_csv_import',
						'plugin' => null,
						'admin' => true
					)
				),
				'icon' => 'fa-angle-right',
				'title' => __('Nhập thành tích thực tế'),
			),
            array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'budget_sales',
					'action' => 'admin_past_highest_sales',
					'plugin' => null,
					'admin' => true
				),

				'icon' => 'fa-angle-right',
				'title' => __('Doanh số cao nhất trong quá khứ'),
			),

            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'budget_sales',
                    'action' => 'admin_update_sale_config',
                    'plugin' => null,
                    'admin' => true
                ),

                'icon' => 'fa-angle-right',
                'title' => __('売上(ほのぼの)'),
            ),
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Doanh số'),
	),
    'Point for employees' => array(
        'is_display' => 1,
        'childs' => array(
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'point_details',
                    'action' => 'admin_input_point',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title'=> __('Manual input point'),
                'modules_active' => array(
                    array(
                        'controller' => 'point_details',
                        'action' => 'admin_csv_import',
                        'plugin' => null,
                        'admin' => true
                    ),
                ),
            ),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'point_details',
                    'action' => 'admin_history',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title'=> __('Point history'),
                'modules_active' => array(
                    array(
                        'controller' => 'point_details',
                        'action' => 'admin_history_csv_download',
                        'plugin' => null,
                        'admin' => true
                    ),
                ),
            ),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'point_bonus',
                    'action' => 'admin_input',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title' => __('Basic bonus'),
                'modules_active' => array(
                    array(
                        'controller' => 'point_bonus',
                        'action' => 'admin_csv_import',
                        'plugin' => null,
                        'admin' => true
                    ),

                ),
            ),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'point_types',
                    'action' => 'admin_index',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title'=> __('Point type'),
            ),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'condition_points',
                    'action' => 'admin_index',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title' => __('Achievement point condition')
            ),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'point_bonus',
                    'action' => 'admin_quarterly_csv_download',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title' => __('Quarterly CSV')
            )
        ),
        'icon' => 'fa-angle-double-right',
        'title' => __('Point for employees'),
    ),
	'Companies' => array(
		'is_display' => 1,
		'childs' => array(
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'companies',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Danh sách company'),
                'modules_active' => array(
					array(
						'controller' => 'companies',
						'action' => 'admin_add',
						'plugin' => null,
						'admin' => true
					),
					array(
						'controller' => 'companies',
						'action' => 'admin_edit',
						'plugin' => null,
						'admin' => true
					),
				),
			),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'companies',
                    'action' => 'admin_daily_settlement_color_setting',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title' => __('Daily settlement chart group color setting'),
                'modules_active' => array(
                    array(
                        'controller' => 'companies',
                        'action' => 'admin_daily_settlement_color_setting',
                        'plugin' => null,
                        'admin' => true
                    ),

                ),
            ),

			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'company_groups',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký Group DB'),
                'modules_active' => array(
					array(
						'controller' => 'companygroups',
						'action' => 'admin_index',
						'plugin' => null,
						'admin' => true
					),

				),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'companies',
					'action' => 'admin_config',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Daily settlement display item setting'),
				'modules_active' => array(
					array(
						'controller' => 'companies',
						'action' => 'admin_config',
						'plugin' => null,
						'admin' => true
					),

				),
			),
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Setting Company'),
	),
	'Offices' => array(
		'is_display' => 1,
		'childs' => array(
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'offices',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'offices',
						'action' => 'admin_add',
						'plugin' => null,
						'admin' => true
					),
					array(
						'controller' => 'offices',
						'action' => 'admin_edit',
						'plugin' => null,
						'admin' => true
					),
				),
				'icon' => 'fa-angle-right',
				'title' => __('Danh sách Office')
			),
            array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'offices',
					'action' => 'admin_office_manager',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'offices',
						'action' => 'admin_office_manager',
						'plugin' => null,
						'admin' => true
					),

				),
				'icon' => 'fa-angle-right',
				'title' => __('Office manager')
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'business_categories',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký phân loại hình thức kinh doanh DB')
			),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'divisions',
                    'action' => 'admin_index',
                    'plugin' => null,
                    'admin' => true
                ),
                'icon' => 'fa-angle-right',
                'title' => __('Đăng ký Division DB')
            ),

            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'office_groups',
                    'action' => 'admin_index',
                    'plugin' => null,
                    'admin' => true
                ),
                'modules_active' => array(
                    array(
                        'controller' => 'office_groups',
                        'action' => 'admin_index',
                        'plugin' => null,
                        'admin' => true
                    ),

                ),
                'icon' => 'fa-angle-right',
                'title' => __('Đăng ký Office Group DB')
            ),
			/*array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'evaluations',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký Evaluation DB')
			),*/
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Setting Office'),
	),
	'Employees' => array(
		'is_display' => 1,
		'childs' => array(
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'employees',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'employees',
						'action' => 'admin_edit',
						'plugin' => null,
						'admin' => true
					),
					array(
						'controller' => 'employees',
						'action' => 'admin_csv_import',
						'plugin' => null,
						'admin' => true
					)
				),
				'icon' => 'fa-angle-right',
				'title'=> __('Danh sách Employee'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'employees',
					'action' => 'admin_add',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'employees',
						'action' => 'admin_add',
						'plugin' => null,
						'admin' => true
					),
				),
				'icon' => 'fa-angle-right',
				'title'=> __('従業員登録'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'positions',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title'=> __('Đăng ký Position DB'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'occupations',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký Job DB')
			),

			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'licenses',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký bằng cấp DB')
			),
            array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'allowances',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký trợ cấp DB')
			),
            array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'night_shifts',
					'action' => 'admin_index',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký trợ cấp làm đêm DB')
			),
            array(
                'is_display' => 1,
                'url' => array(
                    'controller' => 'companies',
                    'action' => 'admin_company_notification',
                    'plugin' => null,
                    'admin' => true
                ),
                'modules_active' => array(
                    array(
                        'controller' => 'companies',
                        'action' => 'admin_email_list',
                        'plugin' => null,
                        'admin' => true
                    ),

                ),
                'icon' => 'fa-angle-right',
                'title' => __('Cài đặt thông báo đăng ký nhân viên')
            ),
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Setting Employee'),
	),
	'AclManager' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'acl',
			'action' => 'admin_permissions',
			'plugin' => 'AclManager',
			'admin' => true
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Quyền xem'),
        'modules_active' => array(
            array(
                'controller' => 'acl',
                'action' => 'admin_full_permissions',
                'plugin' => 'AclManager',
                'admin' => true
            ),
        ),
	),
	'Setting kết nối API' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'setting_api',
			'action' => 'admin_index',
			'plugin' => null,
			'admin' => true
		),
		'title' => __('Setting kết nối API'),
		'icon' => 'fa-angle-double-right',
	),
	'Account' => array(
		'is_display' => 1,
		'childs' => array(
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'accounts',
					'action' => 'admin_list_admin_user',
					'plugin' => null,
					'admin' => true
				),
				'modules_active' => array(
					array(
						'controller' => 'accounts',
						'action' => 'admin_change_admin_user',
						'plugin' => null,
						'admin' => true
					)
				),
				'icon' => 'fa-angle-right',
				'title' => __('Danh sách admin user'),
			),
			array(
				'is_display' => 1,
				'url' => array(
					'controller' => 'accounts',
					'action' => 'admin_register_admin_user',
					'plugin' => null,
					'admin' => true
				),
				'icon' => 'fa-angle-right',
				'title' => __('Đăng ký admin user')
			)
		),
		'icon' => 'fa-angle-double-right',
		'title' => __('Admin user'),
	),


    'LinkJob' => array(
        'is_display' => 1,
        'url' => 'https://shift.good-job.online/',
        'icon' => 'fa-angle-double-right',
        'target'=>'_blank',
        'title' => __('シフト管理'),
    ),
);

/*=======================================================
=            Config menu top for frontend            =
=======================================================*/
$config['frontend_menu'] = array(
	'home' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'dashboard',
			'action' => 'index',
		),
		'title' => __('Home')
	),
	'quyet-toan ' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'daily_settlement',
			'action' => 'index',
		),
		'title' => __('Quyết toán')
	),
	'ranking-dat-duoc ' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'budget_ranking',
			'action' => 'index',
		),
		'title' => __('Ranking dự toán đạt được')
	),
	'ranking-so-voi-nam-ngoai ' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'comparisons',
			'action' => 'index',
		),
		'title' => __('Ranking so với năm ngoái')
	),
	'My page' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'userpage',
			'action' => 'index',
		),
		'title' => __('My page')
	),
	'posts' => array(
		'is_display' => 1,
		'url' => array(
			'controller' => 'posts',
			'action' => 'index',
		),
		'modules_active' => array(
			array(
				'controller' => 'posts',
				'action' => 'view',
			)
		),
		'title'=> __('Posts'),
	),
);
// start month
$config['start_month'] = 4;

// grid config
define('COL_1', 'col_1');
define('COL_2', 'col_2');
define('COL_3', 'col_3');
define('COL_4', 'col_4');
define('COL_5', 'col_5');
define('COL_6', 'col_6');
define('COL_7', 'col_7');
define('COL_8', 'col_8');
define('COL_9', 'col_9');
define('COL_10', 'col_10');
define('COL_11', 'col_11');
define('COL_12', 'col_12');
define('COL_13', 'col_13');
define('COL_14', 'col_14');
define('COL_15', 'col_15');
//define('COL_16', 'col_16');
//define('COL_17', 'col_17');
//define('COL_18', 'col_18');
//define('COL_19', 'col_19');
define('COL_20', 'col_20');
$config['Grid'] = array(
	COL_1 => __('highest ever sales'),
	COL_2 => __('sales target'),
	COL_3 => __('revenue sales'),
	COL_4 => __('budget achievement rate'),
	COL_5 => __('sales').__('current year'),
	COL_6 => __('cumulative percent complete'),
	COL_7 => __('operating profit target'),
	COL_8 => __('profit sales'),
	COL_9 => __('operating income achievement rate'),
	COL_10 => __('operating income').__('current year'),
	COL_11 => __('operating income').__('cumulative percent complete'),
	COL_12 => __('labor cost'),
	COL_13 => __('overtime'),
	COL_14 => __('budget ratio'),
	COL_15 => __('compare past year'),
//	COL_16 => __('occupancy rate'),
//	COL_17 => __('average care level'),
//    COL_18 => __('number of users'),
//    COL_19 => __('stay'),
    COL_20 => __('other expenses'),
);

define('SUPER_ADMIN_GROUP', 1);
define('COMPANY_ADMIN_GROUP', 2);
define('EMPLOYEE_REGISTER_ONLY_GROUP', 13);
define('SALE_GROUP', 14);
define('EMPLOYEE_AND_SALE_GROUP', 15);
$config['AdminPermission'] = array( SUPER_ADMIN_GROUP, COMPANY_ADMIN_GROUP, EMPLOYEE_REGISTER_ONLY_GROUP, SALE_GROUP, EMPLOYEE_AND_SALE_GROUP );
