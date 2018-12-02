<?php


class programClass
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


    public function create($title,$schedule,
                           $description,$created_at,
                           $focus,$level,$duration,
                           $instructor_id)
    {
        $sql_new_program = "INSERT INTO programs VALUES" . "(null, '$title',
                                                        '$schedule','$description',
                                                        '$created_at','$focus',
                                                        '$level','$duration','$instructor_id',null,null,null)";
        $this->conn->query($sql_new_program);
    }

    public function update($id,$title,$schedule,$description,$created_at,$focus,$level,$duration,$instructor_id)
    {
        $sql_update_program = "UPDATE programs SET title='$title',
                          schedule='$schedule',
                          description='$description',
                          created_at='$created_at',
                          focus='$focus',
                          level='$level',
                          duration='$duration',
                          instructor_id='$instructor_id'
                          
                          WHERE id='$id'";
        $this->conn->query($sql_update_program);
    }

    public function delete($id){
        $sql_delete_video = "DELETE FROM programs WHERE id='$id'";
        $this->conn->query($sql_delete_video);
    }

    public function addToFavorite($user, $program)
    {
        $sql_add_favorite = "INSERT INTO `favorite-programs` VALUES" . "(null, '$user',
                                                        '$program')";
        $this->conn->query($sql_add_favorite);
    }

    public function deleteFromFavorites($user, $program){
        $sql_delete_favorite = "DELETE FROM `favorite-programs` WHERE user='$user' and program='$program'";
        $this->conn->query($sql_delete_favorite);
    }

}