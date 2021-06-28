<?php


namespace App\Application\query\FuelEconomy;


class FuelEconomyQueryConditions
{
    private ?\DateTime $dateStart;
    private ?\DateTime $dateEnd;
    private ?float $amountLow;
    private ?float $amountHigh;
    private ?float $distanceLow;
    private ?float $distanceHigh;
    private ?string $gasStation;
    private ?string $memo;
    private ?int $page;

    /**
     * FuelEconomyQueryConditions constructor.
     * @param ?\DateTime $dateStart
     * @param ?\DateTime $dateEnd
     * @param ?float $amountLow
     * @param ?float $amountHigh
     * @param ?float $distanceLow
     * @param ?float $distanceHigh
     * @param ?string $gasStation
     * @param ?string $memo
     */
    public function __construct(?\DateTime $dateStart,
                                ?\DateTime $dateEnd,
                                ?float $amountLow,
                                ?float $amountHigh,
                                ?float $distanceLow,
                                ?float $distanceHigh,
                                ?string $gasStation,
                                ?string $memo,
                                ?int $page)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->amountLow = $amountLow;
        $this->amountHigh = $amountHigh;
        $this->distanceLow = $distanceLow;
        $this->distanceHigh = $distanceHigh;
        $this->gasStation = $gasStation;
        $this->memo = $memo;
        $this->page = $page;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateStart(): ?\DateTime
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @return float|null
     */
    public function getAmountLow(): ?float
    {
        return $this->amountLow;
    }

    /**
     * @return float|null
     */
    public function getAmountHigh(): ?float
    {
        return $this->amountHigh;
    }

    /**
     * @return float|null
     */
    public function getDistanceLow(): ?float
    {
        return $this->distanceLow;
    }

    /**
     * @return float|null
     */
    public function getDistanceHigh(): ?float
    {
        return $this->distanceHigh;
    }

    /**
     * @return string|null
     */
    public function getGasStation(): ?string
    {
        return $this->gasStation;
    }

    /**
     * @return string|null
     */
    public function getMemo(): ?string
    {
        return $this->memo;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }



}
