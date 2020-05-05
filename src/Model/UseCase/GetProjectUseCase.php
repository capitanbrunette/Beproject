<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 04/05/2020
 * Time: 16:45
 */

namespace BeProject\Model\UseCase;


use BeProject\Model\Interfaces\IDoctrineRepository;

class GetProjectUseCase
{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($username, $owner,$path)
    {

        $ownerId = $this->repository->getUsrIdFromUsername($owner);
        $projectId = $this->repository->getProjectIdFromPath($path, $ownerId);
        if($username == $owner){
            $data = $this->repository->getContentsInProject($projectId, $ownerId);
        }else{
            /*$user_id = $this->repository->getUsrIdFromUsername($username);
            $permission = $this->repository->permissionsInFolder($user_id, $folder_id, $ownerId);
            if( $permission != 0){
                $data = $this->repository->getContentsInSharedFolder($folder_id, $user_id);
                $data['isadmin'] = (bool)($permission-1);
            }else{
                throw new \Exception('Forbidden');
            }*/
        }

        return  $data;
    }


}