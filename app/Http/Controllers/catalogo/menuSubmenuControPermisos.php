<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class menuSubmenuControPermisos extends Controller
{
    public function getMenu(Request $request){
        $json = json_decode( $request['datos'] ,true);
        $json    = (isset($json) && $json != "" ? "'" . (trim($json)) . "'" : "");           
            
        $selecmenu= "SELECT rusm.ecodSubMenu, csm.tNombre AS submenu, csm.url,cm.tNombre,cp.tNombre AS permisos,cp.tNombreCorto AS permisosNCorto,cc.url AS controller FROM relusuariosubmenu rusm
            LEFT JOIN catsubmenu csm ON csm.ecodSubMenu = rusm.ecodSubMenu
            LEFT JOIN catmenu cm ON cm.ecodMenu = rusm.ecodMenu
            LEFT JOIN catpermisos cp ON cp.ecodPermisos = rusm.ecodPermisos
            LEFT JOIN catcontroller cc ON cc.ecodController =rusm.ecodController
        WHERE rusm.ecodUsuario= ".$json;
        $selecmenusql = DB::select(DB::raw($selecmenu));
        foreach ($selecmenusql as $key => $v){
            $arrselecmenu[]=array(
                'Menu' => $v->tNombre,
                'ecodSubMenu' => $v-> ecodSubMenu,
                'submenu'=>$v->submenu,
                'url'=> $v->url,
                'permisos'=>$v->permisos,
                'permisosNCorto' =>$v->permisosNCorto,
                'controller'=>$v->controller
            );
        }
        return response()->json(['arrselecmenu'=>(isset($arrselecmenu) ? $arrselecmenu : "")]);
    }
    
    public function p1file(Request $request){
       print_r($request);
    }
    public function getMenuSubmenus(Request $request){
        $selectcatsubmenu="SELECT * FROM catsubmenu";
        $sqlselectcatsubmenu = DB::select(DB::raw($selectcatsubmenu));
        $selectcatcontroller="SELECT * FROM catcontroller";
        $sqlselectcatcontroller = DB::select(DB::raw($selectcatcontroller));
        $selectcatmenu="SELECT * FROM catmenu";
        $sqlselectcatmenu = DB::select(DB::raw($selectcatmenu));
        $selectcatpermisos="SELECT * FROM catpermisos";
        $sqlselectcatpermisos = DB::select(DB::raw($selectcatpermisos));
        return response()->json(['sqlselectcatsubmenu'=>(isset($sqlselectcatsubmenu) ? $sqlselectcatsubmenu : ""),'sqlselectcatcontroller'=>(isset($sqlselectcatcontroller) ? $sqlselectcatcontroller : ""),'sqlselectcatmenu'=>(isset($sqlselectcatmenu) ? $sqlselectcatmenu : ""),'sqlselectcatpermisos'=>(isset($sqlselectcatpermisos)?$sqlselectcatpermisos:"")]);   
    }
}
