<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 18/04/2018
 * Time: 16:48
 */

namespace BeProject\Model\Implementation;

use PDO;
use BeProject\Model\Interfaces\IDoctrineRepository;
use Doctrine\DBAL\Connection;
use BeProject\Model\User;

class DoctrineRepository implements IDoctrineRepository{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    private $connection;

    function __construct(Connection $connection){
        $this->connection = $connection;
    }
    public function save(User $user){

        $sql = "INSERT INTO user (username, email, password, createdAt, updatedAt, validation) VALUES(:username, :email, :password, :createdAt, :updatedAt, :validation)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("username", $user->getUsername(),'string');

        $stmt->bindValue("email", $user->getEmail(),'string');

        $stmt->bindValue("password", md5($user->getPassword()),'string');

        $stmt->bindValue("createdAt", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updatedAt", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("validation", $user->getValidation(), 'string');
        $stmt->execute();

    }

    public function userExists($username){
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['username']);
    }

    public function emailExists($email){
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['email']);
    }

    public function getPwdByUsername(array $rawData){
        $aux = $rawData['user_name'];
        $sql = "SELECT * FROM user WHERE username = '$aux'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['password'];

    }

    public function getPwdByEmail(array $rawData){
        $aux = $rawData['user_name'];
        $sql = "SELECT * FROM user WHERE email = '$aux'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['password'];

    }


    public function getEmailFromUsername($username){
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['email'];
    }

    public function getUsrName(array $rawData){
        $aux = $rawData['user_name'];
        $sql = "SELECT * FROM user WHERE email = '$aux'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row == null){
            $aux = $rawData['user_name'];
            $sql = "SELECT * FROM user WHERE username = '$aux'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $row['username'];

    }

    public function getUsrIdFromUsername($username){
        $sql = "SELECT userId FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['userId'];
    }

    public function getUsrIdFromEmail($email){
        $sql = "SELECT userId FROM user WHERE email = '$email'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }


    public function getUsr( $data){
        
        $sql = "SELECT * FROM user WHERE username = '$data'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;

    }

    public function updateEmail($user_id, array $data){
        $sql = "UPDATE user SET email = '$data[email]' WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function updatePassword($user_id, array $data){
        $aux = md5($data['password']);
        $sql = "UPDATE user SET password = '$aux' WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }


    public function checkUser($user_id){
        $sql = "UPDATE user SET activated = TRUE WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }
    public function getValidationLink($user_id){
        $sql = "SELECT * FROM user WHERE username = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['validation'];
    }



    public function getUsrProfile($user_id)
    {
        //Get information about user
        $sql = "SELECT u.username,u.name,u.fsurname,u.lsurname, u.quote, u.about, l.neighborhood, l.city, l.state, 
        l.country FROM user AS u, location AS l WHERE u.locationId = l.locationId AND u.userId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['user'] = $row;

        //Get Followers
        $sql = "SELECT count(followerId) AS followers FROM following WHERE following.followedId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['followers'] = $row['followers'];

        //Get Following
        $sql = "SELECT count(followerId) AS following FROM following WHERE following.followerId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['following'] = $row['following'];


        //Get Projects
        $projects = [];
        $nprojects = 0;
        $sql = "SELECT p.*, l.neighborhood, l.city, l.state, l.country FROM project AS p, location AS l WHERE ownerId = '$user_id' AND l.locationId = p.locationId";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = array(
                'projectId' => $row['projectId'],
                'title' => $row['title'],
                'definition' => $row['definition'],
                'patter' => $row['patter'],
                'status' => $row['status'],
                'createdAt' => $row['createdAt'],
                'places' => $row['places'],
                'neighborhood' => $row['neighborhood'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country']
            );
            $nprojects = $nprojects + 1;
        }
        $data['nprojects'] = $nprojects;
        $data['projects'] = $projects;

        //Get Collaborations

        $collabs = [];
        $ncollabs = 0;
        $sql = "SELECT pr.*, c.status AS collabstate FROM (SELECT p.*, u.username, l.neighborhood, l.city, l.state, l.country FROM project AS p, 
        location AS l, user AS u WHERE l.locationId = p.locationId AND u.userId=p.ownerId) AS pr 
        INNER JOIN (SELECT c.projectId, c.status FROM collaboration AS c WHERE c.userId='$user_id') AS c WHERE pr.projectId = c.projectId;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $collabs[] = array(
                'projectId' => $row['projectId'],
                'title' => $row['title'],
                'definition' => $row['definition'],
                'patter' => $row['patter'],
                'status' => $row['status'],
                'createdAt' => $row['createdAt'],
                'places' => $row['places'],
                'neighborhood' => $row['neighborhood'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'username' => $row['username'],
                'ownerId' => $row['ownerId'],
                'request' => $row['collabstate']
            );
            $ncollabs = $ncollabs + 1;
        }
        $data['ncollabs'] = $ncollabs;
        $data['collabs'] = $collabs;

        $knowledge = [];
        $sql = "SELECT f.*, k.professional FROM knowledge AS k, field AS f WHERE k.userId = '$user_id' AND k.fieldId = f.fieldId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $knowledge[] = array(
                'tag' => $row['tag'],
                'definition' => $row['definition'],
                'professional' => $row['professional']
            );
        }
        $data['knowledge'] = $knowledge;

        return $data;
    }

    /***********************   DELETE IMPLEMENTATIONS  ******************************/



    public function deleteUsr($username){
        $sql = "DELETE FROM user WHERE username = '$username'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function deleteCollabsFromUsr($id)
    {
        $sql = "DELETE FROM collaboration WHERE userId ='$id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function getFilesToDelete()
    {
        $sql = "SELECT * FROM file_to_delete";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function deleteFTD()
    {
        $sql = "DELETE FROM file_to_delete";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function deleteFilesFromUsr($id)
    {
        $sql = "SELECT * FROM file WHERE ownerId = '$id'";
        $stmt = $this->connection->prepare($sql);

        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach ($rows as $row){
            $x = $row['storage_name'];
            var_dump($row);
            $sql = "INSERT INTO file_to_delete (storage_name) VALUES ('$x')";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }
        $sql = "DELETE FROM file WHERE ownerId ='$id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function deleteCollab($projectId)
    {
        $sql = "DELETE FROM collaboration WHERE projectId ='$projectId'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function deleteChildFiles($projectId)
    {
        $sql = "SELECT * FROM file WHERE projectId = '$projectId'";
        $stmt = $this->connection->prepare($sql);

        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach ($rows as $row){
            $x = $row['storage_name'];
            var_dump($row);
            $sql = "INSERT INTO file_to_delete (storage_name) VALUES ('$x')";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }

        $sql = "DELETE FROM file WHERE projectId ='$projectId'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function deleteProject($projectId)
    {
        var_dump($projectId);

        while($projectId != null){

            $sql = "SELECT *  FROM project WHERE patter = '$projectId'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT *  FROM file WHERE projectId = '$projectId'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($rows == null && $files == null){ //si no folders filles i no files fills delete folder
                $this->deleteCollab($projectId);
                $sql = "DELETE FROM project WHERE projectId = '$projectId'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                return;
            } else if($rows == null && $files != null) { //si no folders filles i si fitxers fills delete fitxers fills
                $this->deleteChildFiles($projectId);
                $sql = "DELETE FROM project WHERE projectId = '$projectId'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
            }else{
                foreach ($rows as $row){
                    //si hi ha carpetes filles delete participacions, fitxers fills i cridem la funció per les folders filles
                    $this->deleteChildFiles($projectId);
                    $this->deleteProject($row['projectId']);
                }
            }
        }
    }

    public function deleteNotificationsFromUsr($id)
    {
        $sql = "DELETE FROM notification WHERE userId ='$id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }



    public function deleteFile($fileId)
    {
        // TODO: Implement deleteFile() method.
    }

    public function deleteProjectsFromUsr($id)
    {
        // TODO: Implement deleteProjectsFromUsr() method.
    }




    /*****  THINGS FOR PROJECTS - OWNER *****/
    public function getRootProjectsForUser($id)
    {
        $sql = "SELECT * FROM project WHERE patter IS NULL AND ownerId = '$id' ";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;

    }
}