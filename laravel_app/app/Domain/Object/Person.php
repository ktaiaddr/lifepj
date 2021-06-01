<?php
namespace  App\Domain\Object;

class Person
{
    /** @var int id */
    private int $persionId;
    /** @var Name  */
    private Name $name;

    /** @var \DateTime 生年月日 */
    private \DateTime $bornOn;

    /** @var Sex 性別 */
    private Sex $sex;

    /**
     * Person constructor.
     * @param int $persionId
     * @param Name $name
     * @param \DateTime $bornOn
     * @param \Sex $sex
     * @throws \Exception
     */
    public function __construct(int $persionId, Name $name, \DateTime $bornOn,
                                Sex $sex)
    {
        if( $persionId < 1 ){
            throw new \Exception('idは1以上の数値です', 4101);
        }

        $this->persionId = $persionId;
        $this->name = $name;
        $this->bornOn = $bornOn;
    }


    public function getBornOn(){
        return $this->bornOn->format('Y-m-d');
    }
    public function getFullName(): string{
        return $this->name->getFullName();
    }

}
