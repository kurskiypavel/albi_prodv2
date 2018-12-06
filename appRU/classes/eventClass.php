<?php


class eventClass
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


    public function createGroupEvent($group_event_id, $program,
                                     $student, $instructor,
                                      $comment,
                                     $confirmed, $canceled)
    {
        $sql_new_group_event = "INSERT INTO events VALUES" . "(null,'$group_event_id',
                                                        '$program','$student','$instructor',null,null,
                                                        '$comment','0',
                                                        '$confirmed','$canceled',1)";
        $this->conn->query($sql_new_group_event);

    }

    public function createPrivateEvent($student, $instructor,
                                       $date, $time, $comment,
                                       $confirmed, $canceled,$repeatable)
    {
        $sql_new_event = "INSERT INTO events VALUES" . "(null,null,
                                                        null,'$student','$instructor',
                                                        '$date','$time','$comment','1',
                                                        '$confirmed','$canceled','$repeatable')";
        $this->conn->query($sql_new_event);
    }

    public function updateGroup( $id, $group_event_id, $comment)
    {
        $sql_update_event = "UPDATE events SET group_event_id='$group_event_id',
                          
                         comment='$comment'
                         WHERE id='$id'";
        $this->conn->query($sql_update_event);
    }

    public function updatePrivate($date, $time, $id, $comment)
    {
        $sql_update_event = "UPDATE events SET date='$date',
                         time='$time',
                         comment='$comment',
                         confirmed='0'
                         WHERE id='$id'";
        $this->conn->query($sql_update_event);
    }

    public function delete($id)
    {
        $sql_delete_event = "DELETE FROM events WHERE id='$id'";
        $this->conn->query($sql_delete_event);
    }

    public function confirm($id)
    {
        $sql_update_event = "UPDATE events SET confirmed='1'
                        WHERE id='$id'";
        $this->conn->query($sql_update_event);
    }


}
