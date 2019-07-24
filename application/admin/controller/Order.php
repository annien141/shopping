<?php
namespace app\admin\controller;
use think\Db;
use think\db\Query;
use think\Session;
use think\Controller;
use gmars\rbac\Rbac;
use Request;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\admin\model\Brand as BrandModel;
if (!session_id()) session_start();
class Order extends Base
{
    function list1(){
       return $this->fetch();
    }
    function show(){
        $db=Base::connect();
        $arr=$db->query("select * from `order` order by order_id");
        echo json_encode($arr);
        die;
    }
    function daochu(){
        $helper = new Sample();
        if ($helper->isCli()) {
            $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

            return;
        }
// Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

// Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

// Add some data
        $db=Base::connect();
        $arr=$db->query("select * from `order`");
        foreach ($arr as $key =>$value){
            $i=$key+1;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $value['user_id'])
            ->setCellValue('B'.$i, $value['consignee'])
            ->setCellValue('C'.$i, $value['order_sn'])
            ->setCellValue('D'.$i, $value['order_status']);
        }
// Miscellaneous glyphs, UTF-8
//        $spreadsheet->setActiveSheetIndex(0)
//            ->setCellValue('A4', 'Miscellaneous glyphs')
//            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    function daoru(){
        $db=Base::connect();
        $spreadsheet = new Spreadsheet();
        $helper = new Sample();
        $name=request()->file('excel');

        if(!$name){
            $arr5 = ['code' => '6', 'status' => 'error', 'data' => "文件不能为空"];
            echo json_encode($arr5);
            die;
        }
        $info = $name->move("./uploads1");
        if(!$info){
            $this->error($info->getError());
            die;
        }

        $inputFileName = $info->getSaveName();
        $inputFileName =substr_replace($inputFileName,"/",8,1);
        $inputFileName="./uploads1/".$inputFileName;

        $helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');
        $spreadsheet = IOFactory::load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $db->query("delete from `order`");
        foreach ($sheetData as $key => $value){
            $user_id=$value['A'];
            $consignee=$value['B'];
            $order_sn=$value['C'];
            $order_status=$value['D'];
            $db->query("insert into `order` (`user_id`,`consignee`,`order_sn`,`order_status`) values('$user_id','$consignee','$order_sn','$order_status')");
        }
        unset($info);
        unlink($inputFileName);
        $arr5 = ['code' => '0', 'status' => 'ok', 'data' => "导入成功"];
        return json($arr5);
    }
}