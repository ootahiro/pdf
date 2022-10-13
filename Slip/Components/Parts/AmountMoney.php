<?php
/**
 * 金額表記
 */
namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class AmountMoney extends ComponentsAbstract
{
    private float $baseX = 25;
    private float $baseY = 39.4;

    public function setBaseX(float $x): self
    {
        $this->baseX = $x;
        return $this;
    }
    public function setBaseY(float $y): self
    {
        $this->baseY = $y;
        return $this;
    }

    /**
     * 金額表
     * @param float $x
     * @param float $y
     * @return $this
     */
    public function addTotalAmountBlock(
        ?float $x = null,
        ?float $y = null
    ): self
    {
        if(!$x) $x = $this->baseX;
        if(!$y) $y = $this->baseY;
        $y += $this->offsetY;
        $dashLine = [
            "dash" => "1.5,1"
        ];
        (new AmountColumnLine($this->pdf, $this->offsetY))
            ->drawLine(
                $x + 18.9, $y, 8, 9, true
            );
        $this->pdf
            ->drawLine($x, $y, 90, 0, 0.6)
            ->drawLine($x, $y + 9, 90, 0, 0.6)
            ->drawLine($x, $y, 0, 9, 0.6)
            ->drawLine($x + 90, $y, 0, 9, 0.6)
            ->drawLine($x + 18.9, $y, 0, 9)
            ->addText(
                "金　額",
                $x,
                $y,
                "C",
                18.9,
                9,
                [
                    "font" => new FontSet(11.9),
                    "valign" => "M"
                ]
            )
            ;
        return $this;
    }

    /**
     * 消費税等の文言
     * @param float|null $x
     * @param float|null $y
     * @return $this
     */
    public function addTaxDescription(
        ?float $x = null,
        ?float $y = null
    ): self
    {
        if(!$x) $x = $this->baseX;
        if(!$y) $y = $this->baseY + 9;
        $this->pdf->addText(
            "但し、上記領収金額の内、消費税等は",
            $x,
            $y + $this->offsetY,
            "C",
            90,
            8.4,
            [
                "font" => new FontSet(9.7),
                "valign" => "M"
            ]
        );
        return $this;
    }

    /**
     * 消費税金額部分
     * @param float|null $x
     * @param float|null $y
     * @return $this
     */
    public function addTaxAmountBlock(
        ?float $x = null,
        ?float $y = null
    ): self
    {
        if(!$x) $x = $this->baseX + 19.1;
        if(!$y) $y = $this->baseY + 17.5;
        $y += $this->offsetY;
        (new AmountColumnLine($this->pdf, $this->offsetY))
            ->drawLine(
                $x+10.8, $y, 6.4 ,7.4, false
            );
        $this->pdf
            ->drawLine($x, $y, 61.4, 0, 0.6)
            ->drawLine($x, $y+ 7.4, 61.4, 0, 0.6)
            ->drawLine($x, $y, 0, 7.4, 0.6)
            ->drawLine($x+61.4, $y, 0, 7.4, 0.6)
            ->drawLine($x + 10.8, $y, 0, 7.4)
            ->addText(
                "金額",
                $x,
                $y,
                "C",
                10.8,
                7.4,
                [
                    "font" => new FontSet(11),
                    "valign" => "M"
                ]
            )
            ->addText(
                "です。",
                $x + 61.6,
                $y + 2.6,
                "L",
                16
            )
            ;

        return $this;
    }

    public function addLastNote(
        ?float $x = null,
        ?float $y = null
    ): self
    {
        if(!$x) $x = $this->baseX + 12.5;
        if(!$y) $y = $this->baseY + 27.2;
        $y += $this->offsetY;
        $this->pdf->addText(
            "上記金額正に領収しました",
            $x, $y, "L", 60, 0, [
                "font" => new FontSet(9.7)
            ]
        );
        return $this;
    }
}