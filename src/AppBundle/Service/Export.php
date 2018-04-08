<?php
namespace AppBundle\Service;

class Export
{
    public function Export(array $list)
    {

        //Création d'un fichier temporaire'
        $uniqname = uniqid(rand(), true).'.csv';
        $temp_file = fopen($_SERVER["DOCUMENT_ROOT"]."/export/".$uniqname, "a");

        // Insertion des données dans le fichier
        $data = null ;
        foreach ($list as $index => $item) {
            $data = null ;

            $data .= $item->getnom().',';

            $data .= addcslashes($item->getprenom(), "\n\r").',';
            $data .= addcslashes($item->getsociete(), "\n\r").',';
            $data .= addcslashes($item->getadresse(), "\n\r").',';
            $data .= addcslashes($item->getnumfixe(), "\n\r").',';
            $data .= addcslashes($item->getnumport(), "\n\r").',';
            $data .= addcslashes($item->getmail(), "\n\r")."\n";

            fwrite($temp_file, $data);
        }
        //génération de l'URL de téléchargement
        $lien ="/export/".$uniqname;

        //fermeture du fichiers
        fclose($temp_file);

        return $lien;
    }


    public function DeleteOldFile(string $directory)
    {
        $date = new \DateTime();

//        Parcours tous les fichiers du répertoire
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess") {
                $currentModified = filectime($directory."/".$file);

//                Si la date est inférieur au timestamp - 1 h on supprimer le fichiers
                if ($currentModified < $date->getTimestamp() - 3600) {
                    unlink($directory."/".$file);
                }
            }
        }
        closedir($handler);
    }
}
