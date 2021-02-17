<?php

namespace App\Services;

use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    private $repo;
    private $repoProfil;
    public function __construct(EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator,UserRepository $repo,ProfilRepository $repoProfil) {
        $this->manager = $manager;
        $this->encoder= $encoder;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->repo=$repo;
        $this->repoProfil=$repoProfil;
    }
    public function addUser(Request $request)
    {
        $user = $request->request->all();
        //dd($user);
        $avatar = $request->files->get("avatar");
        if ($avatar){
            $avatar = fopen($avatar->getRealPath(),"rb");
            //$avatar = $avatar;
        }
        $idProfil = (int)$user["profil"];
        unset($user["profil"]);
        $profil = $this->repoProfil->find($idProfil);
        $entity = substr($profil->getLibelle(), 0, 1).strtolower(substr($profil->getLibelle(), 1));

        $user = $this->serializer->denormalize($user,"App\Entity\\$entity");

        $user->setAvatar($avatar);
        $user->setPassword($this->encoder->encodePassword($user,"pass_1234"));
        $user->setProfil($profil);
        $user->setArchive(0);
        //return $user;
        //dd($user);
        $errors = $this->validator->validate($user);
        if (count($errors)){
            return $errors;
        }
        $this->manager->persist($user);
        $this->manager->flush();
        if ($avatar){
            fclose($avatar);
        }
        return $user;
    }

    /**
     * put image of user
     * @param Request $request
     * @param string|null $fileName
     */
    public function UpdateUser(Request $request,string $fileName = null)
    {
        $raw =$request->getContent();
        $delimiteur = "multipart/form-data; boundary=";
        $boundary= "--" . explode($delimiteur,$request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$raw);
        $elementsTab = explode("\r\n\r\n",$elements);
        $data =[];
        //dd($elementsTab);
        for ($i=0;isset($elementsTab[$i+1]);$i+=2){
            $key = str_replace(["\r\n",' "','"'],'',$elementsTab[$i]);
            if (strchr($key,$fileName)){
                $stream =fopen('php://memory','r+');
                fwrite($stream,$elementsTab[$i +1]);
                rewind($stream);
                $data[$fileName] = $stream;
            }else{
                $val=$elementsTab[$i+1];
                $data[$key] = $val;
            }
        }
        return $data;
    }

}