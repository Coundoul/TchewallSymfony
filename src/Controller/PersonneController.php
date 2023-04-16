<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $entityManager): Response
    {
        $repository=$entityManager->getRepository(Personne::class);

        $personnes = $repository->findAll();

        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes
        ]);
    }

    #[Route('/all/{page?1}/{nbre?12}', name: 'personne.listAll')]
    public function indexFindBy(ManagerRegistry $entityManager, $page, $nbre): Response
    {
        $repository=$entityManager->getRepository(Personne::class);

        $nbrePersonne = $repository->count([]);

        $nbrePage = ceil($nbrePersonne / $nbre);

        $personnes = $repository->findBy([], [], $nbre, ($page -1)* $nbre);


        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes,
            'pagination'=>true,
            'nbrePage'=>$nbrePage,
            'page'=>$page,
            'nbre'=>$nbre
        ]);
    }

    #[Route('/detail/{id<\d+>}', name: 'personne.detail')]
    public function detailPersonne(Personne $personne = null): Response
    {
        if (!$personne) {
            $this->addFlash('error', "Cette Personne n'existe pas");

            return $this->redirectToRoute('personne.list');
        }

        return $this->render('personne/detail.html.twig',[
            'personne'=>$personne
        ]);
    }

    #[Route('/update/{id<\d+>}/{firtname}/{name}/{age}', name: 'personne.update')]
    public function updatePersonne(EntityManagerInterface $entityManager,Personne $personne = null, $firtname, $name, $age): Response
    {
        if (!$personne) {
            $this->addFlash('error', "Cette Personne n'existe pas");

            return $this->redirectToRoute('personne.list');
        }
        $personne->setFirstname($firtname);
        $personne->setName($name);
        $personne->setAge($age);

        $entityManager->persist($personne);

        $entityManager->flush();

        $this->addFlash('success', "Cette Personne a ete mise a jour avec success");

        return $this->redirectToRoute('personne.listAll');
    }

    #[Route('/delete/{id<\d+>}', name: 'personne.supprimer')]
    public function deletePersonne(EntityManagerInterface $entityManager, Personne $personne = null): RedirectResponse
    {
        if (!$personne) {
            $this->addFlash('error', "Cette Personne n'existe pas");

            return $this->redirectToRoute('personne.listAll');
        }

        $entityManager->remove($personne);

        $entityManager->flush();

        $this->addFlash('success', "Cette Personne a ete supprimer avec success");

        return $this->redirectToRoute('personne.listAll');
    }

    #[Route('/add', name: 'personne.add')]
    public function addPersonne(EntityManagerInterface $entityManager): Response
    {
        $personne = new Personne();

        //Initialisation de l'objet
//        $personne->setFirstname('Coura');
//        $personne->setName('Gadji');
//        $personne->setAge(19);

        //ajout Personne dans la transaction
//        $entityManager->persist($personne);

        // execute la transaction
        //$entityManager->flush();

        return $this->render('personne/ajout.html.twig', [
            'personne' => $personne
        ]);
    }

}
