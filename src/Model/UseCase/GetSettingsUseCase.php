<?php

namespace BeProject\Model\UseCase;

use  BeProject\Model\Interfaces\IDoctrineRepository;


class GetSettingsUseCase
{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($user)
    {
        $data = $this->repository->getUsr($user);

        return  $data;
    }

}