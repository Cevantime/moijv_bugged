<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Product;
use App\Form\LoanType;
use App\Repository\LoanRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/loan")
 */
class LoanController extends Controller
{
    /**
     * @Route("", name="loans")
     */
    public function loans(ProductRepository $productRepository)
    {
        $products = $productRepository->findPaginatedByLoaner($this->getUser());

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
    /**
     * @Route("/{id}/add", name="add_loan")
     */
    public function add(Product $product, ObjectManager $manager)
    {
        $loan = new Loan();
        $loan->setDateStart(new \DateTime('now'))
                ->setStatus('pending')
                ->setProduct($product)
                ->setLoaner($this->getUser());
        $manager->persist($loan);
        $manager->flush();
        return $this->redirectToRoute('edit_loan', ['id' => $loan->getId()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_loan")
     */
    public function edit(Loan $loan, Request $request, ObjectManager $manager)
    {
        $loanForm = $this->createForm(LoanType::class, $loan)
                ->add('Valider', SubmitType::class);

        $loanForm->handleRequest($request);

        if($loanForm->isSubmitted() && $loanForm->isValid()){
            $manager->persist($loan);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('loan/edit.html.twig',[
            'form' => $loanForm->createView()
        ]);
    }
}
