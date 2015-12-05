<?php

namespace Miw\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Miw\DemoBundle\Entity\Producto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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

    /**
     *
     * @param type $id Id del producto
     * @param type $format Formato de la salida
     * @return Response
     */
    public function getAction($id, $format) {
        $producto = $this->getDoctrine()
                ->getRepository('MiwDemoBundle:Producto')
                ->find(intval($id));

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        if ($format == 'json') {
            $jsonContent = $serializer->serialize($producto, $format);
            return new Response($jsonContent);
        } elseif ($format == 'xml') {
            $xmlContent = $serializer->serialize($producto, $format);
            return new Response($xmlContent);
        } else {
            return $this->render("MiwDemoBundle:Producto:producto.html.twig", array("producto" => $producto));
        }
    }

    public function getAllAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM MiwDemoBundle:Producto p";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $productos = $paginator->paginate(
                $query, /* query NOT result */ $request->query->getInt('page', 1)/* page number */, 3/* limit per page */
        );

//        $productos = $this->getDoctrine()->getManager()
//                ->getRepository('MiwDemoBundle:Producto')
//                ->findAll();

        return $this->render("MiwDemoBundle:Producto:productos.html.twig", array("productos" => $productos));
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $producto = $em->getRepository('MiwDemoBundle:Producto')->find($id);
        $em->remove($producto);
        $em->flush();
        return $this->redirectToRoute("miw_producto_getAll");
    }

}
