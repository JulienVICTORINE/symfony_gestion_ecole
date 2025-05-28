<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\Entity\Etudiant;
use App\Entity\Professeur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // --- Création des professeurs ---
        $prof1 = new Professeur();
        $prof1->setNom('Dupont');
        $prof1->setPrenom('Jean');
        $prof1->setEmail('jean.dupont@ecole.fr');
        $prof1->setMatiereTeaching('Mathématiques');
        $manager->persist($prof1);

        $prof2 = new Professeur();
        $prof2->setNom('Martin');
        $prof2->setPrenom('Claire');
        $prof2->setEmail('claire.martin@ecole.fr');
        $prof2->setMatiereTeaching('Histoire');
        $manager->persist($prof2);


        // --- Création des cours ---
        $cours1 = new Cours();
        $cours1->setNom('Algèbre');
        $cours1->setDescription('Cours d\'algèbre niveau L1');
        $cours1->setNombreHeures(30);
        $cours1->setProfesseur($prof1);
        $manager->persist($cours1);

        $cours2 = new Cours();
        $cours2->setNom('Géographie');
        $cours2->setDescription('Introduction à la géographie');
        $cours2->setNombreHeures(20);
        $cours2->setProfesseur($prof2);
        $manager->persist($cours2);


        // --- Création des étudiants ---
        $etu1 = new Etudiant();
        $etu1->setNom('Bernard');
        $etu1->setPrenom('Lucie');
        $etu1->setEmail('lucie.bernard@email.com');
        $etu1->setDateNaissance(new \DateTime('2002-05-10'));
        $etu1->setNiveau('L1');
        $etu1->addCour($cours1);
        $manager->persist($etu1);

        $etu2 = new Etudiant();
        $etu2->setNom('Durand');
        $etu2->setPrenom('Marc');
        $etu2->setEmail('marc.durand@email.com');
        $etu2->setDateNaissance(new \DateTime('2001-09-20'));
        $etu2->setNiveau('L1');
        $etu2->addCour($cours1);
        $etu2->addCour($cours2);
        $manager->persist($etu2);

        $etu3 = new Etudiant();
        $etu3->setNom('Lemoine');
        $etu3->setPrenom('Sophie');
        $etu3->setEmail('sophie.lemoine@email.com');
        $etu3->setDateNaissance(new \DateTime('2000-12-01'));
        $etu3->setNiveau('L2');
        $etu3->addCour($cours2);
        $manager->persist($etu3);

        $manager->flush();
    }
}
