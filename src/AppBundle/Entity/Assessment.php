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

    const ASSIGNMENT = "Assignemnt";
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
     * @var int
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
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="assessments") 
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     */
    private $module;

    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="assessment") 
     */
    private $marks;

    public function __construct() {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection;
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
     * @param integer $type
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
     * @return int
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
