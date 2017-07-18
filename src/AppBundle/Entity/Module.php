<?php

/*
 * The MIT License
 *
 * Copyright 2017 azarias.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module implements \JsonSerializable {

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
     *
     * @var int
     * 
     * @ORM\Column(name="number", type="integer", nullable = true) 
     */
    private $number;

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
     * @ORM\OneToMany(targetEntity="Assessment", mappedBy="module", cascade={"persist"}, orphanRemoval=true) 
     */
    private $assessments;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Student", inversedBy="modules", cascade={"persist"})
     * @ORM\JoinTable(name="students_modules")
     */
    private $students;

    public function __construct() {
        $this->assessments = new \Doctrine\Common\Collections\ArrayCollection;
        $this->students = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function studentAverageToCSs(Student $s) {
        $avg = $this->studentAverage($s);
        return $avg != '/' ? $avg > 40 ? $avg >= 80 ? "success" : "primary" : "warning" : "";
    }

    /**
     * 
     * @return int
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * 
     * @param int $nwNumber
     */
    public function setNumber($nwNumber) {
        $this->number = $nwNumber;
    }

    /**
     * 
     * @param \AppBundle\Entity\Student $s
     * @return array
     */
    public function studentMarks(Student $s) {
        $res = [];
        foreach ($this->assessments as $assess) {
            $mark = $assess->hasStudentMark($s);
            if ($mark != false && $mark) {
                $res[] = $mark;
            }
        }
        return $res;
    }

    /**
     * 
     * @param \AppBundle\Entity\Student $s
     * @return Student
     */
    public function removeStudent(Student $s) {
        return $this->students->removeElement($s);
    }

    /**
     * Adds the student to the list of students
     * 
     * @param \AppBundle\Entity\Student $s
     * @return boolean
     */
    public function addStudent(Student $s) {
        return $this->students->add($s);
    }

    /**
     * 
     * @param \AppBundle\Entity\Student $s
     */
    public function addUniqueStudent(Student $s) {
        if (!$this->students->contains($s)) {
            $this->students->add($s);
            foreach ($this->assessments->toArray() as $assess) {
                $mark = new Mark();
                $mark->setAssessment($assess);
                $mark->setStudent($s);

                $s->getMarks()->add($mark);
                $assess->getMarks()->add($mark);
            }
            return true;
        }
        return false;
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
            $mark = $assess->hasStudentMark($s);
            if ($mark && $mark->getValue()) {
                $assessments += ($assess->getWeight() / 100);
                $total += $mark->getCalculatedResult();
            }
        }
        if ($assessments == 0) {
            return '/';
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
            if($assess->isResit()){
               continue; 
            }
            
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

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'students' => $this->students->toArray(),
            'assessments' => $this->assessments->toArray()
        ];
    }

}
