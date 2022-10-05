<?php
require_once (__DIR__."/Slip/autoload.php");

use App\Slip\Deposit\DepositSlip;
use App\Slip\Deposit\DepositSlipValue;
use App\Slip\Pdf\Pdf;

// モックデータ
$value = new DepositSlipValue(
    "03286",
    "50220400099",
    "三工機器　株式会社"

);

$pdf = new Pdf("P", "mm", "A4");
$pdf->AddPage();
$writer = (new DepositSlip($pdf))
    ->printing($value)
    ;
// 2段目
$writer = (new DepositSlip($pdf, true))
    ->printing($value)
;

// 2ページ目
$pdf->AddPage();
$writer = (new DepositSlip($pdf))
    ->printing($value)
;
// 2段目
$writer = (new DepositSlip($pdf, true))
    ->printing($value)
;


$pdf->Output('deposit.pdf', "I");