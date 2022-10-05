<?php

/**
 * 宛名部分
 */
namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class Name extends ComponentsAbstract
{
    public function addName(
        float $x,
        float $y,
        float $width,
        string $name,
        string $postFix = "様"
    ): self
    {
        $this->pdf
            ->drawLine(
                $x,
                $y + $this->offsetY + 5.2,
                $width
            )
            ->addText(
                $postFix,
                $x + $width - 10,
                $y + $this->offsetY,
                "R",
                10,
                4.5,
                [
                    "font" => new FontSet(10.4)
                ]
            )
            ->addText(
                $name,
                $x,
                $y + $this->offsetY,
                "L",
                $width - 11.2,
                5.2,
                [
                    "font" => new FontSet(11.9, FontSet::FONT_MEDIUM)
                ]
            )
        ;

        return $this;
    }
}