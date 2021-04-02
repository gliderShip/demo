<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\AuthorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @Route("/author")
 */
class AuthorController extends AbstractController
{
    private $cacheApp;

    /**
     * @Route("/", name="author_index", methods={"GET"})
     * @Cache(smaxage="300", maxage="300", expires="+2 days", public=true)
     */
    public function index(Request $request, AuthorManager $authorManager, AuthorRepository $authorRepository, AdapterInterface $cacheApp): Response
    {
        /** @var Author[] $authors */
        $authors = null;
        $etag = $request->cookies->get('xx-etag', 'NOT FOUND');
        $version = null;

        $cachedAuthorsIndex = $cacheApp->getItem('authors_index');
        if ($cachedAuthorsIndex->isHit()) {
            $authors = $cachedAuthorsIndex->get();
        } else {
            $authors = $authorRepository->findAuthorsAndBooks();
            $cachedAuthorsIndex->set($authors);
            $cachedAuthorsIndex->expiresAfter(60);
//            $cacheApp->save($cachedAuthorsIndex);
        }

        $version = $authorManager->getEtag($authors);

        $response = $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);

        $response->setEtag($version);
        $response->headers->setCookie(new Cookie('xx-etag', $version));
//        $response->headers->set('xx-is-hit', $cachedAuthorsIndex->isHit() ? 'true' : 'false');

        return $response;
    }

    /**
     * @Route("/new", name="author_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/new.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="author_show", methods={"GET"})
     */
    public function show(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="author_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Author $author): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="author_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Author $author): Response
    {
        if ($this->isCsrfTokenValid('delete' . $author->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($author);
            $entityManager->flush();
        }

        return $this->redirectToRoute('author_index');
    }
}
