<?php


namespace App\Domain\Object;


class Sex
{
    /** @var int 1:女性, 2:男性 */
    private int $value;

    /**
     * Sex constructor.
     * @param int $value
     */
    public function __construct(int $value)
    {

        if($value != 1 && $value != 2 ){
            throw new \Exception('性別の値は0又は1です',4301);
        }

        $this->value = $value;
    }


}
