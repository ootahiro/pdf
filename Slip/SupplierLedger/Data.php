<?php

namespace App\Slip\SupplierLedger;

class Data
{
    public array $tableData = [];
    private int $iterator = 0;
    public function __construct(
        // Logo File
        public string $logoFile,
        // 仕入れ先元帳番号
        public string $number,
        // 郵便番号
        public string $postal,
        // 所在地
        public string $address,
        // TEL
        public string $tel,
        // FAX
        public string $fax,

        // 仕入先コード
        public string $supplierCode,
        // 仕入先名
        public string $supplierName,

        // 前月買掛残高
        public string $pmAccountsPayable,
        // 支払額
        public string $paid,
        // 差引繰越残高
        public string $carriedForward,
        // 当月仕入額
        public string $stockingAmount,
        // 当月消費税
        public string $taxAmount,
        // 買掛残高
        public string $accountsPayable,
        // 締切日
        public string $closeDate,
        // 伝票枚数
        public string $numberOfSlip,
        // PAGE
        public string $page,
        // 行数
        public int $rowCount = 50
    ) {
        $this->tableData = array_fill(0, $this->rowCount, null);
    }
    public function addTableData(TableData $data): self
    {
        $this->tableData[$this->iterator] = $data;
        $this->iterator++;
        return $this;
    }
}