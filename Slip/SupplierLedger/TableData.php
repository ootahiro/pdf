<?php

namespace App\Slip\SupplierLedger;

class TableData
{
    public function __construct(
        public string $month = "",
        public string $day = "",
        public string $slipNumber = "",
        public string $slipDivision = "",
        public string $name = "",
        public string $size = "",
        public string $quantity = "",
        public string $unit = "",
        public string $unitPrice = "",
        public string $price = "",
        public string $tax = "",
        public string $paymentDivision = "",
        public string $total = "",
        public string $balance = "",
        public string $abstract = "",
        public string $orderNumber = "",
        public string $taxType = ""
    )
    {
    }
}