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

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Assessment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Description of AssessmentController
 *
 * @author azarias
 */
class AssessmentController extends SuperController {

    /**
     * 
     * @param \AppBundle\Entity\Assessment $assessment
     * 
     * @Route("/assessment/{assessmentId}",name="assessment")
     * @ParamConverter("assessment", class="AppBundle:Assessment", options={"id" = "assessmentId"})
     */
    public function indexAction(Assessment $assessment, Request $req) {
        if ($this->isGranted('ROLE_TEACHER')) {
            $form = $this->createForm(\AppBundle\Form\AssessmentMarksType::class, $assessment);

            $form->handleRequest($req);

            if ($form->isSubmitted()) {
                if (!$assessment->validDates()) {
                    $form->addError(new \Symfony\Component\Form\FormError("Dates are invalid"));
                }

                if ($form->isValid()) {
                    $this->mergeEntity($assessment);
                }
            }

            return $this->render('lobby/teacher/assessment.html.twig', [
                        'assessment' => $assessment,
                        'form' => $form->createView()
            ]);
        } else {
            return $this->render('lobby/student/assessment.html.twig', [
                        'assessment' => $assessment
            ]);
        }
    }

    /**
     * 
     * @param Assessment $assessment
     * @Route("/assessment/{id}/json",name="assessmentJson")
     * @ParamConverter("assessment", class="AppBundle:Assessment")
     */
    public function jsonAction(Assessment $assessment) {
        return new \Symfony\Component\HttpFoundation\JsonResponse([
            'grades' => $this->getGrades($assess),
            'students' => $this->getStudents($assess)
        ]);
    }

    
    private function getGrades(Assessment $assess){
        $res = [];
        foreach($assess->getMarks() as $mark){
            
        }
        
        return $res;
    }
    
    private function getStudents(Assessment $assess){
        return [];
    }
}
