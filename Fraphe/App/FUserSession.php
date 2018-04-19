<?php
namespace Fraphe\App;

class FUserSession
{
    private $locLang;       // ISO 639-1
    private $locCountry;    // ISO 3166-1
    private $locCurrency;   // ISO 4217
    private $locTimeZone;
    private $curUser;
    private $curDate;

    public function __construct(FUser $curUser, int $curDate)
    {
        $this->locLang = "es";
        $this->locCountry = "MEX";
        $this->locCurrency = "MXN";
        $this->locTimeZone = "America/Mexico_City";
        $this->curUser = $curUser;
        $this->curDate = $curDate;
    }

    public function getLocLang(): string
    {
        return $this->locLang;
    }

    public function getLocCountry(): string
    {
        return $this->locCountry;
    }

    public function getLocCurrency(): string
    {
        return $this->locCurrency;
    }

    public function getLocTimeZone(): string
    {
        return $this->locTimeZone;
    }

    public function getCurUser(): FUser
    {
        return $this->curUser;
    }

    public function getCurDate(): int
    {
        return $this->curDate;
    }

    public function getConnection(): PDO
    {
        $connection = null;

        try {
            $connection = new PDO("mysql:host=localhost;port=3306;dbname=fraphe", "root", "msroot");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

        }

        return $connection;
    }
}
