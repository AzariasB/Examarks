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
     * Get marks
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMarks(){
        return $this->marks;
    }
    
    /**
     * Set marks
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwMarks
     */
    public function setMarks(\Doctrine\Common\Collections\ArrayCollection $nwMarks){
        $this->marks = $nwMarks;
    }
    
    public function jsonSerialize() {
        $parent = parent::jsonSerialize();
        $mMarks = [];
        foreach($this->marks as $m){
            $mMarks[] = $m->jsonSerialize();
        }
        $parent['marks'] = $mMarks;
        
        return $parent;
    }
}
