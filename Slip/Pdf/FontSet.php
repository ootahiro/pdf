<?php
/**
 * Pdfクラスで使用するフォント指定クラス
 */
namespace App\Slip\Pdf;

class FontSet
{
    public const FONT_NORMAL = "genshingothicpnormal";
    public const FONT_MEDIUM = "genshingothicpmedium";
    public const FONT_MINCHO = "aozoraminchomedium";

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
    public function setFamily(string $family): self
    {
        $this->family = $family;
        return $this;
    }

    public function getStyle(): string
    {
        return $this->style;
    }
    public function setStyle(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    public function getSize(): float
    {
        return $this->size;
    }
    public function setSize(float $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getColor(): array
    {
        return $this->color;
    }
    public function setColor(array $color): self
    {
        $this->color = $color;
        return $this;
    }
}
