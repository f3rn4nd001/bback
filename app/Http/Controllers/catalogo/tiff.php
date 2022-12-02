<?php

namespace App\Http\Controllers\catalogo;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class tiff extends Controller
{
    public function objeto_a_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value){$result[$key] = $this->objeto_a_array($value);}
            return $result;
        }
        return $data;
    }
    public function getRegistro(Request $request){ 
        if (is_array($request['datos']) || is_object($request['datos'])){
            $result = array();
            foreach ($request['datos'] as $key => $value){
                $result[$key] = $this->objeto_a_array($value);
            }
            $result;
            $jsonX = isset($result['filtro'])? $result['filtro']:[];
        }
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        $tipousuario        = (isset($result['tipousuario']) && $result['tipousuario'] != "" ? "'" . (trim($result['tipousuario'])) . "'" : "NULL");           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $tTiff           = (isset($jsonX['tTiff']) && $jsonX['tTiff'] != "" ? "" . (trim($jsonX['tTiff'])) . "" : "" );           
            $tNombre           = (isset($jsonX['tNombre']) && $jsonX['tNombre'] != "" ? "" . (trim($jsonX['tNombre'])) . "" : "" );           
            $tEstado           = (isset($jsonX['tEstado']) && $jsonX['tEstado'] != "" ? "" . (trim($jsonX['tEstado'])) . "" : "" );           
            $tCiudad           = (isset($jsonX['tCiudad']) && $jsonX['tCiudad'] != "" ? "" . (trim($jsonX['tCiudad'])) . "" : "" );           
            if($tipousuario == "'Administrador'"){
                $select="SELECT
                ctf.ecodTif,
                ctf.tNombre,
                ctf.tNombreCorto,
                ctf.ecodCiudad,
                cmu.tNombre AS Ciudad,
                ctf.ecodEstado,
                ces.tNombre AS Estado,
                ctf.tDireccion,
                ctf.tcp,
                ctf.tTif,
                ctf.tRFC
            FROM
                cattiff ctf
            LEFT JOIN catmunicipios cmu ON cmu.ecodMunicipio = ctf.ecodCiudad
            LEFT JOIN catestados ces ON ces.ecodEstado = ctf.ecodEstado".
                    " WHERE 1 = 1". 
                    (isset($tTiff)    ? " AND  ctf.tTif LIKE '%". $tTiff."%' " : "").              
                    (isset($tNombre)    ? " AND  ctf.tNombre LIKE '%". $tNombre."%' " : "").              
                    (isset($tEstado)    ? " AND  ces.tNombre LIKE '%". $tEstado."%' " : "").              
                    (isset($tCiudad)    ? " AND  cmu.tNombre LIKE '%". $tCiudad."%' " : "").              
                    " ORDER BY ctf.ecodTif DESC";
            }
            if($tipousuario == "'Agente'"){
                $select="SELECT
                ctf.ecodTif,
                ctf.tNombre,
                ctf.tNombreCorto,
                ctf.ecodCiudad,
                cmu.tNombre AS Ciudad,
                ctf.ecodEstado,
                ces.tNombre AS Estado,
                ctf.tDireccion,
                ctf.tcp,
                ctf.tTif,
                ctf.tRFC
            FROM
                cattiff ctf
            LEFT JOIN catmunicipios cmu ON cmu.ecodMunicipio = ctf.ecodCiudad
            LEFT JOIN catestados ces ON ces.ecodEstado = ctf.ecodEstado".
                " WHERE 1 = 1".
                   (isset($tTiff)    ? " AND  ctf.tTif LIKE '%". $tTiff."%' " : "").              
                    (isset($tNombre)    ? " AND  ctf.tNombre LIKE '%". $tNombre."%' " : "").              
                    (isset($tEstado)    ? " AND  ces.tNombre LIKE '%". $tEstado."%' " : "").              
                    (isset($tCiudad)    ? " AND  cmu.tNombre LIKE '%". $tCiudad."%' " : "").              
        
                " ORDER BY ctf.ecodTif DESC";
            }
            if($tipousuario == "'Operador'"){
                $select="SELECT
                ctf.ecodTif,
                ctf.tNombre,
                ctf.tNombreCorto,
                ctf.ecodCiudad,
                cmu.tNombre AS Ciudad,
                ctf.ecodEstado,
                ces.tNombre AS Estado,
                ctf.tDireccion,
                ctf.tcp,
                ctf.tTif,
                ctf.tRFC
            FROM
                cattiff ctf
            LEFT JOIN catmunicipios cmu ON cmu.ecodMunicipio = ctf.ecodCiudad
            LEFT JOIN catestados ces ON ces.ecodEstado = ctf.ecodEstado".
                " WHERE 1 = 1". 
                " ORDER BY ctf.ecodTif DESC";
            }
                $sql = DB::select(DB::raw($select));
            return response()->json(($sql));
        }
    }
    public function postRegistro(Request $request){
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $exito = 1;
          
            if (is_array($request['datos']) || is_object($request['datos'])){
                $result = array();
                foreach ($request['datos'] as $key => $value){
                    $result[$key] = $this->objeto_a_array($value);
                }
                $result;
            }
            $ecodTiffv            = (isset($result['ecodTiffv'])&& $result['ecodTiffv']>0  ? (int)$result['ecodTiffv']  : "NULL");      
            $ecodEstatus        = (isset($result['ecodEstatus'])&& $result['ecodEstatus']>0  ? (int)$result['ecodEstatus']  : "NULL");      
            $ecodEntidades      = (isset($result['ecodEntidades']['ecodestados'])&& $result['ecodEntidades']['ecodestados']>0  ? (int)$result['ecodEntidades']['ecodestados']  : "NULL");       
            $tTiff              = (isset($result['tTiff']) && $result['tTiff'] != "" ? "'" . (trim($result['tTiff'])) . "'" : "NULL");
            $tNombre            = (isset($result['tNombre']) && $result['tNombre'] != "" ? "'" . (trim($result['tNombre'])) . "'" : "NULL");
            $tNpmbreCorto       = (isset($result['tNpmbreCorto']) && $result['tNpmbreCorto'] != "" ? "'" . (trim($result['tNpmbreCorto'])) . "'" : "NULL");
            $tRFC               = (isset($result['tRFC']) && $result['tRFC'] != "" ? "'" . (trim($result['tRFC'])) . "'" : "NULL");
            $ecodMunicipios     = (isset($result['ecodMunicipios']['ecodmunicipios'])&& $result['ecodMunicipios']['ecodmunicipios']>0  ? (int)$result['ecodMunicipios']['ecodmunicipios']  : "NULL");       
            $loginEcodUsuarios  = (isset($result['loginEcodUsuarios'])&&$result['loginEcodUsuarios']!="" ? "'".(trim($result['loginEcodUsuarios']))."'":   "NULL");         
            $tDireccion         = (isset($result['tDireccion']) && $result['tDireccion'] != "" ? "'" . (trim($result['tDireccion'])) . "'" : "NULL");
            $tCP                = (isset($result['tCP']) && $result['tCP'] != "" ? "'" . (trim($result['tCP'])) . "'" : "NULL");
            try {
                DB::beginTransaction();
                $insert=" CALL `stpInsertarCatTiff`(".$tTiff.",".$tNombre.",".$tNpmbreCorto.",".$tRFC.",".$ecodEntidades.",".$ecodMunicipios.",".$loginEcodUsuarios.",".$ecodTiffv.",".$ecodEstatus.",".$tCP.",".$tDireccion.")";
                $response = DB::select($insert);
                $codigoreltiff  = (isset($response[0]->Codigo)&& $response[0]->Codigo>0  ? (int)$response[0]->Codigo : "NULL");
                if (!$codigoreltiff) {
                    $exito = 0;
                }
                if ($exito == 0) {
                    DB::rollback();
                } else {
                    DB::commit();
                }
            } catch (Exception $e) {
                DB::rollback();
                $exito = $e->getMessage();
            }
            return response()->json(['codigo' => $codigoreltiff, 'exito' => $exito]);
        }
    }
    public function getDetalles(Request $request){
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $json = json_decode( $request['datos'] ,true);
            $ecodTiffv          = (isset($json)&& $json>0  ? (int)$json  : "NULL");      
          
            $select="SELECT
            ctf.ecodTif,
            ctf.tNombre,
            ctf.tNombreCorto,
            ctf.ecodCiudad,
            cmu.tNombre AS Ciudad,
            ctf.ecodEstado,
            ces.tNombre AS Estado,
            ctf.tDireccion,
            ctf.tcp,
            ctf.tTif,
            ctf.tRFC,
            ctf.ecodEstatus,
			cts.tNombre AS estatus
        FROM
            cattiff ctf
        LEFT JOIN catmunicipios cmu ON cmu.ecodMunicipio = ctf.ecodCiudad
        LEFT JOIN catestados ces ON ces.ecodEstado = ctf.ecodEstado
        LEFT JOIN catestatus cts ON cts.EcodEstatus= ctf.ecodEstatus
        WHERE ctf.ecodTif = ".(int)$ecodTiffv;
        $sql = DB::select(DB::raw($select));
          }
          return response()->json(['sql'=>$sql]);
    
    }
}
