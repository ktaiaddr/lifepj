<?php


namespace App\Domain\Object\Person;


class Sex
{
    const WOMAN = 1;
    const MAN = 2;

    /** @var int 1:女性, 2:男性 */
    private int $value;

    /**
     * Sex constructor.
     * @param int $value
     * @throws \Exception
     */
    public function __construct(int $value)
    {

        if( $value != self::WOMAN )
            if( $value != self::MAN )
                throw new \Exception('性別の値は1又は2です',4301);

        $this->value = $value;
    }


}
