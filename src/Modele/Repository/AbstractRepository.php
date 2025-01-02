<?php
namespace App\MDS\Modele\Repository;
use App\MDS\Modele\ConnexionBaseDeDonnees;
use App\MDS\Modele\DataObject\AbstractDataObject;
use PDOException;

abstract class AbstractRepository {
    protected abstract function getNomTable(): string;
    protected abstract function construireDepuisTableauSQL(array $objetFormatTableau) : AbstractDataObject;

    protected abstract function getNomClePrimaire() : string;

    protected abstract function getNomsColonnes() : array;
    protected abstract function formatTableauSQL(AbstractDataObject $trajet): array;

    protected abstract function getTags(): array;

    public function recupererParClePrimaire(string $cle) : ?AbstractDataObject {
        $controleur = $this->getNomTable();
        $clePrimaire = $this->getNomClePrimaire();
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * from `$controleur` WHERE `$clePrimaire`='$cle'");
        $tableau = $pdoStatement->fetch();
        if (!$tableau) {
            return null;
        }
        $controleur = substr($controleur, 4); //Enlève le 'MDS_'
         $controleur = "App\MDS\Modele\Repository\\" . ucfirst($controleur) . 'Repository';
         return (new $controleur())->construireDepuisTableauSQL($tableau);
    }

    public function recuperer(): array {
            $pdo = ConnexionBaseDeDonnees::getPdo();
            $sql = "SELECT * FROM " . $this->getNomTable();
            $pdoStatement = $pdo->query($sql);

            $resultats = [];
            foreach ($pdoStatement as $formatTableau) {
                $resultats[] = $this->construireDepuisTableauSQL($formatTableau);
            }
            return $resultats;
        }
    public function supprimer($cle) : int
        {
            $controleur = $this->getNomTable();
            $clePrimaire = $this->getNomClePrimaire();
            $pdo = ConnexionBaseDeDonnees::getPdo();

            $pdoStatement = $pdo->prepare("DELETE FROM `$controleur` WHERE `$clePrimaire`='$cle';");
            $controleur = substr($controleur, 4);

            $controleur = "App\MDS\Modele\Repository\\" . ucfirst($controleur) . 'Repository';

            if (is_null((new $controleur())->recupererParClePrimaire($cle))){
                return 1;
            }
            try {
                $pdoStatement->execute();
                return 0;
            } catch (PDOException $e) {
                echo('Erreur : ' . $e->getMessage());
                return 1;
            }

        }
    public function ajouter(AbstractDataObject $objet): bool
        {
            $controleur = $this->getNomTable();
            $nomsColonnes = $this->getNomsColonnes();
            $arrayColonnes = join(', ', $nomsColonnes);
            $tags = $this->getTags();
            $tags = join(', ', $tags);
            $arrayTags = $this->formatTableauSQL($objet);
            try {
                $pdo = ConnexionBaseDeDonnees::getPdo();
                $pdoStatement = $pdo->prepare("INSERT INTO `$controleur` ($arrayColonnes) VALUES ($tags)");
                $pdoStatement->execute($arrayTags);
                return true;
            } catch (PDOException $e) {
                echo('Erreur : ' . $e->getMessage());
                return false;
            }
        }
    public function mettreAJour(AbstractDataObject $objet): void
        {
            $controleur = $this->getNomTable();
            $nomsColonnes = $this->getNomsColonnes();
            $tags = $this->getTags();
            $arrayTags = $this->formatTableauSQL($objet);
            $requete = "";
            for ($i = 1; $i < count($nomsColonnes); $i++) {
                $requete = $requete . " " . $nomsColonnes[$i] . "= " . $tags[$i];
                if ($i != count($nomsColonnes) - 1) {
                    $requete = $requete . ", ";
                }
            }
            $nomColonne = $nomsColonnes[0];
            $nomColonne = trim($nomColonne, '`');
            $nomColonne = ":" . $nomColonne . 'Tag';
            $nomColonne = $nomsColonnes[0] . "=" . $nomColonne;

            try {
                $pdo = ConnexionBaseDeDonnees::getPdo();
                $pdoStatement = $pdo->prepare("UPDATE `$controleur` SET $requete WHERE $nomColonne");
                $pdoStatement->execute($arrayTags);
            } catch (PDOException $e) {
                echo('Erreur : ' . $e->getMessage());
            }
        }

    }
?>