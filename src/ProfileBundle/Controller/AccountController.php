<?php

namespace ProfileBundle\Controller;

use ProfileBundle\Form\AccountRegistrationForm;
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

        $data = $form->getData();
        $accountId = $this->doRegisterAccount(
            $data['email'],
            $data['name'],
            $data['password']
        );

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
        $account = $this->findAccount($accountId);

        return $this->render('account/welcome.html.twig', [
            'name' => $account['name'],
        ]);
    }

    private function findAccount($accountId)
    {
        $connection = $this->databaseConnection();

        $statement = $connection->query(sprintf(
            'SELECT * FROM accounts WHERE id = %d LIMIT 1',
            $accountId
        ));

        return $statement->fetch();
    }

    private function accountAlreadyExistsWithEmail($email)
    {
        $connection = $this->databaseConnection();

        $statement = $connection->query(sprintf(
            'SELECT id FROM accounts WHERE email = "%s" LIMIT 1',
            $email
        ));

        return $statement->rowCount() > 0;
    }

    private function doRegisterAccount($email, $name, $password)
    {
        $connection = $this->databaseConnection();

        $connection->exec(sprintf(
            'INSERT INTO accounts SET email="%s", name="%s", password="%s"',
            $email,
            $name,
            $password
        ));

        $id = $connection->lastInsertId();

        $this->sendConfirmationEmail($id, $email, $name);

        return $id;
    }

    private function passwordIsSafe($password)
    {
        return strlen($password) >= 4;
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

    private function databaseConnection()
    {
        return new \PDO('mysql:host=127.0.0.1;dbname=profile', 'root', 'root');
    }
}
