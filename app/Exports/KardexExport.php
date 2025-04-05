<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCharts;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\ChartColor;




use Maatwebsite\Excel\Events\AfterSheet;

class KardexExport implements
    FromCollection,
    WithHeadings,
    WithDrawings,
    WithStartRow,
    WithColumnWidths,
    WithEvents,
    WithCharts
{
    protected $kardex;

    public function __construct($kardex)
    {
        $this->kardex = $kardex;
    }

    public function collection()
    {
        return collect($this->kardex);
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Tipo',
            'Producto',
            'Entrada',
            'Salida',
            'Stock',
            'Total'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 10,
            'C' => 25,
            'D' => 10,
            'E' => 10,
            'F' => 12,
            'G' => 15,
        ];
    }

    public function startRow(): int
    {
        return 20; // Espacio para el logo
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo Happy Colors');
        $drawing->setPath(public_path('static/logo.jpeg'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('C19');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);

        return $drawing;
    }



    public function charts(): array
{
    // Agrupar datos y renombrar tipos
    $tipos = collect($this->kardex)->groupBy('tipo')->keys()->map(function ($tipo) {
        return $tipo === 'COMPRA' ? 'Inversión' : 'Beneficio';
    });

    $totales = collect($this->kardex)->groupBy('tipo')->map(fn ($items) => collect($items)->sum('total'))->values();

    // Etiquetas y valores
    $dataSeriesLabels = [
        new DataSeriesValues('String', 'Worksheet!$J$2', null, 1),
    ];

    $xAxisTickValues = [
        new DataSeriesValues('String', 'Worksheet!$I$3:I' . (2 + count($tipos)), null, count($tipos)),
    ];

    $dataSeriesValues = [
        new DataSeriesValues('Number', 'Worksheet!$J$3:J' . (2 + count($totales)), null, count($totales)),
    ];

    // Crear gráfico circular (pie chart)
    $series = new DataSeries(
        DataSeries::TYPE_PIECHART,         // <- Cambiado a gráfico circular
        null,                              // Sin agrupación
        range(0, count($dataSeriesValues) - 1), // plotOrder
        $dataSeriesLabels,
        $xAxisTickValues,
        $dataSeriesValues
    );

    $plotArea = new PlotArea(null, [$series]);
    $legend = new Legend(Legend::POSITION_RIGHT, null, false);
    $title = new Title('Distribución: Inversión vs Beneficio');

    $chart = new Chart(
        'grafico_kardex_pie',
        $title,
        $legend,
        $plotArea
    );

    $chart->setTopLeftPosition('L2');
    $chart->setBottomRightPosition('T18');

    return [$chart];
}






    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
    
                // Agrupar y renombrar tipos con sus totales
                $data = collect($this->kardex)->groupBy('tipo')->map(function ($items, $tipo) {
                    $nombre = $tipo === 'COMPRA' ? 'Inversión' : 'Beneficio';
                    $total = collect($items)->sum('total');
    
                    return [
                        'nombre' => $nombre,
                        'total' => $total
                    ];
                })->values();
    
                // Escribir encabezados en la tabla auxiliar
                $startRow = 2;
                $sheet->setCellValue("I{$startRow}", 'Tipo');
                $sheet->setCellValue("J{$startRow}", 'Total');
    
                // Escribir los datos en las columnas I y J
                foreach ($data as $index => $item) {
                    $row = $startRow + $index + 1;
                    $sheet->setCellValue("I{$row}", $item['nombre']);
                    $sheet->setCellValue("J{$row}", $item['total']);
                }
            },
        ];
    }
    
}
