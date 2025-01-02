<?php
namespace App\MDS\Configuration;
class ConfigurationBaseDeDonnees {
    static private array $configurationBaseDeDonnees = array(
        "nomHote"=>"webinfo.iutmontp.univ-montp2.fr",
        "nomBaseDeDonnees" => "deschanela",
        "port" => "3316",
        "login"=>"deschanela",
        "motDePasse"=>"mot de passe"
    );

    public static function getLogin(): string {
        return self::$configurationBaseDeDonnees['login'];
    }

    public static function getNomHote(): string {
        return self::$configurationBaseDeDonnees['nomHote'];
    }

    public static function getPort(): string {
        return self::$configurationBaseDeDonnees['port'];
    }

    public static function getNomBaseDeDonnees(): string {
        return self::$configurationBaseDeDonnees['nomBaseDeDonnees'];
    }

    public static function getPassword(): string {
        return self::$configurationBaseDeDonnees['motDePasse'];
    }
}
?>