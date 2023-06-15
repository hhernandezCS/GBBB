<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CajaBanco\ArqueoCaja;

class ArqueoCajaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $arqueo;
    public $vendedor_arqueo;
    public $bancos;
    public $producto_top_cant;
    public $producto_top_cant_ingresos;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($arqueo, $vendedor_arqueo, $bancos, $producto_top_cant, $producto_top_cant_ingresos)
    {
        $this->arqueo  = $arqueo;
        $this->vendedor_arqueo  = $vendedor_arqueo;
        $this->bancos = $bancos;
        $this->producto_top_cant  = $producto_top_cant;
        $this->producto_top_cant_ingresos  = $producto_top_cant_ingresos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.arqueo');
    }
}
