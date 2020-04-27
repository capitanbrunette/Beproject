<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 18/04/2018
 * Time: 16:26
 */


namespace BeProject\Model\Interfaces;

use BeProject\Model\User;

interface IDoctrineRepository
{
    public function save(User $user);

    public function getPwdByUsername(array $rawData);

    public function getPwdByEmail(array $rawData);

    public function getUsr($data);

    public function getUsrName(array $rawData);

    public function updateEmail($user_id, array $rawData);

    public function updatePassword($user_id, array $rawData);

    public function checkUser($user_id);

    public function getValidationLink($user_id);

    public function getUsrIdFromUsername($username);

    public function getUsrIdFromEmail($email);

    /*
     *
    public function emailExists($email);
    public function userExists($username);
    public function getEmailFromUsername($username);
    public function getNotifications($user_id);
    *
    */


/*****   DELETE USER SEQUENCE *****/

    public function deleteUsr($username);

    public function getFilesToDelete();

    public function deleteFTD();

    public function deleteCollabsFromUsr($id);

    public function deleteFilesFromUsr($id);

    public function deleteProjectsFromUsr($id);

    public function deleteCollab($projectId);

    public function deleteChildFiles($projectId);

    public function deleteProject($projectId);

    public function deleteFile($fileId);

    public function deleteNotificationsFromUsr($id);

/*****  THINGS FOR PROJECTS - OWNER *****/

    public function getRootProjectsForUser($id);


    /*public function createFolder($id_owner, $id_patter, $folder_name);

    public function folderExists($folder_name, $id_patter, $id_owner);

    public function getFolderIdFromPath($path, $user_id);

    public function getFolderPathFromId($id_folder);

    public function getContentsInFolder($patter, $owner_id);

    public function renameFolder($patter, $new_name, $oldname, $id_owner);

    public function getIdFromPatterAndName($id_patter, $folder_name);

    public function renameFile($id_patter, $new_file_name, $oldname, $id_owner);

    public function fileExists($new_file_name, $id_folder, $id_owner);

    public function getFileIdFromIdFolderAndRealName($id_patter, $file_name);

    public function getStorageNameFromId($id_file);

    public function getStorageName($id_owner, $id_patter, $real_name);

    public function getUserStorage($id_owner);

    public function createNotification($id_owner, $message);*/


    //SHARED FOLDERS

    /*public function permissionsInFolder($user_id, $folder_id, $owner_id);

    public function getSharedFoldersForUsr($user_id);

    public function getContentsInSharedFolder($patter, $owner_id);

    public function shareFolder($id_guest, $id_folder, $isadmin);

    public function uploadFile($id_owner, $id_folder, $real_name, $storage_name, $extension, $size);


    public function fileAlreadyExists($id_patter, $real_name, $id_owner);*/



}
