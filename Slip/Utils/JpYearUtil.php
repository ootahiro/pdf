<?php
/**
 * 西暦を和暦に変換
 */
namespace App\Slip\Utils;

class JpYearUtil
{
    static private array $yearMap = [
        2019 => "令和",
        1989 => "平成",
        1926 => "昭和",
        1912 => "大正",
        1868 => "明治"
    ];

    /**
     * 西暦から
     * @param int $baseYear
     * @return string
     */
    static public function convert(int $baseYear): string
    {
        foreach(self::$yearMap as $year => $name) {
            if($baseYear >= $year) {
                $printYear = $baseYear - $year + 1;
                if($printYear === 1) {
                    $printYear = "元";
                }
                return $name. $printYear;
            }
        }
        return "";
    }

    /**
     * DateTimeを和暦表記にする
     * @param \DateTime|string $date
     * @param string $preFix
     * @param string $postFix
     * @return string
     */
    static public function formatJpDate(\DateTime|string $date, string $preFix = "", string $postFix = ""): string
    {
        if(is_string($date)) return $date;
        return sprintf(
            "%s%s年%s%s",
            $preFix,
            self::convert($date->format('Y')),
            $date->format("m月d日"),
            $postFix
        );
    }
}