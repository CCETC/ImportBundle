<?php

namespace CCETC\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MyCCE\AppBundle\Resources\xlsTools;
use Symfony\Component\HttpFoundation\Response;

class XLSController extends Controller
{

    public function importAction($filePath, $handlerServiceName)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit ( 0 );

        $handler = $this->container->get($handlerServiceName);
        $handler->init($filePath);
        $handler->processRows();                
                
        $templateParameters = array();
        
        if(isset($handler)) {
            $templateParameters['insertions'] = $handler->insertions;
            $templateParameters['updates'] = $handler->updates;
            $templateParameters['deletions'] = $handler->deletions;
            $templateParameters['duplicates'] = $handler->duplicates;
            $templateParameters['notFound'] = $handler->notFound;
        }
        return $this->render('CCETCImportBundle::results.html.twig', $templateParameters);
    }
    

}