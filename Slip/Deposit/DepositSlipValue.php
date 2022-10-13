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
        // ロゴファイルのパス
        public string $logoFile,
        // 担当営業所名
        public string $officeName,
        // 担当者番号
        public string $chargerNumber,
        // 担当者名
        public string $chargerName,
        // 決算月
        public string $resolutionMonth,

        // 集金日
        public string $payday,
        // 締め切り日
        public string $closeDay,
        // 前月請求額
        public string $previousMonthBill,
        // 御入金額
        public string $deposits,
        // 相殺額
        public string $offsetAmount,
        // 繰越残高
        public string $balanceCarriedForward,
        // 当月御買上額
        public string $purchaseAmount,
        // 売上消費税額
        public string $consumptionTax,
        // 当月御請求額
        public string $amountBilled,

        // 伝票枚数
        public string $numberOfSlips,
        // 最終入金日
        public string $lastPaymentDate,
        // 請求後入金額
        public string $depositAfterBilling,
        // 集金額
        public string $amountCollected,




        // 宛名敬称
        public string $nameLabel = "様"
    ) {}
}