<?php

namespace AppBundle\Entity\Survey;

use Doctrine\ORM\Mapping as ORM;

/**
 * IncreasedInterest
 *
 * @ORM\Table(name="survey_increased_interest")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Survey\IncreasedInterestRepository")
 */
class IncreasedInterest
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
     * @return IncreasedInterest
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

