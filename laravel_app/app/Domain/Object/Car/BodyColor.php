<?php


namespace App\Domain\Object\Car;


class BodyColor
{
    const WHITE = 1;
    const BLACK = 2;
    const RED = 3;
    const BLUE = 4;
    const PURPLE = 5;
    const BROWN = 6;
    const PINK = 7;

    const COLOR_VALUES = [
        self::WHITE   => '白',
        self::BLACK    => '黒',
        self::RED    => '赤',
        self::BLUE    => '青',
        self::PURPLE    => '紫',
        self::BROWN    => '茶',
        self::PINK    => 'ピンク',
    ];
    /** @var int  */
    private int $value;

    /**
     * BodyColor constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {
        if( !array_key_exists($value,self::COLOR_VALUES) )
            throw new \Exception('BodyColorが想定外です', 4401 );

        $this->value = $value;
    }

    public function getBodyColor(): string{
        return self::COLOR_VALUES[$this->value];
    }

}
