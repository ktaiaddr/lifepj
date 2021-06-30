<?php


namespace App\Domain\Model\Car;


class CarName
{
    private string $value;

    /**
     * CarName constructor.
     * @param string $value
     * @throws \Exception
     */
    public function __construct(string $value)
    {
        if( mb_strlen($value) < 1 )
            throw new \Exception('車名は１文字以上必要です', 4501);

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }


}
