<?php
/**
 * 入金伝票１ページを印刷する
 */
namespace App\Slip\Deposit;

use App\Slip\Components\Block\Header;
use App\Slip\Components\Parts\AmountMoney;
use App\Slip\Components\Parts\Head;
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
        (new Header($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addTitle("入　金　伝　票")
            ->addCustomerCode($value->customerCode)
            ->addSlipNumber($value->slipNumber)
            ;
        (new Head($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addName(
                21.1,
                28.2,
                113.5,
                $value->name,
                $value->nameLabel
            )
            ->addDate(
                149.9,
                23.2
            )
            ->addCompanySign(
                $value->logoFile,
                152,
                31,
                44.5,
                6.9,
                $value->officeName
            )
            ->addCharger(
                134.9,
                47.2,
                64,
                $value->chargerNumber,
                $value->chargerName
            )
            ->addResolutionMonth(
                134.6,
                34.5,
                $value->resolutionMonth
            )
        ;
        (new AmountMoney($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addTotalAmountBlock()
            ->addTaxDescription()
            ->addTaxAmountBlock()
            ->addLastNote()
            ;
        return $this;
    }
}