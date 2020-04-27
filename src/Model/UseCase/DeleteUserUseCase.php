<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 24/04/2020
 * Time: 0:16
 */

namespace BeProject\Model\UseCase;


use BeProject\Model\Interfaces\IDoctrineRepository;

class DeleteUserUseCase
{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke( $user_id){


        $id = $this->repository->getUsrIdFromUsername($user_id);

        $folders = $this->repository->getRootProjectsForUser($id);

        $this->repository->deleteCollabsFromUsr($id);
        $this->repository->deleteFilesFromUsr($id);
        $this->repository->deleteNotificationsFromUsr($id);

        foreach ($folders as $folder){
            $this->repository->deleteProject($folder['id_folder']);
        }

        $filestodelete = $this->repository->getFilesToDelete();


        $directory = '/home/vagrant/code/beproject/public/uploads';
        foreach ($filestodelete as $filetodelete){
            unlink($directory . '/'.$user_id.'/'.$filetodelete['storage_name']);
        }
        rmdir($directory . '/'.$user_id);
        $this->repository->deleteFTD();



        $this->repository->deleteUsr($user_id);

        unlink('/home/vagrant/code/beproject/public/assets/images/profiles/'.$user_id.'.jpg');

    }

}