<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
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
    private $Date_Dachat;

    /**
     * @ORM\Column(type="float")
     */
    private $Grand_Total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LePanier", mappedBy="id_panier")
     */
    private $panier;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDachat(): ?\DateTimeInterface
    {
        return $this->Date_Dachat;
    }

    public function setDateDachat(String $Date_Dachat): self
    {
        $this->Date_Dachat = $Date_Dachat;

        return $this;
    }

    public function getGrandTotal(): ?float
    {
        return $this->Grand_Total;
    }

    public function setGrandTotal(float $Grand_Total): self
    {
        $this->Grand_Total = $Grand_Total;

        return $this;
    }

    /**
     * @return Collection|LePanier[]
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(LePanier $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier[] = $panier;
            $panier->setIdPanier($this);
        }

        return $this;
    }

    public function removePanier(LePanier $panier): self
    {
        if ($this->panier->contains($panier)) {
            $this->panier->removeElement($panier);
            // set the owning side to null (unless already changed)
            if ($panier->getIdPanier() === $this) {
                $panier->setIdPanier(null);
            }
        }

        return $this;
    }
}
