<?php
require_once (__DIR__."/Slip/autoload.php");

use App\Slip\Components;
use App\Slip\SupplierLedger;

$pdf = SupplierLedger\Writer::createPdf();
$pdf->setGlobalTextDebug(false);

// モック
$data = new SupplierLedger\Data(
    logoFile: Components\Logo::getLogoFilePath(),
    number: "99220900001-0",
    postal: "〒452-0962",
    address: "愛知県清須市春日立作６２番地",
    tel: "052-409-4859",
    fax: "052-409-4883",
    supplierCode: "00204",
    supplierName: "株式会社　ワールドケミカル技研",
//    pmAccountsPayable: "41121988",
    pmAccountsPayable: "00000000",
    paid: "41121988",
    carriedForward: "00000000",
    stockingAmount: "31976554",
    taxAmount: "00000000",
    accountsPayable: "31976554",
    closeDate: "R04.09.30",
    numberOfSlip: "1",
    page: "1"
);
$data
    ->addTableData(new SupplierLedger\TableData(
        "08",
        "25",
        "支払",
        "0",
        "  102808",
        "　手形 R04.12.20",
        price: "41121988",
        paymentDivision: "4",
        total: "-41121988",
        balance: "0"
    ))
    ->addTableData(new SupplierLedger\TableData(
        "08",
        "31",
        "520021",
        "1",
        "樹脂製品",
        "",
        "1",
        "",
        "18038915",
        "18538915",
        "0",
        orderNumber: "100000",
        taxType: "D"
    ))
    ->addTableData(new SupplierLedger\TableData(
        "",
        "",
        "",
        "1",
        "樹脂製品",
        "",
        "1000",
        "",
        "951060",
        "951060",
        "8000",
        total: "31976554",
        balance: "31976554",
        orderNumber: "950000",
        taxType: "D"
    ))
    ;
for($i = 1; $i <= 47; $i++) {
    $data->addTableData(new SupplierLedger\TableData(
        "",
        "",
        "",
        "1",
        "樹脂製品".$i,
        "",
        "1000",
        "",
        "951060",
        "951060",
        "8000",
        total: "31976554",
        balance: "31976554",
        orderNumber: "950000",
        taxType: "D"
    ));
}
$writer = (new SupplierLedger\Writer($pdf))
    ->printing($data)
    ;
// 変数デバッグ
// $pdf->dump($var);
// $pdf->dumpPage();
$pdf->Output("supplier_ledger.pdf", "I");