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
use AppBundle\Entity\Survey\Overall;
use AppBundle\Entity\Survey\Agreement;
use AppBundle\Form\Survey\AgreementType;
use AppBundle\Form\Survey\OverallType;

/**
 * Description of SurveyController
 *
 * @author azarias
 */
class SurveyController extends SuperController {

    /**
     * 
     * @Route("/survey",)
     */
    public function indexAction(Request $req) {
        $agreement = new Overall(1);

        $agreements = [];
        $overalls = [];

        $forms = [];

        for ($i = 0; $i < count(Agreement::QUESTIONS) / 2; $i++) {
            $randAgreemen = rand(0, count(Agreement::QUESTIONS) - 1);
            if (array_key_exists($randAgreemen, $agreements)) {
                $i--;
            } else {
                $agreements[$randAgreemen] = Agreement::QUESTIONS[$randAgreemen];
                $forms[] = $this->createForm(AgreementType::class, $agreement[$randAgreemen]);
            }
        }

        for ($i = 0; $i < count($overalls) / 2; $i++) {
            $randO = rand(0, count(Overall::QUESTIONS) - 1);
            if(array_key_exists($randO, $overalls)){
                $i--;
            }else{
                $overalls[$randO] = Agreement::QUESTIONS[$randO];
//                $forms[] = $this->createForm(OverallType::class, $)
            }
        }

        $form = $this->createForm(OverallType::class, $agreement);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('lobby');
        }

        return $this->render('survey/agreement.html.twig', [
                    'forms' => array_map(function(Form $f) {
                                return $f->createView();
                            }, $forms)
        ]);
    }

}
