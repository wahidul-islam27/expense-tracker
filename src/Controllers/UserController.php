<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Security\JWTUtils;
use App\Services\EmailService;
use App\Services\ServiceUtils;
use App\Services\UserService;
use App\View;
use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class UserController extends Controller
{
    // private $loggedInUserId;
    private $confirmationCode;
    private $newPass;
    private $username;
    private $emailService;

    public function __construct(protected UserService $userService) {}

    public function createUser()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
            $monthlyIncome = $_POST['monthlyIncome'] ?? null;
            $incomeAddDate = new DateTime();

            $this->userService->createUser(
                $this->createUserObject($userName, $password, $monthlyIncome, $incomeAddDate)
            );
            $this->sendEmail($userName);
            header('Location: /expense-tracker/public/login');
            exit;
        }

        echo View::make('register', []);
    }

    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $isUserLoggedIn = $this->userService->loginUser($username, $password);
            if ($isUserLoggedIn) {
                header('Location: /expense-tracker/public');
                exit;
            }
            $error = 'Username or Password is incorrect';
        }
        echo View::make('login', []);
    }

    public function logout()
    {
        setcookie('token', '', time() - 3600, '/', '', false, true);
        header('Location: /expense-tracker/public/login');
    }

    public function profile()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
            $monthlyIncome = $_POST['monthlyIncome'] ?? null;
            $incomeAddDate = new DateTime();

            try {
                $this->userService->updateMonthlyIncome(
                    $this->createUserObject($userName, $password, $monthlyIncome, $incomeAddDate)
                );

                header('Location: /expense-tracker/public');
            } catch (BusinessException $e) {
                $error = $e;
                $this->showAlert($error);
            }
            exit;
        }

        try {
            $userLoggedIn = $this->getAuthenticatedUser();
            if (!empty($userLoggedIn)) {
                $user = $this->userService->getUserById($userLoggedIn[0]);
                echo View::make('profile', ['user' => $user]);
            } else {
                header('Location: /expense-tracker/public/login');
            }
        } catch (SystemException $e) {
            $error = $e->getMessage();
        }
    }

    public function forget()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $newPassword = $_POST['newPassword'] ?? null;
            $conPassword = $_POST['conPassword'] ?? null;

            if ($newPassword !== $conPassword) {
                $error = 'Password is not matched';
            }

            setcookie('username', $username, time() + 60, '/');
            setcookie('password', $newPassword, time() + 60, '/');
            $this->emailService = new EmailService($_ENV['MAILER_DSN']);
            $this->emailService->send($this->getEmailMessage($username));


            header('Location: /expense-tracker/public/confirm');
            exit;
        }

        echo View::make('forget', []);
    }

    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? null;
            $confirmCode = $_COOKIE['confirmationCode'];
            $username = $_COOKIE['username'];
            $password = $_COOKIE['password'];

            if ($code === $confirmCode) {
                $this->userService->updatePassword($username, $password);
                header('Location: /expense-tracker/public/login');
            } else {
                $error = 'Invalid code';
            }

            exit;
        }

        echo View::make('confirm', []);
    }

    private function createUserObject($userName, $password, $monthlyIncome, $incomeAddDate)
    {
        return [
            'user_name' => $userName,
            'password' => $password,
            'monthly_income' => $monthlyIncome,
            'income_add_time' => $incomeAddDate
        ];
    }

    private function sendEmail($email)
    {
        $emailText = (new Email())
            ->from('support@example.com')
            ->to($email)
            ->subject('Welcome')
            ->text("Hello Ahnaf");

        $dsn = 'smtp://localhost:1025';

        $transport = Transport::fromDsn($dsn);

        $mailer = new Mailer($transport);
        $mailer->send($emailText);
    }

    private function init()
    {
        if (!$this->isUserLoggedIn) {
            die('Unauthorized user');
        }
    }

    // private function validate()
    // {
    //     $this->loggedInUserId = JWTUtils::validateUserLoggedIn();
    // }

    private function showAlert($message)
    {
        echo '<script language="javascript">';
        echo 'alert(' . $message . ')';
        echo '</script>';
    }

    private function getEmailMessage($email)
    {
        $this->confirmationCode = $this->generateRandomWord();
        setcookie('confirmationCode', $this->confirmationCode, time() + 60, '/');

        return (new Email())
            ->from('support@example.com')
            ->to($email)
            ->subject('Password Reset Email')
            ->text('Hello your password reset code is: ' . $this->confirmationCode);
    }

    private function generateRandomWord($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $word = '';

        for ($i = 0; $i < $length; $i++) {
            $word .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $word;
    }

    private function getAuthenticatedUser()
    {
        $userLoggedIn = JWTUtils::validateUserLoggedIn();

        return $userLoggedIn;
    }
}
