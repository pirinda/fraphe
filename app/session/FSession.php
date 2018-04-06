<?php
namespace Fraphe\Session;

class FSession
{
    private $locLang;       // ISO 639-1
    private $locCountry;    // ISO 3166-1
    private $locCurrency;   // ISO 4217

    function __construct($name)
    {
        $this->userName = $name;
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
}
