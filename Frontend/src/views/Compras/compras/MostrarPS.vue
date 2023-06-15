<template>
    <div class="main">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Orden de Compra No. {{form.id_orden_servicio}}</span>
                <div class="btn-group">
                    <a @click="regresar" style="margin-right: 10px;"> <i class="fa fa-arrow-left"></i> Regresar</a>
                    <a :disabled="descargando" @click.prevent="downloadItem('compras/ordenes-ps/reporte/'+form.id_orden_servicio)">  <i class="fa fa-download"></i> Descargar</a>
                </div>
            </div>

            <div class="form-wizard-nav">
                <template v-if="form.estado===0">
                    <div class="step complete">Rechazado</div>
                    <div class="step">Borrador</div>
                    <div class="step">Registrado</div>
                    <div class="step">Ordenado</div>
                    <div class="step">Facturado</div>
                    <div class="step">Enviado</div>
                    <div class="step">Recibido</div>
                </template>

                <template v-if="form.estado===99">
                    <div class="step complete">Borrador</div>
                    <div class="step">Registrado</div>
                    <div class="step">Ordenado</div>
                    <div class="step">Facturado</div>
                    <div class="step">Enviado</div>
                    <div class="step">Recibido</div>
                </template>

                <template v-if="form.estado===1">
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
                    <div class="step">Ordenado</div>
                    <div class="step">Facturado</div>
                    <div class="step">Enviado</div>
                    <div class="step">Recibido</div>
                </template>

                <template v-if="form.estado===2">
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
                    <div class="step">Facturado</div>
                    <div class="step">Enviado</div>
                    <div class="step">Recibido</div>
                </template>

                <template v-if="form.estado===3">
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Facturado</div>
                    <div class="step">Enviado</div>
                    <div class="step">Recibido</div>
                </template>

                <template v-if="form.estado===4">
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Facturado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Enviado</div>
                    <div class="step">Recibido</div>
                </template>

                <template v-if="form.estado===5">
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Registrado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Ordenado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Facturado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Enviado</div>
                    <div class="step complete"><i aria-hidden="true" class="fa fa-check-circle"></i>    Recibido</div>
                </template>

            </div>

            <div class="panel-body">
                <div class="document">
                    <div class="document-heading">
                        <div class="row">
                            <div class="col col-12 text-center">
                                <div class="company-name"><template><img class="logo_previa" v-bind:src="logo_empresa" alt="IMG"></template>LUBRICANTES DE NICARAGUA, S. A.</div>
                                <div class="company-address">De la CST. 1 Cuadra arriba, 1 cuadra al sur, Managua Nicaragua</div>
                                <div class="company-address"><strong>Orden de Compra No. {{form.id_orden_servicio}}</strong></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col col-6">
                                <table class="document-summary-left table-striped">
                                    <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>PROVEEDOR</strong></td>
                                    </tr>
                                    <tr>
                                        <template v-if="form.orden_compra_proveedor">
                                            <td><strong>Proveedor:</strong></td>
                                            <td>{{form.orden_compra_proveedor.nombre_comercial}}</td>
                                        </template>
                                        <template v-else>
                                            <span>N/A</span>
                                        </template>
                                    </tr>
                                    <tr>
                                        <template v-if="form.orden_compra_proveedor">
                                            <td><strong>Numero RUC:</strong></td>
                                            <td>{{form.orden_compra_proveedor.numero_ruc}}</td>
                                        </template>
                                        <template v-else>
                                            <span>N/A</span>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td><strong>Atención:</strong></td>
                                        <td>{{form.atencion}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{form.email}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Referencia:</strong></td>
                                        <td>{{form.referencia_solicitud}}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Area/ Dpto:</strong></td>
                                        <td>{{form.area_proveedor}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col col-6">
                                <table class="document-summary-left table-striped">
                                    <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center"><strong>ENVIE A</strong></td>
                                    </tr>
                                    <template v-if="form.id_solicitud_compra">
                                        <tr>
                                            <td><strong>No. Ref.</strong></td>
                                            <td>{{'SC #0000'+form.id_solicitud_compra}}</td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <td><strong>RUC</strong></td>
                                        <td>123456789</td>
                                    </tr>
                                    <tr>
                                        <td><strong>PBX</strong></td>
                                        <td>2268 2316</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha Elaboración:</strong></td>
                                        <td>{{ formatDate(form.f_orden_compra)}}</td>
                                    </tr>
                                    <tr>
                                        <td>Moneda:</td>
                                        <td>{{form.orden_compra_moneda ? form.orden_compra_moneda.descripcion : ''}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado Actual:</strong></td>
                                        <td>
                                            <div v-if="form.estado===0">
                                                <span class="badge badge-danger" style="font-size: 100%;">Rechazada</span>
                                            </div>

                                            <div v-if="form.estado===1">
                                                <span class="badge badge-primary" style="font-size: 100%;">Registrado</span>
                                            </div>

                                            <div v-if="form.estado===2">
                                                <span class="badge badge-success" style="font-size: 100%;">Ordenado</span>
                                            </div>

                                            <div v-if="form.estado===3">
                                                <span class="badge badge-success" style="font-size: 100%;">Facturado</span>
                                            </div>
                                            <div v-if="form.estado===4">
                                                <span class="badge badge-success" style="font-size: 100%;">Enviado</span>
                                            </div>
                                            <div v-if="form.estado===5">
                                                <span class="badge badge-success" style="font-size: 100%;">Recibido</span>
                                            </div>
                                            <div v-if="form.estado===99">
                                                <span class="badge badge-grey" style="font-size: 100%;">Borrrador</span>
                                            </div>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="document-body">
                        <table class="document-table">
                            <thead>
                            <tr>
                                <th>Descripción</th>
                                <th class="align-center">Cantidad</th>
                                <th class="align-center">Precio unitario {{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}}</th>
                                <th class="align-center">Descuento %</th>
                                <th class="align-center">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="item in form.orden_compra_listado" >
                                <tr :key="item.id_orden_servicio_producto">
                                    <td class="width-2">
                                        <pre>{{item.descripcion}}</pre>
                                    </td>
                                    <template v-if="form.estado > 2">
                                        <td class="width-1 align-center">{{item.cantidad_facturado}}</td>
                                        <td
                                                class="width-2 align-right"
                                        >{{item.precio_facturado | formatMoney(2)}}</td>


                                    </template>
                                    <template v-else>
                                        <td class="width-1 align-center">{{item.cantidad}}</td>
                                        <td
                                                class="width-2 align-right"
                                        >{{item.precio | formatMoney(2)}}</td>

                                    </template>

                                    <td
                                            class="width-2 align-right"
                                    >{{item.descuento | formatMoney(2)}}</td>
                                    <td
                                            class="width-2 align-right"
                                    >{{sub_total(item)| formatMoney(2)}}</td>
                                </tr>
                            </template>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">Total Antes de descuento {{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}}</td>
                                <td class="align-right"><strong>{{total_subt | formatMoney(2)}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">Descuento {{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}}</td>
                                <td> <strong>{{total_descuento | formatMoney(2)}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">Impuesto {{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}}</td>
                                <td> <strong>{{form.iva | formatMoney(2)}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">
                                    <strong>Total {{form.orden_compra_moneda ? form.orden_compra_moneda.codigo : ''}} </strong>
                                </td>
                                <td class="align-right">
                                    <strong>{{Number(form.total)+Number(form.iva) | formatMoney(2)}}</strong>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col col-12">
                            <table class="document-summary-left table-striped">
                                <tbody>
                                <tr>
                                    <td colspan="2" class="text-center"><strong>DETALLES CONSOLIDADOR</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Proveedor:</strong></td>
                                    <td>{{form.direccion}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Condición de pago:</strong></td>
                                    <template v-if="form.id_condicion_pago===1">
                                        <td>{{'Contado'}}</td>
                                    </template>

                                    <template v-if="form.id_condicion_pago===2">
                                        <td>{{'Crédito'}}</td>
                                    </template>

                                    <template v-if="form.id_condicion_pago===3">
                                        <td>{{'Consignación'}}</td>
                                    </template>
                                </tr>

                                <tr>
                                    <td><strong>Medio de pago:</strong></td>
                                    <template v-if="form.id_medio_pago===1">
                                        <td>{{'Crédito'}}</td>
                                    </template>

                                    <template v-if="form.id_medio_pago===2">
                                        <td>{{'Transferencia'}}</td>
                                    </template>

                                    <template v-if="form.id_medio_pago===3">
                                        <td>{{'Cheque'}}</td>
                                    </template>
                                    <template v-if="form.id_medio_pago===4">
                                        <td>{{'Minuta Depósito'}}</td>
                                    </template>
                                    <template v-if="form.id_medio_pago===5">
                                        <td>{{'Efectivo'}}</td>
                                    </template>
                                    <template v-if="form.id_medio_pago===6">
                                        <td>{{'Tarjeta'}}</td>
                                    </template>
                                </tr>

                                <tr>
                                    <td><strong>Condición de Envío:</strong></td>
                                    <td>{{form.condicion_envio}}</td>
                                </tr>

                                <tr>
                                    <td><strong>Atención:</strong></td>
                                    <td>{{form.atencion}}</td>
                                </tr>

                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{form.email}}</td>
                                </tr>

                                <tr>
                                    <td><strong>Referencia:</strong></td>
                                    <td>{{form.referencia_solicitud}}</td>
                                </tr>

                                <tr>
                                    <td><strong>Area/ Dpto:</strong></td>
                                    <td>{{form.area_proveedor}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <template v-if="loading">
            <BlockUI :message="msg" :url="url"></BlockUI>
        </template>

    </div>
</template>
<script type="text/ecmascript-6">
    import orden from "../../api/orden_compras_ps";
    import loadingImage from '../../assets/images/block50.gif'
    export default {
        data() {
            return {
                loading:true,
                msg: 'Cargando el contenido espere un momento',
                url : '/public'+loadingImage,
                format: "dd-MM-yyyy",
                descargando:false,
                form: {
                    orden_compra_listado:[],
                    orden_compra_proveedor:[],
                    total: 0,
                    sub_total: 0,

                },
                btnAction: "Registrar",
                errorMessages: [],
                logo_empresa:'',
            };
        },
        methods: {
            sub_total(itemX) {

                if(this.form.estado >2){
                    itemX.subtotal= Number((Number(itemX.precio_facturado)* Number(itemX.cantidad_facturado)).toFixed(2));
                }
                else{
                    itemX.subtotal= Number((Number(itemX.precio)* Number(itemX.cantidad)).toFixed(2));
                }


                itemX.monto_descuento= Number(Number(itemX.subtotal* Number(itemX.descuento/100)).toFixed(2));
                itemX.total =itemX.subtotal - itemX.monto_descuento;
                if(!isNaN(itemX.total)){
                    return itemX.total;
                }
                else return 0;
            },
            downloadItem (url) {
                if(!this.descargando){
                    this.descargando=true;
                    alertify.success("Descargando datos, espere un momento.....",5);
                    axios.get(url, { responseType: 'blob' })
                        .then(({ data }) => {
                            let blob = new Blob([data], { type: 'application/pdf' })
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob)
                            link.download = 'ReporteOrdenCompra-'+this.form.id_orden_servicio+'.pdf';
                            link.click()
                            this.descargando=false;
                        }) .catch(function (error) {
                        alertify.error("Error Descargando archivo.....",5);
                        this.descargando=false;
                    })
                }else{
                    alertify.warning("Espere a que se complete la descarga anterior",5);
                }
            },
            formatDate(date) {
                return moment(date).format('YYYY-MM-DD')
            },
            regresar(){
                this.$router.push({
                    name: "listado-ordenes-compras-ps"
                });
            },
            obtenerOrdenCompra() {
                var self = this;
                orden.obtenerOrdenCompra(
                    {
                        id_orden_servicio: this.$route.params.id_orden_servicio,
                        cargar_dependencias:false
                    },
                    data => {
                        self.form = data.orden;
                        self.loading = false;
                    },
                    err => {
                        console.log(err);
                        self.loading = false;
                    }
                );
            },
            nueva() {
                var self = this;
                orden.nueva({
                    }, data => {
                        self.logo_empresa= data.logo_empresa;
                    },
                    err => {
                        console.log(err);
                    })

            },
        },
        computed: {

            total_cantidad() {
                return this.form.orden_compra_listado.reduce((carry, item) => {

                    if(this.form.estado > 2){
                        return (carry + Number(item.cantidad_facturado))
                    }
                    else{
                        return (carry + Number(item.cantidad))
                    }

                }, 0)
            },
            total_subt() {
                return this.form.orden_compra_listado.reduce((carry, item) => {
                        if(this.form.estado >2){
                            return(carry + Number( (Number(item.cantidad_facturado * item.precio_facturado).toFixed(2))));
                        }
                        else{
                            return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))));
                        }
                    }
                    , 0)
            },
            total_descuento() {
                return this.form.orden_compra_listado.reduce((carry, item) => {
                        if(this.form.estado > 2){
                            return(carry + Number( (Number(item.cantidad_facturado * item.precio_facturado).toFixed(2))* Number(item.descuento/100).toFixed(2)));
                        }
                        else{
                            return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))* Number(item.descuento/100).toFixed(2)));
                        }

                    }
                    , 0)
            },
            gran_total() {
                this.form.total =  this.form.orden_compra_listado.reduce((carry, item) => {

                        if(this.form.estado >2){
                            return(carry + Number( (Number(item.cantidad_facturado * item.precio_facturado).toFixed(2))* Number( 1- (item.descuento/100)).toFixed(2)));
                        }
                        else{
                            return(carry + Number( (Number(item.cantidad * item.precio).toFixed(2))* Number( 1- (item.descuento/100)).toFixed(2)));
                        }

                    }
                    , 0)

                return this.form.total;
            },
        },
        mounted() {
            this.obtenerOrdenCompra();
            this.nueva();
        }
    };
</script>
<style>
    .logo_previa{
        display: inline-block;
        width: 70px;
        height: 70px;
        margin: auto;

    }
</style>