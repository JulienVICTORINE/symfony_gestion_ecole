<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CoursRepository::class)]

class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]

    // Validation pour le nom du cours
    #[Assert\NotBlank(message: 'Le nom du cours ne peut pas être vide')]
    #[Assert\Length(
        min:2, 
        max:100, 
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',  
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]

    // Validation pour la description du cours
    #[Assert\NotBlank(message: 'La description du cours est requise.')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nombre d'heures ne peut pas être vide")]
    #[Assert\Type(type: 'integer', message: "Le nombre d'heures est un nombre entier")]
    #[Assert\Range(min:0, max:200, notInRangeMessage: 'Le nombre d\'heures doit être entre {{ min }} et {{ max }}' )]
    private ?int $nombreHeures = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[Assert\NotNull(message: 'Un professeur doit être sélectionné pour ce cours.')]
    private ?Professeur $professeur = null;

    /**
     * @var Collection<int, Etudiant>
     */
    #[ORM\ManyToMany(targetEntity: Etudiant::class, inversedBy: 'cours')]
    #[Assert\Count(
        min: 1,
        minMessage: 'Vous devez inscrire au moins un étudiant à ce cours.'
    )]
    private Collection $etudiants;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNombreHeures(): ?int
    {
        return $this->nombreHeures;
    }

    public function setNombreHeures(int $nombreHeures): static
    {
        $this->nombreHeures = $nombreHeures;

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->professeur;
    }

    public function setProfesseur(?Professeur $professeur): static
    {
        $this->professeur = $professeur;

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): static
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        $this->etudiants->removeElement($etudiant);

        return $this;
    }
}
