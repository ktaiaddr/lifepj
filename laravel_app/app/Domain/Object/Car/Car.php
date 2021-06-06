<?php


namespace App\Domain\Object\Car;


class Car
{
    /** @var int  */
    private int $carId;
    /** @var CarName  */
    private CarName $name;
    /** @var int 定員 */
    private int $capacity;
    /** @var BodyColor ボディカラー */
    private BodyColor $color;

    /**
     * Car constructor.
     * @param int $carId
     * @param CarName $name
     * @param int $capacity
     * @param BodyColor|int $color
     * @throws \Exception
     */
    public function __construct(int $carId, CarName $name, int $capacity, BodyColor $color)
    {
        if( $carId < 0 )
            throw new \Exception('caridは0以上の数値が必要です', 4602);

        if( $capacity < 1 )
            throw new \Exception('定員は１名以上必要です', 4601);

        $this->name = $name;
        $this->capacity = $capacity;
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name->getValue();
    }

    /**
     * @return string
     */
    public function getColor(): string{
        return $this->color->getBodyColor();
    }

}
