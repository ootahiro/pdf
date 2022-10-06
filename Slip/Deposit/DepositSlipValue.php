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




        // 宛名敬称
        public string $nameLabel = "様"
    ) {}
}