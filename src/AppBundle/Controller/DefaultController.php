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

class DefaultController extends SuperController {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        return $this->redirectToRoute('lobby');
    }

    /**
     * @Route("/home", name="lobby")
     * 
     * @param Request $req
     */
    public function lobbyAction(Request $req) {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_TEACHER")) {


            return $this->render('lobby/index-teacher.html.twig', [
                        'modules' => $this->getAllFromClass(\AppBundle\Entity\Module::class)
            ]);
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_STUDENT')) {
            $student = $this->getFromClass(\AppBundle\Entity\Student::class, ['login' => $this->getUser()->getUsername()]);
            return $this->render('lobby/index-student.html.twig', [
                        'student' => $student[0]
            ]);
        } else {
            //lobby 
        }
    }

}
