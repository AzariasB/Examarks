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
 * Assessment
 *
 * @ORM\Table(name="assessment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AssessmentRepository")
 */
class Assessment implements \JsonSerializable {

    const ASSIGNMENT = "Assignement";
    const LAB_TEST = "Lab test";
    const WRITTEN_EXAM = "Written exam";

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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     *
     * @var Module
     * 
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="assessments", cascade={"persist"}) 
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     */
    private $module;

    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="assessment", cascade={"persist"}, orphanRemoval=true) 
     */
    private $marks;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="submissionDate" , type="datetime", nullable = true)
     */
    private $submissionDate;

    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="examDate", type="datetime", nullable = true) 
     */
    private $examDate;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255, nullable = true) 
     */
    private $name;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="room", type="string", length=255, nullable = true)
     */
    private $room;

    /**
     *
     * @var Assessment
     * 
     * @ORM\OneToOne(targetEntity="Assessment", inversedBy="resitParent", cascade={"all"})
     * @ORM\JoinColumn(name="resit_id", referencedColumnName="id")
     */
    private $resit;
    
    /**
     *
     * @var Assessment 
     * 
     * @ORM\OneToOne(targetEntity="Assessment", mappedBy="resit")
     */
    private $resitParent;


    public function __construct() {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection;
    }

    /**
     * Called to create the resit exam
     * Will add all the students who failed the
     * exam to the newly created assessment
     */
    public function createResit() {
        $this->resit = new Assessment;
        $this->resit->setResitParent($this);
        $this->resit->setType($this->type);
        $this->resit->setModule($this->module);
        $this->resit->setWeight($this->weight);
        
        foreach ($this->marks->toArray() as $m) {
            if ($m->isFailed()) {
                $nwMark = new Mark();
                $nwMark->setStudent($m->getStudent());
                $nwMark->setAssessment($this->resit);
                $m->getStudent()->getMarks()->add($nwMark);
                $this->resit->getMarks()->add($nwMark);
            }
        }
        
        return $this->resit;
    }


    /**
     * Get resit parent
     * 
     * @return Assessment
     */
    public function getResitParent(){
        return $this->resitParent;
    }
    
    
    /**
     * Set resit parent
     * 
     * @param \AppBundle\Entity\Assessment $resitParent
     */
    public function setResitParent(Assessment $resitParent){
        $this->resitParent = $resitParent;
    }
    
    /**
     * 
     * @return bool
     */
    public function isResit() {
        return $this->resitParent != null;
    }

    public function hasResit(){
        return $this->resit != null;
    }
    
    /**
     * 
     * @return Assessment
     */
    public function getResit() {
        return $this->resit;
    }

    /**
     * Set assessment
     * 
     * @param \AppBundle\Entity\Assessment $resit
     */
    public function setResit(Assessment $resit = null) {
        $this->resit = $resit;
    }

    /**
     * Wether all the students of this 
     * assessment have a mark
     * 
     * @return boolean
     */
    public function allMarked() {
        return $this->marks->forAll(function($i, Mark $m) {
                    return $m->getValue() != null;
                });
    }

    public function hasFailedMarks() {
        return $this->marks->exists(function($i, Mark $m){
            return $m->isFailed();
        });
    }

    /**
     * Returns the marks sorted by
     * their values
     * 
     * @return array
     */
    public function sortedMarks() {
        $arr = $this->marks->toArray();
        usort($arr, function(Mark $m1, Mark $m2) {
            if ($m1->getValue() == $m2->getValue()) {
                return strcmp($m1->getStudent()->getLogin(), $m2->getStudent()->getLogin());
            }
            if (!$m2->getValue() || $m1->getValue() > $m2->getValue()) {
                return -1;
            }
            return 1;
        });
        return $arr;
    }

    public function hasStudentMark(Student $stud) {
        foreach ($this->marks->toArray() as $myMark) {
            foreach ($stud->getMarks()->toArray() as $hisMarks) {
                if ($myMark->getId() == $hisMarks->getId()) {
                    return $hisMarks;
                }
            }
        }
        return false;
    }

    /**
     * 
     */
    public function validDates() {
        if ($this->examDate) {
            return $this->examDate > new \DateTime;
        }
        if ($this->submissionDate) {
            return $this->submissionDate > new \DateTime;
        }
        return true;
    }

    /**
     * Due date string
     */
    public function dueDate() {
        if ($this->isWrittenExam()) {
            $roomstr = $this->room ? " at room : " . $this->room : "";
            return $this->examDate ? "Exam the " . $this->examDate->format("dd/MM/yyyy HH:mm") . $roomstr : "";
        } else {
            return $this->submissionDate ? "Submission due the " . $this->submissionDate->format("d M Y \a\\t H:m") : "";
        }
    }

    /**
     * Set room
     * 
     * @param string $nwRoom
     */
    public function setRoom($nwRoom) {
        $this->room = $nwRoom;
    }

    /**
     * Get room
     * 
     * @return string
     */
    public function getRoom() {
        return $this->room;
    }

    /**
     * Wether this assessmentis a written exam
     * 
     * @return bool
     */
    public function isWrittenExam() {
        return $this->type == Assessment::WRITTEN_EXAM;
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
     * Set name
     * 
     * @param string $nwName
     */
    public function setName($nwName) {
        $this->name = $nwName;
    }

    /**
     * Set exam date
     * 
     * @param \DateTime $nwexamDate
     */
    public function setExamDate(\DateTime $nwexamDate = null) {
        $this->examDate = clone $nwexamDate;
    }

    /**
     * Get exam date
     * 
     * @return \DateTime
     */
    public function getExamDate() {
        return clone $this->examDate;
    }

    /**
     * Get submission date
     * 
     * @return \DateTime
     */
    public function getSubmissionDate() {
        return $this->submissionDate;
    }

    /**
     * Set submission date
     * 
     * @param \DateTime $nwsubmission
     */
    public function setSubmissionDate(\DateTime $nwsubmission = null) {
        $this->submissionDate = $nwsubmission;
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
     * Set type
     *
     * @param string $type
     *
     * @return Assessment
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Assessment
     */
    public function setWeight($weight) {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * Get module
     * 
     * @return Module
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * Set module
     * 
     * @param Module $nwModule
     */
    public function setModule(Module $nwModule) {
        $this->module = $nwModule;
    }

    /**
     * Get marks
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMarks() {
        return $this->marks;
    }

    /**
     * Set marks
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwmarks
     */
    public function setMarks(\Doctrine\Common\Collections\ArrayCollection $nwmarks) {
        $this->marks = $nwmarks;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'weigth' => $this->weight,
            'moduleId' => $this->module->getId()
        ];
    }

}
