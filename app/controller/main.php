<?php
class ControllerMain extends Controller
{
	function index()
	{
		// $data = array();
		// $data['messages'] = $this->load->controller('chat/getMessage');
		$this->view->generate('main_view.php', 'home.php', $data);
	}
}
