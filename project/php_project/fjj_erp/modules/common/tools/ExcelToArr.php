<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/13
 * Time: 下午 05:24
 */

namespace app\modules\common\tools;


class ExcelToArr
{
    public function excel2arry($fileName, $fileExt, $path = 'uploads/')
    {
        set_time_limit(0);
        if ($fileExt == 'xls') {
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else
            if ($fileExt == 'xlsx') {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }
        // 载入文件
        $PHPExcel = $PHPReader->load($path . $fileName . '.' . $fileExt);
        // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        // 获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        // 获取总行数
        $allRow = $currentSheet->getHighestRow();
        // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            // 从哪列开始，A表示第一列
            if (strlen($allColumn) == 2) {
                $Column = "Z";
            } else {
                $Column = $allColumn;
            }
            for ($currentColumn = "A"; $currentColumn <= $Column; $currentColumn++) {
                if ($currentColumn == "AA") {
                    $Column = $allColumn;
                }
                $address = $currentColumn . $currentRow;
                $cell = $currentSheet->getCell($address)->getValue();
                //判断日期并转为时间戳
                if($PHPExcel->getActiveSheet()->getStyle($address)->getNumberFormat()->getFormatCode() == \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14){
                    $cell=\PHPExcel_Shared_Date::ExcelToPHP($cell);
                }
                if (is_object($cell)) {
                    $cell = $cell->__toString();
                }
                if ($cell instanceof PHPExcel_RichText) {
                    $cell = $cell->__toString();
                }
                $data[$currentRow][$currentColumn] = $cell;
            }
        }
        foreach ($data as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (!empty($vv)) {
                    $new[$k][$kk] = $vv;
                } else {
                    $new[$k][$kk] = '';
                }
            }
        }
        return $new;
    }

}