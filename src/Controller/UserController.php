<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\PerformerType;
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
     */
    public function user_view(User $user)
    {
        $user= $this->getUser();

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
     */
    public function userChangeData(User $user, UserRepository $repository, Request $request): Response
    {
        if ($user !== $this->getUser()) {
            $this->addFlash('warning', 'message.forbidden');

            return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
        } else {
            $form = $this->createForm(UserDataType::class, $user, ['method' => 'PUT']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $repository->save($user);
                $this->addFlash('success', 'message.updated_successfully');

                return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
            }
        }

        return $this->render('front/user/change_data.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
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
     *     "/edit_pass/{id}",
     *     name="user_edit_pass",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "PUT"},
     * )
     */
    public function userChangePass(User $user, UserRepository $repository, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        if ($user !== $this->getUser()) {
            $this->addFlash('warning', 'message.forbidden');

            return $this->redirectToRoute('user_view', ['id' => $user->getId()]);
        } else {
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
        }

        return $this->render('front/user/change_pass.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]);
    }

    /**
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





}
