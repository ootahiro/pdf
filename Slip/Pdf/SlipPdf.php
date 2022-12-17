<?php
/**
 * Tcpdfをラップする
 */
namespace App\Slip\Pdf;

require_once(__DIR__. '/../../tcpdf/tcpdf.php');
require_once(__DIR__. '/../../tcpdf/fpdi/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

class SlipPdf extends Fpdi
{
    /**
     * Pdf->GetStringWidth()で幅を取得すると文字が落ちてしまうので、
     * 落ちないぎりぎりの追加する幅の、文字サイズに対する割合
     */
    public const TEXT_NO_WRAP_PADDING_RATIO = 6.9;

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

    private array $dumpData = [];

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

    public function dumpPage()
    {
        $this->AddPage();
        $this->addText(
            implode("\n", $this->dumpData),
            10, 10,
            options: [
                "font" => new FontSet(8.5, color: [0,0,0])
            ]
        );
    }
    public function dump($arg): self
    {
        $trace = debug_backtrace();
        $this->dumpData[] = $trace[0]["file"]. " IN ". $trace[0]["line"];
        $this->dumpData[] = var_export($arg, true);
        $this->dumpData[] = " ";
        return $this;
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
    public function getDefaultFont(): FontSet
    {
        return $this->defaultFontSet;
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
            'maxh' => $h,
            'valign' => 'T',
            'fitcell' => true,
            // 今回のみのフォント設定 FontSetインスタンス
            'font' => null,
            // fill => true の場合で今回だけ適用する塗り色 [R,G,B]
            'fillColor' => null,
            // テキストバウンディングボックスのデバッグモード
            'debug' => false,
            // パッディング
            'paddingTop' => 0,
            'paddingBottom' => 0,
            'paddingLeft' => 0,
            'paddingRight' => 0
        ], $options);
        if(is_a($options['font'], FontSet::class)) {
            $this->useFontSet($options['font']);
            $font = $options["font"];
        } else {
            $font = $this->defaultFontSet;
        }
        if(!$options['fill'] && ($options['debug'] || $this->globalTextDebug)) {
            $options['fill'] = 1;
            $this->setFillColorArray($this->debugFillColor);
        }
        if($options['fill'] && is_array($options['fillColor'])) {
            $this->setFillColorArray($options['fillColor']);
        }
        if(!$w) {
            $w = $this->getCellWidthByText($text, $font) + ($font->getSize() * 0.1);
        }
        $w = $w - $options['paddingLeft'] - $options['paddingRight'];
        $x += $options['paddingLeft'];
        $h = $h - $options['paddingTop'] - $options['paddingBottom'];
        $y += $options['paddingTop'];

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
        $color = $fontSet->getColor();
        $this->setTextColor($color[0], $color[1], $color[2]);
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
        $dash = (isset($options['dash']) && $options['dash']);
        $color = ($options['line_color'] ?? [0, 0, 0]);
        $options = array_merge([
            "width" => $width,
            "color" => $color,
            "cap" => "round"
        ], $options);
        $this->Line(
            $x, $y, $toX, $toY, $options
        );
        if($dash) {
            $this->setLineStyle([
                "dash" => false
            ]);
        }
        $this->setLineStyle([
            "color" => $color
        ]);

        return $this;
    }

    public function fillRect(
        float $x,
        float $y,
        float $w,
        float $h,
        array $color,
        string $style = "F",
        ?array $borderStyle = null
    ): self
    {
        $this->rect(
            $x, $y, $w, $h, $style,
            $borderStyle? $borderStyle: [],
            $color
        );
        return $this;
    }

    /**
     * 文字と設定から必要な横幅を返す
     * @param string $text
     * @param FontSet $fontSet
     * @return float
     */
    public function getCellWidthByText(
        string $text,
        FontSet $fontSet
    ): float
    {
        return $this->GetStringWidth(
            $text,
            $fontSet->getFamily(),
            $fontSet->getStyle(),
            $fontSet->getSize()
        ) + ($fontSet->getSize() / self::TEXT_NO_WRAP_PADDING_RATIO);
    }

    /**
     * 文字高さを取得
     * @param float $textWidth
     * @param string $text
     * @param FontSet $fontSet
     * @return float
     */
    public function getCellHeightByText(
        float $textWidth,
        string $text,
        FontSet $fontSet
    ): float
    {
        $this->useFontSet($fontSet);
        $height = $this->GetStringHeight($textWidth, $text, false);
        $this->useFontSet($this->defaultFontSet);
        return $height;
    }
}