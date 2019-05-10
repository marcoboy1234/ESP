<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RabaisRepository")
 */
class Rabais
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan("today", message="La date est invalide")
     */
    private $Date_De_Debut;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan("today", message="La date est invalide")
     */
    private $Date_De_Fin;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThan(76, message="Vous de mettre une valeur plus petite que 76")
     * @Assert\GreaterThan(9, message="Vous devez mettre une valeur plus haute que 9")
     */
    private $Rabais;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Employe = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="Rabais")
     */
    private $rabaisProduit;

    /**
     * @ORM\Column(type="float")
     */
    private $NouveauPrix;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeDebut(): ?\DateTimeInterface
    {
        return $this->Date_De_Debut;
    }

    public function setDateDeDebut(\DateTimeInterface $Date_De_Debut): self
    {
        $this->Date_De_Debut = $Date_De_Debut;

        return $this;
    }

    public function getDateDeFin(): ?\DateTimeInterface
    {
        return $this->Date_De_Fin;
    }

    public function setDateDeFin(\DateTimeInterface $Date_De_Fin): self
    {
        $this->Date_De_Fin = $Date_De_Fin;

        return $this;
    }

    public function getRabais(): ?float
    {
        return $this->Rabais;
    }

    public function setRabais(float $Rabais): self
    {
        $this->Rabais = $Rabais;

        return $this;
    }

    public function getEmploye(): ?bool
    {
        return $this->Employe;
    }

    public function setEmploye(bool $Employe): self
    {
        $this->Employe = $Employe;

        return $this;
    }

    public function getRabaisProduit(): ?Produit
    {
        return $this->rabaisProduit;
    }

    public function setRabaisProduit(?Produit $rabaisProduit): self
    {
        $this->rabaisProduit = $rabaisProduit;

        return $this;
    }

    public function getNouveauPrix(): ?float
    {
        return $this->NouveauPrix;
    }

    public function setNouveauPrix(float $NouveauPrix): self
    {
        $this->NouveauPrix = $NouveauPrix;

        return $this;
    }

//    /**
//     * @return Collection|Produit[]
//     */
//    public function getIdProduit(): Collection
//    {
//        return $this->id_Produit;
//    }
//
//    public function addIdProduit(Produit $idProduit): self
//    {
//        if (!$this->id_Produit->contains($idProduit)) {
//            $this->id_Produit[] = $idProduit;
//            $idProduit->setIdRabais($this);
//        }
//
//        return $this;
//    }
//
//    public function addIdProduitEmploye(Produit $idProduit): self
//    {
//        if (!$this->id_Produit_Employe->contains($idProduit)) {
//            $this->id_Produit_Employe[] = $idProduit;
//            $idProduit->setIdRabaisEmploye($this);
//        }
//
//        return $this;
//    }
//
//    public function removeIdProduit(Produit $idProduit): self
//    {
//        if ($this->id_Produit->contains($idProduit)) {
//            $this->id_Produit->removeElement($idProduit);
//            // set the owning side to null (unless already changed)
//            if ($idProduit->getIdRabais() === $this) {
//                $idProduit->setIdRabais(null);
//            }
//        }
//
//        return $this;
//    }
}
