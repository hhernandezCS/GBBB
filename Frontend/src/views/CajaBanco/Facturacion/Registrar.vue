<template>
    <b-card>

        <template>
            <b-row>
                <!--Datos de cliente-->
                <b-col
                        lg="6"
                        md="6"
                >

                    <!-- Aquí van los datos del cliente del collapse -->

                    <template>
                        <div>
                            <b-alert
                                    show
                                    variant="success"
                            >
                                <div class="alert-body">
                                    <b-button
                                            variant="link"
                                            class="ml-auto float-right"
                                            @click="isCollapsed = !isCollapsed"
                                    >
                                        <feather-icon
                                                icon="MenuIcon"
                                                size="21"
                                                style="margin-top: -10px; margin-left: 5px;"
                                        />
                                    </b-button>
                                    <span><strong>Datos del Cliente</strong></span>
                                </div>
                            </b-alert>
                            <b-collapse v-model="isCollapsed">

                                <div>
                                    <div>
                                        <div class="form-group">

                                            <label> Cliente</label>
                                            <a
                                                    v-b-tooltip.hover.top="'Registrar cliente nuevo'"
                                                    class="btn-agregar col-sm-3"
                                                    variant="success"
                                                    @click="abrirModal"
                                            >
                                                <feather-icon icon="PlusCircleIcon"/>
                                                Nuevo
                                            </a>
                                            <div class="form-group">
                                                <v-select
                                                        v-model="form.factura_cliente"
                                                        :filterable="false"
                                                        :options="clientes"
                                                        label="nombre_comercial"
                                                        style="width: 100%;"
                                                        @input="seleccionarCliente"
                                                        @search="onSearch"
                                                        :clearable="false"
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
                                                            v-for="message in errorMessages.factura_cliente"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>
                                    <div>
                                        <!--                    <div class="form-group">
                                          <label>Tipo Cliente</label>
                                          <b-form-select v-model.number="form.tipo_identificacion">
                                            <option value="1">
                                              Natural
                                            </option>
                                            <option value="2">
                                              Jurídico
                                            </option>
                                          </b-form-select>
                                        </div>-->
                                    </div>
                                    <!--                  <div>
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
                                    </div>-->
                                    <template v-if="form.id_tipo !== 4">

                                        <div class="form-group">
                                            <label> Identificación</label>
                                            <input
                                                    v-model="form.identificacion"
                                                    class="form-control"
                                                    placeholder="Número Identificación"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.identificacion"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>

                                    </template>
                                    <template v-else>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label> Listado de Documentos pendientes</label>
                                                <div class="form-group">
                                                    <v-select
                                                            ref="cuenta"
                                                            v-model="detalleForm.cuentax"
                                                            :allow-empty="false"
                                                            :options="cuentas"
                                                            :searchable="true"
                                                            label="textdol"
                                                            placeholder="Selecciona una cuenta para pagar"
                                                            track-by="id_cuentaxcobrar"
                                                            @input="cargar_detalles_cuenta()"
                                                    >
                                                        <template slot="no-options">
                                                            No se encontraron documentos pendientes para este cliente
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <b-alert
                                                        show
                                                        variant="danger"
                                                >
                                                    <ul class="error-messages">
                                                        <li
                                                                v-for="message in errorMessages.cuentax"
                                                                :key="message"
                                                                v-text="message"
                                                        />
                                                    </ul>
                                                </b-alert>

                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for>Saldo abonado C$</label>
                                                <label class="form-control"> {{
                                                    detalleForm.cuentax ? detalleForm.cuentax.saldo_actual_me : 0 |
                                                    formatMoney(2)
                                                    }}</label>

                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for>Monto a facturar C$</label>
                                                <input
                                                        ref="abono"
                                                        v-model.number="detalleForm.monto_me"
                                                        class="form-control"
                                                        min="0"
                                                        type="number"
                                                        @change="detalleForm.monto_me = detalleForm.cuentax? Math.max(Math.min(Number(!isNaN(detalleForm.monto_me)?detalleForm.monto_me.toFixed(2):0), Number(Number(detalleForm.cuentax.saldo_actual_me).toFixed(2))), 1):0"
                                                >
                                                <b-alert
                                                        show
                                                        variant="danger"
                                                >
                                                    <ul class="error-messages">
                                                        <li
                                                                v-for="message in errorMessages.monto"
                                                                :key="message"
                                                                v-text="message"
                                                        />
                                                    </ul>
                                                </b-alert>

                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for>&nbsp;</label>
                                                <b-button
                                                        ref="agregar"
                                                        class="btn-agregar"
                                                        variant="info"
                                                        @click="agregarDetalleRecibo"
                                                >
                                                    Agregar
                                                </b-button>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.detalleCuentasxCobrar"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                            <table class="table table-bordered table-hover table-responsive">
                                                <thead>
                                                <tr>
                                                    <th/>
                                                    <th
                                                            v-if="form.tipo_recibo ==='3'"
                                                            style="min-width:50px"
                                                    >
                                                        Documento Origen
                                                    </th>
                                                    <th style="min-width:300px">
                                                        Concepto
                                                    </th>
                                                    <th style="min-width:100px">
                                                        Subtotal C$
                                                    </th>
                                                    <th style="min-width:100px">
                                                        Retención IR C$
                                                    </th>
                                                    <th style="min-width:100px">
                                                        Retención IMI C$
                                                    </th>
                                                    <th style="min-width:150px">
                                                        Monto Abono C$
                                                    </th>
                                                    <th
                                                            v-if="form.tipo_recibo ==='3'"
                                                            colspan="2"
                                                            style="min-width:300px"
                                                    >Saldo Pendiente
                                                        C$
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <template v-for="(item, index) in form.detalleCuentasxCobrar">
                                                    <tr>
                                                        <td>
                                                            <b-button
                                                                    v-b-tooltip.hover.top="'Eliminar línea'"
                                                                    variant="outline-danger"
                                                                    @click="eliminarLineaRecibo(item, index)"
                                                            >
                                                                <feather-icon icon="TrashIcon"/>
                                                            </b-button>
                                                        </td>
                                                        <td
                                                                v-if="form.tipo_recibo ==='3'"
                                                                style="min-width:150px"
                                                        >
                                                            <input
                                                                    v-model="item.cuentax.no_documento_origen"
                                                                    class="form-control"
                                                                    disabled
                                                            >
                                                        </td>

                                                        <td>
                                                            {{ item.descripcion_pago }}
                                                        </td>

                                                        <td
                                                                class="text-center"
                                                                style="width: 12%"
                                                        >
                                                            <input
                                                                    v-model.number="item.monto_subtotal"
                                                                    class="form-control"
                                                                    disabled
                                                                    min="0"
                                                            >
                                                        </td>
                                                        <td
                                                                class="text-center"
                                                                style="width: 12%"
                                                        >
                                                            <input
                                                                    v-model.number="item.monto_retencion_ir"

                                                                    v-b-tooltip.hover.top="'No. Documento Exoneración:'+item.doc_exoneracion_ir"
                                                                    class="form-control"
                                                            >
                                                        </td>
                                                        <td
                                                                class="text-center"
                                                                style="width: 12%"
                                                        >
                                                            <input
                                                                    v-model.number="item.monto_retencion_imi"

                                                                    v-b-tooltip.hover.top="'No. Documento Exoneración:'+item.doc_exoneracion_imi"
                                                                    class="form-control"
                                                            >
                                                        </td>
                                                        <td class="text-center">
                                                            <!-- Para recibos por cobro a clientes-->
                                                            <input
                                                                    v-if="form.tipo_recibo ==='1'"
                                                                    v-model.number="item.monto_me"
                                                                    class="form-control"
                                                                    min="0"
                                                                    @change="establecerConcepto(item)"
                                                            >
                                                            <!-- Para recibos por anticipos de clientes-->
                                                            <input
                                                                    v-if="form.tipo_recibo ==='2'"
                                                                    v-model.number="item.monto_me"
                                                                    class="form-control"
                                                                    min="0"
                                                                    readonly
                                                            >
                                                            <b-alert
                                                                    show
                                                                    variant="danger"
                                                            >
                                                                <ul class="error-messages">
                                                                    <li
                                                                            v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.monto`]"
                                                                            :key="message"
                                                                            v-text="message"
                                                                    />
                                                                </ul>
                                                            </b-alert>

                                                        </td>
                                                        <td
                                                                v-if="form.tipo_recibo ==='1'"
                                                                class="text-center"
                                                                colspan="2"
                                                        >
                                                            <strong>$ {{ calcularSaldoX(item)| formatMoney(2)
                                                                }}</strong>
                                                        </td>

                                                    </tr>
                                                    <tr/>
                                                </template>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="4"/>
                                                    <td class="text-right">
                                                        <strong> Total a pagar C$</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{ total_a_pagar| formatMoney(2) }}</strong>
                                                    </td>
                                                    <td
                                                            v-if="form.tipo_recibo ==='1'"
                                                            class="text-right"
                                                    ><strong> Total saldo pendiente
                                                        C$</strong>
                                                    </td>
                                                    <td
                                                            v-if="form.tipo_recibo ==='1'"
                                                            class="text-center"
                                                    >
                                                        <strong>{{ form.saldo_total_me| formatMoney(2) }}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"/>
                                                    <td class="text-right">
                                                        <strong> Total a pagar C$</strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{ form.monto_total| formatMoney(2) }}</strong>
                                                    </td>
                                                    <td
                                                            v-if="form.tipo_recibo ==='1'"
                                                            class="text-right"
                                                    ><strong> Total saldo pendiente
                                                        C$</strong>
                                                    </td>
                                                    <td
                                                            v-if="form.tipo_recibo ==='1'"
                                                            class="text-center"
                                                    >
                                                        <strong>{{ form.saldo_total| formatMoney(2) }}</strong>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    </template>
                                </div>
                            </b-collapse>
                        </div>
                    </template>

                </b-col>
                <!--Datos de la factura-->
                <b-col
                        lg="6"
                        md="6"
                >
                    <template>
                        <div>
                            <b-alert
                                    show
                                    variant="success"
                            >
                                <div class="alert-body">
                                    <b-button
                                            variant="link"
                                            class="ml-auto float-right"
                                            @click="isCollapsed_df = !isCollapsed_df"
                                    >
                                        <feather-icon
                                                icon="MenuIcon"
                                                size="21"
                                                style="margin-top: -10px; margin-left: 5px;"
                                        />
                                    </b-button>
                                    <span><strong>Datos de la Factura</strong></span>
                                </div>
                            </b-alert>
                            <b-collapse v-model="isCollapsed_df">
                                <div>
                                    <template v-if="proformaFormHeader.es_nuevo === true">
                                        <b-row>
                                            <!--                      <div class="col-sm-4">
                                              <div class="form-group">
                                                <label> Sucursal</label>
                                                <div class="form-group">
                                                  &lt;!&ndash; <typeahead :initial="form.factura_sucursal" :trim="80" :url="sucursalesBusquedaURL" @input="seleccionarSucursal" style="width: 100%;"></typeahead>&ndash;&gt;
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
                                            </div>-->

                                            <!--                      <div class="col-sm-4">
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
                                            </div>-->
                                            <!--                      <div class="col-sm-4">
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
                                            </div>-->
                                            <!--                      <div class="col-sm-4">
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
                                            </div>-->

                                            <!--                      <div class="col-sm-4">
                                              <div class="form-group">
                                                <label>Tipo Factura</label>
                                                <b-form-select @change="seleccionarTipo" v-model.number="form.id_tipo">
                                                  <option value="1">Contado</option>
                                                  <option :disabled="!clienteCredito" value="2">Crédito</option>
                                                  <option value="4">Anticipo</option>
                                                </b-form-select>
                                              </div>
                                            </div>-->

                                            <!--                      <div class="col-sm-4">
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
                                            </div>-->

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label> Vendedor</label>
                                                    <!-- <div class="form-group">
                                                           <typeahead :initial="form.factura_vendedor" :trim="80" :url="vendedoresBusquedaURL" @input="seleccionarVendedor" style="width: 100%;"></typeahead>

                                                         </div>-->
                                                    <v-select

                                                            v-model="form.factura_vendedor"
                                                            :options="vendedores"
                                                            label="nombre_completo"
                                                            :clearable="false"
                                                    >
                                                        <template slot="no-options"/>
                                                    </v-select>
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.factura_vendedor"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for>Fecha Emitida</label>

                                                    <b-form-group>
                                                        <flat-pickr
                                                                v-model="form.f_facturax"
                                                                class="form-control"
                                                                :config="{ dateFormat: 'Y-m-d'}"

                                                                placeholder="f. emitida"
                                                                @input="onDateSelect()"
                                                        />
                                                    </b-form-group>

                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.f_factura"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </div>
                                            </div>

                                            <!--                      <div class="col-sm-4">
                                              <div class="form-group">
                                                <label for>Fecha Vencimiento</label>
                                                <input
                                                    class="form-control"
                                                    disabled
                                                    type="text"
                                                    v-model="form.f_vencimiento"
                                                >
                                              </div>
                                            </div>-->

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for>T/C</label>
                                                    <input
                                                            v-model.number="form.t_cambio"
                                                            min="0"
                                                            class="form-control"
                                                            type="number"
                                                            :readonly="true"
                                                    >
                                                </div>
                                            </div>

                                            <!--                      <div class="col-sm-4 ">
                                              <label for=""></label>
                                              <b-form-checkbox
                                                  @input="deseleccionar"
                                                  class="mx-lg-1 mb-sm-1 mt-sm-1"
                                                  v-model="form.proforma_especifica"
                                              >
                                                Tiene proforma
                                              </b-form-checkbox>
                                            </div>-->
                                            <!--                      <div class="col-sm-4">
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
                                                    &lt;!&ndash;v-on:input="$emit('input', $event.target.value)" Emitir evento a v-model de vue-select&ndash;&gt;
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
                                            </div>-->
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

                                            <!--                      <div class="col-sm-12">
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
                                            </div>-->

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
                                                                v-model="form.proforma_sucursal"
                                                                :disabled="true"
                                                                :options="sucursales"
                                                                label="descripcion"
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
                                                                    v-for="message in errorMessages.factura_sucursal"
                                                                    :key="message"
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
                                                            v-model="form.proforma_sucursal.serie"
                                                            class="form-control"
                                                            disabled
                                                            placeholder="Serie"
                                                    >
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.serie"
                                                                    :key="message"
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
                                                            v-model="no_documento"
                                                            class="form-control"
                                                            disabled
                                                            placeholder="Consecutivo"
                                                    >
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.no_documento"
                                                                    :key="message"
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
                                                            v-model="form.proforma_bodega"
                                                            :disabled="true"
                                                            :options="bodegas"
                                                            label="descripcion"
                                                            @input="seleccionarBodega()"
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
                                                                    v-for="message in errorMessages.factura_bodega"
                                                                    :key="message"
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
                                                            v-model.number="proformaFormHeader.id_tipo"
                                                            @change="seleccionarTipo"
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
                                                            v-model.number="form.dias_credito"
                                                            :disabled="!(form.id_tipo===2)"
                                                            @change="obtenerTCParalela"
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
                                                                    v-for="message in errorMessages.plazo_credito"
                                                                    :key="message"
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

                                                            v-model="form.proforma_vendedor"
                                                            :options="vendedores"
                                                            label="nombre_completo"
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
                                                                    v-for="message in errorMessages.factura_vendedor"
                                                                    :key="message"
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
                                                                v-model="form.f_factura"
                                                                :config="{ dateFormat: 'Y-m-d'}"
                                                                class="form-control"
                                                                placeholder="f. emitida"
                                                        />
                                                    </b-form-group>
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.f_factura"
                                                                    :key="message"
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
                                                            v-model="form.f_vencimiento"
                                                            class="form-control"
                                                            disabled
                                                            type="text"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for>T/C</label>
                                                    <input
                                                            v-model="form.tasa_paralela"
                                                            class="form-control"
                                                            disabled
                                                            type="text"
                                                    >
                                                </div>
                                            </div>

                                            <div class="col-sm-10">
                                                <div class="form-group">
                                                    <label for>Observaciones</label>
                                                    <input
                                                            v-model="proformaFormHeader.observacion"
                                                            class="form-control"
                                                            type="text"
                                                    >
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.observacion"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <b-form-checkbox
                                                        v-model="form.proforma_especifica"
                                                        class="mx-lg-1 mb-sm-1 mt-sm-1"
                                                        @input="deseleccionar"
                                                >
                                                    Tiene proforma
                                                </b-form-checkbox>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label> Proforma</label>
                                                    <div class="form-group">
                                                        <v-select
                                                                v-if="form.proforma_especifica"
                                                                v-model="form.proformasBusqueda"
                                                                :trim="80"
                                                                :url="proformasBusquedaURL"
                                                                label="no_documento"
                                                                style="width: 100%;"
                                                                @input="seleccionarProforma"
                                                        />
                                                        <input
                                                                v-if="!form.proforma_especifica"
                                                                class="form-control"
                                                                disabled
                                                        >
                                                    </div>
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages.proformasBusqueda"
                                                                    :key="message"
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
                                </div>
                            </b-collapse>

                        </div>
                    </template>

                </b-col>

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
                    <div class="form-group d-flex">
                        <b-form-group
                                label="Tipo Código de Barras"
                                v-slot="{ ariaDescribedby }"
                        >
                            <b-form-radio-group
                                    id="btn-radios-3"
                                    v-model="selected"
                                    :options="optionsTP"
                                    :aria-describedby="ariaDescribedby"
                                    button-variant="outline-primary"
                                    size="lg"
                                    name="radio-btn-outline"
                                    buttons
                            ></b-form-radio-group>
                        </b-form-group>
                    </div>
                </div>
            </div>
        </b-row>
        <b-row>
            <div class="col-sm-6">
                <div class="form-group">
                    <label> Producto</label>
                    <div class="form-group">

                        <typeahead
                                :initial="detalleForm.productox"
                                :url="productosBodegaBusquedaURL"
                                :params="{id_bodega: form.factura_bodega.id_bodega, is_carne : this.selected}"
                                style="width: 100%;"
                                :tabindex="1"
                                :focused="foc"
                                @input="cargar_detalles_producto"
                        />

                    </div>
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    v-for="message in errorMessages.productox"
                                    :key="message"
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
                            ref="cantidad"
                            v-model.number="detalleForm.cantidad"
                            class="form-control"
                            min="0"
                            type="number"
                            @change="detalleForm.cantidad = Math.round(Math.max(Math.min(detalleForm.cantidad, (!isNaN(detalleForm.productox.cantidad_disponible))?detalleForm.productox.cantidad_disponible:0 ), 0))"

                    >
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    v-for="message in errorMessages.cantidadx"
                                    :key="message"
                                    v-text="message"
                            />
                        </ul>
                    </b-alert>

                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for>Precio Lista C$ </label>
                    <input
                            v-model.number="detalleForm.precio_sugerido"
                            class="form-control"
                            min="0"
                            type="number"
                    >
                    <b-alert
                            show
                            variant="danger"
                    >
                        <ul class="error-messages">
                            <li
                                    v-for="message in errorMessages.preciox"
                                    :key="message"
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
                            ref="agregar"
                            v-b-tooltip.hover.top="'Agregar producto al detalle'"
                            class="btn-agregar"
                            variant="info"
                            @click="agregarDetalle"
                            id="my-button"
                    >
                        <feather-icon icon="PlusCircleIcon"/>
                        Agregar
                    </b-button>
                </div>
            </div>
        </b-row>

        <b-row>
            <div class="col-sm-12">
                <b-alert
                        show
                        variant="danger"
                >
                    <ul class="error-messages">
                        <li
                                v-for="message in errorMessages.detalleProductos"
                                :key="message"
                                v-text="message"
                        />
                    </ul>
                </b-alert>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="table-number"/>
                            <th>Producto</th>
                            <th class="table-number">
                                Cantidad
                            </th>
                            <th class="table-number">
                                Descuento %
                            </th>
                            <th>Descuento C$</th>
                            <th>Precio Unit. C$</th>
                            <th>Valor C$</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="(item, index) in form.detalleProductos">
                            <tr>
                                <td>
                                    <b-button
                                            variant="danger"
                                            @click="eliminarLinea(item, index)"
                                    >
                                        <feather-icon icon="TrashIcon"/>
                                    </b-button>
                                </td>
                                <td>
                                    <input
                                            v-model="item.productox.descripcion"
                                            class="form-control mt-1"
                                            disabled
                                    >
                                    <b-alert
                                            show
                                            variant="danger"
                                    >
                                        <ul class="error-messages">
                                            <li
                                                    v-for="message in errorMessages[`detalleProductos.${index}.productox.id_producto`]"
                                                    :key="message"
                                            >
                                                {{ message }}
                                            </li>
                                        </ul>
                                    </b-alert>
                                </td>
                                <td>
                                    <input
                                            v-model.number="item.cantidad"
                                            class="form-control mt-1"
                                            min="0"
                                            type="number"
                                            @change="calcantidad(item)"

                                    >
                                    <b-alert
                                            show
                                            variant="danger"
                                    >
                                        <ul class="error-messages">
                                            <li
                                                    v-for="message in errorMessages[`detalleProductos.${index}.cantidad`]"
                                                    :key="message"
                                            >
                                                {{ message }}
                                            </li>
                                        </ul>
                                    </b-alert>
                                </td>
                                <td>
                                    <input
                                            v-model.number="item.p_descuento"
                                            :disabled="item.p_ajuste > 0"
                                            class="form-control mt-1"
                                            type="number"
                                            min="0"
                                    >
                                    <b-alert
                                            show
                                            variant="danger"
                                    >
                                        <ul class="error-messages">
                                            <li
                                                    v-for="message in errorMessages[`detalleProductos.${index}.p_descuento`]"
                                                    :key="message"
                                            >
                                                {{ message }}
                                            </li>
                                        </ul>
                                    </b-alert>
                                </td>
                                <td>
                                    <strong>{{ calcular_montos(item) | formatMoney(2) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ item.p_unitario }}</strong>
                                </td>
                                <td>
                                    <strong>{{ item.total_sin_iva | formatMoney(2) }}</strong>
                                </td>
                            </tr>
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
                                                v-for="message in errorMessages.error_general"
                                                :key="message"
                                        >
                                            {{ message }}
                                        </li>
                                    </ul>
                                </b-alert>
                            </td>
                            <td>Subtotal</td>
                            <td><strong>{{ total_subt_sin_iva | formatMoney(2) }}</strong></td>
                        </tr>
                        <!--<tr>
              <td></td>
              <td>
                <input
                    :disabled="!form.aplicaIR"
                    class="form-control"
                    v-model="form.doc_exoneracion_ir"
                    placeholder="No. Documento:"
                />
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
&lt;!&ndash;            <td><strong>C$ {{ total_retencion | formatMoney(2) }}</strong></td>&ndash;&gt;

            <td style="width:8%">
              <input
                  :disabled="!form.aplicaIMI"
                  class="form-control"
                  v-model="form.doc_exoneracion_imi"
                  placeholder="No. Documento:"
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
&lt;!&ndash;            <td><strong>C$ {{ total_retencion_imi | formatMoney(2) }}</strong></td>&ndash;&gt;
          </tr>-->
                        <tr>
                            <td colspan="3"/>
                            <td><!--No. Documento:--></td>
                            <!--            <td>
              <input
                  :disabled="!form.aplicaIMI"
                  class="form-control"
                  v-model="form.doc_exoneracion_imi"
                  placeholder="No. Documento:"
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
            <td><strong>C$ {{ total_retencion_imi | formatMoney(2) }}</strong></td>-->
                        </tr>

                        <tr class="table-active table-light">
                            <td colspan="0"/>
                            <td class="item-footer">
                                <strong>Totales</strong>
                            </td>
                            <td class="item-footer">
                                <strong>{{ total_cantidad | formatMoney(2) }}</strong>
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
                                Equivalente Dólares
                            </td>
                            <td><strong>$ {{ gran_total | formatMoney(2) }}</strong></td>
                        </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </b-row>

        <hr>

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
            <a
                    ref="facturar"
                    class="btn btn-success ml-2"
                    variant="success"
                    @click="abrirModalPago"
            >
                <feather-icon icon="DollarSignIcon"/>
                Pagar
            </a>
        </b-card-footer>

        <template v-if="loading">
            <BlockUI :url="url"/>
        </template>

        <!-- Modal - registro basico cliente -->

        <div>
            <b-modal
                    id="modal-select2"
                    ref="modal"
                    hide-footer
                    size="lg"
                    title="Registrar cliente"
            >
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tipo de Persona</label>
                            <b-form-select v-model.number="formCliente.tipo_persona">
                                <option value="1">
                                    Natural
                                </option>
                                <option value="2">
                                    Jurídico
                                </option>
                            </b-form-select>
                        </div>
                    </div>

                    <template v-if="formCliente.tipo_persona === 1">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Número Cédula</label>
                                <input
                                        v-model="formCliente.numero_cedula"
                                        class="form-control"
                                        placeholder="Número Cédula"
                                >
                                <b-alert
                                        show
                                        variant="danger"
                                >
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages.numero_cedula"
                                                :key="message"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label> Número RUC</label>
                                <input
                                        v-model="formCliente.numero_ruc"
                                        v-mask="'A#############'"
                                        class="form-control"
                                        placeholder="Número RUC"
                                >
                                <b-alert
                                        show
                                        variant="danger"
                                >
                                    <ul class="error-messages">
                                        <li
                                                v-for="message in errorMessages.numero_ruc"
                                                :key="message"
                                                v-text="message"
                                        />
                                    </ul>
                                </b-alert>

                            </div>
                        </div>
                    </template>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tipo de Cliente</label>
                            <b-form-select v-model.number="formCliente.tipo_cliente">
                                <option value="1">
                                    Minorista
                                </option>
                                <option value="2">
                                    Distribuidor
                                </option>
                            </b-form-select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> Teléfono</label>
                            <input
                                    v-model="formCliente.telefono"
                                    class="form-control"
                                    placeholder="Teléfono"
                            >
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.telefono"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> Nombre Completo</label>
                            <input
                                    v-model="formCliente.nombre_comercial"
                                    class="form-control"
                                    placeholder="Nombre Completo"
                            >
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.nombre_comercial"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> Dirección</label>
                            <input
                                    v-model="formCliente.direccion"
                                    class="form-control"
                                    placeholder="Dirección"
                            >
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.direccion"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label> Contacto</label>
                            <input
                                    v-model="formCliente.contacto"
                                    class="form-control"
                                    placeholder="Contacto"
                            >
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.contacto"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Departamento</label>
                            <v-select
                                    v-model="formCliente.departamento"
                                    :options="departamentos"
                                    label="descripcion"
                                    placeholder="Seleccione un departamento"
                                    style="background: white"
                                    @input="obtenerMunicipios()"
                                    :clearable="false"
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
                                            v-for="message in errorMessages.departamento"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Municipio</label>
                            <v-select
                                    v-model="formCliente.municipio"
                                    :options="municipios"
                                    label="descripcion"
                                    placeholder="Seleccione un municipio"
                                    style="background: white"
                                    :clearable="false"
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
                                            v-for="message in errorMessages.municipio"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Vendedor</label>
                            <v-select
                                    v-model="formCliente.vendedor"
                                    :options="vendedores"
                                    label="nombre_completo"
                                    placeholder="Seleccione un vendedor"
                                    style="background: white"
                                    :clearable="false"
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
                                            v-for="message in errorMessages.vendedor"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label> Observaciones</label>
                            <input
                                    v-model="formCliente.observaciones"
                                    class="form-control"
                                    placeholder="Observaciones"
                            >
                            <b-alert
                                    show
                                    variant="danger"
                            >
                                <ul class="error-messages">
                                    <li
                                            v-for="message in errorMessages.observaciones"
                                            :key="message"
                                            v-text="message"
                                    />
                                </ul>
                            </b-alert>

                        </div>
                    </div>
                </div>

                <div class="content-box-footer text-right">
                    <b-button
                            class="mx-lg-1 mx-0"
                            variant="secondary"
                            @click="cerrarModal"
                    >
                        Cancelar
                    </b-button>
                    <b-button
                            :disabled="btnActionCliente !== 'Registrar Cliente'"
                            class="mx-lg-1 mx-0"
                            type="button"
                            variant="primary"
                            @click="registrarCliente"
                    >{{ btnActionCliente }}
                    </b-button>
                </div>
            </b-modal>
        </div>
        <div class="modalPagoContainer">
            <b-modal
                    id="modal-selectPago"
                    ref="modalPago"
                    hide-footer
                    size="xl"
                    title="Registrar datos de pago"
                    class="card"
                    no-close-on-esc
                    no-close-on-backdrop
            >

                <b-card>
                    <b-row>
                        <!--Agregar vias de pago-->
                        <b-col
                                lg="8"
                                md="12"
                                sm="12"
                                class="mb-md-3"
                        >
                            <b-row>
                                <div class="col-sm-12">
                                    <div class="form-group">

                                        <label><strong style="font-size: 1rem"> Método de pago</strong></label>
                                        <div class="form-group">
                                            <div class="form-group d-flex flex-lg-row flex-column ">
                                                <label class="radio-button-group tmr" v-for="option in vias_pago"
                                                       :key="option.id_via_pago">
                                                    <div class="d-flex align-items-center mr-1">
                                                        <input type="radio" class=""
                                                               :value="option" v-model="detalleFormPago.via_pagox">
                                                        <label for="option.id_vias_pago">{{option.descripcion}}</label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <b-alert
                                                show
                                                variant="danger"
                                        >
                                            <ul class="error-messages">
                                                <li
                                                        v-for="message in errorMessages.via_pagox"
                                                        :key="message"
                                                        v-text="message"
                                                />
                                            </ul>
                                        </b-alert>
                                    </div>
                                </div>


                                <template v-if="detalleFormPago.via_pagox.id_via_pago">

                                    <div
                                            v-if="[1,3,5,6].indexOf(detalleFormPago.via_pagox.id_via_pago) >= 0"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label> Banco</label>
                                            <div class="form-group">
                                                <v-select
                                                        ref="banco"
                                                        v-model="detalleFormPago.banco_x"
                                                        :allow-empty="false"
                                                        :options="bancos"
                                                        :searchable="true"
                                                        label="descripcion"
                                                        placeholder="Selecciona un banco"
                                                        track-by="id_banco"
                                                        :clearable="false"
                                                />
                                            </div>
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.moneda_x"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                    <div
                                            v-if="[1].indexOf(detalleFormPago.via_pagox.id_via_pago) >= 0"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label for>Número Minuta</label>
                                            <input
                                                    v-model="detalleFormPago.numero_minuta"
                                                    class="form-control"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.numero_minuta"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                    <div
                                            v-if="detalleFormPago.via_pagox.id_via_pago === 3"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label for="numero_minuta">Número Voucher</label>
                                            <input
                                                    id="numero_minuta"
                                                    v-model="detalleFormPago.numero_minuta"
                                                    class="form-control"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.numero_minuta"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                    <div
                                            v-if="detalleFormPago.via_pagox.id_via_pago === 4"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label for="nota_credito">Número Nota Crédito</label>
                                            <input
                                                    id="nota_credito"
                                                    v-model="detalleFormPago.numero_nota_credito"
                                                    class="form-control"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.numero_nota_credito"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                    <div
                                            v-if="detalleFormPago.via_pagox.id_via_pago === 5"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label for="numero_cheque">Número Cheque</label>
                                            <input
                                                    id="numero_cheque"
                                                    v-model="detalleFormPago.numero_cheque"
                                                    class="form-control"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.numero_cheque"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                    <div
                                            v-if="detalleFormPago.via_pagox.id_via_pago === 6"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label for="numero_transferencia">Número Transferencia</label>
                                            <input
                                                    id="numero_transferencia"
                                                    v-model="detalleFormPago.numero_transferencia"
                                                    class="form-control"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.numero_transferencia"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                    <div
                                            v-if="detalleFormPago.via_pagox.id_via_pago === 7"
                                            class="col-sm-6"
                                    >
                                        <div class="form-group">
                                            <label for="numero_recibo_pago">Número Recibo Pago Anticipado</label>
                                            <input
                                                    id="numero_recibo_pago"
                                                    v-model="detalleFormPago.numero_recibo_pago"
                                                    class="form-control"
                                            >
                                            <b-alert
                                                    show
                                                    variant="danger"
                                            >
                                                <ul class="error-messages">
                                                    <li
                                                            v-for="message in errorMessages.numero_recibo_pago"
                                                            :key="message"
                                                            v-text="message"
                                                    />
                                                </ul>
                                            </b-alert>

                                        </div>
                                    </div>

                                </template>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong style="font-size: 1rem"> Moneda</strong></label>

                                        <div class="form-group d-flex">
                                            <label class="radio-button-group tmr" v-for="mon in monedas"
                                                   :key="mon.id_moneda">
                                                <div class="d-flex align-items-center mr-1">
                                                    <input type="radio" class=""
                                                           :value="mon" v-model="detalleFormPago.moneda_x"
                                                           @change="viaPagoSelected">
                                                    <label for="mon.id_moneda">{{mon.descripcion}}</label>
                                                </div>
                                            </label>
                                        </div>
                                        <b-alert
                                                show
                                                variant="danger"
                                        >
                                            <ul class="error-messages">
                                                <li
                                                        v-for="message in errorMessages.moneda_x"
                                                        :key="message"
                                                        v-text="message"
                                                />
                                            </ul>
                                        </b-alert>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="monto">Monto {{
                                            detalleFormPago.moneda_x ? detalleFormPago.moneda_x.codigo
                                            : ''
                                            }}</label>
                                        <input
                                                ref="monto"
                                                id="monto"
                                                v-model.number="detalleFormPago.monto"
                                                class="form-control"
                                                min="0"
                                                type="number"
                                                @keydown.enter="pasarDetalle"
                                                @keydown.tab="pasarDetalle"
                                                @change="montoconBancos"
                                        >
                                        <b-alert
                                                show
                                                variant="danger"
                                        >
                                            <ul class="error-messages">
                                                <li
                                                        v-for="message in errorMessages.monto"
                                                        :key="message"
                                                        v-text="message"
                                                />
                                            </ul>
                                        </b-alert>

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for>&nbsp;</label>
                                        <b-button
                                                ref="agregarpago"
                                                class="btn-agregar"
                                                variant="info"
                                                @click="agregarMetodoPago"
                                        >Agregar
                                        </b-button>
                                    </div>
                                </div>
                                <b-col
                                        lg="12"
                                        md="12"
                                        sm="12"
                                >
                                    <table class="table table-responsive table-bordered">
                                        <thead>
                                        <tr>
                                            <th/>
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
                                                <td>
                                                    <b-button
                                                            variant="danger"
                                                            @click="eliminarLineaPago(item, index)"
                                                    >
                                                        <feather-icon icon="TrashIcon"/>
                                                    </b-button>
                                                </td>
                                                <td>
                                                    {{ item.via_pagox.descripcion }}
                                                    <!--                                    <input class="form-control" disabled
                                                                             v-model="item.via_pagox.descripcion">-->
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages[`detallePagos.${index}.via_pagox.id_via_pago`]"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </td>

                                                <td>
                                                    {{ item.moneda_x.descripcion }}
                                                    <!--<input class="form-control" disabled
                                         v-model="item.moneda_x.descripcion">-->
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages[`detallePagos.${index}.moneda_x.id_moneda`]"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </td>

                                                <td v-if="item.moneda_x.id_moneda === 1">
                                                    <input
                                                            v-model.number="item.monto"
                                                            :readonly="item.via_pagox.id_via_pago === 1 || item.via_pagox.id_via_pago === 3 || item.via_pagox.id_via_pago === 6 ? true : false"
                                                            class="form-control"
                                                            min="0"
                                                            type="number"
                                                            @change="calcularEquivalente(item)"
                                                    >

                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages[`detallePagos.${index}.monto`]"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </td>

                                                <td v-if="item.moneda_x.id_moneda === 2">
                                                    <input
                                                            v-model.number="item.monto_me"
                                                            :readonly="item.via_pagox.id_via_pago === 1 || item.via_pagox.id_via_pago === 3 || item.via_pagox.id_via_pago === 6 ? true : false"
                                                            class="form-control"
                                                            min="0"
                                                            type="number"
                                                            @change="calcularEquivalente(item)"
                                                    >
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    >
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages[`detallePagos.${index}.monto`]"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </b-alert>

                                                </td>

                                                <td>
                                                    {{ item.banco_x.descripcion }}
                                                    <!--<input class="form-control"
                                         disabled
                                         v-model="item.banco_x.descripcion">-->
                                                    <b-alert
                                                            show
                                                            variant="danger"
                                                    />
                                                    <ul class="error-messages">
                                                        <li
                                                                v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.descripcion`]"
                                                                :key="message"
                                                                v-text="message"
                                                        />
                                                    </ul>
                                                </td>
                                                <td>
                                                    <template v-if="[1,3].indexOf(item.via_pagox.id_via_pago) >= 0">
                                                        <input
                                                                v-model="item.numero_minuta"
                                                                class="form-control"
                                                        >
                                                        <b-alert
                                                                show
                                                                variant="danger"
                                                        >
                                                            <ul class="error-messages">
                                                                <li
                                                                        v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_minuta`]"
                                                                        :key="message"
                                                                        v-text="message"
                                                                />
                                                            </ul>
                                                        </b-alert>

                                                    </template>

                                                    <template v-if="item.via_pagox.id_via_pago === 4">
                                                        <input
                                                                v-model="item.numero_nota_credito"
                                                                class="form-control"
                                                        >
                                                        <b-alert
                                                                show
                                                                variant="danger"
                                                        >
                                                            <ul class="error-messages">
                                                                <li
                                                                        v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_nota_credito`]"
                                                                        :key="message"
                                                                        v-text="message"
                                                                />
                                                            </ul>
                                                        </b-alert>

                                                    </template>

                                                    <template v-if="item.via_pagox.id_via_pago === 5">
                                                        <input
                                                                v-model="item.numero_cheque"
                                                                class="form-control"
                                                        >
                                                        <b-alert
                                                                show
                                                                variant="danger"
                                                        >
                                                            <ul class="error-messages">
                                                                <li
                                                                        v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_cheque`]"
                                                                        :key="message"
                                                                        v-text="message"
                                                                />
                                                            </ul>
                                                        </b-alert>

                                                    </template>

                                                    <template v-if="item.via_pagox.id_via_pago === 6">

                                                        <input
                                                                v-model="item.numero_transferencia"
                                                                class="form-control"
                                                        >
                                                        <b-alert
                                                                show
                                                                variant="danger"
                                                        />
                                                        <ul class="error-messages">
                                                            <li
                                                                    v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_transferencia`]"
                                                                    :key="message"
                                                                    v-text="message"
                                                            />
                                                        </ul>
                                                    </template>

                                                    <template v-if="item.via_pagox.id_via_pago === 7">
                                                        <input
                                                                v-model="item.numero_recibo_pago"
                                                                class="form-control"
                                                        >
                                                        <b-alert
                                                                show
                                                                variant="danger"
                                                        >
                                                            <ul class="error-messages">
                                                                <li
                                                                        v-for="message in errorMessages[`detalleCuentasxCobrar.${index}.numero_recibo_pago`]"
                                                                        :key="message"
                                                                        v-text="message"
                                                                />
                                                            </ul>
                                                        </b-alert>

                                                    </template>

                                                </td>

                                            </tr>
                                            <tr/>
                                        </template>
                                        </tbody>
                                        <tfoot/>
                                    </table>
                                </b-col>
                            </b-row>
                        </b-col>
                        <!--Tabla resumen de pago y vuelto-->
                        <b-col
                                lg="4"
                                md="12"
                                sm="12"
                        >
                            <table class="table table-responsive table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="2">
                                        Resumen
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <label> Total a Abonar en Córdobas</label>
                                    </td>
                                    <td style="width: 50%">
                                        <p><strong>C$ {{ gran_total_cordobas | formatMoney(2) }}</strong></p>
                                    </td>
                                </tr>

                                <template v-for="(item, index) in form.detallePago">

                                    <tr>
                                        <td style="width: 50%">
                                            {{ '(-) Pagado con ' + item.via_pagox.descripcion + ' (Equivalente en
                                            Córdobas)'
                                            }}
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
                                        <p><strong>$ {{ form.pago_pendiente.toFixed(4) }}</strong></p>
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
                                <tfoot/>
                            </table>
                        </b-col>
                    </b-row>
                    <hr>
                    <div class="content-box-footer text-right">
                        <b-button
                                class="mx-lg-1 mx-0"
                                variant="secondary"
                                @click="cerrarModalPago"
                        >
                            Cancelar
                        </b-button>
                        <b-button
                                :disabled="btnAction !== 'Facturar'"
                                class="mx-lg-1 mx-0"
                                variant="success"
                                @click="registrar"
                        >{{ btnAction }}
                        </b-button>
                    </div>
                </b-card>
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
        VBTooltip,
        BCol,
        BCollapse,
        BFormRadio,
        BFormRadioGroup,
    } from 'bootstrap-vue'
    import vSelect from 'vue-select'
    import Ripple from 'vue-ripple-directive'
    import axios from 'axios'
    import flatPickr from 'vue-flatpickr-component'
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
    import proyecto from '../../../api/CajaBanco/proyectos'
    import cuenta from '../../../api/CuentasXCobrar/cuentas_por_cobrar'
    import numberasstring from '../../../assets/custom-scripts/NumberAsString'
    /*    import es from 'vuejs-datepicker/dist/locale/translations/es' */

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
            BCol,
            BCollapse,
            BFormRadio,
            BFormRadioGroup,
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
                selected: 'A',
                format: 'dd-MM-yyyy',
                foc: false,
                optionsTP: [
                    {text: 'Cod.Balanza', value: 'A'},
                    {text: 'Otros Cod.', value: 'B'},
                ],

                proformasBusquedaURL: '/cajabanco/proformas/buscar',

                clientesBusquedaURL: '/ventas/clientes/buscar',
                vendedoresBusquedaURL: '/ventas/vendedores/buscar',

                sucursalesBusquedaURL: '/sucursales/buscar',
                // bodegasBusquedaURL: "/bodegas/buscar",
                productosBodegaBusquedaURL: '/bodegas/obtener-bodegas-productos-venta',
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
                    vendedor: null,
                    numero_ruc: '',
                    numero_cedula: '',
                    direccion: '',
                    tipo_cliente: 1,
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
                    productox: {text: 'Escanea el codigo de barras o escribe para buscar un producto'},
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
                    monto_a_pagar: 0,
                    cantP: 0,
                },

                detalleFormPago: {
                    via_pagox: {descripcion: "Efectivo", id_via_pago: 2},
                    monto: 1,
                    moneda_x: {
                        codigo: "C$",
                        decimales: 2,
                        descripcion: "Córdobas",
                        descripcion_singular: "Córdoba",
                        estado: 1,
                        f_creacion: "2022-02-18T14:13:45.000000Z",
                        f_modificacion: null,
                        id_moneda: 1,
                        u_creacion: "octaviom",
                        u_modificacion: null
                    },
                    // moneda_x: [],
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
                factura_id: 0,

                form: {
                    detalleCuentasxCobrar: [],
                    no_factura: '',
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
                    f_facturax: new Date(),
                    f_vencimiento: moment(new Date()).format('YYYY-MM-DD'),
                    serie: '',
                    id_tipo: 1,
                    id_tipo_documento: 3,
                    // factura_moneda: {},
                    factura_sucursal: {},
                    factura_bodega: '',
                    tipo_identificacion: 1,
                    identificacion: '',
                    factura_cliente: {
                        id_cliente: '1',
                        nombre_comercial: 'Cliente Contado',
                    },
                    id_tipo_cliente: 1,
                    dias_credito: 0,
                    nombre_razon: '',
                    factura_vendedor: '',
                    t_cambio: 0,
                    tasa_paralela: 0,
                    doc_exoneracion: '12345',
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
                    aplicaIVA: false,
                    total_final: 0,
                    total_final_cordobas: 0,
                    currency_id: 1,

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
                    pendiente: 0,
                    pendientemn: 0,
                },
                filter: {
                    search: {
                        mes: 0, // moment(new Date()).format("YYYY-MM-DD"),
                        anio: 0, // moment(new Date()).format("YYYY-MM-DD"),
                    },
                },
                isCollapsed: false,
                isCollapsed_df: false,
                btnAction: 'Facturar',
                btnActionCliente: 'Registrar Cliente',
                registrandoCliente: false,

                errorMessages: [],
                cuentas: [],
            }
        },
        computed: {
            total_subt_sin_iva() {
                this.form.mt_subtotal_sin_iva = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.total_sin_iva, 2)),
                    0)))
                this.form.mt_subtotal = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.subtotal, 2)),
                    0)))
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
                this.form.mt_ajuste = this.form.detalleProductos.reduce((carry, item) => (carry + Number(item.mt_ajuste)), 0)
                return this.form.mt_ajuste
            },

            total_descuento() {
                this.form.mt_descuento = Number((this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(item.mt_descuento, 2)), 0)))
                return this.form.mt_descuento
            },
            descuento_maximo() {
                // this.form.descuento_maximo_producto = Number((this.form.detalleProductos.reduce((carry, item) => (Round.round(item.p_descuento,2)), 0)));
                const array = this.form.detalleProductos.map(producto => producto.p_descuento)
                return Math.max(...array)
            },
            total_cantidad() {
                this.form.total_unidades_sin_bonificacion = Number(this.form.detalleProductos.reduce((carry, item) => (carry + ((item.afectacionx.id_afectacion !== 2) ? (Round.round(Number(item.cantidad), 2)) : 0)), 0))

                this.form.total_unidades_con_bonificacion = Number(this.form.detalleProductos.reduce((carry, item) => (carry + ((item.afectacionx.id_afectacion === 2) ? (Round.round(Number(item.cantidad), 2)) : 0)), 0))

                return this.form.detalleProductos.reduce((carry, item) => (carry + Round.round(Number(item.cantidad), 2)), 0);
            },

            gran_total_cordobas() {
                this.form.total_final_cordobas = Round.round((this.form.mt_subtotal_sin_iva - this.form.mt_retencion - this.form.mt_retencion_imi) + this.form.mt_impuesto, 2);
                // roundNumber.round(Number((Number(this.form.total_final)*this.form.t_cambio).toFixed(2)),2);

                if (!isNaN(this.form.total_final_cordobas)) {
                    return Number(this.form.total_final_cordobas)
                }
                return 0
            },

            gran_total() {
                /* this.form.total_final = roundNumber.decimalAdjust('ceil', Number(this.form.total_final_cordobas / this.form.t_cambio), -2); */
                this.form.total_final = Round.round(this.form.total_final_cordobas / this.form.t_cambio, 2);

                if (!isNaN(this.form.total_final)) {
                    return this.form.total_final
                }
                return 0
            },
            total_recibos() {
                if (this.recibos) {
                    this.form.total_final_recibos_dolares = this.recibos.reduce((carry, item) => (carry + Number(item.monto_total_me)), 0)

                    if (!isNaN(this.form.total_final_recibos_dolares)) {
                        return Round.round(this.form.total_final_recibos_dolares, 2)
                    }
                    return 0
                }
            },
            total_recibos_cordobas() {
                if (this.recibos) {
                    this.form.total_final_recibos_cordobas = this.recibos.reduce((carry, item) => (carry + Number(item.monto_total)), 0)

                    if (!isNaN(this.form.total_final_recibos_cordobas)) {
                        return this.form.total_final_recibos_cordobas
                    }
                    return 0
                }
            },

            total_deuda() {
                //obtenemos los montos de las diferentes vías de pago redondeado a 2 decimales para que al aplicarse la resta que calcula vueltos y pagos pendientes ambas sean en base a 2 decimales
                const total_pagos = Round.round(this.form.detallePago.reduce((carry, item) => (carry + Number(item.moneda_x.id_moneda === 1 ? Number(item.monto) : item.monto_me * this.form.t_cambio)), 0), 2);

                /* let total_pagos_cuentas = this.form.detalleCuentasxCobrar.reduce((carry, item) => {
                return (carry + Number(item.monto));
            }, 0); */
                const total_pagos_cuentas = this.form.total_final_cordobas.toFixed(2);

                if (Round.round(total_pagos_cuentas - total_pagos, 4) < 0) {
                    this.form.pago_pendiente = 0;
                    this.form.pago_pendiente_mn = 0;

                    // this.form.pago_vuelto_mn = roundNumber.round(Number((Number((total_pagos).toFixed(2)) - Number((this.form.monto_total)).toFixed(2))), 2);
                    this.form.pago_vuelto_mn = Round.round(total_pagos - total_pagos_cuentas, 4)
                    // this.form.pago_vuelto = roundNumber.round(Number((this.form.pago_vuelto_mn / this.form.t_cambio).toFixed(2)), 2);
                    this.form.pago_vuelto = Round.round(this.form.pago_vuelto_mn / this.form.t_cambio, 4)

                    /* console.log("===========================resta entre monto total y monto pagado menor a 0=================");
              console.log(" Monto total a pagar  : " + total_pagos_cuentas);
              console.log("total pagado :  " + total_pagos);
              console.log(" pago pendiente dolares " + this.form.pago_pendiente);
              console.log(" pago pendiente cordobas  " + this.form.pago_pendiente_mn); */
                } else {
                    this.form.pago_pendiente_mn = Round.round(total_pagos_cuentas - total_pagos, 4);
                    // this.form.pago_pendiente = roundNumber.decimalAdjust('ceil', Number((this.form.pago_pendiente_mn / this.form.t_cambio).toFixed(4)), -2);
                    this.form.pago_pendiente = Round.round(this.form.pago_pendiente_mn / this.form.t_cambio, 4);

                    this.form.pago_vuelto = 0
                    this.form.pago_vuelto_mn = 0

                    /* console.log("===========================resta entre monto total y monto pagado mayor a 0=================");
               console.log(" Monto total a pagar  : " + total_pagos_cuentas);
               console.log("total pagado :  " + total_pagos);
               console.log(" pago pendiente dolares " + this.form.pago_pendiente);
               console.log(" pago pendiente cordobas  " + this.form.pago_pendiente_mn); */
                }

                if (!isNaN(this.form.pago_pendiente_mn)) {
                    // this.form.pago_pendiente = roundNumber.round(Number((this.form.pago_pendiente_mn / this.form.t_cambio)),2);
                    return this.form.pago_pendiente_mn;
                } else {
                    return this.form.pago_pendiente_mn = Math.round(Number((this.form.pago_pendiente * this.form.t_cambio)), 4);
                }
                if (!isNaN(this.form.pago_pendiente)) {
                    return this.form.pago_pendiente;
                } else {
                    return this.form.pago_pendiente = Math.round(Number((this.form.pago_pendiente_mn / this.form.t_cambio)), 4);
                }
                return 0
            },

            total_vuelto() {
                /* let total_pagos = this.form.detallePago.reduce((carry, item) => {
                               return (carry + Number(item.moneda_x.id_moneda === 3 ? item.monto_me : Number(item.monto/this.form.t_cambio.toFixed(2))));
                           }, 0); */

                let total_pagos = 0

                const monto_dolares_decimales = Round.round((Number(this.form.total_final_cordobas) / Number(this.form.t_cambio)), 2)
                total_pagos = this.form.detallePago.reduce((carry, item) =>
                        // return (carry + Number(item.moneda_x.id_moneda === 1 ? item.monto : Number((item.monto_me * this.form.t_cambio).toFixed(2))));
                    carry + Number(item.moneda_x.id_moneda === 1 ? item.monto : Round.round((item.monto_me) * (this.form.t_cambio), 2)),
                    0)

                if (((Round.round((this.form.total_final_cordobas), 2) - Round.round((total_pagos), 2))) > 0) { /// revision
                    this.form.pago_vuelto = 0
                    this.form.pago_vuelto_mn = 0
                } else {
                    this.form.pago_vuelto_mn = Round.round((this.form.total_final_cordobas), 2) - Number((Round.round((total_pagos), 2)))
                    // console.log(total_pagos);
                    this.form.pago_vuelto = Round.round(this.form.pago_vuelto_mn / this.form.t_cambio, 2)
                }

                // console.log('Master C$: '+((Number((this.form.total_final).toFixed(2)) - Number((total_pagos).toFixed(2))).toFixed(2)));

                /* let total_pagos_cordobas = this.form.detallePago.reduce((carry, item) => {
                            return (carry + Number(item.moneda_x.id_moneda === 3 ? item.monto : Number((item.monto_me*this.form.t_cambio).toFixed(2))));
                          }, 0); */

                if (!isNaN(this.form.pago_vuelto_mn)) {
                    return this.form.pago_vuelto_mn
                }
                return 0
            },

            total_a_pagar() {
                const self = this

                self.form.monto_total_me = Number(this.form.detalleCuentasxCobrar.reduce((carry, item) => (carry + Number(item.monto_me)), 0))

                self.form.saldo_total_me = Number(this.form.detalleCuentasxCobrar.reduce((carry, item) => (carry + Round.round(item.cuentax.saldo_actual_me - item.monto_me, 2)), 0))

                this.form.monto_total_letras_me = numberasstring.numberasstring(this.form.monto_total_me, 'DOLAR', 'DOLARES', true)

                if (!isNaN(this.form.monto_total)) {
                    // this.form.monto_total_me = roundNumber.decimalAdjust('ceil', Number((this.form.monto_total / this.form.t_cambio).toFixed(2)));
                    this.form.monto_total = Round.round(this.form.monto_total_me * this.form.t_cambio, 2)
                    // console.log("monto total en dolares  "+this.form.monto_total_me);
                    // this.form.saldo_total_me = roundNumber.decimalAdjust('ceil', Number((this.form.saldo_total / this.form.t_cambio).toFixed(2)));
                    this.form.saldo_total = Round.round(this.form.saldo_total_me * this.form.t_cambio, 2)
                    self.form.monto_total_letras = numberasstring.numberasstring(this.form.monto_total, 'CORDOBA', 'CORDOBAS', true)

                    return this.form.monto_total_me
                }
                return 0
            },

        },
        methods: {
            montoconBancos() {
                const self = this;
                if (self.detalleFormPago.via_pagox.id_via_pago === 1 || self.detalleFormPago.via_pagox.id_via_pago === 3 || self.detalleFormPago.via_pagox.id_via_pago === 6) {
                    if (self.detalleFormPago.moneda_x.id_moneda === 1 && self.detalleFormPago.monto > self.form.pago_pendiente_mn) {
                        self.detalleFormPago.monto = Math.min(self.form.pago_pendiente_mn);
                    } else if (self.detalleFormPago.moneda_x.id_moneda === 2 && self.detalleFormPago.monto > self.form.pago_pendiente) {
                        self.detalleFormPago.monto = Math.min(self.form.pago_pendiente);
                    }
                }
            },
            calcantidad(item) {
                if (item.cantidad > item.productox.cantidad_disponible) {
                    item.cantidad = Math.max(Math.min(item.cantidad > 0 ? item.cantidad : 1, item.productox.cantidad_disponible), 0.1);
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Se ha actualizado a la cantidad máxima disponible de este producto.',
                                variant: 'warning',

                            }
                        },
                        {
                            position: 'bottom-left'
                        });
                } else {
                    item.cantidad = Math.max(Math.min(item.cantidad > 0 ? item.cantidad : 1, item.productox.cantidad_disponible), 0.1);
                }
            },
            /*
                    Author: omorales (c)
                    hot search
                    14/03/22    */
            onSearch(search, loading) {
                if (search.length) {
                    loading(true)
                    this.search(loading, search, this)
                }
            },
            onSearchP(searchP, loading) {
                if (searchP.length) {
                    loading(true)
                    this.searchP(loading, searchP, this)
                }
            },
            select(e) {
                this.$emit('input', {
                    target: {
                        value: result,
                    },
                })
                this.onEsc()
            },
            search: _.debounce((loading, search, vm) => { // /ventas/clientes/buscar
                const self = this

                axios.get(`/cuentas-por-cobrar/clientes/buscar?q=${escape(search)}&es_deudor=${vm.form.es_deudor}&permite_anticipo=${vm.form.permite_anticipo}`).then(res => {
                    vm.options = res.data.results
                    vm.clientes = res.data.results
                    loading(false)
                })
            }, 100),
            searchP: _.debounce((loading, searchP, vm) => { // busqueda proformas
                const self = this
                axios.get(`/cajabanco/proformas/buscar?q=${escape(searchP)}`).then(res => {
                    vm.options = res.data.results
                    vm.proformas = res.data.results
                    loading(false)
                })
            }, 100),
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
                const self = this
                /* self.form.proformasBusqueda = form.proformasBusqueda */
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
                        self.proformaForm = data.detalleProforma
                        self.proformaFormHeader = data.proforma
                        self.form.es_nuevo = self.proformaFormHeader.es_nuevo
                        self.form.id_tipo = self.proformaFormHeader.id_tipo
                        self.form.serie = self.proformaFormHeader.serie
                        self.form.tipo_identificacion = self.proformaFormHeader.tipo_identificacion
                        self.form.identificacion = self.proformaFormHeader.identificacion
                        self.form.observacion = self.proformaFormHeader.observacion
                        self.form.t_cambio = self.proformaFormHeader.t_cambio
                        self.form.dias_credito = self.proformaFormHeader.dias_credito
                        self.form.proforma_cliente = self.proformaFormHeader.proforma_cliente
                        self.form.proforma_vendedor = self.proformaFormHeader.proforma_vendedor
                        self.form.proforma_bodega = self.proformaFormHeader.proforma_bodega
                        self.form.proforma_sucursal = self.proformaFormHeader.proforma_sucursal

                        self.form.factura_cliente = self.form.proforma_cliente
                        self.form.factura_vendedor = self.form.proforma_vendedor
                        self.form.factura_bodega = self.form.proforma_bodega
                        self.form.factura_sucursal = self.form.proforma_sucursal
                        /* self.afectacion_producto = data.detalleProforma.afectacion_producto;
                                        self.bodega_producto = data.detalleProforma.bodega_producto; */
                        this.cargarProductosProforma()
                        self.loading = false
                    }, err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encontró un detalle de la cotización seleccionada',
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        self.loading = false
                    })
            },
            /*
                    Author: omorales (c)
                    Función para agregar datos de la proforma al detalle de productos de la factura
                    03/09/21    */
            cargarProductosProforma() {
                const self = this
                if (self.form.proformasBusqueda) {
                    self.proformaForm.forEach((productox, key) => {
                        if (productox) {
                            let cantidad = 0
                            if (self.form.detalleProductos) {
                                self.form.detalleProductos.forEach((productodx, key) => {
                                    if (productox.bodega_producto.id_producto === productodx.productox.id_producto) {
                                        cantidad = cantidad + productodx.cantidad + productox.cantidad
                                    }
                                })
                            }

                            const i = 0
                            const keyx = 0
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

                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })

                                    self.detalleForm.productox = null
                                    self.detalleForm.afectacionx = self.afectaciones[0]
                                    self.detalleForm.cantidad = 0
                                    self.detalleForm.precio_sugerido = 0
                                    self.detalleForm.subtotal = 0
                                    self.detalleForm.total = 0
                                    self.detalleForm.total_sin_iva = 0
                                    this.$refs.producto.$el.focus()
                                } else {
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'No se cuenta con existencias suficiente para poder facturar',
                                                variant: 'warning',

                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })
                                }
                            } else {
                                const nuevo_total = self.form.detalleProductos[keyx].cantidad + self.detalleForm.cantidad
                                if (nuevo_total <= self.form.detalleProductos[keyx].productox.cantidad_disponible) {
                                    self.form.detalleProductos[keyx].cantidad = nuevo_total
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Artículo agregado!',
                                                variant: 'success',

                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })
                                } else {
                                    self.form.detalleProductos[keyx].cantidad = Number(self.form.detalleProductos[keyx].productox.cantidad_disponible)
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Se ha agregado la cantidad máxima de este producto',
                                                variant: 'warning',

                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })
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

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                }
            },

            calcularEquivalente(itemX) {
                if (itemX.moneda_x.id_moneda === 1 && itemX.monto > 0) { // equivalente para moneda cordobas
                    itemX.monto_me = Round.round(itemX.monto / this.form.t_cambio, 2)
                    setTimeout(() => {
                        this.viaPagoSelected();
                    }, 500);
                }

                if (itemX.moneda_x.id_moneda === 2 && itemX.monto_me > 0) { // equivalente para moneda dolares
                    setTimeout(() => {
                        this.viaPagoSelected();
                    }, 500);
                    itemX.monto = Round.round(itemX.monto_me * this.form.t_cambio, 2)
                }
            },

            abrirModal() {
                this.$refs.modal.show();
            },
            cerrarModal() {
                this.$refs.modal.hide();
            },
            abrirModalPago() {
                this.viaPagoSelected();
                this.$refs.modalPago.show();
            },
            cerrarModalPago() {
                this.$refs.modalPago.hide();
            },
            cambiarFocoCantidad() {
                const self = this;
                // self.$refs.agregar.focus();

                setTimeout(() => {
                    const button = document.getElementById('my-button');

                    button.addEventListener('keyup', function (event) {
                        if (event.keyCode === 13) {
                            button.click();
                        }
                    });

                    const enterEvent = new KeyboardEvent('keyup', {keyCode: 13});
                    button.dispatchEvent(enterEvent);

                }, 500);

            },

            seleccionarCliente(e) {
                // const trabajadorP = e.target.value;
                const self = this

                // self.form.factura_cliente = trabajadorP;
                self.form.tipo_identificacion = self.form.factura_cliente.tipo_persona
                // self.form.id_tipo = 1;

                self.tipo_cliente = self.form.factura_cliente.id_tipo_cliente

                self.form.dias_credito = 0
                self.form.aplicaIR = self.form.factura_cliente.retencion_ir
                self.form.aplicaIMI = self.form.factura_cliente.retencion_imi
                self.plazo_maximo_credito = self.form.factura_cliente.plazo_credito
                // self.form.factura_vendedor = '';
                self.form.factura_vendedor = self.vendedores[0];
                (self.form.factura_cliente.permite_credito && self.form.factura_cliente.plazo_credito > 0) ? self.clienteCredito = true : self.clienteCredito = false

                if (self.form.factura_cliente.tipo_persona === 1) {
                    self.form.identificacion = self.form.factura_cliente.numero_cedula
                } else {
                    self.form.identificacion = self.form.factura_cliente.numero_ruc
                }

                self.form.detalleProductos = [];

                self.obtenerProyectoCliente()
                self.form.proyecto = []
                self.cargarDocumentosPendientes()
            },
            cargarDocumentosPendientes() {
                const self = this
                if (self.form.factura_cliente.id_cliente > 0) {
                    cuenta.obtenerCuentasCliente({
                        id_cliente: self.form.factura_cliente.id_cliente,
                        id_tipo_documento: self.form.id_tipo_documento,
                    }, data => {
                        if (data !== null) {
                            self.cuentas = data
                            self.detalleForm.cuentax = ''
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encuentran cuentas pendientes para este cliente.',
                                        variant: 'warning',
                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                            self.detalleForm.cuentax = ''
                        }
                        self.loading = false
                    }, err => {
                        /* if(err.codigo_bateria){
                  self.detalleForm.bateria_busqueda = '';
                  self.$refs.bateria.focus();
                  alertify.warning("No se encuentra esta batería.",5);
                  self.detalleForm.cuentax = '';
                } */
                        self.loading = false
                    })
                }
            },
            cargar_detalles_cuenta() {
                const self = this
                if (self.detalleForm.cuentax) {
                    // self.detalleForm.moneda_x = self.monedas[1];
                    self.detalleForm.monto_me = Number(Number(self.detalleForm.cuentax.saldo_actual_me).toFixed(2))
                }
                // self.detalleForm.monto = Number(Number(self.detalleForm.cuentax.saldo_actual).toFixed(2));
                self.detalleForm.monto = 0
            },
            calcularSaldoX(item) {
                return Number((roundNumber.round(Number(item.cuentax.saldo_actual_me), 2)) - Number(item.monto_me).toFixed(2))
            },
            establecerConcepto(itemX) {
                itemX.monto = Math.max(Math.min(Number(!isNaN(itemX.monto) ? itemX.monto.toFixed(2) : 0), Number(Number(itemX.cuentax.saldo_actual_me).toFixed(2))), 1)
                if (itemX.monto === Number(Number(itemX.cuentax.saldo_actual_me).toFixed(2))) {
                    itemX.descripcion_pago = `Cancela factura No.${itemX.cuentax.no_documento_origen}.`
                } else {
                    itemX.descripcion_pago = `Abona factura No.${itemX.cuentax.no_documento_origen}. Saldo $${(Number(itemX.cuentax.saldo_actual_me) - itemX.monto).toFixed(2)}.`
                }

                let monto_retencion_ir = 0;
                let monto_retencion_imi = 0;
                let
                    iva = 1.15

                if (itemX.cuentax.cuenta_factura.impuesto_exonerado) {
                    iva = 1
                }

                if (itemX.aplicaIR) {
                    monto_retencion_ir = this.round(Number(itemX.monto / iva) * 0.02, 2)
                } else {
                    monto_retencion_ir = 0
                }

                if (itemX.aplicaIMI) {
                    monto_retencion_imi = this.round(Number(itemX.monto / iva) * 0.01, 2)
                } else {
                    monto_retencion_imi = 0
                }

                itemX.monto_retencion_ir = monto_retencion_ir
                itemX.monto_retencion_imi = monto_retencion_imi
            },
            agregarDetalleRecibo() {
                const self = this
                if (self.detalleForm.cuentax) {
                    if (self.detalleForm.monto_me > 0) {
                        let validacion = 0
                        if (self.detalleForm.aplicaIR && self.detalleForm.doc_exoneracion_ir === '') {
                            validacion++
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'Si el pago retiene IR debe digitar el No. documento de retención.',
                                        variant: 'warning',
                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                        }
                        if (self.detalleForm.aplicaIMI && self.detalleForm.doc_exoneracion_imi === '') {
                            validacion++
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'Si el pago retiene IMI, digitar el No. documento IMI',
                                        variant: 'warning',
                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                        }
                        if (validacion === 0) {
                            let i = 0
                            let keyx = 0

                            if (self.form.detalleCuentasxCobrar) {
                                self.form.detalleCuentasxCobrar.forEach((cuentax, key) => {
                                    if (self.detalleForm.cuentax.id_cuentaxcobrar === cuentax.cuentax.id_cuentaxcobrar) {
                                        i++
                                        keyx = key
                                    }
                                })
                            }
                            if (i === 0) {
                                if (Round.round(self.detalleForm.monto_me, 2) === Round.round(self.detalleForm.cuentax.saldo_actual_me, 2)) {
                                    self.detalleForm.descripcion_pago = `Cancela factura No.${self.detalleForm.cuentax.no_documento_origen}.`
                                } else {
                                    self.detalleForm.descripcion_pago = `Abona factura No.${self.detalleForm.cuentax.no_documento_origen}. Saldo $${(Number(self.detalleForm.cuentax.saldo_actual_me) - self.detalleForm.monto_me).toFixed(2)}.`
                                }

                                let monto_retencion_ir = 0;
                                let monto_retencion_imi = 0;
                                let
                                    iva = 1.15
                                if (!self.detalleForm.cuentax.es_registro_importado) {
                                    iva = 1
                                }

                                if (self.detalleForm.aplicaIR) {
                                    monto_retencion_ir = Round.round(Number(self.detalleForm.monto_me / iva) * 0.02, 2)
                                } else {
                                    monto_retencion_ir = 0
                                }

                                if (self.detalleForm.aplicaIMI) {
                                    monto_retencion_imi = Round.round(Number(self.detalleForm.monto_me / iva) * 0.01, 2)
                                } else {
                                    monto_retencion_imi = 0
                                }

                                self.form.detalleCuentasxCobrar.push({
                                    cuentax: self.detalleForm.cuentax,
                                    monto: Round.round(self.detalleForm.monto_me * this.form.t_cambio, 2),
                                    monto_me: Round.round(self.detalleForm.monto_me, 2),
                                    monto_subtotal: Round.round(self.detalleForm.monto_me - monto_retencion_imi - monto_retencion_ir, 2),
                                    monto_retencion_ir,
                                    monto_retencion_imi,
                                    aplicaIR: self.detalleForm.aplicaIR,
                                    aplicaIMI: self.detalleForm.aplicaIMI,
                                    doc_exoneracion_ir: self.detalleForm.doc_exoneracion_ir,
                                    doc_exoneracion_imi: self.detalleForm.doc_exoneracion_imi,
                                    descripcion_pago: self.detalleForm.descripcion_pago,
                                    monto_a_facturar: self.detalleForm.monto_me,

                                })
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'CheckIcon',
                                            text: 'Agregado correctamente.',
                                            variant: 'success',
                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            } else {
                                const nuevo_total = self.form.detalleCuentasxCobrar[keyx].monto_me + self.detalleForm.monto_me
                                if (nuevo_total <= Number(self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me)) {
                                    // self.form.detalleCuentasxCobrar[keyx].monto_subtotal = nuevo_total;
                                    self.form.detalleCuentasxCobrar[keyx].monto = nuevo_total
                                    self.form.detalleCuentasxCobrar[keyx].monto_me = Round.round(self.detalleForm.monto_me, 2)
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'CheckIcon',
                                                text: 'Cuenta agregada correctamente',
                                                variant: 'success',
                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })

                                    if (nuevo_total === Number(self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me)) {
                                        self.form.detalleCuentasxCobrar[keyx].descripcion_pago = `Cancela factura No.${self.form.detalleCuentasxCobrar[keyx].cuentax.no_documento_origen}.`
                                    } else {
                                        self.form.detalleCuentasxCobrar[keyx].descripcion_pago = `Abona factura No.${self.detalleCuentasxCobrar[keyx].cuentax.no_documento_origen}. Saldo $${this.round((self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me) - nuevo_total, 2)}.`
                                    }
                                } else {
                                    self.form.detalleCuentasxCobrar[keyx].monto = Number(self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual)
                                    self.form.detalleCuentasxCobrar[keyx].monto_me = Round.round((self.form.detalleCuentasxCobrar[keyx].cuentax.saldo_actual_me), 2)
                                    self.form.detalleCuentasxCobrar[keyx].descripcion_pago = `Cancela factura No.${self.form.detalleCuentasxCobrar[keyx].cuentax.no_documento_origen}.`
                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'CheckIcon',
                                                text: 'Se ha agregado el monto máximo para saldar esta cuenta.',
                                                variant: 'warning',
                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })
                                }
                            }

                            self.detalleForm.cuentax = null
                            self.detalleForm.monto_me = 0
                            self.detalleForm.monto = 0
                            self.detalleForm.descripcion_pago = ''
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
                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                    }
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'CheckIcon',
                                text: 'Debe seleccionar una cuenta a saldar para agregar un abono.',
                                variant: 'warning',
                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                }
            },
            eliminarLineaRecibo(item, index) {
                if (this.form.detalleCuentasxCobrar.length >= 1) {
                    this.form.detalleCuentasxCobrar.splice(index, 1)
                } else {
                    this.form.detalleCuentasxCobrar = []
                }
            },
            seleccionarProyecto(e) {
                // const trabajadorP = e.target.value;
                const self = this
                if (self.form.factura_cliente.id_cliente > 0) {
                    self.loading = true
                    recibo.obtenerRecibosCliente({
                        id_cliente: self.form.factura_cliente.id_cliente,
                        id_proyecto: self.form.proyecto.id_proyecto,
                    }, data => {
                        if (data !== null) {
                            self.recibos = data
                            self.form.recibos = data
                            self.loading = false
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encuentran recibos pendientes para este cliente.',
                                        variant: 'warning',
                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                            self.detalleForm.cuentax = ''
                            self.loading = false
                        }
                        self.loading = false
                    }, err => {
                        /* if(err.codigo_bateria){
                          self.detalleForm.bateria_busqueda = '';
                          self.$refs.bateria.focus();
                          alertify.warning("No se encuentra esta batería.",5);
                          self.detalleForm.cuentax = '';
                        } */
                        self.loading = false
                    })
                } else {
                    self.form.proyecto = ''
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'AlertCircleIcon',
                                text: 'Debe seleccionar un cliente primero.',
                                variant: 'warning',
                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                }
            },
            obtenerProyectoCliente(e) {
                // const trabajadorP = e.target.value;
                const self = this
                if (self.form.factura_cliente.id_cliente > 0) {
                    self.loading = true
                    proyecto.obtenerProyectosCliente({
                        id_cliente: self.form.factura_cliente.id_cliente,
                    }, data => {
                        if (data !== null) {
                            self.proyectos = data
                            self.loading = false
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encuentran proyectos para este cliente.',
                                        variant: 'warning',
                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                            self.loading = false
                        }
                        self.loading = false
                    }, err => {
                        /* if(err.codigo_bateria){
                          self.detalleForm.bateria_busqueda = '';
                          self.$refs.bateria.focus();
                          alertify.warning("No se encuentra esta batería.",5);
                          self.detalleForm.cuentax = '';
                        } */
                        self.loading = false
                    })
                } else {
                    self.form.proyecto = ''
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'AlertCircleIcon',
                                text: 'Debe seleccionar un cliente primero.',
                                variant: 'warning',
                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                }
            },
            seleccionarVendedor(e) {
                const proveedorP = e.target.value
                const self = this
                self.form.factura_vendedor = proveedorP
            },
            seleccionarSucursal() {
                let self = this
                self.bodegas = []
                self.form.factura_bodega = []
                // self.form.factura_sucursal = sucursalx;
                // console.log(sucursalx);
                // console.log(self.form.factura_sucursal);
                self.form.serie = self.form.factura_sucursal.serie
                if (self.form.factura_sucursal.sucursal_bodega_ventas.length) {
                    self.bodegas = self.form.factura_sucursal.sucursal_bodega_ventas
                    self.form.factura_bodega = self.bodegas[0]
                    self.id_sucursal = self.form.factura_sucursal.id_sucursal

                    self.loading = true
                    self.form.detalleProductos = []
                    self.form.detallePago = []
                    self.detalleForm.productox = {text: 'Escanea el codigo de barras o escribe para buscar un producto'}
                    self.form.proforma_especifica = false
                    self.form.proformasBusqueda = []
                    self.proformaForm = []

                    // Metodo de carga de productos legacy
                    /* bodega.buscarProductosBodega({
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
                  position: 'bottom-left'
                });
            self.detalleForm.productox = ''
          }
          self.loading = false
        }, err => {
          /!* if(err.codigo_bateria){
                                        self.detalleForm.bateria_busqueda = '';
                                        self.$refs.bateria.focus();
                                        alertify.warning("No se encuentra esta batería.",5);
                                        self.detalleForm.productox = '';
                                      } *!/
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
              position: 'bottom-left'
            }); */
                }

                self = this
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
                factura.obtenerConsecutivo({factura_sucursal: self.form.factura_sucursal, serie: self.form.serie})
                {
                    self.no_documento = data.no_documento_siguiente
                }
            },
            seleccionarArea(e) {
                const areaP = e.target.value
                const self = this
                self.form.factura_area = areaP
            },
            seleccionarBodega() {
                const self = this
                self.loading = true
                self.form.detalleProductos = []
                self.form.detallePago = []
                self.detalleForm.productox = ''
            },
            cargar_detalles_producto(e) {
                const productoP = e.target.value
                const self = this
                self.detalleForm.productox = productoP
                if (self.detalleForm.productox) {
                    if (Number(self.detalleForm.productox.cantidad_disponible) === 0) {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'CheckIcon',
                                    text: 'No hay cantidad disponible para la venta de este producto',
                                    variant: 'warning',
                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        self.detalleForm.cantidad = 0
                    } else {
                        if (self.detalleForm.productox.cantP) {
                            if (Number(self.detalleForm.productox.cantP) === 0) {
                                self.detalleForm.cantidad = 1;
                            } else {
                                self.detalleForm.cantidad = self.detalleForm.productox.cantP;
                            }
                        } else {
                            self.detalleForm.cantidad = 1;
                        }


                    }

                    if (self.tipo_cliente === 1) {
                        self.detalleForm.precio_sugerido = Number(self.detalleForm.productox.precio_sugerido)
                        self.detalleForm.precio_sugerido_me = Round.round(Number((self.detalleForm.productox.precio_sugerido / self.form.t_cambio)), 2)
                    } else {
                        self.detalleForm.precio_sugerido = Number(self.detalleForm.productox.precio_distribuidor)
                        self.detalleForm.precio_sugerido_me = Round.round(Number((self.detalleForm.productox.precio_distribuidor / self.form.t_cambio)), 2)
                    }

                    if (self.detalleForm.productox.tipo_producto === 2) {
                        self.detalleForm.afectacionx = self.afectaciones[0]
                    }

                    // self.$refs.cantidad.focus()
                    // self.detalleForm.precio_unitario = self.detalleForm.productox.costo_promedio;
                    // self.$refs.agregar.focus();
                }
                self.cambiarFocoCantidad();
            },
            calcularAjuste(itemX) {
                itemX.p_ajuste = Number(itemX.afectacionx.valor)
                if (itemX.p_ajuste > 0) {
                    itemX.p_descuento = 0
                }
            },

            calcular_montos(itemX) {
                itemX.subtotal = Round.round(((Number(itemX.precio) * Number(itemX.cantidad))), 4)

                // itemX.mt_descuento = Number((Number(itemX.p_descuento / 100) *  ( Number(((Number(itemX.precio) * Number(itemX.cantidad)))  ).toFixed(2))).toFixed(2));
                itemX.mt_descuento = Number((Number(itemX.precio) * Number(itemX.cantidad)) * Number(itemX.p_descuento / 100))

                itemX.p_ajuste = Number(itemX.afectacionx.valor)

                itemX.mt_ajuste = Round.round((Number(itemX.p_ajuste / 100) * Number(((Number(itemX.precio) * Number(itemX.cantidad))))), 2)

                itemX.p_unitario = Round.round(((Number(itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste) / Number(itemX.cantidad))), 4)

                /* console.log(itemX.p_impuesto);
                          console.log(Number(itemX.p_impuesto/100));
                          console.log(itemX.subtotal-itemX.mt_descuento-itemX.mt_ajuste);
                          console.log(Number((Number(itemX.p_impuesto/100)*Number((itemX.subtotal-itemX.mt_descuento-itemX.mt_ajuste)))));
                          */
                /* let xy = Number((Number(itemX.p_impuesto/100)*Number((itemX.subtotal-itemX.mt_descuento-itemX.mt_ajuste))));
                          console.log(roundNumber.round(xy,2)); */

                itemX.mt_impuesto = Round.round(Number((Number(itemX.p_impuesto / 100) * Number((itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste)))), 2)

                itemX.total_sin_iva = Round.round(Number((itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste)), 4)

                itemX.total = Round.round((itemX.subtotal - itemX.mt_descuento - itemX.mt_ajuste + itemX.mt_impuesto), 4)

                if (!isNaN(itemX.mt_descuento)) {
                    return itemX.mt_descuento
                }
                return 0
            },

            obtenerTCParalela() {
                const self = this
                self.loading = true
                tc.obtenerTCParalela({
                    fecha: self.form.f_factura,
                    dias: self.form.dias_credito,
                }, data => {
                    if (data.tasa_paralela !== null) {
                        self.form.tasa_paralela = Number(data.tasa_paralela)
                        // self.form.tasa_oficial = Number(data.tasa);
                        self.form.f_vencimiento = data.fecha
                        self.form.detalleProductos.forEach((productox, key) => {
                            productox.precio_lista = Round.round((productox.precio_lista_me * self.form.t_cambio), 2)
                            productox.precio = Round.round((productox.precio_lista_me * self.form.t_cambio), 2)
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

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        self.form.t_cambio = 0
                        self.form.f_vencimiento = self.form.f_factura
                        self.form.detalleProductos = []
                    }
                    self.loading = false
                }, err => {
                    if (err.fecha[0]) {
                        self.form.t_cambio = 0
                        self.form.f_vencimiento = self.form.f_factura
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: `Ha ocurrido un error: ${err.fecha[0]}`,
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
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
                const self = this
                if (self.formCliente.departamento.municipios_departamento) {
                    self.municipios = self.formCliente.departamento.municipios_departamento
                    self.formCliente.municipio = self.municipios[0];
                }
            },

            nueva() {
                const self = this
                factura.nueva({
                        id_sucursal: self.id_sucursal,
                    }, data => {
                        self.sucursales = data.sucursales;
                        self.vias_pago = data.vias_pago;
                        self.afectaciones = data.afectaciones;
                        self.detalleForm.afectacionx = self.afectaciones[0]
                        self.monedas = data.monedas
                        self.bancos = data.bancos
                        // self.proyectos = data.proyectos;
                        // self.form.factura_bodega=self.bodegas[0];
                        // self.productos = [];
                        self.form.t_cambio = Number(data?.t_cambio?.tasa)
                        // Verificar si la respuesta del servidor es indefinida o la tasa de cambio es igual a 0
                        // De ser asi, alertar al usuario que espere un momento mientras las tasas de cambio del mes se descargan del BCN
                        if (data?.t_cambio?.tasa === undefined || Number(data.t_cambio.tasa) === 0) {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'CheckIcon',
                                        text: 'No se encontraron tasas de cambio para el mes actual, espere un momento.....',
                                        variant: 'warning',
                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })

                            self.filter.search.anio = self.form.f_factura.split('-')[0]
                            self.filter.search.mes = self.form.f_factura.split('-')[1]
                            // Llamado a la funcion de descarga de tasas de cambio
                            this.descargarTasas()
                            this.obtenerTC()
                        }
                        self.form.tasa_paralela = Number(data.t_cambio.tasa_paralela)
                        // self.zonas = data.zonas;
                        self.vendedores = data.vendedores
                        // self.formCliente.zona = data.zonas[0];
                        self.departamentos = data.departamentos
                        self.formCliente.departamento = self.departamentos[9]
                        self.municipios = self.formCliente.departamento.municipios_departamento
                        self.formCliente.municipio = self.municipios[5]
                        self.form.factura_vendedor = self.vendedores[0]
                        self.formCliente.vendedor = self.form.factura_vendedor
                        self.form.factura_sucursal = self.sucursales[0]
                        self.form.discount_max = Number(data.discount_max)
                        self.seleccionarSucursal()
                        self.loading = false
                    },
                    err => {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: `Ha ocurrido un error al cargar los datos. ${err}`,
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        self.loading = false
                    })
            },
            descargarTasas() {
                const self = this
                self.msg = 'Descargando datos del Banco Central, espere un momento.....'
                self.loading = true
                tc.descargarTasas(self.filter, data => {
                    // console.log(data);
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'BellIcon',
                                text: 'Tasas de cambio actualizadas',
                                variant: 'success',

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                    this.obtenerTC()
                }, err => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'BellIcon',
                                text: 'Ha ocurrido un error',
                                variant: 'warning',

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                    self.loading = false
                })
            },
            agregarDetalle() {
                const self = this
                if (self.detalleForm.productox && self.detalleForm.afectacionx) {
                    if (self.detalleForm.cantidad > 0 && self.detalleForm.precio_sugerido > 0) {
                        let cantidad = 0;
                        if (self.form.detalleProductos) {
                            self.form.detalleProductos.forEach((productox, key) => {
                                if (self.detalleForm.productox.id_producto === productox.productox.id_producto && (self.detalleForm.productox.id_bodega_producto === productox.productox.id_bodega_producto)) {
                                    cantidad = cantidad + productox.cantidad + self.detalleForm.cantidad
                                }
                            })
                        }

                        let i = 0
                        let keyx = 0
                        if (self.form.detalleProductos) {
                            self.form.detalleProductos.forEach((productox, key) => {
                                if ((self.detalleForm.productox.id_producto === productox.productox.id_producto)
                                    && (self.detalleForm.productox.id_bodega_producto === productox.productox.id_bodega_producto)) {
                                    i++
                                    keyx = key
                                }
                            })
                        }
                        if (i === 0) {
                            if (Number(self.detalleForm.cantidad) <= Number(self.detalleForm.productox.cantidad_disponible)) {
                                self.form.detalleProductos.push({
                                    productox: self.detalleForm.productox,
                                    afectacionx: self.detalleForm.afectacionx,
                                    cantidad: Round.round(Number(self.detalleForm.cantidad), 2),
                                    precio_costo: Number(self.detalleForm.productox.costo_promedio),
                                    precio_costo_me: Number(self.detalleForm.productox.costo_promedio_me),
                                    precio_lista_me: Round.round(Number(self.detalleForm.precio_sugerido_me), 2),
                                    precio_lista: Number(self.detalleForm.precio_sugerido),
                                    precio: Number(self.detalleForm.precio_sugerido),
                                    p_descuento: 0,
                                    mt_descuento: 0,
                                    p_impuesto: Number(self.detalleForm.productox.tasa_impuesto),
                                    e_minima: Number(self.detalleForm.productox.existencia_minima),
                                    mt_impuesto: 0,
                                    subtotal: 0,
                                    total: 0,
                                    total_sin_iva: 0,
                                    mt_ajuste: 0,
                                    p_ajuste: 0,
                                })
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Producto agregado correctamente.',
                                            variant: 'success',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            } else {
                                self.form.detalleProductos.push({
                                    productox: self.detalleForm.productox,
                                    afectacionx: self.detalleForm.afectacionx,
                                    cantidad: Math.min(Round.round(Number(self.detalleForm.cantidad), 2), self.detalleForm.productox.cantidad_disponible),
                                    precio_costo: Number(self.detalleForm.productox.costo_promedio),
                                    precio_costo_me: Number(self.detalleForm.productox.costo_promedio_me),
                                    precio_lista_me: Round.round(Number(self.detalleForm.precio_sugerido_me), 2),
                                    precio_lista: Number(self.detalleForm.precio_sugerido),
                                    precio: Number(self.detalleForm.precio_sugerido),
                                    p_descuento: 0,
                                    mt_descuento: 0,
                                    p_impuesto: Number(self.detalleForm.productox.tasa_impuesto),
                                    e_minima: Number(self.detalleForm.productox.existencia_minima),
                                    mt_impuesto: 0,
                                    subtotal: 0,
                                    total: 0,
                                    total_sin_iva: 0,
                                    mt_ajuste: 0,
                                    p_ajuste: 0,
                                })
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Se ha agregado la cantidad máximad disponible de este producto.',
                                            variant: 'warning',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            }
                        } else {
                            const nuevo_total = self.form.detalleProductos[keyx].cantidad + self.detalleForm.cantidad
                            if (nuevo_total <= self.form.detalleProductos[keyx].productox.cantidad_disponible) {
                                self.form.detalleProductos[keyx].cantidad = Round.round(nuevo_total, 2);
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Producto agregado.!',
                                            variant: 'success',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            } else {
                                const cantidad = Round.round(Number(self.form.detalleProductos[keyx].cantidad), 2);
                                const existencia_minima = Number(self.form.detalleProductos[keyx].productox.existencia_minima)
                                const umbral = 10 // umbral para mostrar la alerta adicional (puede ajustarlo según sus necesidades)

                                if (cantidad === Number(self.detalleForm.cantidad)) {
                                    // mostrar alerta si la cantidad es igual a la entrada del usuario
                                    this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'La cantidad se ha actualizado a la máxima disponible de este producto.',
                                            variant: 'warning',
                                        },
                                    }, {
                                        position: 'bottom-left',
                                    })
                                } else {
                                    // actualizar la cantidad con la cantidad máxima disponible
                                    self.form.detalleProductos[keyx].cantidad = Round.round(Number(self.form.detalleProductos[keyx].productox.cantidad_disponible), 2);

                                    if (cantidad <= existencia_minima) {
                                        // mostrar alerta de existencia mínima si la cantidad es menor o igual a la existencia mínima
                                        this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: 'Quedan pocas existencias.',
                                                variant: 'warning',
                                            },
                                        }, {
                                            position: 'bottom-left',
                                        })
                                    }
                                    this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'La cantidad se ha actualizado a la máxima disponible de este producto.',
                                            variant: 'warning',
                                        },
                                    }, {
                                        position: 'bottom-left',
                                    })
                                }
                            }
                        }

                        self.detalleForm.productox = {text: "Escanea el codigo de barras o escribe para buscar un producto"};
                        self.detalleForm.afectacionx = self.afectaciones[0];
                        self.detalleForm.cantidad = 0;
                        self.detalleForm.precio_sugerido = 0;
                        self.detalleForm.precio_sugerido_me = 0;
                        self.detalleForm.subtotal = 0;
                        self.detalleForm.total = 0;
                        self.detalleForm.total_sin_iva = 0;

                        this.foc = true
                        setTimeout(() => {
                            this.foc = false
                        }, 1000);
                    } else {
                        // this.$toast({
                        //         component: ToastificationContent,
                        //         props: {
                        //             title: 'Notificación',
                        //             icon: 'InfoIcon',
                        //             text: 'Los valores para cantidad y precio deben ser mayor a cero..',
                        //             variant: 'warning',
                        //
                        //         },
                        //     },
                        //     {
                        //         position: 'bottom-left',
                        //     })
                    }
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un producto.',
                                variant: 'warning',

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
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
                    self.form.detallePago = []
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

                    self.detalleFormPago.via_pagox = ''
                    self.detalleFormPago.monto = 0
                    self.detalleFormPago.monto_me = 0
                    self.detalleFormPago.moneda_x = ''
                    self.detalleFormPago.banco_x = ''
                    self.detalleFormPago.numero_minuta = ''
                    self.detalleFormPago.numero_nota_credito = ''
                    self.detalleFormPago.numero_cheque = ''
                    self.detalleFormPago.numero_transferencia = ''
                    self.detalleFormPago.numero_recibo_pago = ''
                }
            },
            viaPagoSelected() {
                if (this.detalleFormPago.moneda_x.id_moneda === 1) {
                    this.detalleFormPago.monto = this.form.pago_pendiente_mn;
                }
                if (this.detalleFormPago.moneda_x.id_moneda === 2) {
                    this.detalleFormPago.monto = this.form.pago_pendiente;
                }
            },
            agregarMetodoPago() {
                const self = this;
                if (self.detalleFormPago.via_pagox && self.detalleFormPago.moneda_x) {
                    if ((self.detalleFormPago.via_pagox.id_via_pago === 1 || self.detalleFormPago.via_pagox.id_via_pago === 3 || self.detalleFormPago.via_pagox.id_via_pago === 6) && self.detalleFormPago.banco_x.length === 0) {

                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'Debe seleccionar un Banco.',
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                    } else {
                        if (self.form.total_final > 0 && self.detalleFormPago.monto > 0) {
                            let i = 0;
                            let keyx = 0;
                            if (self.form.detallePago) {
                                self.form.detallePago.forEach((via_pago_x, key) => {
                                    if (self.detalleFormPago.via_pagox.id_via_pago === via_pago_x.via_pagox.id_via_pago && self.detalleFormPago.moneda_x.id_moneda === via_pago_x.moneda_x.id_moneda && self.detalleFormPago.banco_x.id_banco === via_pago_x.banco_x.id_banco && self.detalleFormPago.numero_minuta === via_pago_x.numero_minuta) {
                                        i++
                                        keyx = key
                                    }
                                })
                            }
                            let monto_me = 0
                            let
                                monto_mn = 0

                            if (self.detalleFormPago.moneda_x.id_moneda === 1) { // metodos de pago con moneda cordobas
                                monto_mn = Number(self.detalleFormPago.monto)
                                monto_me = Round.round((self.detalleFormPago.monto / self.form.t_cambio), 2)
                            } else if (self.detalleFormPago.moneda_x.id_moneda === 2) { // metodos de pago con moneda dolares
                                monto_me = Number(self.detalleFormPago.monto)
                                // const monto_mn_2 = (Number(self.form.total_final_cordobas) / Number(self.form.t_cambio)).toFixed(2);
                                // console.log(`calculo 4 decimales${monto_mn_2}`)
                                monto_mn = Round.round((self.detalleFormPago.monto * self.form.t_cambio), 2)
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

                                })
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Método de pago agregado.',
                                            variant: 'success',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            } else {
                                if (self.detalleFormPago.moneda_x.id_moneda === 1) {
                                    monto_mn = Number(self.detalleFormPago.monto)
                                    monto_me = Round.round(Number((self.detalleFormPago.monto / self.form.t_cambio)), 2)

                                    self.form.detallePago[keyx].monto = Round.round((self.form.detallePago[keyx].monto + self.detalleFormPago.monto), 2)
                                    self.form.detallePago[keyx].monto_me = Round.round((self.form.detallePago[keyx].monto / self.form.t_cambio), 2)
                                } else if (self.detalleFormPago.moneda_x.id_moneda === 2) {
                                    self.form.detallePago[keyx].monto_me = Round.round(self.form.detallePago[keyx].monto_me + self.detalleFormPago.monto, 2)
                                    self.form.detallePago[keyx].monto = Round.round(self.form.detallePago[keyx].monto_me * self.form.t_cambio, 2)
                                }

                                // let nuevo_monto_total = self.form.detallePago[keyx].monto + self.detalleFormPago.monto;

                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Pago agregado.',
                                            variant: 'warning',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            }

                            self.detalleFormPago.via_pagox = {descripcion: "Efectivo", id_via_pago: 2};
                            self.detalleFormPago.monto = 0;
                            self.detalleFormPago.monto_me = 0;
                            self.detalleFormPago.moneda_x = {
                                codigo: "C$",
                                decimales: 2,
                                descripcion: "Córdobas",
                                descripcion_singular: "Córdoba",
                                estado: 1,
                                f_creacion: "2022-02-18T14:13:45.000000Z",
                                f_modificacion: null,
                                id_moneda: 1,
                                u_creacion: "octaviom",
                                u_modificacion: null
                            },
                                self.detalleFormPago.banco_x = [];
                            self.detalleFormPago.numero_minuta = '';
                            self.detalleFormPago.numero_nota_credito = '';
                            self.detalleFormPago.numero_cheque = '';
                            self.detalleFormPago.numero_transferencia = '';
                            self.detalleFormPago.numero_recibo_pago = '';

                            setTimeout(() => {
                                self.viaPagoSelected();
                            }, 500);
                        } else {
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'El monto debe ser mayor a cero.',
                                        variant: 'warning',

                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                        }
                    }
                } else {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Debe seleccionar un método y una moneda.',
                                variant: 'warning',

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                }
            },

            eliminarLineaPago(item, index) {
                if (this.form.detallePago.length >= 1) {
                    this.form.detallePago.splice(index, 1)
                    setTimeout(() => {
                        this.viaPagoSelected();
                    }, 500);
                } else {
                    this.form.detallePago = []
                }
            },

            procesar_factura() {
                const self = this
                // self.$swal.fire({
                //     title: 'Esta seguro de completar la factura?',
                //     text: 'Detalles de la factura: ',
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     allowOutsideClick: false,
                //     confirmButtonText: 'Si, confirmar',
                //     cancelButtonText: 'Cancelar',
                // }).then(result => {
                //     if (result.value) {
                this.cerrarModalPago();
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
                        allowOutsideClick: false,
                        confirmButtonText: 'Si, confirmar',
                        cancelButtonText: 'Cancelar',
                    }).then(result => {
                        if (result.value) {
                            this.form.codigo_autorizacion = result.value
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
                                            /*      this.$router.push({
                    name: 'cajabanco-facturas',
                  }) */
                                            this.$router.push({
                                                name: 'cajabanco-factura-mostrar',
                                                params: {
                                                    id_factura: data,
                                                },
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

                                                },
                                            },
                                            {
                                                position: 'bottom-left',
                                            })
                                    } else if (err.status === 'fields_empty') {
                                        self.errorMessages = err.result
                                        this.$toast({
                                                component: ToastificationContent,
                                                props: {
                                                    title: 'Notificación',
                                                    icon: 'InfoIcon',
                                                    text: 'Favor revise los datos faltantes.',
                                                    variant: 'warning',

                                                },
                                            },
                                            {
                                                position: 'bottom-left',
                                            })
                                    } else {
                                        this.$toast({
                                                component: ToastificationContent,
                                                props: {
                                                    title: 'Notificación',
                                                    icon: 'InfoIcon',
                                                    text: 'Ha ocurrido un error inesperado.',
                                                    variant: 'warning',

                                                },
                                            },
                                            {
                                                position: 'bottom-left',
                                            })
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
                            })
                            self.btnAction = 'Facturar'
                        }
                    })
                } else {
                    factura.registrar(
                        self.form,
                        // eslint-disable-next-line no-unused-vars
                        data => {
                            console.log(data)
                            this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: 'Notificación',
                                        icon: 'InfoIcon',
                                        text: 'La Factura fue registrada correctamente.',
                                        variant: 'success',

                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                            this.$router.push({
                                name: 'cajabanco-factura-mostrar',
                                params: {
                                    id_factura: data,
                                },
                            })
                            //               this.$swal.fire(
                            //                   'Factura Registrada!',
                            //                   'La Factura fue registrada correctamente',
                            //                   'success',
                            //               ).then(result2 => {
                            //                   if (result2.value) {
                            //                       /* this.$router.push({
                            //   name: 'cajabanco-facturas',
                            // }) */
                            //                       this.$router.push({
                            //                           name: 'cajabanco-factura-mostrar',
                            //                           params: {
                            //                               id_factura: data,
                            //                           },
                            //                       })
                            //                   }
                            //               })
                        },
                        err => {
                            if (err.status === 'fields_empty') {
                                self.errorMessages = err.result
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Favor revise los datos faltantes.',
                                            variant: 'warning',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            } else {
                                this.$toast({
                                        component: ToastificationContent,
                                        props: {
                                            title: 'Notificación',
                                            icon: 'InfoIcon',
                                            text: 'Ha ocurrido un error inesperado.',
                                            variant: 'warning',

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
                            }
                            self.btnAction = 'Facturar'
                        },
                    )
                }
                // } else if (result.dismiss === 'cancel') {
                //
                //     this.$swal({
                //         title: 'Cancelado',
                //         text: 'No se ha realizado el registro',
                //         icon: 'error',
                //         customClass: {
                //             confirmButton: 'btn btn-success',
                //         },
                //     })
                //     self.btnAction = 'Facturar'
                // }
                // })
            },

            registrar() {
                const self = this
                self.btnAction = 'Registrando, espere....'

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

                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
                            // self.errorMessages.serie = Array('Error serie');
                            self.btnAction = 'Facturar'
                        }
                    } else {
                        this.procesar_factura()
                    }
                }

                /* facturas de credito */
                if (self.form.id_tipo === 2) {
                    if (self.form.total_final > 0) { // && (self.form.pago_pendiente_mn > 1 || self.form.pago_pendiente > 0)
                        if (self.form.factura_cliente) {
                            if (self.form.factura_cliente.limite_credito_me > 0) {
                                if (self.form.pago_pendiente <= Number(self.form.factura_cliente.monto_credito_dol_disponible) || self.form.pago_pendiente <= Number(self.form.proforma_cliente.monto_credito_dol_disponible)) {
                                    self.procesar_factura()
                                } else {
                                    /* alertify.warning(`El monto máximo actual de crédito de este cliente es C$ ${Number(self.form.factura_cliente.monto_credito_disponible).toFixed(2)
                                     } , el monto de ésta factura supera ese monto`, 5); */

                                    this.$toast({
                                            component: ToastificationContent,
                                            props: {
                                                title: 'Notificación',
                                                icon: 'InfoIcon',
                                                text: `El monto máximo actual de crédito de este cliente es $ ${Round.round(this.form.factura_cliente.monto_credito_dol_disponible, 2)} El monto de la factura supera este limite.`,
                                                variant: 'warning',

                                            },
                                        },
                                        {
                                            position: 'bottom-left',
                                        })
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

                                        },
                                    },
                                    {
                                        position: 'bottom-left',
                                    })
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

                                    },
                                },
                                {
                                    position: 'bottom-left',
                                })
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

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        // self.errorMessages.serie = Array('Error serie');
                        self.btnAction = 'Facturar'
                    }
                }

                /* Factura por anticipo */
                if (self.form.id_tipo === 4) {
                    if (self.form.total_final) {
                        this.procesar_factura()
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'AlertTriangleIcon',
                                    text: 'El monto total de los recibos no coincide con lo facturado.',
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        // self.errorMessages.serie = Array('Error serie');
                        self.btnAction = 'Facturar'
                    }
                }
            },

            registrarCliente() {
                const self = this
                self.btnActionCliente = 'Registrando, espere....'

                if (!self.registrandoCliente) self.registrandoCliente = true
                cliente.registrarBasico(self.formCliente, data => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: 'Cliente registrado exitosamente.',
                                variant: 'success',

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                    // console.log(data);
                    self.form.factura_cliente = data
                    // self.form.factura_vendedor = self.formCliente.vendedor;
                    self.form.tipo_identificacion = self.form.factura_cliente.tipo_persona
                    if (self.form.factura_cliente.tipo_persona === 1) {
                        self.form.identificacion = self.form.factura_cliente.numero_cedula
                    } else {
                        self.form.identificacion = self.form.factura_cliente.numero_ruc
                    }

                    self.tipo_cliente = self.formCliente.tipo_cliente;
                    self.btnActionCliente = 'Registrar Cliente'
                    // self.form.factura_vendedor
                    self.registrandoCliente = false

                    self.formCliente.tipo_persona = 1
                    self.formCliente.nombre_comercial = ''
                    self.formCliente.vendedor = self.form.factura_vendedor;
                    self.formCliente.numero_ruc = ''
                    self.formCliente.numero_cedula = ''
                    self.formCliente.direccion = ''
                    self.formCliente.tipo_cliente = 1
                    self.formCliente.telefono = ''
                    self.formCliente.correo = ''
                    self.formCliente.municipio = ''
                    self.formCliente.giro_negocio = ''
                    self.formCliente.zona = ''
                    self.formCliente.id_impuesto = 1
                    self.formCliente.tipo_contribuyente = 1
                    self.formCliente.retencion_ir = true
                    self.formCliente.retencion_imi = true
                    self.formCliente.clasificacion = 1
                    self.formCliente.permite_credito = false
                    self.formCliente.plazo_credito = 0
                    self.formCliente.limite_credito = 0
                    self.formCliente.porcentaje_descuento = 0
                    self.formCliente.observaciones = ''
                    self.formCliente.permite_consignacion = false
                    self.formCliente.tramite_cheque = 15

                    self.$refs.modal.hide()
                }, err => {
                    this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: 'Notificación',
                                icon: 'InfoIcon',
                                text: err,
                                variant: 'warning',

                            },
                        },
                        {
                            position: 'bottom-left',
                        })
                    self.errorMessages = err
                    self.registrandoCliente = false
                    self.btnActionCliente = 'Registrar Cliente'
                })
            },

            seleccionarTipo() {
                const self = this
                if (self.form.id_tipo === 4) {
                    self.form.permite_anticipo = true
                } else {
                    self.form.permite_anticipo = false
                }
                if (self.form.id_tipo === 1 || self.form.id_tipo === 4) {
                    self.form.dias_credito = 0
                } else {
                    self.form.aplicaIR = false
                    self.form.aplicaIMI = false

                    self.form.mt_retencion = 0
                    self.form.mt_retencion_imi = 0

                    self.form.doc_exoneracion_ir = ''
                    self.form.doc_exoneracion_imi = ''

                    self.form.dias_credito = 0
                    self.form.dias_credito = self.plazo_maximo_credito
                }
                self.obtenerTCParalela()
                /* calcular fecha */
            },
            onDateSelect(date) {
                this.form.f_factura = this.form.f_facturax
                this.obtenerTC()
            },
            obtenerTC() {
                // console.log('ejecutando obtener tc con fecha: ' + this.form.fecha_entrada_x);
                const self = this
                tc.obtenerTC({
                    fecha: self.form.f_factura,
                }, data => {
                    if (data.tasa !== null) {
                        self.form.t_cambio = Number(data.tasa)
                    } else {
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encuentran tasas de cambia para esta fecha.',
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        self.form.t_cambio = 0
                    }
                    self.loading = false
                }, err => {
                    if (err.fecha) {
                        self.form.t_cambio = 0
                        this.$toast({
                                component: ToastificationContent,
                                props: {
                                    title: 'Notificación',
                                    icon: 'InfoIcon',
                                    text: 'No se encuentran tasas de cambia para esta fecha.',
                                    variant: 'warning',

                                },
                            },
                            {
                                position: 'bottom-left',
                            })
                        self.loading = false
                    }
                })
            },
            // llamarBanco(){
            //     this.$nextTick(() => {
            //         this.$refs.banco.focus();
            //     })
            // },
            pasarDetalle() {
                this.$nextTick(() => {
                    this.$refs.agregarpago.focus();
                })
            },

            configurarZoom() {
                document.body.style.zoom = '80%';
            }

        },
        mounted() {
            // this.obtenerAfectacionesTodas();
            // this.obtenerTodasBodegasProductos();
            this.nueva();
            this.configurarZoom();
        },
        /*  created() {
    this.formCliente = this.clientes;
  }, */

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

    .tmr {
        font-size: 1rem;
    }

    .tmr input {
        width: 30px;
        height: 30px;
        margin-right: 5px;
        border: 2px solid green;
    }

    .tmr .custom-control-label {
        padding: 7px 20px;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        z-index: 1040;
        width: 100%;
        height: 100%;
    }

    @media (max-width: 1024px) {

        #modal-selectPago___BV_modal_content_ {
            width: 1024px;
        }
        .modal-xl {
            margin: 8%;
        }
    }

</style>
