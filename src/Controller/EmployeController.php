<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employe;
use App\Entity\Fonction;
use Doctrine\ORM\EntityManagerInterface;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="employe")
     */
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

    /**
     * @Route("/employe/creer", name="employe_creer")
     */
    public function creerEmploye(EntityManagerInterface $entityManager): Response
    {
        // : Response   type de retour de la méthode creerEmploye
        // pour récupérer le EntityManager (manager d'entités, d'objets)
        //  on peut ajouter l'argument à la méthode comme ici creerProduit(EntityManagerInterface $entityManager)
        // ou on peut récupérer le EntityManager via $this->getDoctrine() comme ci dessus en commentaire
        // $entityManager = $this->getDoctrine()->getManager();

        // créer l'objet 
        $employe = new Employe();
        $employe->setNom('Dupont');
        $employe->setPrenom('Jean');
        $employe->setRue('05 rue du Général Fôche');
        $employe->setCodePostal(57000);
        $employe->setVille('Metz');
        $employe->setSalaire(12000.00);

        // dire à Doctrine que l'objet sera (éventuellement) persisté
        $entityManager->persist($employe);

        // exécuter les requêtes (indiques avec persist) ici il s'agit de l'ordre INSERT qui sera exécuté
        $entityManager->flush();

        return new Response('Nouvel-le employé-e enregistré-e, son id est :'.$employe->getId());
    }

    /**
     * @Route("/employe/{id}", name="employe_lire")
     */
    public function lire($id)
    {
        // {id} dans la route permet de récupérer $id en argument de la méthode
        // on utilise le Repository de la classe Employe
        // il s'agit d'une classe qui est utilisée pour les recherches d'entités (et donc de données dans la base)
        // la classe EmployeRepository a été créée en même temps que l'entité par le make
        $employe = $this->getDoctrine()
            ->getRepository(Employe::class)
            ->find($id);
        if (!$employe){
            throw $this->createNotFoundException('Cet-te employé-e n\'existe pas : '. $id);
        }
        return new Response('Voici le nom de l\'employé-e : ' . $employe->getNom());
        // on peut bien sûr également rendre un template 
    }


    /**
     * @Route("/employeautomatique/{id}", name="employeautomatique_lire")
     */
    public function lireautomatique(Employe $employe)
    {
        // grâce au SensioFrameworkExtraBundle (installé ici car création application complète)
        // il suffit de donner l'employé en argument
        // la requête de recherche sera automatique
        // et une page 404 sera générée si le produit n'existe pas

        return new Response('Voici le nom de l\'employé-e lu automatiquement : ' . $employe->getNom(). ' embauché le ' . $employe->getDateEmbauche()->format('Y-m-d'));
        // on peut bien sûr également rendre un template
    }

    /**
     * @Route("/employe/modifier/{id}", name="employe_modifier")
     */
    public function modifier($id)
    {
        // 1 recherche de l'employé
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        // en cas d'employé inexistant, affichage page 404
        if (!$employe) {
            throw $this->createNotFoundException( 'Aucun employé avec l\'id ' . $id);
        }

        // 2 modification des propriétés
        $employe->setNom('François');
        // 3 execution de l'update
        $entityManager->flush();

        // redirection vers l'affichage de l'employé
        return $this->redirectToRoute('employe_lire', ['id' => $employe->getId()]);
    }

    /**
     * @Route("/employe/supprimer/{id}", name="employe_supprimer")
     */
    public function supprimer($id)
    {
        // 1 recherche de l'employe
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        // en cas d'employe inexistant, affichage page 404
        if (!$employe) {
            throw $this->createNotFoundException('Aucun-e employé-e avec l\'id '. $id);
        }

        // 2 suppression de l'employe
        $entityManager->remove(($employe));
        // 3 éxécution du delete
        $entityManager->flush();

        // affichage réponse
        return new Response('L\'employé-e a été supprimé-e, id : ' .$id);
    }

    /**
     * @Route("/employe/complet/creer", name="employe_complet_creer")
     */
    public function creerEmployeComplet()
    {
        // créer une fonction
        $fonction = new Fonction();
        $fonction->setLibFonction('Cassier');

        // créer un employé
        $employe = new Employe();
        $employe->setNom('Habsburg-Lothringen');
        $employe->setPrenom('Franz');
        $employe->setRue('25 Leopold Strass');
        $employe->setCodePostal(57000);
        $employe->setVille('Metz');
        $employe->setSalaire(1200.00);

        // mettre en relation l'employé avec la fonction
        $employe->setFonction($fonction);

        // persister les objets
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fonction);
        $entityManager->persist($employe);
        // éxécutez les requêtes 
        $entityManager->flush();

        // retourner une réponse 
        return new Response('Nouvel-le employé-e enregistré-e avec l\'id : ' .$employe->getId() . ' et nouvelle fonction enregistrée avec id: ' .$fonction->getId());

    }
}
