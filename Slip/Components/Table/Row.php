<?php

namespace App\Slip\Components\Table;

use App\Slip\Components\ComponentsAbstract;

class Row extends ComponentsAbstract
{
    private ?Table $table = null;
    private array $cols = [];
    private int $colCount = 0;
    private float $minHeight = 0;
    private float $height = 0;
    private float $underLineWidth = 0.2;

    public function setTable(Table $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function setMinHeight(float $height): self
    {
        $this->minHeight = $height;
        return $this;
    }

    public function setHeight(float $colHeight): float
    {
        if($colHeight < $this->minHeight) {
            $colHeight = $this->minHeight;
        }
        if($colHeight > $this->height) {
            $this->height = $colHeight;
        }
        return $this->height;
    }
    public function getHeight(): float
    {
        return ($this->height > $this->minHeight)? $this->height : $this->minHeight;
    }

    public function setUnderLineWidth(float $width): self
    {
        $this->underLineWidth = $width;
        return $this;
    }

    public function createCol(string $text, array $textOption = [], int $colspan = 1): self
    {
        if(!$this->table) {
            throw new \LogicException("Table not assign.");
        }
        $colWidth = 0;
        for($i = $this->colCount+1; $i <= $this->colCount+ $colspan; $i++) {
            $width = $this->table->getColWidth($i-1);
            if(!$width) {
                $i --;
                throw new \LogicException('Col width ['. $i. '] not set');
            }
            $colWidth += $width;
        }
        $col = (new Col($this->pdf, $this->offsetY))
            ->setRow($this)
            ->setWidth($colWidth)
            ->setText($text, $textOption)
        ;

        $this->cols[] = [
            "col" => $col,
            "colspan" => $colspan
        ];
        $this->colCount += $colspan;
        $this->table->addColCount($this->colCount);

        return $this;
    }

    public function getCols(): array
    {
        return $this->cols;
    }

    public function drawRow(float $x, float $y, bool $isLastRow = false): float
    {
        if(!$isLastRow) {
            $this->pdf->drawLine($x, $y+ $this->getHeight(), $this->table->getWidth(), 0, $this->underLineWidth);
        }
        foreach($this->cols as $key => $col) {
            $x += $col['col']->drawColText($x, $y, (count($this->cols) -1 === $key));
        }

        return $this->getHeight();
    }
}