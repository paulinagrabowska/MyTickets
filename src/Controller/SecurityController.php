<?php
/**
 * Security controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
    /**
     * Login form action.
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils Auth utils
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/login",
     *     name="security_login",
     * )
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * Logout action.
     *
     * @throws \Exception
     *
     * @Route(
     *     "/logout",
     *     name="security_logout",
     * )
     */
    public function logout(): void
    {
        // Request is intercepted before reaches this exception:
        throw new \Exception('Internal security module error');
    }

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param PasswordEncoderInterface $encoder
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="user_add",
     * )

     */
    public function register(Request $request, UserRepository $repository, UserPasswordEncoderInterface $encoder): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $data = $form->getData();
//            dump($data);
            $password = $form->get('password')->getData();
            $user->setPassword(
                $encoder->encodePassword($user, $password
                ));
            $user->setRoles([User::ROLE_USER]);
            if ($repository->findOneBy(['email' => $user->getEmail()])) {
                $this->addFlash('error', 'message.user_exists');

                return $this->redirectToRoute('user_new');
            }
            $repository->save($user);

            $this->addFlash('success', 'message.registration_success');

            return $this->redirectToRoute('security_login');

        }

        return $this->render(
            'security/register.html.twig',
            ['form' => $form->createView()]
        );



    }
}