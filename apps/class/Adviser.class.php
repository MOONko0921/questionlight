<?php
require 'DB.class.php';
require 'Validate.class.php';

class Adviser extends User
{
    private $db;


    public function __construct()
    {
        parent::__construct();
        $this->db = new DB();
    }
    public function getAdvisers($get_start)
    {
        $sql = 'SELECT u.name, u.id, um.img, a.school, a.period FROM ch_users u, ch_user_meta um, ch_advisers a WHERE u.id=a.user_id AND u.id=um.user_id LIMIT '.$get_start.', 3';
        $recode = $this->db->queryPost($sql, array());
        $row = $this->db->dbFetch($recode);
        return $row;
    }
    public function getAdviser($id)
    {
        $sql = 'SELECT u.name, um.img, a.* FROM ch_users u, ch_user_meta um,  ch_advisers a WHERE u.id=:id AND u.id=um.user_id AND u.id=a.user_id';
        $data = array(':id' => $id);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        return $row[0];
    }
    public function registerAdviser($id)
    {
        $sql = 'INSERT INTO ch_advisers SET user_id=:id, created=NOW()';
        $data = array(':id' => $id);
        $this->db->queryPost($sql, $data);
        $sql = 'UPDATE ch_user_meta SET user_status=2 WHERE user_id=:id';
        $this->db->queryPost($sql, $data);
    }

    public function countBestAnsewer($user_id)
    {
        $sql = 'SELECT q.id, a.created AS ba FROM ch_questions q, ch_answers a WHERE a.user_id=:id AND q.best_ans_id=a.id';
        $data = array(':id' => $user_id);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        return count($row);
    }
}
