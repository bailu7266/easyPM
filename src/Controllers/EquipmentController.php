<?php

namespace easyPM\Controllers;

use easyPM\Models\EquipmentModel;

class EquipmentController extends AbstractController {

    public function getAllWithPage(int $page) : string {
        $page = (int)$page;
        $equipmentModel = new EquipmentModel($this->db);
        $equipment = $equipmentModel->getAll($page, self::PAGE_LENGTH);
        $properties = [
            'equipment' => $equipment,
            'currentPage' => $page,
            'lastPage' => count($books) < self::PAGE_LENGTH
        ];
        return $this->render('equipment.twig', $properties);
    }

    public function getAll(): string {
        return $this->getAllWithPage(1);
    }

    public function get(int $equipmentId): string {
        $equipmentModel = new BookModel($this->db);
        try {
            $equipment = $equipmentModel->get($equipmentId);
        } catch (\Exception $e) {
            $this->log->error(
                'Error getting book: ' . $e->getMessage()
            );
            $properties = ['errorMessage' => 'Book not found!'];
            return $this->render('error.twig', $properties);
        }
        $properties = ['equipment' => $equipment];
        return $this->render('equipment.twig', $properties);
    }

    public function getByUser(): string {
        $equipmentModel = new EquipmentModel($this->db);
        $equipment = $EquipmentModel->getByUser($this->customerId);
        $properties = [
            'equipment' => $equipment,
            'currentPage' => 1,
            'lastPage' => true
        ];
        return $this->render('books.twig', $properties);
    }

    public function apply(int $id): string {
        $equipmentModel = new EquipmentModel($this->db);
        try {
            $equipment = $equipmentModel->get($id);
        } catch (NotFoundException $e) {
            $this->log->warn('Equipment not found: ' . $id);
            $params = ['errorMessage' => 'Equipment not found.'];
            return $this->render('error.twig', $params);
        }
        if (!$equipment->isIdle()) {
            $params = [
                'errorMessage' => 'This equipment is in use.'
            ];
            return $this->render('error.twig', $params);
        }
        try {
            $equipmentModel->apply($equipment, $this->userId);
        } catch (DbException $e) {
            $this->log->error(
                'Error applying equipment: ' . $e->getMessage()
            );
            $params = ['errorMessage' => 'Error applying equipment.'];
            return $this->render('error.twig', $params);
        }
        return $this->getByUser();
    }

    public function returnEquipment(int $id): string {
        $EquipmentModel = new EquipmentModel($this->db);
        try {
            $equipment = $equipmentModel->get($id);
        } catch (NotFoundException $e) {
            $this->log->warn('Equipment not found: ' . $id);
            $params = ['errorMessage' => 'Equipment not found.'];
            return $this->render('error.twig', $params);
        }
        $equipment->setIdle();
        try {
            $equipmentModel->returnEquipment($equipment, $this->userId);
        } catch (DbException $e) {
            $this->log->error(
                'Error returning equipment: ' . $e->getMessage()
            );
            $params = ['errorMessage' => 'Error returning equipment.'];
            return $this->render('error.twig', $params);
        }
        return $this->getByUser();
    }
}

?>
