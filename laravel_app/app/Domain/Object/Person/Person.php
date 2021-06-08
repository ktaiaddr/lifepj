<?php
namespace  App\Domain\Object\Person;


use App\Domain\Object\Entity;

class Person extends Entity
{

    const KEY = 'personId';

    /** @var ?int id */
    protected ?int $personId;
    /** @var Name  */
    private Name $name;
    /** @var \DateTime 生年月日 */
    private \DateTime $bornOn;
    /** @var Sex 性別 */
    private Sex $sex;

    /**
     * Person constructor.
     * @param ?int $personId
     * @param Name $name
     * @param \DateTime $bornOn
     * @param \Sex $sex
     * @throws \Exception
     */
    public function __construct(?int $personId, Name $name, \DateTime $bornOn,
                                Sex $sex)
    {
        if( $personId !== null && $personId < 1 )
            throw new \Exception('idは1以上の数値です', 4101);

        $this->personId = $personId;
        $this->name = $name;
        $this->bornOn = $bornOn;
    }


    public function getBornOn(): string{
        return $this->bornOn->format('Y-m-d');
    }
    public function getFullName(): string{
        return $this->name->getFullName();
    }
}
