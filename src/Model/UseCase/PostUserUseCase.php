<?php

namespace BeProject\Model\UseCase;

use BeProject\Model\User;
use BeProject\Model\Interfaces\IDoctrineRepository;



class PostUserUseCase{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;

    }

    public function __invoke(array $rawData, $validatestring)
    {
      /*if(!isset($rawData['username'])||!isset($rawData['password'])||
            !isset($rawData['confirm_password'])||!isset($rawData['email'])){
            throw new \Exception('Strange error!');
        }*/
        /*if($rawData['first_name']=='' || $rawData['birthdate']==''){
            throw new \Exception('Empty fields!');
        }*/
        if($rawData['password']!=$rawData['confirm_password'] || !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).+$/', $rawData['password'])
           ||  strlen($rawData['password']) < 6 || strlen($rawData['password']) > 12 ){
            throw new \Exception('Password error!');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $rawData['username']) || strlen($rawData['username']) > 20 ) {
            throw new \Exception('Username fields!');
        }

       if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $rawData['email'])) {
           throw new \Exception('Email fields!');
        }


        if ($this->repository->userExists($rawData['username'])){
            throw new \Exception('Username repeated!');
        }

        if ($this->repository->emailExists($rawData['email'])){
            throw new \Exception('Email repeated!');
        }

        $now = new \DateTime('now');
        $user = new User(
            null,
            $rawData['username'],
            $rawData['email'],
            $rawData['password'],
            $now,
            $now,
            $validatestring
        );

            $this->repository->save($user);

    }

}
