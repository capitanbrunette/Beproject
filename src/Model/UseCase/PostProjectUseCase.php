<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 03/05/2020
 * Time: 22:47
 */

namespace BeProject\Model\UseCase;


use BeProject\Model\Interfaces\IDoctrineRepository;
use BeProject\Model\Project;

class PostProjectUseCase
{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $rawData,  $path, $username, $owner)
    {

        $id_owner = $this->repository->getUsrIdFromUsername($owner);
        $id_user = $this->repository->getUsrIdFromUsername($username);
        //$id_patter = $this->repository->getFolderIdFromPath($path, $id_owner);
        $now = new \DateTime('now');
        $project = new Project($rawData['prtitle'],$rawData['prdescription'],
            null, $id_owner, $rawData['prlocation'], $rawData['prplaces'], $rawData['prtag'], $now);
        //if(!$this->repository->folderExists($folder_name, $id_patter, $id_owner) && $this->repository->permissionsInFolder($id_user, $id_patter, $id_owner) == 2){
            $this->repository->createNewProject($project);
        //}
    }


}