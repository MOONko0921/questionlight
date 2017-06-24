<?php
//detabase class 読み込み
require_once('DB.class.php');


/* messge class
2017.04.18*/
class Message
{


    private $db;
    private $msg_list_key;
    private $adv_msg_list_key;
    public function __construct()
    {
        $this->db = new DB();
    }
    // メッセージ開始
    public function startMessage($user_id, $to_user_id)
    {
        $msg_list = $this->getMessagesListKey($user_id);
        $adv_msg_list = $this->getMessagesListKey($to_user_id);
        if(!in_array($to_user_id, $msg_list)){
            $sql = 'UPDATE ch_user_meta SET message_list=:list WHERE user_id=:user_id';
            $data = array(
                ':user_id' => $user_id,
                ':list' => $to_user_id.','.$this->msg_list_key
            );
            $this->db->queryPost($sql, $data);
            $sql = 'UPDATE ch_user_meta SET message_list=:list WHERE user_id=:user_id';
            $data = array(
                ':user_id' => $to_user_id,
                ':list' => $user_id.','.$this->adv_msg_list_key
            );
            $this->db->queryPost($sql, $data);
        }
        return false;
    }
    //メッセージ投稿
    public function insertMessage($user_id, $to_user_id, $content)
    {
        $sql = 'INSERT INTO messages SET user_id=:user_id, to_user_id=:to_user_id, content=:content, flug=1, created=NOW()';
        $data = array(
            ':user_id' => $user_id,
            ':to_user_id' => $to_user_id,
            ':content' => $content
        );
        $this->db->queryPost($sql, $data);
    }

    //メッセージ取得
    public function getMessages($user_id, $to_user_id)
    {
        $sql = 'SELECT m.*, u.name FROM ch_messages m, ch_users u WHERE
        m.user_id=u.id AND (m.user_id=:user_id OR (m.to_user_id=:user_id AND user_id=:to_user_id))';
        $data = array(
            ':user_id' => $user_id,
            ':to_user_id' => $to_user_id
        );
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        return $row;
    }

    //メッセージリストの取得
    public function getMessagesListKey($user_id)
    {
        $sql = 'SELECT message_list FROM ch_user_meta WHERE user_id=:id';
        $data = array(
            ':id' => $user_id
        );
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        if(!empty($row[0]['message_list'])){
            $key = substr($row[0]['message_list'], 0, -1);
            $msg_list = explode(',', $key);
            $this->msg_list_key = $row[0]['message_list'];
            return $msg_list;
        }else{
            return array();
        }
    }

    public function getMessagesList($user_id)
    {
        $msg_list = $this->getMessagesListKey($user_id);
        if($msg_list){
            $ret = array();
            foreach($msg_list as $value){
                $sql = 'SELECT u.name, u.id, um.img FROM ch_users u, ch_user_meta um WHERE u.id=:id AND u.id=um.user_id';
                $data = array(':id' => $value);
                $recode = $this->db->queryPost($sql, $data);
                $row = $this->db->dbFetch($recode);
                $ret[] = $row;
            }
            return $ret;
        }else{
            return false;
        }
    }
}
