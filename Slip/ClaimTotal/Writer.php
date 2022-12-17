<?php

namespace App\Slip\ClaimTotal;

use App\Slip\Components\Parts;
use App\Slip\Components\Table\Table;
use App\Slip\Pdf\FontSet;
use App\Slip\Pdf\SlipPdf;
use App\Slip\Utils\JpYearUtil;

class Writer
{
    private array $headFillColor = [245,245,245];

    private FontSet $font;

    public function __construct(
        private readonly SlipPdf $pdf
    )
    {
        $this->font = new FontSet(15, FontSet::FONT_MINCHO);
    }

    static public function createPdf(): SlipPdf
    {
        return new SlipPdf("P", "mm", "A4");
    }

    public function printing(Data $data): self
    {
        $this->pdf->AddPage();
        // ヘッダー
        (new Parts\CenteringTitle($this->pdf, 0))
            ->addTitle(
                "請　求　合　計　表",
                $this->font,
                9
            )
            ;
        // 請求書番号 / 発行日
        $this->pdf
            ->addText("請求書番号", 160.3, 9.1, options: [
                "font" => $this->font->setSize(9.7)
            ])
            ->addText($data->slipNumber, 179.2, 9.1, options:["font" => $this->font])
            ->addText("発行日", 160.3, 14, options:["font" => $this->font])
            ->addText(
                JpYearUtil::formatJpDate($data->issueDate),172.3, 14, options:["font" => $this->font]
            )
        ;

        // 宛先
        $this->pdf
            ->drawLineRect(6.4, 18.8, 100, 28.8, 0.2, ["dash" => true])
            ->addText("〒".$data->postal, 7.6, 21.3, options: ["font" => $this->font])
            ->addText($data->address, 7.6, 25.1, "L", 96.5, 12.9, ["font" => $this->font])
            ->addText($data->name, 7.6, 39.1, options: ["font" => $this->font])
            ->addText("御中", 95.8, 38.6, options: ["font" => $this->font->setSize(11.2)])
            ;
        // ロゴ / 会社情報
        (new Parts\Head($this->pdf, 0))
            ->addCompanySign(
                $data->logoFile,
                138.4, 20.7, 45.4, 7
            )
            ->drawHeadQuarterAddress(124, 29.2, $this->font->setSize(8.9))
            ->drawHeadQuarterPhone(123.9, 46.5, $this->font)
            ->drawHeadQuarterPhone(160.8, 46.5, $this->font, Parts\Head::$headQuarterFax, "FAX　")
        ;
        $this->pdf
            ->addText("[取引銀行]", 124.2, 51.8, options: ["font" => $this->font])
            ->addText(Parts\Head::$headQuarterBank, 126.5, 55.4, options: ["font" => $this->font])
            ;

        // 締日
        $this->pdf
            ->addText(
                JpYearUtil::formatJpDate($data->closeDate, "", "分"),
                5.3, 50.3, options: ["font" => $this->font->setSize(9.7)]
            )
            ;

        // 請求テーブル
        $this->pdf
            ->addText("下記の通りご請求申し上げます。", 5.3, 58.9, options: ["font" => $this->font->setSize(8.9)])
            ;
        $headTable = (new Table($this->pdf, 0))
            ->setColWidths([
                23.7, 23.7, 25, 23.7, 23.7, 23.7, 23.7, 24.4
            ]);
        $headFont = [
            "font" => $this->font->setSize(8.8),
            "align" => "C",
            "valign" => "M"
        ];

        $headTable->createRow(4.8)
            ->createCol("前月御請求高", $headFont)
            ->createCol("当月御入金額", $headFont)
            ->createCol("当月相殺額", $headFont)
            ->createCol("差引繰越残高", $headFont)
            ->createCol("当月御買上高", $headFont)
            ->createCol("当月消費税額", $headFont)
            ->createCol("当月合計額", $headFont)
            ->createCol("当月請求高", $headFont)
            ->setFillColor($this->headFillColor)
            ;

        $_bodyFont = clone $this->font;
        $bodyFont = [
            "font" => $_bodyFont->setSize(11.2),
            "align" => "R",
            "valign" => "B",
            "paddingRight" => 1.2
        ];
        $row = $headTable->createRow(9);
        foreach($data->headTableData->toArray() as $value) {
            $row->createCol(number_format($value), $bodyFont);
        }

        $headTable->drawTable(5.1,63, 0.2);

        // 詳細テーブル
        $detailTable = (new Table($this->pdf, 0))
            ->setColWidths([
                45.9, 19.1, 19.1, 19.1, 19.1, 19.1, 19.1, 19.1, 20.2
            ]);
        $headFont = [
            "font" => $this->font->setSize(7.4),
            "align" => "C",
            "valign" => "M"
        ];
        $detailTable->createRow(4.8)
            ->createCol("お得意先", $headFont)
            ->createCol("前月御請求高", $headFont)
            ->createCol("当月御入金額", $headFont)
            ->createCol("当月相殺額", $headFont)
            ->createCol("差引繰越残高", $headFont)
            ->createCol("当月御買上高", $headFont)
            ->createCol("当月消費税額", $headFont)
            ->createCol("当月合計額", $headFont)
            ->createCol("当月請求高", $headFont)
            ->setFillColor($this->headFillColor)
            ;
        $_bodyFont = clone $this->font;
        $bodyFont = [
            "font" => $_bodyFont->setSize(8.9),
            "align" => "R",
            "valign" => "M"
        ];
        foreach($data->detailTableValue as $value) {
            $row = $detailTable->createRow(4.8)
                ->createCol($value->name, array_merge($bodyFont, ["align" => "L"]))
                ;
            foreach($value->toArray() as $item) {
                $row->createCol(number_format($item), $bodyFont);
            }
        }
        $detailTable->drawTable(5.1, 80.3, 0.2);

        return $this;
    }
}