<?php
/**
 * 内訳テーブル
 */
namespace App\Slip\Components\Parts;

use App\Slip\Components\ComponentsAbstract;
use App\Slip\Components\Table\Table;
use App\Slip\Pdf\FontSet;
use App\Slip\Pdf\SlipPdf;

class ItemsTable extends ComponentsAbstract
{
    public array $theadFont;
    public array $tbodyFont;
    public array $slashFont;
    public array $totalFont;
    public array $tableCols = [
        56.9, 14.1, 37.7
    ];
    private Table $table;
    private float $headerHeight;

    public function __construct(SlipPdf $pdf, float $offsetY)
    {
        parent::__construct($pdf, $offsetY);
        $this->theadFont = [
            "align" => "C",
            "valign" => "M",
            "font" => new FontSet(9.9)
        ];
        $this->tbodyFont = [
            "align" => "L",
            "valign" => "M",
            "paddingLeft" => 3,
            "font" => new FontSet(10.4)
        ];
        $this->slashFont = [
            "align" => "C",
            "valign" => "M",
            "font" => new FontSet(10.4)
        ];
        $this->totalFont = [
            "align" => "C",
            "valign" => "M",
            "font" => new FontSet(11.9)
        ];
        $this->table = (new Table($this->pdf, $this->offsetY))
            ->setColWidths($this->tableCols);
    }

    public function addHeader(array $titles, float $minHeight = 4.4): self
    {
        $row = $this->table->createRow($minHeight);
        foreach($titles as $title) {
            $row->createCol($title, $this->theadFont);
        }
        $this->headerHeight = $row->getHeight();

        return $this;
    }

    public function addRow(string $label, bool $addSlash = true, float $minHeight = 8): self
    {
        $row = $this->table->createRow($minHeight);
        if($addSlash) {
            $row
                ->createCol($label, $this->tbodyFont)
                ->createCol("/", $this->slashFont)
                ->createCol("");
        } else {
            $row
                ->createCol($label, $this->tbodyFont, 2)
                ->createCol("");
        }
        return $this;
    }

    public function addTotalRow(string $label = "合　　　　　　　　　計", float $minHeight = 8): self
    {
        $this->table->createRow($minHeight)
            ->createCol($label, $this->totalFont, 2)
            ->createCol("")
            ;
        return $this;
    }

    public function drawTable(float $x, float $y, float $outlineWidth = 0.6): self
    {
        (new AmountColumnLine($this->pdf, $this->offsetY))
            ->drawLine($x + 71.5, $y + $this->headerHeight + $this->offsetY, 4.7, $this->table->getHeight() - $this->headerHeight)
        ;
        $this->table->drawTable($x, $y, $outlineWidth);
        return $this;
    }
}