<template>
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">Nota de Débito No: {{form.no_documento}}</span>
            <div class="btn-group">
                <a @click="$router.go(-1)" style="margin-right: 10px;"> <i class="fa fa-arrow-left"></i> Regresar</a>
                <a :disabled="descargando" @click.prevent="descargarND">  <i class="fa fa-download"></i> Descargar</a>
            </div>
        </div>

        <div class="panel-body">
            <div class="document">
                <div class="document-heading">
                    <div class="row">
                        <div class="col col-12 text-center">
                            <div class="company-name">LUBRICANTES DE NICARAGUA, S. A.</div>
                            <div class="company-address">De la CST. 1 Cuadra arriba, 1 cuadra al sur, Managua Nicaragua</div>
                            <div class="company-name">NOTA DE DEBITO</div>
                            <div class="company-address"><strong>No. {{form.no_documento}}</strong></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col col-8">
                            <p><strong>Debitamos a la cuenta de:</strong></p>
                            <template v-if="form.nota_debito_cliente">
                                <span>{{form.nota_debito_cliente.nombre_comercial + (form.nota_debito_cliente.tipo_persona===1? ', Numero Cédula: '+form.nota_debito_cliente.numero_cedula:', Numero RUC: '+form.nota_debito_cliente.numero_ruc)}}</span>
                                <span v-show="form.nota_debito_sucursal">{{ form.nota_debito_sucursal?', Sucursal: ' + form.nota_debito_sucursal.nombre_comercial:'' }}</span>
                            </template>
                            <template v-else>
                                <span>N/A</span>
                            </template>
                        </div>
                        
                        <div class="col col-4">
                            <table class="document-summary">
                                <tbody>
                                <tr>
                                    <td>Número Nota:</td>
                                    <td>{{form.no_documento}}</td>
                                </tr>
                                <tr>
                                    <td>Fecha Emisión:</td>
                                    <td>{{ formatDate(form.fecha_emision)}}</td>
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
                            <th class="align-center">Subtotal C$</th>
                            <th class="align-center">IVA C$</th>
                            <th class="align-center">Total C$</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="item in form.nota_debito_detalles" >
                            <tr :key="item.id_nota_debito_detalle">
                                <td class="width-4">
                                    <pre>{{item.descripcion}}</pre>
                                </td>
                                <td
                                        class="width-2 text-center"
                                >{{item.precio | formatMoney(2)}}</td>
                                <td
                                        class="width-2 text-center"
                                >{{item.iva | formatMoney(2)}}</td>
                                <td
                                        class="width-2 text-center"
                                >{{item.subtotal | formatMoney(2)}}</td>
                            </tr>
                        </template>
                        </tbody>
                        <tfoot>

                        <tr>
                            <td colspan="3" class="text-right">
                                <strong>Subtotal C$</strong>
                            </td>
                            <td class="text-center">
                                <strong>{{form.monto_subtotal | formatMoney(2)}}</strong>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" class="text-right">
                                <strong>IVA C$</strong>
                            </td>
                            <td class="text-center">
                                <strong>{{form.monto_iva | formatMoney(2)}}</strong>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3" class="text-right">
                                <strong>Total C$</strong>
                            </td>
                            <td class="text-center">
                                <strong>{{form.monto_total | formatMoney(2)}}</strong>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <template v-if="loading">
                <BlockUI :message="msg" :url="url"></BlockUI>
            </template>
        </div>

    </div>

</template>
<script type="text/ecmascript-6">
    import notas from "../../../api/CuentasXCobrar/notas_debito";
    import loadingImage from '../../../assets/images/loader/block50.gif'

    export default {
        data() {
            return {
                loading:true,
                msg: 'Cargando el contenido espere un momento',
                url : '/public'+loadingImage,   //It is important to import the loading image then use here
                format: "dd-MM-yyyy",
                descargando:false,
                form: {
                    no_documento: "",
                    fecha_emision: "",
                    nota_debito_cliente: [],
                    nota_debito_detalles: [],
                    monto_total_iva: 0,
                    monto_total:0,
                    subtotal: 0,
                },
                btnAction: "Registrar",
                errorMessages: []
            };
        },
        methods: {
            formatDate(date) {
                return moment(date).format('YYYY-MM-DD')
            },
            obtenerNotaDebito() {
                var self = this;
                notas.obtenerNotaDebito(
                    {
                        id_nota_debito: this.$route.params.id_nota_debito
                    },
                    data => {
                        self.form = data;
                        self.loading = false;
                    },
                    err => {
                        console.log(err);
                        self.loading = false;
                    }
                );
            },


        descargarND() {
            var self=this;
            self.loading=true;
            self.formato = 'pdf';
            axios.post('cuentas-cobrar/notas-debito/reporte',
                {
                    extension:self.formato,
                    id_nota_debito:self.form.id_nota_debito,
                }, { responseType: 'blob' })
                .then(({ data }) => {
                    let blob = new Blob([data], { type: 'application/pdf' })
                    self.formato === 'xls' ?
                        blob = new Blob([data], { type: 'application/octet-stream' }) :
                        self.formato === 'pdf' ?
                            blob = new Blob([data], { type: 'application/pdf' }):
                            blob = new Blob([data], { type: 'text/html' });
                    let link = document.createElement('a');
                    link.setAttribute('target', '_blank');
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'NotaDebito.'+self.formato;
                    link.click()
                    self.loading = false;
                }).catch(function (error) {
                console.log(error);
                alertify.error("Error Descargando archivo.....", 5);
                self.loading = false;
            });

        },
        },
        mounted() {
            this.obtenerNotaDebito();
        }
    };
</script>
<style>
    td {
        max-width: 250px;
    }
</style>