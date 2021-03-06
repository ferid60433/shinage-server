<?php
declare(strict_types=1);

/*
 * Copyright 2018 by Michael Zapf.
 * Licensed under MIT. See file /LICENSE.
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class OrganizationsController extends Controller
{
    public function indexAction(): Response
    {
        return $this->render('adm/organizations.html.twig', []);
    }
}
