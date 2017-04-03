<?php

namespace App\Http\Controllers;

use App\Steeringcontent;
use App\Unit;
use App\Sourcesteering;
use App\User;
use App\Viphuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;

class ReportController extends Controller
{
    public function export(Request $request) {
        $chuahoanthanh_dunghan = intval( $request->input('v1') );
        $chuahoanthanh_quahan = intval( $request->input('v2') );
        $hoanthanh_dunghan = intval( $request->input('v3') );
        $hoanthanh_quahan = intval( $request->input('v4') );
        $bi_huy = intval( $request->input('v5') );
        $total = $chuahoanthanh_dunghan + $chuahoanthanh_quahan + $hoanthanh_dunghan + $hoanthanh_quahan + $bi_huy;

        $filter = $request->input('f');

        $fileName = base_path() . "/storage/example/format-baocao.xlsx";
        $footer = "*) Dữ liệu được trích suất từ hệ thống theodoichidao.moet.gov.vn (" . date('H:i d/m/Y') .")";
        $excelobj = PHPExcel_IOFactory::load($fileName);
        $excelobj->setActiveSheetIndex(0);
        $excelobj->getActiveSheet()->toArray(null, true, true, true);
        $excelobj->getActiveSheet()->setCellValue('A5', $total)
            ->setCellValue('B5', $chuahoanthanh_dunghan)
            ->setCellValue('C5', $chuahoanthanh_quahan)
            ->setCellValue('D5', $hoanthanh_dunghan)
            ->setCellValue('E5', $hoanthanh_quahan)
            ->setCellValue('F5', $bi_huy)
            ->setCellValue('G5', $filter)
            ->setCellValue('A6', $footer);

        $output_file = "/public/file/export-data-" . date("dmYHis") . ".xlsx";
        $objWriter = PHPExcel_IOFactory::createWriter($excelobj, "Excel2007");
        $objWriter->save(base_path() . $output_file);
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=baocaotonghop" . date('Ymd') . ".xlsx");
        readfile(base_path() . $output_file);
    }

    public function exportSteering(Request $request) {
        $fileName = base_path() . "/storage/example/template_steering.xlsx";
        $excelobj = PHPExcel_IOFactory::load($fileName);
        $excelobj->setActiveSheetIndex(0);
        $excelobj->getActiveSheet()->toArray(null, true, true, true);
//        return response()->json(['mess' => $request->data[0]['content']]);
        foreach($request->data as $idx=>$data){
            $excelobj->getActiveSheet()->setCellValue('A'. ($idx + 2), $idx + 1)
                ->setCellValue('B'. ($idx + 2), $data['content'])
                ->setCellValue('C'. ($idx + 2), $data['source'])
                ->setCellValue('D'. ($idx + 2), $data['unit'])
                ->setCellValue('E'. ($idx + 2), $data['follow'])
                ->setCellValue('F'. ($idx + 2), $data['deadline'])
                ->setCellValue('G'. ($idx + 2), $data['status']);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($excelobj, "Excel2007");
        $output_file = "/file/Danhmucnhiemvu" . date("dmyhis") . ".xlsx";
        $objWriter->save(base_path() . "/public" . $output_file);
        return response()->json(['file' => $output_file]);
    }

    public function exportReport(Request $request) {
        $fileName = base_path() . "/storage/example/template_report.xlsx";
        $excelobj = PHPExcel_IOFactory::load($fileName);
        $excelobj->setActiveSheetIndex(0);
        $excelobj->getActiveSheet()->toArray(null, true, true, true);
        foreach($request->data as $idx=>$data){
            $excelobj->getActiveSheet()->setCellValue('A'. ($idx + 2), $idx + 1)
                ->setCellValue('B'. ($idx + 2), $data['content'])
                ->setCellValue('C'. ($idx + 2), $data['conductor'])
                ->setCellValue('D'. ($idx + 2), $data['time'])
                ->setCellValue('E'. ($idx + 2), $data['source'])
                ->setCellValue('F'. ($idx + 2), $data['unit'])
                ->setCellValue('G'. ($idx + 2), $data['follow'])
                ->setCellValue('H'. ($idx + 2), $data['deadline'])
                ->setCellValue('I'. ($idx + 2), $data['status']);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($excelobj, "Excel2007");
        $output_file = "/file/Danhmucbaocao" . date("dmyhis") . ".xlsx";
        $objWriter->save(base_path() . "/public" . $output_file);
        return response()->json(['file' => $output_file]);
    }

    public function exportUnit(Request $request) {
        $fileName = base_path() . "/storage/example/format-baocaodonvi.xlsx";
        $excelobj = PHPExcel_IOFactory::load($fileName);
        $excelobj->setActiveSheetIndex(0);
        $excelobj->getActiveSheet()->toArray(null, true, true, true);

        $filter = $request->input('filter');
        $count = 5;
        foreach($request->data as $idx=>$data){
            $excelobj->getActiveSheet()->setCellValue('A'. ($idx + 5), $idx + 1)
                ->setCellValue('B'. ($idx + 5), $data['unit'])
                ->setCellValue('C'. ($idx + 5), $data['total'])
                ->setCellValue('D'. ($idx + 5), $data['v1'])
                ->setCellValue('E'. ($idx + 5), $data['v2'])
                ->setCellValue('F'. ($idx + 5), $data['v3'])
                ->setCellValue('G'. ($idx + 5), $data['v4'])
                ->setCellValue('H'. ($idx + 5), $data['v5']);

            $excelobj->getActiveSheet()
                ->getStyle('A'.($idx + 5).':I'.($idx + 5))
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getColor()
                ->setRGB('000000');

            $count ++;
        }
        $excelobj->getActiveSheet()->mergeCells('I5:I'.($count-1));


        $excelobj->getActiveSheet()->setCellValue('I5', $filter);
        $footer = "*) Dữ liệu được trích suất từ hệ thống theodoichidao.moet.gov.vn (" . date('H:i d/m/Y') .")";
        $excelobj->getActiveSheet()->setCellValue('A' . ($count + 1), $footer);
        $objWriter = PHPExcel_IOFactory::createWriter($excelobj, "Excel2007");
        $output_file = "/file/Baocaodonvi" . date("dmyhis") . ".xlsx";
        $objWriter->save(base_path() . "/public" . $output_file);
        return response()->json(['file' => $output_file]);
    }

    public function index(Request $request)
    {

        $unit=Unit::orderBy('created_at', 'DESC')->get();
        $users = User::orderBy('fullname', 'ASC')->get();
        $sourcesteering=Sourcesteering::orderBy('id', 'DESC')->get();
        $viphuman = Viphuman::orderBy('created_at', 'DESC')->get();

        $firstunit = array();
        $secondunit = array();
        $tree_unit = array();
        foreach ($unit as $row) {
            if ($row->parent_id == 0){
                $children = array();
                foreach ($unit as $c) {
                    if ($c->parent_id == $row->id){
                        $children[$c->id] = $c;
                    }
                }
                $row->children = $children;
                $tree_unit[] = $row;
            }
        }
        foreach ($unit as $row) {
            $firstunit["u|" . $row->id] = $row->name;
            $secondunit["u|" . $row->id] = $row->shortname;
        }
        foreach ($users as $row){
            $firstunit["h|" . $row->id] = $row->fullname;
            $secondunit["h|" . $row->id] = $row->fullname;
        }

        $dataunit=Unit::orderBy('created_at', 'DESC')->get();
        $datauser=User::orderBy('fullname', 'ASC')->get();

        $firstunit = array();
        $secondunit = array();

        foreach ($dataunit as $row) {
            $firstunit[$row->id] = $row->name;
            $secondunit[$row->id] = $row->name;
        }

        $datauser=User::orderBy('fullname', 'ASC')->get();
        $user = array();
        foreach($datauser as $row){
            $user[$row->id] = $row->fullname;
        }

        $data=Steeringcontent::orderBy('created_at', 'DESC')->get();

        $allsteeringcode = DB::table('sourcesteering')->pluck('code');


        return view('report.index',['lst'=>$data, 'firstunit'=>$firstunit,'secondunit'=>$secondunit, 'treeunit'=>$tree_unit,
            'unit'=>$unit, 'user'=>$user, 'sourcesteering'=>$sourcesteering, 'viphuman'=>$viphuman,
            'allsteeringcode'=>$allsteeringcode->all(), 'unit'=>$firstunit,'unit2'=>$secondunit, 'users'=>$users]);
    }

    public function unit(Request $request)
    {
        $unit=Unit::orderBy('created_at', 'DESC')->get();
        $unitall=DB::table('unit')->where('parent_id', '!=', '0')->get();
        $users = User::orderBy('fullname', 'ASC')->get();
        $sourcesteering=Sourcesteering::orderBy('id', 'DESC')->get();
        $viphuman = Viphuman::orderBy('created_at', 'DESC')->get();

        $firstunit = array();
        $secondunit = array();
        $tree_unit = array();
        foreach ($unit as $row) {
            if ($row->parent_id == 0){
                $children = array();
                foreach ($unit as $c) {
                    if ($c->parent_id == $row->id){
                        $children[$c->id] = $c;
                    }
                }
                $row->children = $children;
                $tree_unit[] = $row;
            }
        }
        foreach ($unit as $row) {
            $firstunit["u|" . $row->id] = $row->name;
            $secondunit["u|" . $row->id] = $row->shortname;
        }
        foreach ($users as $row){
            $firstunit["h|" . $row->id] = $row->fullname;
            $secondunit["h|" . $row->id] = $row->fullname;
        }

        $dataunit=Unit::orderBy('created_at', 'DESC')->get();
        $datauser=User::orderBy('fullname', 'ASC')->get();

        $firstunit = array();
        $secondunit = array();

        foreach ($dataunit as $row) {
            $firstunit[$row->id] = $row->name;
            $secondunit[$row->id] = $row->name;
        }

        $datauser=User::orderBy('fullname', 'ASC')->get();
        $user = array();
        foreach($datauser as $row){
            $user[$row->id] = $row->fullname;
        }

        $data=Steeringcontent::orderBy('unit', 'DESC')->get();

        $allsteeringcode = DB::table('sourcesteering')->pluck('code');


        return view('report.unit',['lst'=>$data, 'firstunit'=>$firstunit,'secondunit'=>$secondunit, 'treeunit'=>$tree_unit,
            'unitall'=>$unitall, 'user'=>$user, 'sourcesteering'=>$sourcesteering, 'viphuman'=>$viphuman,
            'allsteeringcode'=>$allsteeringcode->all(), 'unit'=>$firstunit,'unit2'=>$secondunit, 'users'=>$users]);
    }

}


