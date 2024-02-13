<?php

namespace App\Controller;

use App\Entity\Chimpokodex;
use App\Repository\ChimpokodexRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ChipokomonController extends AbstractController
{
    
    #[Route('/api/chipokomon/{idChimpokodex}', name: 'chipomkodex.get', methods: ['GET'])]
    #[ParamConverter("chimpokodex", options: ["id"=>"idChimpokodex"])]
    /**
     * Retourne tous les entrees du chipokomon de chipokodex
     * 
     * @return JsonResponse
     */
    public function getChipomkodex(Chimpokodex $chimpokodex, SerializerInterface $serializer): JsonResponse
    {
        $jsonChimpokodex = $serializer->serialize($chimpokodex,'json');
        return new JsonResponse($jsonChimpokodex,200,[],true );
    } 

    
    #[Route('/api/chipokomon', name: 'chipomkodex.getAll',methods: ['GET'])]
    /**
     * Retourne tous les entrees du chipokomon de chipokodex
     * 
     * @return JsonResponse
     */
    public function getAllChipomkodex(ChimpokodexRepository $repository, SerializerInterface $serializer): JsonResponse
    {

        $chimpokodexs = $repository->findAll(); 
        $jsonChimpokodex = $serializer->serialize($chimpokodexs,'json');
        return new JsonResponse($jsonChimpokodex,200,[],true );
    } 


    #[Route('/api/chipokomon', name: 'chipomkodex.post', methods: ['POST'])]
    /**
     *  crée un chimpokodex
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param UrlGeneratorInterface $urlGeneratorInterface
     * @return JsonResponse
     */
    public function createChimpokodex(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UrlGeneratorInterface $urlGeneratorInterface): JsonResponse
    {

        $chimpokodex = $serializer->deserialize($request->getContent(),Chimpokodex::class,"json" );
        $dateNow = new \DateTime();

        $chimpokodex->setStatus("ON")
        ->setCreatedAt($dateNow)
        ->setUpdatedAt($dateNow);
        

       $manager->persist($chimpokodex);
       $manager->flush();
      
       $jsonChimpokodex = $serializer->serialize($chimpokodex,'json');

       $location = $urlGeneratorInterface->generate('chipomkodex.get',["idChimpokodex"=>$chimpokodex->getId()],);
       //$request->getContent();

       return new JsonResponse($jsonChimpokodex,Response::HTTP_CREATED,["Location"=>$location], true); 
    } 


    #[Route('/api/chipokomon/{id}', name: 'chipomkodex.update', methods: ['PUT'])]
    /**
     * mettre à  jour chimpokodex
     *
     * @param Chimpokodex $chimpokodex
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function updateChimpokodex(Chimpokodex $chimpokodex , Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): JsonResponse
    {

        $updatedChimpokodex = $serializer->deserialize($request->getContent(), Chimpokodex::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $chimpokodex]);
        $updatedChimpokodex->setUpdatedAt(new DateTime()); 
        $manager->persist($updatedChimpokodex);
        $manager->flush();
       return new JsonResponse(null,Response::HTTP_NO_CONTENT);  
    }

/*     #[Route('/api/chipokomon/{id}', name: 'chipomkodex.delete', methods: ['DELETE'])]
    public function deleteChimpokodex(Chimpokodex $chimpokodex , Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UrlGeneratorInterface $urlGeneratorInterface): JsonResponse
    {

        $manager->remove($chimpokodex);
        $manager->flush();
        return new JsonResponse(null,Response::HTTP_NO_CONTENT);  
    }

 */

    #[Route('/api/chipokomon/{id}', name: 'chipomkodex.softDelete', methods: ['DELETE'])]
    /**
     * Undocumented function
     *
     * @param Chimpokodex $chimpokodex
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param UrlGeneratorInterface $urlGeneratorInterface
     * @return JsonResponse
     */
     public function softDeleteChimpokodexVersusUn( Chimpokodex $chimpokodex,Request $request, EntityManagerInterface $manager, ChimpokodexRepository $chimpokodexRepository): JsonResponse
    { 

        $force = $request->toArray();
        
        if(isset($force["force"]) && $force["force"]){
            $manager->remove($chimpokodex);
        }else{
            $chimpokodex->setStatus("OFF"); 
        }
        $manager->flush();

        

        return new JsonResponse(null,Response::HTTP_NO_CONTENT);  
    } 

}
