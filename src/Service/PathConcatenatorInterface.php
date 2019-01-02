<?php
declare(strict_types=1);

/*
 * Copyright 2018 by Michael Zapf.
 * Licensed under MIT. See file /LICENSE.
 */

namespace App\Service;

interface PathConcatenatorInterface
{
    public function concatTwo(string $left, string $right): string;
}