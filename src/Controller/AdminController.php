<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Performer;
use App\Entity\Tag;
use App\Entity\Venue;
use App\Form\ConcertType;
use App\Form\PerformerType;
use App\Form\TagType;
use App\Form\VenueType;
use App\Repository\ConcertRepository;
use App\Repository\PerformerRepository;
use App\Repository\TagRepository;
use App\Repository\VenueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_main_page")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * Show concerts.
     *
     * @param ConcertRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/concert", name="concert_show")
     */
    public function concert(ConcertRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $concerts = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Concert::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/concert/index.html.twig',
            ['concerts' => $concerts]
        );
    }

    /**
     * Show performers.
     *
     * @param PerformerRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/performer", name="performer_show")
     */
    public function performer(PerformerRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $performers = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Performer::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/performer/index.html.twig',
            ['performers' => $performers]
        );
    }

    /**
     * Add a new performer.
     *
     * @param Request $request
     * @param PerformerRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/performer/add",
     *     methods={"GET", "POST"},
     *     name="performer_add",
     * )
     */
    public function addPerformer(Request $request, PerformerRepository $repository): Response
    {
        $performer = new Performer();
        $form = $this->createForm(PerformerType::class, $performer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($performer);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('performer_show');
        }

        return $this->render(
            'admin/performer/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param Performer $performer
     * @param PerformerRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     * "/performer/{id}/edit",
     *  methods={"GET", "PUT"},
     *  requirements={"id": "[1-9]\d*"},
     *  name="performer_edit",
     * )
     */

    public function editPerformer(Request $request, Performer $performer, PerformerRepository $repository): Response
    {
        $form = $this->createForm(PerformerType::class, $performer, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($performer);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('performer_show');
        }

        return $this->render(
            'admin/performer/edit.html.twig',
            [
                'form' => $form->createView(),
                'performer' => $performer,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Performer $performer
     * @param PerformerRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *   "/performer/{id}/delete",
     *   methods={"GET", "DELETE"},
     *   requirements={"id": "[1-9]\d*"},
     *   name="performer_delete",
     * )
     */
    public function deletePerformer(Request $request, Performer $performer, PerformerRepository $repository): Response
    {
        if ($performer->getConcerts()->count()) {
            $this->addFlash('warning', 'message.performer_has_concerts');

            return $this->redirectToRoute('performer_show');
        }

        $form = $this->createForm(PerformerType::class, $performer, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($performer);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('performer_show');
        }

        return $this->render(
            'admin/performer/delete.html.twig',
            [
                'form' => $form->createView(),
                'performer' => $performer,
            ]
        );
    }


    /**
     * Add a new concert.
     *
     * @param Request $request
     * @param ConcertRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/concert/add",
     *     methods={"GET", "POST"},
     *     name="concert_add",
     * )
     */

    public function addConcert(Request $request, ConcertRepository $repository): Response
    {
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($concert);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('concert_show');
        }

        return $this->render(
            'admin/concert/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit a concert.
     *
     * @param Request $request
     * @param Concert $concert
     * @param ConcertRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     * "/concert/{id}/edit",
     *  methods={"GET", "PUT"},
     *  requirements={"id": "[1-9]\d*"},
     *  name="concert_edit",
     * )
     */

    public function editConcert(Request $request, Concert $concert, ConcertRepository $repository): Response
    {
        $form = $this->createForm(ConcertType::class, $concert, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($concert);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('concert_show');
        }

        return $this->render(
            'admin/concert/edit.html.twig',
            [
                'form' => $form->createView(),
                'concert' => $concert,
            ]
        );
    }

    /**
     * Delete a concert.
     *
     * @param Request $request
     * @param Concert $concert
     * @param ConcertRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *   "/concert/{id}/delete",
     *   methods={"GET", "DELETE"},
     *   requirements={"id": "[1-9]\d*"},
     *   name="concert_delete",
     * )
     */
    public function deleteConcert(Request $request, Concert $concert, ConcertRepository $repository): Response
    {

        $form = $this->createForm(ConcertType::class, $concert, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($concert);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('concert_show');
        }

        return $this->render(
            'admin/concert/delete.html.twig',
            [
                'form' => $form->createView(),
                'concert' => $concert,
            ]
        );
    }

    /**
     * Show venues.
     *
     * @param VenueRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Route("/venue", name="venue_show")
     */
    public function venue(VenueRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $venues= $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Venue::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/venue/index.html.twig',
            ['venues' => $venues]
        );
    }

    /**
     * Add Venue.
     *
     * @param Request $request
     * @param VenueRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/venue/add",
     *     methods={"GET", "POST"},
     *     name="venue_add",
     * )
     */
    public function addVenue(Request $request, VenueRepository $repository): Response
    {
        $venue = new Venue();
        $form = $this->createForm(VenueType::class, $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($venue);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('venue_show');
        }

        return $this->render(
            'admin/venue/add.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Edit venue.
     *
     * @param Request $request
     * @param Venue $venue
     * @param VenueRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     * "/venue/{id}/edit",
     *  methods={"GET", "PUT"},
     *  requirements={"id": "[1-9]\d*"},
     *  name="venue_edit",
     * )
     */
    public function editVenue(Request $request, Venue $venue, VenueRepository $repository): Response
    {
        $form = $this->createForm(VenueType::class, $venue, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($venue);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('venue_show');
        }

        return $this->render(
            'admin/venue/edit.html.twig',
            [
                'form' => $form->createView(),
                'venue' => $venue,
            ]
        );
    }

    /**
     * Delete venue.
     *
     * @param Request $request
     * @param Venue $venue
     * @param VenueRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *   "/venue/{id}/delete",
     *   methods={"GET", "DELETE"},
     *   requirements={"id": "[1-9]\d*"},
     *   name="venue_delete",
     * )
     */

    public function deleteVenue(Request $request, Venue $venue, VenueRepository $repository): Response
    {
        if ($venue->getConcerts()->count()) {
            $this->addFlash('warning', 'message.venue_has_concerts');

            return $this->redirectToRoute('venue_show');
        }

        $form = $this->createForm(VenueType::class, $venue, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($venue);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('venue_show');
        }

        return $this->render(
            'admin/venue/delete.html.twig',
            [
                'form' => $form->createView(),
                'venue' => $venue,
            ]
        );
    }

    /*TAGS*/

    /**
     * @param TagRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route("/tag", name="tag_show")
     */
    public function tag(TagRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $tags= $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Venue::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/tag/index.html.twig',
            ['tags' => $tags]
        );
    }


    /**
     * Add a new tag.
     *
     * @param Request $request
     * @param TagRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/tag/add",
     *     methods={"GET", "POST"},
     *     name="tag_add",
     * )
     */
    public function addTag(Request $request, TagRepository $repository): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($tag);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('tag_show');
        }

        return $this->render(
            'admin/tag/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit a tag.
     *
     * @param Request $request
     * @param Tag $tag
     * @param TagRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     * "/tag/{id}/edit",
     *  methods={"GET", "PUT"},
     *  requirements={"id": "[1-9]\d*"},
     *  name="tag_edit",
     * )
     */
    public function editTag(Request $request, Tag $tag, TagRepository $repository): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($tag);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('tag_show');
        }

        return $this->render(
            'admin/tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }

    /**
     * Delete tag.
     *
     * @param Request $request
     * @param Tag $tag
     * @param TagRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *   "/tag/{id}/delete",
     *   methods={"GET", "DELETE"},
     *   requirements={"id": "[1-9]\d*"},
     *   name="tag_delete",
     * )
     */

    public function deleteTag(Request $request, Tag $tag, TagRepository $repository): Response
    {


        $form = $this->createForm(TagType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($tag);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('tag_show');
        }

        return $this->render(
            'admin/tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
}
