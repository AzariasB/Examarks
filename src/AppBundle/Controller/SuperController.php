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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of SuperController
 *
 * @author azarias
 */
class SuperController extends Controller {

    /**
     * Save entity in the database
     * 
     * @param Object $entity
     */
    protected function saveEntity(&$entity, $flush = true) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    /**
     * Merges the entity
     * 
     * @param type $entity
     */
    protected function mergeEntity(&$entity, $flush = true) {
        $em = $this->getDoctrine()->getManager();
        $em->merge($entity);
        if ($flush) {
            $em->flush();
        }
    }

    /**
     * Remove an entity from the database
     * 
     * @param Object $entity
     */
    protected function removeEntity($entity) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * Find the entity with the given
     * class name and the givne id
     * in the database
     * throw an error if not found
     * 
     * @param string $className
     * @param int $id
     * @return Object
     * @throws NotFoundHttpException
     */
    protected function getEntityFromId($className, $id) {
        $entity = $this->getDoctrine()->getManager()->find($className, $id);
        if (!$entity) {
            throw $this->createNotFoundException("Entity with id $id not found");
        }
        return $entity;
    }

    /**
     * Find all the entity
     * of a given class
     * 
     * @param string $className
     * @return array
     */
    protected function getAllFromClass($className) {
        return $this->getDoctrine()->getRepository($className)->findAll();
    }

    /**
     * Find the object with the given class name
     * the given predicate and the given ordering
     * 
     * @param string $className
     * @param array $predicate
     * @param array $ordering
     * @return array
     */
    protected function getFromClass($className, $predicate, $ordering = []) {
        return $this->getDoctrine()
                        ->getRepository($className)
                        ->findBy($predicate, $ordering);
    }

    /**
     * If the assertion if false
     * throw an exception with the given message
     * 
     * @param boolean $assertion
     * @param string $errorMessage
     * @throws Exception
     */
    protected function assert($assertion, $errorMessage = 'An error occured') {
        if (!$assertion) {
            throw new Exception($errorMessage);
        }
    }

    protected function assertAdmin($errorMessage = 'Only the admin can have access to it') {
        $this->assert($this->isGranted('ROLE_ADMIN'), $errorMessage);
    }

}
