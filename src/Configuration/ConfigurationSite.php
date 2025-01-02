<?php

namespace App\MDS\Configuration;

class ConfigurationSite {
    public static $session_expiration = 10;

    public static function getURLAbsolue() : string {
        return "http://localhost/projetphp/web/controleurFrontal.php";
    }

    public static function getDebug() : bool {
        return false;
    }
}