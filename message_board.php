<?php
/**
 * File: message_board.php
 * Author: Shi Weifeng
 * Student Number: 221500317
 * Email: weafung@163.com
 * Generate: 2016/10/26
 */
class MessageBoard {
	private static $db;

	public function __construct(){
		require_once('db.class.php');
		$this->db = db::getInstance();
	}

	public function add() {
		if(empty($_POST['nickname']) || empty($_POST['content'])) {
			echo json_encode(array('success'=>false,'data'=>'error','message'=>'昵称或内容不能为空'));
			exit;
		}
		$data = array(
			'nickname' => $_POST['nickname'],
			'email'    => $_POST['email'],
			'content'  => $_POST['content'],
			'time'     => time()
		);
		if($this->db->insert('message_board',$data) > 0) {
			echo json_encode(array('success'=>true,'data'=>$data));
			exit;
		} else {
			echo json_encode(array('success'=>false,'data'=>'出现错误啦~'));
			exit;
		}
	}

	public function fetch() {
		$data = $this->db->select('message_board','','','time');
		if(!empty($data)) {
			echo json_encode(array('success'=>true,'data'=>$data));
		} else {
			echo json_encode(array('success'=>false,'data'=>''));
		}
	}
}


$message_board = new MessageBoard();
if(isset($_GET['action'])) {
	if($_GET['action'] == 'add') {
		$message_board->add();
	} else if($_GET['action'] == 'fetch') {
		$message_board->fetch();
	}
} else {
	echo json_encode(array('success'=>false,'data'=>''));
}
