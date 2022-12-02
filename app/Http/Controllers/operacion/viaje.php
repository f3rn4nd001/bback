<?php

namespace App\Http\Controllers\operacion;
use DB;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class viaje extends Controller
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
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $fhFechaSalida      = $this->cambiarFecha(isset($jsonX['fhSalidaInicio']) ? $jsonX['fhSalidaInicio'] : null, isset( $jsonX['fhSalidaTermino']) ?  $jsonX['fhSalidaTermino'] : null);
            $fhSalidaInicio     = $fhFechaSalida['inicio'];
            $fhSalidaTermino    = $fhFechaSalida['termino'];
            $fhLlegada          = $this->cambiarFecha(isset($jsonX['fhLlegadaInicio']) ? $jsonX['fhLlegadaInicio'] : null, isset( $jsonX['fhLlegadaTermino']) ?  $jsonX['fhLlegadaTermino'] : null);
            $fhLlegadaInicio    = $fhLlegada['inicio'];
            $fhLlegadaTermino   = $fhLlegada['termino'];
            $eCodViaje          = (isset($jsonX['ecodViaje']) && $jsonX['ecodViaje'] != "" ? "" . (trim($jsonX['ecodViaje'])) . "" : "" );           
            $tDestino           = (isset($jsonX['tDestino']) && $jsonX['tDestino'] != "" ? "" . (trim($jsonX['tDestino'])) . "" : "" );           
            $Estatus            = (isset($jsonX['Estatus']) && $jsonX['Estatus'] != "" ? "" . (trim($jsonX['Estatus'])) . "" : "" );           
            $tOrigen            = (isset($jsonX['tOrigen']) && $jsonX['tOrigen'] != "" ? "" . (trim($jsonX['tOrigen'])) . "" : "" );           
            $treferencia        = (isset($jsonX['treferencia']) && $jsonX['treferencia'] != "" ? "" . (trim($jsonX['treferencia'])) . "" : "" );           
            $tpedido            = (isset($jsonX['tpedido']) && $jsonX['tpedido'] != "" ? "" . (trim($jsonX['tpedido'])) . "" : "" );           
            $cliente            = (isset($jsonX['cliente']) && $jsonX['cliente'] != "" ? "" . (trim($jsonX['cliente'])) . "" : "" );              
            $operador           = (isset($jsonX['operador']) && $jsonX['operador'] != "" ? "" . (trim($jsonX['operador'])) . "" : "" );              
            $tipousuario        = (isset($result['tipousuario']) && $result['tipousuario'] != "" ? "'" . (trim($result['tipousuario'])) . "'" : "NULL");           
            $loginEcodUsuarios  = (isset($result['loginEcodUsuarios']) && $result['loginEcodUsuarios'] != "" ? "'" . (trim($result['loginEcodUsuarios'])) . "'" : "NULL");           
            if($tipousuario == "'Administrador'"){
                $select="SELECT ctff.tTif,  ctfdes.tTif AS tiffdestino, bc.Link, bc.ecodProvedor, bc.fhSalida,bc.ecodViaje,bc.treferencia, bc.tpedido,bc.tDestino,bc.tOrigen,concat_ws('',cu.tNombre,' ', cu.tApellido) AS operador,concat_ws(' ',cuc.tNombre,' ', cuc.tApellido) AS cliente,ce.tNombre AS Estatus, bc.fhLlegada FROM bitviajes bc
                LEFT JOIN catestatus ce ON ce.EcodEstatus = bc.EcodEstatus
                LEFT JOIN catusuarios cu ON cu.ecodUsuarios = bc.ecodOperados
                LEFT JOIN catusuarios cuc ON cuc.ecodUsuarios=bc.ecodCliente
                LEFT JOIN cattiff ctff ON ctff.ecodTif = bc.tOrigen
                LEFT JOIN cattiff ctfdes On ctfdes.ecodTif= bc.tDestino".
                " WHERE 1 = 1". 
                (isset($eCodViaje)&&$eCodViaje  ? " AND bc.ecodViaje = ".(int)$eCodViaje : "").
                (isset($treferencia)            ? " AND  bc.treferencia LIKE '%". $treferencia."%' " : "").
                (isset($tpedido)                ? " AND  bc.tpedido LIKE '%". $tpedido."%' " : "").
                (isset($cliente)                ? " AND concat_ws('',cuc.tNombre,' ', cuc.tApellido) LIKE '%".$cliente."%'" : '').
                (isset($operador)               ? " AND concat_ws('',cu.tNombre,' ', cu.tApellido) LIKE '%".$operador."%'" : '').
                (isset($tOrigen)                ? " AND  bc.tOrigen LIKE '%". $tOrigen."%' " : "").
                (isset($tDestino)               ? " AND  bc.tDestino LIKE '%". $tDestino."%' " : "").
                (isset($Estatus)                ? " AND  ce.tNombre LIKE '%". $Estatus."%' " : "").
                (isset($fhSalidaInicio)         ? " AND bc.fhSalida BETWEEN '" . $fhSalidaInicio . "' AND '" . ($fhSalidaTermino ? $fhSalidaTermino : $fhSalidaInicio) . "' "    :   '') . 
                (isset($fhLlegadaInicio)        ? " AND bc.fhLlegada BETWEEN '" . $fhLlegadaInicio . "' AND '" . ($fhLlegadaTermino ? $fhLlegadaTermino : $fhLlegadaInicio) . "' "    :   '') . 
                " ORDER BY bc.ecodViaje DESC";
            }
            if($tipousuario == "'Agente'"){
                $select="SELECT ctff.tTif,  ctfdes.tTif AS tiffdestino, bc.Link, bc.ecodProvedor, bc.ecodViaje,bc.treferencia, bc.tpedido,bc.tDestino,bc.tOrigen,concat_ws('',cu.tNombre,' ', cu.tApellido) AS operador,concat_ws(' ',cuc.tNombre,' ', cuc.tApellido) AS cliente,ce.tNombre AS Estatus, bc.fhSalida, bc.fhLlegada FROM bitviajes bc
                LEFT JOIN catestatus ce ON ce.EcodEstatus = bc.EcodEstatus
                LEFT JOIN catusuarios cu ON cu.ecodUsuarios = bc.ecodOperados
                LEFT JOIN catusuarios cuc ON cuc.ecodUsuarios=bc.ecodCliente
                LEFT JOIN cattiff ctff ON ctff.ecodTif = bc.tOrigen
                LEFT JOIN cattiff ctfdes On ctfdes.ecodTif= bc.tDestino".
                " WHERE 1 = 1". 
                (isset($eCodViaje)&&$eCodViaje  ? " AND bc.ecodViaje = ".(int)$eCodViaje : "").
                (isset($treferencia)            ? " AND  bc.treferencia LIKE '%". $treferencia."%' " : "").
                (isset($tpedido)                ? " AND  bc.tpedido LIKE '%". $tpedido."%' " : "").
                (isset($cliente)                ? " AND concat_ws('',cuc.tNombre,' ', cuc.tApellido) LIKE '%".$cliente."%'" : '').
                (isset($operador)               ? " AND concat_ws('',cu.tNombre,' ', cu.tApellido) LIKE '%".$operador."%'" : '').
                (isset($tOrigen)                ? " AND  bc.tOrigen LIKE '%". $tOrigen."%' " : "").
                (isset($tDestino)               ? " AND  bc.tDestino LIKE '%". $tDestino."%' " : "").
                (isset($fhSalidaInicio)         ? " AND bc.fhSalida BETWEEN '" . $fhSalidaInicio . "' AND '" . ($fhSalidaTermino ? $fhSalidaTermino : $fhSalidaInicio) . "' "    :   '') .
                (isset($fhLlegadaInicio)         ? " AND bc.fhLlegada BETWEEN '" . $fhLlegadaInicio . "' AND '" . ($fhLlegadaTermino ? $fhLlegadaTermino : $fhLlegadaInicio) . "' "    :   '') . 
                " ORDER BY bc.ecodViaje DESC";
            }
            if($tipousuario == "'Operador'"){
                $select=" SELECT ctff.tTif,  ctfdes.tTif AS tiffdestino, bc.Link, bc.ecodProvedor,bc.ecodViaje,bc.treferencia, bc.tpedido,bc.tDestino,bc.tOrigen,concat_ws('',cu.tNombre,' ', cu.tApellido) AS operador,concat_ws(' ',cuc.tNombre,' ', cuc.tApellido) AS cliente,ce.tNombre AS Estatus, bc.fhSalida, bc.fhLlegada FROM bitviajes bc
                LEFT JOIN catestatus ce ON ce.EcodEstatus = bc.EcodEstatus
                LEFT JOIN catusuarios cu ON cu.ecodUsuarios = bc.ecodOperados
                LEFT JOIN catusuarios cuc ON cuc.ecodUsuarios=bc.ecodCliente
                LEFT JOIN cattiff ctff ON ctff.ecodTif = bc.tOrigen
                LEFT JOIN cattiff ctfdes On ctfdes.ecodTif= bc.tDestino
                WHERE ecodOperados = ".$loginEcodUsuarios.
                (isset($eCodViaje)&&$eCodViaje  ? " AND bc.ecodViaje = ".(int)$eCodViaje : "").
                (isset($treferencia)            ? " AND  bc.treferencia LIKE '%". $treferencia."%' " : "").
                (isset($tpedido)                ? " AND  bc.tpedido LIKE '%". $tpedido."%' " : "").
                (isset($cliente)                ? " AND concat_ws('',cuc.tNombre,' ', cuc.tApellido) LIKE '%".$cliente."%'" : '').
                (isset($operador)               ? " AND concat_ws('',cu.tNombre,' ', cu.tApellido) LIKE '%".$operador."%'" : '').
                (isset($tOrigen)                ? " AND  bc.tOrigen LIKE '%". $tOrigen."%' " : "").
                (isset($tDestino)               ? " AND  bc.tDestino LIKE '%". $tDestino."%' " : "").
                (isset($fhSalidaInicio)         ? " AND bc.fhSalida BETWEEN '" . $fhSalidaInicio . "' AND '" . ($fhSalidaTermino ? $fhSalidaTermino : $fhSalidaInicio) . "' "    :   '') .
                (isset($fhLlegadaInicio)         ? " AND bc.fhLlegada BETWEEN '" . $fhLlegadaInicio . "' AND '" . ($fhLlegadaTermino ? $fhLlegadaTermino : $fhLlegadaInicio) . "' "    :   '') . 
                " ORDER BY bc.ecodViaje DESC";
            }
            if($tipousuario == "'Cliente'"){
                $select=" SELECT ctff.tTif,  ctfdes.tTif AS tiffdestino, bc.Link, bc.ecodProvedor, bc.ecodViaje,bc.treferencia, bc.tpedido,bc.tDestino,bc.tOrigen,concat_ws('',cu.tNombre,' ', cu.tApellido) AS operador,concat_ws(' ',cuc.tNombre,' ', cuc.tApellido) AS cliente,ce.tNombre AS Estatus, DATE_FORMAT(bc.fhSalida,'%y-%m-%d')as fhSalida, DATE_FORMAT(bc.fhLlegada,'%y-%m-%d') as fhLlegada FROM bitviajes bc
                LEFT JOIN catestatus ce ON ce.EcodEstatus = bc.EcodEstatus
                LEFT JOIN catusuarios cu ON cu.ecodUsuarios = bc.ecodOperados
                LEFT JOIN catusuarios cuc ON cuc.ecodUsuarios=bc.ecodCliente
                LEFT JOIN cattiff ctff ON ctff.ecodTif = bc.tOrigen
                LEFT JOIN cattiff ctfdes On ctfdes.ecodTif= bc.tDestino
                WHERE ecodCliente = ".$loginEcodUsuarios.
                (isset($eCodViaje)&&$eCodViaje  ? " AND bc.ecodViaje = ".(int)$eCodViaje : "").
                (isset($treferencia)            ? " AND  bc.treferencia LIKE '%". $treferencia."%' " : "").
                (isset($tpedido)                ? " AND  bc.tpedido LIKE '%". $tpedido."%' " : "").
                (isset($cliente)                ? " AND concat_ws('',cuc.tNombre,' ', cuc.tApellido) LIKE '%".$cliente."%'" : '').
                (isset($operador)               ? " AND concat_ws('',cu.tNombre,' ', cu.tApellido) LIKE '%".$operador."%'" : '').
                (isset($tOrigen)                ? " AND  bc.tOrigen LIKE '%". $tOrigen."%' " : "").
                (isset($tDestino)               ? " AND  bc.tDestino LIKE '%". $tDestino."%' " : "").
                (isset($fhSalidaInicio)         ? " AND bc.fhSalida BETWEEN '" . $fhSalidaInicio . "' AND '" . ($fhSalidaTermino ? $fhSalidaTermino : $fhSalidaInicio) . "' "    :   '') .
                (isset($fhLlegadaInicio)         ? " AND bc.fhLlegada BETWEEN '" . $fhLlegadaInicio . "' AND '" . ($fhLlegadaTermino ? $fhLlegadaTermino : $fhLlegadaInicio) . "' "    :   '') . 
                " ORDER BY bc.ecodViaje DESC";
            }
            $sql = DB::select(DB::raw($select));
            return response()->json(($sql));
        }
    }

    public function cambiarFecha($inicio, $termino)
	{
      	if (isset($inicio) || isset($termino)) {
			if (isset($inicio)) {
				$fecha = explode('T', $inicio);
				$inicio = $fecha[0] ;
			} else {
				$fecha = explode('T', $termino);
				$inicio = $fecha[0] ;
			}
			if (isset($termino)) {
				$fecha = explode('T', $termino);
				$termino = $fecha[0] ;
			} else {
				$fecha = explode('T', $inicio);
				$termino = $fecha[0] . 'T23:59:59';
			}
		}
		return array('inicio' => $inicio, 'termino' => $termino);
	}

    public function getDatosMonitor(Request $request){
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $json = json_decode( $request['datos'] ,true);
            $selectViajes ="SELECT bc.ecodProvedor,bc.tmonitoreo,bc.ecodCliente,bc.ecodViaje,bc.treferencia, bc.tpedido,bc.tDestino,bc.tOrigen,concat_ws('',cu.tNombre,' ', cu.tApellido) AS operador,concat_ws(' ',cuc.tNombre,' ', cuc.tApellido) AS cliente,ce.tNombre AS Estatus, bc.fhSalida, bc.fhLlegada FROM bitviajes bc
            LEFT JOIN catestatus ce ON ce.EcodEstatus = bc.EcodEstatus
            LEFT JOIN catusuarios cu ON cu.ecodUsuarios = bc.ecodOperados
            LEFT JOIN catusuarios cuc ON cuc.ecodUsuarios=bc.ecodCliente
            WHERE bc.ecodCliente =".(int)$json." AND bc.EcodEstatus <> 4";
            $sql = DB::select(DB::raw($selectViajes));
            foreach ($sql as $key => $v){
                $resViajes[]=array(
                    'ecodCliente' => $v->ecodCliente,
                    'ecodViaje' => $v->ecodViaje,
                    'treferencia' => $v->treferencia,
                    'tpedido' => $v->tpedido,
                    'tDestino' => $v->tDestino,
                    'tOrigen' => $v->tOrigen,
                    'operador' => $v->operador,
                    'cliente' => $v->cliente,
                    'Estatus' => $v->Estatus,
                    'fhSalida' => $v->fhSalida,
                    'fhLlegada' => $v->fhLlegada,
                    'tmonitoreo'=> $v->tmonitoreo,
                    'ecodProvedor' => $v->ecodProvedor
                );
            }
            return response()->json(['Viajes'=>(isset($resViajes) ? $resViajes : "")]);
        }
    }
    
    public function getDetalles(Request $request){
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            $json = json_decode( $request['datos'] ,true);
            $select="SELECT 	cesde.tNombre AS estadodes, mcd.tNombre as municipiodes, ctfdes.tNombre as nombreempresades,ctf.ecodCiudad,ctf.ecodEstado,ctf.tNombre AS nombreEmpresa,ctfdes.tTif AS tiffdestino, ces.tNombre AS Estado, ctf.tNombreCorto AS nombrecortoempresa, ctf.tTif, cm.tNombre AS Municipio, bc.Link, bc.tTipoViaje,bc.tTipoGasto,bc.ecodProvedor,bc.EcodEstatus,bc.ecodOperados,bc.tmonitoreo,bc.ecodCliente,bc.ecodViaje,bc.treferencia, bc.tpedido,bc.tDestino,bc.tOrigen,concat_ws('',cu.tNombre,' ', cu.tApellido) AS operador,concat_ws(' ',cuc.tNombre,' ', cuc.tApellido) AS cliente,ce.tNombre AS Estatus, DATE_FORMAT(bc.fhSalida,'%y-%m-%d')as fhSalida, DATE_FORMAT(bc.fhLlegada,'%y-%m-%d') as fhLlegada FROM bitviajes bc
            LEFT JOIN catestatus ce ON ce.EcodEstatus = bc.EcodEstatus
            LEFT JOIN catusuarios cu ON cu.ecodUsuarios = bc.ecodOperados
            LEFT JOIN catusuarios cuc ON cuc.ecodUsuarios=bc.ecodCliente
            LEFT JOIN cattiff ctf ON ctf.ecodTif = bc.tOrigen
            LEFT JOIN catmunicipios cm ON cm.ecodMunicipio=ctf.ecodCiudad
            LEFT JOIN catestados ces ON ces.ecodEstado=ctf.ecodEstado
            LEFT JOIN cattiff ctfdes On ctfdes.ecodTif= bc.tDestino
            LEFT JOIN catmunicipios mcd ON mcd.ecodMunicipio = ctfdes.ecodCiudad
            LEFT JOIN catestados cesde ON cesde.ecodEstado = ctfdes.ecodEstado
                WHERE bc.ecodViaje = ".(int)$json;
            $sql = DB::select(DB::raw($select));
           
            $mensjeri="SELECT bm.* FROM relviajemensaje rvm
                LEFT JOIN bitviajes bv ON bv.ecodViaje = rvm.ecodViaje
                LEFT JOIN bitmensaje bm ON bm.ecodMensaje = rvm.ecodMensaje
                WHERE bv.ecodViaje = ".(int)$json." ORDER BY bm.ecodMensaje DESC";
            $mensjerisql = DB::select(DB::raw($mensjeri));
           
            foreach ($mensjerisql as $key => $v){
                $resultados[]=array(
                    'tMensaje' => $v->tMensaje,
                    'fechaHora' => $v->fhSalida,
                );
            }
            $celures="SELECT bc.*
                FROM
                    bitviajes bv
                LEFT JOIN relusuariocelular ruc ON ruc.ecodUsuario = bv.ecodCliente 
                LEFT JOIN bitcelular bc ON bc.ecodCelular = ruc.ecodCelular
                WHERE bv.ecodViaje = ".(int)$json;
            $celuresdb = DB::select(DB::raw($celures));
            foreach ($celuresdb as $key => $v){
                $celuresr[]=array(
                    'tcelular' => $v->tcelular,
                );
            }
            $selectincidencias="SELECT ctf.tTif AS TifOrigen, ctfdes.tTif AS Tifdestino, bin.Link,bin.tTipoViaje, bin.tTipoGasto, cest.tNombre AS estatus,bin.treferencia, bin.tpedido, bin.tDestino, bin.tOrigen, bin.EcodEstatus, bin.fhSalida, bin.fhLlegada, bin.tmonitoreo, bin.tIncidentes, bin.fhEdicion, bin.ecodProvedor, concat_ws('', cuop.tNombre, ' ', cuop.tApellido ) AS operador, concat_ws(  '',  cucli.tNombre, ' ', cucli.tApellido ) AS cliente,  concat_ws(  '',  cued.tNombre,  ' ',  cued.tApellido ) AS useredit
                FROM
                    bitinciadencias bin
                INNER JOIN catusuarios cuop ON cuop.ecodUsuarios = bin.ecodOperados
                INNER JOIN catusuarios cucli ON cucli.ecodUsuarios = bin.ecodCliente
                INNER JOIN catusuarios cued ON cued.ecodUsuarios = bin.ecodEdicion
                INNER JOIN catestatus cest ON cest.EcodEstatus=bin.EcodEstatus
                INNER JOIN cattiff ctf ON ctf.ecodTif = bin.tOrigen
                INNER JOIN cattiff ctfdes ON ctfdes.ecodTif = bin.tDestino
                WHERE bin.ecodViaje = ".(int)$json." ORDER BY bin.fhEdicion DESC";
            $resultincidencias = DB::select(DB::raw($selectincidencias));
            foreach ($resultincidencias as $key => $v){
                $incidenciasArr[]=array(
                    'treferencia'   =>  $v->treferencia,
                    'tpedido'       =>  $v->tpedido,
                    'tDestino'      =>  $v->tDestino,
                    'tOrigen'       =>  $v->tOrigen,
                    'estatus'       =>  $v->estatus,
                    'fhSalida'      =>  $v->fhSalida,
                    'fhLlegada'     =>  $v->fhLlegada,
                    'tmonitoreo'    =>  $v->tmonitoreo,
                    'fhEdicion'     =>  $v->fhEdicion,
                    'ecodProvedor'  =>  $v->ecodProvedor,
                    'operador'      =>  $v->operador,
                    'cliente'       =>  $v->cliente,
                    'useredit'      =>  $v->useredit,
                    'tIncidentes'   =>  $v->tIncidentes,
                    'tTipoViaje'    =>  $v->tTipoViaje,
                    'tTipoGasto'    =>  $v->tTipoGasto,
                    'Link'          =>  $v->Link,
                    'TifOrigen'     =>  $v->TifOrigen,
                    'Tifdestino'    =>  $v->Tifdestino,
                );
            }
            return response()->json(['sql'=>$sql,'resultados'=>(isset($resultados) ? $resultados : ""),'celuraler'=>(isset($celuresr) ? $celuresr : ""),'incidenciasArr'=>(isset($incidenciasArr) ? $incidenciasArr : "")]);
        }
    }
    
   public function postMonitoreoMasivo(Request $request)
    {
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            if (is_array($request['datos']) || is_object($request['datos'])){
                $result = array();
                foreach ($request['datos'] as $key => $value){
                    $result[$key] = $this->objeto_a_array($value);
                }
                $result;
            }
            $sid    = "AC734689817fa8c10fa5a3d1a19fde276b"; 
            $token  = "88381592ece3d503a80060caf6dbe97c"; 
            $exito = 1;
            foreach ($result as $key => $value) {
                $ecodViaje      = (isset($value['ecodViaje'])&& $value['ecodViaje']>0  ? (int)$value['ecodViaje']  : "NULL"); 
                $ecodCliente    = (isset($value['ecodCliente'])&& $value['ecodCliente']>0  ? (int)$value['ecodCliente']  : "NULL"); 
                $tComentario    = (isset($value['tComentario']) && $value['tComentario'] != "" ? "'" . (trim($value['tComentario'])) . "'" : "NULL");           
                $treferencia    = (isset($value['treferencia']) && $value['treferencia'] != "" ? "'" . (trim($value['treferencia'])) . "'" : "NULL");
                $tpedido        = (isset($value['tpedido']) && $value['tpedido'] != "" ? "'" . (trim($value['tpedido'])) . "'" : "NULL");
                $tDestino       = (isset($value['tDestino']) && $value['tDestino'] != "" ? "'" . (trim($value['tDestino'])) . "'" : "NULL");
                $operador       = (isset($value['operador']) && $value['operador'] != "" ? "'" . (trim($value['operador'])) . "'" : "NULL");
                $tpedido        = (isset($value['tpedido']) && $value['tpedido'] != "" ? "'" . (trim($value['tpedido'])) . "'" : "NULL");
                $tOrigen        = (isset($value['tOrigen']) && $value['tOrigen'] != "" ? "'" . (trim($value['tOrigen'])) . "'" : "NULL");
                $fhSalida       = (isset($value['fhSalida']) && $value['fhSalida'] != "" ? "'" . (trim($value['fhSalida'])) . "'" : "NULL");
                $fhLlegada      = (isset($value['fhLlegada']) && $value['fhLlegada'] != "" ? "'" . (trim($value['fhLlegada'])) . "'" : "NULL");
                $tmonitoreo     = (isset($value['tmonitoreo']) && $value['tmonitoreo'] != "" ? "'" . (trim($value['tmonitoreo'])) . "'" : "NULL");
                if ($tmonitoreo == 'NULL') {
                    $tmonitoreo = 0;
                }
            if ($tmonitoreo == "'1'") {
            
                    $arrmensaje[]=array(
                "*$tpedido* *$treferencia* *$tDestino* OPERADOR:$operador Comentario: *$tComentario*  %1\$s"
                    );
                }
                if ($tmonitoreo == "'1'") {
                    $insert =" CALL `stpInsertarBitMenjes`(".$tComentario.")";
                    $response = DB::select($insert);
                    $codigoviMen=$response[0];
                    $codigope  = (isset($response[0]->Codigo)&& $response[0]->Codigo>0  ? (int)$response[0]->Codigo : "NULL");       
                    $insertrelviajeMensaje=" CALL `stpInsertarRelViajeMensaje`(".$codigope.",".$ecodViaje.")";
                    $response2 = DB::select($insertrelviajeMensaje);
                }
                if ($tmonitoreo == "'1'") {
                    $tmonitoreo = 1;
                }
                if ($tmonitoreo == "'0'") {
                    $tmonitoreo = 0;
                }
                $insert=" CALL `stpmodifBitViajesmonitos`(".$tmonitoreo.",".$ecodViaje.")";
                $response = DB::select($insert);   
            }
            $celures="SELECT bc.ecodCelular,bc.tcelular
                FROM
                    relusuariocelular ruc
                    LEFT JOIN bitcelular bc ON bc.ecodCelular = ruc.ecodCelular
                WHERE ruc.ecodUsuario = ".(int)$ecodCliente;
            $celuresdb = DB::select(DB::raw($celures));
            $ldate = date('d-m-Y');
            foreach ($celuresdb as $key => $v){
                $celuresr[]=array(
                    'tcelular' => $v->tcelular,
                );
            }
            $number="

    ";
        $mens = json_encode( $arrmensaje);
        $json2 = json_encode(explode('],[', $mens));
        $json = vsprintf($json2, array($number));
        foreach ($celuresr as $key => $value) {
            $tcelular       = (isset($value['tcelular'])&& $value['tcelular']>0  ? (int)$value['tcelular']  : "NULL");
        $twilio = new Client($sid, $token); 
        $message = $twilio->messages ->create("whatsapp:+521$tcelular",  
            array( 
                    "from" => "whatsapp:+14155238886",       
                    "body" => "🛣️🚛🌎 *MONITOREO INTERRA // BSL // DESCARGAS $ldate* 

$json"));
            }   
            return response()->json([ 'exito' => $exito]);
        }
    }


    public function postMonitoreoMensaje(Request $request ){
        $dadsad        = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            if (is_array($request['datos']) || is_object($request['datos'])){
                $result = array();
                foreach ($request['datos'] as $key => $value){
                    $result[$key] = $this->objeto_a_array($value);
                }
                $result;
                foreach ($result[0]['viajes'] as $key => $values){
                    $viajes[$key] = $this->objeto_a_array($values);
                }
                foreach ($result[0]['celuraler'] as $key => $valueceluraler){
                    $celuraler[$key] = $this->objeto_a_array($valueceluraler);
                }
                if (count($result[0]['newCel']) > 0) {
                    foreach ($result[0]['newCel'] as $key => $valuenewCel){
                        $newCel[$key] = $this->objeto_a_array($valuenewCel);
                    }
                }
            }
            $sid    = "AC734689817fa8c10fa5a3d1a19fde276b"; 
            $token  = "88381592ece3d503a80060caf6dbe97c"; 
            $exito  = 1;
            $datas2=$result[0];
            if (count($result[0]['newCel']) > 0) {
                foreach ($newCel as $key => $s){
                    $telefono       = (isset($s['telefono'])&& $s['telefono']>0  ? (int)$s['telefono']  : "NULL");
                    $ecodCliente       = (isset($viajes['ecodCliente'])&& $viajes['ecodCliente']>0  ? (int)$viajes['ecodCliente']  : "NULL");
                    $incercel =" CALL `stpInsertarBitCelular`(".$telefono.")";
                    $responseincercel = DB::select($incercel);
                    $codigoCel  = (isset($responseincercel[0]->Codigo)&& $responseincercel[0]->Codigo>0  ? (int)$responseincercel[0]->Codigo : "NULL");
                    $incerrelucercel =" CALL `stpInsertarRelusUarioCelular`(".$ecodCliente.",".$codigoCel.")";
                    $responsincerrelucercel = DB::select($incerrelucercel);
                    $treferencia    = (isset($viajes['treferencia']) && $viajes['treferencia'] != "" ? "'" . (trim($viajes['treferencia'])) . "'" : "NULL");
                    $tpedido        = (isset($viajes['tpedido']) && $viajes['tpedido'] != "" ? "'" . (trim($viajes['tpedido'])) . "'" : "NULL");
                    $ecodViaje      = (isset($viajes['ecodViaje'])&& $viajes['ecodViaje']>0  ? (int)$viajes['ecodViaje']  : "NULL");
                    $tDestino       = (isset($viajes['tDestino']) && $viajes['tDestino'] != "" ? "'" . (trim($viajes['tDestino'])) . "'" : "NULL");
                    $tOrigen        = (isset($viajes['tOrigen']) && $viajes['tOrigen'] != "" ? "'" . (trim($viajes['tOrigen'])) . "'" : "NULL");
                    $operador       = (isset($viajes['operador']) && $viajes['operador'] != "" ? "'" . (trim($viajes['operador'])) . "'" : "NULL");
                    $fhSalida       = (isset($viajes['fhSalida']) && $viajes['fhSalida'] != "" ? "'" . (trim($viajes['fhSalida'])) . "'" : "NULL");
                    $fhLlegada      = (isset($viajes['fhLlegada']) && $viajes['fhLlegada'] != "" ? "'" . (trim($viajes['fhLlegada'])) . "'" : "NULL");
                    $tMensaje       = (isset($datas2['mensajes']) && $datas2['mensajes'] != "" ? "'" . (trim($datas2['mensajes'])) . "'" : "NULL");
                    $insert =" CALL `stpInsertarBitMenjes`(".$tMensaje.")";
                    $response = DB::select($insert);
                    $codigoviMen=$response[0];
                    $codigope  = (isset($response[0]->Codigo)&& $response[0]->Codigo>0  ? (int)$response[0]->Codigo : "NULL");       
                    $insertrelviajeMensaje=" CALL `stpInsertarRelViajeMensaje`(".$codigope.",".$ecodViaje.")";
                    $response2 = DB::select($insertrelviajeMensaje);
                    $codigorelviajemensa  = (isset($response2[0]->Codigo)&& $response2[0]->Codigo>0  ? (int)$response2[0]->Codigo : "NULL");
                    $twilio = new Client($sid, $token); 
                    $ldate = date('d-m-Y');
                    $message = $twilio->messages ->create("whatsapp:+521$telefono", // to 
                                            array( 
                                                "from" => "whatsapp:+14155238886",       
                                                "body" => "🛣️🚛🌎 *MONITOREO INTERRA // BSL // DESCARGAS $ldate* 
                *$tpedido* *$treferencia* *// $tDestino* 
                OPERADOR: $operador
                *$tMensaje*"));    
                }
            }
            foreach ($celuraler as $key => $s){
                $tcelular       = (isset($s['tcelular'])&& $s['tcelular']>0  ? (int)$s['tcelular']  : "NULL");
                if($tcelular != 'NULL') {
                    $treferencia    = (isset($viajes['treferencia']) && $viajes['treferencia'] != "" ? "'" . (trim($viajes['treferencia'])) . "'" : "NULL");
                    $tpedido        = (isset($viajes['tpedido']) && $viajes['tpedido'] != "" ? "'" . (trim($viajes['tpedido'])) . "'" : "NULL");
                    $ecodViaje      = (isset($viajes['ecodViaje'])&& $viajes['ecodViaje']>0  ? (int)$viajes['ecodViaje']  : "NULL");
                    $tDestino       = (isset($viajes['tDestino']) && $viajes['tDestino'] != "" ? "'" . (trim($viajes['tDestino'])) . "'" : "NULL");
                    $tOrigen        = (isset($viajes['tOrigen']) && $viajes['tOrigen'] != "" ? "'" . (trim($viajes['tOrigen'])) . "'" : "NULL");
                    $operador       = (isset($viajes['operador']) && $viajes['operador'] != "" ? "'" . (trim($viajes['operador'])) . "'" : "NULL");
                    $fhSalida       = (isset($viajes['fhSalida']) && $viajes['fhSalida'] != "" ? "'" . (trim($viajes['fhSalida'])) . "'" : "NULL");
                    $fhLlegada      = (isset($viajes['fhLlegada']) && $viajes['fhLlegada'] != "" ? "'" . (trim($viajes['fhLlegada'])) . "'" : "NULL");
                    $tMensaje       = (isset($datas2['mensajes']) && $datas2['mensajes'] != "" ? "'" . (trim($datas2['mensajes'])) . "'" : "NULL");
                    $insert =" CALL `stpInsertarBitMenjes`(".$tMensaje.")";
                    $response = DB::select($insert);
                    $codigoviMen=$response[0];
                    $codigope  = (isset($response[0]->Codigo)&& $response[0]->Codigo>0  ? (int)$response[0]->Codigo : "NULL");       
                    $insertrelviajeMensaje=" CALL `stpInsertarRelViajeMensaje`(".$codigope.",".$ecodViaje.")";
                    $response2 = DB::select($insertrelviajeMensaje);
                    $codigorelviajemensa  = (isset($response2[0]->Codigo)&& $response2[0]->Codigo>0  ? (int)$response2[0]->Codigo : "NULL");
                    $ldate = date('d-m-Y');
                    $twilio = new Client($sid, $token); 
                    $message = $twilio->messages 
                                ->create("whatsapp:+521$tcelular", // to 
                                        array( 
                                            "from" => "whatsapp:+14155238886",       
                                            "body" => "🛣️🚛🌎 *MONITOREO INTERRA // BSL // DESCARGAS $ldate* 
            *$tpedido* *$treferencia* *// $tDestino* 
                OPERADOR: $operador
                *$tMensaje*") );  
                }
            }
            return response()->json([ 'exito' => $exito]);
        }
    }

    public function postRegistro(Request $request){
        $dadsad        = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="^SL#Hcj[d8kTjwOr4~p4aK7+8x0OlF9GLCvH2c-]~bxLMos") {
            if (is_array($request['datos']) || is_object($request['datos'])){
                $result = array();
                foreach ($request['datos'] as $key => $value){
                    $result[$key] = $this->objeto_a_array($value);
                }
                $result;
            }
            $exito = 1;
            $checkmonitor   = (isset($result['checkmonitor'])&& $result['checkmonitor']>0  ? (int)$result['checkmonitor']  : "NULL");
            if ($checkmonitor == 'NULL') {
                $checkmonitor = 0;
            }
            $ecodTifforigen    = (isset($result['ecodtifforigen']['ecodTif']) && $result['ecodtifforigen']['ecodTif'] != "" ? "" . (trim($result['ecodtifforigen']['ecodTif'])) . "" : "NULL");
            $ecodTiffnorigen    = (isset($result['ecodNombreOrigen']['ecodTif']) && $result['ecodNombreOrigen']['ecodTif'] != "" ? "" . (trim($result['ecodNombreOrigen']['ecodTif'])) . "" : "NULL");
            $ecodtiffDestino    = (isset($result['ecodtiffDestino']['ecodTif']) && $result['ecodtiffDestino']['ecodTif'] != "" ? "" . (trim($result['ecodtiffDestino']['ecodTif'])) . "" : "NULL");
            $ecodNombreDestino    = (isset($result['ecodNombreDestino']['ecodTif']) && $result['ecodNombreDestino']['ecodTif'] != "" ? "" . (trim($result['ecodNombreDestino']['ecodTif'])) . "" : "NULL");
            if ( $ecodTifforigen != "NULL") {
                $tOrigen = $ecodTifforigen;
            }
            if ( $ecodTiffnorigen != "NULL") {
                $tOrigen = $ecodTiffnorigen;
            }
            if ( $ecodtiffDestino != "NULL") {
                $tDestino = $ecodtiffDestino;
            }
            if ( $ecodNombreDestino != "NULL") {
                $tDestino = $ecodNombreDestino;
            }
            $ecodViaje      = (isset($result['ecodViaje'])&& $result['ecodViaje']>0  ? (int)$result['ecodViaje']  : "NULL");       
            $treferencia    = (isset($result['treferencia']) && $result['treferencia'] != "" ? "'" . (trim($result['treferencia'])) . "'" : "NULL");
            $tpedido        = (isset($result['tpedido']) && $result['tpedido'] != "" ? "'" . (trim($result['tpedido'])) . "'" : "NULL");
            $ecodProvedor   = (isset($result['ecodProvedor']) && $result['ecodProvedor'] != "" ? "'" . (trim($result['ecodProvedor'])) . "'" : "NULL");
            $fhSalida 	    = (isset($result['fhSalida']) && $result['fhSalida'] != "" 	? "'" . date("Y-m-d", strtotime($result['fhSalida'])) . "'"	: "NULL");
            $fhLlegada 		= (isset($result['fhLlegada']) && $result['fhLlegada'] != "" ? "'" . date("Y-m-d", strtotime($result['fhLlegada'])) . "'"	: "NULL");
            $ecodOperados   = (isset($result['ecodOperados']['ecodUsuarios']) && $result['ecodOperados']['ecodUsuarios'] != "" ? "'" . (trim($result['ecodOperados']['ecodUsuarios'])) . "'" : "NULL");
            $ecodCliente    = (isset($result['ecodClientes']['ecodUsuarios']) && $result['ecodClientes']['ecodUsuarios'] != "" ? "'" . (trim($result['ecodClientes']['ecodUsuarios'])) . "'" : "NULL");
            $tTipoViaje     = (isset($result['tTipoViaje']) && $result['tTipoViaje'] != "" ? "'" . (trim($result['tTipoViaje'])) . "'" : "NULL");
            $tTipoGasto     = (isset($result['tTipoGasto']) && $result['tTipoGasto'] != "" ? "'" . (trim($result['tTipoGasto'])) . "'" : "NULL");
            $Link           = (isset($result['Link']) && $result['Link'] != "" ? "'" . (trim($result['Link'])) . "'" : "NULL");
            $EcodEstatus    = (isset($result['ecodEstatus'])&& $result['ecodEstatus']>0  ? (int)$result['ecodEstatus']  : "NULL");       
            $loginEcodUsuarios  = (isset($result['loginEcodUsuarios']) && $result['loginEcodUsuarios'] != "" ? "'" . (trim($result['loginEcodUsuarios'])) . "'" : "NULL");
            if (count($result['datosViajes']) > 0) { 
                $relecodViaje       = (isset($result['datosViajes']['ecodViaje'])&& $result['datosViajes']['ecodViaje']>0  ? (int)$result['datosViajes']['ecodViaje']  : "NULL");       
                $reltreferencia     = (isset($result['datosViajes']['treferencia']) && $result['datosViajes']['treferencia'] != "" ? "'" . (trim($result['datosViajes']['treferencia'])) . "'" : "NULL");
                $reltpedido         = (isset($result['datosViajes']['tpedido']) && $result['datosViajes']['tpedido'] != "" ? "'" . (trim($result['datosViajes']['tpedido'])) . "'" : "NULL");
                $relecodProvedor    = (isset($result['datosViajes']['ecodProvedor']) && $result['datosViajes']['ecodProvedor'] != "" ? "'" . (trim($result['datosViajes']['ecodProvedor'])) . "'" : "NULL");
                $reltOrigen         = (isset($result['datosViajes']['tOrigen']) && $result['datosViajes']['tOrigen'] != "" ? "" . (trim($result['datosViajes']['tOrigen'])) . "" : "NULL");
                $reltDestino        = (isset($result['datosViajes']['tDestino']) && $result['datosViajes']['tDestino'] != "" ? "'" . (trim($result['datosViajes']['tDestino'])) . "'" : "NULL");
                $relfhSalida 	    = (isset($result['datosViajes']['fhSalida']) && $result['datosViajes']['fhSalida'] != "" 	? "'" . date("Y-m-d", strtotime($result['datosViajes']['fhSalida'])) . "'"	: "NULL");
                $relfhLlegada 		= (isset($result['datosViajes']['fhLlegada']) && $result['datosViajes']['fhLlegada'] != "" ? "'" . date("Y-m-d", strtotime($result['datosViajes']['fhLlegada'])) . "'"	: "NULL");
                $relecodOperados    = (isset($result['datosViajes']['ecodOperados']) && $result['datosViajes']['ecodOperados'] != "" ? "'" . (trim($result['datosViajes']['ecodOperados'])) . "'" : "NULL");
                $relecodCliente     = (isset($result['datosViajes']['ecodCliente']) && $result['datosViajes']['ecodCliente'] != "" ? "'" . (trim($result['datosViajes']['ecodCliente'])) . "'" : "NULL");
                $relcheckmonitor    = (isset($result['datosViajes']['tmonitoreo'])&& $result['datosViajes']['tmonitoreo']>0  ? (int)$result['datosViajes']['tmonitoreo']  : "NULL");
                $relecodEstatus     = (isset($result['datosViajes']['EcodEstatus'])&& $result['datosViajes']['EcodEstatus']>0  ? (int)$result['datosViajes']['EcodEstatus']  : "NULL");       
                $tincidentes        = (isset($result['tincidentes']) && $result['tincidentes'] != "" ? "'" . (trim($result['tincidentes'])) . "'" : "NULL");
                $reltTipoViaje      = (isset($result['datosViajes']['tTipoViaje']) && $result['datosViajes']['tTipoViaje'] != "" ? "'" . (trim($result['datosViajes']['tTipoViaje'])) . "'" : "NULL");
                $reltTipoGasto      = (isset($result['datosViajes']['tTipoGasto']) && $result['datosViajes']['tTipoGasto'] != "" ? "'" . (trim($result['datosViajes']['tTipoGasto'])) . "'" : "NULL");
                $relLink               = (isset($result['datosViajes']['Link']) && $result['datosViajes']['Link'] != "" ? "'" . (trim($result['datosViajes']['Link'])) . "'" : "NULL");
                if ($relcheckmonitor == 'NULL') {
                    $relcheckmonitor = 0;
                } 
               $insertviajesincidentes=" CALL `stpInsertarviajesincidentes`(".$reltreferencia.",".$reltpedido.",".$reltOrigen.",".$reltDestino.",".$relfhSalida.",".$relfhLlegada. ",".$relecodOperados. ",".$relecodCliente.",".$relecodProvedor.",".$relecodEstatus.",".$relcheckmonitor.",".$relecodViaje.",".$loginEcodUsuarios.",".$tincidentes.",".$reltTipoViaje.",".$reltTipoGasto.",".$relLink.")";
                $responseviajesincidentes = DB::select($insertviajesincidentes);
            }
            try {
                DB::beginTransaction();
                $insert=" CALL `stpInsertarBitViajes`(".$treferencia.",".$tpedido.",".$ecodProvedor.",".$tOrigen.",".$tDestino.",".$fhSalida.",".$fhLlegada. ",".$ecodOperados. ",".$ecodCliente.",".$EcodEstatus.",".$checkmonitor.",".$ecodViaje.",".$tTipoViaje.",".$tTipoGasto.",".$Link.",".$loginEcodUsuarios.")";
                $response = DB::select($insert);
                $codigorelvia  = (isset($response[0]->Codigo)&& $response[0]->Codigo>0  ? (int)$response[0]->Codigo : "NULL");
                if (!$codigorelvia) {
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
            return response()->json(['codigo' => $codigorelvia, 'exito' => $exito]);
        }
    }
}
