<?php
namespace easyPM\Models;

use easyPM\Domain\Equipment;
use easyPM\Exceptions\DbException;
use easyPM\Exceptions\NotFoundException;
use PDO;

class EquipmentModel extends AbstractModel {
    const CLASSNAME = '\easyPM\Domain\Book';

    public function get(int $id): Equipment {
        $query = 'SELECT * FROM Equipment WHERE id = :id';
        $sth = $this->db->prepare($query);
        $sth->execute(['id' => $id]);
        $equipment = $sth->fetchAll(
            PDO::FETCH_CLASS, self::CLASSNAME
        );
        if (empty($equipment)) {
            throw new NotFoundException();
        }
        return $equipment[0];
    }
}
