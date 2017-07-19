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
use AppBundle\Entity\Survey\Survey;
use AppBundle\Form\Survey\SurveyType;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Survey\Agreement;
use AppBundle\Entity\Survey\Overall;

/**
 * Description of SurveyController
 *
 * @author azarias
 */
class SurveyController extends SuperController {

    /**
     * @Route("/surveyResult", name="surveyResult")
     */
    public function surveyResultAction() {
        return $this->render('survey/result.html.twig');
    }

    /**
     * @Route("/surveyJson", name="surveyJson")
     */
    public function surveyJsonAction() {
        $agreements = $this->getAllFromClass(Agreement::class);
        $overalls = $this->getAllFromClass(Overall::class);

        $questions = [];
        $this->addResult($agreements, Agreement::class, $questions);
        $this->addResult($overalls, Overall::class, $questions);

        return new JsonResponse([
            'questions' => $questions
        ]);
    }

    private function addResult($questions, $class, &$total) {
        foreach ($questions as $q) {
            $qStr = $class::QUESTIONS[$q->getQuestion()];
            $qRating = array_search($q->getRating(), $class::CHOICES);
            if (!in_array($qStr, $total)) {
                $total[$qStr] = [];
                foreach ($class::CHOICES as $choice => $value) {
                    $total[$qStr][$choice] = 0;
                }
            }
            $total[$qStr][$qRating] ++;
        }
    }

    /**
     * 
     * @Route("/survey",)
     */
    public function indexAction() {
        if ($this->getUser()->didSurvey()) {
            return $this->redirectToRoute('lobby');
        }

        return $this->render('survey/survey.html.twig');
    }

    /**
     * 
     * @param Request $req
     * @Route("/surveyForm/json", name="surveyFormJson")
     */
    public function formJsonAction(Request $req) {
        $survey = new Survey();
        $survey->generateQuestions();

        $form = $this->createForm(SurveyType::class, $survey);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $survey->setStudent($this->getUser());
            $this->getUser()->setSurvey($survey);
            $this->saveEntity($survey);

            return new JsonResponse([
                'success' => true,
                'message' => 'Thank your for answering the survey'
            ]);
        }

        return new JsonResponse([
            'form' => $this->renderView('survey/form.html.twig', [
                'form' => $form->createView()
            ])
        ]);
    }

}
