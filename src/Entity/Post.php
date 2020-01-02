<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as DateTimeType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_alias;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUrlAlias(): ?string
    {
        return $this->url_alias;
    }

    public function setUrlAlias(string $url_alias): self
    {
        $this->url_alias = $url_alias;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): \DateTime
    {
        return $this->published;
    }

    public function setPublished(\DateTime $published): self
    {
        $this->published = $published;

        return $this;
    }

    // check that forms is well filled or not
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('titre', new NotBlank());
        $metadata->addPropertyConstraint('url_alias', new NotBlank());
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'url_alias',
        ]));
        $metadata->addPropertyConstraint('content', new NotBlank());
        $metadata->addPropertyConstraint(
            'published',
            new Type(\DateTime::class)
        );
    }
}
