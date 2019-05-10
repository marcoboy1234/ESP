<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @Assert\GreaterThan(-1, message="L'inventaire ne doit pas être négatif")
     */
    private $Inventaire = 0;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $Disponible = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="produits")
     */
    private $Categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rabais", mappedBy="rabais_produit_client")
     */
    private $Rabais;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LePanier", mappedBy="id_produit")
     */
    private $panier;

    public function __construct()
    {
        $this->Rabais = new ArrayCollection();
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getSlug(){
        return (new Slugify())->slugify($this->Nom);
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function getFormatedPrice(){
        return number_format($this->Prix,0,',',' ');
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getInventaire(): ?int
    {
        return $this->Inventaire;
    }

    public function setInventaire(int $Inventaire): self
    {
        $this->Inventaire = $Inventaire;

        return $this;
    }

    public function getDisponible(): ?bool
    {
        return $this->Disponible;
    }

    public function setDisponible(bool $Disponible): self
    {
        $this->Disponible = $Disponible;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getPhoto()
    {
        return $this->Photo;
    }

    public function setPhoto($Photo)
    {
        $this->Photo = $Photo;

        return $this;
    }

    /**
     * @return Collection|Rabais[]
     */
    public function getRabais(): Collection
    {
        return $this->Rabais;
    }

    public function addRabai(Rabais $rabai): self
    {
        if (!$this->Rabais->contains($rabai)) {
            $this->Rabais[] = $rabai;
            $rabai->setRabaisProduitClient($this);
        }

        return $this;
    }

    public function removeRabai(Rabais $rabai): self
    {
        if ($this->Rabais->contains($rabai)) {
            $this->Rabais->removeElement($rabai);
            // set the owning side to null (unless already changed)
            if ($rabai->getRabaisProduitClient() === $this) {
                $rabai->setRabaisProduitClient(null);
            }
        }

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
            $panier->setIdProduit($this);
        }

        return $this;
    }

    public function removePanier(LePanier $panier): self
    {
        if ($this->panier->contains($panier)) {
            $this->panier->removeElement($panier);
            // set the owning side to null (unless already changed)
            if ($panier->getIdProduit() === $this) {
                $panier->setIdProduit(null);
            }
        }

        return $this;
    }

}
