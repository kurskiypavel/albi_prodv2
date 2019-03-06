<?php


class messageClass
{

//    public $id;
    /**
     * @var PDO
     */
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function readAll($author,$conversation)
    {
        $sql_read_messages = "UPDATE `messages` SET readed='1'
                          
                         WHERE author != '$author' and readed = 0 and conversation='$conversation'";
        $this->conn->query($sql_read_messages);
    }



}
