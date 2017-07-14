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
use \AppBundle\Entity\Student;

/**
 * Description of UserController
 *
 * @author azarias
 */
class UserController extends SuperController {

    /**
     * @Route("/student/{studentId}",name="student")
     */
    public function studentProfileAction($studentId) {




        $stud = $this->getEntityFromId(Student::class, $studentId);

        return $this->render('default/student-profile.html.twig', [
                    'student' => $stud
        ]);
    }

    /**
     * 
     * @param int $teacherId
     * @Route("/teacher/{teacherId}",name="teacher")
     */
    public function teacherProfileAction($teacherId) {


        $teacher = $this->getEntityFromId(\AppBundle\Entity\Teacher::class, $teacherId);

        return $this->render('default/teacher-profile', [
                    'teacher' => $teacher
        ]);
    }

    /**
     * 
     * @param type $studentId
     * 
     * @Route(path="/editStudent/{studentId}", name="editStudent")
     */
    public function editStudentAction($studentId, Request $req) {
        $stud = $this->getEntityFromId(Student::class, $studentId);

        $form = $this->createForm(\AppBundle\Form\StudentEditType::class, $stud);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveEntity($stud);
            return $this->render('default/student-profile.html.twig', [
                        'student' => $stud
            ]);
        }

        return $this->render('default/student-edit.html.twig', [
                    'form' => $form->createView(),
                    'student' => $stud
        ]);
    }

    /**
     * 
     * @param int $studentId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * 
     * @Route("/studentModules/{studentId}",name="studentModules")
     */
    public function getStudentModules($studentId) {
        $stud = $this->getEntityFromId(Student::class, $studentId);

        return new \Symfony\Component\HttpFoundation\JsonResponse([
            'modules' => $stud->getModules()->toArray()
        ]);
    }

    /**
     * Removes the student from the given module
     * also removes all the marks of the modules from the user
     * 
     * @param int $studentId
     * @param int $moduleId
     * 
     * @Route("/removeStudentFromModule/{studentId}/{moduleId}", name="removeStudentFromModule")
     */
    public function removeStudentFromModule($studentId, $moduleId) {
        $module = $this->getEntityFromId(\AppBundle\Entity\Module::class, $moduleId);
        $stud = $this->getEntityFromId(Student::class, $studentId);

        $stud->removeModule($module);
        $module->removeStudent($stud);

        $marks = $module->studentMarks($stud);

        foreach ($marks as $ma) {
            $this->removeEntity($ma, false);
        }

        $this->mergeEntity($stud, false);
        $this->mergeEntity($module);

        return new \Symfony\Component\HttpFoundation\JsonResponse([
            'success' => true,
            'message' => 'Succesfully removed student from module'
        ]);
    }

}
