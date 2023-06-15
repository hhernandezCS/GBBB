<template>
    <b-card>
        <b-row>
            <b-col sm="12">
                <ul class="error-messages">
                    <li
                            :key="message"
                            v-for="message in errorMessages.detalleProductos"
                            v-text="message"
                    ></li>
                </ul>
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th colspan="4"></th>
                        <th class="text-center table-number" colspan="5">CONTADO</th>
                        <th colspan="5"></th>
                    </tr>
                    <tr>
                        <th>N° FACTURA CONTADO / CREDITO</th>
                        <th>N° RECIBO DE CAJA</th>
                        <th>RET.N°</th>
                        <th>MONTO A RETENER</th>
                        <th>Minuta Deposito</th>
                        <th>Efectivo</th>
                        <th>Tarjeta</th>
                        <th>Cheque</th>
                        <th>Transferencia</th>
                        <th>TOTAL INGRESOS</th>
                        <th>VENTAS DE CRÉDITO</th>
                        <th>INGRESOS TOTALES</th>
                        <th>OBSERVACION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.arqueo_loop">
                        <tr>
                            <td>
                                {{item.no_documento}}
                            </td>
                            <td>
                                {{item.no_recibo}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.doc_exonera:0}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_retencion:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_minuta:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_efectivo:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_tarjeta:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_cheque:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_transferencia:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_deuda:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_total:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estado}}
                            </td>

                        </tr>
                    </template>
                    <tr>
                        <template v-if="form.arqueo_loop.length<=0">
                            <td class="text-center" colspan="13">
                                No hay datos registrados
                            </td>
                        </template>
                    </tr>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="3"> Totales</td>
                        <td class="item-footer">
                            <strong>{{total_retencion|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_minutas|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_efectivo|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_tarjeta|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_cheques|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_transferencias|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_pagado|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_credito|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total|formatMoney(2)}}</strong>
                        </td>

                        <td></td>
                        <!--<td>Total</td>
                        <td> <strong>C$ {{gran_total | formatMoney(2)}}</strong></td>-->
                    </tr>

                    </tfoot>
                </table>
            </b-col>
        </b-row>


        <br>
        <div class="row">
            <div class="col-sm-4">

                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="3">EFECTIVO CÓRDOBAS</th>
                    </tr>
                    <tr>
                        <th>DENOMINACION</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            C$ 1,000.00
                        </td>
                        <td>
                            <input class="form-control" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_mil">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_mil"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_mil*1000 |formatMoney(2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            C$ 500.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_quinientos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_quinientos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_quinientos*500 |formatMoney(2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            C$ 200.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_doscientos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_doscientos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_doscientos*200 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 100.00
                        </td>
                        <td>
                            <input class="form-control" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_cien">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cien"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_cien*100 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 50.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_cincuenta">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cincuenta"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_cincuenta*50 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 20.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_veinte">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_veinte"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_veinte*20 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 10.00
                        </td>
                        <td>
                            <input class="form-control" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_diez">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_diez"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_diez*10 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 5.00
                        </td>
                        <td>
                            <input class="form-control" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_cinco">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cinco"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_cinco*5 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 1.00
                        </td>
                        <td>
                            <input class="form-control" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_uno">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_uno"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_uno*1 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 0.50
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly
                                   v-model.number="form.arqueo_deposito.cantidad_cincuenta_centavos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cincuenta_centavos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.arqueo_deposito.cantidad_cincuenta_centavos*0.5 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 0.25
                        </td>
                        <td>
                            <input class="form-control" type="number" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_veinticinco_centavos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_veinticinco_centavos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_veinticinco_centavos*0.25 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 0.10
                        </td>
                        <td>
                            <input class="form-control" type="number" readonly
                                   v-model.number="form.arqueo_deposito.cantidad_diez_centavos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_diez_centavos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_diez_centavos*0.1 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr></tr>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="2"> Totales</td>
                        <td class="item-footer">
                            <strong>C$ {{total_efectivo_cordobas|formatMoney(2)}}</strong>
                        </td>
                    </tr>

                    </tfoot>
                </table>

            </div>


            <b-col sm="4">

                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="3">EFECTIVO DÓLARES</th>
                    </tr>
                    <tr>
                        <th>DENOMINACION</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td>
                            $ 100.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_cien_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cien_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.arqueo_deposito.cantidad_cien_dol*100 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            $ 50.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_cincuenta_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cincuenta_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.arqueo_deposito.cantidad_cincuenta_dol*50 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            $ 20.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_veinte_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_veinte_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.arqueo_deposito.cantidad_veinte_dol*20 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            $ 10.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_diez_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_diez_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.arqueo_deposito.cantidad_diez_dol*10 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            $ 5.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_cinco_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cinco_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.arqueo_deposito.cantidad_cinco_dol*5 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            $ 1.00
                        </td>
                        <td>
                            <input class="form-control"
                                   readonly v-model.number="form.arqueo_deposito.cantidad_uno_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_uno_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.arqueo_deposito.cantidad_uno_dol |formatMoney(2)}}
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="2"> Totales</td>
                        <td class="item-footer">
                            <strong>$ {{total_efectivo_dolares|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td> TC</td>
                        <td> {{form.arqueo_deposito.tasa_cambio}}</td>
                        <td class="item-footer">
                            <strong>C$ {{form.efectivo_dolares_equivalente|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </b-col>


            <b-col sm="4">
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="2">CHEQUES</th>
                    </tr>
                    <tr>
                        <th>Banco</th>
                        <th>Monto C$</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.arqueo_cheques">
                        <tr>
                            <td>
                                {{item.descripcion}}
                            </td>
                            <td>
                                {{item.monto_tran_cordobas|formatMoney(2)}}
                            </td>
                        </tr>
                        <tr></tr>
                    </template>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td> Totales</td>
                        <td class="item-footer">
                            <strong>C$ {{total_cheques_banco|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>

                <br>

                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="3">VOUCHER / MINUTAS</th>
                    </tr>
                    <tr>
                        <th>Banco</th>
                        <th>CORDOBAS</th>
                        <th>DOLARES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.arqueo_minutas">
                        <tr>
                            <td>
                                {{item.descripcion}}
                            </td>
                            <td>
                                {{item.monto_tran_cordobas|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.monto_tran_dolares|formatMoney(2)}}
                            </td>

                        </tr>
                        <tr></tr>
                    </template>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td> Totales</td>
                        <td class="item-footer">
                            <strong>C$ {{total_transferencias_banco|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>$ {{form.monto_pagado_tran_bancos_dol|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td> Total Cordobas</td>
                        <td class="text-center" colspan="2">
                            <strong>C$ {{form.monto_pagado_tran_bancos_total_cord|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>

                <br>

                <table class="table table-responsive table-bordered">
                    <thead>
                    </thead>
                    <tbody>
                    <!--<tr>
                        <td> Total A Depositar</td>
                        <td class="item-footer">
                            <strong>C$ {{form.total_a_depositar|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td> SOBRANTE / FALTANTE</td>
                        <td class="item-footer">
                            <strong>C$ {{form.sobrante_faltante|formatMoney(2)}}</strong>
                        </td>
                    </tr>-->


                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>


            </b-col>
        </div>
        <br>
        <b-col sm="12">
            <div class="form-group">
                <label for>Observaciones arqueo</label>
                <b-form-input class="form-control" readonly type="text"
                              v-model="form.arqueo_deposito.observaciones"></b-form-input>
                <ul class="error-messages">
                    <li
                            :key="message"
                            v-for="message in errorMessages.observaciones"
                            v-text="message"
                    ></li>
                </ul>
            </div>
        </b-col>

        <b-col sm="12">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th class="text-center" colspan="7">DEPOSITOS</th>
                </tr>
                <tr>
                    <th>Banco</th>
                    <th>Valor C$</th>
                    <th>Referencia</th>
                    <th>Fecha</th>
                    <th>Valor $</th>
                    <th>Referencia</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(item, index) in bancos_deposito">
                    <tr>
                        <td>
                            {{item.descripcion}}
                        </td>

                        <td>
                            <b-form-input class="form-control" min="0" type="number"
                                          v-model.number="item.valor_cord"></b-form-input>
                        </td>
                        <td>
                            <b-form-input class="form-control" type="text"
                                          v-model="item.referencia_cord"></b-form-input>
                        </td>
                        <td>
                            <flat-pickr
                                    @selected="seleccionarFechaCord"
                                    class="form-control"
                                    v-model="item.fecha_cord"
                            />
                            <!--                            <b-form-datepicker :format="format"
                                                                    @selected="seleccionarFechaCord"
                                                                    v-model="item.fecha_cord"></b-form-datepicker>-->
                        </td>
                        <td>
                            <b-form-input class="form-control" min="0" type="number"
                                          v-model.number="item.valor_dol"></b-form-input>
                        </td>
                        <td>
                            <b-form-input class="form-control" type="text" v-model="item.referencia_dol"></b-form-input>
                        </td>
                        <td>

                            <flat-pickr
                                    @selected="seleccionarFechaDol"
                                    class="form-control"
                                    v-model="item.fecha_dol"
                            />
                        </td>
                    </tr>
                </template>
                </tbody>
                <tfoot>

                <!--<tr>
                    <td> Totales</td>
                    <td class="item-footer">
                        <strong>C$ {{total_cheques_banco|formatMoney(2)}}</strong>
                    </td>
                </tr>-->
                </tfoot>
            </table>


        </b-col>

        <b-card-footer class="text-right">
            <router-link :to="{ name: 'cajabanco-arqueos' }">
                <b-button class="mx-1" type="button" variant="secondary">Cancelar</b-button>
            </router-link>
            <b-button
                    :disabled="btnAction !== 'Actualizar'"
                    @click="registrar()"
                    type="button"
                    variant="primary"
            >{{ btnAction }}
            </b-button>
        </b-card-footer>

        <!--Gif de pantalla de carga-->
        <template v-if="loading">
            <BlockUI>
                <b-spinner label="cargando..." variant="info"/>
            </BlockUI>
        </template>
    </b-card>

</template>

<script type="text/ecmascript-6">
    import orden from '../../../api/CajaBanco/arqueos_cajas';
    import moment from "../../../../../Backend/resources/app/assets/plugins/moment/moment";
    import es from 'vuejs-datepicker/dist/locale/translations/es';
    import {
        BAlert,
        BButton,
        BCard,
        BCardFooter,
        BCol,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BPaginationNav,
        BRow,
        BSpinner,
        VBTooltip
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import tc from "../../../api/contabilidad/tasas-cambio.js";
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
    import flatPickr from 'vue-flatpickr-component'

    export default {
        components: {
            BCard,
            BCardFooter,
            BPaginationNav,
            BFormCheckbox,
            BFormGroup,
            vSelect,
            BRow,
            BButton,
            BFormCheckboxGroup,
            BFormDatepicker,
            BAlert,
            BFormSelect,
            BFormInput,
            BSpinner,
            flatPickr,
            BCol
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data() {
            return {

                loading: true,
                existe_arqueo: false,
                es: es,
                format: "dd-MM-yyyy",
                vendedores: [],
                fechax: new Date(),
                bancos_deposito: [],

                id_arqueo: 0,
                form: {
                    // fields new table

                    valor_cord: 0,
                    referencia_cord: '',
                    fecha_cord: '',
                    valor_dol: '',
                    referencia_dol: '',
                    fecha_dol: '',
                    depositos: [],

                    bancos_montos: [],
                    bancos_montos_trans: [],
                    fecha_arqueo: moment(new Date()).format("YYYY-MM-DD"),
                    vendedor: "",
                    cantidad_mil: 0,
                    cantidad_quinientos: 0,
                    cantidad_doscientos: 0,
                    cantidad_cien: 0,
                    cantidad_cincuenta: 0,
                    cantidad_veinte: 0,
                    cantidad_diez: 0,
                    cantidad_cinco: 0,
                    cantidad_uno: 0,
                    cantidad_cincuenta_centavos: 0,
                    cantidad_veinticinco_centavos: 0,
                    cantidad_diez_centavos: 0,
                    efectivo_cordobas: 0,

                    cantidad_cien_dol: 0,
                    cantidad_cincuenta_dol: 0,
                    cantidad_veinte_dol: 0,
                    cantidad_diez_dol: 0,
                    cantidad_cinco_dol: 0,
                    cantidad_uno_dol: 0,
                    efectivo_dolares: 0,
                    efectivo_dolares_equivalente: 0,

                    tasa_cambio: 0,
                    tasa_paralela: 0,
                    observaciones: '',

                    arqueo_loop: [],
                    arqueo_deposito: [],
                    arqueo_cheques: [],
                    arqueo_minutas: [],


                    total_retencion: 0,
                    monto_pagado_minuta: 0,
                    monto_pagado_efectivo: 0,
                    monto_pagado_tarjeta: 0,
                    monto_pagado_cheque: 0,
                    monto_pagado_cheque_bancos: 0,
                    monto_pagado_transferencia: 0,
                    monto_pagado: 0,
                    monto_credito: 0,
                    monto_ingreso: 0,
                    total_a_depositar: 0,
                    sobrante_faltante: 0,

                    monto_pagado_tran_bancos_cord: 0,
                    monto_pagado_tran_bancos_dol: 0,
                    monto_pagado_tran_bancos_total_cord: 0,

                },
                btnAction: "Actualizar",
                btnActionDraft: "Guardar Borrador",
                errorMessages: []
            };
        },
        computed: {
            total_retencion() {
                this.form.total_retencion = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_retencion : 0))
                }, 0)
                return this.form.total_retencion;
            },
            total_minutas() {
                this.form.monto_pagado_minuta = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_minuta : 0))
                }, 0)
                return this.form.monto_pagado_minuta;
            },
            total_efectivo() {
                this.form.monto_pagado_efectivo = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_efectivo : 0))
                }, 0)
                return this.form.monto_pagado_efectivo;
            },
            total_tarjeta() {
                this.form.monto_pagado_tarjeta = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_tarjeta : 0))
                }, 0)
                return this.form.monto_pagado_tarjeta;
            },
            total_cheques() {
                this.form.monto_pagado_cheque = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_cheque : 0))
                }, 0)
                return this.form.monto_pagado_cheque;
            },
            total_cheques_banco() {
                this.form.monto_pagado_cheque_bancos = this.form.arqueo_cheques.reduce((carry, item) => {
                    return (carry + Number(item.monto_tran_cordobas))
                }, 0)


                return this.form.monto_pagado_cheque_bancos;
            },
            total_transferencias() {
                this.form.monto_pagado_transferencia = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_transferencia : 0))
                }, 0)


                return this.form.monto_pagado_transferencia;
            },

            total_transferencias_banco() {
                this.form.monto_pagado_tran_bancos_cord = this.form.arqueo_minutas.reduce((carry, item) => {
                    return (carry + Number(item.monto_tran_cordobas))
                }, 0)

                this.form.monto_pagado_tran_bancos_dol = this.form.arqueo_minutas.reduce((carry, item) => {
                    return (carry + Number(item.monto_tran_dolares))
                }, 0)

                this.form.monto_pagado_tran_bancos_total_cord = Number(this.form.monto_pagado_tran_bancos_cord + Number((this.form.monto_pagado_tran_bancos_dol * this.form.arqueo_deposito.tasa_cambio).toFixed(2)));

                this.form.total_a_depositar = this.form.monto_pagado_cheque_bancos + this.form.efectivo_cordobas + this.form.efectivo_dolares_equivalente;

                this.form.sobrante_faltante = Number((this.form.total_a_depositar + this.form.monto_pagado_tran_bancos_total_cord -
                    this.form.monto_pagado_minuta -
                    this.form.monto_pagado_efectivo -
                    this.form.monto_pagado_tarjeta -
                    this.form.monto_pagado_cheque -
                    this.form.monto_pagado_transferencia).toFixed(2));


                return this.form.monto_pagado_tran_bancos_cord;
            },

            total_pagado() {
                this.form.monto_pagado = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado : 0))
                }, 0)
                return this.form.monto_pagado;
            },
            total_credito() {
                this.form.monto_credito = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_deuda : 0))
                }, 0)
                return this.form.monto_credito;
            },
            total() {
                this.form.monto_ingreso = this.form.arqueo_loop.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_total : 0))
                }, 0)
                return this.form.monto_ingreso;
            },

            total_efectivo_cordobas() {
                this.form.efectivo_cordobas =
                    this.form.arqueo_deposito.cantidad_mil * 1000 +
                    this.form.arqueo_deposito.cantidad_quinientos * 500 +
                    this.form.arqueo_deposito.cantidad_doscientos * 200 +
                    this.form.arqueo_deposito.cantidad_cien * 100 +
                    this.form.arqueo_deposito.cantidad_cincuenta * 50 +
                    this.form.arqueo_deposito.cantidad_veinte * 20 +
                    this.form.arqueo_deposito.cantidad_diez * 10 +
                    this.form.arqueo_deposito.cantidad_cinco * 5 +
                    this.form.arqueo_deposito.cantidad_uno +
                    this.form.arqueo_deposito.cantidad_cincuenta_centavos * 0.5;
                return this.form.efectivo_cordobas;
            },
            total_efectivo_dolares() {
                this.form.efectivo_dolares =

                    this.form.arqueo_deposito.cantidad_cien_dol * 100 +
                    this.form.arqueo_deposito.cantidad_cincuenta_dol * 50 +
                    this.form.arqueo_deposito.cantidad_veinte_dol * 20 +
                    this.form.arqueo_deposito.cantidad_diez_dol * 10 +
                    this.form.arqueo_deposito.cantidad_cinco_dol * 5 +
                    this.form.arqueo_deposito.cantidad_uno_dol;

                this.form.efectivo_dolares_equivalente = Number((this.form.efectivo_dolares * this.form.arqueo_deposito.tasa_cambio).toFixed(2));
                return this.form.efectivo_dolares;
            },
        },
        methods: {
            limpiarArqueo() {
                let self = this;
                self.form.total_retencion = [];
            },
            obtenerArqueo() {
                var self = this
                self.loading = true;
                orden.obtenerArqueoEspecifico({
                    id_arqueo: self.$route.params.id_arqueo
                }, data => {
                    self.form = data;
                    self.loading = false;
                }, err => {
                    self.loading = false;
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: err.id_arqueo,
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    this.$router.push({
                        name: 'cajabanco-arqueos'
                    });
                })


            },

            onDateSelect(date) {
                var self = this;
                self.loading = true;
                this.form.fecha_arqueo = moment(date).format("YYYY-MM-DD");
                this.form.detalleFacturas = [];
                tc.obtenerTCParalela({
                    fecha: self.form.fecha_arqueo,
                    dias: 0
                }, data => {
                    if (data.tasa !== null) {
                        self.form.tasa_cambio = Number(data.tasa);
                        self.form.tasa_paralela = Number(data.tasa_paralela);
                        self.loading = false;
                    } else {
                        self.loading = false;
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'No se encuentra tasa de cambio para esta fecha.',
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                }, err => {
                    if (err.fecha[0]) {
                        self.loading = false;
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: err.fecha[0],
                                    variant: 'warning',
                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                })

            },


            nuevo() {
                const self = this;
                orden.nuevo({}, data => {
                    self.vendedores = data.vendedores;
                    self.bancos_deposito = data.bancos;
                    self.form.tasa_paralela = data.tasa_paralela;
                    self.form.tasa_cambio = data.tasa_cambio;
                    self.loading = false;

                })

            },
            seleccionarFechaCord(date) {
                this.form.fecha_cord = moment(date).format("YYYY-MM-DD");
            },
            seleccionarFechaDol(date) {
                this.form.fecha_dol = moment(date).format("YYYY-MM-DD");
            },

            registrar() {
                var self = this;
                self.btnAction = "Registrando, espere....";
                self.$swal.fire({
                    title: 'Esta seguro de actualizar el arqueo diario?',
                    text: "Revise bien los datos",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        self.form.bancos_deposito = this.bancos_deposito;
                        self.form.id_arqueo = self.form.arqueo_deposito.id_arqueo;
                        orden.actualizar(
                            self.form,
                            data => {
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'CheckIcon',
                                            text: 'Arqueo diario actualizado correctamente.',
                                            variant: 'success',
                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                                this.$router.push({
                                    name: "cajabanco-arqueos"
                                });
                            },
                            err => {
                                self.errorMessages = err;
                                self.btnAction = "Actualizar";
                            }
                        );
                    } else {
                        self.btnAction = "Actualizar";
                    }
                })

            }
        },
        mounted() {
            this.nuevo();
            this.obtenerArqueo();
        }
    };
</script>
<style lang="scss">
    @import '../../../@core/scss/vue/libs/vue-flatpicker';

    .btn-agregar {
        margin-top: 33px;
    }

    .check-label2 {
        margin-top: 30px;
    }

    .btn-generar {
        margin-top: 33px;
    }

    .table {
        white-space: nowrap;
        overflow-x: auto;
    }
</style>
