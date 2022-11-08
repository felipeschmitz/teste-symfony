<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchFormType;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="list_users")
     * @Method ({"GET"})
     */
    public function index(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $q = $request->request->get('search_form')['q'];
            $users = $this->getDoctrine()->getRepository(User::class)->findByNameOrEmail($q);
        }
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/new", name="new_user")
     * @Method ({"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('list_users');
        }
        return $this->render('user/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/show/{id}", name="show_user")
     * @Method ({"GET"})
     */
    public function show(int $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/user/update/{id}", name="update_user")
     * @Method ({"GET","PUT"})
     */
    public function update(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('list_users');
        }
        return $this->render('user/update.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_user")
     * @Method ({"DELETE"})
     */
    public function delete(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('list_users');
    }
}
