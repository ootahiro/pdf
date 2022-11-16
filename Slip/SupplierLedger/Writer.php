<?php
/**
 * 仕入先元帳1ページを作成
 */
namespace App\Slip\SupplierLedger;

use App\Slip\Components\Parts;
use App\Slip\Components\Table\Table;
use App\Slip\Pdf\FontSet;
use App\Slip\Pdf\SlipPdf;

class Writer
{
    public const LEFT = 10.7;
    static public array $fillColor = [169,169,169];
    static public array $stripeColor = [211,211,211];
    private FontSet $tableFont;

    public function __construct(
        private readonly SlipPdf $pdf
    ) {
        $this->tableFont = new FontSet(8);
    }

    public function printing(Data $data): self
    {
        $this->pdf->AddPage();
        // ヘッダー
        (new Parts\CenteringTitle($this->pdf, 0))
            ->addTitle(
                "仕　入　先　元　帳",
                new FontSet(14.5, FontSet::FONT_MEDIUM, "B"),
                8.2
            )
            ;
        // ロゴ
        (new Parts\Head($this->pdf, 0))
            ->addCompanySign(
                $data->logoFile,
                253.5,
                13.8,
                45.4,
                7
            );
        // 所在地
        $addressOption = [
            "font" => new FontSet(10.4 )
        ];
        $labelFont = [
            "font" => new FontSet(8.2)
        ];
        $valueFont = [
            "font" => new FontSet(9.7)
        ];
        $this->pdf
            ->addText(
                $data->postal,
                self::LEFT,
                8.9,

                options: $addressOption
            )->addText(
                $data->address,
                self::LEFT,
                13,
                options:$addressOption
            )

            // 仕入れ先元帳番号
            ->addText(
                "仕入先元帳番号",
                298.7,
                6.1,
                options: $labelFont
            )->addText(
                $data->number,
                323,
                6.1,
                options: $valueFont
            )
            // TEL
            ->addText(
                "TEL",
                29.4,
                24.6,
                options: $labelFont
            )->addText(
                $data->tel,
                37.7,
                24.1,
                options: $valueFont
            )
            // FAX
            ->addText(
                "FAX",
                77.5,
                24.6,

                options: $labelFont
            )->addText(
                $data->fax,
                85.4,
                24.1,
                options: $valueFont
            )
            ;
        // -------- 仕入先テーブル --------
        $supplerTable = (new Table($this->pdf, 0))
            ->setColWidths([19.7, 104])
            ;
        $headFont = [
            "font" => new FontSet(8),
            "align" => "C",
            "valign" => "M"
        ];
        $supplerTable->createRow(4.4)
            ->createCol("仕入先コード", $headFont)
            ->createCol("仕　入　先　名", $headFont)
            ;
        $supplerTable->createRow(8)
            ->createCol($data->supplierCode, [
                "font" => new FontSet(11.2),
                "align" => "C",
                "valign" => "M"
            ])
            ->createCol($data->supplierName, [
                "font" => new FontSet(10.4),
                "valign" => "M",
                "paddingLeft" => 1.2
            ]);
        $supplerTable->drawTable(11, 29.3);
        $this->pdf->addText(
            "様",
            128.1,
            36.4,
            options: [
                "font" => new FontSet(9)
            ]
        );

        // --------- 右上側テーブル ---------
        $headTable = (new Table($this->pdf, 0))
            ->setColWidths([
                23.7, 25, 25, 25, 25, 25,20,13.8,12.7
            ]);
        $headFont = [
            "font" => new FontSet(8.1, color: [255,255,255]),
            "align" => "C",
            "valign" => "M"
        ];
        $lineOption = [
            "color" => [255,255,255]
        ];
        $bodyFont = [
            "font" => new FontSet(11.2),
            "align" => "R",
            "valign" => "B"
        ];
        $headTableY = 29.3;
        $headRow = $headTable->createRow(4.4, false)
            ->createCol("前月買掛残高", $headFont,  rightLineOption: $lineOption)
            ->createCol("支　払　額", $headFont,  rightLineOption: $lineOption)
            ->createCol("差引繰越残高", $headFont,  rightLineOption: $lineOption)
            ->createCol("当月仕入額", $headFont,  rightLineOption: $lineOption)
            ->createCol("当月消費税", $headFont,  rightLineOption: $lineOption)
            ->createCol("買掛残高", $headFont,  rightLineOption: $lineOption)
            ->createCol("締　切　日", $headFont,  rightLineOption: $lineOption)
            ->createCol("伝票枚数", $headFont,  rightLineOption: $lineOption)
            ->createCol("PAGE", $headFont,  rightLineOption: $lineOption)
        ;
        $headRow->setFillColor(self::$fillColor);
        $headY = $headTable->getHeight();
        $headTable->createRow(4, false, 1)
            ->createCol("")
            ->createCol("")
            ->createCol("")
            ->createCol("")
            ->createCol("内部処理", [
                "font" => new FontSet(10.4),
                "valign" => "M"
            ])
            ->createCol("")
            ->createCol("")
            ->createCol("")
            ->createCol("")
        ;

        $headTable->createRow(4.2,false,1.2)
            ->createCol($data->pmAccountsPayable, $bodyFont)
            ->createCol($data->paid, $bodyFont)
            ->createCol($data->carriedForward, $bodyFont)
            ->createCol($data->stockingAmount, $bodyFont)
            ->createCol($data->taxAmount, $bodyFont)
            ->createCol($data->accountsPayable, $bodyFont)
            ->createCol($data->closeDate, $bodyFont)
            ->createCol($data->numberOfSlip, array_merge($bodyFont, ["align"=>"C"]))
            ->createCol($data->page, array_merge($bodyFont, ["align"=>"C"]))
            ;
        $headToY = $headTable->getHeight()-$headY;
        $headY += $headTableY;

        // Dash線を先に引く
        $diff = 6.4;
        $dashX = 159.6;
        $spaceDiff = 25;
        for($i = 1; $i <= 6; $i++) {
            $this->pdf->drawLine($dashX, $headY, 0, $headToY, 0.1, ["dash" => true]);
            $this->pdf->drawLine($dashX+$diff, $headY, 0, $headToY, 0.1, ["dash" => true]);
            $dashX += $spaceDiff;
        }
        // テーブル描画
        $headTable->drawTable(
            $this->pdf->getPageWidth() - $headTable->getWidth() - 7.8,
            29.3);

        // 税区分
        $this->pdf->addText(
            "C:8% D:10%↓",
            322.8,43,
            options: [
                "font" => new FontSet(9)
            ]
        );

        // -------- 本体テーブル --------
        $mainTable = (new Table($this->pdf, 0))
            ->setColWidths([
                5.5, 5.5, 14, 4,
                //品名・サイズ・数量
                60, 72.5, 10.2,5.5,
                //単位,単価,金額,消費税
                10.8, 15.8, 5.5, 17.7, 17.7,
                // 支区, 伝票計, 残高
                4, 17.7, 17.7,
                //摘要, 注番, 税
                31.7, 13.7, 5
            ]);
        $tableY = 47.7;
        $smallFont = [
            "font" => new FontSet(5.1, color: [255,255,255]),
            "align" => "C",
            "valign" => "M"
        ];

        $headRow = $mainTable->createRow(4.2, false)
            ->createCol("月", $headFont,  rightLineOption: $lineOption)
            ->createCol("日", $headFont,  rightLineOption: $lineOption)
            ->createCol("伝票No", $headFont,  rightLineOption: $lineOption)
            ->createCol("伝区", $smallFont,  rightLineOption: $lineOption)
            ->createCol("品　　　　　　名", $headFont,  rightLineOption: $lineOption)
            ->createCol("サ　　イ　　ズ", $headFont,  rightLineOption: $lineOption)
            ->createCol("数量", $headFont,2,  rightLineOption: $lineOption)
            ->createCol("単位", $headFont,  rightLineOption: $lineOption)
            ->createCol("単　価", $headFont, 2,  rightLineOption: $lineOption)
            ->createCol("金　額", $headFont,  rightLineOption: $lineOption)
            ->createCol("消費税", $headFont,  rightLineOption: $lineOption)
            ->createCol("支区", $smallFont,  rightLineOption: $lineOption)
            ->createCol("伝　票　計", $headFont,  rightLineOption: $lineOption)
            ->createCol("残　高", $headFont,  rightLineOption: $lineOption)
            ->createCol("摘　　要", $headFont,  rightLineOption: $lineOption)
            ->createCol("注番", $headFont,  rightLineOption: $lineOption)
            ->createCol("税", $headFont,  rightLineOption: $lineOption)
        ;
        $headRow->setFillColor(self::$fillColor);

        foreach($data->tableData as $k => $tableData) {
            $this->addMainTable($mainTable, $tableData, ($k % 2 !== 0));
        }

        $mainTable->drawTable(
            10.7,
            $tableY
        );
        // ダッシュ線
        $dashY1 = $tableY + 4.4;
        $dashY2 =  $mainTable->getHeight() - 4.4;
        // 数量
        $this->pdf->drawLine(175.7, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        // 単価
        $this->pdf->drawLine(203.2, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        $this->pdf->drawLine(207.9, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        // 金額
        $this->pdf->drawLine(226.4, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        $this->pdf->drawLine(231, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        // 消費税
        $this->pdf->drawLine(248.7, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        // 伝票計
        $this->pdf->drawLine(265.8, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        $this->pdf->drawLine(270.5, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        // 残高
        $this->pdf->drawLine(283.5, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);
        $this->pdf->drawLine(288.15, $dashY1, 0, $dashY2, 0.1, ["dash" => true]);

        // フッター注釈
        $font = new FontSet(8);
        $triangle = new FontSet(4);
        $this->pdf->addText("▲", 36, 231.3, w:10 ,options: ["font" => $triangle]);
        $this->pdf->addText(
            " 1.仕入 2返品 3.値引 11.仕入訂正 12.返品訂正 13.値引訂正",
            38.5,
            230.3,
            w: 80,
            options: [
                "font" => $font
            ]
        );
        $this->pdf->addText("▲", 255.7, 231.3, w:10 ,options: ["font" => $triangle]);
        $this->pdf->addText(
            "1.現金　　3.振込み　5.売掛金　　　7.支払割引
2.小切手　4.手形　　6.振込手数料　8.その他",
            258.7,
            230.3,
            w: 80,
            options: [
                "font" => $font
            ]
        );

        return $this;
    }
    private function addMainTable(Table $mainTable, ?TableData $data, bool $fill): void
    {
        $center = [
            "font" => $this->tableFont,
            "align" => "C",
            "valign" => "M"
        ];
        $left = [
            "font" => $this->tableFont,
            "align" => "L",
            "valign" => "M"
        ];
        $right = [
            "font" => $this->tableFont,
            "align" => "R",
            "valign" => "M"
        ];
        if($data) {
            $row = $mainTable
                ->createRow(3.5, false)
                ->createCol($data->month, $center, rightLineOption: ["dash" => true])
                ->createCol($data->day, $center)
                ->createCol($data->slipNumber, $center)
                ->createCol($data->slipDivision, $center)
                ->createCol($data->name, $left)
                ->createCol($data->size, $left)
                ->createCol($data->quantity, $right)
                ->createCol("", $right)
                ->createCol($data->unit, $center)
                ->createCol($data->unitPrice, $right)
                ->createCol("", $right)
                ->createCol($data->price, $right)
                ->createCol($data->tax, $right)
                ->createCol($data->paymentDivision, $center)
                ->createCol($data->total, $right)
                ->createCol($data->balance, $right)
                ->createCol($data->abstract, $left)
                ->createCol($data->orderNumber, $left)
                ->createCol($data->taxType, $center)
            ;
        } else {
            $row = $mainTable->createRow(3.5, false);
            $row->createCol("", $left, rightLineOption: ["dash" => true]);
            for($i = 1; $i <= 18; $i++) {
                $row->createCol("", $left);
            }
        }

        if($fill) {
            $row->setFillColor(self::$stripeColor);
        }
    }
}