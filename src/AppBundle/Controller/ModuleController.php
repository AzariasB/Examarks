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
 * Description of ModuleController
 *
 * @author azarias
 */
class ModuleController extends SuperController {
    //put your code here

    /**
     * 
     * @param integer $moduleId
     * @Route("/module/{moduleId}", name="module")
     *      */
    public function indexAction($moduleId) {
        $module = $this->getEntityFromId(Module::class, $moduleId);

        return $this->render('lobby/teacher/module.html.twig', [
                    'module' => $module
        ]);
    }

    /**
     * 
     * @param type $moduleId
     * @Route("/deleteModule/{moduleId}",name="deleteModule")
     */
    public function deleteAction($moduleId) {
        $moduleToDelete = $this->getEntityFromId(Module::class, $moduleId);
        $this->removeEntity($moduleToDelete);
        return $this->redirectToRoute('moduleList');
    }

    /**
     * 
     * @param type $moduleId
     * @param Request $req
     * @Route("/editModule/{moduleId}", name="editModule")
     */
    public function editAction($moduleId, Request $req) {
        $mod = $this->getEntityFromId(Module::class, $moduleId);

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

}
