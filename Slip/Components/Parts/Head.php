<?php
/**
 * 書類のヘッダーにあるパーツ
 */
namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class Head extends ComponentsAbstract
{
    static public string $headQuarterAddress = "本社
〒454-0056
名古屋市中川区十一番町2丁目6番地";
    static public string $headQuarterPhone = "052-661-6161";
    static public string $headQuarterFax = "052-661-6567";
    static public string $headQuarterBank = "三菱UFJ銀行 浄心支店 当座 0152138
名義:カ)オオタヒロ 銀行コード:0005-400";
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

    /**
     * 会社所在地を印字
     * @param float $x
     * @param float $y
     * @param FontSet $font
     * @param string|null $address
     * @return $this
     */
    public function drawHeadQuarterAddress(float $x, float $y, FontSet $font, ?string $address = null): self
    {
        $address = $address ? $address : self::$headQuarterAddress;
        $this->pdf->addText(
            $address, $x, $y, options:["font" => $font]
        );
        return $this;
    }

    /**
     * 会社電話番号を印字
     * @param float $x
     * @param float $y
     * @param FontSet $font
     * @param string|null $phone
     * @param string $preFix
     * @return $this
     */
    public function drawHeadQuarterPhone(float $x, float $y, FontSet $font, ?string $phone = null, string $preFix = "TEL　"): self
    {
        $phone = $phone ? $phone : self::$headQuarterPhone;
        $this->pdf->addText(
            $preFix. $phone, $x, $y, options: ["font" => $font]
        );
        return $this;
    }
}