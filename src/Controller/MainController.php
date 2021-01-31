<?php

namespace App\Controller;

use App\Entity\Koszyk;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $item_list = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('main/index.html.twig', [
            'products' => $item_list,
        ]);
    }

    /**
     * @Route("/cart", name="cart")
     * @param Request $request
     * @return Response
     */
    public function cart(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException('Customer not found');
        }

        $cart = $user->getKid();

        if (!$cart) {
            throw new NotFoundHttpException('Cart does not exist for this customer');
        }

        $products = $cart->getProducts();

        return $this->render('main/products.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/addtocart", name="addtocart", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addtocart(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException('Customer not found');
        }

        $cart = $user->getKid();

        if (!$cart) {
            throw new NotFoundHttpException('Cart does not exist for this customer');
        }

        $data = $request->get('pid');
        $product = $this->getDoctrine()->getManager()->find('App\Entity\Product', $data);

        $cart->addProduct($product);

        $this->getDoctrine()->getManager()->persist($cart);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect('/cart');
    }

    /**
     * @Route("/delfromcart", name="delfromcart", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function delfromcart(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw new NotFoundHttpException('Customer not found');
        }

        $cart = $user->getKid();

        if (!$cart) {
            throw new NotFoundHttpException('Cart does not exist for this customer');
        }

        $data = $request->get('pid');
        $product = $this->getDoctrine()->getManager()->find('App\Entity\Product', $data);

        $cart->removeProduct($product);

        $this->getDoctrine()->getManager()->persist($cart);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect('/cart');
    }

    /**
     * @Route("/products", name="getProducts", methods={"GET"})
     */
    public function getProducts(Request $request): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->json($products);
    }
    
    /**
     * @Route("/register", name="addUser")
     * @param Request $request
     * @return Response
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password']
            ])
            ->add('register', SubmitType::class, [
                'attr' => [

                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $user = new User();
                $koszyk = new Koszyk();
                $user->setKid(null);
                $user->setName($data['username']);
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $data['password'])
                );


                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                $koszyk->setUid($user);
                $this->getDoctrine()->getManager()->persist($koszyk);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirect('/');
            }
        }

        return $this->render('main/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="addProduct")
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('customId')
            ->add('add', SubmitType::class, [
                'attr' => [

                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                $product = new Product();

                $product->setName($data['name']);
                $product->setDescription($data['description']);
                $product->setPid($data['customId']);
                $product->setPrice(floatval($data['price']));

                $this->getDoctrine()->getManager()->persist($product);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->render('main/addProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
