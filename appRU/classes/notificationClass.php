<?php


class notificationClass
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


    public function read($notification)
    {
        $sql_read_notification = "UPDATE `notifications-booking` SET readed='1'
                          
                         WHERE id='$notification'";
        $this->conn->query($sql_read_notification);
    }

    public function delete($notification)
    {
        $sql_read_notification = "DELETE FROM `notifications-booking` WHERE id='$notification'";
        $this->conn->query($sql_read_notification);
    }


}
