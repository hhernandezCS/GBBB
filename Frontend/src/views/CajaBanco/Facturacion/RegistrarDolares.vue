<template>
    <b-card>
        <b-alert
                show
                variant="success"
        >
            <div class="alert-body">
                <span><strong>Datos del Cliente</strong></span>
            </div>
        </b-alert>

        <template>
            <b-row>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label> Cliente</label>
                        <div class="form-group">
                            <v-select
                                    :filterable="false"
                                    :options="clientes"
                                    @input="seleccionarCliente"
                                    @search="onSearch"
                                    label="text"
                                    style="width: 100%;"
                                    v-model="form.factura_cliente"
                            >
                                <!--v-on:input="$emit('input', $event.target.value)" Emitir evento a v-model de vue-select-->
                                <template slot="no-options">
                                    Escriba para buscar un cliente
                                </template>
                            </v-select>
                        </div>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_cliente"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Tipo Cliente</label>
                        <b-form-select v-model.number="form.tipo_identificacion">
                            <option value="1">
                                Natural
                            </option>
                            <option value="2">
                                Jurídico
                            </option>
                        </b-form-select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="form-group text-center">
                            <b-button
                                    @click="abrirModal"
                                    class="btn-agregar"
                                    v-b-tooltip.hover.top="'Registrar cliente nuevo'"
                                    variant="success"
                            >
                                <feather-icon icon="PlusCircleIcon"></feather-icon>
                                Nuevo
                            </b-button>
                        </div>
                    </div>
                </div>

                <template v-if="form.id_tipo !== 4">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> Identificación</label>
                            <input class="form-control" placeholder="Número Identificación"
                                   v-model="form.identificacion">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.identificacion"
                                        v-text="message"/>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                </template>
                <template v-else>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label> Listado de Documentos pendientes</label>
                                <div class="form-group">
                                    <v-select :allow-empty="false" :options="cuentas"
                                              :searchable="true"
                                              label="textdol"
                                              placeholder="Selecciona una cuenta para pagar"
                                              ref="cuenta"
                                              track-by="id_cuentaxcobrar"
                                              v-model="detalleForm.cuentax"
                                              v-on:input="cargar_detalles_cuenta()"
                                    >
                                        <template slot="no-options">
                                           No se encontraron documentos pendientes para este cliente
                                        </template>
                                    </v-select>
                                </div>
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.cuentax" v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for>Saldo abonado $</label>
                                <label class="form-control"> {{detalleForm.cuentax?detalleForm.cuentax.saldo_actual_me:0 |
                                    formatMoney(2)}}</label>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for>Monto a facturar $</label>
                                <input @change="detalleForm.monto_me = detalleForm.cuentax? Math.max(Math.min(Number(!isNaN(detalleForm.monto_me)?detalleForm.monto_me.toFixed(2):0), Number(Number(detalleForm.cuentax.saldo_actual_me).toFixed(2))), 1):0"
                                       class="form-control" min="0" ref="abono" type="number"
                                       v-model.number="detalleForm.monto_me">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.monto"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for>&nbsp;</label>
                            <b-button @click="agregarDetalleRecibo" class="btn-agregar" ref="agregar" variant="info">Agregar
                            </b-button>
                        </div>
                    </div>


                        <div class="col-sm-12">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.detalleCuentasxCobrar"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>

                            <table class="table table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th style="min-width:50px" v-if="form.tipo_recibo ==='3'">Documento Origen</th>
                                    <th style="min-width:300px">Concepto</th>
                                    <th style="min-width:100px">Subtotal $</th>
                                    <th style="min-width:100px">Retención IR $</th>
                                    <th style="min-width:100px">Retención IMI $</th>
                                    <th style="min-width:150px">Monto Abono $</th>
                                    <th colspan="2" style="min-width:300px" v-if="form.tipo_recibo ==='3'">Saldo Pendiente $</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(item, index) in form.detalleCuentasxCobrar">
                                    <tr>
                                        <td>
                                            <b-button @click="eliminarLineaRecibo(item, index)" v-b-tooltip.hover.top="'Eliminar línea'"
                                                      variant="outline-danger">
                                                <feather-icon icon="TrashIcon"></feather-icon>
                                            </b-button>
                                        </td>
                                        <td style="min-width:150px" v-if="form.tipo_recibo ==='3'">
                                            <input class="form-control" disabled
                                                   v-model="item.cuentax.no_documento_origen">
                                        </td>

                                        <td>
                                            {{item.descripcion_pago}}
                                        </td>

                                        <td class="text-center" style="width: 12%">
                                            <input class="form-control" disabled min="0"
                                                   v-model.number="item.monto_subtotal">
                                        </td>
                                        <td class="text-center" style="width: 12%">
                                            <input class="form-control"

                                                   v-b-tooltip.hover.top="'No. Documento Exoneración:'+item.doc_exoneracion_ir"
                                                   v-model.number="item.monto_retencion_ir">
                                        </td>
                                        <td class="text-center" style="width: 12%">
                                            <input class="form-control"

                                                   v-b-tooltip.hover.top="'No. Documento Exoneración:'+item.doc_exoneracion_imi"
                                                   v-model.number="item.monto_retencion_imi">
                                        </td>
                                        <td class="text-center">
                                            <!-- Para recibos por cobro a clientes-->
                                            <input @change="establecerConcepto(item)" class="form-control"
                                                   min="0" v-if="form.tipo_recibo ==='1'" v-model.number="item.monto_me">
                                            <!-- Para recibos por anticipos de clientes-->
                                            <input class="form-control" min="0"
                                                   readonly v-if="form.tipo_recibo ==='2'" v-model.number="item.monto_me">
                                            <b-alert show variant="danger">
                                                <ul class="error-messages">
                                                    <li
                                                            :key="message"
                                                            v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.monto`]"
                                                            v-text="message">
                                                    </li>
                                                </ul>
                                            </b-alert>

                                        </td>
                                        <td class="text-center" colspan="2" v-if="form.tipo_recibo ==='1'">
                                            <strong>$ {{calcularSaldoX(item)| formatMoney(2)}}</strong>
                                        </td>

                                    </tr>
                                    <tr></tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-right"><strong> Total a pagar $</strong></td>
                                    <td class="text-center">
                                        <strong>{{total_a_pagar| formatMoney(2)}}</strong>
                                    </td>
                                    <td class="text-right" v-if="form.tipo_recibo ==='1'"><strong> Total saldo pendiente $</strong>
                                    </td>
                                    <td class="text-center" v-if="form.tipo_recibo ==='1'">
                                        <strong>{{form.saldo_total_me| formatMoney(2)}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-right"><strong> Total a pagar C$</strong></td>
                                    <td class="text-center">
                                        <strong>{{form.monto_total| formatMoney(2)}}</strong>
                                    </td>
                                    <td class="text-right" v-if="form.tipo_recibo ==='1'"><strong> Total saldo pendiente C$</strong>
                                    </td>
                                    <td class="text-center" v-if="form.tipo_recibo ==='1'">
                                        <strong>{{form.saldo_total| formatMoney(2)}}</strong>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>


                </template>

            </b-row>
        </template>

        <b-alert
                class="mb-2 mt-2"
                show
                variant="success"
        >
            <div class="alert-body">
                <span><strong>Datos de la Factura</strong></span>
            </div>
        </b-alert>

        <template v-if="proformaFormHeader.es_nuevo === true">
            <b-row>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label> Sucursal</label>
                        <div class="form-group">
                            <!-- <typeahead :initial="form.factura_sucursal" :trim="80" :url="sucursalesBusquedaURL" @input="seleccionarSucursal" style="width: 100%;"></typeahead>-->
                            <b-form-select
                                    @change="seleccionarSucursal"
                                    v-model="form.factura_sucursal"
                            >
                                <option
                                        :key="sucursal.id_sucursal"
                                        :value="sucursal"
                                        v-for="sucursal in sucursales"
                                >{{ sucursal.descripcion }}
                                </option>
                            </b-form-select>

                        </div>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_sucursal"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Serie</label>
                        <input
                                class="form-control"
                                disabled
                                placeholder="Serie"
                                v-model="form.serie"
                        >
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.serie"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Consecutivo</label>
                        <input
                                class="form-control"

                                placeholder="Consecutivo"
                                v-model="form.no_factura"
                        >
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.no_documento"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for>Bodega</label>
                        <v-select
                                :options="bodegas"
                                @input="seleccionarBodega()"
                                label="descripcion"
                                v-model="form.factura_bodega"
                        >
                            <template slot="no-options">
                                No se encontraron registros
                            </template>
                        </v-select>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_bodega"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Tipo Factura</label>
                        <b-form-select @change="seleccionarTipo" v-model.number="form.id_tipo">
                            <option value="1">Contado</option>
                            <option :disabled="!clienteCredito" value="2">Crédito</option>
                            <option value="4">Anticipo</option>
                        </b-form-select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label> Plazo Crédito</label>
                        <b-form-select
                                :disabled="!(form.id_tipo===2)"
                                @change="obtenerTCParalela"
                                v-model.number="form.dias_credito"
                        >
                            <option
                                    :disabled="(form.id_tipo===2)"
                                    value="0"
                            >
                                N/A
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=8)"
                                    value="8"
                            >
                                8 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=15)"
                                    value="15"
                            >
                                15 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=30)"
                                    value="30"
                            >
                                30 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=45)"
                                    value="45"
                            >
                                45 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=60)"
                                    value="60"
                            >
                                60 días
                            </option>
                        </b-form-select>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.plazo_credito"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label> Vendedor</label>
                        <!-- <div class="form-group">
                                       <typeahead :initial="form.factura_vendedor" :trim="80" :url="vendedoresBusquedaURL" @input="seleccionarVendedor" style="width: 100%;"></typeahead>

                                     </div>-->
                        <v-select
                                :disabled="true"
                                :options="vendedores"
                                label="nombre_completo"
                                v-model="form.factura_vendedor"
                        >
                            <template slot="no-options"/>
                        </v-select>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_vendedor"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for>Fecha Emitida</label>


                      <b-form-group>
                        <flat-pickr
                            class="form-control"
                            :config="{ dateFormat: 'Y-m-d'}"
                            placeholder="f. emitida"
                            v-model="form.f_factura"
                        />
                      </b-form-group>


                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.f_factura"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for>Fecha Vencimiento</label>
                        <input
                                class="form-control"
                                disabled
                                type="text"
                                v-model="form.f_vencimiento"
                        >
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for>T/C</label>
                        <input
                                class="form-control"

                                type="text"
                                v-model="form.t_cambio"
                        >
                    </div>
                </div>


                <div class="col-sm-4 ">
                    <label for=""></label>
                    <b-form-checkbox
                            @input="deseleccionar"
                            class="mx-lg-1 mb-sm-1 mt-sm-1"
                            v-model="form.proforma_especifica"
                    >
                        Tiene proforma
                    </b-form-checkbox>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label> Proforma</label>
                        <div class="form-group">
                            <v-select
                                    :filterable="false"
                                    :options="proformas"
                                    @input="seleccionarProforma"
                                    @search="onSearchP"
                                    label="text"
                                    style="width: 100%;"
                                    v-if="form.proforma_especifica"
                                    v-model="form.proformasBusqueda"
                            >
                                <!--v-on:input="$emit('input', $event.target.value)" Emitir evento a v-model de vue-select-->
                                <template slot="no-options">
                                    Escriba para buscar una proforma
                                </template>
                            </v-select>

                            <input
                                    class="form-control"
                                    disabled
                                    v-if="!form.proforma_especifica"
                            >
                        </div>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.proformasBusqueda"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <!--<div class="col-sm-3"> Cargar productos desde una proforma, manualmente
                    <label for=""></label>
                    <div class="form-group">
                        <div class="form-group">
                            <b-button
                                    variant="success"
                                    @click="cargarProductosProforma"
                            >
                                Cargar Productos
                            </b-button>
                        </div>
                    </div>
                </div>-->

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for>Observaciones</label>
                        <input
                                class="form-control"
                                type="text"
                                v-model="form.observacion"
                        >
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.observacion"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

            </b-row>
        </template>
        <template v-else>
            <b-row>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label> Sucursal</label>
                        <div class="form-group">
                            <!-- <typeahead :initial="form.factura_sucursal" :trim="80" :url="sucursalesBusquedaURL" @input="seleccionarSucursal" style="width: 100%;"></typeahead>-->
                            <v-select
                                    :disabled="true"
                                    :options="sucursales"
                                    label="descripcion"
                                    v-model="form.proforma_sucursal"
                            >
                                <template slot="no-options">
                                    No se encontraron registros
                                </template>
                            </v-select>

                        </div>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_sucursal"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Serie</label>
                        <input
                                class="form-control"
                                disabled
                                placeholder="Serie"
                                v-model="form.proforma_sucursal.serie"
                        >
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.serie"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Consecutivo</label>
                        <input
                                class="form-control"
                                disabled
                                placeholder="Consecutivo"
                                v-model="no_documento"
                        >
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.no_documento"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>Bodega</label>
                        <v-select
                                :disabled="true"
                                :options="bodegas"
                                @input="seleccionarBodega()"
                                label="descripcion"
                                v-model="form.proforma_bodega"
                        >
                            <template slot="no-options">
                                No se encontraron registros
                            </template>
                        </v-select>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_bodega"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Tipo Factura</label>
                        <b-form-select
                                @change="seleccionarTipo"
                                v-model.number="proformaFormHeader.id_tipo"
                        >
                            <option value="1">
                                Contado
                            </option>
                            <option
                                    :disabled="!clienteCredito"
                                    value="2"
                            >
                                Crédito
                            </option>
                        </b-form-select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label> Plazo Crédito</label>
                        <b-form-select
                                :disabled="!(form.id_tipo===2)"
                                @change="obtenerTCParalela"
                                v-model.number="form.dias_credito"
                        >
                            <option
                                    :disabled="(form.id_tipo===2)"
                                    value="0"
                            >
                                N/A
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=8)"
                                    value="8"
                            >
                                8 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=15)"
                                    value="15"
                            >
                                15 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=30)"
                                    value="30"
                            >
                                30 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=45)"
                                    value="45"
                            >
                                45 días
                            </option>
                            <option
                                    :disabled="!(plazo_maximo_credito>=60)"
                                    value="60"
                            >
                                60 días
                            </option>
                        </b-form-select>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.plazo_credito"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label> Vendedor</label>
                        <!-- <div class="form-group">
                                       <typeahead :initial="form.factura_vendedor" :trim="80" :url="vendedoresBusquedaURL" @input="seleccionarVendedor" style="width: 100%;"></typeahead>

                                     </div>-->
                        <v-select
                                :disabled="true"
                                :options="vendedores"
                                label="nombre_completo"
                                v-model="form.proforma_vendedor"
                        >
                            <template slot="no-options">
                                No se encontraron registros
                            </template>
                        </v-select>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.factura_vendedor"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>Fecha Emitida</label>

                      <b-form-group>
                      <flat-pickr
                          class="form-control"
                          :config="{ dateFormat: 'Y-m-d'}"
                          placeholder="f. emitida"
                          v-model="form.f_factura"
                      />
                      </b-form-group>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.f_factura"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for>Fecha Vencimiento</label>
                        <input
                                class="form-control"
                                disabled
                                type="text"
                                v-model="form.f_vencimiento"
                        >
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for>T/C</label>
                        <input
                                class="form-control"
                                disabled
                                type="text"
                                v-model="form.tasa_paralela"
                        >
                    </div>
                </div>

                <div class="col-sm-10">
                    <div class="form-group">
                        <label for>Observaciones</label>
                        <input
                                class="form-control"
                                type="text"
                                v-model="proformaFormHeader.observacion"
                        >
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.observacion"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <div class="col-sm-2">
                    <b-form-checkbox
                            @input="deseleccionar"
                            class="mx-lg-1 mb-sm-1 mt-sm-1"
                            v-model="form.proforma_especifica"
                    >
                        Tiene proforma
                    </b-form-checkbox>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label> Proforma</label>
                        <div class="form-group">
                            <v-select
                                    :trim="80"
                                    :url="proformasBusquedaURL"
                                    @input="seleccionarProforma"
                                    label="no_documento"
                                    style="width: 100%;"
                                    v-if="form.proforma_especifica"
                                    v-model="form.proformasBusqueda"
                            />
                            <input
                                    class="form-control"
                                    disabled
                                    v-if="!form.proforma_especifica"
                            >
                        </div>
                        <b-alert
                                show
                                variant="danger"
                        >
                            <ul class="error-messages">
                                <li
                                        :key="message"
                                        v-for="message in errorMessages.proformasBusqueda"
                                        v-text="message"
                                />
                            </ul>
                        </b-alert>

                    </div>
                </div>
                <!--<div class="col-sm-1"> cargar productos desde proforma manualmente
                    <div class="form-group">
                        <div class="form-group">
                            <b-button
                                    variant="success"
                                    @click="cargarProductosProforma"
                            >Cargar
                                Productos
                            </b-button>
                        </div>
                    </div>
                </div>-->

            </b-row>
        </template>

        <div v-if="!form.factura_bodega">
            <b-alert
                    class="mb-2 mt-2"
                    show
                    variant="success"
            >
                <div class="alert-body">
                    <span>Se requiere que seleccione una bodega para continuar.</span>
                </div>
            </b-alert>
        </div>

        <b-alert
                class="mb-2 mt-2"
                show
                variant="success"
        >
            <div class="alert-body">
                <span><strong>Detalle de factura</strong></span>
            </div>
        </b-alert>

        <b-row>
            <div class="col-sm-6">
                <div class="form-group">
                    <label> Producto</label>
                    <div class="form-group">
                        <v-select
                                :options="productos"
                                :searchable="true"
                                label="text"
                                placeholder="Selecciona un producto"
                                ref="producto"
                                track-by="id_producto"
                                v-model="detalleForm.productox"
                                v-on:input="cargar_detalles_producto()"
                        >
                            <template slot="no-options">
                                No se encontraron registros
                            </template>
                        </v-select>
                    </div>
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.productox"
                                    v-text="message"
                            />
                        </ul>
                    </b-alert>

                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <label for>Cantidad</label>
                    <input
                            @change="detalleForm.cantidad = Math.max(Math.min(detalleForm.cantidad, (!isNaN(detalleForm.productox.cantidad_disponible))?detalleForm.productox.cantidad_disponible:0 ), 0)"
                            @keydown.enter="cambiarFocoCantidad"
                            class="form-control"
                            min="0"
                            ref="cantidad"
                            type="number"
                            v-model.number="detalleForm.cantidad"
                    >
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.cantidadx"
                                    v-text="message"
                            />
                        </ul>
                    </b-alert>

                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for>Precio Lista $ </label>
                    <input class="form-control" v-model.number="detalleForm.precio_sugerido_me">
                    <b-alert show variant="danger">
                        <ul class="error-messages">
                            <li
                                    :key="message"
                                    v-for="message in errorMessages.preciox"
                                    v-text="message"
                            />
                        </ul>
                    </b-alert>

                </div>
            </div>

            <!--<div class="col-sm-2">
                <div class="form-group">
                    <label for>Tipo de Afectación</label>
                    <v-select
                            v-model="detalleForm.afectacionx"
                            :disabled="(!detalleForm.productox)?true:detalleForm.productox.tipo_producto===2"
                            label="descripcion"
                            :options="afectaciones"
                    >
                        <template slot="no-options">
                            No se encontraron registros
                        </template>
                    </v-select>
                </div>
            </div>-->

            <div class="col-sm-2">
                <div class="form-group">
                    <label for>&nbsp;</label>
                    <b-button
                            @click="agregarDetalle"
                            class="btn-agregar"
                            ref="agregar"
                            v-b-tooltip.hover.top="'Agregar producto al detalle'"
                            variant="info"
                    >
                        <feather-icon icon="PlusCircleIcon"></feather-icon>
                        Agregar
                    </b-button>
                </div>
            </div>

        </b-row>

        <b-row>
            <div class="col-sm-12">
                <b-alert show variant="danger">
                    <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages.detalleProductos"
                                v-text="message"
                        />
                    </ul>
                </b-alert>

                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th class="table-number"></th>
                        <th>Producto</th>
                        <!--                        <th>Afectación</th>-->
                        <!--                        <th>% Ajuste</th>-->
                        <th class="table-number">Cantidad</th>
                        <!-- <th >U/M</th>-->
                        <!--                        <th>P. Lista C$</th>-->
                        <th class="table-number">Descuento %</th>
                        <th>Descuento $</th>
                        <!--                        <th>Ajuste C$</th>-->
                        <th>Precio Unit. $</th>
                        <!--<th >Monto IVA U$</th>-->
                        <th>Valor $</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(item, index) in form.detalleProductos">
                        <tr>
                            <td style="width:1%">
                                <b-button
                                        @click="eliminarLinea(item, index)"
                                        variant="danger"
                                >
                                    <feather-icon icon="TrashIcon"/>
                                </b-button>
                            </td>
                            <td>
                                <input
                                        class="form-control"
                                        disabled
                                        v-model="item.productox.descripcion"
                                >
                                <b-alert
                                        show
                                        variant="danger"
                                >
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages[`detalleProductos.${index}.productox.id_producto`]"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </td>
                            <!--<td style="width: 12%">
                                <input
                                        v-model="item.afectacionx.descripcion"
                                        class="form-control"
                                        disabled
                                >

                                &lt;!&ndash;<v-select
                                                      :disabled="true"
                                                      v-model="item.afectacionx"
                                                      label="descripcion"
                                                      :options="afectaciones"
                                                      v-on:input="calcularAjuste(item)"
                                              ></v-select>&ndash;&gt;
                                <b-alert
                                        variant="danger"
                                        show
                                >
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages[`detalleProductos.${index}.afectacionx.id_afectacion`]"
                                                :key="message"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </td>-->

                            <!--<td style="width: 5%">
                                {{ item.p_ajuste +'%' }}
                            </td>-->

                            <td style="width:1%">
                                <input @change="item.cantidad = Math.max(Math.min(Math.round(item.cantidad), item.productox.cantidad_disponible), 1)"
                                       class="form-control" min="1" type="number"
                                       v-model.number="item.cantidad">
                                <b-alert
                                        show
                                        variant="danger"
                                >
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages[`detalleProductos.${index}.cantidad`]"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </td>

                            <!--<td style="width: 3%">
                                             {{item.productox.unidad_medida}}
                                             <ul class="error-messages">
                                               <li
                                                       :key="message"
                                                       v-for="message in errorMessages[`detalleProductos.${index}.unidad_medida`]"
                                                       v-text="message">
                                               </li>
                                             </ul>
                                           </td>-->

                            <!--<td style="width: 10%">
                                <input
                                        v-model.number="item.precio"
                                        class="form-control"
                                        disabled
                                >
                                <b-alert
                                        variant="danger"
                                        show
                                >
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages[`detalleProductos.${index}.precio`]"
                                                :key="message"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </td>-->

                            <td style="width:1%">
                                <!--@change="item.p_descuento = Math.max(Math.min(item.p_descuento, 20), 0)"-->
                                <input
                                        :disabled="item.p_ajuste>0"
                                        class="form-control"
                                        v-model.number="item.p_descuento"
                                >
                                <b-alert
                                        show
                                        variant="danger"
                                >
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages[`detalleProductos.${index}.p_descuento`]"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </td>

                            <td style="width:12%">
                                <strong>{{ calcular_montos(item)| formatMoney(2) }}</strong>
                            </td>

                            <!--<td style="width: 8%"><strong>C$ {{ item.mt_ajuste| formatMoney(2) }}</strong>
                            </td>-->

                            <td style="width:1%">
                                <strong>{{ item.p_unitario }}</strong>
                            </td>

                            <td style="width:12%">
                                <strong>{{ item.total_sin_iva| formatMoney(2) }}</strong>
                            </td>

                        </tr>
                        <tr/>
                    </template>
                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="5">
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.error_general"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </td>
                        <td>Subtotal</td>
                        <td><strong>{{ total_subt_sin_iva | formatMoney(2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"/>
                        <td>No Doc. Exoneración</td>
                        <td><input
                                :disabled="form.aplicaIVA"
                                class="form-control"
                                v-model="form.doc_exoneracion"
                        ></td>
                        <td>
                            <b-form-checkbox
                                    class="mx-lg-1 mb-sm-1 mt-sm-1"
                                    v-model="form.aplicaIVA"
                            >
                                IVA
                            </b-form-checkbox>
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.doc_exoneracion"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </td>
                        <td><strong>{{ total_impuesto | formatMoney(2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"/>
                        <td>No. Documento:</td>
                        <td>
                            <input
                                    :disabled="!form.aplicaIR"
                                    class="form-control"
                                    v-model="form.doc_exoneracion_ir"
                            >
                        </td>
                        <td>
                            <b-form-checkbox

                                    class="mx-lg-1 mb-sm-1 mt-sm-1"
                                    v-model="form.aplicaIR"
                            >
                                IR (2%)
                            </b-form-checkbox>
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.doc_exoneracion_ir"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </td>
                        <td><strong>$ {{ total_retencion | formatMoney(2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"/>
                        <td>No. Documento:</td>
                        <td>
                            <input
                                    :disabled="!form.aplicaIMI"
                                    class="form-control"
                                    v-model="form.doc_exoneracion_imi"
                            >
                        </td>
                        <td>
                            <b-form-checkbox

                                    class="mx-lg-1 mb-sm-1 mt-sm-1"
                                    v-model="form.aplicaIMI"
                            >
                                Retención IMI (1%)
                            </b-form-checkbox>
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.doc_exoneracion_imi"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </td>
                        <td><strong>$ {{ total_retencion_imi | formatMoney(2) }}</strong></td>
                    </tr>

                    <tr class="table-active table-light">
                        <td colspan="0"/>
                        <td class="item-footer">
                            <strong>Totales</strong>
                        </td>
                        <td class="item-footer">
                            <strong>{{ total_cantidad }}</strong>
                        </td>
                        <td colspan="0">
                            <!--                            Total Descuento | Ajuste-->
                        </td>
                        <td><strong>{{ total_descuento | formatMoney(2) }}</strong></td>
                        <td><strong><!--C$ {{ total_ajuste | formatMoney(2) }}--></strong></td>
                        <!--<td>Total</td>-->
                        <td><strong>{{ gran_total_cordobas | formatMoney(2) }}</strong></td>
                    </tr>

                    <tr>
                        <td colspan="5"/>
                        <td>
                            Equivalente Córdobas
                        </td>
                        <td><strong>C$ {{ gran_total | formatMoney(2) }}</strong></td>
                    </tr>

                    </tfoot>
                </table>
            </div>
        </b-row>
      <br>
      <br>
      <b-alert show variant="success">
        <div class="alert-body">
          <span><strong>Datos de pago y Resumen</strong></span>
        </div>
      </b-alert>


      <hr>
      <b-row>
        <div class="col-sm-12">
          <b-row>
            <div class="col-sm-6">
              <div class="form-group">
                <label> Método Pago</label>

                <div class="form-group">
                  <v-select :allow-empty="false" :options="vias_pago"
                            :searchable="true"
                            label="descripcion"
                            placeholder="Selecciona un método pago"
                            ref="via_pago"
                            track-by="id_via_pago"
                            v-model="detalleFormPago.via_pagox"

                  >

                  </v-select>
                </div>
                <b-alert show variant="danger">
                  <ul class="error-messages">
                    <li :key="message" v-for="message in errorMessages.via_pagox"
                        v-text="message"></li>
                  </ul>
                </b-alert>

              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label> Moneda</label>
                <div class="form-group">
                  <v-select :allow-empty="false" :options="monedas"
                            :searchable="true"
                            label="descripcion"
                            placeholder="Selecciona una moneda"
                            ref="moneda"
                            track-by="id_moneda"
                            v-model="detalleFormPago.moneda_x"
                  ></v-select>
                </div>
                <b-alert show variant="danger">
                  <ul class="error-messages">
                    <li :key="message" v-for="message in errorMessages.moneda_x"
                        v-text="message"></li>
                  </ul>
                </b-alert>

              </div>
            </div>

            <template v-if="detalleFormPago.via_pagox">

              <div class="col-sm-6"
                   v-if="[1,3,5,6].indexOf(detalleFormPago.via_pagox.id_via_pago) >= 0">
                <div class="form-group">
                  <label> Banco</label>
                  <div class="form-group">
                    <v-select :allow-empty="false" :options="bancos"
                              :searchable="true"
                              label="descripcion"
                              placeholder="Selecciona un método pago"
                              ref="banco"
                              track-by="id_banco"
                              v-model="detalleFormPago.banco_x"
                    ></v-select>
                  </div>
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li :key="message" v-for="message in errorMessages.moneda_x"
                          v-text="message"></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

              <div class="col-sm-6"
                   v-if="[1].indexOf(detalleFormPago.via_pagox.id_via_pago) >= 0">
                <div class="form-group">
                  <label for>Número Minuta</label>
                  <input class="form-control" v-model="detalleFormPago.numero_minuta">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages.numero_minuta"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

              <div class="col-sm-6" v-if="detalleFormPago.via_pagox.id_via_pago === 3">
                <div class="form-group">
                  <label for="numero_minuta">Número Voucher</label>
                  <input class="form-control" id="numero_minuta" v-model="detalleFormPago.numero_minuta">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages.numero_minuta"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

              <div class="col-sm-6" v-if="detalleFormPago.via_pagox.id_via_pago === 4">
                <div class="form-group">
                  <label for="nota_credito">Número Nota Crédito</label>
                  <input class="form-control" id="nota_credito"
                         v-model="detalleFormPago.numero_nota_credito">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages.numero_nota_credito"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

              <div class="col-sm-6" v-if="detalleFormPago.via_pagox.id_via_pago === 5">
                <div class="form-group">
                  <label for="numero_cheque">Número Cheque</label>
                  <input class="form-control" id="numero_cheque" v-model="detalleFormPago.numero_cheque">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages.numero_cheque"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

              <div class="col-sm-6" v-if="detalleFormPago.via_pagox.id_via_pago === 6">
                <div class="form-group">
                  <label for="numero_transferencia">Número Transferencia</label>
                  <input class="form-control" id="numero_transferencia"
                         v-model="detalleFormPago.numero_transferencia">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages.numero_transferencia"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

              <div class="col-sm-6" v-if="detalleFormPago.via_pagox.id_via_pago === 7">
                <div class="form-group">
                  <label for="numero_recibo_pago">Número Recibo Pago Anticipado</label>
                  <input class="form-control" id="numero_recibo_pago"
                         v-model="detalleFormPago.numero_recibo_pago">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages.numero_recibo_pago"
                          v-text="message"
                      ></li>
                    </ul>
                  </b-alert>

                </div>
              </div>

            </template>

            <div class="col-sm-3">
              <div class="form-group">
                <label for="monto">Monto {{
                    detalleFormPago.moneda_x ? detalleFormPago.moneda_x.codigo
                        : ''
                  }}</label>
                <input class="form-control" id="monto" min="0" v-model.number="detalleFormPago.monto">
                <b-alert show variant="danger">
                  <ul class="error-messages">
                    <li
                        :key="message"
                        v-for="message in errorMessages.monto"
                        v-text="message"
                    ></li>
                  </ul>
                </b-alert>

              </div>
            </div>
          </b-row>

          <div class="col-sm-3">
            <div class="form-group">
              <label for>&nbsp;</label>
              <b-button @click="agregarMetodoPago" class="btn-agregar" ref="agregarpago"
                        variant="info">Agregar Pago
              </b-button>
            </div>
          </div>
        </div>

        <div class="col-sm-12 table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th></th>
              <th>Tipo</th>
              <th>Moneda</th>
              <th>Monto</th>
              <th>Banco</th>
              <th>No Referencia</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(item, index) in form.detallePago">
              <tr>
                <td style="width: 2%">
                  <b-button @click="eliminarLineaPago(item, index)" variant="danger">
                    <feather-icon icon="TrashIcon"></feather-icon>
                  </b-button>
                </td>
                <td style="width: 5%">
                  <input class="form-control" disabled
                         v-model="item.via_pagox.descripcion">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detallePagos.${index}.via_pagox.id_via_pago`]"
                          v-text="message">
                      </li>
                    </ul>
                  </b-alert>

                </td>

                <td style="width: 5%">
                  <input class="form-control" disabled
                         v-model="item.moneda_x.descripcion">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detallePagos.${index}.moneda_x.id_moneda`]"
                          v-text="message">
                      </li>
                    </ul>
                  </b-alert>

                </td>


                <td style="width: 5%" v-if="item.moneda_x.id_moneda === 1">
                  <input @change="calcularEquivalente(item)" class="form-control"
                         min="0" type="number"
                         v-model.number="item.monto">

                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detallePagos.${index}.monto`]"
                          v-text="message">
                      </li>
                    </ul>
                  </b-alert>

                </td>

                <td style="width: 5%" v-if="item.moneda_x.id_moneda === 2">
                  <input @change="calcularEquivalente(item)" class="form-control" min="0"
                         type="number"
                         v-model.number="item.monto_me">
                  <b-alert show variant="danger">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detallePagos.${index}.monto`]"
                          v-text="message">
                      </li>
                    </ul>
                  </b-alert>

                </td>

                <td style="width: 5%">
                  <input class="form-control"
                         disabled
                         v-model="item.banco_x.descripcion">
                  <b-alert show variant="danger">
                  </b-alert>
                  <ul class="error-messages">
                    <li
                        :key="message"
                        v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.descripcion`]"
                        v-text="message">
                    </li>
                  </ul>
                </td>
                <td style="width: 5%">
                  <template v-if="[1,3].indexOf(item.via_pagox.id_via_pago) >= 0">
                    <input class="form-control"
                           v-model="item.numero_minuta">
                    <b-alert show variant="danger">
                      <ul class="error-messages">
                        <li
                            :key="message"
                            v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_minuta`]"
                            v-text="message">
                        </li>
                      </ul>
                    </b-alert>

                  </template>

                  <template v-if="item.via_pagox.id_via_pago === 4">
                    <input class="form-control"
                           v-model="item.numero_nota_credito">
                    <b-alert show variant="danger">
                      <ul class="error-messages">
                        <li
                            :key="message"
                            v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_nota_credito`]"
                            v-text="message">
                        </li>
                      </ul>
                    </b-alert>

                  </template>

                  <template v-if="item.via_pagox.id_via_pago === 5">
                    <input class="form-control"
                           v-model="item.numero_cheque">
                    <b-alert show variant="danger">
                      <ul class="error-messages">
                        <li
                            :key="message"
                            v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_cheque`]"
                            v-text="message">
                        </li>
                      </ul>
                    </b-alert>

                  </template>

                  <template v-if="item.via_pagox.id_via_pago === 6">


                    <input class="form-control"
                           v-model="item.numero_transferencia">
                    <b-alert show variant="danger">
                    </b-alert>
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_transferencia`]"
                          v-text="message">
                      </li>
                    </ul>
                  </template>

                  <template v-if="item.via_pagox.id_via_pago === 7">
                    <input class="form-control"
                           v-model="item.numero_recibo_pago">
                    <b-alert show variant="danger">
                      <ul class="error-messages">
                        <li
                            :key="message"
                            v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_recibo_pago`]"
                            v-text="message">
                        </li>
                      </ul>
                    </b-alert>

                  </template>

                </td>

              </tr>
              <tr></tr>
            </template>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>


        <div class="col-sm-12 table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th colspan="2">Resumen</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td style="width: 50%">
                <label> Total a Abonar en Córdobas</label>
              </td>
              <td style="width: 50%">
                <p><strong>C$ {{ gran_total | formatMoney(2) }}</strong></p>
              </td>
            </tr>

            <template v-for="(item, index) in form.detallePago">

              <tr>
                <td style="width: 50%">
                  {{ '(-) Pagado con ' + item.via_pagox.descripcion + ' (Equivalente en Córdobas)' }}
                </td>
                <td style="width: 50%">
                  <p><strong>C$ {{ item.monto | formatMoney(2) }}</strong></p>
                </td>
              </tr>

            </template>
            <tr>
              <td style="width: 50%">
                <label> Monto Pendiente Córdobas</label>
              </td>
              <td style="width: 50%">
                <p><strong>C$ {{ total_deuda | formatMoney(2) }}</strong></p>
              </td>
            </tr>

            <tr>
              <td style="width: 50%">
                <label> Monto Pendiente (Equivalente en Dólares)</label>
              </td>
              <td style="width: 50%">
                <p><strong>$ {{ form.pago_pendiente | formatMoney(2) }}</strong></p>
              </td>
            </tr>


            <tr>
              <td style="width: 50%">
                <label> Monto Vuelto</label>
              </td>
              <td style="width: 50%">
                <p><strong>C$ {{ form.pago_vuelto_mn | formatMoney(2) }}</strong></p>
              </td>
            </tr>


            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>


      </b-row>



      <b-card-footer class="text-lg-right text-center">
            <router-link :to="{ name: 'cajabanco-facturas' }">
                <b-button
                        class="mx-lg-1 mx-0"
                        type="button"
                        variant="secondary"
                >
                    Cancelar
                </b-button>
            </router-link>
            <b-button
                    :disabled="btnAction !== 'Facturar'"
                    @click="registrar"
                    class="mx-lg-1 mx-0"
                    variant="success"
            >{{ btnAction }}
            </b-button>
        </b-card-footer>

        <template v-if="loading">
            <BlockUI :url="url"/>
        </template>


        <!-- Modal - registro basico cliente -->

        <div>
            <b-modal hide-footer id="modal-select2" ref="modal" size="lg" title="Registrar cliente">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tipo Persona</label>
                            <b-form-select v-model.number="formCliente.tipo_persona">
                                <option value="1">Natural</option>
                                <option value="2">Jurídico</option>
                            </b-form-select>
                        </div>
                    </div>

                    <template v-if="formCliente.tipo_persona === 1">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Número Cédula</label>
                                <input class="form-control" placeholder="Número Cédula" v-mask="'#############A'"
                                       v-model="formCliente.numero_cedula">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.numero_cedula"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Número RUC</label>
                                <input class="form-control" placeholder="Número RUC" v-mask="'A#############'"
                                       v-model="formCliente.numero_ruc">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li :key="message" v-for="message in errorMessages.numero_ruc"
                                            v-text="message"></li>
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                    </template>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> Nombre Completo</label>
                            <input class="form-control" placeholder="Nombre Completo"
                                   v-model="formCliente.nombre_comercial">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.nombre_comercial"
                                        v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> Contacto</label>
                            <input class="form-control" placeholder="Contacto" v-model="formCliente.contacto">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.contacto" v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> Dirección</label>
                            <input class="form-control" placeholder="Dirección" v-model="formCliente.direccion">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.direccion" v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> Teléfono</label>
                            <input class="form-control" placeholder="Teléfono" v-model="formCliente.telefono">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.telefono" v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Departamento</label>
                            <v-select
                                    :options="departamentos"
                                    label="descripcion"
                                    placeholder="Seleccione un departamento"
                                    style="background: white"
                                    v-model="formCliente.departamento"
                                    v-on:input="obtenerMunicipios()"
                            >
                                <template slot="no-options">No se encontraron registros</template>
                            </v-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.departamento"
                                        v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Municipio</label>
                            <v-select
                                    :options="municipios"
                                    label="descripcion"
                                    placeholder="Seleccione un municipio"
                                    style="background: white"
                                    v-model="formCliente.municipio"
                            >
                                <template slot="no-options">No se encontraron registros</template>
                            </v-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.municipio" v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Vendedor</label>
                            <v-select
                                    :options="vendedores"
                                    label="nombre_completo"
                                    placeholder="Seleccione un vendedor"
                                    style="background: white"
                                    v-model="formCliente.vendedor"
                            >
                                <template slot="no-options">No se encontraron registros</template>
                            </v-select>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.vendedor" v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> Observaciones</label>
                            <input class="form-control" placeholder="Observaciones" v-model="formCliente.observaciones">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li :key="message" v-for="message in errorMessages.observaciones"
                                        v-text="message"></li>
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                </div>

                <div class="content-box-footer text-right">
                    <b-button @click="cerrarModal" class="mx-lg-1 mx-0" variant="secondary">Cancelar</b-button>
                    <b-button :disabled="btnActionCliente !== 'Registrar Cliente'"
                              @click="registrarCliente" class="mx-lg-1 mx-0"
                              type="button"
                              variant="primary">{{ btnActionCliente }}
                    </b-button>
                </div>
            </b-modal>
        </div>


        <!-- Fin Modal - registro basico cliente -->


    </b-card>


    <!--DIALOG -->
    <!--    <b-modal ref="modal" modal-theme="dark" overlay-theme="dark" title="Registrar Cliente">
              <div class="row">

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label>Tipo Persona</label>
                          <select class="form-control" v-model.number="formCliente.tipo_persona">
                              <option value="1">Natural</option>
                              <option value="2">Jurídico</option>
                          </select>
                      </div>
                  </div>

                  <template v-if="formCliente.tipo_persona === 1">
                      <div class="col-sm-4">
                          <div class="form-group">
                              <label> Número Cédula</label>
                              <input class="form-control" v-mask="'#############A'" placeholder="Número Cédula"
                                     v-model="formCliente.numero_cedula">
                              <ul class="error-messages">
                                  <li :key="message" v-for="message in errorMessages.numero_cedula" v-text="message"></li>
                              </ul>
                          </div>
                      </div>
                  </template>
                  <template v-else>
                      <div class="col-sm-4">
                          <div class="form-group">
                              <label> Número RUC</label>
                              <input class="form-control" v-mask="'A#############'" placeholder="Número RUC"
                                     v-model="formCliente.numero_ruc">
                              <ul class="error-messages">
                                  <li :key="message" v-for="message in errorMessages.numero_ruc" v-text="message"></li>
                              </ul>
                          </div>
                      </div>
                  </template>

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label> Nombre Completo</label>
                          <input class="form-control" placeholder="Nombre Completo"
                                 v-model="formCliente.nombre_comercial">
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.nombre_comercial" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label> Contacto</label>
                          <input class="form-control" placeholder="Contacto" v-model="formCliente.contacto">
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.contacto" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label> Dirección</label>
                          <input class="form-control" placeholder="Dirección" v-model="formCliente.direccion">
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.direccion" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label> Teléfono</label>
                          <input class="form-control" placeholder="Teléfono" v-model="formCliente.telefono">
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.telefono" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label>Departamento</label>
                          <v-select
                                  :options="departamentos"
                                  label="descripcion"
                                  v-model="formCliente.departamento"
                                  v-on:input="obtenerMunicipios()"
                                  style="background: white"
                          ></v-select>
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.departamento" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label>Municipio</label>
                          <v-select
                                  :options="municipios"
                                  label="descripcion"
                                  v-model="formCliente.municipio"
                                  style="background: white"
                          ></v-select>
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.municipio" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

      &lt;!&ndash;            <div class="col-sm-4">&ndash;&gt;
      &lt;!&ndash;                <div class="form-group">&ndash;&gt;
      &lt;!&ndash;                    <label>Zona</label>&ndash;&gt;
      &lt;!&ndash;                    <v-select&ndash;&gt;
      &lt;!&ndash;                            :options="zonas"&ndash;&gt;
      &lt;!&ndash;                            label="descripcion"&ndash;&gt;
      &lt;!&ndash;                            v-model="formCliente.zona"&ndash;&gt;
      &lt;!&ndash;                            style="background: white"&ndash;&gt;
      &lt;!&ndash;                    ></v-select>&ndash;&gt;
      &lt;!&ndash;                    <ul class="error-messages">&ndash;&gt;
      &lt;!&ndash;                        <li :key="message" v-for="message in errorMessages.zona" v-text="message"></li>&ndash;&gt;
      &lt;!&ndash;                    </ul>&ndash;&gt;
      &lt;!&ndash;                </div>&ndash;&gt;
      &lt;!&ndash;            </div>&ndash;&gt;

                  <div class="col-sm-4">
                      <div class="form-group">
                          <label>Vendedor</label>
                          <v-select
                                  :options="vendedores"
                                  label="nombre_completo"
                                  v-model="formCliente.vendedor"
                                  style="background: white"
                          ></v-select>
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.vendedor" v-text="message"></li>
                          </ul>
                      </div>
                  </div>

                  <div class="col-sm-10">
                      <div class="form-group">
                          <label> Observaciones</label>
                          <input class="form-control" placeholder="Observaciones" v-model="formCliente.observaciones">
                          <ul class="error-messages">
                              <li :key="message" v-for="message in errorMessages.observaciones" v-text="message"></li>
                          </ul>
                      </div>
                  </div>
              </div>

              <div class="content-box-footer text-right">
                  <button class="btn btn-default" @click="cerrarModal">Cancelar</button>
                  <button :disabled="btnActionCliente !== 'Registrar Cliente'"
                          @click="registrarCliente"
                          class="btn btn-primary"
                          type="button">{{ btnActionCliente }}
                  </button>
              </div>

          </b-modal>-->
</template>

<script type="text/ecmascript-6">

    import {
        BAlert,
        BButton,
        BCard,
        BCardFooter,
        BForm,
        BFormCheckbox,
        BFormCheckboxGroup,
        BFormDatepicker,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BListGroup,
        BListGroupItem,
        BModal,
        BPaginationNav,
        BRow,
        VBModal,
        VBTooltip
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import Ripple from 'vue-ripple-directive'
    import axios from 'axios'
    import factura from '../../../api/CajaBanco/facturas'
    import bodega from '../../../api/Inventario/bodegas'
    import proforma from '../../../api/CajaBanco/proformas'
    import cliente from '../../../api/Ventas/clientes'
    import recibo from '../../../api/CuentasXCobrar/recibos_oficiales.js'
    import tc from '../../../api/contabilidad/tasas-cambio'
    import Round from '../../../assets/custom-scripts/Round'
    import moment from '../../../../../Backend/resources/app/assets/plugins/moment/moment'
    import ToastificationContent from '../../../@core/components/toastification/ToastificationContent'
    import loadingImage from '../../../assets/images/loader/block50.gif'
    import proyecto from "../../../api/CajaBanco/proyectos";
    import cuenta from "../../../api/CuentasXCobrar/cuentas_por_cobrar";
    import numberasstring from "../../../assets/custom-scripts/NumberAsString";
    import flatPickr from 'vue-flatpickr-component';
/*    import es from 'vuejs-datepicker/dist/locale/translations/es'*/

    export default {
        components: {
            BCard,
            BCardFooter,
            BPaginationNav,
            BFormCheckbox,
            BFormGroup,
            BFormInput,
            vSelect,
            flatPickr,
            BRow,
            BButton,
            BFormCheckboxGroup,
            BFormDatepicker,
            BAlert,
            BFormSelect,
            BModal,
            BListGroup,
            BListGroupItem,
            BForm,
        },
        directives: {
            'b-tooltip': VBTooltip,
            'b-modal': VBModal,
            Ripple,
        },
        data() {
            return {
                loading: true,
                url: loadingImage,


                format: 'dd-MM-yyyy',

                proformasBusquedaURL: '/cajabanco/proformas/buscar',

                clientesBusquedaURL: '/ventas/clientes/buscar',
                vendedoresBusquedaURL: '/ventas/vendedores/buscar',

                sucursalesBusquedaURL: '/sucursales/buscar',
                // bodegasBusquedaURL: "/bodegas/buscar",
                // productosBodegaBusquedaURL: "/productos/buscar-bodega-venta",
                clienteCredito: false,
                plazo_maximo_credito: 0,

                bodegas: [],
                sucursales: [],
                productos: [],
                servicios: [],
                productosORG: [],
                clientes: [],
                proformas: [],

                departamentos: [],
                municipios: [],
                zonas: [],
                vendedores: [],
                id_sucursal: 0,


                formCliente: {
                    tipo_persona: 1,
                    nombre_comercial: '',
                    vendedor: '',
                    numero_ruc: '',
                    numero_cedula: '',
                    direccion: '',
                    id_tipo_cliente: 1,
                    telefono: '',
                    correo: '',
                    municipio: '',
                    giro_negocio: '',
                    zona: '',
                    id_impuesto: 1,
                    tipo_contribuyente: 1,
                    retencion_ir: true,
                    retencion_imi: true,
                    clasificacion: 1,
                    permite_credito: false,
                    plazo_credito: 0,
                    limite_credito: 0,
                    limite_credito_me: 0,
                    porcentaje_descuento: 0,
                    observaciones: '',
                    permite_consignacion: false,
                    tramite_cheque: 15,
                },

                // afectacionesBusquedaURL: "/ventas/afectaciones/buscar",
                afectaciones: [],
                vias_pago: [],
                monedas: [],
                bancos: [],
                proyectos: [],
                recibos: [],
                detalleForm: {
                    productox: '',
                    cuentax: '',
                    afectacionx: [],
                    cantidad: 1,
                    precio_sugerido: 0,
                    precio_sugerido_me: 0,
                    descuento: 0,
                    precio_costo: 0,
                    precio_lista: 0,
                    subtotal: 0,
                    total: 0,
                    total_sin_iva: 0,
                    monto_a_pagar:0
                },

                detalleFormPago: {
                    via_pagox: [],
                    monto: 1,
                    moneda_x: [],
                    banco_x: [],
                    numero_minuta: '',
                    numero_nota_credito: '',
                    numero_cheque: '',
                    numero_transferencia: '',
                    numero_recibo_pago: '',
                },
                tipo_cliente: 1, // tipo normal por defecto
                proformaForm: {
                    afectacion_producto: [],
                    bodega_producto: [],
                },
                proformaFormHeader: {es_nuevo: true},

                form: {
                  detalleCuentasxCobrar:[],
                    no_factura:'',
                    discount_max: '',
                    codigo_autorizacion: '',
                    es_deudor: false,
                    es_nuevo: true,
                    proforma_cliente: [],
                    proforma_bodega: [],
                    proforma_sucursal: [],
                    proforma_vendedor: [],
                    recibos: [],
                    proformasBusqueda: {},
                    proforma_especifica: false,
                    permite_anticipo: false,
                    tipo_venta: 1,
                    no_documento: '',
                    f_factura: moment(new Date()).format('YYYY-MM-DD'),
                    fecha_emisionx: new Date(),
                    f_vencimiento: moment(new Date()).format('YYYY-MM-DD'),
                    serie: '',
                    id_tipo: 1,
                    id_tipo_documento: 3,
                    // factura_moneda: {},
                    factura_sucursal: {},
                    factura_bodega: '',
                    tipo_identificacion: 1,
                    identificacion: '',
                    factura_cliente: {},
                    id_tipo_cliente: 1,
                    dias_credito: 0,
                    nombre_razon: '',
                    factura_vendedor: '',
                    t_cambio: 0,
                    tasa_paralela: 0,
                    doc_exoneracion: '',
                    doc_exoneracion_ir: '',
                    doc_exoneracion_imi: '',
                    impuesto_exonerado: false,
                    mt_retencion: 0,
                    mt_retencion_imi: 0,
                    mt_impuesto: 0,
                    mt_descuento: 0,
                    mt_ajuste: 0,

                    pago_vuelto: 0,
                    pago_pendiente: 0,

                    pago_vuelto_mn: 0,
                    pago_pendiente_mn: 0,

                    observacion: '',
                    mt_subtotal: 0,
                    mt_subtotal_sin_iva: 0,
                    aplicaIR: false,
                    aplicaIMI: false,
                    aplicaIVA: true,
                    total_final: 0,
                    total_final_cordobas: 0,
                    currency_id: 2,

                    total_unidades_sin_bonificacion: 0,
                    total_unidades_con_bonificacion: 0,
                    total_final_recibos_dolares: 0,
                    total_final_recibos_cordobas: 0,

                    detalleProductos: [],
                    detallePago: [],
                    proyecto: [],

                  monto_total_me: 0,
                  monto_total: 0,
                  monto_total_letras: '',
                  monto_total_letras_me: '',
                  saldo_total_me: 0,
                  saldo_total: 0,
                },
                btnAction: 'Facturar',
                btnActionCliente: 'Registrar Cliente',
                registrandoCliente: false,

                errorMessages: [],
                cuentas:[]
            }
        },
        computed: {
            total_subt_sin_iva() {
                this.form.mt_subtotal_sin_iva = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.total_sin_iva, 2)),
                    0)));
                this.form.mt_subtotal = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.subtotal, 2)),
                    0)));
                return Round.round(this.form.mt_subtotal_sin_iva, 2)
            },
            total_impuesto() {
                if (this.form.aplicaIVA) {
                    this.form.mt_impuesto = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.mt_impuesto, 2)), 0)))
                } else {
                    this.form.mt_impuesto = 0
                }

                return this.form.mt_impuesto
            },

            total_retencion() {
              if (this.form.aplicaIR && (Number(this.form.mt_subtotal_sin_iva) * this.form.t_cambio >= 1000)) {
                    this.form.mt_retencion = Round.round(this.form.mt_subtotal_sin_iva * 0.02, 2)
                } else {
                    this.form.mt_retencion = 0
                }
                return this.form.mt_retencion
            },

            total_retencion_imi() {
                if (this.form.aplicaIMI) {
                    this.form.mt_retencion_imi = Round.round(this.form.mt_subtotal_sin_iva * 0.01, 2)
                } else {
                    this.form.mt_retencion_imi = 0
                }

                return this.form.mt_retencion_imi
            },

            total_ajuste() {
                this.form.mt_ajuste = this.form.detalleProductos.reduce((carry, item) => (carry + Number(item.mt_ajuste)), 0);
                return this.form.mt_ajuste
            },

            total_descuento() {
                this.form.mt_descuento = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.mt_descuento, 2)), 0)));
                return this.form.mt_descuento
            },
            descuento_maximo() {
                // this.form.descuento_maximo_producto = Number((this.form.detalleProductos.reduce((carry, item) => (Round.round(item.p_descuento,2)), 0)));
                let array = this.form.detalleProductos.map(function (producto) {
                    return producto.p_descuento
                });
                return Math.max(...array)
            },
            total_cantidad() {
                this.form.total_unidades_sin_bonificacion = Number(this.form.detalleProductos.reduce((carry, item) => (carry + ((item.afectacionx.id_afectacion !== 2) ? (Number(item.cantidad)) : 0)), 0));

                this.form.total_unidades_con_bonificacion = Number(this.form.detalleProductos.reduce((carry, item) => (carry + ((item.afectacionx.id_afectacion === 2) ? (Number(item.cantidad)) : 0)), 0));

                return this.form.detalleProductos.reduce((carry, item) => (carry + Number(item.cantidad)), 0)
            },

            gran_total_cordobas() {
                this.form.total_final = Round.round((this.form.mt_subtotal_sin_iva - this.form.mt_retencion - this.form.mt_retencion_imi) + this.form.mt_impuesto, 2);
                // roundNumber.round(Number((Number(this.form.total_final)*this.form.t_cambio).toFixed(2)),2);

                if (!isNaN(this.form.total_final)) {
                    return Number(this.form.total_final);
                }
                return 0
            },

            gran_total() {
                /* this.form.total_final = roundNumber.decimalAdjust('ceil', Number(this.form.total_final_cordobas / this.form.t_cambio), -2); */
                this.form.total_final_cordobas = Round.round(this.form.total_final * this.form.t_cambio, 2);

                if (!isNaN(this.form.total_final_cordobas)) {
                    return this.form.total_final_cordobas
                }
                return 0
            },
            total_recibos() {
                if (this.recibos) {
                    this.form.total_final_recibos_dolares = this.recibos.reduce((carry, item) => (carry + Number(item.monto_total_me)), 0);

                    if (!isNaN(this.form.total_final_recibos_dolares)) {
                        return Round.round(this.form.total_final_recibos_dolares,2)
                    }
                    return 0
                }
            },
            total_recibos_cordobas() {
                if (this.recibos) {
                    this.form.total_final_recibos_cordobas = this.recibos.reduce((carry, item) => (carry + Number(item.monto_total)), 0);

                    if (!isNaN(this.form.total_final_recibos_cordobas)) {
                        return this.form.total_final_recibos_cordobas
                    }
                    return 0
                }
            },

            total_deuda() {
                /* let total_pagos = this.form.detallePago.reduce((carry, item) => {
                              return (carry + Number(item.moneda_x.id_moneda === 3 ? item.monto_me : Number((item.monto/this.form.t_cambio).toFixed(2))));
                          }, 0); */


                // total_pagos_cordobas = this.form.detallePago.reduce((carry, item) => (carry + Number(item.moneda_x.id_moneda === 1 ? item.monto : Number(item.monto_me * this.form.t_cambio).toFixed(2))), 0)
                let total_pagos_cordobas = 0;
                 total_pagos_cordobas = this.form.detallePago.reduce((carry, item) => {
                    return (carry + Number(item.moneda_x.id_moneda === 1 ? Number(item.monto) : Round.round((item.monto_me) * (this.form.t_cambio), 2)))
                }, 0);


                /*console.log('Total pago C$: ' + total_pagos_cordobas);
                console.log('Total factura C$: ' + this.form.total_final_cordobas);
                console.log('Dif C$: ' + this.form.total_final_cordobas - total_pagos_cordobas);*/

                let total_final_cordobas = this.form.total_final_cordobas;
                if (Round.round(total_final_cordobas - total_pagos_cordobas, 2) < 0) {
                    this.form.pago_pendiente = 0;
                    this.form.pago_pendiente_mn = 0;
                    /*                    let result = Number(total_final_cordobas - total_pagos_cordobas).toFixed(2);
                                        console.log("total pagos cordobas" + " "+ total_pagos_cordobas);
                                        console.log("total final cordobas" + " " + total_final_cordobas);
                                        console.log("total final cordobas - total_pagos_cordobas  " + result);*/
                } else {
                    // this.form.pago_pendiente_mn = Number((Number((this.form.total_final_cordobas)).toFixed(2) - Number((total_pagos_cordobas)).toFixed(2)).toFixed(2));
                    this.form.pago_pendiente_mn = Round.round(total_final_cordobas - total_pagos_cordobas, 2);

                    /*                    console.log("total pagos cordobas" + " "+ total_pagos_cordobas);
                                        console.log("total final cordobas" + " " + total_final_cordobas);

                                        console.log('pago pendiente mn  ' + this.form.pago_pendiente_mn);*/
                    /* this.form.pago_pendiente = roundNumber.decimalAdjust('ceil', Number(this.form.pago_pendiente_mn / this.form.t_cambio), -2); */

                    /*console.log('total final cordobas ' + this.form.total_final_cordobas);
                    console.log('total pagos cordobas ' + total_pagos_cordobas);
                    console.log('Diferencia total - pago ' + this.form.total_final_cordobas - total_pagos_cordobas);
                    console.log('Pago pendiente mn ' + this.form.pago_pendiente_mn);*/
                    this.form.pago_pendiente = Round.round(this.form.pago_pendiente_mn / this.form.t_cambio, 2);
                }

                /* let total_pagos_cordobas = this.form.detallePago.reduce((carry, item) => {
                            return (carry + Number(item.moneda_x.id_moneda === 3 ? item.monto : Number((item.monto_me*this.form.t_cambio).toFixed(2))));
                          }, 0); */

                if (!isNaN(this.form.pago_pendiente_mn)) {
                    // Number((Number((this.form.total_final*this.form.t_cambio).toFixed(2)) - total_pagos_cordobas).toFixed(2));
                    return this.form.pago_pendiente_mn
                }
                return 0
            },

            total_vuelto() {
                /* let total_pagos = this.form.detallePago.reduce((carry, item) => {
                               return (carry + Number(item.moneda_x.id_moneda === 3 ? item.monto_me : Number(item.monto/this.form.t_cambio.toFixed(2))));
                           }, 0); */

                let total_pagos = 0;


                const monto_dolares_decimales = Round.round((Number(this.form.total_final_cordobas) / Number(this.form.t_cambio)), 2);
                total_pagos = this.form.detallePago.reduce((carry, item) => {
                    // return (carry + Number(item.moneda_x.id_moneda === 1 ? item.monto : Number((item.monto_me * this.form.t_cambio).toFixed(2))));
                    return carry + Number(item.moneda_x.id_moneda === 1 ? item.monto : Round.round((item.monto_me) * (this.form.t_cambio), 2))
                }, 0);


                if (((Round.round((this.form.total_final_cordobas), 2) - Round.round((total_pagos), 2))) > 0) { /// revision
                    this.form.pago_vuelto = 0;
                    this.form.pago_vuelto_mn = 0;
                } else {
                    this.form.pago_vuelto_mn = Round.round((this.form.total_final_cordobas), 2) - Number((Round.round((total_pagos), 2)));
                    // console.log(total_pagos);
                    this.form.pago_vuelto = Round.round(this.form.pago_vuelto_mn / this.form.t_cambio, 2);
                }

                // console.log('Master C$: '+((Number((this.form.total_final).toFixed(2)) - Number((total_pagos).toFixed(2))).toFixed(2)));

                /* let total_pagos_cordobas = this.form.detallePago.reduce((carry, item) => {
                            return (carry + Number(item.moneda_x.id_moneda === 3 ? item.monto : Number((item.monto_me*this.form.t_cambio).toFixed(2))));
                          }, 0); */

                if (!isNaN(this.form.pago_vuelto_mn)) {
                    return this.form.pago_vuelto_mn
                } else return 0
            },

          total_a_pagar() {
            let self = this;

            self.form.monto_total_me = Number(this.form.detalleCuentasxCobrar.reduce((carry, item) => {
              return (carry + Number(item.monto_me))
            }, 0));

            self.form.saldo_total_me = Number(this.form.detalleCuentasxCobrar.reduce((carry, item) => {
              return (carry + Round.round(item.cuentax.saldo_actual_me - item.monto_me, 2))
            }, 0));

            this.form.monto_total_letras_me = numberasstring.numberasstring(this.form.monto_total_me, 'DOLAR', 'DOLARES', true);

            if (!isNaN(this.form.monto_total)) {
              // this.form.monto_total_me = roundNumber.decimalAdjust('ceil', Number((this.form.monto_total / this.form.t_cambio).toFixed(2)));
              this.form.monto_total = Round.round(this.form.monto_total_me * this.form.t_cambio, 2);
              // console.log("monto total en dolares  "+this.form.monto_total_me);
              // this.form.saldo_total_me = roundNumber.decimalAdjust('ceil', Number((this.form.saldo_total / this.form.t_cambio).toFixed(2)));
              this.form.saldo_total = Round.round(this.form.saldo_total_me * this.form.t_cambio, 2);
              self.form.monto_total_letras = numberasstring.numberasstring(this.form.monto_total, 'CORDOBA', 'CORDOBAS', true);

              return this.form.monto_total_me;
            } else return 0;

          },

        },
        methods: {
            /*
                    Author: omorales (c)
                    live search
                    14/03/22    */
            onSearch(search, loading) {
                if (search.length) {
                    loading(true);
                    this.search(loading, search, this)
                }
            },
            onSearchP(searchP, loading) {
                if (searchP.length) {
                    loading(true);
                    this.searchP(loading, searchP, this)
                }
            },
            select(e) {
                this.$emit('input', {
                    target: {
                        value: result,
                    },
                });
                this.onEsc()
            },
            search: _.debounce((loading, search, vm) => { // /ventas/clientes/buscar
                const self = this;

                    axios.get(`/cuentas-por-cobrar/clientes/buscar?q=${escape(search)}&es_deudor=${vm.form.es_deudor}&permite_anticipo=${vm.form.permite_anticipo}`).then(res => {
                        vm.options = res.data.results;
                        vm.clientes = res.data.results;
                        loading(false)
                    })
            }, 100),
            searchP: _.debounce((loading, searchP, vm) => { // busqueda proformas
                const self = this
                axios.get(`/cajabanco/proformas/buscar?q=${escape(searchP)}`).then(res => {
                    vm.options = res.data.results;
                    vm.proformas = res.data.results;
                    loading(false)
                })
            }, 100),
            /*
                   Author: omorales (c)
                   Limpiar formulario al deseleccionar la opción de proforma
                   03/09/21    */
            deseleccionar() {
                this.form.proformasBusqueda = {}
                this.form.detalleProductos = []
                this.form.detallePago = []
                this.detalleForm.productox = ''
                this.proforma_cliente = []
                this.proforma_vendedor = []
                this.proforma_bodega = []
                this.proforma_sucursal = []
                this.proformaFormHeader.es_nuevo = true

                // this.filter.search.value=''
            },
            /*
                    Author: omorales (c)
                    Se agregó metodo para cargar detalle de proforma a la función de selección
                    03/09/21    */
            seleccionarProforma(e) {
                // const oc = e.target.value
                const self = this;
                /* self.form.proformasBusqueda = form.proformasBusqueda*/
                self.cargarDetalleProforma()
            },
            /*
                    Author: omorales (c)
                    Se creo función de carga de detalle de proforma mediante id
                    03/09/21    */
            cargarDetalleProforma() {
                const self = this
                self.loading = true
                proforma.obtenerDetalleProforma({
                        id_proforma: self.form.proformasBusqueda.id_proforma,
                        id_cliente: self.form.proformasBusqueda.id_cliente,
                        id_bodega: self.form.proformasBusqueda.id_bodega,
                    },
                    data => {
                        self.proformaForm = data.detalleProforma;
                        self.proformaFormHeader = data.proforma;
                        self.form.es_nuevo = self.proformaFormHeader.es_nuevo;
                        self.form.id_tipo = self.proformaFormHeader.id_tipo;
                        self.form.serie = self.proformaFormHeader.serie;
                        self.form.tipo_identificacion = self.proformaFormHeader.tipo_identificacion;
                        self.form.identificacion = self.proformaFormHeader.identificacion;
                        self.form.observacion = self.proformaFormHeader.observacion;
                        self.form.t_cambio = self.proformaFormHeader.t_cambio;
                        self.form.dias_credito = self.proformaFormHeader.dias_credito;
                        self.form.proforma_cliente = self.proformaFormHeader.proforma_cliente;
                        self.form.proforma_vendedor = self.proformaFormHeader.proforma_vendedor;
                        self.form.proforma_bodega = self.proformaFormHeader.proforma_bodega;
                        self.form.proforma_sucursal = self.proformaFormHeader.proforma_sucursal;

                        self.form.factura_cliente = self.form.proforma_cliente;
                        self.form.factura_vendedor = self.form.proforma_vendedor;
                        self.form.factura_bodega = self.form.proforma_bodega;
                        self.form.factura_sucursal = self.form.proforma_sucursal;
                        /* self.afectacion_producto = data.detalleProforma.afectacion_producto;
                                        self.bodega_producto = data.detalleProforma.bodega_producto; */
                        this.cargarProductosProforma();
                        self.loading = false
                    }, err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encontró un detalle de la cotización seleccionada',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        self.loading = false
                    })
            },
            /*
                    Author: omorales (c)
                    Función para agregar datos de la proforma al detalle de productos de la factura
                    03/09/21    */
            cargarProductosProforma() {
                const self = this;
                if (self.form.proformasBusqueda) {
                    self.proformaForm.forEach((productox, key) => {
                        if (productox) {
                            let cantidad = 0;
                            if (self.form.detalleProductos) {
                                self.form.detalleProductos.forEach((productodx, key) => {
                                    if (productox.bodega_producto.id_producto === productodx.productox.id_producto) {
                                        cantidad = cantidad + productodx.cantidad + productox.cantidad
                                    }
                                })
                            }

                            const i = 0;
                            const keyx = 0;
                            // if (self.form.detalleProductos) {
                            //     self.form.detalleProductos.forEach((productodx, key) => {
                            //         if ((self.detalleForm.productox.id_producto === productodx.productox.id_producto)
                            //             && (self.detalleForm.afectacionx.id_afectacion === productodx.afectacionx.id_afectacion)) {
                            //             i++;
                            //             keyx = key;
                            //         }
                            //     });
                            // }

                            if (i === 0) {
                                if (cantidad <= Number(productox.bodega_producto.cantidad_disponible)) { /* validar existencias de proforma respecto al inventario */
                                    self.form.detalleProductos.push({
                                        productox: productox.bodega_producto,
                                        afectacionx: productox.afectacion_producto,
                                        cantidad: Number(productox.cantidad),
                                        precio_costo: Number(productox.bodega_producto.costo_promedio),
                                        precio_costo_me: Number(productox.bodega_producto.costo_promedio_me),
                                        precio_lista_me: Number(productox.precio),
                                        precio_lista: Number(productox.precio),
                                        precio: Number(productox.precio),
                                        p_descuento: Number(productox.p_descuento),
                                        mt_descuento: Number(productox.mt_descuento),
                                        p_impuesto: Number(productox.p_impuesto),
                                        mt_impuesto: Number(productox.mt_impuesto),
                                        subtotal: 0,
                                        total: 0,
                                        total_sin_iva: 0,
                                        mt_ajuste: Number(productox.mt_ajuste),
                                        p_ajuste: Number(productox.p_ajuste),
                                    })
                                    /* console.log(productox.bodega_producto); */
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Artículos agregados',
                                                variant: 'success',

                                            }
                                        },
                                        {
                                            position: 'bottom-right'
                                        });

                                    self.detalleForm.productox = null;
                                    self.detalleForm.afectacionx = self.afectaciones[0];
                                    self.detalleForm.cantidad = 0;
                                    self.detalleForm.precio_sugerido = 0;
                                    self.detalleForm.subtotal = 0;
                                    self.detalleForm.total = 0;
                                    self.detalleForm.total_sin_iva = 0;
                                    this.$refs.producto.$el.focus()
                                } else {
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'No se cuenta con existencias suficiente para poder facturar',
                                                variant: 'warning',

                                            }
                                        },
                                        {
                                            position: 'bottom-right'
                                        });
                                }
                            } else {
                                const nuevo_total = self.form.detalleProductos[keyx].cantidad + self.detalleForm.cantidad;
                                if (nuevo_total <= self.form.detalleProductos[keyx].productox.cantidad_disponible) {
                                    self.form.detalleProductos[keyx].cantidad = nuevo_total;
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Artículo agregado!',
                                                variant: 'success',

                                            }
                                        },
                                        {
                                            position: 'bottom-right'
                                        });
                                } else {
                                    self.form.detalleProductos[keyx].cantidad = Number(self.form.detalleProductos[keyx].productox.cantidad_disponible);
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Se ha agregado la cantidad máxima de este producto',
                                                variant: 'warning',

                                            }
                                        },
                                        {
                                            position: 'bottom-right'
                                        });
                                }
                            }
                        }
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Seleccione una proforma para continuar',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },

            calcularEquivalente(itemX) {
                if (itemX.moneda_x.id_moneda === 1 && itemX.monto > 0) { // equivalente para moneda cordobas
                    itemX.monto_me = Round.round(itemX.monto / this.form.t_cambio, 2)
                }

                if (itemX.moneda_x.id_moneda === 2 && itemX.monto_me > 0) { // equivalente para moneda dolares
                    itemX.monto = Round.round(itemX.monto_me * this.form.t_cambio, 2)
                }
            },

            abrirModal() {
                this.$refs['modal'].show()
            },
            cerrarModal() {
                this.$refs['modal'].hide()
            },
            cambiarFocoCantidad() {
                const self = this;
                self.$refs.agregar.focus()
            },

            seleccionarCliente(e) {
                // const trabajadorP = e.target.value;
                const self = this;

                // self.form.factura_cliente = trabajadorP;
                self.form.tipo_identificacion = self.form.factura_cliente.tipo_persona;
                // self.form.id_tipo = 1;

                self.tipo_cliente = self.form.factura_cliente.id_tipo_cliente;

                self.form.dias_credito = 0;
                self.form.aplicaIR = self.form.factura_cliente.retencion_ir;
                self.form.aplicaIMI = self.form.factura_cliente.retencion_imi;
                self.plazo_maximo_credito = self.form.factura_cliente.plazo_credito;
                self.form.factura_vendedor = self.form.factura_cliente.vendedor_cliente;
                (self.form.factura_cliente.permite_credito && self.form.factura_cliente.plazo_credito > 0) ? self.clienteCredito = true : self.clienteCredito = false

                if (self.form.factura_cliente.tipo_persona === 1) {
                    self.form.identificacion = self.form.factura_cliente.numero_cedula
                } else {
                    self.form.identificacion = self.form.factura_cliente.numero_ruc
                }

                self.obtenerProyectoCliente();
                self.form.proyecto = [];
                self.cargarDocumentosPendientes();

            },
              cargarDocumentosPendientes(){
              let self = this;
              if(self.form.factura_cliente.id_cliente > 0){
              cuenta.obtenerCuentasCliente({
                id_cliente: self.form.factura_cliente.id_cliente,
                id_tipo_documento: self.form.id_tipo_documento
              }, data => {
                if (data !== null) {
                  self.cuentas = data;
                  self.detalleForm.cuentax = '';
                } else {
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'No se encuentran cuentas pendientes para este cliente.',
                          variant: 'warning',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                  self.detalleForm.cuentax = '';
                }
                self.loading = false;
              }, err => {
                /*if(err.codigo_bateria){
                  self.detalleForm.bateria_busqueda = '';
                  self.$refs.bateria.focus();
                  alertify.warning("No se encuentra esta batería.",5);
                  self.detalleForm.cuentax = '';
                }*/
                self.loading = false;
              });
              }
            },
          cargar_detalles_cuenta() {
            const self = this;
            if (self.detalleForm.cuentax){
                //self.detalleForm.moneda_x = self.monedas[1];
                self.detalleForm.monto_me = Number(Number(self.detalleForm.cuentax.saldo_actual_me).toFixed(2));
            }
            // self.detalleForm.monto = Number(Number(self.detalleForm.cuentax.saldo_actual).toFixed(2));
            self.detalleForm.monto = 0;
          },
          calcularSaldoX(item) {

            return Number((roundNumber.round(Number(item.cuentax.saldo_actual_me), 2)) - Number(item.monto_me).toFixed(2));

          },
          establecerConcepto(itemX) {
            itemX.monto = Math.max(Math.min(Number(!isNaN(itemX.monto) ? itemX.monto.toFixed(2) : 0), Number(Number(itemX.cuentax.saldo_actual_me).toFixed(2))), 1);
            if (itemX.monto === Number(Number(itemX.cuentax.saldo_actual_me).toFixed(2))) {
              itemX.descripcion_pago = 'Cancela factura No.' + itemX.cuentax.no_documento_origen + '.';
            } else {
              itemX.descripcion_pago = 'Abona factura No.' + itemX.cuentax.no_documento_origen + '. Saldo $' + (Number(itemX.cuentax.saldo_actual_me) - itemX.monto).toFixed(2) + '.';
            }

            let monto_retencion_ir = 0, monto_retencion_imi = 0, iva = 1.15;

            if (itemX.cuentax.cuenta_factura.impuesto_exonerado) {
              iva = 1;
            }

            if (itemX.aplicaIR) {
              monto_retencion_ir = this.round(Number(itemX.monto / iva) * 0.02, 2);
            } else {
              monto_retencion_ir = 0;
            }

            if (itemX.aplicaIMI) {
              monto_retencion_imi = this.round(Number(itemX.monto / iva) * 0.01, 2);
            } else {
              monto_retencion_imi = 0;
            }

            itemX.monto_retencion_ir = monto_retencion_ir;
            itemX.monto_retencion_imi = monto_retencion_imi;

          }
          ,
          agregarDetalleRecibo() {
            const self = this;
            if (self.detalleForm.cuentax) {
              if (self.detalleForm.monto_me > 0) {
                let validacion = 0;
                if (self.detalleForm.aplicaIR && self.detalleForm.doc_exoneracion_ir === '') {
                  validacion++;
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'Si el pago retiene IR debe digitar el No. documento de retención.',
                          variant: 'warning',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                }
                if (self.detalleForm.aplicaIMI && self.detalleForm.doc_exoneracion_imi === '') {
                  validacion++;
                  this.$toast({
                        component: ToastificationContent,
                        props: {
                          title: 'Notificación',
                          icon: 'CheckIcon',
                          text: 'Si el pago retiene IMI, digitar el No. documento IMI',
                          variant: 'warning',
                        }
                      },
                      {
                        position: 'bottom-right'
                      });
                }
                if (validacion === 0) {
                  let i = 0;
                  let keyx = 0;

                  if (self.form.detalleCuentasxCobrar) {
                    self.form.detalleCuentasxCobrar.forEach((cuentax, key) => {
                      if (self.detalleForm.cuentax.id_cuentaxcobrar === cuentax.cuentax.id_cuentaxcobrar) {
                        i++;
                        keyx = key;
                      }
                    });
                  }
                  if (i === 0) {

                    if (Round.round(self.detalleForm.monto_me, 2) === Round.round(self.detalleForm.cuentax.saldo_actual_me, 2)) {
                      self.detalleForm.descripcion_pago = 'Cancela factura No.' + self.detalleForm.cuentax.no_documento_origen + '.';
                    } else {
                      self.detalleForm.descripcion_pago = 'Abona factura No.' + self.detalleForm.cuentax.no_documento_origen + '. Saldo $' + (Number(self.detalleForm.cuentax.saldo_actual_me) - self.detalleForm.monto_me).toFixed(2) + '.';
                    }

                    let monto_retencion_ir = 0, monto_retencion_imi = 0, iva = 1.15;
                    if (!self.detalleForm.cuentax.es_registro_importado) {
                        iva = 1;

                    }

                    if (self.detalleForm.aplicaIR) {
                      monto_retencion_ir = Round.round(Number(self.detalleForm.monto_me / iva) * 0.02, 2);
                    } else {
                      monto_retencion_ir = 0;
                    }

                    if (self.detalleForm.aplicaIMI) {
                      monto_retencion_imi = Round.round(Number(self.detalleForm.monto_me / iva) * 0.01, 2);
                    } else {
                      monto_retencion_imi = 0;
                    }

                    self.form.detalleCuentasxCobrar.push({
                      cuentax: self.detalleForm.cuentax,
                      monto: Round.round(self.detalleForm.monto_me * this.form.t_cambio, 2),
                      monto_me: Round.round(self.detalleForm.monto_me, 2),
                      monto_subtotal: Round.round(self.detalleForm.monto_me - monto_retencion_imi - monto_retencion_ir, 2),
                      monto_retencion_ir: monto_retencion_ir,
                      monto_retencion_imi: monto_retencion_imi,
                      aplicaIR: self.detalleForm.aplicaIR,
                      aplicaIMI: self.detalleForm.aplicaIMI,
                      doc_exoneracion_ir: self.detalleForm.doc_exoneracion_ir,
                      doc_exoneracion_imi: self.detalleForm.doc_exoneracion_imi,
                      descripcion_pago: self.detalleForm.descripcion_pago,
                      monto_a_facturar : self.detalleForm.monto_me

                    });
                    this.$toast({
                          component: ToastificationContent,
                          props: {
                            title: 'Notificación',
                            icon: 'CheckIcon',
                            text: 'Agregado correctamente.',
                            variant: 'success',
                          }
                        },
                        {
                          position: 'bottom-right'
                        });
                  } else {
                    let nuevo_total = self.form.detalleCuentasxCobrar[keyx].monto_me + self.detalleForm.monto_me;
                    if (nuevo_total <= Number(self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me)) {

                      //self.form.detalleCuentasxCobrar[keyx].monto_subtotal = nuevo_total;
                      self.form.detalleCuentasxCobrar[keyx].monto = nuevo_total;
                      self.form.detalleCuentasxCobrar[keyx].monto_me = Round.round(self.detalleForm.monto_me, 2);
                      this.$toast({
                            component: ToastificationContent,
                            props: {
                              title: 'Notificación',
                              icon: 'CheckIcon',
                              text: 'Cuenta agregada correctamente',
                              variant: 'success',
                            }
                          },
                          {
                            position: 'bottom-right'
                          });

                      if (nuevo_total === Number(self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me)) {
                        self.form.detalleCuentasxCobrar[keyx].descripcion_pago = 'Cancela factura No.' + self.form.detalleCuentasxCobrar[keyx].cuentax.no_documento_origen + '.';
                      } else {
                        self.form.detalleCuentasxCobrar[keyx].descripcion_pago = 'Abona factura No.' + self.detalleCuentasxCobrar[keyx].cuentax.no_documento_origen + '. Saldo $' + this.round((self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me) - nuevo_total, 2) + '.';
                      }

                    } else {
                      self.form.detalleCuentasxCobrar[keyx].monto = Number(self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual);
                      self.form.detalleCuentasxCobrar[keyx].monto_me = Round.round((self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me), 2);
                      self.form.detalleCuentasxCobrar[keyx].descripcion_pago = 'Cancela factura No.' + self.form.detalleCuentasxCobrar[keyx].cuentax.no_documento_origen + '.';
                      this.$toast({
                            component: ToastificationContent,
                            props: {
                              title: 'Notificación',
                              icon: 'CheckIcon',
                              text: 'Se ha agregado el monto máximo para saldar esta cuenta.',
                              variant: 'warning',
                            }
                          },
                          {
                            position: 'bottom-right'
                          });
                    }
                  }

                  self.detalleForm.cuentax = null;
                  self.detalleForm.monto_me = 0;
                  self.detalleForm.monto = 0;
                  self.detalleForm.descripcion_pago = '';
                  this.$refs.cuenta.$el.focus()

                }
              } else {
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'CheckIcon',
                        text: 'El monto del abono debe ser mayor a cero.',
                        variant: 'warning',
                      }
                    },
                    {
                      position: 'bottom-right'
                    });
              }
            } else {
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'CheckIcon',
                      text: 'Debe seleccionar una cuenta a saldar para agregar un abono.',
                      variant: 'warning',
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
            }
          },
          eliminarLineaRecibo(item, index) {
            if (this.form.detalleCuentasxCobrar.length >= 1) {
              this.form.detalleCuentasxCobrar.splice(index, 1);

            } else {
              this.form.detalleCuentasxCobrar = [];
            }
          }
          ,
            seleccionarProyecto(e) {
                // const trabajadorP = e.target.value;
                const self = this;
                if (self.form.factura_cliente.id_cliente > 0) {
                    self.loading = true;
                    recibo.obtenerRecibosCliente({
                        id_cliente: self.form.factura_cliente.id_cliente,
                        id_proyecto: self.form.proyecto.id_proyecto
                    }, data => {
                        if (data !== null) {
                            self.recibos = data;
                            self.form.recibos = data;
                            self.loading = false;
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encuentran recibos pendientes para este cliente.',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            self.detalleForm.cuentax = '';
                            self.loading = false;
                        }
                        self.loading = false;
                    }, err => {
                        /*if(err.codigo_bateria){
                          self.detalleForm.bateria_busqueda = '';
                          self.$refs.bateria.focus();
                          alertify.warning("No se encuentra esta batería.",5);
                          self.detalleForm.cuentax = '';
                        }*/
                        self.loading = false;
                    });
                } else {
                    self.form.proyecto = '';
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'AlertCircleIcon',
                                text: 'Debe seleccionar un cliente primero.',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });


                }
            },
            obtenerProyectoCliente(e) {
                // const trabajadorP = e.target.value;
                const self = this;
                if (self.form.factura_cliente.id_cliente > 0) {
                    self.loading = true;
                    proyecto.obtenerProyectosCliente({
                        id_cliente: self.form.factura_cliente.id_cliente,
                    }, data => {
                        if (data !== null) {
                            self.proyectos = data;
                            self.loading = false;
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encuentran proyectos para este cliente.',
                                        variant: 'warning',
                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            self.loading = false;
                        }
                        self.loading = false;
                    }, err => {
                        /*if(err.codigo_bateria){
                          self.detalleForm.bateria_busqueda = '';
                          self.$refs.bateria.focus();
                          alertify.warning("No se encuentra esta batería.",5);
                          self.detalleForm.cuentax = '';
                        }*/
                        self.loading = false;
                    });
                } else {
                    self.form.proyecto = '';
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'AlertCircleIcon',
                                text: 'Debe seleccionar un cliente primero.',
                                variant: 'warning',
                            }
                        },
                        {
                            position: 'bottom-right'
                        });


                }
            },
            seleccionarVendedor(e) {
                const proveedorP = e.target.value;
                const self = this;
                self.form.factura_vendedor = proveedorP
            },
            seleccionarSucursal() {
                var self = this;
                self.bodegas = [];
                self.form.factura_bodega = [];
                // self.form.factura_sucursal = sucursalx;
                // console.log(sucursalx);
                // console.log(self.form.factura_sucursal);
                self.form.serie = self.form.factura_sucursal.serie;
                if (self.form.factura_sucursal.sucursal_bodega_ventas.length) {
                    self.bodegas = self.form.factura_sucursal.sucursal_bodega_ventas
                    self.form.factura_bodega = self.bodegas[0]
                    self.id_sucursal = self.form.factura_sucursal.id_sucursal

                    self.loading = true;
                    self.form.detalleProductos = [];
                    self.form.detallePago = [];
                    self.detalleForm.productox = '';
                    self.form.proforma_especifica = false;
                    self.form.proformasBusqueda = [];
                    self.proformaForm = [];

                    bodega.buscarProductosBodega({
                        id_bodega: self.form.factura_bodega.id_bodega,
                    }, data => {
                        if (data !== null) {
                            self.productos = [];
                            self.productosORG = [];
                            self.servicios = [];

                            self.productosORG = data.productos;
                            self.servicios = data.servicios;

                            self.productosORG.forEach((producto, key) => {
                                self.productos.push(producto)
                            })
                            self.servicios.forEach((servicio, key) => {
                                self.productos.push(servicio)
                            })

                            self.detalleForm.productox = ''
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'No se encuentran productos para esta bodega',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            self.detalleForm.productox = ''
                        }
                        self.loading = false
                    }, err => {
                        /* if(err.codigo_bateria){
                                        self.detalleForm.bateria_busqueda = '';
                                        self.$refs.bateria.focus();
                                        alertify.warning("No se encuentra esta batería.",5);
                                        self.detalleForm.productox = '';
                                      } */
                        self.loading = false
                    })
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'La sucursal seleccionada no tiene bodegas disponibles.',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }

              var self = this
              factura.obtenerConsecutivo({
                id_sucursal: self.id_sucursal,
                serie: self.form.serie,
              }, data => {
                if (data !== null) {
                  self.form.no_documento = data.no_documento_siguiente,
                      self.form.no_factura = data.no_factura
                } else {
                  self.form.no_documento = ''
                }
                self.loading = false
              }, err => {
                self.loading = false
              })
            },
          obtenerConsecutivo() {
            const self = this
            factura.obtenerConsecutivo({factura_sucursal: self.form.factura_sucursal, serie: self.form.serie});
            {
              self.no_documento = data.no_documento_siguiente
            }
          },
            seleccionarArea(e) {
                const areaP = e.target.value;
                const self = this;
                self.form.factura_area = areaP
            },
            seleccionarBodega() {
                const self = this;
                self.loading = true;
                self.form.detalleProductos = [];
                self.form.detallePago = [];
                self.detalleForm.productox = '';

                bodega.buscarProductosBodega({
                    id_bodega: self.form.factura_bodega.id_bodega,
                }, data => {
                    if (data !== null) {
                        self.productos = [];
                        self.productosORG = [];
                        self.servicios = [];

                        self.productosORG = data.productos;
                        self.servicios = data.servicios;

                        self.productosORG.forEach((producto, key) => {
                            self.productos.push(producto)
                        })
                        self.servicios.forEach((servicio, key) => {
                            self.productos.push(servicio)
                        })

                        self.detalleForm.productox = ''
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encuentran productos para esta bodega',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        self.detalleForm.productox = ''
                    }
                    self.loading = false
                }, err => {
                    /* if(err.codigo_bateria){
                                  self.detalleForm.bateria_busqueda = '';
                                  self.$refs.bateria.focus();
                                  alertify.warning("No se encuentra esta batería.",5);
                                  self.detalleForm.productox = '';
                                } */
                    self.loading = false
                })
            },
            cargar_detalles_producto() {
                // const productoP = e.target.value;
                const self = this;
                // self.detalleForm.productox = productoP;
                if (self.detalleForm.productox) {
                    self.detalleForm.cantidad = 1;


                    if (self.tipo_cliente === 1) {
                        self.detalleForm.precio_sugerido = Round.round(Number((self.detalleForm.productox.precio_sugerido * self.form.t_cambio)), 2);
                        self.detalleForm.precio_sugerido_me = Number(self.detalleForm.productox.precio_sugerido)
                    } else {
                        self.detalleForm.precio_sugerido = Round.round(Number((self.detalleForm.productox.precio_distribuidor * self.form.t_cambio)), 2);
                        self.detalleForm.precio_sugerido_me = Number(self.detalleForm.productox.precio_distribuidor)
                    }

                    if (self.detalleForm.productox.tipo_producto === 2) {
                        self.detalleForm.afectacionx = self.afectaciones[0]
                    }

                    self.$refs.cantidad.focus()
                    // self.detalleForm.precio_unitario = self.detalleForm.productox.costo_promedio;
                }
            },
            calcularAjuste(itemX) {
                itemX.p_ajuste = Number(itemX.afectacionx.valor);
                if (itemX.p_ajuste > 0) {
                    itemX.p_descuento = 0
                }
            },

            calcular_montos(itemX) {
                itemX.subtotal = Round.round(((Number(itemX.precio) * Number(itemX.cantidad))), 2);

                // itemX.mt_descuento = Number((Number(itemX.p_descuento / 100) *  ( Number(((Number(itemX.precio) * Number(itemX.cantidad)))  ).toFixed(2))).toFixed(2));
                itemX.mt_descuento = Number((Number(itemX.precio) * Number(itemX.cantidad)) * Number(itemX.p_descuento / 100));

                itemX.p_ajuste = Number(itemX.afectacionx.valor);

                itemX.mt_ajuste = Round.round((Number(itemX.p_ajuste / 100) * Number(((Number(itemX.precio) * Number(itemX.cantidad))))), 2);

                itemX.p_unitario = Round.round(((Number(itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste) / Number(itemX.cantidad))), 4);

                /* console.log(itemX.p_impuesto);
                          console.log(Number(itemX.p_impuesto/100));
                          console.log(itemX.subtotal-itemX.mt_descuento-itemX.mt_ajuste);
                          console.log(Number((Number(itemX.p_impuesto/100)*Number((itemX.subtotal-itemX.mt_descuento-itemX.mt_ajuste)))));
                          */
                /* let xy = Number((Number(itemX.p_impuesto/100)*Number((itemX.subtotal-itemX.mt_descuento-itemX.mt_ajuste))));
                          console.log(roundNumber.round(xy,2)); */

                itemX.mt_impuesto = Round.round(Number((Number(itemX.p_impuesto / 100) * Number((itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste)))), 2);

                itemX.total_sin_iva = Round.round(Number((itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste)), 2);

                itemX.total = Round.round((itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste + itemX.mt_impuesto), 2);

                if (!isNaN(itemX.mt_descuento)) {
                    return itemX.mt_descuento
                }
                return 0
            },

            obtenerTCParalela() {
                const self = this;
                self.loading = true;
                tc.obtenerTCParalela({
                    fecha: self.form.f_factura,
                    dias: self.form.dias_credito,
                }, data => {
                    if (data.tasa_paralela !== null) {
                        self.form.tasa_paralela = Number(data.tasa_paralela);
                        // self.form.tasa_oficial = Number(data.tasa);
                        self.form.f_vencimiento = data.fecha;
                        self.form.detalleProductos.forEach((productox, key) => {
                            productox.precio_lista = Round.round((productox.precio_lista_me * self.form.t_cambio), 2);
                            productox.precio = Round.round((productox.precio_lista_me * self.form.t_cambio), 2);
                            // console.log(productox.precio_lista);
                        })
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encuentran tasas de cambia para esta fecha.',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        self.form.t_cambio = 0;
                        self.form.f_vencimiento = self.form.f_factura;
                        self.form.detalleProductos = [];
                    }
                    self.loading = false
                }, err => {
                    if (err.fecha[0]) {
                        self.form.t_cambio = 0;
                        self.form.f_vencimiento = self.form.f_factura;
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error: ' + err.fecha[0],
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        self.loading = false
                    }
                })
            },

            /* obtenerTC(){
                      var self = this;
                      tc.obtenerTC({
                        fecha: self.form.f_factura
                      }, data => {
                        if(data.tasa_paralela !== null){
                          self.form.t_cambio = Number(data.tasa_paralela);
                        }else{
                          alertify.warning("No se encuentra tasa de cambio para esta fecha.",5);
                          self.form.t_cambio = 0;
                        }
                        self.loading = false;
                      }, err => {
                        if(err.fecha[0]){
                          self.form.t_cambio = 0;
                          alertify.warning(err.fecha[0],5);
                          self.loading = false;
                        }
                      })
                    }, */

            /* obtenerAfectacionesTodas() {
                       var self = this;
                       afectacion.obtenerTodos(
                               data => {
                                 self.afectaciones = data;
                               },
                               err => {
                                 console.log(err);
                               }
                       );
                     }, */

            obtenerMunicipios() {
                const self = this;
                self.form.municipio = null;
                if (self.formCliente.departamento.municipios_departamento) self.municipios = self.formCliente.departamento.municipios_departamento
            },

            nueva() {
                const self = this;
                factura.nueva({
                        id_sucursal: self.id_sucursal,
                    }, data => {
                        self.sucursales = data.sucursales;
                        self.vias_pago = data.vias_pago;
                        self.afectaciones = data.afectaciones;
                        self.detalleForm.afectacionx = self.afectaciones[0];
                        self.monedas = data.monedas;
                        self.bancos = data.bancos;
                        // self.proyectos = data.proyectos;
                        // self.form.factura_bodega=self.bodegas[0];
                        self.productos = [];
                        self.form.t_cambio = Number(data.t_cambio.tasa);
                        self.form.tasa_paralela = Number(data.t_cambio.tasa_paralela);
                        // self.zonas = data.zonas;
                        self.vendedores = data.vendedores;
                        // self.formCliente.zona = data.zonas[0];
                        self.departamentos = data.departamentos;
                        self.formCliente.departamento = self.departamentos[9];
                        self.municipios = self.formCliente.departamento.municipios_departamento;
                        self.formCliente.municipio = self.municipios[5];
                        self.formCliente.vendedor = data.vendedor_actual;
                        self.form.factura_sucursal = self.sucursales[0];
                        self.form.discount_max = Number(data.discount_max);
                        self.seleccionarSucursal();
                        self.loading = false
                        /* self.form.factura_bodega.productos_bodega.forEach((bodega_producto, key) => {
                                          self.productos.push({
                                            codigo_sistema: bodega_producto.producto_venta.codigo_sistema,
                                            costo_promedio:  Number(bodega_producto.producto_venta.costo_promedio),
                                            precio:Number(bodega_producto.producto_venta.precio_sugerido),
                                            descripcion:  bodega_producto.producto_venta.descripcion,
                                            id_producto:  bodega_producto.producto_venta.id_producto,
                                            tasa_impuesto: Number(bodega_producto.producto_venta.tasa_impuesto),
                                            id_bodega_producto:  self.form.factura_bodega.productos_bodega[key].id_bodega_producto,
                                            nombre_comercial:  bodega_producto.producto_venta.nombre_comercial,
                                            cantidad_disponible: Number(self.form.factura_bodega.productos_bodega[key].cantidad),
                                            unidad_medida: bodega_producto.producto_venta.unidad_medida,
                                            nombre_full: bodega_producto.producto_venta.descripcion+' - '+bodega_producto.producto_venta.codigo_barra,
                                          });
                                        }); */
                    },
                    err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Ha ocurrido un error al cargar los datos. ' + err,
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        console.log(err);
                        self.loading = false
                    })
            },

            agregarDetalle() {
                const self = this;
                if (self.detalleForm.productox && self.detalleForm.afectacionx) {
                    if (self.detalleForm.cantidad > 0 || self.detalleForm.precio_sugerido_me > 0) {
                        let cantidad = 0;
                        if (self.form.detalleProductos) {
                            self.form.detalleProductos.forEach((productox, key) => {
                                if (self.detalleForm.productox.id_producto === productox.productox.id_producto && (self.detalleForm.productox.id_bodega_producto === productox.productox.id_bodega_producto)) {
                                    cantidad = cantidad + productox.cantidad + self.detalleForm.cantidad
                                }
                            })
                        }

                        let i = 0;
                        let keyx = 0;
                        if (self.form.detalleProductos) {
                            self.form.detalleProductos.forEach((productox, key) => {
                                if ((self.detalleForm.productox.id_producto === productox.productox.id_producto)
                                    && (self.detalleForm.productox.id_bodega_producto === productox.productox.id_bodega_producto)) {
                                    i++;
                                    keyx = key
                                }
                            })
                        }
                        if (i === 0) {
                            if (cantidad <= Number(self.detalleForm.productox.cantidad_disponible)) {
                                self.form.detalleProductos.push({
                                    productox: self.detalleForm.productox,
                                    afectacionx: self.detalleForm.afectacionx,
                                    cantidad: Number(self.detalleForm.cantidad),
                                    precio_costo: Number(self.detalleForm.productox.costo_promedio),
                                    precio_costo_me: Number(self.detalleForm.productox.costo_promedio_me),
                                    precio_lista_me: Number(self.detalleForm.precio_sugerido_me),
                                    precio_lista: Round.round( Number(self.detalleForm.precio_sugerido_me) * self.form.t_cambio, 4),
                                    precio: Number(self.detalleForm.precio_sugerido_me),
                                    p_descuento: 0,
                                    mt_descuento: 0,
                                    p_impuesto: Number(self.detalleForm.productox.tasa_impuesto),
                                    mt_impuesto: 0,
                                    subtotal: 0,
                                    total: 0,
                                    total_sin_iva: 0,
                                    mt_ajuste: 0,
                                    p_ajuste: 0,
                                });
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Producto agregado correctamente.',
                                            variant: 'success',

                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                            } else {
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Se ha agregado la cantidad máximad disponible de este producto.',
                                            variant: 'warning',

                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                            }
                        } else {
                            const nuevo_total = self.form.detalleProductos[keyx].cantidad + self.detalleForm.cantidad;
                            if (nuevo_total <= self.form.detalleProductos[keyx].productox.cantidad_disponible) {
                                self.form.detalleProductos[keyx].cantidad = nuevo_total;
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Producto agregado.!',
                                            variant: 'success',

                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                            } else {
                                self.form.detalleProductos[keyx].cantidad = Number(self.form.detalleProductos[keyx].productox.cantidad_disponible);
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Se ha agregado la cantidad máximad disponible de este producto.',
                                            variant: 'warning',

                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                            }
                        }

                        self.detalleForm.productox = null;
                        self.detalleForm.afectacionx = self.afectaciones[0];
                        self.detalleForm.cantidad = 0;
                        self.detalleForm.precio_sugerido = 0;
                        self.detalleForm.subtotal = 0;
                        self.detalleForm.total = 0;
                        self.detalleForm.total_sin_iva = 0;

                        // this.$refs.producto.$el.focus();
                        this.$refs.producto.$refs.search.focus();
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Los valores para cantidad y precio deben ser mayor a cero..',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un producto.',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },
            eliminarLinea(item, index) {
                if (this.form.detalleProductos.length >= 1) {
                    this.form.detalleProductos.splice(index, 1)
                } else {
                    this.form.detalleProductos = []
                }
            },

            pagoCompleto(IdMoneda) {
                const self = this

                if (Round.round(self.form.total_final_cordobas, 2) > 0) {
                    self.form.detallePago = [];
                    /*
                                      let monto =0,monto_me=0;
                                      if(IdMoneda === 1){
                                        monto=Number(self.form.total_final_cordobas.toFixed(2));
                                        monto_me=Number((self.form.total_final_cordobas/self.form.t_cambio).toFixed(2));
                                      }else{
                                        monto=Number(self.form.total_final_cordobas.toFixed(2));
                                        monto_me=Number((self.form.total_final_cordobas/self.form.t_cambio).toFixed(2));
                                      } */

                    self.form.detallePago.push({
                        via_pagox: self.vias_pago[1],
                        moneda_x: self.monedas[Number(IdMoneda)],
                        monto: Round.round(self.form.total_final_cordobas, 2),
                        monto_me: Round.round((self.form.total_final_cordobas / self.form.t_cambio), 2),
                        banco_x: '',
                        numero_minuta: '',
                        numero_nota_credito: '',
                        numero_cheque: '',
                        numero_transferencia: '',
                        numero_recibo_pago: '',

                    })

                    self.detalleFormPago.via_pagox = '';
                    self.detalleFormPago.monto = 0;
                    self.detalleFormPago.monto_me = 0;
                    self.detalleFormPago.moneda_x = '';
                    self.detalleFormPago.banco_x = '';
                    self.detalleFormPago.numero_minuta = '';
                    self.detalleFormPago.numero_nota_credito = '';
                    self.detalleFormPago.numero_cheque = '';
                    self.detalleFormPago.numero_transferencia = '';
                    self.detalleFormPago.numero_recibo_pago = '';
                }
            },

            agregarMetodoPago() {
                const self = this;
                if (self.detalleFormPago.via_pagox && self.detalleFormPago.moneda_x) {
                    if (self.detalleFormPago.monto > 0) {
                        let i = 0;
                        let keyx = 0;
                        if (self.form.detallePago) {
                            self.form.detallePago.forEach((via_pago_x, key) => {
                                if (self.detalleFormPago.via_pagox.id_via_pago === via_pago_x.via_pagox.id_via_pago && self.detalleFormPago.moneda_x.id_moneda === via_pago_x.moneda_x.id_moneda) {
                                    i++;
                                    keyx = key
                                }
                            })
                        }
                        let monto_me = 0;
                        let
                            monto_mn = 0;

                        if (self.detalleFormPago.moneda_x.id_moneda === 1) { // metodos de pago con moneda cordobas

                            monto_mn = Number(self.detalleFormPago.monto);
                            monto_me = Round.round((self.detalleFormPago.monto / self.form.t_cambio), 2)

                        } else if (self.detalleFormPago.moneda_x.id_moneda === 2) { // metodos de pago con moneda dolares
                            monto_me = Number(self.detalleFormPago.monto);
                            // const monto_mn_2 = (Number(self.form.total_final_cordobas) / Number(self.form.t_cambio)).toFixed(2);
                            // console.log(`calculo 4 decimales${monto_mn_2}`)
                            monto_mn = Round.round((self.detalleFormPago.monto * self.form.t_cambio), 2);
                            // console.log('monto me: ' + monto_me);
                            // console.log('monto mn: ' + monto_mn);
                            // monto_mn = Number((monto_mn_2 * self.form.t_cambio).toFixed(2))
                        }

                        if (i === 0) {
                            self.form.detallePago.unshift({
                                via_pagox: self.detalleFormPago.via_pagox,
                                moneda_x: self.detalleFormPago.moneda_x,
                                monto: Number(monto_mn),
                                monto_me: Number(monto_me),
                                banco_x: self.detalleFormPago.banco_x,
                                numero_minuta: self.detalleFormPago.numero_minuta,
                                numero_nota_credito: self.detalleFormPago.numero_nota_credito,
                                numero_cheque: self.detalleFormPago.numero_cheque,
                                numero_transferencia: self.detalleFormPago.numero_transferencia,
                                numero_recibo_pago: self.detalleFormPago.numero_recibo_pago,

                            });
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Método de pago agregado.',
                                        variant: 'success',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                        } else {
                            if (self.detalleFormPago.moneda_x.id_moneda === 1) {
                                monto_mn = Number(self.detalleFormPago.monto);
                                monto_me = Round.round(Number((self.detalleFormPago.monto / self.form.t_cambio)), 2);

                                self.form.detallePago[keyx].monto = Round.round((self.form.detallePago[keyx].monto + self.detalleFormPago.monto), 2);
                                self.form.detallePago[keyx].monto_me = Round.round((self.form.detallePago[keyx].monto / self.form.t_cambio), 2)

                            } else if (self.detalleFormPago.moneda_x.id_moneda === 2) {
                                self.form.detallePago[keyx].monto_me = Round.round(self.form.detallePago[keyx].monto_me + self.detalleFormPago.monto, 2);
                                self.form.detallePago[keyx].monto = Round.round(self.form.detallePago[keyx].monto_me * self.form.t_cambio, 2);
                            }

                            // let nuevo_monto_total = self.form.detallePago[keyx].monto + self.detalleFormPago.monto;

                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Pago agregado.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                        }

                        self.detalleFormPago.via_pagox = '';
                        self.detalleFormPago.monto = 0;
                        self.detalleFormPago.monto_me = 0;
                        self.detalleFormPago.moneda_x = '';
                        self.detalleFormPago.banco_x = '';
                        self.detalleFormPago.numero_minuta = '';
                        self.detalleFormPago.numero_nota_credito = '';
                        self.detalleFormPago.numero_cheque = '';
                        self.detalleFormPago.numero_transferencia = '';
                        self.detalleFormPago.numero_recibo_pago = '';
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'El monto debe ser mayor a cero.',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                    }
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un método y una moneda.',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                }
            },

            eliminarLineaPago(item, index) {
                if (this.form.detallePago.length >= 1) {
                    this.form.detallePago.splice(index, 1)
                } else {
                    this.form.detallePago = []
                }
            },

            procesar_factura() {
                const self = this
                self.$swal.fire({
                    title: 'Esta seguro de completar la factura?',
                    text: 'Detalles de la factura: ',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, confirmar',
                    cancelButtonText: 'Cancelar',
                }).then(result => {
                    if (result.value) {
                        if (this.descuento_maximo > this.form.discount_max) {
                            self.$swal.fire({
                                title: 'Autorización de descuento',
                                text: 'El descuento digitado para uno de los productos supera el limite establecido, ingrese código de autorización para poder facturar',
                                icon: 'warning',
                                input: 'text',
                                inputAttributes: {
                                    autocapitalize: 'off',
                                },
                                showLoaderOnConfirm: true,
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Si, confirmar',
                                cancelButtonText: 'Cancelar',
                            }).then(result => {
                                if (result.value) {
                                    this.form.codigo_autorizacion = result.value;
                                    factura.registrar(
                                        self.form,
                                        // eslint-disable-next-line no-unused-vars
                                        data => {
                                            this.$swal.fire(
                                                'Factura Registrada!',
                                                'La Factura fue registrada correctamente',
                                                'success',
                                            ).then(result2 => {
                                                if (result2.value) {
                                                    this.$router.push({
                                                        name: 'cajabanco-facturas',
                                                    })
                                                }
                                            })
                                        },
                                        err => {
                                            // console.log(err);
                                            if (err.result === 'invalid_code') {
                                                this.$toast({
                                                        component: ToastificationContent,
                                                        props: {
                                                            title: 'Notificación',
                                                            icon: 'InfoIcon',
                                                            text: err.code,
                                                            variant: 'warning',

                                                        }
                                                    },
                                                    {
                                                        position: 'bottom-right'
                                                    });
                                            } else if (err.status === 'fields_empty') {
                                                self.errorMessages = err.result;
                                                this.$toast({
                                                        component: ToastificationContent,
                                                        props: {
                                                            title: 'Notificación',
                                                            icon: 'InfoIcon',
                                                            text: 'Favor revise los datos faltantes.',
                                                            variant: 'warning',

                                                        }
                                                    },
                                                    {
                                                        position: 'bottom-right'
                                                    });
                                            } else {
                                                this.$toast({
                                                        component: ToastificationContent,
                                                        props: {
                                                            title: 'Notificación',
                                                            icon: 'InfoIcon',
                                                            text: 'Ha ocurrido un error inesperado.',
                                                            variant: 'warning',

                                                        }
                                                    },
                                                    {
                                                        position: 'bottom-right'
                                                    });
                                            }

                                            self.btnAction = 'Facturar'
                                        },
                                    )
                                } else if (result.dismiss === 'cancel') {
                                    this.$swal({
                                        title: 'Cancelado',
                                        text: 'No se ha realizado el registro',
                                        icon: 'error',
                                        customClass: {
                                            confirmButton: 'btn btn-success',
                                        },
                                    });
                                    self.btnAction = 'Facturar'
                                }
                            })
                        } else {
                            factura.registrar(
                                self.form,
                                // eslint-disable-next-line no-unused-vars
                                data => {
                                    this.$swal.fire(
                                        'Factura Registrada!',
                                        'La Factura fue registrada correctamente',
                                        'success',
                                    ).then(result2 => {
                                        if (result2.value) {
                                            this.$router.push({
                                                name: 'cajabanco-facturas',
                                            })
                                        }
                                    })
                                },
                                err => {
                                    if (err.status === 'fields_empty') {
                                        self.errorMessages = err.result;
                                        this.$toast({
                                                component: ToastificationContent,
                                                props: {
                                                    title: 'Notificación',
                                                    icon: 'InfoIcon',
                                                    text: 'Favor revise los datos faltantes.',
                                                    variant: 'warning',

                                                }
                                            },
                                            {
                                                position: 'bottom-right'
                                            });
                                    } else {
                                        this.$toast({
                                                component: ToastificationContent,
                                                props: {
                                                    title: 'Notificación',
                                                    icon: 'InfoIcon',
                                                    text: 'Ha ocurrido un error inesperado.',
                                                    variant: 'warning',

                                                }
                                            },
                                            {
                                                position: 'bottom-right'
                                            });
                                    }
                                    self.btnAction = 'Facturar'
                                },
                            )
                        }
                    } else if (result.dismiss === 'cancel') {
                        this.$swal({
                            title: 'Cancelado',
                            text: 'No se ha realizado el registro',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-success',
                            },
                        });
                        self.btnAction = 'Facturar'
                    }
                })
            },

            registrar() {
                const self = this;
                self.btnAction = 'Registrando, espere....';

                /* facturas de contado */
                if (self.form.id_tipo === 1) {
                    if (this.form.currency_id === 1) {
                        if (/* self.form.pago_vuelto_mn >= 0 && self.form.total_final_cordobas > 0  && */self.form.pago_pendiente_mn === 0) {
                            this.procesar_factura()
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Las facturas de contado deben ser pagadas en su totalidad.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            // self.errorMessages.serie = Array('Error serie');
                            self.btnAction = 'Facturar'
                        }
                    } else {
                        this.procesar_factura()
                    }

                }

                /* facturas de credito */
                if (self.form.id_tipo === 2) {
                    if (self.form.total_final > 0 ) { //&& (self.form.pago_pendiente_mn > 1 || self.form.pago_pendiente > 0)
                        if (self.form.factura_cliente) {
                            if (self.form.factura_cliente.limite_credito_me > 0) {
                                if (self.form.pago_pendiente <= Number(self.form.factura_cliente.monto_credito_dol_disponible) || self.form.pago_pendiente <= Number(self.form.proforma_cliente.monto_credito_dol_disponible)) {
                                    self.procesar_factura()
                                } else {
                                    /* alertify.warning(`El monto máximo actual de crédito de este cliente es C$ ${Number(self.form.factura_cliente.monto_credito_disponible).toFixed(2)
                                     } , el monto de ésta factura supera ese monto`, 5);*/

                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'El monto máximo actual de crédito de este cliente es $ ' + Round.round(this.form.factura_cliente.monto_credito_dol_disponible, 2) + ' El monto de la factura supera este limite.',
                                                variant: 'warning',

                                            }
                                        },
                                        {
                                            position: 'bottom-right'
                                        });
                                    self.btnAction = 'Facturar'
                                }
                            } else {
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'El cliente seleccionado no tiene un limite de crédito grabado.',
                                            variant: 'warning',

                                        }
                                    },
                                    {
                                        position: 'bottom-right'
                                    });
                                self.btnAction = 'Facturar'
                            }

                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'Debe seleccionar un cliente.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            self.btnAction = 'Facturar'
                        }
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Las facturas de crédito deben tener saldo a pagar.',
                                    variant: 'warning',

                                }
                            },
                            {
                                position: 'bottom-right'
                            });
                        // self.errorMessages.serie = Array('Error serie');
                        self.btnAction = 'Facturar'
                    }
                }

                /* Factura por anticipo*/
                if (self.form.id_tipo === 4) {
                        if (self.form.total_final ){
                            this.procesar_factura()
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'AlertTriangleIcon',
                                        text: 'El monto total de los recibos no coincide con lo facturado.',
                                        variant: 'warning',

                                    }
                                },
                                {
                                    position: 'bottom-right'
                                });
                            // self.errorMessages.serie = Array('Error serie');
                            self.btnAction = 'Facturar'
                        }


                }
            },

            registrarCliente() {
                const self = this;
                self.btnActionCliente = 'Registrando, espere....';

                if (!self.registrandoCliente) self.registrandoCliente = true;
                cliente.registrarBasico(self.formCliente, data => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Cliente registrado exitosamente.',
                                variant: 'success',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    // console.log(data);
                    self.form.factura_cliente = data;
                    self.form.factura_vendedor = self.formCliente.vendedor;
                    self.form.tipo_identificacion = self.form.factura_cliente.tipo_persona;
                    if (self.form.factura_cliente.tipo_persona === 1) {
                        self.form.identificacion = self.form.factura_cliente.numero_cedula
                    } else {
                        self.form.identificacion = self.form.factura_cliente.numero_ruc
                    }
                    self.btnActionCliente = 'Registrar Cliente';
                    // self.form.factura_vendedor
                    self.registrandoCliente = false;

                    self.formCliente.tipo_persona = 1;
                    self.formCliente.nombre_comercial = '';
                    self.formCliente.vendedor = '';
                    self.formCliente.numero_ruc = '';
                    self.formCliente.numero_cedula = '';
                    self.formCliente.direccion = '';
                    self.formCliente.id_tipo_cliente = 1;
                    self.formCliente.telefono = '';
                    self.formCliente.correo = '';
                    self.formCliente.municipio = '';
                    self.formCliente.giro_negocio = '';
                    self.formCliente.zona = '';
                    self.formCliente.id_impuesto = 1;
                    self.formCliente.tipo_contribuyente = 1;
                    self.formCliente.retencion_ir = true;
                    self.formCliente.retencion_imi = true;
                    self.formCliente.clasificacion = 1;
                    self.formCliente.permite_credito = false;
                    self.formCliente.plazo_credito = 0;
                    self.formCliente.limite_credito = 0;
                    self.formCliente.porcentaje_descuento = 0;
                    self.formCliente.observaciones = '';
                    self.formCliente.permite_consignacion = false;
                    self.formCliente.tramite_cheque = 15;

                    self.$refs['modal'].hide()
                }, err => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Ha ocurrido un error. ' + err,
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-right'
                        });
                    self.errorMessages = err;
                    self.registrandoCliente = false;
                    self.btnActionCliente = 'Registrar Cliente'
                })
            },

            seleccionarTipo() {
                const self = this;
                if(self.form.id_tipo === 4){
                    self.form.permite_anticipo = true;
                }else {
                    self.form.permite_anticipo = false;
                }
                if (self.form.id_tipo === 1 || self.form.id_tipo === 4) {
                    self.form.dias_credito = 0
                } else {
                    self.form.aplicaIR = false;
                    self.form.aplicaIMI = false;

                    self.form.mt_retencion = 0;
                    self.form.mt_retencion_imi = 0;

                    self.form.doc_exoneracion_ir = '';
                    self.form.doc_exoneracion_imi = '';

                    self.form.dias_credito = 0;
                    self.form.dias_credito = self.plazo_maximo_credito
                }
                self.obtenerTCParalela()
                /* calcular fecha */
            },
          onDateSelect(date) {
            this.form.f_factura = this.form.f_facturax;
            this.obtenerTC();
          },
          obtenerTC() {
            // console.log('ejecutando obtener tc con fecha: ' + this.form.fecha_entrada_x);
            const self = this;
            tc.obtenerTC({
              fecha: self.form.f_factura
            }, data => {
              if (data.tasa !== null) {
                self.form.t_cambio = Number(data.tasa);
              } else {
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'InfoIcon',
                        text: 'No se encuentran tasas de cambia para esta fecha.',
                        variant: 'warning',

                      }
                    },
                    {
                      position: 'bottom-right'
                    });
                self.form.t_cambio = 0;
              }
              self.loading = false;
            }, err => {
              if (err.fecha) {
                self.form.t_cambio = 0;
                this.$toast({
                      component: ToastificationContent,
                      props: {
                        title: 'Notificación',
                        icon: 'InfoIcon',
                        text: 'No se encuentran tasas de cambia para esta fecha.',
                        variant: 'warning',

                      }
                    },
                    {
                      position: 'bottom-right'
                    });
                self.loading = false;
              }
            })
          },

        },
        mounted() {
            // this.obtenerAfectacionesTodas();
            // this.obtenerTodasBodegasProductos();
            this.nueva()
        },
    }
</script>
<style lang="scss">

    @import '@core/scss/vue/libs/vue-select.scss';
    @import '@core/scss/vue/libs/vue-flatpicker.scss';

    .btn-agregar {
        margin-top: 1.6rem;
    }

    .check-label2 {
        margin-top: 30px;
    }
</style>
