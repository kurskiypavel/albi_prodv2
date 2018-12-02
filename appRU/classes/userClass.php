<?php


class userClass
{

    /**
     * @var PDO
     */
    private $conn;
    public $first_name;
    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function update($id,$first_name,$last_name,$birthdate,$location,$email,$phone){
        $sql_new_info = "UPDATE users SET first_name='$first_name',
                                          last_name='$last_name',
                                          
                                          birthdate='$birthdate',
                                          location='$location',
                                          email='$email',
                                          phone='$phone'
                                          
                                          WHERE id='$id'";
        $this->conn ->query($sql_new_info);
    }

    public function updateAbout($id,$about){
        $sql_new_info = "UPDATE users SET about='$about'
                                          WHERE id='$id'";
        $this->conn ->query($sql_new_info);
    }

}