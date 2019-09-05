<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\PerformerType;
use App\Form\ReservationType;
use App\Form\UserDataType;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class ProfileController.
 *
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */

class UserController extends Controller

{
    /**
     * @param User $user
     * @return Response
     *
     * @Route(
     *     "/profile/{id}",
     *     name="user_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function userView(User $user)
    {
//        if ($user !== $this->getUser()) {
//            $this->addFlash('warning', 'message.item_not_found');
//
//            return $this->redirectToRoute('main_page');
//        }

        return $this->render('front/user/profile.html.twig', [
            'user' => $user ,
        ]);
    }

    /**
     * Change user data.
     *
     * @param User $user
     * @param UserRepository $repository
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/edit/{id}",
     *     name="user_edit",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function userChangeData(User $user, UserRepository $repository, Request $request): Response
    {
//        if ($user !== $this->getUser()) {
//            $this->addFlash('warning', 'message.forbidden');
//
//            return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
//        } else {
            $form = $this->createForm(UserDataType::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $repository->save($user);
                $this->addFlash('success', 'message.updated_successfully');

                return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
            }
//        }

        return $this->render('front/user/change_data.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]);
    }


    /**
     * Change user password.
     *
     * @param User $user
     * @param UserRepository $repository
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/edit_pass/{id}",
     *     name="user_edit_pass",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "PUT"},
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function userChangePass(User $user, UserRepository $repository, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
//        if ($user !== $this->getUser()) {
//            $this->addFlash('warning', 'message.forbidden');
//            return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
//        } else {
            $form = $this->createForm(UserPasswordType::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $form->get('password')->getData();
                $user->setPassword(
                    $encoder->encodePassword($user, $password
                    ));
                $repository->save($user);
                $this->addFlash('success', 'message.updated_successfully');
                return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
            }
//        }

        return $this->render('front/user/change_pass.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]);
    }

    /**
     * Show user's reservation list.
     *
     * @param ReservationRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     * @Route("/reservation", name="user_reservations")
     */
    public function reservationList(ReservationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getUser();
        $reservations= $paginator->paginate(
            $repository->queryByAuthor($user),
            $request->query->getInt('page', 1),
            Reservation::NUMBER_OF_ITEMS
        );

        return $this->render(
            'front/user/reservations.html.twig',
            ['reservations' => $reservations,
                'user' => $user
            ]
        );
    }

    /**
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
     *   name="user_reservation_delete",
     * )
     * @IsGranted(
     *     "MANAGE",
     *     subject="reservation",
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

            return $this->redirectToRoute('main_page');
        }

        return $this->render(
            'front/reservation/delete.html.twig',
            [
                'form' => $form->createView(),
                'reservation' => $reservation,
            ]
        );
    }
}
