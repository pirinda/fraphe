<?php
namespace Fraphe\Session;

class FSession
{
    private $locLang;       // ISO 639-1
    private $locCountry;    // ISO 3166-1
    private $locCurrency;   // ISO 4217
    private $locTimeZone;
    private $curDate;
    private $curUser;

    function __construct()
    {
        $this->locLang = "es";
        $this->locCountry = "MEX";
        $this->locCurrency = "MXN";
        $this->locTimeZone = "America/Mexico_City";
    }

    public function getLocLang()
    {
        return $this->locLang;
    }

    public function getLocCountry()
    {
        return $this->locCountry;
    }

    public function getLocCurrency()
    {
        return $this->locCurrency;
    }

    public function getLocTimeZone()
    {
        return $this->locTimeZone;
    }

    public function setCurSettings($curDate, $curUser)
    {
        $this->curDate = $curDate;
        $this->curUser = $curUser;
    }

    public function getCurDate()
    {
        return $this->curDate;
    }

    public function getCurUser()
    {
        return $this->curUser;
    }

    public function getConnection()
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
