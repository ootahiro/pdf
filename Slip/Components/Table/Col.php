<?php

namespace App\Slip\Components\Table;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Pdf\FontSet;

class Col extends ComponentsAbstract
{
    private ?Row $row = null;
    private float $width = 0;
    private float $height = 0;
    private string $text = "";
    private ?FontSet $fontSet = null;
    private float $rightLineWidth = 0.2;
    private array $rightLineOption = [];
    private array $textOption = [
        "align" => "L"
    ];
    private ?array $fillColor = null;

    public function setRow(Row $row): self
    {
        $this->row = $row;
        return $this;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function setText(string $text, array $textOption = []): self
    {
        if(isset($textOption['font']) && is_a($textOption['font'], FontSet::class )) {
            $this->fontSet = $textOption['font'];
            $this->pdf->useFontSet($this->fontSet);
        }
        $this->text = $text;
        $this->textOption = array_merge($this->textOption, $textOption);
        $height = $this->pdf->getStringHeight($this->width, $text);
        $this->height = $this->row->setHeight($height);

        if($this->fontSet) {
            $this->pdf->useFontSet($this->pdf->getDefaultFont());
        }

        return $this;
    }
    public function setFillColor(array $fillColor): self
    {
        $this->fillColor = $fillColor;
        return $this;
    }
    public function setRightLineOption(array $lineOption): self
    {
        $this->rightLineOption = $lineOption;
        return $this;
    }

    public function getWidth(): float
    {
        return $this->width;
    }
    public function getHeight(): float
    {
        return $this->height;
    }

    public function drawColText(float $x, float $y, bool $isLastCol = false): float
    {
        if($this->fillColor) {
            $this->pdf->fillRect(
                $x, $y, $this->width, $this->height, $this->fillColor
            );
        }

        if(!$isLastCol) {
            $this->pdf->drawLine(
                $x + $this->width,
                $y+$this->row->getNegativeMarginTop(),
                0,
                $this->row->getHeight(),
                $this->rightLineWidth,
                $this->rightLineOption
            );
        }
        $this->pdf->addText(
            $this->text,
            $x,
            $y,
            $this->textOption['align'],
            $this->width,
            $this->height,
            $this->textOption
        );
        return $this->width;
    }
}