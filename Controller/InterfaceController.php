<?php

namespace CCETC\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MyCCE\AppBundle\Resources\xlsTools;
use Symfony\Component\HttpFoundation\Response;

class InterfaceController extends Controller
{

    public function importAction()
    {
        $templateParameters = array();
        $request = $this->getRequest();
        
        $form = $this->createFormBuilder()
            ->add('handlerServiceName', 'text', array('required' => true, 'label' => 'Service Name of Handler (ex: my.bundle.class )'))
            ->add('filePath', 'text', array('required' => true, 'label' => 'XLS file to import (ex: files/myData.xls)'))
        ;
        
        $form = $form->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            if ($form->isValid()) {
                return $this->forward('CCETCImportBundle:XLS:import', array('filePath' => $form->get('filePath')->getData(), 'handlerServiceName' => $form->get('handlerServiceName')->getData() ));
            }
        }
        
        $templateParameters = array(
            'form' => $form->createView(),
        );
        
        return $this->render('CCETCImportBundle::select.html.twig', $templateParameters);
    }
    

}