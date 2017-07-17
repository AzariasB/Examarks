<?php

namespace AppBundle\Entity\Survey;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherEffective
 *
 * @ORM\Table(name="survey_teacher_effective")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Survey\TeacherEffectiveRepository")
 */
class TeacherEffective
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return TeacherEffective
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }
}

