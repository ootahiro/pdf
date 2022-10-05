<?php
/**
 * Tcpdfをラップする
 */
namespace App\Slip\Pdf;

require_once(__DIR__. '/../../tcpdf/tcpdf.php');
require_once(__DIR__. '/../../tcpdf/fpdi/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

class Pdf extends Fpdi
{
    /**
     * デフォルトの文字設定
     */
    private FontSet $defaultFontSet;
    /**
     * デフォルトの塗り色 [ R, G, B ]
     */
    private array $defaultFillColor;
    /**
     * デバッグ時の塗り色
     */
    private array $debugFillColor = [200,200,200];
    /**
     * テキストの全体デバッグモード
     * @var bool
     */
    private bool $globalTextDebug = false;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->setMargins(0,0,0,0);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->setAutoPageBreak(true);
        $this->defaultFontSet = new FontSet(12, FontSet::FONT_NORMAL);
        $this->defaultFillColor = [255,255,255];
    }

    /**
     * テキストのバウンディングボックスの全体デバッグモードを指定
     * @param bool $mode
     * @return $this
     */
    public function setGlobalTextDebug(bool $mode): self
    {
        $this->globalTextDebug = $mode;
        return $this;
    }

    /**
     * 基本フォントを設定
     * @param FontSet $fontSet
     * @return $this
     */
    public function setDefaultFont(FontSet $fontSet): self
    {
        $this->defaultFontSet = $fontSet;
        $this->useFontSet($fontSet);
        return $this;
    }

    /**
     * 塗り色を指定
     * @param array $color
     * @return $this
     */
    public function setDefaultFillColor(array $color): self
    {
        $this->defaultFillColor = $color;
        $this->setFillColorArray($color);
        return $this;
    }

    /**
     * MultiCell()のショートカット
     * @param string $text
     * @param float $x
     * @param float $y
     * @param string $align
     * @param float $w
     * @param float $h
     * @param array $options その他
     * @param FontSet|null $fontSet 一時使用するフォントを指定
     * @return $this
     */
    public function addText(
        string $text,
        float $x,
        float $y,
        string $align = "L",
        float $w = 0,
        float $h = 0,
        array $options = []
    ): self
    {
        $options = array_merge([
            // FpdiのMultiCell()の設定と同じ
            'border' => 0,
            'fill' => false,
            'ln' => 1,
            'reseth' => true,
            'stretch' => 0,
            'ishtml' => false,
            'autopadding' => true,
            'maxh' => 0,
            'valign' => 'T',
            'fitcell' => false,
            // 今回のみのフォント設定 FontSetインスタンス
            'font' => null,
            // fill => true の場合で今回だけ適用する塗り色 [R,G,B]
            'fillColor' => null,
            // テキストバウンディングボックスのデバッグモード
            'debug' => false
        ], $options);
        if(is_a($options['font'], FontSet::class)) {
            $this->useFontSet($options['font']);
        }
        if(!$options['fill'] && ($options['debug'] || $this->globalTextDebug)) {
            $options['fill'] = 1;
            $this->setFillColorArray($this->debugFillColor);
        }
        if($options['fill'] && is_array($options['fillColor'])) {
            $this->setFillColorArray($options['fillColor']);
        }

        $this->MultiCell(
            $w, $h, $text,
            $options['border'],
            $align,
            $options['fill'],
            $options['ln'],
            $x,
            $y,
            $options['reseth'],
            $options['stretch'],
            $options['ishtml'],
            $options['autopadding'],
            $options['maxh'],
            $options['valign'],
            $options['fitcell']
        );
        if(is_a($options['font'], FontSet::class)) {
            $this->useFontSet($this->defaultFontSet);
        }
        if(!$options['fill'] && ($options['debug'] || $this->globalTextDebug)) {
            $this->setFillColorArray($this->defaultFillColor);
        }
        if($options['fill'] && is_array($options['fillColor'])) {
            $this->setFillColorArray($this->defaultFillColor);
        }
        return $this;
    }

    /**
     * FontSetクラスから setFont()を実行
     * @param FontSet $fontSet
     * @return $this
     */
    public function useFontSet(FontSet $fontSet): self
    {
        $this->setFont($fontSet->getFamily(), $fontSet->getStyle(), $fontSet->getSize());
        return $this;
    }

    /**
     * 線を描画
     * Line() と違い、 $toXと$toYに線の長さを指定
     * @param float $x
     * @param float $y
     * @param float|null $toX
     * @param float|null $toY
     * @param float $width
     * @param array $options
     * @return $this
     */
    public function drawLine(
        float $x,
        float $y,
        float $toX = null,
        float $toY = null,
        float $width = 0.2,
        array $options = []
    ): self
    {
        $toX = ($toX)? $x + $toX : $x;
        $toY = ($toY)? $y + $toY : $y;
        $options = array_merge([
            "width" => $width
        ], $options);
        $this->Line(
            $x, $y, $toX, $toY, $options
        );

        return $this;
    }
}