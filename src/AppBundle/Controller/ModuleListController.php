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
use AppBundle\Form\ModuleType;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\Form\Form;
use AppBundle\Entity\Module;

/**
 * Controller for the list of module page
 *
 * @author azarias
 */
class ModuleListController extends SuperController {

    /**
     * @Route("/moduleList",name="moduleList")
     */
    public function indexAction() {
        return $this->render('lobby/teacher/module-list.html.twig', [
                    'modules' => $this->getAllFromClass(\AppBundle\Entity\Module::class)
        ]);
    }

    /**
     * @Route("/createModule",name="createModule")
     */
    public function createModuleAction(Request $req) {
        $module = new Module;

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->createModule($form, $module);
        }

        return $this->createModuleForm($form);
    }

    private function createModule(Form $form, Module $m) {
        if ($form->isValid()) {

            foreach ($m->getAssessments() as $a) {
                $a->setModule($m);
                $this->saveEntity($a);
            }

            //For now, set all students to attend to the module
            $students = $this->getAllFromClass(\AppBundle\Entity\Student::class);
            $m->setStudents(new \Doctrine\Common\Collections\ArrayCollection($students));
            foreach ($students as $stud) {
                $stud->getModules()->add($m);
                $this->mergeEntity($stud, false);
            }

            $this->mergeEntity($m);

            return new JsonResponse([
                'success' => true,
                'module' => $m
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'newContent' => $this->createModuleForm($form)
        ]);
    }

    private function createModuleForm(Form $form) {
        return $this->render('lobby/teacher/new-module.html.twig', [
                    'form' => $form->createView()
        ]);
    }

}
