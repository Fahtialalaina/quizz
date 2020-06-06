<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Competences;
use App\Entity\Families;
use App\Entity\Questions;
use App\Form\AnswersType;
use App\Form\FamiliesToQuestionsType;
use App\Form\QuestionsEditType;
use App\Form\QuestionType;
use Doctrine\ORM\Cache\CollectionCacheEntry;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, PaginatorInterface $paginator)
    {
        //$user = new UserInterface();
        $donnees = $this->getDoctrine()->getRepository(Questions::class)->findBy([], ['createAt'=> 'DESC']);
        //dd($userId);
        //dd($user->getId());

        //Remplir nos donnée
        $questions = $paginator->paginate(
            $donnees, //On passe nos données
            $request->query->getInt('page', 1), //Numéro de la page en coures, 1 par defaut
            10
        );

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
        // $families = new Families();
        // $answer = new Answers();

        $form = $this->createForm(QuestionType::class, $questions);
        $form_2 = $this->createForm(FamiliesToQuestionsType::class);
        // $form_answer = $this->createForm(AnswersType::class, $answer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$answ = $request->request->get('answers_title')->getExtraFields();
            // $answ = $form->get('answers_title')->getExtraFields();
           //$answ = $form['answers_title']->getData();
           $answ = $form->get('answer')->getData()->getExtraFields();
          // dd($answ);
            //On recupere les images transmises;
            $images = $form->get('images')->getData();
            //dd($answ);

            //dd($images);
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

            $questions->setEtat('0');
            $questions->setCreateAt(new \DateTime());
            $questions->setUsers($user);

            //dd($questions);
            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($questions);
            $entityManager->flush();

            return $this->redirectToRoute('questions_list');
            //return $this->redirectToRoute('questions_addAnswer', array('questions' => $questions->getId()));
        }

        return $this->render('question/new.html.twig', [
            'questionForm' => $form->createView(),
            'familiesForm' => $form_2->createView(),
        ]);
    }

    /**
     * @Route("detail/{id}", name="detail")
     */
    public function detailsQuestion(Questions $questions)
    {
        $question = $this->getDoctrine()->getRepository(Questions::class)->findOneBy(['id' => $questions->getId()]);

        return $this->render("question/detail.html.twig", [
            'controller_name' => 'Détail question',
            'question' => $question,
        ]);
    }

    /**
     * @Route("edit/{id}", name="edit")
     */
    public function editQuestion(Request $request, Questions $questions) : Response
    {
        $form = $this->createForm(QuestionsEditType::class, $questions);
        $families = $this->getDoctrine()->getRepository(Families::class)->findOneBy(['id' => $questions->getCompetences()->getFamilies()->getId()]);
        $form_families = $this->createForm(FamiliesToQuestionsType::class, $families );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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

            //dd($questions);
            // $entityManager =$this->getDoctrine()->getManager();
            // $entityManager->persist($questions);
            // $entityManager->flush();

            return $this->redirectToRoute('questions_list');

        }
        return $this->render('question/edit.html.twig', [
            'controller_name' => 'Modifier question',
            'questionForm' => $form->createView(),
            'familiesForm' => $form_families->createView()
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
    }

    /**
     * @Route("/myquestion", name="myquestion")
     */
    public function myQuestion(Request $request, PaginatorInterface $paginator, UserInterface $users=null)
    {
       //$user = new UserInterface();
       $donnees = $this->getDoctrine()->getRepository(Questions::class)->findBy(['users' => $users], ['createAt'=> 'DESC']);
       //dd($userId);
       //dd($user->getId());

       //Remplir nos donnée
       $questions = $paginator->paginate(
           $donnees, //On passe nos données
           $request->query->getInt('page', 1), //Numéro de la page en coures, 1 par defaut
           10
       );

       return $this->render('question/index.html.twig', [
           'controller_name' => 'Listes des questions',
           'questions' => $questions
       ]);
    }
}
