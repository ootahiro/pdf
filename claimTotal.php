<?php
require_once (__DIR__."/Slip/autoload.php");

use App\Slip\ClaimTotal;
use App\Slip\Components;

$pdf = ClaimTotal\Writer::createPdf();
$pdf->setGlobalTextDebug(false);

// Mock
$data = new ClaimTotal\Data(
    logoFile: Components\Logo::getLogoFilePath(),
    slipNumber: "10221000703",
    issueDate: new \DateTime("2022-11-21"),
    postal: "455-0001",
    address: "愛知県名古屋市港区七番町3-22",
    name: "株式会社 高津製作所",
    closeDate: new \DateTime("2022-10-31"),
    headTableData: new ClaimTotal\HeadTableData(
        327426,
        327426,
        0,
        0,
        1730902,
        173090,
        1903992,
        1903992
    )
);
$data
    ->addDetailTableValue(new ClaimTotal\DetailTableData(
        "04657 高津製作所",
        269346,
        269346,
        0,
        0,
        1730902,
        173090,
        1903992,
        1903992
    ))
    ->addDetailTableValue(new ClaimTotal\DetailTableData(
        "05434 高津製作所 豊田工場",
        58080,
        58080,
        0,
        0,
        0,
        0,
        0,
        0
    ))
    ;

$writer = (new ClaimTotal\Writer($pdf))
    ->printing($data);

// 変数デバッグ
// $pdf->dump($var);
//$pdf->dumpPage();
$pdf->Output("claim_total.pdf", "I");