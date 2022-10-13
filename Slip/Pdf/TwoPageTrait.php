<?php
/**
 * 2分割したA4のY座標を返す
 */
namespace App\Slip\Pdf;

trait TwoPageTrait
{
    private float $A4P_offsetY = 148.5;
    public function getA4PY(float $y, bool $isSecondPage): float
    {
        return $isSecondPage ? $y + $this->A4P_offsetY: $y;
    }
}