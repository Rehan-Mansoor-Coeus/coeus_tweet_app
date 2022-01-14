<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=TweetRepository::class)
 */
class Tweet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User" , inversedBy="tweet")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $tweet;


    /**
     * @ORM\Column(type="text",nullable="true")
     */
    private $image;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('tweet', new Assert\NotBlank([
            'message' => 'Please Write Something',
        ]));

    }

    /**
     * @ORM\Column(type="date")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param user $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }


    public function getTweet(): ?string
    {
        return $this->tweet;
    }

    public function setTweet(string $tweet): self
    {
        $this->tweet = $tweet;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setimage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at->format('Y-m-d');
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }


}
