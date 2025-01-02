<?php

namespace App\MDS\Modele\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        if (is_null($dureeExpiration)) {
            setcookie($cle, $valeur);
        } else {
            setcookie($cle, $valeur, $dureeExpiration);
        }
    }

    public static function lire(string $cle) : mixed
    {
        if (isset($_COOKIE[$cle])) {
            return $_COOKIE[$cle];
        } else {
            return null;
        }
    }

    public static function supprimer(string $cle) : void
    {
        if (isset($_COOKIE[$cle])) {
            unset($_COOKIE[$cle]);
        } else {
            echo "<br> Erreur : Le cookie n'existe pas";
        }
    }

    public static function tousSupprimer() : void
    {
        $count = 0;
        foreach ($_COOKIE as $cle => $valeur) {
            self::supprimer($cle);
            $count++;
        }
        echo "$count cookies effacées";
    }
}