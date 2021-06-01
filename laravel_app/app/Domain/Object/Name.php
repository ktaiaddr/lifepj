<?php


namespace App\Domain\Object;


class Name
{
    /** @var string 名 */
    private string $firstName;
    /** @var string 姓 */
    private string $lastName;

    /**
     * Name constructor.
     * @param string $firstName
     * @param string $lastName
     * @throws \Exception
     */
    public function __construct(string $lastName, string $firstName)
    {
        if( mb_strlen($lastName) < 1 || mb_strlen($firstName) < 1)
            throw new \Exception('姓、名は1文字以上必要です', 4201);

        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFullName(): string{
        return $this->lastName.' '.$this->firstName;
    }
}
