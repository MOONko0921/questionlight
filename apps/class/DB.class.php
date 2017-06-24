<?php
/*データベースクラス
2017.04.07*/

class DB
{


    const DB_HOST = 'mysql:dbname=chiebukuro_test;host=localhost;charset=utf8';
    const DB_USER = 'root';
    const DB_PASSWORD = 'root';
    const DB_OPTIONS = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    );

    private $dbh;

    public function __construct()
    {
        try{
            $this->dbh = new PDO(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_OPTIONS);
        }catch(PDOException $e){
            exit('missing connect to database'.$e->message());
        }
    }

    public function queryPost($sql, $data)
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($data);
        return $stmt;
    }

    public function dbFetch($recordSet)
    {
        $ret = array();
        while($row = $recordSet->fetch(PDO::FETCH_ASSOC)){
            $ret[] = $row;
        }
        return $ret;
    }
}
