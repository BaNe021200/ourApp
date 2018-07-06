<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Thumbnails;
use App\Entity\Images;

class DownloadController extends Controller
{

    public function download($ville)
    {
        return $this->render('images/'.$ville.'/download'.$ville.'.html.twig',[

        ]);
    }


    public function upload($ville)
    {
        $messages=[];

        foreach ($_FILES as $file)
        {
            if($file['error']== UPLOAD_ERR_NO_FILE)
            {
                continue;
            }


            $destinationPath='img/'.$ville.'/'.$file['name'];
            $temporaryPath= $file['tmp_name'];
            if(move_uploaded_file($temporaryPath,$destinationPath))
            {
                $messages[] = "le fichier ".$file['name']." a été correctement uploadé";
                $entityManager = $this->getDoctrine()->getManager();
                $image = new Images();


                $image->setDirname('img/'.$ville);
                $image->setBasename($file['name']);
                $entityManager->persist($image);
                $entityManager->flush();
                $imageId= $image->getId();

                $thumbnail = new Thumbnails();
                $thumbnail->setImagesId($imageId);
                $thumbnail->setDirname('img/'.$ville.'/thumbs/');
                $thumbnail->setBasename($file['name']);
                $entityManager->persist($thumbnail);
                $entityManager->flush();




            }
            else
            {
                $messages[] = "le fichier ".$file['name']." n'a pas été correctement uploadé";
            }
            $this->thumbNails(75,125,$ville);

        }
        return $this->render('images/'.$ville.'/success'.$ville.'.html.twig',[
            'message' => $messages,
        ]);
    }


    function thumbNails($width,$height,$ville){
        //$user= new oldUserManager();
        //$images=$user->getPhoto2Thumb($userId,$photoId);






            $src = glob('img/'.$ville.'/*.jpg');


            foreach ($src as $image)
            {
                $infoName = pathinfo($image);
                $dirname= $infoName['dirname'];
                $fileName = $infoName['basename'];

                $picture = $dirname.'/'.$fileName;





            @$image = imagecreatefromjpeg($picture);


            $size = getimagesize($picture);
            @$thumb = imagecreatetruecolor($width, $height);
            // On va gérer la position et le redimensionnement de la grande image
            if ($size[0] > ($width / $height) * $size[1]) {
                $dimY = $height;
                $dimX = $height * $size[0] / $size[1];
                $decalX = -($dimX - $width) / 2;
                $decalY = 0;
            }
            if ($size[0] < ($width / $height) * $size[1]) {
                $dimX = $width;
                $dimY = $width * $size[1] / $size[0];
                $decalY = -($dimY - $height) / 2;
                $decalX = 0;
            }
            if ($size[0] == ($width / $height) * $size[1]) {
                $dimX = $width;
                $dimY = $height;
                $decalX = 0;
                $decalY = 0;
            }
            // on modifie l'image crée en y plaçant la grande image redimensionné et décalée
            if ($image == false) {
            }
            else
            {

                @imagecopyresampled($thumb, $image, intval($decalX), intval($decalY), 0, 0, intval($dimX), intval($dimY), $size[0], $size[1]);
                // On sauvegarde le tout
                $imageThumbnail = imagejpeg($thumb, 'img/'.$ville.'/thumbs/' . $fileName );
            }


            }

                //$saveThumbnail = $user->addThumbnails($userId, $photoId, $dirname, $fileName);


    }


    public function downloadBesse()
    {

        return ($this->download('Besse'));
    }

    function uploadBesse()
    {
        return($this->upload("Besse"));
    }



    public function downloadSigale()
    {

        return ($this->download("Sigale"));
    }

    function uploadSigale()
    {
        return($this->upload("Sigale"));
    }

    public function downloadParis()
    {

        return ($this->download("Paris"));
    }

    function uploadParis()
    {
        return($this->upload("Paris"));
    }




}
