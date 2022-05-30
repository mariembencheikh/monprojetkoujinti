<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entree;

    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dessert;

 

    

    /**
     * @ORM\Column(type="text")
     */
    private $recetteEntree;

    /**
     * @ORM\Column(type="text")
     */
    private $recettePrincipale;

    /**
     * @ORM\Column(type="text")
     */
    private $recetteDessert;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $platPrincipale;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntree(): ?string
    {
        return $this->entree;
    }

    public function setEntree(string $entree): self
    {
        $this->entree = $entree;

        return $this;
    }

   

    public function getDessert(): ?string
    {
        return $this->dessert;
    }

    public function setDessert(string $dessert): self
    {
        $this->dessert = $dessert;

        return $this;
    }

    

    

    public function getRecetteEntree(): ?string
    {
        return $this->recetteEntree;
    }

    public function setRecetteEntree(string $recetteEntree): self
    {
        $this->recetteEntree = $recetteEntree;

        return $this;
    }

    public function getRecettePrincipale(): ?string
    {
        return $this->recettePrincipale;
    }

    public function setRecettePrincipale(string $recettePrincipale): self
    {
        $this->recettePrincipale = $recettePrincipale;

        return $this;
    }

    public function getRecetteDessert(): ?string
    {
        return $this->recetteDessert;
    }

    public function setRecetteDessert(string $recetteDessert): self
    {
        $this->recetteDessert = $recetteDessert;

        return $this;
    }

    public function getPlatPrincipale(): ?string
    {
        return $this->platPrincipale;
    }

    public function setPlatPrincipale(string $platPrincipale): self
    {
        $this->platPrincipale = $platPrincipale;

        return $this;
    }
}
