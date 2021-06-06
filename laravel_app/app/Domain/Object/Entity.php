<?php


namespace App\Domain\Object;


class Entity
{
    public function isNewObject(): bool{
        return $this->{static::KEY} === null;
    }
}
