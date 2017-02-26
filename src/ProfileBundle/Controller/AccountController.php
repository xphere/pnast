<?php

namespace ProfileBundle\Controller;

use ProfileBundle\Form\AccountRegistrationForm;
use ProfileBundle\Form\AccountRegistration;
use ProfileBundle\Entity\AccountManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    /**
     * @Route("/register", name="account_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this
            ->createForm(AccountRegistrationForm::class)
            ->handleRequest($request)
        ;

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('account/register.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $accountId = $this->doRegisterAccount($form->getData());

        return $this->redirectToRoute('account_welcome', [
            'accountId' => $accountId,
        ]);
    }

    /**
     * @Route(
     *     name="account_welcome",
     *     path="/welcome/{accountId}",
     *     requirements={
     *         "accountId"="\d+",
     *     },
     * )
     */
    public function welcomeAction(int $accountId)
    {
        $account = $this->accountManager()->find($accountId);

        return $this->render('account/welcome.html.twig', [
            'name' => $account->name(),
        ]);
    }

    private function doRegisterAccount(AccountRegistration $command)
    {
        $account = $this->accountManager()->register($command);

        $id = $account->id();
        $this->sendConfirmationEmail($id, $command->email, $command->name);

        return $id;
    }

    private function sendConfirmationEmail($id, $email, $name)
    {
        $subject = sprintf(
            'Thanks for registering, %s',
            $name
        );

        $welcomeUrl = $this->generateUrl('account_welcome', [
            'accountId' => $id,
        ]);

        $message = sprintf(
            'Dear %s, we are glad you register in our website, please do visit %s to follow.',
            $name,
            $welcomeUrl
        );

        mail($email, $subject, $message);
    }

    private function accountManager(): AccountManager
    {
        return $this->get('profile.manager.account');
    }
}
