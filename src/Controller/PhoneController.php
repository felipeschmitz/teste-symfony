<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Form\PhoneFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="list_phones")
     * @Method ({"GET"})
     */
    public function index(): Response
    {
        $phones = $this->getDoctrine()->getRepository(Phone::class)->findAll();
        return $this->render('phone/index.html.twig', [
            'phones' => $phones,
        ]);
    }

    /**
     * @Route("/phone/new", name="new_phone")
     * @Method ({"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phone = new Phone();
        $form = $this->createForm(PhoneFormType::class, $phone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();
            return $this->redirectToRoute('list_phones');
        }
        return $this->render('phone/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/phone/show/{id}", name="show_phone")
     * @Method ({"GET"})
     */
    public function show(int $id): Response
    {
        $phone = $this->getDoctrine()->getRepository(Phone::class)->find($id);
        return $this->render('phone/show.html.twig', ['phone' => $phone]);
    }

    /**
     * @Route("/phone/update/{id}", name="update_phone")
     * @Method ({"GET","PUT"})
     */
    public function update(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $phone = $this->getDoctrine()->getRepository(Phone::class)->find($id);
        $form = $this->createForm(PhoneFormType::class, $phone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('list_phones');
        }
        return $this->render('phone/update.html.twig', ['phone' => $phone, 'form' => $form->createView()]);
    }

    /**
     * @Route("/phone/delete/{id}", name="delete_phone")
     * @Method ({"DELETE"})
     */
    public function delete(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository(Phone::class)->find($id);
        $em->remove($phone);
        $em->flush();
        return $this->redirectToRoute('list_phones');
    }
}
