<?php

namespace App\Http\Controllers\catalogo;
use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ciuddesEntidades extends Controller
{
    public function objeto_a_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value){$result[$key] = $this->objeto_a_array($value);}
            return $result;
        }
        return $data;
    }
    public function getRegistrociudadmunicipio(Request $request){ 
        if (is_array($request['datos']) || is_object($request['datos'])){
            $result = array();
            foreach ($request['datos'] as $key => $value){
                $result[$key] = $this->objeto_a_array($value);
            }
            $result;
            $jsonX = isset($result['filtro'])? $result['filtro']:[];
        }
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
                $select="SELECT rlesmun.ecodestadosmunicipios,cest.tNombre AS Estado, rlesmun.ecodestados,cmun.tNombre AS Municipio, rlesmun.ecodmunicipios FROM relestadosmunicipios rlesmun
                LEFT JOIN catmunicipios cmun ON cmun.ecodMunicipio = rlesmun.ecodmunicipios
                LEFT JOIN catestados cest ON cest.ecodEstado = rlesmun.ecodestados".
                " WHERE 1 = 1". 
                " ORDER BY rlesmun.ecodestadosmunicipios DESC";
                $sql = DB::select(DB::raw($select));
            return response()->json(($sql));
        }
    }

}
