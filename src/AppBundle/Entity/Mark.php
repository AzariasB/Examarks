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

    public function isPassed() {
        return $this->value && $this->value >= 40;
    }

    public function isFailed() {
        return $this->value && $this->value < 40;
    }

    public function toCssClass() {
        return $this->value ? $this->value > 20 ? $this->isPassed() ? $this->value >= 80 ? "success" : "primary" : "warning" : "danger" : "";
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function getMarkStr() {
        return $this->value ? $this->value : "Not marked";
    }

    /**
     * Returns the grade based on the mark's value
     */
    public function getGrade() {
        return $this->value ? Mark::toGrade($this->value) : 'Not graded';
    }

    public static function toGrade($num) {
        if ($num < 40) {
            return 'F';
        }

        if ($num >= 40 && $num < 50) {
            return 'D';
        } else if ($num >= 50 && $num < 60) {
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
