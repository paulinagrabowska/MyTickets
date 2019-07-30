<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Performer;
use App\Repository\ConcertRepository;
use App\Repository\PerformerRepository;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/search-results", methods={"POST"}, name="search_results")
     */
    public function searchResults()
    {
        return $this->render('front/search_results.html.twig');
    }

}
