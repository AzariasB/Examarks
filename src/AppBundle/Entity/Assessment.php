<?php

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
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="assessments", cascade={"all"}) 
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     */
    private $module;

    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="assessment", cascade={"all"}) 
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

    public function __construct() {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection;
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

    public function isWrittenExam() {
        return $this->type == Assessment::WRITTEN_EXAM;
    }
    
    /**
     * Get name
     * 
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Set name
     * 
     * @param string $nwName
     */
    public function setName($nwName){
        $this->name = $nwName;
    }

    /**
     * Set exam date
     * 
     * @param \DateTime $nwexamDate
     */
    public function setExamDate(\DateTime $nwexamDate = null) {
        $this->examDate = $nwexamDate;
    }

    /**
     * Get exam date
     * 
     * @return \DateTime
     */
    public function getExamDate() {
        return $this->examDate;
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
