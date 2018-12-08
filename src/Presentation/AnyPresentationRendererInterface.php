<?php
declare(strict_types=1);

/*
 * Copyright 2018 by Michael Zapf.
 * Licensed under MIT. See file /LICENSE.
 */

namespace App\Presentation;

use App\Entity\PresentationInterface;

interface AnyPresentationRendererInterface
{
    public function render(PresentationInterface $presentation): string;
}
