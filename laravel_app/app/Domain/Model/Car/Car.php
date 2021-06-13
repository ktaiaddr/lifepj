<?php


namespace App\Domain\Model\Car;


use Exception;

class Car
{
    /** @var int  */
    private int $carId;
    /** @var CarName  */
    private CarName $name;
    /** @var int 定員 */
    private int $capacity;
    /** @var CarColor ボディカラー */
    private CarColor $color;

    /**
     * Car constructor.
     * @param int $carId
     * @param CarName $name
     * @param int $capacity
     * @param CarColor $color
     * @throws Exception
     */
    public function __construct(int $carId, CarName $name, int $capacity, CarColor $color)
    {
        if( $carId < 0 )
            throw new Exception('caridは0以上の数値が必要です', 4602);

        if( $capacity < 1 )
            throw new Exception('定員は１名以上必要です', 4601);

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
