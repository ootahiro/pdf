<?php
require_once (__DIR__."/Slip/autoload.php");

use App\Slip\Deposit\DepositSlip;
use App\Slip\Deposit\DepositSlipValue;
use App\Slip\Pdf\Pdf;

// モックデータ
$value = new DepositSlipValue(
    "03286",
    "50220400099",
    "三工機器　株式会社",
    __DIR__. "/ootahiro_logo_w193x30.png",
    "名古屋西営業所",
    "505011",
    "伊藤 守",
    "12",

    "99　　  翌月",
    "R04.04.30",
    "3,212,311",
    "3,212,311",
    '0',
    '0',
    "1,144,296",
    "114,430",
    "1,258,726",
    "34",
    "",
    "0",
    "1,258,726"


);

$pdf = new Pdf("P", "mm", "A4");
$pdf->setGlobalTextDebug(false);

$pdf->AddPage();
$writer = (new DepositSlip($pdf))
    ->printing($value)
    ->setSecondPage(true)
    ->printing($value)
    ->setSecondPage(false)
    ;


$pdf->Output('deposit.pdf', "I");