<?php

namespace AppBundle\Entity\Survey;

use Doctrine\ORM\Mapping as ORM;

/**
 * LastWords
 *
 * @ORM\Table(name="survey_last_words")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Survey\LastWordsRepository")
 */
class LastWords
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
     * @var string
     *
     * @ORM\Column(name="comments", type="text")
     */
    private $comments;


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
     * Set comments
     *
     * @param string $comments
     *
     * @return LastWords
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }
}

