<?php

namespace App\Http\Controllers;

use App\Models\articulos;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($request->btn_update == null) { //Clic en imprimir

            $products = articulos::where('codigo', $request->txt_codigo)->get();

            view()->share('exports.index', $products);
            $pdf = Pdf::loadView('exports.index', ['products' => $products]);
            //Tamaño de papel. Se establece por puntos. 
            $pdf->setPaper(array(0,0,156.4901574803, 71.13188976378), 'portrait');                 
            $pdf->render();

            return $pdf->stream("detail.pdf", ['Attachment' => false]);           
            
        } else { //Clic en actualizar productos

            DB::table('articulos')->truncate();

            try {
                DB::statement(DB::raw("COPY test FROM 'articulos.txt' USING DELIMITERS ';'"));
                session()->flash('status', 'Articulos actualizados!');              
            } catch(Exception $e) {
                session()->flash('status', $e->getMessage());
            }

            return view('consulta');           
        }
    }
}
