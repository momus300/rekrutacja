<?php

namespace NaszSystemBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Tests\Controller;

/**
 * Controler extendujacy po klasie z Symfony
 *
 * Class ZalogowanyController
 */
class ManagementController extends Controller
{

    /**
     * Pomijam routing
     * do danego kontrolera bedzie mial dostep tylko szef
     */
    public function szefAction(Request $request)
    {
        /*pobieramy wszytkich pracownikow
        mozna to rozgalezic ze szef zarzadza kierownikami, a kierownicy dalszymi pracownikami, ale tu uproszcozne*/
        $users = $this->getDoctrine()
            ->getRepository('Users')
            ->findAll();

        if ($request->isMethod('POST')) {
            /*walidujemy formularz i redirect do akcji zmianaUprawnien*/
        }

        /*render widoku z podgladem pracowniow
        przy zalozeniu ze to nei tak wielu pracownikow ;)
        jesli wiecej mozna stworzyc widok w ktorym wpisujac minimalnie 3 znaki i AJAXem szukamy uzytkownikow np po nazwisku
        jesli szukamy po nazwisku to z kluczem FULLTEXT na nazwisko w bazie*/
        return $this->render('NaszSystemBundle:Management:szef.html.twig', ['users' => $users]);
    }

    /**
     * Klasa zmieniajaca role uzytkownikowi o przekazanym id w parametrze
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function zmianaUprawnienAction(Request $request, $id)
    {
        /*pobieramy uzytkownika o podanym id, wybranym we wczesniejszym widoku*/
        $user = $this->getDoctrine()
            ->getRepository('Users')
            ->find($id);

        /*pobieramy role jakie moze otrzymac uzytkownik*/
        $roles = $this->getDoctrine()
            ->getRepository('Roles')
            ->findAll();

        if ($request->isMethod('POST')) {
            /*walidujemy formularz i zapis rol*/
            $user->setRoles($request->get('roles'));
            /*voila ;)*/
        }

        /*w danym widoku formularz gdzie wybieramy dla uzytkownika ROLE wlaczamy/wylaczamy*/
        return $this->render('NaszSystemBundle:Management:zmianaUprawnien.html.twig', ['user' => $user, 'roles' => $roles]);
    }


}