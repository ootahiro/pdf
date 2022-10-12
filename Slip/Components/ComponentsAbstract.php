<?php
namespace App\Slip\Components;

use App\Slip\Pdf\SlipPdf;

abstract class ComponentsAbstract
{
    public function __construct(
        protected SlipPdf $pdf,
        protected float   $offsetY
    ) {}
}