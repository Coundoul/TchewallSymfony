<?php

namespace App\Controller;

use phpDocumentor\Reflection\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    #[Route('/to/do', name: 'app_to_do')]
    public function index(Request $request): Response
    {
        //afficher notre tableau toDo
        //si j ai mon tableau de todo dans la session je ne fait que l afficher
        //sinon je l initiolise puis l affiche

        $session = $request->getSession();
        $nbrVisite = 0;

        if(!$session->has('todos')){
            $todos = [
                'achat'=>'acheter cle usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examen'
            ];

            $session->set('todos', $todos);

            $this->addFlash('info', "la liste des todos vient d'être initialiser");

        }
        return $this->render('to_do/index.html.twig');
    }

    #[Route('/to/do/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content) : RedirectResponse{

        $session = $request->getSession();

        //verifier si j ai mon tableau de todos dans la session
        if($session->has('todos')){

            //permet de recuperer le tableau todos
            $todos = $session->get('todos');

            //verifier si on a un to do de meme name afficher erreur
            if(isset($todos[$name])){
                $this->addFlash('error', "Ce name existe deja dans todos");
            }

            //sinon
            else{
                //sinon ajouter le to do dans la liste
                $todos[$name]=$content;
                $this->addFlash('success', "Ce name est ajouter avec success dans la liste des todos");
                $session->set('todos', $todos);
            }
        }


        //sinon afficher une erreur et le rediriger vers le controlleur index
        else{

            $this->addFlash('error', "la liste des todos n'est pas encore initialise");
        }
        return $this->redirectToRoute('app_to_do');
    }

    #[Route('/to/do/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content) : RedirectResponse
    {

        $session = $request->getSession();

        //verifier si j ai mon tableau de todos dans la session
        if ($session->has('todos')) {

            //permet de recuperer le tableau todos
            $todos = $session->get('todos');

            //verifier si on a un to do de meme name afficher erreur
            if (!isset($todos[$name])) {
                $this->addFlash('error', "Ce name n'existe pas dans todos");
            }
            //sinon
            else {
                //sinon ajouter le to do dans la liste
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "Ce name est ajouter avec success dans la liste des todos");
            }
        }

        //sinon afficher une erreur et le rediriger vers le controlleur index
        else{

            $this->addFlash('error', "la liste des todos n'est pas encore initialise");
        }
        return $this->redirectToRoute('app_to_do');
    }

    #[Route('/to/do/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name) : RedirectResponse{

        $session = $request->getSession();

        //verifier si j ai mon tableau de todos dans la session
        if($session->has('todos')){

            //permet de recuperer le tableau todos
            $todos = $session->get('todos');

            //verifier si on a un to do de meme name
            if(isset($todos[$name])){
                //si oui supprimer le to do dans la liste
                unset($todos[$name]);
                $this->addFlash('success', "Ce name est supprimer avec success dans la liste des todos");
                $session->set('todos', $todos);
            }
            //sinon
            else{
                //sinon
                $this->addFlash('error', "Ce name n'existe pas deja dans todos");
            }
        }

        //sinon afficher une erreur et le rediriger vers le controlleur index
        else{

            $this->addFlash('error', "la liste des todos n'est pas encore initialise");
        }
        return $this->redirectToRoute('app_to_do');
    }

    #[Route('/to/do/reset', name: 'todo.reset')]
    public function resetToDo(Request $request): Response
    {
        $session = $request->getSession();

        $session-> remove('todos');

        return $this->redirectToRoute('app_to_do');
    }

}
