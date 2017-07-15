<?php

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
    public function lobbyAction(Request $req, \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder) {

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
