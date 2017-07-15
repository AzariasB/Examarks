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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Module;
use AppBundle\Entity\Assessment;
use AppBundle\Entity\Student;

/**
 * Description of ModuleController
 *
 * @author azarias
 */
class ModuleController extends SuperController {
    //put your code here

    /**
     * 
     * @param Module $module
     * @Route("/module/{moduleId}", name="module")
     * @ParamConverter("module", class="AppBundle:Module", options={"id" = "moduleId"})
     */
    public function indexAction(Module $module) {
        return $this->render('lobby/teacher/module.html.twig', [
                    'module' => $module
        ]);
    }

    /**
     * 
     * @Route("/deleteModule/{id}",name="deleteModule")
     * @ParamConverter("mod", class="AppBundle:Module")
     */
    public function deleteAction(Module $moduleToDelete) {
        $this->removeEntity($moduleToDelete);
        return $this->redirectToRoute('moduleList');
    }

    /**
     * 
     * @param Request $req
     * @Route("/editModule/{id}", name="editModule")
     * @ParamConverter("mod", class="AppBundle:Module")
     */
    public function editAction(Module $mod, Request $req) {
        $form = $this->createForm(\AppBundle\Form\ModuleEditType::class, $mod);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveEntity($mod);
            return $this->redirectToRoute('module', ['moduleId' => $mod->getId()]);
        }

        return $this->render('lobby/teacher/module-edit.html.twig', [
                    'form' => $form->createView(),
                    'module' => $mod
        ]);
    }

    /**
     * 
     * @Route("/module/{id}/json", name="moduleStats")
     * @ParamConverter("module", class="AppBundle:Module")
     */
    public function statsAction(Module $module) {
        return new JsonResponse([
            'assessments' => $this->getAssessmentStats($module),
            'students' => $this->getStudentsStats($module)
        ]);
    }

    private function getAssessmentStats(Module $module) {
        $totalAssessments = $module->getAssessments()->count();

        $marked = $module->getAssessments()->filter(function(Assessment $s) {
                    return $s->allMarked();
                })->count();

        $unmarked = $totalAssessments - $marked;

        return [
            'Marked' => $marked,
            'Not marked yet' => $unmarked
        ];
    }

    private function getStudentsStats(Module $m) {
        $notCompleted = 0;
        $passed = 0;
        $failed = 0;

        foreach ($m->getStudents()->toArray() as $s) {
            if (!$m->studentCompleted($s)) {
                $notCompleted++;
            } elseif ($m->studentPassed($s)) {
                $passed++;
            } else {
                $failed++;
            }
        }

        return [
            'Not completed' => $notCompleted,
            'Passed' => $passed,
            'Failed' => $failed,
        ];
    }

}
