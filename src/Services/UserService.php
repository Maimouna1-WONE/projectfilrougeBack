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
    public function addUser(Request $request,$entity)
    {
        $user = $request->request->all();
        $avatar = $request->files->get("avatar");
        if ($avatar){
            $avatar = fopen($avatar->getRealPath(),"rb");
            $user["avatar"] = $avatar;
        }
        $user = $this->serializer->denormalize($user,$entity);
        $errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $user->setPassword($this->encoder->encodePassword($user,$user->getPlainPassword()));
        if ($entity==="App\Entity\Admin") {
            $user->setProfil($this->repoProfil->findOneByLibelle("ADMIN"));
        }
        if ($entity==="App\Entity\Formateur") {
            $user->setProfil($this->repoProfil->findOneByLibelle("FORMATEUR"));
        }
        if ($entity==="App\Entity\Apprenant") {
            $user->setProfil($this->repoProfil->findOneByLibelle("APPRENAijNT"));
        }
        if ($entity==="App\Entity\Cm") {
            $user->setProfil($this->repoProfil->findOneByLibelle("CM"));
        }
        $user->setArchive(1);
        $this->manager->persist($user);
        $this->manager->flush();
        if ($avatar){
            fclose($avatar);
        }
    }

    /**
     * put image of user
     * @param Request $request
     * @param string|null $fileName
     * @return array
     */
    public function UpdateUser(Request $request,string $fileName = null): array
    {
        $raw =$request->getContent();
        $delimiteur = "multipart/form-data; boundary=";
        $boundary= "--" . explode($delimiteur,$request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$raw);
        $elementsTab = explode("\r\n\r\n",$elements);
        $data =[];
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