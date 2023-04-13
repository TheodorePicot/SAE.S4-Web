<?php

namespace App\PlusCourtChemin\Configuration;

class ConfigurationBDDPostgreSQL implements ConfigurationBDDInterface
{
    private string $nomBDD = "iut";
    private string $hostname = "162.38.222.142";
    private string $login = "picott";
    private string $mdp = "100630880CE";

    private string $port = "5673";


    public function getLogin(): string
    {
        return $this->login;
    }

    public function getMotDePasse(): string
    {
        return $this->mdp;
    }

    public function getDSN(): string
    {
        return "pgsql:host={$this->hostname};port={$this->port};dbname={$this->nomBDD};options='--client_encoding=UTF8'";
    }

    public function getOptions(): array
    {
        return array();
    }
}
