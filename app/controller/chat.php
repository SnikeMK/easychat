<?php
class ControllerChat extends Controller {
	public function index() {
		$this->load->model('chat');
		$tempdata = json_decode(file_get_contents('php://input'));
		$data = [
			'nick' => $tempdata[0],
			'message' => $tempdata[1],
		];
		$this->sendMessage($data);
		$messages = $this->getMessage();
		echo json_encode($messages);
	}

	public function loadMessages() {
		$msg_index = json_decode(file_get_contents('php://input'));
		$this->load->model('chat');
		$messages = $this->model_chat->getMessage($msg_index);
		echo json_encode($messages);
	}

	public function getMessage() {
		$this->load->model('chat');
		return $this->model_chat->getMessage();
	}

	public function sendMessage($array) {
		$this->load->model('chat');
		$this->model_chat->sendMessage($array['nick'], $array['message']);
	}
}
