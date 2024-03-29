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

namespace AppBundle\Entity\Survey;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Student;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Survey\SurveyRepository")
 */
class Survey {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var \AppBundle\Entity\Student
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\Student", inversedBy="survey")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     * @ORM\ManyToMany(targetEntity="Question",cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinTable(name="survey_questions",
     *      joinColumns={@ORM\JoinColumn(name="survey_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $questions;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    private function chooseRandom($array, $className) {
        $indexes = [];
        for ($i = 0; $i < count($array) / 2; $i++) {
            $randIndex = rand(0, count($array) - 1);
            if (array_key_exists($randIndex, $indexes)) {
                $i--;
            } else {
                $indexes[$randIndex] = true;
                $this->getQuestions()->add(new $className($randIndex));
            }
        }
    }

    public function generateQuestions() {
        $this->chooseRandom(Agreement::QUESTIONS, Agreement::class);
        $this->chooseRandom(Overall::QUESTIONS, Overall::class);
    }

    public function __construct() {
        $this->questions = new ArrayCollection;
    }

    /**
     * Get student
     * 
     * @return \AppBundle\Entity\Student
     */
    public function getStudent() {
        return $this->student;
    }

    /**
     * 
     * @param Student $s
     */
    public function setStudent(Student $s = null) {
        $this->student = $s;
    }

    /**
     * Get questions
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getQuestions() {
        return $this->questions;
    }

    /**
     * Set questions
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $questions
     */
    public function setQuestions(ArrayCollection $questions) {
        $this->questions = $questions;
    }

}
