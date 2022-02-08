<?php

App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class EmailComponent extends Component{
	function send($url,$email){
	$Email = new CakeEmail('forgot_password');
        $Email->template('active', 'default');
        $Email->emailFormat('both');
        $Email->viewVars(array('url' => $url, 'email' => $email));
        $Email->from(array('noreply@good-job.online' => 'Caregiver Japan'));
        $Email->to($email);
        $Email->subject(__('グッジョブ！登録用URLをお知らせします'));
        $Email->send();
	}	
}