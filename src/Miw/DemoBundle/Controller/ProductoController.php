<?php

namespace Miw\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Miw\DemoBundle\Entity\Producto;
use Symfony\Component\HttpFoundation\Response;

class ProductoController extends Controller {

    public function newAction() {
        $producto = new Producto();
        $num = rand(0, 1000);
        $producto->setNombre("Nombre " . $num);
        $producto->setDescripcion("Descripcion " . $num);
        $producto->setPrecio($num);

        $em = $this->getDoctrine()->getManager();
        $em->persist($producto);
        $em->flush();

        return new Response("Id " . $producto->getId());
    }

    public function getAction($id) {
        $producto = $this->getDoctrine()
                ->getRepository('MiwDemoBundle:Producto')
                ->find(intval($id));
//        return new Response($producto);
        return $this->render("MiwDemoBundle:Producto:producto.html.twig", array("producto" => $producto));
    }

}
