<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Performer;
use App\Form\PerformerType;
use App\Repository\ConcertRepository;
use App\Repository\PerformerRepository;
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
}
