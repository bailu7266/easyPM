<?php

namespace easyPM\Models;
use easyPM\Domain\User;
use easyPM\Domain\User\UserFactory;
use easyPM\Exceptions\NotFoundException;

class UserModel extends AbstractModel {
    public function get(int $userId): User {
        $query = 'SELECT * FROM user WHERE user_id = :user';
        $sth = $this->db->prepare($query);
        $sth->execute(['user' => $userId]);
        $row = $sth->fetch();
        if (empty($row)) {
            throw new NotFoundException();
        }
        return UserFactory::factory(
            $row['type'],
            $row['id'],
            $row['firstname'],
            $row['surname'],
            $row['email']
        );
    }

    public function getByEmail(string $email): User {
        $query = 'SELECT * FROM user WHERE email = :user';
        $sth = $this->db->prepare($query);
        $sth->execute(['user' => $email]);
        $row = $sth->fetch();
        if (empty($row)) {
            throw new NotFoundException();
        }
        return UserFactory::factory(
            $row['type'],
            $row['id'],
            $row['firstname'],
            $row['surname'],
            $row['email']
        );
    }
}
