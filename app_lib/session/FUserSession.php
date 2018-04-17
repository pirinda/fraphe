<?php
namespace Fraphe\Session;

require_once "FUser.php";

class FSession
{
    private $locLang;       // ISO 639-1
    private $locCountry;    // ISO 3166-1
    private $locCurrency;   // ISO 4217
    private $locTimeZone;
    private $curDate;
    private $curUser;

    public function __construct()
    {
        $this->locLang = "es";
        $this->locCountry = "MEX";
        $this->locCurrency = "MXN";
        $this->locTimeZone = "America/Mexico_City";
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

    public function setCurSettings(int $curDate, FUser $curUser)
    {
        $this->curDate = $curDate;
        $this->curUser = $curUser;
    }

    public function getCurDate(): int
    {
        return $this->curDate;
    }

    public function getCurUser(): FUser
    {
        return $this->curUser;
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
