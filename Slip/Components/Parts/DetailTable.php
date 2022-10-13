<?php

namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Components\Table\Table;
use App\Slip\Pdf\FontSet;
use App\Slip\Pdf\SlipPdf;

class DetailTable extends ComponentsAbstract
{
    public array $thFont;
    public array $tdFont;
    public array $colWidths = [23.6, 34.8];
    public float $colMinHeight = 4.6;
    public Table $table;

    public function __construct(SlipPdf $pdf, float $offsetY)
    {
        parent::__construct($pdf, $offsetY);
        $this->thFont = [
            "align" => "C",
            "valign" => "M",
            "font" => new FontSet(8.9)
        ];
        $this->tdFont = [
            "align" => "R",
            "valign" => "M",
            "font" => new FontSet(10.4),
            "paddingRight" => 6.1,
        ];
        $this->table = (new Table($pdf, $offsetY))
            ->setColWidths($this->colWidths);
    }

    public function addRow(
        string $label,
        string $value,
        array $textOption = [],
        ?float $underLineWidth = null
    ): self
    {
        $row = $this->table->createRow($this->colMinHeight)
            ->createCol($label, $this->thFont)
            ->createCol($value, array_merge($this->tdFont, $textOption));
        if($underLineWidth) {
            $row->setUnderLineWidth($underLineWidth);
        }
        return $this;
    }
}