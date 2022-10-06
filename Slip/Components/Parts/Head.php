<?php
/**
 * 書類のヘッダーにあるパーツ
 */
namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class Head extends ComponentsAbstract
{
    /**
     * 宛名記入欄
     * @param float $x
     * @param float $y
     * @param float $width
     * @param string $name
     * @param string $postFix
     * @return $this
     */
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
                0,
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
                0,
                [
                    "font" => new FontSet(11.9, FontSet::FONT_MEDIUM)
                ]
            )
        ;

        return $this;
    }
    /**
     * 年月日記入欄
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $textSeparate
     * @return $this
     */
    public function addDate(
        float $x,
        float $y,
        float $width = 48.2,
        float $textSeparate = 14
    ): self
    {
        $this->pdf
            ->drawLine(
                $x,
                $y + $this->offsetY + 4.6,
                $width
            )
            ;
        $this
            ->drawDateText("日", $x + $width - 6.5, $y)
            ->drawDateText("月", $x + $width - 6.5 - $textSeparate, $y)
            ->drawDateText("年", $x + $width - 6.5 - ($textSeparate*2), $y)
            ;
        return $this;
    }
    private function drawDateText(string $text, $x, $y): self
    {
        $this->pdf->addText(
            $text,
            $x,
            $y + $this->offsetY,
            "C",
            5.5,
            0,
            [
                "font" => new FontSet(9.7)
            ]
        );
        return $this;
    }

    /**
     * 会社ロゴと担当営業所印字
     * @param string $logoFile
     * @param float $x
     * @param float $y
     * @param float $logoWidth
     * @param float $logoHeight
     * @param string|null $officeName
     * @param float $officeMargin
     * @return $this
     */
    public function addCompanySign(
        string $logoFile,
        float $x,
        float $y,
        float $logoWidth,
        float $logoHeight,
        ?string $officeName = null,
        float $officeMargin = 2.2
    ): self
    {
        $this->pdf->Image(
            $logoFile,
            $x,
            $y + $this->offsetY,
            $logoWidth,
            $logoHeight
        );
        if($officeName) {
            $this->pdf->addText(
                $officeName,
                $x,
                $y + $this->offsetY + $logoHeight + $officeMargin,
                "C",
                $logoWidth,
                0,
                [
                    "font" => new FontSet(14, FontSet::FONT_MEDIUM)
                ]
            );
        }
        return $this;
    }

    /**
     * 担当者表記
     * @param float $x
     * @param float $y
     * @param float $width
     * @param string $chargerNumber
     * @param string $chargerName
     * @return $this
     */
    public function addCharger(
        float $x,
        float $y,
        float $width,
        string $chargerNumber,
        string $chargerName
    ): self
    {
        $this->pdf
            ->drawLine(
                $x,
                $y + $this->offsetY + 6.1,
                $width
            )
            ->addText(
                "担当者",
                $x,
                $y + $this->offsetY,
                "L",
                14.3,
                0,
                [
                    "font" => new FontSet(11.2)
                ]
            )
            ->addText(
                $chargerNumber,
                $x + 14.5,
                $y + $this->offsetY,
                "L",
                15,
                0,
                [
                    "font" => new FontSet(10.4)
                ]
            )
            ->addText(
                $chargerName,
                $x + 29.5,
                $y + $this->offsetY,
                "L",
                $width - 29.5,
                0,
                [
                    "font" => new FontSet(9.7)
                ]
            )
        ;
        return $this;
    }

    /**
     * 決算月印字
     * @param float $x
     * @param float $y
     * @param string $month
     * @return $this
     */
    public function addResolutionMonth(float $x, float $y, string $month): self
    {
        $this->pdf->addText(
            "決算".$month."月",
            $x,
            $y + $this->offsetY,
            "L",
            17.3,
            0,
            [
                "font" => new FontSet(10.4)
            ]
        );
        return $this;
    }
}