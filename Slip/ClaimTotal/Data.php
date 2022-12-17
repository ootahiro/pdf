<?php

namespace App\Slip\ClaimTotal;

class Data
{
    public array $detailTableValue = [];
    public function __construct(
        // LogoFile
        public string $logoFile,
        // 請求書番号
        public string $slipNumber,
        // 発行日
        public string|\DateTime $issueDate,

        // 郵便番号
        public string $postal,
        // 所在地
        public string $address,
        // 宛名
        public string $name,

        // 〆日
        public string|\DateTime $closeDate,

        // 請求テーブル
        public HeadTableData $headTableData
    )
    {
    }
    public function addDetailTableValue(DetailTableData $data): self
    {
        $this->detailTableValue[] = $data;
        return $this;
    }
}