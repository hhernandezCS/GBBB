<template>
    <b-card>
        <b-row>
            <b-col sm="4">
                <div class="form-group">
                    <label> Vendedor</label>
                    <div class="form-group">
                        <v-select :allow-empty="false" :options="vendedores"
                                  :searchable="true"
                                  :show-labels="false"
                                  deselect-label="No se puede eliminar este valor"
                                  label="nombre_completo"
                                  placeholder="Selecciona un vendedor"
                                  track-by="id_vendedor"
                                  v-model="form.vendedor"
                                  v-on:input="limpiarArqueo"
                        ></v-select>
                    </div>
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.vendedor" v-text="message"></li>
                    </ul>
                </div>
            </b-col>

            <b-col sm="3">
                <div class="form-group">
                    <label for>Fecha Arqueo</label>
                    <b-form-datepicker :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                       :format="format"
                                       :value="new Date()"
                                       @input="onDateSelect"
                                       local="es"
                                       v-model="fechax">

                    </b-form-datepicker>

                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.fecha_arqueo"
                                v-text="message"
                        ></li>
                    </ul>
                </div>
            </b-col>

            <b-col sm="2">
                <div class="form-group">
                    <label for>T/C</label>
                    <b-form-input class="form-control" disabled type="text" v-model="form.tasa_cambio"></b-form-input>
                </div>
            </b-col>

            <div class="col-sm-3">
                <div class="form-group">
                    <b-button @click="cargarArqueo" class="btn-generar" variant="info">
                        <feather-icon icon="SearchIcon"></feather-icon>
                        Generar
                    </b-button>
                </div>
            </div>

        </b-row>


        <div class="row">
            <div class="col-sm-12">
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
                        <th>Minuta Deposito C$</th>
                        <th>Minuta Deposito $</th>
                        <th>Efectivo C$</th>
                        <th>Efectivo $</th>
                        <th>Tarjeta C$</th>
                        <th>Tarjeta $</th>
                        <th>Cheque C$</th>
                        <th>Cheque $</th>
                        <th>Transferencia C$</th>
                        <th>Transferencia $</th>
                        <th>TOTAL INGRESOS C$</th>
                        <th>TOTAL INGRESOS $</th>
                        <th>VENTAS DE CRÉDITO C$</th>
                        <th>VENTAS DE CRÉDITO $</th>
                        <th>INGRESOS TOTALES C$</th>
                        <th>INGRESOS TOTALES $</th>
                        <th>OBSERVACION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.detalleFacturas">
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
                                {{item.estadox!==0?item.monto_pagado_minuta_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_efectivo:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_efectivo_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_tarjeta:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_tarjeta_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_cheque:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_cheque_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_transferencia:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_transferencia_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.monto_pagado_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_deuda:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_deuda_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_total:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estadox!==0?item.mt_total_me:0|formatMoney(2)}}
                            </td>
                            <td>
                                {{item.estado}}
                            </td>

                        </tr>
                    </template>
                    <tr>
                        <template v-if="form.detalleFacturas.length<=0">
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
                            <strong>{{total_minutas_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_efectivo|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_efectivo_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_tarjeta|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_tarjeta_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_cheques|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_cheques_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_transferencias|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_transferencias_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_pagado|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_pagado_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_credito|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_credito_me|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total|formatMoney(2)}}</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{total_me|formatMoney(2)}}</strong>
                        </td>

                        <td></td>
                        <!--<td>Total</td>
                        <td> <strong>C$ {{gran_total | formatMoney(2)}}</strong></td>-->
                    </tr>

                    </tfoot>
                </table>
            </div>
        </div>


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
                            <input class="form-control" type="number" v-model.number="form.cantidad_mil">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_mil"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_mil*1000 |formatMoney(2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            C$ 500.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_quinientos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_quinientos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_quinientos*500 |formatMoney(2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            C$ 200.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_doscientos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_doscientos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_doscientos*200 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 100.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_cien">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cien"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_cien*100 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 50.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_cincuenta">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cincuenta"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_cincuenta*50 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 20.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_veinte">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_veinte"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_veinte*20 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            C$ 10.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_diez">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_diez"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_diez*10 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 5.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_cinco">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cinco"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_cinco*5 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 1.00
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_uno">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_uno"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_uno*1 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            C$ 0.50
                        </td>
                        <td>
                            <input class="form-control" type="number" v-model.number="form.cantidad_cincuenta_centavos">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cincuenta_centavos"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            C$ {{form.cantidad_cincuenta_centavos*0.5 |formatMoney(2)}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            C$ 0.25
                        </td>
                        <td>
                            <input class="form-control" type="number"
                                   v-model.number="form.cantidad_veinticinco_centavos">
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
                            <input class="form-control" type="number" v-model.number="form.cantidad_diez_centavos">
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


            <div class="col-sm-4">

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
                            <input class="form-control" v-model.number="form.cantidad_cien_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cien_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.cantidad_cien_dol*100 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            $ 50.00
                        </td>
                        <td>
                            <input class="form-control" v-model.number="form.cantidad_cincuenta_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cincuenta_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.cantidad_cincuenta_dol*50 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            $ 20.00
                        </td>
                        <td>
                            <input class="form-control" v-model.number="form.cantidad_veinte_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_veinte_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.cantidad_veinte_dol*20 |formatMoney(2)}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            $ 10.00
                        </td>
                        <td>
                            <input class="form-control" v-model.number="form.cantidad_diez_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_diez_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.cantidad_diez_dol*10 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            $ 5.00
                        </td>
                        <td>
                            <input class="form-control" v-model.number="form.cantidad_cinco_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_cinco_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.cantidad_cinco_dol*5 |formatMoney(2)}}
                        </td>
                    </tr>


                    <tr>
                        <td>
                            $ 1.00
                        </td>
                        <td>
                            <input class="form-control" v-model.number="form.cantidad_uno_dol">
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.cantidad_uno_dol"
                                        v-text="message">
                                </li>
                            </ul>
                        </td>
                        <td>
                            $ {{form.cantidad_uno_dol |formatMoney(2)}}
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
                        <td> {{form.tasa_cambio}}</td>
                        <td class="item-footer">
                            <strong>C$ {{form.efectivo_dolares_equivalente|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>


            <div class="col-sm-4">
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
                    <template v-for="(item, index) in form.bancos_montos">
                        <tr>
                            <td>
                                {{item.descripcion}}
                            </td>
                            <td>
                                {{item.monto_cheque_banco|formatMoney(2)}}
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
                    <template v-for="(item, index) in form.bancos_montos_trans">
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
                    <tr>
                        <td> Efectivo Inicial</td>
                        <td class="item-footer">
                            <input class="form-control" min="0" placeholder="Digita el monto de gastos" type="number"
                                   v-model.number="form.efectivo_inicial">
                        </td>
                    </tr>
                    <tr>
                        <td> Total A Depositar</td>
                        <td class="item-footer">
                            <strong>C$ {{form.total_a_depositar|formatMoney(2)}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Costos / Gastos</td>
                        <input class="form-control" min="0" placeholder="Digita el monto de gastos" type="number"
                               v-model.number="form.costos_gastos">
                    </tr>
                    <tr>
                        <td> DIFERENCIA</td>
                        <td class="item-footer">
                            <strong>C$ {{form.sobrante_faltante|formatMoney(2)}}</strong>
                        </td>
                    </tr>


                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>


            </div>
        </div>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label for>Observaciones arqueo</label>
                <input class="form-control" type="text" v-model="form.observaciones">
                <ul class="error-messages">
                    <li
                            :key="message"
                            v-for="message in errorMessages.observaciones"
                            v-text="message"
                    ></li>
                </ul>
            </div>
        </div>

        <b-card-footer>
            <router-link :to="{ name: 'cajabanco-arqueos' }">
                <b-button class="mx-1" type="button" variant="secondary">Cancelar</b-button>
            </router-link>
            <b-button
                    :disabled="btnAction !== 'Registrar'"
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
    import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
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
            BCol
        },
        directives: {
            'b-tooltip': VBTooltip,
        },
        data() {
            return {
                loading: true,
                existe_arqueo: false,
                format: "dd-MM-yyyy",
                vendedores: [],
                fechax: new Date(),

                form: {
                    efectivo_inicial: [],
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

                    detalleFacturas: [],


                    total_retencion: 0,
                    monto_pagado_minuta: 0,
                    monto_pagado_minuta_me: 0,
                    monto_pagado_efectivo: 0,
                    monto_pagado_efectivo_me: 0,
                    monto_pagado_tarjeta: 0,
                    monto_pagado_tarjeta_me: 0,
                    monto_pagado_cheque: 0,
                    monto_pagado_cheque_me: 0,
                    monto_pagado_cheque_bancos: 0,
                    monto_pagado_transferencia: 0,
                    monto_pagado_transferencia_me: 0,
                    monto_pagado: 0,
                    monto_pagado_me: 0,
                    monto_credito: 0,
                    monto_credito_me: 0,
                    monto_ingreso: 0,
                    monto_ingreso_me: 0,
                    total_a_depositar: 0,
                    sobrante_faltante: 0,

                    monto_pagado_tran_bancos_cord: 0,
                    monto_pagado_tran_bancos_dol: 0,
                    monto_pagado_tran_bancos_total_cord: 0,
                    costos_gastos: 0

                },
                btnAction: "Registrar",
                btnActionDraft: "Guardar Borrador",
                errorMessages: []
            };
        },
        computed: {
            total_retencion() {
                this.form.total_retencion = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_retencion : 0))
                }, 0)
                return this.form.total_retencion;
            },
            total_minutas() {
                this.form.monto_pagado_minuta = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_minuta : 0))
                }, 0)
                return this.form.monto_pagado_minuta;
            },
            total_minutas_me() {
                this.form.monto_pagado_minuta_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_minuta_me : 0))
                }, 0)
                return this.form.monto_pagado_minuta_me;
            },
            total_efectivo() {
                this.form.monto_pagado_efectivo = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_efectivo : 0))
                }, 0)
                return this.form.monto_pagado_efectivo;
            },
            total_efectivo_me() {
                this.form.monto_pagado_efectivo_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_efectivo_me : 0))
                }, 0)
                return this.form.monto_pagado_efectivo_me;
            },
            total_tarjeta() {
                this.form.monto_pagado_tarjeta = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_tarjeta : 0))
                }, 0)
                return this.form.monto_pagado_tarjeta;
            },
            total_tarjeta_me() {
                this.form.monto_pagado_tarjeta_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_tarjeta_me : 0))
                }, 0)
                return this.form.monto_pagado_tarjeta_me;
            },
            total_cheques() {
                this.form.monto_pagado_cheque = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_cheque : 0))
                }, 0)
                return this.form.monto_pagado_cheque;
            },
            total_cheques_me() {
                this.form.monto_pagado_cheque_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_cheque_me : 0))
                }, 0)
                return this.form.monto_pagado_cheque_me;
            },
            total_cheques_banco() {
                this.form.monto_pagado_cheque_bancos = this.form.bancos_montos.reduce((carry, item) => {
                    return (carry + Number(item.monto_cheque_banco))
                }, 0)


                return this.form.monto_pagado_cheque_bancos;
            },
            total_transferencias() {
                this.form.monto_pagado_transferencia = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_transferencia : 0))
                }, 0)


                return this.form.monto_pagado_transferencia;
            },
            total_transferencias_me() {
                this.form.monto_pagado_transferencia_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_transferencia_me : 0))
                }, 0)


                return this.form.monto_pagado_transferencia_me;
            },

            total_transferencias_banco() {
                this.form.monto_pagado_tran_bancos_cord = this.form.bancos_montos_trans.reduce((carry, item) => {
                    return (carry + Number(item.monto_tran_cordobas))
                }, 0)

                this.form.monto_pagado_tran_bancos_dol = this.form.bancos_montos_trans.reduce((carry, item) => {
                    return (carry + Number(item.monto_tran_dolares))
                }, 0)

                this.form.monto_pagado_tran_bancos_total_cord = Number(this.form.monto_pagado_tran_bancos_cord + Number((this.form.monto_pagado_tran_bancos_dol * this.form.tasa_cambio).toFixed(2)));


                this.form.total_a_depositar = (this.form.monto_pagado_cheque_bancos + this.form.efectivo_cordobas + this.form.efectivo_dolares_equivalente);
                this.form.sobrante_faltante = Number((this.form.total_a_depositar + this.form.monto_pagado_tran_bancos_total_cord -
                    this.form.monto_pagado_minuta -
                    this.form.monto_pagado_efectivo -
                    // parseFloat(this.form.monto_pagado_efectivo_me / this.form.t_cambio) -
                    this.form.monto_pagado_efectivo_me -
                    this.form.monto_pagado_tarjeta -
                    this.form.monto_pagado_cheque -
                    this.form.monto_pagado_transferencia).toFixed(2));
                this.form.sobrante_faltante = this.form.sobrante_faltante - (this.form.costos_gastos * -1);


                return this.form.monto_pagado_tran_bancos_cord;
            },

            total_pagado() {
                this.form.monto_pagado = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado : 0))
                }, 0)
                return this.form.monto_pagado;
            },
            total_pagado_me() {
                this.form.monto_pagado_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.monto_pagado_me : 0))
                }, 0)
                return this.form.monto_pagado_me;
            },
            total_credito() {
                this.form.monto_credito = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_deuda : 0))
                }, 0)
                return this.form.monto_credito;
            },
            total_credito_me() {
                this.form.monto_credito_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_deuda_me : 0))
                }, 0)
                return this.form.monto_credito_me;
            },
            total() {
                this.form.monto_ingreso = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_total : 0))
                }, 0)
                return this.form.monto_ingreso;
            },
            total_me() {
                this.form.monto_ingreso_me = this.form.detalleFacturas.reduce((carry, item) => {
                    return (carry + Number(item.estadox !== 0 ? item.mt_total_me : 0))
                }, 0)
                return this.form.monto_ingreso_me;
            },

            total_efectivo_cordobas() {
                this.form.efectivo_cordobas =
                    this.form.cantidad_mil * 1000 +
                    this.form.cantidad_quinientos * 500 +
                    this.form.cantidad_doscientos * 200 +
                    this.form.cantidad_cien * 100 +
                    this.form.cantidad_cincuenta * 50 +
                    this.form.cantidad_veinte * 20 +
                    this.form.cantidad_diez * 10 +
                    this.form.cantidad_cinco * 5 +
                    this.form.cantidad_uno +
                    this.form.cantidad_cincuenta_centavos * 0.5;
                return this.form.efectivo_cordobas;
            },
            total_efectivo_dolares() {
                this.form.efectivo_dolares =

                    this.form.cantidad_cien_dol * 100 +
                    this.form.cantidad_cincuenta_dol * 50 +
                    this.form.cantidad_veinte_dol * 20 +
                    this.form.cantidad_diez_dol * 10 +
                    this.form.cantidad_cinco_dol * 5 +
                    this.form.cantidad_uno_dol;

                this.form.efectivo_dolares_equivalente = Number((this.form.efectivo_dolares * this.form.tasa_cambio).toFixed(2));
                return this.form.efectivo_dolares;
            },
        },
        methods: {
            limpiarArqueo() {
                let self = this;
                self.form.detalleFacturas = [];
            },
            cargarArqueo() {
                let self = this;

                if (self.form.vendedor && self.form.fecha_arqueo) {
                    self.loading = true;
                    self.errorMessages.fecha_arqueo = '';
                    orden.obtenerDatosArqueo({
                        fecha_arqueo: self.form.fecha_arqueo,
                        id_vendedor: self.form.vendedor.id_vendedor
                    }, data => {
                        //console.log(data.arqueo);
                        if (data.arqueo !== null && data.arqueo !== '') {
                            self.form.detalleFacturas = [];
                            self.form.bancos_montos = [];
                            self.form.bancos_montos_trans = [];
                            self.form.detalleFacturas = data.arqueo;
                            self.existe_arqueo = data.existe_arqueo;
                            if (self.existe_arqueo) {
                                self.errorMessages.fecha_arqueo = ['Ya existe un arqueo registrado para esta fecha y vendedor'];
                            } else {
                                self.errorMessages.fecha_arqueo = '';
                            }

                            self.form.bancos_montos = data.bancos;
                            self.form.bancos_montos_trans = data.bancos_min_transf;
                            self.loading = false;
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encuentran datos para este vendedor y esta fecha;',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            self.form.detalleFacturas = [];
                            self.loading = false;
                        }
                        self.loading = false;
                    }, err => {
                        self.loading = false;
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Debe seleccionar una fecha y un vendedor.',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    self.loading = false;
                }
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
                                    text: 'No se encuentra tasa de cambio para este fecha.',
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
                var self = this;
                orden.nuevo({}, data => {
                    self.vendedores = data.vendedores;
                    self.form.tasa_paralela = data.tasa_paralela;
                    self.form.tasa_cambio = data.tasa_cambio;
                    self.loading = false;

                })

            },

            registrar() {
                var self = this;
                if (!self.existe_arqueo) {
                    self.btnAction = "Registrando, espere....";

                    self.$swal.fire({
                        title: 'Esta seguro de registrar el arqueo diario?',
                        text: "Revise bien los datos",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, registrar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            orden.registrar(
                                self.form,
                                data => {
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'CheckIcon',
                                                text: 'Arqueo diaria registrado correctamente.',
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
                                    self.btnAction = "Registrar";
                                }
                            );
                        } else {
                            self.btnAction = "Registrar";
                        }
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Ya existe un arqueo registrado con esta fecha y vendedor, si desea sobreescribirlo, primero anule el arqueo anterior.',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            }
        },
        mounted() {
            this.nuevo();
        }
    };
</script>
<style>
    .btn-agregar {
        margin-top: 33px;
    }

    .check-label2 {
        margin-top: 30px;
    }

    .btn-generar {
        margin-top: 33px;
    }
</style>
