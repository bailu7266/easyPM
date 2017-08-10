<?php
namespace easyPM\Controllers;

use easyPM\Exceptions\NotFoundException;
use easyPM\Models\UserModel;

class UserController extends AbstractController {
    public function login(): string {
        if (!$this->request->isPost()) {
            return $this->render('login.twig', [
                'NameExisted' => 0,
                'NameIsEmpty' => 1,
                'EmailExisted' => 0,
                'EmailIsEmpty' => 1,
                'PswdError' => 0
            ]);
        }
        $params = $this->request->getParams();
        if (!$params->has('email')) {
            $params = ['errorMessage' => 'No info provided.'];
            return $this->render('login.twig', $params);
        }
        $email = $params->getString('email');
        $userModel = new UserModel($this->db);
        try {
            $user = $userModel->getByEmail($email);
        } catch (NotFoundException $e) {
            $this->log->warn('User email not found: ' . $email);
            $params = ['errorMessage' => 'Email not found.'];
            return $this->render('login.twig', $params);
        }
        setcookie('user', $user->getId());
        $newController = new EquipmentController($this->request);
        return $newController->getAll();
    }
}
