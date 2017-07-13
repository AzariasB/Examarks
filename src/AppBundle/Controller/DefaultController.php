<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends SuperController {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/home", name="lobby")
     * 
     * @param Request $req
     */
    public function lobbyAction(Request $req) {

        if ($this->get("security.authorization_checker")->isGranted("ROLE_TEACHER") ) {
            return $this->render('lobby/index-teacher.html.twig', [
                'modules' => $this->getAllFromClass(\AppBundle\Entity\Module::class)
            ]);
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_STUDENT')) {
            return $this->render('lobby/index-student.html.twig', [
            ]);
        } else {
            //lobby 
        }
    }

}
