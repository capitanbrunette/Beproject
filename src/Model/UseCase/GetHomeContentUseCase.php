<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 27/04/2020
 * Time: 22:14
 */

namespace BeProject\Model\UseCase;
use BeProject\Model\Interfaces\IDoctrineRepository;

class GetHomeContentUseCase
{
    private $repository;

    public function __construct(IDoctrineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke()
    {
        $data['projects'] = $this->repository->getAllProjects();
        $data['profiles'] = $this->repository->getAllProfiles();
        $data['tags'] = $this->repository->getAllCategories();
        $data['locations'] = $this->repository->getAllLocations();

        return  $data;
    }
}