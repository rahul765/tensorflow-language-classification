<?php

/*
 * Nowe funkcnolaności do wprowadzenia:
 *  - komendy, np: user:abc;pass:123, kick:[hash]
 *  - zastrzeżone nazwy użytkowników
 *  - możliwość wyciszania powiadomień o wiadomościach od użytkowników
 *  - obsługa eventu zamknięcia (przez serwer) połączenia
 */

$SALT = '';
$USER_HASH = hash('sha1', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$SALT);
//require_once '../bb/db.php';

function getHash($hash) {
	return substr($hash, 0, 16);
}

class controller {
	public function __construct() {
		global $dbh, $USER_HASH;
		$n = filter_input(INPUT_POST, 'n', FILTER_SANITIZE_STRING);
		$m = filter_input(INPUT_POST, 'm', FILTER_SANITIZE_STRING);
		$c = filter_input(INPUT_POST, 'c', FILTER_SANITIZE_STRING);
		if (strlen($n) && strlen($n) < 60 && strlen($m) && strlen($m) < 200 && strlen($c) == 6) {
			$this->notSoFast();
			$stmt = $dbh->prepare("INSERT INTO czat_messages (nick, color, message, hash) VALUES (?, ?, ?, '$USER_HASH')");
			$stmt->bindParam(1, $n, PDO::PARAM_STR, 60);
			$stmt->bindParam(2, $c, PDO::PARAM_STR, 6);
			$stmt->bindParam(3, $m, PDO::PARAM_STR, 200);
			$stmt->execute();
			exit;
		}
		
		$re = filter_input(INPUT_POST, 're', FILTER_SANITIZE_NUMBER_INT);
		if (!empty($re)) {
			$this->notSoFast();
			$dbh->query("UPDATE czat_users SET lastpost = (SELECT id FROM czat_messages ORDER BY id DESC LIMIT 1)-3 WHERE hash = '$USER_HASH'");
			exit;
		}
		
		$iam = filter_input(INPUT_POST, 'iam', FILTER_SANITIZE_NUMBER_INT);
		if (!empty($iam)) {
			echo getHash($USER_HASH);
			exit;
		}
	}
	
	private function notSoFast() {
		global $dbh, $USER_HASH;
		$obj = $dbh->query("SELECT TIMESTAMPDIFF(SECOND, time, NOW()) diff FROM czat_messages WHERE hash = '$USER_HASH' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_OBJ);
		if (!empty($obj) && intval($obj->diff) < 1) {
			echo 'nop';
			exit;
		}
	}
}

class server {
	private $db;
	private $user;

	public function __construct() {
		global $dbh, $USER_HASH;
		$this->db = $dbh;
		$this->user = $USER_HASH;
		$this->compose();
	}
	
	private function prepare() {
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
	}
	
	private function compose() {
		$this->clear();
		$this->prepare();
		$pointer = $this->getLastPost();
		$this->checkIn();
		$obj = $this->db->query("SELECT id, nick, color, message, DATE_FORMAT(time, '%H:%i:%s %d/%m/%Y') time, hash FROM czat_messages WHERE id > $pointer ORDER BY id ASC LIMIT 1")->fetch(PDO::FETCH_OBJ);
		if (!empty($obj)) {
			$this->speedUp();
			echo 'id: '.$obj->id.PHP_EOL;
			echo 'data: {"n":"'.$obj->nick.'","h":"'.getHash($obj->hash).'","c":"'.$obj->color.'","m":"'.$obj->message.'","t":"'.$obj->time.'"}'.PHP_EOL;
			$this->save($obj->id);
		} else {
			$this->slowDown();
			echo 'id: '.$pointer.PHP_EOL;
		}
		$this->usersEvent();
		$this->finish();
	}
	
	private function save($post) {
		$this->db->query("UPDATE czat_users SET lastpost = $post WHERE hash = '$this->user'");
	}
	
	private function usersEvent() {
		$str = '';
		$users = $this->db->query("SELECT id, hash FROM czat_users WHERE lastcheckin > ".(time()-6))->fetchAll(PDO::FETCH_OBJ); // store as timestamp..?
		if (!empty($users)) {
			foreach ($users as $k=>$v) {
				$nick = $this->db->query("SELECT nick, color FROM czat_messages WHERE hash = '$v->hash' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_OBJ);
				if (!empty($nick)) {
					$str .= '"'.$nick->nick.'":"'.$nick->color.'",';
				} else {
					$str .= '"'.getHash($v->hash).'":"",';
				}
			}
			echo PHP_EOL.'event: users'.PHP_EOL;
			echo 'data: {'.substr($str, 0, -1).'}'.PHP_EOL;
		}
	}
	
	private function checkIn() {
		$this->db->query("UPDATE czat_users SET lastcheckin = ".time()." WHERE hash = '$this->user'"); // store as timestamp..?
	}

	private function getLastPost() {
		$lastpost = $this->db->query("SELECT lastpost FROM czat_users WHERE hash = '$this->user'")->fetch(PDO::FETCH_OBJ)->lastpost;
		if($lastpost == NULL) {
			$this->db->query("INSERT INTO czat_users VALUES (NULL, '$this->user', 0, 0)");
			$lastpost = 0;
		}
		return $lastpost;
	}

	private function clear() {
		$this->db->query("DELETE FROM czat_messages WHERE TIMESTAMPDIFF(SECOND, time, NOW()) > 360");
	}

	private function finish() {
		echo PHP_EOL;
		ob_flush();
		flush();
	}
	
	private function slowDown() {
		echo 'retry: 1000'.PHP_EOL;
	}
	
	private function speedUp() {
		echo 'retry: 200'.PHP_EOL;
	}
}

$c = new controller();
$s = new server();