<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProfesseurRepository::class)]

#[UniqueEntity(fields: ['email'], message: 'Un professeur utilise déjà cet email.')]
class Professeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]

    // Validation pour le nom du professeur
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    #[Assert\Length(
        min:2, 
        max:100, 
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',  
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]

    // Validation pour le prénom du professeur
    #[Assert\NotBlank(message: 'Le prénom ne peut pas être vide')]  
    #[Assert\Length( 
        min: 2, 
        max: 100, 
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',  
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères'  
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]

    // Validation pour l'email du professeur
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    #[Assert\Email(message: 'L\'email "{{ value }}" n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column(length: 100)]

    // Validation pour la matière principale
    #[Assert\NotBlank(message: 'La matière est requise.')]
    private ?string $matiereTeaching = null;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'professeur')]
    private Collection $cours;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNomComplet(): string 
    {
        return $this->getNom().' '.$this->getPrenom();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMatiereTeaching(): ?string
    {
        return $this->matiereTeaching;
    }

    public function setMatiereTeaching(string $matiereTeaching): static
    {
        $this->matiereTeaching = $matiereTeaching;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setProfesseur($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getProfesseur() === $this) {
                $cour->setProfesseur(null);
            }
        }

        return $this;
    }
}
