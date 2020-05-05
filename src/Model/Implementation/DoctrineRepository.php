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


    /************************** PROFILE INFORMATION *******************************/

    public function getUsrFollowers($user_id){
        //Get Followers
        $sql = "SELECT count(followerId) AS followers FROM following WHERE following.followedId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['followers'];
    }

    public function getUsrFollowing($user_id){
        $sql = "SELECT count(followerId) AS following FROM following WHERE following.followerId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['following'];
    }

    public function getUsrProjects($user_id){
        $projects = [];
        $sql = "SELECT p.*, l.city, l.state, l.country FROM project AS p, location AS l WHERE ownerId = '$user_id' AND l.locationId = p.locationId";

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
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country']
            );
        }

        return $projects;
    }

    public function getUsrCollaborations($user_id){
        $collabs = [];
        $sql = "SELECT pr.*, c.status AS collabstate FROM (SELECT p.*, u.username, l.city, l.state, l.country FROM project AS p, 
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
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'username' => $row['username'],
                'ownerId' => $row['ownerId'],
                'request' => $row['collabstate']
            );
        }
        return $collabs;
    }

    public function getUsrNumberProjects($user_id){
        $sql = "SELECT count(projectId) AS projects FROM project WHERE ownerId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $projects = $row['projects'];

        $sql = "SELECT count(collaborationId) AS collabs FROM collaboration WHERE userId = '$user_id' AND status = true";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $projects + $row['collabs'];
    }

    public function getUsrKnowledge($user_id){
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

        return $knowledge;
    }

    public function getUsrProfile($user_id)
    {
        //Get information about user
        $sql = "SELECT u.username,u.name,u.fsurname,u.lsurname, u.quote, u.about, l.city, l.state, 
        l.country FROM user AS u, location AS l WHERE u.locationId = l.locationId AND u.userId = '$user_id'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data['user'] = $row;

        $data['followers'] = $this->getUsrFollowers($user_id);

        $data['following'] = $this->getUsrFollowing($user_id);

        $data['projects'] = $this->getUsrProjects($user_id);

        $data['collabs'] = $this->getUsrCollaborations($user_id);

        $data['knowledge'] = $this->getUsrKnowledge($user_id);

        return $data;
    }

    public function getAllProfiles(){
        $profiles = [];

        $sql = "SELECT u.userId, u.username,u.name,u.fsurname,u.lsurname, u.quote, u.about, l.city, 
                l.state, l.country FROM user AS u, location AS l WHERE u.locationId = l.locationId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        /*$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row){
            $row['followers'] = $this->getUsrFollowers($row['userId']);
            $row['following'] = $this->getUsrFollowing($row['userId']);
            //$row['projects'] = $this->getUsrFollowing($row['userId']);
        }*/

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $profiles[] = array(
                'username' => $row['username'],
                'name' => $row['name'],
                'fsurname' => $row['fsurname'],
                'lsurname' => $row['lsurname'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'followers' => $this->getUsrFollowers($row['userId']),
                'following' => $this->getUsrFollowing($row['userId']),
                'projects'=> $this->getUsrNumberProjects($row['userId'])
            );
        }

        return $profiles;

    }

    /*********************** FOLLOW IMPLEMENTATIONS **********************************/

    public function followUser($userId, $followedId){
        $sql = "INSERT INTO following (followerId, followedId) VALUES ($userId,$followedId)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }

    public function followExists($userId, $followedId){
        $sql = "SELECT * FROM following WHERE followerId = '$userId' AND followedId = '$followedId'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['followingId']);
    }

    public function unfollowUser($userId, $followedId){
        $sql = "DELETE FROM following WHERE followerId = '$userId' AND followedId = '$followedId'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
    }



    /***********************   PROJECT IMPLEMENTATIONS  ******************************/

    public function getProjectIdFromPath($path, $user_id){
        $subfolders = explode('/', $path);
        $patter = 'null';

        foreach ($subfolders as $subfolder){
            if($patter == 'null') {
                $sql = "SELECT projectId FROM project WHERE patter IS null AND ownerId = $user_id AND title = '$subfolder'";
            }
            else{
                $sql = "SELECT projectId FROM project WHERE patter = $patter AND ownerId = $user_id AND title = '$subfolder'";
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $patter = $row['projectId'];
        }
        if($patter == ''){
            $patter = 'null';
        }
        return $patter;
    }

    public function getAllProjects(){
        $sql = "SELECT p.*, l.city, u.username FROM project as p, user as u, location as l WHERE p.ownerId = userId AND p.locationId = l.locationId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $projects;
    }

    public function getProject($projectId, $owner_id){
        $sql = "SELECT p.title, p.definition, p.createdAt, p.places, l.city, l.state, u.username 
        FROM project as p, user as u, location as l WHERE p.ownerId = '$owner_id' 
        AND p.locationId = l.locationId AND projectId = '$projectId';";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function createNewProject($project){
        //$title, $definition, $patter, $locationId, $places, $tag
        //FALTA AFEGIR LOCATION I TAG
        //$title, $definition, $patter, ownerId, $locationId, $places, $tag

        $sql = "INSERT INTO project (title, patter, definition, ownerId, createdAt, places, locationId) VALUES
        (:title,:patter, :definition, :ownerId, :createdAt, :places, :locationId)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue("title", $project->getTitle(),'string');
        $stmt->bindValue("patter", $project->getPatter(),PDO::PARAM_INT);
        $stmt->bindValue("definition", $project->getDefinition(),'string');
        $stmt->bindValue("ownerId", $project->getOwnerId(),PDO::PARAM_INT);
        $stmt->bindValue("createdAt", $project->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("places", $project->getPlaces(), PDO::PARAM_INT);
        $stmt->bindValue("locationId", $project->getLocationId(), PDO::PARAM_INT);
        $stmt->execute();

    }

    public function getContentsInProject($patter, $owner_id){

        $data['project'] = $this->getProject($patter, $owner_id);

        if($patter == 'null') {
            $sql = "SELECT * FROM project WHERE patter IS null AND ownerId = '$owner_id'";
        }
        else{
            $sql = "SELECT * FROM project WHERE patter = '$patter'";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $data['projects'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*$files = [];
        if($patter == 'null') {
            $sql = "SELECT * FROM file WHERE id_folder IS null AND id_owner = '$owner_id'";
        }
        else{
            $sql = "SELECT * FROM file WHERE id_folder = '$patter'";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch( PDO::FETCH_ASSOC )){
            $files[] = array('file_name'=>$row['real_name'], 'extension'=>$row['extension']);
        }*/

        //$data['files'] = $files;
        $data['isadmin'] = true;

        return $data;
    }

    public function getContentsInSharedFolder($patter, $owner_id){
        $folders = [];
        if($patter == 'null') {
            $sql = "SELECT f.name AS name, u.username AS owner  FROM folder AS f, user AS u WHERE u.id = f.id_owner AND patter IS null AND id_owner = '$owner_id'";
        }
        else{
            $sql = "SELECT f.name AS name, u.username AS owner  FROM folder AS f, user AS u WHERE u.id = f.id_owner AND patter = '$patter'";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch( PDO::FETCH_ASSOC )){
            $folders[] = array('folder_name'=>$row['name'], 'owner'=>$row['owner']);
        }
        $data['folders'] = $folders;

        $files = [];
        if($patter == 'null') {
            $sql = "SELECT * FROM file WHERE id_folder IS null AND id_owner = '$owner_id'";
        }
        else{
            $sql = "SELECT * FROM file WHERE id_folder = '$patter'";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch( PDO::FETCH_ASSOC )){
            $files[] = array('file_name'=>$row['real_name'], 'extension'=>$row['extension']);
        }

        $data['folders'] = $folders;
        $data['files'] = $files;

        return $data;
    }

    public function permissionsInFolder($user_id, $folder_id, $owner_id){
        // 0 = no permission
        // 1 = reader permission
        // 2 = admin permission

        if($owner_id == $user_id){ return 2; }

        $permission = 0;
        $trobat = false;
        while($folder_id != null && !$trobat) {
            $sql = "SELECT admin as isadmin FROM participate WHERE id_user = $user_id AND id_folder = $folder_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch( PDO::FETCH_ASSOC );
            if(isset($row['isadmin'])){
                $trobat = true;
                $permission = $row['isadmin']+1;
            }else{
                $sql = "SELECT patter FROM folder WHERE id_folder = $folder_id";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch( PDO::FETCH_ASSOC );
                $folder_id = $row['patter'];
            }
        }
        return $permission;
    }

    public function getFolderPathFromId($id_folder){
        $path = '';
        while($id_folder != null){
            $sql = "SELECT patter, name FROM folder WHERE id_folder = '$id_folder'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $path = "/".$row['name'].$path;
            $id_folder=$row['patter'];
        }
        return $path;
    }


    /*********************** FIELDS IMPLEMENTATIONS */

    public function getAllCategories(){
        $sql = "SELECT * FROM field WHERE tag = definition";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }


    /*********************** LOCATION IMPLEMENTATIONS *********************************/

    public function getAllLocations(){
        $sql = "SELECT * FROM location WHERE state = 'Catalonia' AND country = 'Spain'";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $locations;
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