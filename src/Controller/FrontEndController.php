<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Performer;
use App\Entity\Reservation;
use App\Entity\Venue;
use App\Form\ReservationType;
use App\Form\SpecificReservationType;
use App\Form\VenueType;
use App\Repository\ConcertRepository;
use App\Repository\PerformerRepository;
use App\Repository\ReservationRepository;
use App\Repository\VenueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FrontEndController
 * @package App\Controller
 */
class FrontEndController extends Controller
{
    /**
     * Index action.
     * Show list of concerts.
     *
     * @param ConcertRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="main_page")
     */
    public function index(ConcertRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $concerts = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Concert::NUMBER_OF_ITEMS
        );

        return $this->render(
            'front/index.html.twig',
            ['concerts' => $concerts]
        );
    }

    /**
     * View a concert.
     *
     * @param Concert $concert
     * @return Response
     * @Route(
     *     "/concert/{id}",
     *     name="concert_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function concert_view(Concert $concert): Response
    {
        return $this->render(
            'front/concert_view.html.twig',
            ['concert' => $concert]
        );
    }

    /**
     * Show performers in front.
     *
     * @param PerformerRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/performer", name="performer_front_show")
     */
    public function performer(PerformerRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $performers = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Performer::NUMBER_OF_ITEMS
        );

        return $this->render(
            'front/performer/index.html.twig',
            ['performers' => $performers]
        );
    }

    /**
     * View a performer.
     *
     * @param Concert $concert
     * @return Response
     * @Route(
     *     "/performer/{id}",
     *     name="performer_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function performerView(Performer $performer): Response
    {
        return $this->render(
            'front/performer/view.html.twig',
            ['performer' => $performer]
        );
    }

    /**
     * Make reservation.
     *
     * @param Request $request
     * @param ReservationRepository $repository
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/reservation",
     *     methods={"GET", "POST"},
     *     name="reservation_add",
     * )
     */
    public function addReservation(Request $request, ReservationRepository $repository , ConcertRepository $concertRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($this->getUser());
            $repository->save($reservation);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('main_page');
        }

        return $this->render(
            'front/reservation/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Make reservation.
     *
     * @param Request $request
     * @param ReservationRepository $repository
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/reservation/{id}",
     *     methods={"GET", "POST"},
     *     name="reservation_new",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function addSpecificReservation(Concert $concert, Request $request, ReservationRepository $repository , ConcertRepository $concertRepository): Response
    {

        $reservation = new Reservation();
        $form = $this->createForm(SpecificReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($this->getUser());
            $reservation->setConcert($concert);
            $repository->save($reservation);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('main_page');
        }

        return $this->render(
            'front/reservation/add_specific.html.twig',
            ['form' => $form->createView(),
                'concert' => $concert]
        );
    }


    /**
     * @param ConcertRepository $concertRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route("/search-results", methods={"POST"}, name="search_results")
     */
    public function searchResults(ConcertRepository $concertRepository, Request $request, PaginatorInterface $paginator)
    {
        $search_results = $concertRepository->search($request->request->get('search_value'));

        $concerts = $paginator->paginate(
            $search_results,
            $request->query->getInt('page', 1),
           Concert::NUMBER_OF_ITEMS
        );

        return $this->render('front/search_results.html.twig',
            ['concerts' => $concerts]);
    }

}
