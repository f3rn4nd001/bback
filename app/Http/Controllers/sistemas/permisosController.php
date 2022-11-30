<?php

namespace App\Http\Controllers\sistemas;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class permisosController extends Controller
{
    public function objeto_a_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value){$result[$key] = $this->objeto_a_array($value);}
            return $result;
        }
        return $data;
    }

    public function getDetallesPermisos(Request $request){ 
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $json = json_decode( $request['datos'] ,true);
            $select="SELECT rusm.*,cm.tNombre AS menu, cct.tNombre AS controler, cct.url AS UrlController,csm.tNombre AS Submenu, csm.url AS urlSubmenu,cp.tNombre AS permisos FROM relusuariosubmenu rusm
            LEFT JOIN catmenu cm ON cm.ecodMenu = rusm.ecodMenu
            LEFT JOIN catcontroller cct On cct.ecodController= rusm.ecodController
            LEFT JOIN catsubmenu csm ON csm.ecodSubMenu = rusm.ecodSubMenu
            LEFT JOIN catpermisos cp ON cp.ecodPermisos = rusm.ecodPermisos 
            WHERE rusm.ecodUsuario = ".(int)$json;
            $sql = DB::select(DB::raw($select));
            return response()->json(($sql));
           }
    }
    public function postRegistro(Request $request){
      
            print_r("rdhhtdth");
            return $request;
    
    }
}
