<?php
/**
 * 外部流入の値を受け渡す
 */
namespace App\Slip\Deposit;

class DepositSlipValue
{
    public function __construct(
        // 取引先コード
        public string $customerCode,
        // 伝票No
        public string $slipNumber,
        // 宛名
        public string $name,




        // 宛名敬称
        public string $nameLabel = "様"
    ) {}
}