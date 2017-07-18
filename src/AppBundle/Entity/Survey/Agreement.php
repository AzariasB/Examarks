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

/**
 * Agreement
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Survey\AgreementRepository")
 */
class Agreement extends Question {

    //put your code here

    const STRONGLY_DISAGREE = 0;
    const DISAGREE = 1;
    const NEUTRAL = 2;
    const AGREE = 3;
    const STRONGRLY_AGREE = 4;
    const CHOICES = array(
        'Strongly disagree' => self::STRONGLY_DISAGREE,
        'Disagree' => self::DISAGREE,
        'Neutral' => self::NEUTRAL,
        'Agree' => self::AGREE,
        'Strongly agree' => self::STRONGRLY_AGREE
    );
    const QUESTIONS = array(
        'The course objectives were clear',
        'The course textbooks were clear and well written',
        'The assignments were appropriate for the level of this class',
        'The course increased my interest in the subject',
        'The course corresponded to my expectations',
        'The teacher demonstrated knowledge of the subject matter',
        'The teacher was effective in communicating the content of the course',
        'The teacher encouraged feedback from the class',
        'The teacher showed genuine concern for the students',
        'The teacher was enthusiastic about the course'
    );

    public function __construct($questionNumber) {
        $this->setQuestion($questionNumber);
    }

    public function questionString() {
        return self::QUESTIONS[$this->getQuestion()];
    }

}
