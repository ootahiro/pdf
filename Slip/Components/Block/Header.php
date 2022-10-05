<?php
/**
 * 伝票の見出しパターン
 */
namespace App\Slip\Components\Block;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class Header extends ComponentsAbstract
{
    /**
     * 書類タイトル印字
     * @param string $title
     * @return $this
     */
    public function addTitle(
        string $title
    ): self {
        $this->pdf->drawLine(
            82,
            17.5 + $this->offsetY,
            55.6
        );
        $this->pdf->addText(
            $title,
            82,
            9.5 + $this->offsetY,
            "C",
            55.6,
            8.2,
            [
                "font" => new FontSet(18.9, FontSet::FONT_MEDIUM)
            ],
        );
        return $this;
    }

    /**
     * 取引先コードを印字
     * @param string $code 取引先コード
     * @param string $label
     * @return $this
     */
    public function addCustomerCode(
        string $code,
        string $label = "得意先コード"
    ): self
    {
        $this->pdf->addText(
            $label,
            22.9,
            13.1 + $this->offsetY,
            "C",
            19.1,
            4,
            [
                "font" => new FontSet(8.9)
            ]
        );
        $this->pdf->drawLine(
            42.6,
            17.5 + $this->offsetY,
            14.4
        );
        $this->pdf->addText(
            $code,
            42.6,
            12.6 + $this->offsetY,
            "C",
            14.4,
            4.7,
            [
                "font" => new FontSet(11, FontSet::FONT_MEDIUM)
            ]
        );

        return $this;
    }

    public function addSlipNumber(
        string $number
    ): self
    {
        $this->pdf->drawLine(
            166.4,
            17.6 + $this->offsetY,
            31.9
        );
        $this->pdf->addText(
            "No.",
            166.4,
            12.3 + $this->offsetY,
            "R",
            8.1,
            5.2,
            [
                "font" => new FontSet(8.9)
            ]
        );
        $this->pdf->addText(
            $number,
            174.1,
            11.9 + $this->offsetY,
            "C",
            24.2,
            4.2,
            [
                "font" => new FontSet(9.9, FontSet::FONT_MEDIUM)
            ]
        );

        return $this;
    }
}