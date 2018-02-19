<?php

namespace sisVentas\Http\Controllers;

use sisVentas\Http\Requests;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comprasmes=DB::select('SELECT monthname(i.fecha_hora) as mes, sum(di.cantidad*di.precio_compra) as totalmes from ingreso i inner join detalle_ingreso di on i.idingreso=di.idingreso where i.estado="A" group by monthname(i.fecha_hora) order by month(i.fecha_hora) desc limit 12');

        $ventasmes=DB::select('SELECT monthname(v.fecha_hora) as mes, sum(v.total_venta) as totalmes from venta v where v.estado="A" group by monthname(v.fecha_hora) order by month(v.fecha_hora) desc limit 12');

        $ventasdia=DB::select('SELECT DATE(v.fecha_hora) as dia, sum(v.total_venta) as totaldia from venta v where v.estado="A" group by v.fecha_hora order by day(v.fecha_hora) desc limit 15');

        $productosvendidos=DB::select('SELECT a.nombre as articulo,sum(dv.cantidad) as cantidad from articulo a inner join detalle_venta dv on a.idarticulo=dv.idarticulo inner join venta v on dv.idventa=v.idventa where v.estado="A" and year(v.fecha_hora)=year(curdate()) group by a.nombre order by sum(dv.cantidad) desc limit 10');

        $totales=DB::select('SELECT (select ifnull(sum(di.cantidad*di.precio_compra),0) from ingreso i inner join detalle_ingreso di on i.idingreso=di.idingreso where DATE(i.fecha_hora)=curdate() and i.estado="A") as totalingreso, (select ifnull(sum(v.total_venta),0) from venta v where DATE(v.fecha_hora)=curdate() and v.estado="A") as totalventa');

            return view('home',["comprasmes"=>$comprasmes,"ventasmes"=>$ventasmes,"ventasdia"=>$ventasdia,"productosvendidos"=>$productosvendidos,"totales"=>$totales]);
    }
}
