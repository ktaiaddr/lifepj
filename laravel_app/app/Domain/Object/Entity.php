<?php


namespace App\Domain\Object;


class Entity
{
    public function isNewObject(): bool{
        return $this->{static::KEY} === null;
    }
    public function setIdForNewObject(int $id):void {
        $this->{static::KEY} = $id;
    }
    public function getId():int {
        return $this->{static::KEY};
    }
}
