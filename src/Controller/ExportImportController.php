<?php

namespace App\Controller;

use App\Form\ExcelFormatType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class ExportImportController extends AbstractController
{

    protected function createSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();
        // Get active sheet - it is also possible to retrieve a specific sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Set cell name and merge cells
        $sheet->setCellValue('A1', 'Browser characteristics')->mergeCells('A1:D1');

        // Set column names
        $columnNames = [
            'Browser',
            'Developper',
            'Release date',
            'Written in',
        ];
        // $columnLetter = 'A';
        // foreach ($columnNames as $columnName) {
        //     // Allow to access AA column if needed and more
            
        //     $columnLetter++; 
        // }

        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            // Center text
            $sheet->getStyle($columnLetter.'3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetter.'3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Text in bold
            $sheet->getStyle($columnLetter.'3')->getFont()->setBold(true);
            $sheet->getStyle($columnLetter.'3')->getFont()->setBold(true);
            // Autosize column
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);

            $sheet->setCellValue($columnLetter.'3', $columnName);
            $columnLetter++;
        }

        // Add data for each column
        $columnValues = [
            ['Google Chrome', 'Google Inc.', 'September 2, 2008', 'C++'],
            ['Firefox', 'Mozilla Foundation', 'September 23, 2002', 'C++, JavaScript, C, HTML, Rust'],
            ['Microsoft Edge', 'Microsoft', 'July 29, 2015', 'C++'],
            ['Safari', 'Apple', 'January 7, 2003', 'C++, Objective-C'],
            ['Opera', 'Opera Software', '1994', 'C++'],
            ['Maxthon', 'Maxthon International Ltd', 'July 23, 2007', 'C++'],
            ['Flock', 'Flock Inc.', '2005', 'C++, XML, XBL, JavaScript'],
        ];

        $i = 4; // Beginning row for active sheet
        foreach ($columnValues as $columnValue) {
            $columnLetter = 'A';
            foreach ($columnValue as $value) {
                $sheet->setCellValue($columnLetter.$i, $value);
                $columnLetter++;
            }

            $i++;
        }

        return $spreadsheet;
    }

    /**
     * @Route("/exportexcel", name="exportexcel")
     */
    public function exportAction(Request $request)
    {
        $form = $this->createForm(ExcelFormatType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $format = $data['format'];
            $filename = 'Browser_characteristics.'.$format;

            $spreadsheet = $this->createSpreadsheet();

            switch ($format) {
                case 'ods':
                    $contentType = 'application/vnd.oasis.opendocument.spreadsheet';
                    $writer = new Ods($spreadsheet);
                    break;
                case 'xlsx':
                    $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $writer = new Xlsx($spreadsheet);
                    break;
                case 'csv':
                    $contentType = 'text/csv';
                    $writer = new Csv($spreadsheet);
                    break;
                default:
                    return $this->render('export_import/export.html.twig', [
                        'form' => $form->createView(),
                    ]);
            }

            # dd($filename);

            $response = new StreamedResponse();
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function() use ($writer) {
                $writer->save('php://output');
            });
    
            return $response;
        }

        return $this->render('export_import/export.html.twig', [
            'controller_name' => 'ExportImportController',
            'form' => $form->createView(),
        ]);
    }


    protected function loadFile($filename)
    {
        return IOFactory::load($filename);
    }

    protected function readFile($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'ods':
                $reader = new ReaderOds();
                break;
            case 'xlsx':
                $reader = new ReaderXlsx();
                break;
            case 'csv':
                $reader = new ReaderCsv();
                break;
            default:
                throw new \Exception('Invalid extension');
        }
        $reader->setReadDataOnly(true);
        return $reader->load($filename);
    }

    protected function createDataFromSpreadsheet($spreadsheet)
    {
        $data = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $data[$worksheetTitle] = [
                'columnNames' => [],
                'columnValues' => [],
            ];
            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex = $row->getRowIndex();
                if ($rowIndex > 2) {
                    $data[$worksheetTitle]['columnValues'][$rowIndex] = [];
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Loop over all cells, even if it is not set
                foreach ($cellIterator as $cell) {
                    if ($rowIndex === 2) {
                        $data[$worksheetTitle]['columnNames'][] = $cell->getCalculatedValue();
                    }
                    if ($rowIndex > 2) {
                        $data[$worksheetTitle]['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @Route("/import", name="import")
     */
    public function importAction(Request $request)
    {
        // $filename = $this->get('kernel')->getRootDir().'/../export/Browser_characteristics.xlsx';
        $filename = $this->getParameter('kernel.project_dir')  . '/public/export/Browser_characteristics.csv';
        if (!file_exists($filename)) {
            throw new \Exception('File does not exist');
        }

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);

        dd($data);
        return $this->render('export_import/import.html.twig', [
            'data' => $data,
        ]);
    }
}
