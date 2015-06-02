<?php
/**
 * User: kasatkin.o.v
 * Date: 21.05.2015
 * Time: 12:38
 */

namespace vollk;

include_once dirname(__FILE__)."/lib/phpexcel/PHPExcel.php";

class TemplateEngine {

    const ROW_NUMBER = 40;
    const COLUMN_NUMBER = 40;

    private $doc_data;
    private $template_path;
    private $dest_path;

    public function __construct(array $doc_data, $template_path , $dest_path)
    {
        $this->doc_data = $doc_data;
        $this->template_path = $template_path;
        $this->dest_path = $dest_path;
    }

    public function compile()
    {
        if(!is_file($this->template_path))
        {
            throw new \Exception('template not found');
        }


        $objPHPExcel = \PHPExcel_IOFactory::load($this->template_path);
        $objPHPExcel->setActiveSheetIndex(0);
        $aSheet = $objPHPExcel->getActiveSheet();
        for($row_number=1;$row_number < self::ROW_NUMBER;$row_number++)
        {
            for($col_number=0;$col_number < self::COLUMN_NUMBER;$col_number++)
            {
                $this->compileCellVal($aSheet, $col_number, $row_number);
            }
        }

        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save($this->dest_path);
    }

    private function compileCellVal($aSheet, $col_number, $row_number)
    {
        $need_replace = 0;
        $new_words = array();
        $val = trim($aSheet->getCellByColumnAndRow($col_number, $row_number)->getValue());
        $words = explode(' ',$val);

        array_map(function($elem){
            return trim($elem);
        }, $words);

        foreach($words as $word)
        {
            if(mb_substr($word,0,1) == '%')
            {
                $need_replace = 1;
                $param_name = mb_substr($word, 1);
                if(isset($this->doc_data[$param_name]))
                    $new_words[] = $this->doc_data[$param_name];
                else
                    $new_words[] = '';
            }
            else
                $new_words[] = $word;
        }
        $new_val = implode(' ',$new_words);

        if($need_replace)
            $aSheet->setCellValueByColumnAndRow($col_number, $row_number,$new_val);
    }
} 