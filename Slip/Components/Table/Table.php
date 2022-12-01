<?php

namespace App\Slip\Components\Table;

use App\Slip\Components\ComponentsAbstract;

class Table extends ComponentsAbstract
{
    private array $rows = [];
    private int $colCount = 0;
    private array $colWidths = [];
    private ?array $stripBgFillColor = null;

    public function createRow(
        ?float $minHeight = null,
        ?bool $drawBottomLine = true,
        float $negativeMarginTop = 0
    ): Row
    {
        $row = (new Row($this->pdf, $this->offsetY))
            ->setTable($this)
        ;
        if($minHeight) {
            $row->setMinHeight($minHeight);
        }
        $row
            ->setDrawBottomLine($drawBottomLine)
            ->setNegativeMarginTop($negativeMarginTop)
        ;


        $this->rows[] = $row;
        return $row;
    }

    public function addColCount(int $count): void
    {
        if($this->colCount < $count) {
            $this->colCount = $count;
        }
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function setColWidths(array $colWidth): self
    {
        foreach($colWidth as $k => $v) {
            $colWidth[$k] = floatval($v);
        }
        $this->colWidths = $colWidth;
        return $this;
    }
    public function getColWidth(int $colCount): ?float
    {
        return $this->colWidths[$colCount] ?? null;
    }
    public function getWidth(): float
    {
        return array_sum($this->colWidths);
    }
    public function getHeight(): float
    {
        $height = 0;
        foreach ($this->rows as $row) {
            $height += $row->getHeight();
        }
        return $height;
    }

    public function drawTable(float $x, float $y, float $outlineWidth = 0.6, array $outlineOption = array()): self
    {
        $w = $this->getWidth();
        $h = $this->getHeight();
        $baseY = $y += $this->offsetY;

        foreach ($this->rows as $key => $row) {
            $y += $row->drawRow(
                $x,
                $y,
                (count($this->rows) -1 === $key ),
                ($this->stripBgFillColor && $key %2 == 0)? $this->stripBgFillColor : null
            );
        }
        $this->pdf->drawLine($x, $baseY, $w, 0, $outlineWidth, $outlineOption);
        $this->pdf->drawLine($x, $baseY + $h, $w, 0, $outlineWidth, $outlineOption);
        $this->pdf->drawLine($x, $baseY, 0, $h, $outlineWidth, $outlineOption);
        $this->pdf->drawLine($x+ $w, $baseY, 0, $h, $outlineWidth, $outlineOption);

        return $this;
    }
}
