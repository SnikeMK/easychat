<?php
class ModelChat extends Model {
	public function getMessage() {
		$temp = $this->db->query("SELECT * FROM `chat` ORDER BY msg_id DESC LIMIT 30");
		return array_reverse($temp->rows);
	}

	public function sendMessage($nick, $message) {
		if ($nick && $message) {
			$this->db->query("INSERT INTO `chat` (`nick`, `message`) VALUES ('" . $nick . "', '" . $message . "')");
		}
	}
}
