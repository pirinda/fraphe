<?php
namespace Fraphe\App;

class FUserSession
{
    private $locLang;       // ISO 639-1
    private $locCountry;    // ISO 3166-1
    private $locCurrency;   // ISO 4217
    private $locTimeZone;
    private $curUser;
    private $curUserLoginTs;

    public function __construct(FUser $curUser, \DateTime $curUserLoginTs)
    {
        $this->locLang = "es";
        $this->locCountry = "MEX";
        $this->locCurrency = "MXN";
        $this->locTimeZone = "America/Mexico_City";
        $this->curUser = $curUser;
        $this->curUserLoginTs = $curUserLoginTs;
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

    public function getCurUserLoginTs(): \DateTime
    {
        return $this->curUserLoginTs;
    }
}
