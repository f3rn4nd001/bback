<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class loginController extends Controller
{
    public function objeto_a_array($data){
        if (is_array($data) || is_object($data)){
            $result = array();
            foreach ($data as $key => $value){$result[$key] = $this->objeto_a_array($value);}
            return $result;
        }
        return $data;
    }
    public function posLogin(Request $request){
        $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
        if ($dadsad=="Ox_mSak@t~r}uh_GoerfQly_=EM$4iIYk#v4oFguL)TY2b0~O[") {
            if (is_array($request['datos']) || is_object($request['datos'])){
                $result = array();
                foreach ($request['datos'] as $key => $value){
                    $result[$key] = $this->objeto_a_array($value);
                }
                $result; 
            }
            $Email    = (isset($result['Email']) && $result['Email'] != "" ? "'" . (trim($result['Email'])) . "'" : "");           
            $password    = (isset($result['password']) && $result['password'] != "" ? "'" . (trim($result['password'])) . "'" : "");           
            
            $selects = "SELECT ctu.tNombre AS tipousuario, cu.ecodUsuarios,cu.trfc,concat_ws('',cu.tNombre,' ', cu.tApellido) AS usuario, ct.tNombre AS estatus ,cu.ecodTipoUsuario FROM bitcorreos bc
            LEFT JOIN relusuariocorreo ruc ON ruc.ecodCorreo = bc.ecodCorreo
            LEFT JOIN catusuarios cu ON cu.ecodUsuarios = ruc.ecodUsuario
            LEFT JOIN catestatus ct ON ct.EcodEstatus = cu.EcodEstatus
            LEFT JOIN cattipousuario ctu ON ctu.ecotTipoUsuario= cu.ecodTipoUsuario
            WHERE bc.tcorreo = ".$Email."  
            AND bc.tpassword = ".$password."
            AND cu.EcodEstatus = 1";
            $sql = DB::select(DB::raw($selects));
            foreach ($sql as $key => $v){
                $logindata[]=array(
                    'ecodUsuarios' => $v->ecodUsuarios,
                    'usuario'=> $v->usuario, 
                    'tipousuario'=>$v->tipousuario,  
                );
            }
            return response()->json(['sql'=>(isset($logindata) ? $logindata : ""),]);
        }
    }
    
    public function poscontras(Request $request)
    {
    $dadsad = (isset($request['haders']) && $request['haders'] != "" ? "" . (trim($request['haders'])) . "" : "" );           
    if ($dadsad=="Ox_mSak@t~r}uh_GoerfQly_=EM$4iIYk#v4oFguL)TY2b0~O[") {
        if (is_array($request['datos']) || is_object($request['datos'])){
            $result = array();
            foreach ($request['datos'] as $key => $value){
                $result[$key] = $this->objeto_a_array($value);
            }
        }
        $ecodUsuario    = (isset($result['loginEcodUsuarios']) && $result['loginEcodUsuarios'] != "" ? "'" . (trim($result['loginEcodUsuarios'])) . "'" : "");           
        $password    = (isset($result['contrasena']) && $result['contrasena'] != "" ? "'" . (trim($result['contrasena'])) . "'" : "");           
        $selectcontra="SELECT count(*) AS dl FROM relusuariocorreo ruc
        LEFT JOIN bitcorreos bc ON bc.ecodCorreo = ruc.ecodCorreo
        WHERE ruc.ecodUsuario =".$ecodUsuario."
        AND bc.tpassword =".$password;
        $sqlcontra = DB::select(DB::raw($selectcontra));
        return response()->json(['sql'=>$sqlcontra[0]]);
        }
    }
    public function geta(Request $request){
        return response()->json(($sql));
    }
public function getc(Request $request){
    

    $sql = "c";
    return response()->json(($sql));
}
}
