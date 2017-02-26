<?php

namespace ProfileBundle\Controller;

use ProfileBundle\Form\AccountRegistrationForm;
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

        $account = $this
            ->accountManager()
            ->register($form->getData())
        ;

        return $this->redirectToRoute('account_welcome', [
            'accountId' => $account->id(),
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

    private function accountManager(): AccountManager
    {
        return $this->get('profile.manager.account');
    }
}
