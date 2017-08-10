<?php
namespace easyPM\Domain;

class Equipment {
    private $id;    // 系统用ID
    private $u_code;  // 公司统一编码
    private $type;
    private $catalog;
    private $description;
    private $status;
    // private $owner;	// 使用者的用户id
    private $imgfile;
    
    const IDLE = 0;
    const IN_USE = 1;

    public function getId(): int {return $this->id;}

    public function getUCode(): string {return $this->u_code;}

    public function getType(): string {return $this->type;}

    public function getCatalog(): string {return $this->catalog;}

    public function getDescription(): string {return $this->description;}

    // public function getOwner(): int {return $this->owner;}

    public function isIdle(): bool {return $this->status == self::IDLE;}

    public function setIdle() { $this->status = self::IDLE; }

    public function setInUse() { $this->status = self::IN_USE; }
}
