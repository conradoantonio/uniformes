<?php

namespace App\Traits;

use DB;
use Mail;
use Image;

use \App\User;
use \App\Singin;
use \App\Cliente;
use \App\Historial;
use \App\Notificacion;

use App\Events\PusherEvent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait GeneralFunctions
{
	/**
     * Verify if a file is valid, then upload it to a given path.
     *
     * @return $name
     */
    public function upload_file($file, $path, $rename = false, $resize = false)
    {
        $extensions = array("1"=>"jpeg", "2"=>"jpg", "3"=>"png", "4"=>"gif", "5" => "pdf");
        $name = '';

        if ( $file ) {
            $file_ext = $file->getClientOriginalExtension();
            if (array_search($file_ext, $extensions)) {
                if (! File::exists( $path ) ) {
                    File::makeDirectory(public_path().'/'.$path, 0755, true, true);
                }

                $timer = microtime();
                $timer = str_replace([' ','.'], '', $timer);

                $name = $rename ? $path.'/'.$timer.'.'.$file_ext : $path.'/'.$file->getClientOriginalName();

                if ( is_array( $resize) ) {
                    $content = Image::make( $file )
                    ->resize( $resize['width'], $resize['height'] )
                    ->save( $name );
                } else {
                    $file->move($path, $name);
                }
                
                return $name;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Delete a path/file from server
     *
     */
    public function delete_path($path)
    {
        if ( $path ) {
            File::delete(public_path( $path ));
            return true;
        }
        return false;
    }

    /*
    * Return boolean, true if mail was sent, false if mail fails
    *
    */
    public function f_mail($params)
    {
        $params['view'] = $params['view'] ? $params['view'] : 'mails.general';
        Mail::send($params['view'], ['content' => $params], function ($message) use($params)
        {
            $message->to($params['email']);
            $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $message->subject(env('APP_NAME').' | '.$params['subject']);
        });
        if ( !Mail::failures() ){
            //error_log('enviado');
            return true;
        }
        error_log('error_send: '.Mail::failures());
        return false;
    }

    /**
     * Envia un correo electrónico al estudiante con el reporte de horas seleccionado
     *
     */
    public function sendReportEmail($path, Request $req)
    {
        $params = array();

        $params['view'] = 'mails.general';
        $params['subject'] = 'Reporte de horas.';
        $params['content'] = 'Se le ha enviado un reporte de horas acumuladas por limpieza de aglomeraciones a su correo electrónico en formato pdf.';
        $params['email'] = explode(",", $req->mail_to);
        $params['files'] = $path;

        $this->f_mail( $params );
    }

    /**
     * Calcula el tiempo de visita entre puntos de venta
     *
     */
    public function calcularTiempoVisita(Recorrido $recorrido, Visita $visita) 
    {
        $time = '00:00:00';

        $ultima_visita = Visita::where('recorrido_id', $recorrido->id)->where('fecha_hora_visita', '!=', null)->orderBy('fecha_hora_visita', 'DESC')->first();
        
        #Es la primer visita
        if (! $ultima_visita ) {

            $datetime1 = date_create($recorrido->fecha_hora_inicio);

        } else {

            $datetime1 = date_create($ultima_visita->fecha_hora_visita);

        }

        $datetime2 = date_create($this->actual_datetime);

        $interval = date_diff($datetime1, $datetime2);
        
        if ( $interval->format('%d') > 0 ) {
            $time = $interval->format('%d dia(s) con %h hrs, %i min y %s seg');
        } else {
            #Es del mismo día
            $time = $interval->format('%h hrs, %i min y %s seg');
        }

        return $time;
    }

    /**
     * Guarda el historial de transacciones de un cliente
     *
     */
    public function guardarHistorial(Cliente $cliente, $item, $typable) 
    {
        $historial = New Historial;

        $historial->cliente_id = $cliente->id;
        $historial->historiable_id = $item->id;
        $historial->historiable_type = $typable;
        $historial->fecha = date('Y-m-d H:i:s');

        $historial->save();

        return $historial;
    }

    /**
     * Edita el historial de transacciones de un cliente
     * @params $historial, cliente nuevo, item, pago, notas de crédito), $typable, fecha nueva
     *
     */
    public function editarHistorial(Historial $historial, Cliente $cliente, $item, $typable, $fecha_nueva) 
    {
        if (! $historial ) { return $historial; }

        $historial = Historial::find($historial->id);

        if ( $historial ) {

            $historial->cliente_id = $cliente->id;
            $historial->historiable_id = $item->id;
            $historial->historiable_type = $typable;
            $historial->fecha = $fecha_nueva;

            $historial->save();

        }

        return $historial;
    }
}
