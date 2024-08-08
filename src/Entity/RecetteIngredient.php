<?php

namespace App\Entity;

use App\Repository\RecetteIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteIngredientRepository::class)]
class RecetteIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mesure = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recette $idRecette = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredients $idIngredient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMesure(): ?string
    {
        return $this->mesure;
    }

    public function setMesure(string $mesure): static
    {
        $this->mesure = $mesure;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIdRecette(): ?Recette
    {
        return $this->idRecette;
    }

    public function setIdRecette(?Recette $idRecette): static
    {
        $this->idRecette = $idRecette;

        return $this;
    }

    public function getIdIngredient(): ?Ingredients
    {
        return $this->idIngredient;
    }

    public function setIdIngredient(?Ingredients $idIngredient): static
    {
        $this->idIngredient = $idIngredient;

        return $this;
    }
}
