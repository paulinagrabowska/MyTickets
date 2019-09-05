<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Performer;
use App\Entity\Reservation;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Venue;
use App\Form\ConcertType;
use App\Form\PerformerType;
use App\Form\ReservationType;
use App\Form\TagType;
use App\Form\UserPromoteType;
use App\Form\UserType;
use App\Form\VenueType;
use App\Repository\ConcertRepository;
use App\Repository\PerformerRepository;
use App\Repository\ReservationRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Repository\VenueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
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

    /*PERFORMERS*/

    /**
     * Show performers in admin panel.
     *
     * @param PerformerRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
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
            /**
             * @var UploadedFile $file
             */
            $file=$request->files->get('performer')['image'];
            //okreslamy miejsce zapisu pliku nadane wczesniej w services.yaml
            if ($file) {
                $uploads_directory = $this->getParameter('uploads_directory');

                $filename = md5(uniqid()). '.' . $file->guessClientExtension();
                //przenosimy plik do wybranego miejsca zapisu
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $performer->setImage($filename);
            }
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
     * Edit a performer.
     *
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
            /**
             * @var UploadedFile $file
             */
            $file=$request->files->get('performer')['image'];
            //okreslamy miejsce zapisu pliku nadane wczesniej w services.yaml
            if ($file) {
                $uploads_directory = $this->getParameter('uploads_directory');

                $filename = md5(uniqid()). '.' . $file->guessClientExtension();
                //przenosimy plik do wybranego miejsca zapisu
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $performer->setImage($filename);
            }
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
     * Delete a performer.
     *
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

        //metoda isSubmitted() oczekuje danych, a my je usuwamy dlatego musimy wysłać formularz ręcznie
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

    /*CONCERTS*/

    /**
     * Show concerts in admin panel.
     *
     * @param ConcertRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
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
        if ($concert->getReservations()->count()) {
            $this->addFlash('warning', 'message.concert_has_reservations');

            return $this->redirectToRoute('concert_show');
        }

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

    /*VENUES*/

    /**
     * Show venues in admin panel.
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
     * Add a new venue.
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
     * Show tags in admin panel.
     *
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
     * Delete a tag.
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

    /*USERS*/

    /**
     * Show all users in admin panel.
     *
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route("/user", name="user_show")
     */
    public function user( UserRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $users= $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/user/index.html.twig',
            ['users' => $users]
        );
    }

    /**
     * Delete a user.
     *
     * @param Request $request
     * @param User $user
     * @param UserRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *   "/user/{id}/delete",
     *   methods={"GET", "DELETE"},
     *   requirements={"id": "[1-9]\d*"},
     *   name="user_delete",
     * )
     */

    public function deleteUser(Request $request, User $user, UserRepository $repository): Response
    {
        if ($user->getReservations()->count()) {
            $this->addFlash('warning', 'message.user_has_reservations');

            return $this->redirectToRoute('user_show');
        }
        $form = $this->createForm(UserType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($user);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('user_show');
        }

        return $this->render(
            'admin/user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Promote user.
     *
     * @param Request $request
     * @param User $user
     * @param UserRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     * "/user/{id}/promote",
     *  methods={"GET", "PUT"},
     *  requirements={"id": "[1-9]\d*"},
     *  name="user_promote",
     * )
     */
    public function userPromote(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(UserPromoteType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!in_array('ROLE_USER', $user->getRoles()))
                $user->setRoles(['ROLE_USER']);
            $repository->save($user);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_show');
        }

        return $this->render(
            'admin/user/promote.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Show reservations in admin panel.
     *
     * @param ReservationRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route("/reservation", name="reservation_show")
     */
    public function reservation(ReservationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $reservations= $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Reservation::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/reservation/index.html.twig',
            ['reservations' => $reservations]
        );
    }

    /**
     * Delete a reservation.
     *
     * @param Request $request
     * @param Reservation $reservation
     * @param ReservationRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *   "/reservation/{id}/delete",
     *   methods={"GET", "DELETE"},
     *   requirements={"id": "[1-9]\d*"},
     *   name="reservation_delete",
     * )
     */
    public function deleteReservation(Request $request, Reservation $reservation, ReservationRepository $repository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($reservation);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('reservation_show');
        }

        return $this->render(
            'admin/reservation/delete.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}
