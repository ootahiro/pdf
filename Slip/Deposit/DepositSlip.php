<?php
/**
 * 入金伝票１ページを印刷する
 */
namespace App\Slip\Deposit;

use App\Slip\Components\Block\Header;
use App\Slip\Components\Parts\AmountMoney;
use App\Slip\Components\Parts\DetailTable;
use App\Slip\Components\Parts\Head;
use App\Slip\Components\Parts\ItemsTable;
use App\Slip\Components\Parts\SignTable;
use App\Slip\Pdf\FontSet;
use App\Slip\Pdf\Pdf;
use App\Slip\Pdf\TwoPageTrait;

class DepositSlip
{
    use TwoPageTrait;

    public function __construct(
        private Pdf $pdf,
        private bool $isSecond = false
    ) {}

    public function setSecondPage(bool $isSecond): self
    {
        $this->isSecond = $isSecond;
        return $this;
    }

    public function printing(DepositSlipValue $value): self
    {
        // 伝票ヘッダー
        (new Header($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addTitle("入　金　伝　票")
            ->addCustomerCode($value->customerCode)
            ->addSlipNumber($value->slipNumber)
            ;
        (new Head($this->pdf, $this->getA4PY(0, $this->isSecond)))
            // 宛名
            ->addName(
                21.1,
                28.2,
                113.5,
                $value->name,
                $value->nameLabel
            )
            // 日付欄
            ->addDate(
                149.9,
                23.2
            )
            // 太田廣ロゴと営業所
            ->addCompanySign(
                $value->logoFile,
                152,
                31,
                44.5,
                6.9,
                $value->officeName
            )
            // 担当者
            ->addCharger(
                134.9,
                47.2,
                64,
                $value->chargerNumber,
                $value->chargerName
            )
            // 決算月
            ->addResolutionMonth(
                134.6,
                34.5,
                $value->resolutionMonth
            )
        ;
        // メイン金額欄
        (new AmountMoney($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addTotalAmountBlock()
            ->addTaxDescription()
            ->addTaxAmountBlock()
            ->addLastNote()
            ;

        // 左詳細テーブル
        $this->pdf->addText(
            "納品書/請求書：",
            144.3,
            55.6 + $this->getA4PY(0, $this->isSecond),
            "L",
            25, 0,
            [
                "font" => new FontSet(8.2)
            ]
        );

        $detailTable = (new DetailTable($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addRow("集　 金 　日", $value->payday, ['paddingRight' => 8 ])
            ->addRow("締　 切 　日", $value->closeDay, ["align" => "C", "paddingRight" => 0])
            ->addRow("前月請求額", $value->previousMonthBill)
            ->addRow("御入　金額", $value->deposits)
            ->addRow("相　 殺　 額", $value->offsetAmount)
            ->addRow("繰越　金額", $value->balanceCarriedForward)
            ->addRow("当月御買上額", $value->purchaseAmount)
            ->addRow("売上消費税額", $value->consumptionTax)
            ->addRow("当月御請求額", $value->amountBilled, [], 0.4)
            ->addRow("伝 票 枚 数", $value->numberOfSlips, ["align" => "C"], 0.4)
            ->addRow("最終入金日", $value->lastPaymentDate)
            ->addRow("請求後入金額", $value->depositAfterBilling)
            ->addRow("集　 金 　額", $value->amountCollected)
        ;
        $detailTable->table->drawTable(141.2, 61.5);

        // 内訳テーブル
        $this->pdf->addText(
            "内訳",
            28.7,
            76.1 + $this->getA4PY(0, $this->isSecond),
            "L",
            9,
            0,
            [
                "font" => new FontSet(9.7)
            ]
        );

        (new ItemsTable($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addHeader([
                "摘　　　　　　　　要",
                "日　 付",
                "金　　　　　額"
            ])
            ->addRow("１現金　　２小切手　 ３振込")
            ->addRow("４手形")
            ->addRow("５買掛金")
            ->addRow("６伝票代")
            ->addRow("７振込手数料")
            ->addRow("８切手　　　 ９送料　　  １０その他", false)
            ->addTotalRow()
            ->drawTable(24.3, 80.4)
        ;

        // 印
        $signTable = (new SignTable($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->drawTable(148.6, 124.6)
        ;

        return $this;
    }
}