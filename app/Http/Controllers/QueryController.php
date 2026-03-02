<?php

namespace App\Http\Controllers;

use App\Models\articulos;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use TCPDF;

class QueryController extends Controller
{
    public function index()
    {
        return view('consulta');
    }

    public function editor(string $codigo)
    {
        $products = articulos::where('codigo', $codigo)->get();
        return view('editor', ['products' => $products, 'codigo' => $codigo]);
    }

    public function show()
    {

        if (RequestFacade::ajax()) {

            $codigo = RequestFacade::get('codigo');

            //Eloquent
            $respuesta = articulos::select('descripcion', 'precio')
                ->where('codigo', $codigo)
                ->get();

            //Si no hay resultados
            if ($respuesta->isEmpty()) {
                //Respuesta en modo JSON
                return response()->json([
                    'descripcion' => 'INEXISTENTE',
                    'precio' => '0'
                ]);
            } else {
                //Destructuración del objeto
                //Se puede ver el resultado con var_dump()
                $descripcion = $respuesta[0]->descripcion;
                $precio = $respuesta[0]->precio;
                //Respuesta en modo JSON
                return response()->json([
                    'descripcion' => $descripcion,
                    'precio' => $precio
                ]);
            }
        }
    }

    public function print(Request $request)
    {

        if ($request->has('btn_print')) { //Clic en imprimir sin fecha

            $products = articulos::where('codigo', $request->txt_cod)->get();
            view()->share('withoutDate.index', $products);
            $pdf = Pdf::loadView('withoutDate.index', ['products' => $products]);
            //Tamaño de papel. Se establece por puntos.
            $pdf->setPaper(array(0, 0, 156.4901574803, 71.13188976378), 'portrait');
            $pdf->render();

            return $pdf->stream("detail.pdf");
        } elseif ($request->has('btn_print_with_fecha')) { //Clic en imprimir con fecha

            $products = articulos::where('codigo', $request->txt_cod)->get();
            view()->share('withDate.index', $products);
            $pdf = Pdf::loadView('withDate.index', ['products' => $products]);
            //Tamaño de papel. Se establece por puntos.
            $pdf->setPaper(array(0, 0, 156.4901574803, 71.13188976378), 'portrait');
            $pdf->render();

            return $pdf->stream("detail.pdf");
        } elseif ($request->has('btn_upload')) { // Subir y procesar archivo
            if (!$request->hasFile('file_upload')) {
                session()->flash('status', 'Debe seleccionar un archivo.');
                return view('consulta');
            }

            $file = $request->file('file_upload');
            $extension = $file->getClientOriginalExtension();
            $path = base_path('csv');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            try {
                if ($extension == 'csv') {
                    $file->move($path, 'articulos.csv');
                    DB::table('articulos')->truncate();

                    $csvFilePath = '/var/lib/postgresql/csv/articulos.csv';
                    DB::statement(DB::raw("SET client_encoding to 'UTF8';"));
                    DB::statement(DB::raw("COPY articulos (codigo, descripcion, precio, fecha_lote, fecha_venc) FROM '{$csvFilePath}' USING DELIMITERS ';'"));

                    session()->flash('status', 'Archivo CSV procesado correctamente!');
                } else {
                    $excelFileName = 'articulos.' . $extension;
                    $file->move($path, $excelFileName);
                    $fullExcelPath = $path . '/' . $excelFileName;

                    $rows = Excel::toArray(new ProductsImport, $fullExcelPath)[0];

                    DB::table('articulos')->truncate();

                    $dataToInsert = [];
                    foreach ($rows as $index => $row) {
                        // Saltar la primera fila si parece ser la cabecera
                        if ($index === 0 && strtolower($row[0]) === 'codigo') {
                            continue;
                        }

                        // O si por alguna razón la cabecera está en otra posición
                        if (strtolower($row[0]) === 'codigo') {
                            continue;
                        }

                        if (empty($row[0])) {
                            continue;
                        }

                        // Función auxiliar para convertir fecha de Excel (número) a string
                        $convertDate = function ($value) {
                            if (is_numeric($value)) {
                                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                            }
                            return $value;
                        };

                        $dataToInsert[] = [
                            'codigo' => $row[0],
                            'descripcion' => $row[1],
                            'precio' => $row[2],
                            'fecha_lote' => $convertDate($row[3] ?? null),
                            'fecha_venc' => $convertDate($row[4] ?? null),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    try {
                        DB::beginTransaction();
                        DB::table('articulos')->truncate();

                        // Insertar en bloques para mejor rendimiento
                        foreach (array_chunk($dataToInsert, 500) as $chunkIndex => $chunk) {
                            DB::table('articulos')->insert($chunk);
                        }

                        DB::commit();
                        session()->flash('status', 'Archivo Excel procesado correctamente! (' . count($dataToInsert) . ' registros)');
                    } catch (Exception $e) {
                        DB::rollBack();
                        session()->flash('status', 'Error al procesar el archivo Excel: ' . $e->getMessage());
                    }
                }
            } catch (Exception $e) {
                session()->flash('status', 'Error: ' . $e->getMessage());
            }

            return view('consulta');
        }
    }
}
