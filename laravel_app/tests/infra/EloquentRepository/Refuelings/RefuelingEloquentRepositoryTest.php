<?php

namespace Tests\infra\EloquentRepository;

use App\Domain\Model\Refuelings\FuelEconomy;
use App\Domain\Model\Refuelings\IRefuelingRepository;
use App\Domain\Model\Refuelings\Refueling;
use App\infra\EloquentRepository\Refuelings\RefuelingEloquentRepository;
//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RefuelingEloquentRepositoryTest extends TestCase
{

    private IRefuelingRepository $elqRefuelingRepository;
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare('truncate refuelings');
        $stmt->execute();
        $this->elqRefuelingRepository = new RefuelingEloquentRepository();

    }

    protected function tearDown(): void
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare('truncate refuelings');
        $stmt->execute();

        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /**
     * @throws \Exception
     */
    public function test_save(){

        $refueling = new Refueling(null, 1, new \DateTime(),
            new FuelEconomy(30,450),
            'gasStation1','memo1', 0);
        $this->elqRefuelingRepository->save($refueling);

        $refueling2 = new Refueling(null, 1, new \DateTime('2021-01-02'),
            new FuelEconomy(31,500),
            'gasStation2','memo2', 0);
        $this->elqRefuelingRepository->save($refueling2);

        $refueling2 = $this->elqRefuelingRepository->find(2, 1);
        $this->assertTrue( $refueling2 instanceof Refueling);

        $refueling = new Refueling(null, 1, new \DateTime(),
            new FuelEconomy(30,450),
            'gasStation1','memo３', 0);
        $this->elqRefuelingRepository->save($refueling);

        return true;
    }
}
