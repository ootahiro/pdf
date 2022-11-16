<?php
/**
 * Pdfクラスで使用するフォント指定クラス
 */
namespace App\Slip\Pdf;

class FontSet
{
    public const FONT_NORMAL = "genshingothicpnormal";
    public const FONT_MEDIUM = "genshingothicpmedium";

    public function __construct(
        private float $size,
        private string $family = self::FONT_NORMAL,
        private string $style = "",
        private array $color = [0,0,0]
    ) {
    }

    public function getFamily(): string
    {
        return $this->family;
    }
    public function getStyle(): string
    {
        return $this->style;
    }
    public function getSize(): float
    {
        return $this->size;
    }
    public function getColor(): array
    {
        return $this->color;
    }
}