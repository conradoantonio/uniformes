<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    * Public variables are a must to declare!!
    */
    public $data;
    public $titulo;
    public $mensaje;
    public $usuario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $titulo, $mensaje, $usuario = null)
    {
        $this->data = $data;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->usuario = $usuario;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ["refresh-channel"];
    }

    /**
    * The event's broadcast name.
    * Si no se agrega toma como nombre el evento App\Events\...
    * @return string
    */
    public function broadcastAs()
    {
        return 'refresh-event';
    }
}
