<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mark
 *
 * @ORM\Table(name="mark")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarkRepository")
 */
class Mark implements \JsonSerializable
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
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;
    
    /**
     * 
     * @var Assessment 
     * 
     * @ORM\ManyToOne(targetEntity="Assessment", inversedBy="marks") 
     * @ORM\JoinColumn(name="assessment_id", referencedColumnName="id")
     */
    private $assessment;
    
    /**
     *
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="marks") 
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;


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
     * Set value
     *
     * @param integer $value
     *
     * @return Mark
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Get assessment
     * 
     * @return Assessment
     */
    public function getAssessment(){
        return $this->assessment;
    }
    
    /**
     * Set assessment
     * 
     * @param \AppBundle\Entity\Assessment $nwAssessment
     */
    public function setAssessment(Assessment $nwAssessment){
        $this->assessment = $nwAssessment;
    }

    /**
     * Get student
     * 
     * @return User
     */
    public function getStudent(){
        return $this->student;
    }
    
    /**
     * Set student
     * 
     * @param \AppBundle\Entity\User $user
     */
    public function setStudent(User $user){
        $this->student = $user;
    }
    
    /**
     * Json serialization
     * 
     * @return array
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'assessmentId' => $this->assessment->getId(),
            'studentId' => $this->student->getId()
        ];
    }

}

