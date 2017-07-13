<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student extends User {

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="student") 
     */
    private $marks;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Module", mappedBy="students")
     */
    private $modules;

    /**
     * Get marks
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMarks() {
        return $this->marks;
    }

    /**
     * Set modules
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwModules
     */
    public function setModules(\Doctrine\Common\Collections\ArrayCollection $nwModules) {
        $this->modules = $nwModules;
    }

    /**
     * 
     * Get modules
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getModules() {
        return $this->modules;
    }

    public function __construct() {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection;
        $this->modules = new \Doctrine\Common\Collections\ArrayCollection;
    }

    /**
     * Set marks
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwMarks
     */
    public function setMarks(\Doctrine\Common\Collections\ArrayCollection $nwMarks) {
        $this->marks = $nwMarks;
    }

    public function jsonSerialize() {
        $parent = parent::jsonSerialize();
        $mMarks = [];
        foreach ($this->marks as $m) {
            $mMarks[] = $m->jsonSerialize();
        }
        $parent['marks'] = $mMarks;

        return $parent;
    }

}
