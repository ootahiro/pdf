<?php
/**
 * 入金伝票１ページを印刷する
 */
namespace App\Slip\Deposit;

use App\Slip\Components\Block\Header;
use App\Slip\Components\Parts\Name;
use App\Slip\Pdf\Pdf;
use App\Slip\Pdf\TwoPageTrait;

class DepositSlip
{
    use TwoPageTrait;

    public function __construct(
        private Pdf $pdf,
        private bool $isSecond = false
    ) {}

    public function printing(DepositSlipValue $value): self
    {
        $this->pdf->setGlobalTextDebug(false);
        $header = (new Header($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addTitle("入　金　伝　票")
            ->addCustomerCode($value->customerCode)
            ->addSlipNumber($value->slipNumber)
            ;
        $name = (new Name($this->pdf, $this->getA4PY(0, $this->isSecond)))
            ->addName(
                21.1,
                28.2,
                113.5,
                $value->name,
                $value->nameLabel
            );
        return $this;
    }
}