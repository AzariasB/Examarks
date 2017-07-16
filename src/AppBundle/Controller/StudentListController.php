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
use AppBundle\Form\StudentType;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\Form\Form;
use \AppBundle\Entity\Student;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of StudentListController
 *
 * @author azarias
 */
class StudentListController extends SuperController {

    /**
     * 
     * @param Request $req
     * @Route("/studentList", name="studentList")
     */
    public function indexAction() {
        return $this->render('lobby/teacher/student-list.html.twig', [
                    'users' => $this->getUsers()
        ]);
    }

    /**
     * @Route("/studentList/json", name="studnetListJson")
     */
    public function jsonAction() {
        return new JsonResponse([
            'users' => $this->getUsers()
        ]);
    }

    /**
     * 
     * @return array
     */
    private function getUsers() {
        $users = $this->getAllFromClass(Student::class);
        if ($this->isGranted('ROLE_ADMIN')) {
            $users = array_merge($users, $this->getAllFromClass(\AppBundle\Entity\Teacher::class));
        }
        return $users;
    }

    /**
     * 
     * 
     * @param Request $req
     * @return Response
     * @Route("/createStudent",name="createStudent")
     */
    public function createStudentAction(Request $req, UserPasswordEncoderInterface $encoder) {
        $s = new Student;

        $form = $this->createForm(StudentType::class, $s);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            return $this->createStudent($form, $s, $encoder);
        }
        return $this->newStudentForm($form);
    }

    private function createStudent(Form $form, Student $s, UserPasswordEncoderInterface $encoder) {
        if ($form->isValid()) {
            $s->setRoles(\AppBundle\Entity\User::ROLE_STUDENT | \AppBundle\Entity\User::ROLE_USER);
            $password = base64_encode(random_bytes(15));
            $s->setPassword($encoder->encodePassword($s, $password));

            $allModules = $this->getAllFromClass(\AppBundle\Entity\Module::class);
            $s->setModules(new \Doctrine\Common\Collections\ArrayCollection($allModules));
            $this->saveEntity($s);
            foreach ($allModules as $m) {
                $m->getStudents()->add($s);

                foreach ($m->getAssessments() as $assessm) {
                    $mark = new \AppBundle\Entity\Mark;
                    $mark->setAssessment($assessm);
                    $mark->setStudent($s);

                    $s->getMarks()->add($mark);
                    $assessm->getMarks()->add($mark);

                    $this->saveEntity($mark, false);
                    $this->mergeEntity($assessm, false);
                }


                $this->mergeEntity($m, false);
            }

            $this->mergeEntity($s);

            return new JsonResponse([
                'success' => true,
                'student' => $s,
                'password' => $password//TO REMOVE LATER (when sending an email)
            ]); //send mail to the studen with generated mail and password
        }

        return new JsonResponse([
            'success' => false,
            'newContent' => $this->newStudentForm($form)
        ]);
    }

    private function newStudentForm(Form $form) {
        return $this->render('lobby/teacher/new-student.html.twig', [
                    'form' => $form->createView()
        ]);
    }

}
