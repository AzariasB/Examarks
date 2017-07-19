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
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="student", cascade={"all"}) 
     */
    private $marks;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Module", mappedBy="students", cascade={"persist"})
     */
    private $modules;

    /**
     *
     * @var Survey\Survey
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Survey\Survey", mappedBy="student", )
     */
    private $survey;

    /**
     * Set survey
     * 
     * @param \AppBundle\Entity\Survey\Survey $s
     */
    public function setSurvey(Survey\Survey $s = null) {
        $this->survey = $s;
    }

    /**
     * Get survey
     * 
     * @return Survey\Survey
     */
    public function getSurvey() {
        return $this->survey;
    }

    public function didSurvey() {
        return $this->survey != null;
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
     * Set modules
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwModules
     */
    public function setModules(\Doctrine\Common\Collections\ArrayCollection $nwModules) {
        $this->modules = $nwModules;
    }

    /**
     * 
     * Get modules
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getModules() {
        return $this->modules;
    }

    public function __construct() {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection;
        $this->modules = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function completedOneModule() {
        return $this->modules->exists(function($i, Module $m) {
                    return $m->studentCompleted($this);
                });
    }

    /**
     * Set marks
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $nwMarks
     */
    public function setMarks(\Doctrine\Common\Collections\ArrayCollection $nwMarks) {
        $this->marks = $nwMarks;
    }

    /**
     * Remove module from list of modules
     * 
     * @param \AppBundle\Entity\Module $m
     */
    public function removeModule(Module $m) {
        $this->modules->removeElement($m);
    }

    /**
     * Add module to list of modules
     * 
     * @param \AppBundle\Entity\Module $m
     */
    public function addModule(Module $m) {
        return $this->modules->add($m);
    }

    public function jsonSerialize() {
        $parent = parent::jsonSerialize();
        $mMarks = [];
        foreach ($this->marks as $m) {
            $mMarks[] = $m->jsonSerialize();
        }
        $parent['marks'] = $mMarks;

        return $parent;
    }

}
