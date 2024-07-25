<?php

namespace App\Http\Controllers;

use App\Models\articulos;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCPDF;

class QueryController extends Controller
{
    public function index()
    {
        return view('consulta');
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

            return $pdf->stream("detail.pdf", ['Attachment' => false]);
        } elseif ($request->has('btn_print_with_fecha')) { //Clic en imprimir con fecha

            $products = articulos::where('codigo', $request->txt_cod)->get();
            view()->share('withDate.index', $products);
            $pdf = Pdf::loadView('withDate.index', ['products' => $products]);
            //Tamaño de papel. Se establece por puntos.
            $pdf->setPaper(array(0, 0, 156.4901574803, 71.13188976378), 'portrait');
            $pdf->render();

            return $pdf->stream("detail.pdf", ['Attachment' => false]);
        } elseif ($request->has('btn_update')) { //Actualizar productos

            DB::table('articulos')->truncate();

            try {

                $csvFilePath = '/var/lib/postgresql/14/main/articulos.csv';
                // $csvFilePath = 'C:\\Users\\Administrador\\Downloads\\articles.txt';

                DB::statement(DB::raw("SET client_encoding to 'UTF8';"));
                DB::statement(DB::raw("COPY articulos (codigo, descripcion, precio, fecha_lote, fecha_venc) FROM '{$csvFilePath}' USING DELIMITERS ';'"));

                session()->flash('status', 'Articulos actualizados!');
            } catch (Exception $e) {
                session()->flash('status', $e->getMessage());
            }

            /*
            esto se ejecuta desde la carpeta 'data' de postgres
            */

            return view('consulta');
        }
    }
}
