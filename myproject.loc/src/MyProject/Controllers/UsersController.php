<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\ActivationException;
use MyProject\Models\Users\UserActivationService;
use MyProject\Services\EmailSender;
use MyProject\Services\UsersAuthService;
use MyProject\View\View;
use MyProject\Models\Users\User;

class UsersController extends AbstractController
{


    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);

                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }

        $this->view->renderHtml('users/signUp.php');
    }

    public function activate(int $userId, string $activationCode)
    {

        $user = User::getById($userId);
        if ($user === null) {
            throw new ActivationException('Такого пользователя не существует');
        }
        if ($user->IsConfirmed()) {
            throw new ActivationException('Пользователь уже активирован');
        }

        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if (!$isCodeValid) {
            throw new ActivationException('Код активации аторизации не верен ');
        }
        $user->activate();
        UserActivationService::deleteActivationCode($userId);
        $this->view->renderHtml('users/activationSuccessful.php',
            ['nickname' => $user->getNickname()]
        );

    }

    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/login.php');
    }

    public function logOut()
    {
        setcookie('token', '', -1, '/', '', false, true);
        header('Location: /');
    }
}