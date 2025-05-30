<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]

#[UniqueEntity(fields:['email'], message: 'Cet email est déjà utilisé.')]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]

    // Validation pour le nom
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    #[Assert\Length(
        min:2, 
        max:100, 
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',  
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]

    #[Assert\NotBlank(message: 'Le prénom ne peut pas être vide')]  
    #[Assert\Length( 
        min: 2, 
        max: 100, 
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',  
        maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères'  
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique:true)]

    // Validation pour l'email
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    #[Assert\Email(message: 'L\'email "{{ value }}" n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]

    // Validation pour la date de naissance
    #[Assert\NotNull(message: 'La date de naissance est requise.')]
    #[Assert\LessThan('today', message: 'La date doit être dans le passé.')]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column(length: 50)]

    // Validation pour le niveau scolaire
    #[Assert\NotBlank(message: 'Le niveau scolaire est requis.')]
    private ?string $niveau = null;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\ManyToMany(targetEntity: Cours::class, mappedBy: 'etudiants')]
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

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTime $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    // Fonction pour Calcul de l'âge des étudiants
    public function getAge(): ?int 
    {
        $today = new \DateTime();
        return $today->diff($this->dateNaissance)->y;

        // diff() calcule la différence entre aujourd’hui et la date de naissance
        // ->y donne le nombre d'années (l'âge)
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

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
            $cour->addEtudiant($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            $cour->removeEtudiant($this);
        }

        return $this;
    }
}
