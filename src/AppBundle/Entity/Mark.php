<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mark
 *
 * @ORM\Table(name="mark")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarkRepository")
 */
class Mark implements \JsonSerializable {

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
     * @ORM\Column(name="value", type="integer", nullable=true)
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
     * @var Student
     * 
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="marks") 
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Returns the grade based on the mark's value
     */
    public function getGrade() {
        return Mark::toGrade($this->value);
    }

    public static function toGrade(int $num) {
        if ($num < 50) {
            return 'C-';
        }

        if ($num >= 50 && $num < 60) {
            return 'C';
        } else if ($num >= 60 && $num < 70) {
            return 'B';
        } else if ($num >= 70 && $num < 80) {
            return 'A';
        } else if ($num >= 80 && $num < 90) {
            return 'A+';
        } else if ($num > 90) {
            return 'A++';
        }
    }

    /**
     * Returns weight * value
     */
    public function getCalculatedResult() {
        return ($this->assessment->getWeight() / 100) * $this->value;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Mark
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Get assessment
     * 
     * @return Assessment
     */
    public function getAssessment() {
        return $this->assessment;
    }

    /**
     * Set assessment
     * 
     * @param \AppBundle\Entity\Assessment $nwAssessment
     */
    public function setAssessment(Assessment $nwAssessment) {
        $this->assessment = $nwAssessment;
    }

    /**
     * Get student
     * 
     * @return User
     */
    public function getStudent() {
        return $this->student;
    }

    /**
     * Set student
     * 
     * @param \AppBundle\Entity\User $user
     */
    public function setStudent(Student $user) {
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
