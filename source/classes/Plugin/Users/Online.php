<?php
class Plugin_Users_Online extends Plugin {
	public function output(Pagemill_Data $data, Pagemill_Stream $stream) {
		$this->pluginTemplate = '/users/online.plug.html';
		$data = $data->fork();
		$users = new Model_User();
		$users->where('DATE_ADD(lastrequest, INTERVAL 30 MINUTE) > NOW()');
		$data->set('usersonline', $users->getTotal());
		parent::output($data, $stream);
	}
}
