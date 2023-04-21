<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Service\MailerService;
use App\Service\UplaodService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/alls/age/{ageMin<\d+>}/{ageMax<\d+>}', name: 'personne.list.age.intervall')]
    public function listAgeIntervalindex(ManagerRegistry $entityManager, $ageMin, $ageMax): Response
    {
        $repository=$entityManager->getRepository(Personne::class);

        $personnes = $repository->findPersonneByInterval($ageMin, $ageMax);

        return $this->render('personne/index.html.twig',[
            'personnes'=>$personnes
        ]);
    }

    #[Route('/stats/age/{ageMin<\d+>}/{ageMax<\d+>}', name: 'personne.stats.age.intervall')]
    public function statsAgeIntervalindex(ManagerRegistry $entityManager, $ageMin, $ageMax): Response
    {
        $repository=$entityManager->getRepository(Personne::class);

        $stats = $repository->StatsPersonneByInterval($ageMin, $ageMax);

        return $this->render('personne/stats.html.twig',[
            'stats'=>$stats[0],
            'ageMin'=>$ageMin,
            'ageMax'=>$ageMax
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
    public function updatePersonne(EntityManagerInterface $entityManager, Personne $personne = null, $firtname, $name, $age): Response
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

    #[Route('/editer/{id<\d+>?0)}', name: 'personne.add')]
    public function addPersonne(EntityManagerInterface $entityManager, Request $request, Personne $personne=null,
                                UplaodService $serviceUpload, MailerService $mailerService): Response
    {
        $new=false;

        if (!$personne){
            $new=true;
            $personne = new Personne();
        }

        $form= $this->createForm(PersonneType::class, $personne);

        $form->remove('createdAt');
        $form->remove('updatedAt');

        // handleRequest permet de mapper les informations du formulaire avec le form de la classe PersonneType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the photo file must be processed only when a file is uploaded
            if ($photo) {
                $directory = $this->getParameter('personne_directory');

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personne->setImage($serviceUpload->uploadFile($photo, $directory));
            }

                $entityManager->persist($personne);

                $entityManager->flush();

                if ($new){
                    $message = " a ete ajouter avec success";
                }
                else{
                    $message=" a ete mis à jour avec success";
                }

                $mailerMessage = $personne->getFirstname()." ".$personne->getName(). " " .$message;

                $this->addFlash('success', $personne->getName(). $message);

                $mailerService->sendEmail(content: $mailerMessage);

                return $this->redirectToRoute('personne.listAll');
            }
        else
        {
            return $this->render('personne/ajout.html.twig', [
                'form' => $form->createView()
            ]);
        }

    }

}
