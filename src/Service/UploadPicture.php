<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadPicture
{

    public function save(?UploadedFile $image,String $directory) : String
    {

        $newFilename = "imageDefault.png";

        /**
         * @var UploadedFile $file
         */
        if ($image) {
            //création d'un nom pour mon fichier
            $newFilename = $image->getClientOriginalName();
            //sauvegarde de mon fichier dans le répertoir de mon choix (cf config/services.yaml)
            $image->move($directory, $newFilename);
        }

        return $newFilename;
    }
}