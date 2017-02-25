<?php

namespace ProfileBundle\Controller;

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
        $email = '';
        $name = '';
        $errors = [];
        if ($request->getMethod() === Request::METHOD_POST) {

            $email = $request->request->get('email');
            if (empty($email)) {
                $errors['email'][] = 'Email field must not be empty';

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'][] = 'Email field must be a valid email';

            } elseif ($this->accountAlreadyExistsWithEmail($email)) {
                $errors['email'][] = 'Email is already registered';
            }

            $name = $request->request->get('name');
            if (empty($name)) {
                $errors['name'][] = 'Name must not be empty';
            }

            $password = $request->request->get('password');
            if (empty($password)) {
                $errors['password'][] = 'Password cannot be empty';

            } elseif (!$this->passwordIsSafe($password)) {
                $errors['password'][] = 'Password is not safe enough';
            }

            $passwordAgain = $request->request->get('password-again');
            if ($password !== $passwordAgain) {
                $errors['password-again'][] = 'Both passwords must match';
            }

            if (empty($errors)) {
                $accountId = $this->doRegisterAccount($email, $password, $name);

                return $this->redirectToRoute('account_welcome', [
                    'accountId' => $accountId,
                ]);
            }
        }

        return $this->render('account/register.html.twig', [
            'errors' => $errors,
            'email' => $email,
            'name' => $name,
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

    private function doRegisterAccount($email, $password, $name)
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
