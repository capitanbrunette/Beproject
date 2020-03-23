<?php



namespace BeProject\Model\UseCase;

use BeProject\Model\Interfaces\IDoctrineRepository;


class SignUserIn{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $rawData)
    {
        $psswd = $this->repository->getPwdByUsername($rawData);


        
        if ($psswd != md5($rawData['user_password']) || $psswd == null){

            $psswd = $this->repository->getPwdByEmail($rawData);

            if ($psswd != md5($rawData['user_password']) || $psswd == null){
                throw new \Exception('foo!');
            }
            
            
        }
        return $this->repository->getUsrName($rawData);
    }
}