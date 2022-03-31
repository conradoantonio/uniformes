<?php

namespace App\Http\Controllers;

use DB;
use Excel;

use Carbon\Carbon;

use \App\User;
use \App\Empleado;
use \App\Historial;
use \App\RazonSocial;

use Illuminate\Http\Request;

use App\Events\PusherEvent;

class LoginController extends Controller
{
    /**
     * Validate the user login.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if ( auth()->attempt(['email' => $req->email, 'password' => $req->password]) ) {

            if ( auth()->user()->role->descripcion == 'Administrador' ) {

                return redirect()->to('dashboard');

            } elseif( auth()->user()->role->descripcion == 'Visualización' ) {
                    
                return redirect()->to('dashboard');
                // return redirect()->to('facturas');

            } elseif( auth()->user()->role->descripcion == 'Escritura' ) {
                    
                return redirect()->to('dashboard');
                // return redirect()->to('facturas');

            } else {

                session([ 'msg' => 'Usuario inválido']);
                auth()->logout();

            }
        } else {

            $user = User::where('email', $req['email'])->withTrashed()->first();

            if (! $user ) {

                session([ 'msg' => 'Usuario inválido']);
                session()->forget('email');

            } else {

                if ( $user->deleted_at ) {

                    session([ 'msg' => 'Cuenta baneada']);
                    session(['email' => $req['email']]);

                } else {

                    session([ 'msg' => 'Contraseña incorrecta']);
                    session(['email' => $req['email']]);
                    
                }
            }
            auth()->logout();
        }

        return redirect()->to('/')->withErrors([]);
    }

    /**
     * redirect to the dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function load_dashboard()
    {
        $title = $menu = 'Inicio';

        $filters = [
            'user'                 => auth()->user(), 
            'fecha_promesa_inicio' => Carbon::now()->startOfWeek()->format('Y-m-d'),
            'fecha_promesa_fin'    => Carbon::now()->endOfWeek()->format('Y-m-d'),
            'no_canceladas'        => true,
            // 'pagada'               => 0,
        ];

        $data = json_decode($this->getChartsData( $filters ));

        $razones = RazonSocial::filter( $filters )->get();

        $facturasPrometidasSemana = [];
        // $facturasPrometidasSemana = Historial::filter($filters)->where('pagada', 0)->orderBy('fecha_promesa_pago', 'asc')->get();

        return view('layouts.dashboard', ['data' => /*json_decode*/($data), 'facturasPrometidasSemana' => $facturasPrometidasSemana, 'razones' => $razones, 'title' => $title, 'menu' => $menu]);
    }

    /**
     * Shows the sign up form
     *
     * @return \Illuminate\Http\Response
     */
    public function sign_up()
    {
        return view('layouts.sign_up');
    }

    /**
     * Shows the sign up form
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $req)
    {
        $item = User::where('email', $req->email)->first();

        if ( $item ) {
            $newPass = str_random(8);

            $item->password = bcrypt( $newPass );
            $item->save();

            $params = array();

            $params['view'] = 'mails.reset_password';
            $params['subject'] = 'Cambio de contraseña';
            $params['user'] = $item;
            $params['email'] = $item->email;
            $params['password'] = $newPass;

            auth()->logout();
            
            $this->f_mail( $params );
        }

        return response(['status' => 'success', 'msg' => 'Correo enviado', 'url' => url('/')], 200);
    }

    /**
     * Get the dashboard data.
     *
     */
    public function getChartsData(array $filters) 
    {
        $data = new \stdClass();
        
        #Total de clientes
        $data->totalClientes = Empleado::filter($filters)->count();

        #Total de facturas pagadas del periodo
        $aux = $filters;
        // dd($aux['fecha_promesa_inicio'], $aux['fecha_promesa_fin']);
        $data->totalFacturasPagadasMes = Historial::filter($filters)
        // ->whereYear('fecha_facturacion', date('Y'))
        // ->whereMonth('fecha_facturacion', date('m'))
        ->where('pagada', 1)
        ->sum('importe');

        #Total de facturas NO pagadas del mes actual
        $data->totalFacturasNOPagadasMes = Historial::filter($filters)
        // ->whereYear('fecha_facturacion', date('Y'))
        // ->whereMonth('fecha_facturacion', date('m'))
        ->where('pagada', 0)
        ->sum('importe');

        // Parche para que los modelos de Recibo filtren bien
        $aux['fecha_inicio'] = $aux['fecha_promesa_inicio'];
        $aux['fecha_fin'] = $aux['fecha_promesa_fin'];

        #Total de notas de crédito del mes actual
        $data->totalNotasCreditoMes = Recibo::filter($aux)
        // ->whereYear('fecha_pago', date('Y'))
        // ->whereMonth('fecha_pago', date('m'))
        ->where('tipo_recibo_id', 1)
        ->sum('importe');

        #Total de notas de crédito del mes actual
        $data->totalPagosMes = Recibo::filter($aux)
        // ->whereYear('fecha_pago', date('Y'))
        // ->whereMonth('fecha_pago', date('m'))
        ->where('tipo_recibo_id', 2)
        ->sum('importe');

        // Estos filtros no aplican para los siguientes datos
        $aux['fecha_promesa_inicio'] = $aux['fecha_promesa_fin'] = $aux['fecha_inicio'] = $aux['fecha_fin'] = null;
        #Total de facturas NO pagadas de todo el tiempo
        $data->totalFacturasNOPagadas = Historial::filter($aux)
        ->where('pagada', 0)
        ->sum('importe');

        #Total de facturas normales
        $data->totalFacturasNormales = Historial::filter($aux)
        ->where('status_id', 1)
        ->count();

        #Total de facturas morosas
        $data->totalFacturasMorosas = Historial::filter($aux)
        ->where('status_id', 2)
        ->count();

        #Total de facturas prelegales
        $data->totalFacturasPrelegales = Historial::filter($aux)
        ->where('status_id', 3)
        ->count();

        #Total de facturas legales
        $data->totalFacturasLegales = Historial::filter($aux)
        ->where('status_id', 4)
        ->count();

        return json_encode($data);
    }

    /**
     * Destroy's the current session.
     *
     */
    public function logout() 
    {
        auth()->logout();
        return redirect('/');
    }
}
