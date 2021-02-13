<?php

namespace App\Entity;

use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainingRepository::class)
 */
class Training
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="trainings")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label_avance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label_expert;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLabelAvance(): ?string
    {
        return $this->label_avance;
    }

    public function setLabelAvance(?string $label_avance): self
    {
        $this->label_avance = $label_avance;

        return $this;
    }

    public function getLabelExpert(): ?string
    {
        return $this->label_expert;
    }

    public function setLabelExpert(?string $label_expert): self
    {
        $this->label_expert = $label_expert;

        return $this;
    }
}
