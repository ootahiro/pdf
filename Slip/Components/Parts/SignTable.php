<?php

namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Components\Table\Table;
use App\Slip\Pdf\FontSet;
use App\Slip\Pdf\Pdf;

class SignTable extends ComponentsAbstract
{
    private array $font;
    private Table $table;

    public float $colWidth = 12.8;
    public float $headHeight = 4.7;
    public float $signColHeight = 11;
    public array $heads = [
        "確認印",
        "記帳印",
        "経理印",
        "係　印"
    ];
    public float $outlineWidth = 0.2;

    public function __construct(Pdf $pdf, float $offsetY)
    {
        parent::__construct($pdf, $offsetY);
        $this->font = [
            "align" => "C",
            "valign" => "M",
            "font" => new FontSet(8.9)
        ];
        $this->table = (new Table($pdf, $offsetY));
        $this->table->setColWidths(
            array_fill(0, count($this->heads), $this->colWidth)
        );
    }

    public function drawTable(float $x, float $y): self
    {
        $row = $this->table->createRow($this->headHeight);
        foreach($this->heads as $head) {
            $row->createCol($head, $this->font);
        }
        $row = $this->table->createRow($this->signColHeight);
        foreach(range(1,count($this->heads)) as $value) {
            $row->createCol("");
        }
        $this->table->drawTable($x, $y, $this->outlineWidth);

        return $this;
    }
}