<?php

namespace Coloc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Coloc\MainBundle\Entity\Produit;
use Coloc\MainBundle\Form\ProduitType;
use Coloc\MainBundle\Entity\Message;
use Coloc\MainBundle\Form\MessageType;
use Coloc\MainBundle\Entity\Depenses;
use Coloc\MainBundle\Form\DepensesType;

use DateTime ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('ColocMainBundle::base.html.twig');
    }

    public function bilanBalanceAction()
    {
        $total = array('0' => 0, '1' => 0, '2' => 0);
        $bilan = array('0' => 0, '1' => 0, '2' => 0);
        $depenses = $this->getDoctrine()
            ->getRepository('ColocMainBundle:Depenses')
            ->createQueryBuilder('e')
            ->select('e')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        foreach ($depenses as &$depense) {
            if (($depense['paye_par'] == 0 && $depense['nbPartEva'] != 0) || ($depense['paye_par'] == 1 && $depense['nbPartRobin'] != 0) || ($depense['paye_par'] == 2 && $depense['nbPartSylvain'] != 0) ) {
                $total[$depense['paye_par']] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
            } else {
                if ($depense['paye_par'] == 0) {
                    $total[0] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
                    $total[1] -= $depense['montant'] * $depense['nbPartRobin']/$depense['nbPartTotal'];
                    $total[2] -= $depense['montant'] * $depense['nbPartSylvain']/$depense['nbPartTotal'];
                } elseif ($depense['paye_par'] == 1) {
                    $total[1] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
                    $total[0] -= $depense['montant'] * $depense['nbPartEva']/$depense['nbPartTotal'];
                    $total[2] -= $depense['montant'] * $depense['nbPartSylvain']/$depense['nbPartTotal'];
                } elseif ($depense['paye_par'] == 2) {
                    $total[2] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
                    $total[0] -= $depense['montant'] * $depense['nbPartEva']/$depense['nbPartTotal'];
                    $total[1] -= $depense['montant'] * $depense['nbPartRobin']/$depense['nbPartTotal'];
                }
            }


            $bilan[$depense['paye_par']] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
            $bilan[0] -= $depense['montant'] * $depense['nbPartEva']/$depense['nbPartTotal'];
            $bilan[1] -= $depense['montant'] * $depense['nbPartRobin']/$depense['nbPartTotal'];
            $bilan[2] -= $depense['montant'] * $depense['nbPartSylvain']/$depense['nbPartTotal'];
        }

        $nul = 0;
        if ($bilan[0] == $bilan[1] && $bilan[0] == 0) {
            $nul = 1;
            return $this->render('ColocMainBundle::bilan_balance.html.twig', array("bilan"=>$bilan, "nul" => $nul) );
        }
        $noms = array(0=>'Eva', 1=>'Robin', 2=>'Sylvain');
        $bilan_copy = array('Eva'=>$bilan[0], 'Robin'=>$bilan[1], 'Sylvain'=>$bilan[2]);
        $operations = array();
        $i = 0;
        while ($i<20 && (abs($bilan_copy['Eva']) > 0.01 || abs($bilan_copy['Robin']) > 0.01)) {
            $operation = array(0=>array_search(min($bilan_copy), $bilan_copy),
                1=>array_search(max($bilan_copy), $bilan_copy),
                2=>min(-min($bilan_copy), max($bilan_copy)));
            array_push($operations, $operation);
            $bilan_copy[$operation[0]] += $operation[2];
            $bilan_copy[$operation[1]] -= $operation[2];
        }
        return $this->render('ColocMainBundle::bilan_balance.html.twig', array("bilan"=>$bilan, "nul" => $nul, "operations"=>$operations) );
    }

    public function bilanTotalAction()
    {
        $total = array('0' => 0, '1' => 0, '2' => 0);
        $bilan = array('0' => 0, '1' => 0, '2' => 0);
        $depenses = $this->getDoctrine()
            ->getRepository('ColocMainBundle:Depenses')
            ->createQueryBuilder('e')
            ->select('e')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        foreach ($depenses as &$depense) {
            if (($depense['paye_par'] == 0 && $depense['nbPartEva'] != 0) || ($depense['paye_par'] == 1 && $depense['nbPartRobin'] != 0) || ($depense['paye_par'] == 2 && $depense['nbPartSylvain'] != 0) ) {
                $total[$depense['paye_par']] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
            } else {
                if ($depense['paye_par'] == 0) {
                    $total[0] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
                    $total[1] -= $depense['montant'] * $depense['nbPartRobin']/$depense['nbPartTotal'];
                    $total[2] -= $depense['montant'] * $depense['nbPartSylvain']/$depense['nbPartTotal'];
                } elseif ($depense['paye_par'] == 1) {
                    $total[1] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
                    $total[0] -= $depense['montant'] * $depense['nbPartEva']/$depense['nbPartTotal'];
                    $total[2] -= $depense['montant'] * $depense['nbPartSylvain']/$depense['nbPartTotal'];
                } elseif ($depense['paye_par'] == 2) {
                    $total[2] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
                    $total[0] -= $depense['montant'] * $depense['nbPartEva']/$depense['nbPartTotal'];
                    $total[1] -= $depense['montant'] * $depense['nbPartRobin']/$depense['nbPartTotal'];
                }
            }


            $bilan[$depense['paye_par']] += $depense['montant'] * $depense['nbPartTotalColoc']/$depense['nbPartTotal'];
            $bilan[0] -= $depense['montant'] * $depense['nbPartEva']/$depense['nbPartTotal'];
            $bilan[1] -= $depense['montant'] * $depense['nbPartRobin']/$depense['nbPartTotal'];
            $bilan[2] -= $depense['montant'] * $depense['nbPartSylvain']/$depense['nbPartTotal'];
        }

        $nul = 0;
        if ($bilan[0] == $bilan[1] && $bilan[0] == 0) {
            $nul = 1;
            return $this->render('ColocMainBundle::bilan_total.html.twig', array("total"=>$total, "nul" => $nul) );
        }
        $bilan_copy = array('Eva'=>$bilan[0], 'Robin'=>$bilan[1], 'Sylvain'=>$bilan[2]);
        $operations = array();
        $i = 0;
        while ($i<20 && (abs($bilan_copy['Eva']) > 0.01 || abs($bilan_copy['Robin']) > 0.01)) {
            $operation = array(0=>array_search(min($bilan_copy), $bilan_copy),
                1=>array_search(max($bilan_copy), $bilan_copy),
                2=>min(-min($bilan_copy), max($bilan_copy)));
            array_push($operations, $operation);
            $bilan_copy[$operation[0]] += $operation[2];
            $bilan_copy[$operation[1]] -= $operation[2];
        }
        return $this->render('ColocMainBundle::bilan_total.html.twig', array("total"=>$total, "nul" => $nul, "operations"=>$operations) );
    }

    public function messagesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('coloc_messages');
        }
        $repo = $em->getRepository('ColocMainBundle:Message');
        $messages = $repo->findAll();
        return $this->render('ColocMainBundle::messages.html.twig', array("form" => $form->createView(),
            "messages" => $messages));
    }

    public function messagesDeleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('coloc_messages');
        }
        $repo = $em->getRepository('ColocMainBundle:Message');
        $deleted = $repo->find($id);
        $em->remove($deleted);
        $em->flush();
        $messages = $repo->findAll();
        return $this->render('ColocMainBundle::messages.html.twig', array("form" => $form->createView(),
            "messages" => $messages));
    }


    public function coursesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setPanier(0);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('coloc_courses');
        }
        $repo = $em->getRepository('ColocMainBundle:Produit');
        $produits = $repo->findBy(array("panier"=>0));
        $produits_panier = $repo->findBy(array("panier"=>1));
        return $this->render('ColocMainBundle::courses.html.twig', array("form" => $form->createView(),
            "produits" => $produits,
            "produits_panier" => $produits_panier));
    }

    public function coursesDeleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setPanier(0);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('coloc_courses');
        }

        $repo = $em->getRepository('ColocMainBundle:Produit');
        $deleted = $repo->find($id);
        $em->remove($deleted);
        $em->flush();
        $produits = $repo->findBy(array("panier"=>0));
        $produits_panier = $repo->findBy(array("panier"=>1));
        return $this->render('ColocMainBundle::courses.html.twig', array("form" => $form->createView(),
            "produits" => $produits,
            "produits_panier" => $produits_panier));
    }

    public function coursesSwitchAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setPanier(0);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('coloc_courses');
        }

        $repo = $em->getRepository('ColocMainBundle:Produit');
        $changed = $repo->find($id);
        $changed->setPanier(1-$changed->getPanier());
        $em->flush();
        $produits = $repo->findBy(array("panier"=>0));
        $produits_panier = $repo->findBy(array("panier"=>1));
        return $this->render('ColocMainBundle::courses.html.twig', array("form" => $form->createView(),
            "produits" => $produits,
            "produits_panier" => $produits_panier));
    }

    public function coursesDoneAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ColocMainBundle:Produit');
        $deleted = $repo->findBy(array("panier"=>1));
        $depense = new Depenses();
        $depense->setNom('Courses');
        $depense->setDate(new DateTime());
        $depense->setNbPartEva(1);
        $depense->setNbPartRobin(1);
        $depense->setNbPartSylvain(1);
        $depense->setNbPartAutres(0);
        $form = $this->createForm(DepensesType::class, $depense);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $this->getDoctrine()
                ->getRepository('ColocMainBundle:Produit')
                ->createQueryBuilder('e')
                ->delete()
                ->where('e.panier = :on')
                ->setParameter('on', 1)
                ->getQuery()
                ->getResult();
            $depense -> setNbPartTotal($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin()+$depense->getNbPartAutres());
            $depense -> setNbPartTotalColoc($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin());
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('coloc_depenses');
        }
        return $this->render('ColocMainBundle::depenses_new.html.twig', array("form" => $form->createView()));
    }

    public function depensesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ColocMainBundle:Depenses');
        $depenses = $repo->findBy(array(), array('date'=>'DESC'));
        return $this->render('ColocMainBundle::depenses.html.twig', array('depenses'=>$depenses));
    }

    public function depensesDeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ColocMainBundle:Depenses');
        $deleted = $repo->find($id);
        $em->remove($deleted);
        $em->flush();
        $depenses = $repo->findBy(array(), array('date'=>'DESC'));
        return $this->render('ColocMainBundle::depenses.html.twig', array('depenses'=>$depenses));
    }

    public function depensesModifyAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ColocMainBundle:Depenses');
        $depense = $repo->find($id);
        $form = $this->createForm(DepensesType::class, $depense);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $depense -> setNbPartTotal($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin()+$depense->getNbPartAutres());
            $depense -> setNbPartTotalColoc($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin());
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('coloc_depenses');
        }
        return $this->render('ColocMainBundle::depenses_new.html.twig', array("form" => $form->createView()));
    }

    public function depensesNewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $depense = new Depenses();
        $depense->setDate(new DateTime());
        $depense->setNbPartEva(1);
        $depense->setNbPartRobin(1);
        $depense->setNbPartSylvain(1);
        $depense->setNbPartAutres(0);
        $form = $this->createForm(DepensesType::class, $depense);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $depense -> setNbPartTotal($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin()+$depense->getNbPartAutres());
            $depense -> setNbPartTotalColoc($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin());
            $em->persist($depense);
            $em->flush();
            return $this->redirectToRoute('coloc_depenses');
        }
        return $this->render('ColocMainBundle::depenses_new.html.twig', array("form" => $form->createView()));
    }

    public function refundAction($paye_par, $recu_par, $montant, Request $request)
    {
        $noms = array(0=>'Eva', 1=>'Robin', 2=>'Sylvain');
        $em = $this->getDoctrine()->getManager();
        $depense = new Depenses();
        $depense->setDate(new DateTime());
        $depense->setNom($paye_par . '->' . $recu_par);
        $depense->setNbPartAutres(0);
        $depense->setPayePar(array_search($paye_par, $noms));
        $depense->setMontant($montant);
        if ($recu_par == 'Eva') {
            $depense->setNbPartEva(1);
            $depense->setNbPartRobin(0);
            $depense->setNbPartSylvain(0);

        } elseif ($recu_par == 'Robin') {
            $depense->setNbPartEva(0);
            $depense->setNbPartRobin(1);
            $depense->setNbPartSylvain(0);
        } else {
            $depense->setNbPartEva(0);
            $depense->setNbPartRobin(0);
            $depense->setNbPartSylvain(1);
        }
        $depense -> setNbPartTotal($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin()+$depense->getNbPartAutres());
        $depense -> setNbPartTotalColoc($depense->getNbPartEva()+$depense->getNbPartSylvain()+$depense->getNbPartRobin());
        $em->persist($depense);
        $em->flush();

        $repo = $em->getRepository('ColocMainBundle:Depenses');
        $depenses = $repo->findBy(array(), array('date'=>'DESC'));
        return $this->render('ColocMainBundle::depenses.html.twig', array('depenses'=>$depenses));
    }

    public function ageAction()
    {
        $datetime1 = new DateTime('2018-03-06');
        $datetime2 = new DateTime('2019-06-04');
        $datetime3 = new DateTime('2019-04-29');
        $datetime = new DateTime();
        $diff = $datetime->diff($datetime1);
        $duree_eva = $datetime3->diff($datetime)->days;
        $duree_robin = $datetime2->diff($datetime)->days;
        $age_sylvain = 22 + $diff->days;
        return $this->render('ColocMainBundle::age.html.twig', array("sylvain"=>$age_sylvain,
            "robin"=>$duree_robin,
            "eva"=>$duree_eva));
    }
}
