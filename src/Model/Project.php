<?php
/**
 * Created by PhpStorm.
 * User: ANGELA
 * Date: 01/05/2020
 * Time: 20:36
 */

namespace BeProject\Model;


class Project
{

    private $title;
    private $definition;
    private $patter;
    private $ownerId;
    private $locationId;
    private $places;
    private $tag;
    private $createdAt;

    /**
     * Project constructor.
     * @param $title
     * @param $definition
     * @param $patter
     * @param $locationId
     * @param $places
     * @param $tag
     */
    public function __construct($title, $definition, $patter, $ownerId, $locationId, $places, $tag, $createdAt)
    {
        $this->title = $title;
        $this->definition = $definition;
        $this->patter = $patter;
        $this->ownerId = $ownerId;
        $this->locationId = $locationId;
        $this->createdAt = $createdAt;
        $this->places = $places;
        $this->tag = $tag;

    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return mixed
     */
    public function getPatter()
    {
        return $this->patter;
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * @return mixed
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }




}