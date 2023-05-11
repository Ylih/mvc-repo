<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function createProduct(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        $entityManager->persist($product);

        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository
            ->findAll();

        $response = $this->json($products);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/product/show/{prodId}', name: 'product_by_id')]
    public function showProductById(
        ProductRepository $productRepository,
        int $prodId
    ): Response {
        $product = $productRepository
            ->find($prodId);

        return $this->json($product);
    }

    #[Route('/product/delete/{prodId}', name: 'product_delete_by_id')]
    public function deleteProductById(
        ProductRepository $productRepository,
        int $prodId
    ): Response {
        $product = $productRepository->find($prodId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$prodId
            );
        }

        $productRepository->remove($product, true);

        return $this->redirectToRoute('product_show_all');
    }

    #[Route('/product/update/{prodId}/{value}', name: 'product_update')]
    public function updateProduct(
        ProductRepository $productRepository,
        int $prodId,
        int $value
    ): Response {
        $product = $productRepository->find($prodId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$prodId
            );
        }

        $product->setValue($value);
        $productRepository->save($product, true);

        return $this->redirectToRoute('product_show_all');
    }
}
