<?php

namespace App\PlusCourtChemin\Configuration;

class Configuration
{
    // la variable debug est un boolean
    static private bool $debug = false;

    public ConfigurationBDDInterface $configurationBDD;


    public function __construct(ConfigurationBDDInterface $configurationBDD)
    {
        $this->configurationBDD = $configurationBDD;
    }

    public function getConfigurationBDD(): ConfigurationBDDInterface
    {
        return $this->configurationBDD;
    }

    static public function getDebug(): bool
    {
        return Configuration::$debug;
    }

    public static function getDureeExpirationSession(): string
    {
        // Durée d'expiration des sessions en secondes
        return 3600;
    }

    public static function getAbsoluteURL(): string
    {
        return 'https://webinfo.iutmontp.univ-montp2.fr/~picott/SAE.S4-Web/web/controleurFrontal.php';
    }

}