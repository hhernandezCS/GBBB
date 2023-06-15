<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
    <xml>
        <o:officedocumentsettings>
            <o:pixelsperinch>96</o:pixelsperinch>
        </o:officedocumentsettings>
    </xml>
    <![endif]-->
    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700"
        rel="stylesheet" media="screen">
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }

            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }

            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased;">
<div role="article" aria-roledescription="email" aria-label="" lang="en"
     style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
    <table style="width: 100%; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0"
           cellspacing="0" role="presentation">
        <tr>
            <td align="center"
                style="mso-line-height-rule: exactly; background-color: #eceff1; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="sm-py-32 sm-px-24"
                            style="mso-line-height-rule: exactly; padding: 48px; text-align: center; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                            <a href="https://pos.capital.software"
                               style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                <img src="https://bkpos.capital.software/img/logo-home.png" width="70%"
                                     alt="Carnes San Martin"
                                     style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" class="sm-px-24"
                            style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="sm-px-24"
                                        style="mso-line-height-rule: exactly; border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 16px; line-height: 24px; color: #626262;">
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 0; font-size: 20px; font-weight: 600;">
                                            Informe de cierre de caja.</p>
                                        <br>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0;">
                                            Estimado(a) Sres. Industrial Comercial San Martin,
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">
                                            esta es una comunicación automática generada por el Sistema POS San Martin
                                            al momento del cierre de caja,
                                            mediante la cual se comunica los detalles de cierre de caja de la Sucursal
                                            Galerias Santo Domingo.
                                        </p>
                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding: 16px; font-size: 16px;">
                                                    <table style="width: 100%;" cellpadding="0" cellspacing="0"
                                                           role="presentation">
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; font-size: 16px;">
                                                                <strong>Cierre:</strong> {{$arqueo->f_creacion}}
                                                            </td>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; font-size: 16px;">
                                                                <strong>Vendedor:</strong> {{$vendedor_arqueo->nombre_completo}}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td colspan="2"
                                                    style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                    <table style="width: 100%;" cellpadding="0" cellspacing="0"
                                                           role="presentation">
                                                        <!--Seccion detalle de arqueo-->
                                                        <tr>
                                                            <th align="left"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px;">
                                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                                    Detalle del arqueo</p>
                                                            </th>
                                                            {{-- <th align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px;">
                                                                 <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">Monto</p>
                                                             </th>--}}
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                Efectivo
                                                            </td>
                                                            <td align="right"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">
                                                                {{$arqueo->monto_pagado_efectivo}} C$
                                                            </td>
                                                        </tr>
                                                        @if(isset($bancos))
                                                            @foreach($bancos as $banco)
                                                                <tr>
                                                                    <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                        {{$banco->descripcion ? $banco->descripcion : '-'}}
                                                                    </td>
                                                                    <td align="right"
                                                                        style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">{{$banco->monto_tran_cordobas > 0 ? $banco->monto_tran_cordobas = round($banco->monto_tran_cordobas + (round((float)$banco->monto_tran_dolares * (float)$arqueo->tasa_cambio, 2)),2) : $banco->monto_tran_cordobas = round((float)$banco->monto_tran_dolares * (float)$arqueo->tasa_cambio, 2) }}
                                                                        C$
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                Transferencia
                                                            </td>
                                                            <td align="right"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">{{$arqueo->monto_pagado_transferencia}}
                                                                C$
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                Diferencias
                                                            </td>
                                                            <td align="right"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">{{$arqueo->sobrante_faltante}}
                                                                C$
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                <strong>Total ingresos</strong>
                                                            </td>
                                                            <td align="right"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">
                                                                <strong>{{$arqueo->monto_ingresos}} C$</strong></td>
                                                        </tr>
                                                        <hr>
                                                        <!--Seccion top productos vendidos-->
                                                        <tr>
                                                            <th align="left"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px;">
                                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                                    Top 3 productos más vendidos</p>
                                                            </th>
                                                        </tr>
                                                        <!--Producto 1-->
                                                        @if(isset($producto_top_cant))
                                                            @foreach($producto_top_cant as $top)
                                                                <tr>
                                                                    <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                        {{--                                                                {{$producto_top_cant ? $producto_top_cant[0]->descripcion : '-'}}--}}
                                                                        {{$top ? $top->descripcion : '-'}}
                                                                    </td>
                                                                    <td align="right"
                                                                        style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">{{$top ? $top->total_vendido : '-'}}
                                                                        LB
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif

                                                        <hr>
                                                        <!--Top 3 productos mayor ingreso-->
                                                        <tr>
                                                            <th align="left"
                                                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px;">
                                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                                    Top 3 productos con mayor ingreso</p>
                                                            </th>
                                                        </tr>
                                                        <!--Producto 1-->
                                                        @if(isset($producto_top_cant_ingresos))
                                                            @foreach($producto_top_cant_ingresos as $top)
                                                                <tr>
                                                                    <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                        {{$top ? $top->descripcion : '-'}}
                                                                    </td>
                                                                    <td align="right"
                                                                        style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">{{$top ? $top->total_ingreso : '-'}}
                                                                        C$
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        {{--<table align="right" style="margin-left: auto; margin-right: auto; width: 100%; text-align: center;" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                    <table style="margin-top: 24px; margin-bottom: 24px;" cellpadding="0" cellspacing="0" role="presentation">
                                                        <tr>
                                                            <td align="right" style="mso-line-height-rule: exactly; mso-padding-alt: 16px 24px; border-radius: 4px; background-color: #7367f0; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                                                                <a href="https://example.com" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; display: block; padding-left: 24px; padding-right: 24px; padding-top: 16px; padding-bottom: 16px; font-size: 16px; font-weight: 600; line-height: 100%; color: #ffffff; text-decoration: none;">Pay Invoice &rarr;</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>--}}
                                        {{--                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 6px; margin-bottom: 20px; font-size: 16px; line-height: 24px;">
                                                                                    If you have any questions about this invoice, simply reply to this email or reach out to our
                                                                                    <a href="{{support_url}}" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">support team</a> for help.
                                                                                </p>--}}
                                        <br>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 6px; margin-bottom: 20px; font-size: 16px; line-height: 24px;">
                                            Saludos cordiales,
                                            <br>POS SAN MARTIN,
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 20px;"></td>
                    </tr>
                    <tr>
                        <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 16px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>

