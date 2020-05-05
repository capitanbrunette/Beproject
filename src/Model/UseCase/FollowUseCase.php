<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 04/05/2020
 * Time: 10:09
 */

namespace BeProject\Model\UseCase;

use BeProject\Model\Interfaces\IDoctrineRepository;



class FollowUseCase
{
    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($username, $followed, $follow)
    {
        $userId = $this->repository->getUsrIdFromUsername($username);
        $followedId = $this->repository->getUsrIdFromUsername($followed);
        if($follow){
            if(!$this->repository->followExists($userId, $followedId)){
                $this->repository->followUser($userId, $followedId);
            }
        }else{
            if($this->repository->followExists($userId, $followedId)){
                $this->repository->unfollowUser($userId, $followedId);
            }
        }
    }






}