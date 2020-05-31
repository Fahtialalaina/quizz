<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\Families;
use App\Entity\Questions;
use App\Form\FamiliesToQuestionsType;
use App\Form\QuestionType;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Security("is_granted('ROLE_USER')")
 * 
 * @Route("/questions", name="questions_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(UserInterface $user=null)
    {
        //$user = new UserInterface();
        $questions = $this->getDoctrine()->getRepository(Questions::class)->findAll();
        //dd($userId);
        //dd($user->getId());

        return $this->render('question/index.html.twig', [
            'controller_name' => 'Listes des questions',
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, UserInterface $user = null): Response
    {
        $questions = new Questions();
        $families = new Families();

        $form = $this->createForm(QuestionType::class, $questions);
        $form_2 = $this->createForm(FamiliesToQuestionsType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            dd($request);
            //$userId = new User
            //On recupere les images transmises;
            $images = $form->get('images')->getData();
            // $fichier = 'a.jpg';
            if($images) {
                foreach ($images as $image) {
                    # code... 
                    //recuperer l'extension d'un image
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();  
                    //Copier le fichier dans notre reperitoire pour le stocker
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    ); 
                    
                    //On stocke l'image dans la base de données (de son nom)
                    $questions->setAttached($fichier);
                }
            }
            
            $questions->setUsers($user);
        
            //dd($questions);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($questions);
            $entityManager->flush();

            return $this->redirectToRoute('questions_list');
        }

        return $this->render('question/new.html.twig', [
            'questionForm' => $form->createView(),
            'familiesForm' => $form_2->createView()
        ]);
    }
    
    
    /** 
     * @Route("/getCompetence/{id}", name="upload")
    */
    public function getCompetence($id)
    {
        $comptences = $this->getDoctrine()->getRepository(Competences::class)->findBy(['families' => $id]);
        $arrayCollection = array();

        foreach ($comptences as $comptence) {
            # code...
            $arrayCollection[] = array(
                'id' => $comptence->getId(),
                'title' => $comptence->getTitle()
            );
        }
        return new JsonResponse($arrayCollection);
        //dd($comptences);
        // $response = new Response(json_encode($comptences));
        // $response->headers->set('Content-Type', 'application/json');
        //return $response;
    }

    // /**
    //  * @Route("/export", name="export")
    //  */
    // public function generateCsvAction() {
    //     //$repository = $this->get('app.repository.user');
    //     $repository = $this->getDoctrine()->getRepository(Families::class)->findAll();
    
    //     // $response = new StreamedResponse();
    //     // $response->setCallback(function() use ($repository) {
    //     //     $handle = fopen('php://output', 'w+');
    
    //     //     fputcsv($handle, ['Firstname', 'Lastname', 'Birthday'], ';');
    
    //     //     $results = $repository->findAll();
    //     //     foreach ($results as $user) {
    //     //         fputcsv(
    //     //             $handle,
    //     //             [$user->getFirstname(), $user->getLastname(), $user->getBirthday()],
    //     //             ';'
    //     //          );
    //     //     }
    
    //     //     fclose($handle);
    //     // });
    
    //     // $response->setStatusCode(200);
    //     // $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    //     // $response->headers->set('Content-Disposition','attachment; filename="export-users.csv"');
    
    //     dd($repository);
    //     // while ($repository) {
    //     //     # code...
    //     // }

    //     // for ($i=0; $$repository['0'] < 0 ; $i++) { 
    //     //     # code...
    //     // }
    //     return $this->redirectToRoute('questions_list');
    // }
}
