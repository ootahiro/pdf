<?php

namespace App\Slip\ClaimTotal;

class DetailTableData
{
    public function __construct(
        //お得意様
        public readonly string $name,
        //前月御請求高
        public readonly int $beforeMonthClaimAmount,
        //当月御入金額
        public readonly int $currentMonthPaymentAmount,
        //当月相殺額
        public readonly int $currentMonthOffset,
        //差引繰越残高
        public readonly int $forwardAmount,
        // 当月御買上高
        public readonly int $purchaseAmount,
        // 当月消費税額
        public readonly int $taxAmount,
        // 当月合計額
        public readonly int $totalAmount,
        // 当月請求高
        public readonly int $claimAmount
    )
    {
    }

    public function toArray(): array
    {
        return [
            $this->beforeMonthClaimAmount,
            $this->currentMonthPaymentAmount,
            $this->currentMonthOffset,
            $this->forwardAmount,
            $this->purchaseAmount,
            $this->taxAmount,
            $this->totalAmount,
            $this->claimAmount
        ];
    }
}