<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Entity\Families;
use App\Form\CompetencesType;
use App\Repository\CompetencesRepository;
use App\Repository\FamiliesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Security("is_granted('ROLE_USER')")
 * 
 * @Route("/competences")
 */
class CompetencesController extends AbstractController
{
    /**
     * @Route("/", name="competences_index", methods={"GET"})
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        return $this->render('competences/index.html.twig', [
            'competences' => $competencesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="competences_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $competence = new Competences();
        $form = $this->createForm(CompetencesType::class, $competence);
        $form->handleRequest($request);

        //dd($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competence);
            $entityManager->flush();

            return $this->redirectToRoute('competences_index');
        }

        return $this->render('competences/new.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="competences_show", methods={"GET"})
     */
    public function show(Competences $competence): Response
    {
        return $this->render('competences/show.html.twig', [
            'competence' => $competence,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="competences_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Competences $competence): Response
    {
        $form = $this->createForm(CompetencesType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('competences_index');
        }

        return $this->render('competences/edit.html.twig', [
            'competence' => $competence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="competences_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Competences $competence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($competence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('competences_index');
    }

     /**
     * @Route("/getFamilies/{id}", name="competences_families", methods={"GET"})
     */
    public function getFamilies($id)
    {
        $families = $this->getDoctrine()->getRepository(Families::class)->findBy(['id' => $id]);
        // dd($families);
        //return new JsonResponse(array('name' => $name));
        //return new JsonResponse($families);
        //return new JsonResponse(array('name' => $families));

        // $response = new Response(json_encode(array('name' => $families)));
        // $response->headers->set('Content-Type', 'application/json');

        // return $response;

        return $this->render('competences/tarifasIndex.html.twig', array(
            'tarifas' => $families,
            'json_tarifas' => json_encode($families)
        ));
    }
}
