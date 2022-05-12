<?php
use setasign\Fpdi\TcpdfFpdi;

require_once('tcpdf/tcpdf.php');
require_once('tcpdf/fpdi/autoload.php');

$f = array();
$f["g"] = "genshingothicpnormal";
$f["gb"] = "genshingothicpmedium";

$start_x = 7;
$start_y = 5;

$page = 1;
$pdf_y = $start_y;
$now_date = date("Y.m.d");

$pdf = new TcpdfFpdi("L", "mm", "A4");
$pdf->SetMargins(0, 0, 0, 0);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(true);

$pdf->AddPage();

$pdf->SetFont($f["gb"], '', 10);
$pdf->MultiCell(0, 0, "仕 入 日 記 帳", 0, 'C', 0, 0, 0, $pdf_y);
$pdf->SetFont($f["g"], '', 8);
$pdf->MultiCell(0, 0, "DATE－　" . $now_date, 0, 'L', 0, 0, 10, $pdf_y);
$pdf->MultiCell(0, 0, "{$page} ページ", 0, 'L', 0, 0, 271, $pdf_y);
$pdf_y += 5.5;
$pdf->SetLineStyle(array("width" => '0.5'));
$pdf->Line($start_x, $pdf_y, 291, $pdf_y);
$pdf->MultiCell(0, 0, "伝票番号", 0, 'L', 0, 0, $start_x + 1, $pdf_y);
$pdf->MultiCell(0, 0, "入荷日", 0, 'L', 0, 0, $start_x + 17, $pdf_y);
$pdf->MultiCell(0, 0, "伝票区分", 0, 'L', 0, 0, $start_x + 38, $pdf_y);
$pdf->MultiCell(0, 0, "仕入先", 0, 'L', 0, 0, $start_x + 58, $pdf_y);
$pdf->MultiCell(0, 0, "備考", 0, 'L', 0, 0, $start_x + 110, $pdf_y);
$pdf_y += 4;
$pdf->SetLineStyle(array("width" => '0.3'));
$pdf->Line($start_x + 2, $pdf_y, 291, $pdf_y);
$pdf->MultiCell(0, 0, "Seq", 0, 'L', 0, 0, $start_x + 6, $pdf_y);
$pdf->MultiCell(0, 0, "拠点", 0, 'L', 0, 0, $start_x + 15, $pdf_y);
$pdf->MultiCell(0, 0, "製品コード", 0, 'L', 0, 0, $start_x + 38, $pdf_y);
$pdf->MultiCell(0, 0, "製品名", 0, 'L', 0, 0, $start_x + 70, $pdf_y);
$pdf->MultiCell(0, 0, "サイズ名", 0, 'L', 0, 0, $start_x + 130, $pdf_y);
$pdf->MultiCell(0, 0, "数量", 0, 'L', 0, 0, $start_x + 181.5, $pdf_y);
$pdf->MultiCell(0, 0, "単位", 0, 'L', 0, 0, $start_x + 190, $pdf_y);
$pdf->MultiCell(20, 0, "単価", 0, 'R', 0, 0, $start_x + 199.5, $pdf_y);
$pdf->MultiCell(20, 0, "金額", 0, 'R', 0, 0, $start_x + 219.5, $pdf_y);
$pdf->MultiCell(20, 0, "消費税", 0, 'R', 0, 0, $start_x + 239.5, $pdf_y);
$pdf_y += 3.2;
$pdf->MultiCell(0, 0, "注番", 0, 'L', 0, 0, $start_x + 70, $pdf_y);
$pdf_y += 3.8;

for ($i = 0; $i <= 10; $i++) {
	$pdf->SetLineStyle(array("width" => '0.4'));
	$pdf->Line($start_x, $pdf_y, 291, $pdf_y);
	$pdf->SetLineStyle(array("width" => '0.3'));
	$pdf_y += 0.5;
	$pdf->MultiCell(0, 0, "468585", 0, 'L', 0, 0, $start_x + 1, $pdf_y);
	$pdf->SetFont($f["gb"], '', 8);
	$pdf->MultiCell(0, 0, "2022/02/02", 0, 'L', 0, 0, $start_x + 17, $pdf_y);
	$pdf->MultiCell(0, 0, "仕入", 0, 'L', 0, 0, $start_x + 38, $pdf_y);
	$pdf->MultiCell(0, 0, "01066", 0, 'L', 0, 0, $start_x + 58, $pdf_y);
	$pdf->MultiCell(0, 0, "ﾍﾞﾙｸｼｰ", 0, 'L', 0, 0, $start_x + 70, $pdf_y);
	$pdf->MultiCell(0, 0, "テストデータです。", 0, 'L', 0, 0, $start_x + 110, $pdf_y);
	$pdf->SetFont($f["g"], '', 8);
	$pdf_y += 4;
	$pdf->Line($start_x + 2, $pdf_y, 291, $pdf_y);
	$pdf_y += 0.5;
	$pdf->MultiCell(12, 0, "1", 0, 'R', 0, 0, $start_x, $pdf_y);
	$pdf->SetFont($f["gb"], '', 8);
	$pdf->MultiCell(0, 0, "95", 0, 'L', 0, 0, $start_x + 14.8, $pdf_y);
	$pdf->MultiCell(0, 0, "東京", 0, 'L', 0, 0, $start_x + 20, $pdf_y);
	$pdf->MultiCell(0, 0, "07206-00-00000-0", 0, 'L', 0, 0, $start_x + 38, $pdf_y);
	$pdf->MultiCell(61, 3.5, "ＰＯＭ (ポリアセタール)製品", 0, 'L', 0, 0, $start_x + 70, $pdf_y, true, 0, false, false, 3.5, 'T', true);
	$pdf->MultiCell(46, 7, "ｸﾞﾘｯﾌﾟ", 0, 'L', 0, 0, $start_x + 130, $pdf_y, true, 0, false, false, 7, 'T', true);
	$pdf->MultiCell(15, 0, "20", 0, 'R', 0, 0, $start_x + 175, $pdf_y);
	$pdf->SetFont($f["g"], '', 8);
	$pdf->MultiCell(0, 0, "本", 0, 'L', 0, 0, $start_x + 190, $pdf_y);
	$pdf->SetFont($f["gb"], '', 8);
	$pdf->MultiCell(20, 0, "1,000", 0, 'R', 0, 0, $start_x + 200, $pdf_y);
	$pdf->MultiCell(20, 0, "20,000", 0, 'R', 0, 0, $start_x + 220, $pdf_y);
	$pdf->MultiCell(20, 0, "2,000", 0, 'R', 0, 0, $start_x + 240, $pdf_y);
	$pdf_y += 3.5;
	$pdf->MultiCell(0, 0, "959512", 0, 'L', 0, 0, $start_x + 70, $pdf_y);
	$pdf->MultiCell(0, 0, "鳥居大輔", 0, 'L', 0, 0, $start_x + 82, $pdf_y);
	$pdf->SetFont($f["g"], '', 8);
	$pdf_y += 3.5;

	$pdf->MultiCell(27, 5, "*伝票合計*", 0, 'R', 0, 0, $start_x + 176, $pdf_y, true, 0, false, false, 5, 'T', true);
	$pdf->SetFont($f["gb"], '', 8);
	$pdf->MultiCell(20, 0, "1", 0, 'R', 0, 0, $start_x + 200, $pdf_y);
	$pdf->MultiCell(20, 0, "20,000", 0, 'R', 0, 0, $start_x + 220, $pdf_y);
	$pdf->MultiCell(20, 0, "2,000", 0, 'R', 0, 0, $start_x + 240, $pdf_y);
	$pdf->MultiCell(20, 0, "22,000", 0, 'R', 0, 0, 271, $pdf_y);
	$pdf_y += 5;
}

$pdf->Output('sample.pdf', "I");
