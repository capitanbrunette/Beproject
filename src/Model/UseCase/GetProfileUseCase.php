<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 24/04/2020
 * Time: 2:05
 */

namespace BeProject\Model\UseCase;


use BeProject\Model\Interfaces\IDoctrineRepository;

class GetProfileUseCase
{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($user)
    {
        $userid = $this->repository->getUsrIdFromUsername($user);
        $data = $this->repository->getUsrProfile($userid);

        return  $data;
    }
}