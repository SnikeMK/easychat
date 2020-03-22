<?php
class ModelChat extends Model {
	public function getMessage($msg_index = 0) {
		if ($msg_index != 0) {
			$temp = $this->db->query("SELECT * FROM `chat` WHERE msg_id > '" . (int)$msg_index . "' ORDER BY msg_id DESC");
		} else {
			$temp = $this->db->query("SELECT * FROM `chat` ORDER BY msg_id DESC LIMIT 30");
		}
		return array_reverse($temp->rows);
	}

	public function sendMessage($nick, $message) {
		if ($nick && $message) {
			$this->db->query("INSERT INTO `chat` (`nick`, `message`) VALUES ('" . $nick . "', '" . $message . "')");
		}
	}
}
