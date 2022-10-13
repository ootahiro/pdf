<?php

namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;

class AmountColumnLine extends ComponentsAbstract
{
    public array $normalLine = [
        "color" => [0,0,0]
    ];
    public array $dashLine = [
        "dash" => "1.5,1",
        "color" => [105,105,105]
    ];

    public function drawLine(float $x, float $y, float $span, float $height, bool $addBillion = false): void
    {
        $x += $span;
        if($addBillion) {
            $x = $this->addLine($x, $y, $span, $height, true);
        }
        $x = $this->addLine($x, $y, $span, $height, true);
        $x = $this->addLine($x, $y, $span, $height, false);
        $x = $this->addLine($x, $y, $span, $height, true);
        $x = $this->addLine($x, $y, $span, $height, true);
        $x = $this->addLine($x, $y, $span, $height, false);
        $x = $this->addLine($x, $y, $span, $height, true);
        $this->addLine($x, $y, $span, $height, true);
        $this->pdf->setLineStyle([
            "color" => [0,0,0]
        ]);
    }

    private function addLine($x, $y, $span, $height, bool $dash): float
    {
        $this->pdf->drawLine($x, $y, 0, $height, 0.2, $dash? $this->dashLine: $this->normalLine);
        return $x + $span;
    }
}