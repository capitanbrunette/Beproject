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

    public function deleteUsr($data);

    public function getUsrName(array $rawData);

    public function updateEmail($user_id, array $rawData);

    public function updatePassword($user_id, array $rawData);

    public function checkUser($user_id);

    public function getValidationLink($user_id);

    public function getUsrIdFromUsername($username);

    public function getUsrIdFromEmail($email);



}
