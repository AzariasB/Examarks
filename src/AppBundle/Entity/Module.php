<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module {

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Assessment", mappedBy="module", cascade={"all"}, orphanRemoval=true) 
     */
    private $assessments;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Student", inversedBy="modules")
     * @ORM\JoinTable(name="students_modules")
     */
    private $students;

    public function __construct() {
        $this->assessments = new \Doctrine\Common\Collections\ArrayCollection;
        $this->students = new \Doctrine\Common\Collections\ArrayCollection;
    }

    /**
     * Average of the module for the given student
     * 
     * @param \AppBundle\Entity\Student $s
     */
    public function studentAverage(Student $s) {
        $total = 0;
        $assessments = 0;
        foreach ($this->assessments->toArray() as $assess) {
            $assessments += ($assess->getWeight() / 100);
            $total += $assess->hasStudentMark($s)->getCalculatedResult();
        }

        return $total / $assessments;
    }

    /**
     * Wether the student completed all the exams
     * 
     * @param \AppBundle\Entity\Student $s
     */
    public function studentCompleted(Student $s) {
        if (!$this->students->contains($s)) {
            return false;
        }

        foreach ($this->assessments->toArray() as $assess) {
            $mark = $assess->hasStudentMark($s);
            if (!$mark || !$mark->getValue()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Wether the student passed the module :
     * - completed all the exams and has more than 40% to all marks
     * 
     * @param \AppBundle\Entity\Student $s
     */
    public function studentPassed(Student $s) {
        if (!$this->studentCompleted($s)) {
            return false;
        }

        foreach ($this->assessments->toArray() as $assess) {
            $mark = $assess->hasStudentMark($s);
            if ($mark->getValue() < 40) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Module
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set abreviation
     *
     * @param string $abreviation
     *
     * @return Module
     */
    public function setAbbreviation($abreviation) {
        $this->abbreviation = $abreviation;

        return $this;
    }

    /**
     * Get abreviation
     *
     * @return string
     */
    public function getAbbreviation() {
        return $this->abbreviation;
    }

    /**
     * Get assessments
     * 
     * @return ArrayCollection
     */
    public function getAssessments() {
        return $this->assessments;
    }

    /**
     * Get students
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getStudents() {
        return $this->students;
    }

    /**
     * Set students
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwStudents
     */
    public function setStudents(\Doctrine\Common\Collections\ArrayCollection $nwStudents) {
        $this->students = $nwStudents;
    }

    /**
     * Set assessments
     * 
     * @param ArrayCollection $assessments
     */
    public function setAssessments(\Doctrine\Common\Collections\ArrayCollection $assessments) {
        $this->assessments = $assessments;
    }

}
