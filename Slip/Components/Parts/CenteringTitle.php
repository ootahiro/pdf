<?php
/**
 * ページ中央揃えで印字する書類タイトル
 */
namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class CenteringTitle extends ComponentsAbstract
{
    public function addTitle(
        string $title,
        FontSet $fontSet,
        float $y,
        float $borderWidth = 0.6
    ): self
    {
        $textWidth = $this->pdf->getCellWidthByText($title, $fontSet);
        $textHeight = $this->pdf->getCellHeightByText($textWidth, $title, $fontSet);
        $pageWidth = $this->pdf->getPageWidth();
        $x = $pageWidth / 2 - ($textWidth / 2);

        $this->pdf->addText(
            $title,
            $x,
            $y,
            "C",
            $textWidth,
            0,
            [
                "font" => $fontSet
            ]
        );
        if($borderWidth) {
            $this->pdf->drawLine(
                $x,
                $y + $textHeight,
                $textWidth,
                0,
                0.6
            );
        }

        return $this;
    }
}