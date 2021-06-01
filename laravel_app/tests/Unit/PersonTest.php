<?php

namespace Tests\Unit;

use App\Domain\Object\Name;
use App\Domain\Object\Person;
use App\Domain\Object\Sex;
use Faker\Provider\DateTime;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{

    /**
     * @test
     */
    public function Sex生成のテスト(): Sex{

        $exception_throw = false;
        // 性別の例外テスト
        try{
            new Sex(0);
        }
        catch (\Exception $e) {
            $exception_throw = true;
        }
        $this->assertTrue( $exception_throw );
        $this->assertSame( '性別の値は0又は1です', $e->getMessage()  );
        $this->assertSame( 4301, $e->getCode()  );

        $sex = null;
        $exception_throw = false;
        try{
            $sex = new Sex(1);
        }catch(\Exception $e){
            $exception_throw = true;
        }
        $this->assertFalse( $exception_throw );

        return $sex;
    }

    /**
     * @test
     */
    public function Name生成のテスト(): Name{

        $name = null;
        $exception_throw = false;
        // 姓名の文字数例外が投げられる
        try{
            new Name('','ハナコ');
        }catch(\Exception $e){
            $exception_throw = true;
        }
        $this->assertTrue( $exception_throw );
        $this->assertSame( $e->getMessage(), '姓、名は1文字以上必要です' );
        $this->assertSame( $e->getCode(), 4201 );

        $exception_throw = false;
        try{
            $name = new Name('山田','ハナコ');
        }catch(\Exception $e){
            $exception_throw = true;
        }
        $this->assertFalse( $exception_throw );

        return $name;
    }
    /**
     * @test
     * @depends Name生成のテスト
     * @depends Sex生成のテスト
     * @throws \Exception
     */
    public function Person生成のテスト(Name $name, Sex $sex){

        // id例外テスト
        try{
            new Person(0,$name,
                (new \DateTime())->setDate(2021,5,31),$sex);
        }
        catch (\Exception $e) {
            $this->assertSame( $e->getMessage(), 'idは1以上の数値です' );
            $this->assertSame( $e->getCode(), 4101 );
        }

        //Personのメソッド戻り値テスト
        $person = new Person(1,new Name('山田','太郎'),
            (new \DateTime())->setDate(1979,1,9),$sex);
        $this->assertTrue( ($person instanceof Person) );
        $this->assertSame( $person->getFullName(), '山田 太郎' );
        $this->assertSame( $person->getBornOn(), '1979-01-09' );

        $person = new Person(1,new Name('山田','ハナコ'),
            (new \DateTime())->setDate(2021,5,31),$sex);
        $this->assertSame( $person->getFullName(), '山田 ハナコ' );
        $this->assertSame( $person->getBornOn(), '2021-05-31' );


    }
}
