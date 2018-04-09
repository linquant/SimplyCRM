<?php
namespace AppBundle\Service;

class Export
{
    const DELAI = 3600;

    public function Export(array $list, string $directory)
    {



        //Création d'un fichier temporaire'
        $uniqname = uniqid(rand(), true) . '.csv';
        $temp_file = fopen($_SERVER["DOCUMENT_ROOT"] . "/export/" . $uniqname, "a");

        $data = null;

        //Appelle de la fonction GetGetter pour récuprer les getter de la Class
        $getters = $this->getGetter($list[0]);


        //boucle sur l' Array contenant les données
        foreach ($list as $index => $item) {

            //Initialisation de la variable data -- elle stocke les données a insérer dans le fichier
            $data = null;

            //Boucle sur les getters disponible dans la classe
            foreach ($getters as $index => $getter) {



                //On s'assure que les getters retourne des String ou numérique
                if (is_string($item->$getter()) or is_numeric($item->$getter()) or is_a($item->$getter(),'Datetime')) {

                    if (is_a($item->$getter(),'Datetime')){

                        $data .= addcslashes($item->$getter()->format('d-m-Y'), "\n\r").',';

                    }else{
                        //Ecriture dans a variable de stockage
                        $data .= addcslashes($item->$getter(), "\n\r").',';
                    }

                }
            }

            // Ajout du retour à la ligne
            $data .= "\n";

            //sérialise dans le fichier
            fwrite($temp_file, $data);
        }

        //Création du lien pour télécharger le fichier
        $lien = "/export/" . $uniqname;

        //fermeture du fichiers
        fclose($temp_file);

        $this->DeleteOldFile($directory);

        return $lien;
    }


    /**
     *  Supprime tous les Fichiers de plus d'une heure // Cron task du pauvre :)
     * @param $directory
     * @return string
     */
    public function DeleteOldFile(string $directory)
    {
        $date = new \DateTime();

//        Parcours tous les fichiers du répertoire
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess") {
                $currentModified = filectime($directory . "/" . $file);

//                Si la date est inférieur au timestamp - 1 h on supprimer le fichiers
                if ($currentModified < $date->getTimestamp() - self::DELAI) {
                    unlink($directory . "/" . $file);
                }
            }
        }
        closedir($handler);
    }


    private function getGetter($objet)
    {

        //on récupere le nom de la classe passé en parametre
        $class = get_class($objet);

        // on récuére les méthodes disiponible
        $methods = get_class_methods($class);

        $list_getter = array();

        foreach ($methods as $index => $method) {

            // On ne récupére que les méthodes commençant par get
            if (preg_match("#^get.#", $method)) {
                array_push($list_getter, $method);
            }
        }


        return $list_getter;
    }
}
