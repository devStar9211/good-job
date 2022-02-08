<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

    public $calendars = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
        'customer_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 7, 'collate' => 'utf8mb4_unicode_ci'),
        'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci'),
        'ical_url' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 256, 'collate' => 'utf8mb4_unicode_ci'),
        'created' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
        'updated' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),

        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'UNIQUE' => array('column' => 'customer_id', 'unique' => 1),
        ),
        'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci', 'engine' => 'InnoDB')
    );

    public $events = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
        'calendar_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11),
        'summary' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 256, 'collate' => 'utf8mb4_unicode_ci'),
        'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_unicode_ci'),
        'location' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 256, 'collate' => 'utf8mb4_unicode_ci'),
        'start_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
        'end_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
        'uid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci'),
        'created' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
        'updated' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),

        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'calendar_id' => array('column' => 'calendar_id'),
        ),
        'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci', 'engine' => 'InnoDB')
    );

    public $users = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
        'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci'),
        'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci'),
        'full_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci'),
        'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8mb4_unicode_ci'),
        'profile_picture' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 256, 'collate' => 'utf8mb4_unicode_ci'),
        'account_type' => array('type' => 'integer', 'null' => true, 'default' => 3, 'length' => 1),
        'status' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
        'created' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
        'updated' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
            'UNIQUE' => array('column' => 'username', 'unique' => 1),
        ),
        'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci', 'engine' => 'InnoDB')
    );

}
