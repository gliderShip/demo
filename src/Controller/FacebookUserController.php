<?php

namespace App\Controller;

use App\Entity\FacebookUser;
use App\Form\FacebookUserType;
use App\Repository\FacebookUserRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use const Grpc\STATUS_OK;

/**
 * @Route("/facebook")
 */
class FacebookUserController extends AbstractController
{
    /**
     * @Route("/user", defaults={"page": "1", "_format"="html"}, methods="GET", name="facebook_user_index")
     * @Route("/user.xml", defaults={"page": "1", "_format"="xml"}, methods="GET", name="facebook_user_index_xml")
     * @Route("/user.json", defaults={"page": "1", "_format"="json"}, methods="GET", name="facebook_user_index_json")
     * @Route("/user/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods="GET", name="facebook_user_index_paginated")
     */
    public function index(Request $request, int $page, string $_format, FacebookUserRepository $faceBookUserRepository, SerializerInterface $serializer): Response
    {
        dump("page: " . $page);

        $facebookUsersPaginator = $faceBookUserRepository->findByPage($page);

        $log = floor(log($facebookUsersPaginator->getLastPage(), 2) - 1);

        dump("users: " . $facebookUsersPaginator->getNumResults());
        dump("total pages: " . $facebookUsersPaginator->getLastPage());
        dump("log: " . $log);

        switch ($_format) {
            case 'json':
//                return $this->json($facebookUsersPaginator->getResults(), Response::HTTP_OK, [], ['json_encode_options' => \JSON_PRETTY_PRINT]);
                return new Response($serializer->serialize($facebookUsersPaginator->getResults(), 'json', ['json_encode_options' => \JSON_PRETTY_PRINT]));
                break;
            case 'xml':
                return new Response($serializer->serialize($facebookUsersPaginator->getResults(), 'xml', []));
                break;
            default:
                return $this->render('facebook_user/index.html.twig', [
                    'paginator' => $facebookUsersPaginator,
                    "log" => $log
                ]);
        }

    }

    /**
     * @Route("/user/new", name="facebook_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $facebookUser = new FacebookUser();
        $form = $this->createForm(FacebookUserType::class, $facebookUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facebookUser);
            $entityManager->flush();

            return $this->redirectToRoute('facebook_user_index');
        }

        return $this->render('facebook_user/new.html.twig', [
            'facebook_user' => $facebookUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="facebook_user_show", methods={"GET"})
     */
    public function show(FacebookUser $facebookUser): Response
    {
        return $this->render('facebook_user/show.html.twig', [
            'facebook_user' => $facebookUser,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="facebook_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FacebookUser $facebookUser): Response
    {
        $form = $this->createForm(FacebookUserType::class, $facebookUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facebook_user_index');
        }

        return $this->render('facebook_user/edit.html.twig', [
            'facebook_user' => $facebookUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="facebook_user_delete", methods={"POST"})
     */
    public function delete(Request $request, FacebookUser $facebookUser): Response
    {
        if ($this->isCsrfTokenValid('delete' . $facebookUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($facebookUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facebook_user_index');
    }

    /**
     * @Route("/search", methods="GET", name="facebook_user_search")
     */
    public function search(Request $request, FacebookUserRepository $facebookUserRepository): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 2500);

        if (!$request->isXmlHttpRequest()) {
            return $this->render('facebook_user/search.html.twig', ['query' => $query]);
        }

        $foundUsers = $facebookUserRepository->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundUsers as $user) {
            $results[] = [
                'fullName' => htmlspecialchars($user->getFirstName(), \ENT_COMPAT | \ENT_HTML5) . htmlspecialchars($user->getLastName(), \ENT_COMPAT | \ENT_HTML5),
                'mobile' => $user->getMobile(),
                'sex' => $user->getSex(),
                'work' => htmlspecialchars($user->getWorkCompany(), \ENT_COMPAT | \ENT_HTML5),
                'url' => $this->generateUrl('facebook_user_show', ['id' => $user->getId()]),
            ];
        }

        dump($results);

        return $this->json($results);
    }

    /**
     * @Route("/hsearch", methods="GET", name="hfacebook_user_search")
     */
    public function hsearch(Request $request, FacebookUserRepository $facebookUserRepository): Response
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 2500);

//        if (!$request->isXmlHttpRequest()) {
//            return $this->render('facebook_user/search.html.twig', ['query' => $query]);
//        }

        $foundUsers = $facebookUserRepository->findBySearchQuery($query, 50);

        $results = [];
        foreach ($foundUsers as $user) {
            $results[] = [
                'id' => $user->getId(),
                'fullName' => htmlspecialchars($user->getFirstName(), \ENT_COMPAT | \ENT_HTML5) . htmlspecialchars($user->getLastName(), \ENT_COMPAT | \ENT_HTML5),
                'mobile' => $user->getMobile(),
                'sex' => $user->getSex(),
                'work' => htmlspecialchars($user->getWorkCompany(), \ENT_COMPAT | \ENT_HTML5),
                'url' => $this->generateUrl('facebook_user_show', ['id' => $user->getId()]),
            ];
        }

        return $this->render("facebook_user/hresult.html.twig", ['users' => $results]);

        dump($results);

        return $this->json($results);
    }
}
