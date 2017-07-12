<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=255)
     */
    private $abbreviation;
    
    /**
     *
     * @var Assessment
     * 
     * @ORM\OneToMany(targetEntity="Assessment", mappedBy="module") 
     */
    private $assessments;

    public function __construct() {
        $this->assessments = new \Doctrine\Common\Collections\ArrayCollection;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Module
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set abreviation
     *
     * @param string $abreviation
     *
     * @return Module
     */
    public function setAbbreviation($abreviation)
    {
        $this->abbreviation = $abreviation;

        return $this;
    }

    /**
     * Get abreviation
     *
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }
    
    /**
     * Get assessments
     * 
     * @return ArrayCollection
     */
    public function getAssessments(){
        return $this->assessments;
    }
    
    /**
     * Set assessments
     * 
     * @param ArrayCollection $assessments
     */
    public function setAssessments(\Doctrine\Common\Collections\ArrayCollection $assessments){
        $this->assessments = $assessments;
    }
}

