<?php
namespace App\Slip\Components;

use App\Slip\Pdf\Pdf;

abstract class ComponentsAbstract
{
    public function __construct(
        protected Pdf $pdf,
        protected float $offsetY
    ) {}
}