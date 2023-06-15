<template>
  <b-card>
    <form>
      <template>
        <div class="demo-spacing-0 mb-1">
          <b-alert
              variant="primary"
              show
          >
            <div class="alert-body">
              <span>Datos solicitud.</span>
            </div>
          </b-alert>
        </div>
      </template>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Tipo Solicitud</label>
            <v-select
                label="descripcion"
                v-model="form.tipo_solicitud"
                :options="tipos_solicitud"
                placeholder="Selecciona un tipo de solicitud"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

            >
              <template slot="no-options">
                No se han encontrado registros
              </template>
            </v-select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Tipo Beneficiario</label>
            <select class="form-control" v-on:change="limpiarDetalles" v-model.number="form.tipo_beneficiario">
              <option :disabled="form.tipo_solicitud.id_tipo_solicitud===3" value="1">Proveedor|Acreedor</option>
              <option :disabled="form.tipo_solicitud.id_tipo_solicitud!==3" value="2">Trabajador</option>
            </select>
          </div>
        </div>
        <div class="col-md-4" v-if="form.tipo_beneficiario===1">
          <div class="form-group">
            <label for="">Proveedor | Acreedor</label>
            <v-select
                label="nombre_comercial"
                v-model="form.proveedor"
                :options="proveedores"
                placeholder="Selecciona un proveedor"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

            >
              <template slot="no-options">
                No se han encontrado registros
              </template>
            </v-select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Beneficiario</label>
            <input type="text" class="form-control" v-model="form.beneficiario" placeholder="Dígite el benficiario">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Fecha solicitud</label>
            <b-form-datepicker
                :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                :disabled="false"
                class="mb-0"
                local="es"
                placeholder="f.solicitud"
                selected-variant="primary"
                v-model="form.fecha_solicitud"
            />
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Moneda</label>
            <v-select
                label="descripcion"
                v-model="form.moneda"
                :options="monedas"
                placeholder="Selecciona una moneda"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

            >
              <template slot="no-options">
                No se han encontrado registros
              </template>
            </v-select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Monto {{(form.moneda)?form.moneda.codigo:'N/D'}}</label>
            <input :disabled="((!form.no_tiene_cuentasxpagar) && form.tipo_beneficiario !== 2)" type="number" class="form-control" v-model="form.monto_solicitado" placeholder="Dígite un monto">
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group">
            <label for="">Concepto</label>
            <input type="text" class="form-control" v-model="form.concepto" placeholder="Dígite el concepto">
          </div>
        </div>
      </div>
      <div  class="col-md-12">
        <div class="demo-inline-spacing">
          <b-form-checkbox  @input="limpiarDetallesEsp" v-model="form.no_tiene_cuentasxpagar">
            <strong> Ésta solicitud de pago no tiene órdenes de compra registradas previamente </strong>
          </b-form-checkbox>
        </div>
      </div>
      <template v-if="!form.no_tiene_cuentasxpagar">
        <template>
          <div class="demo-spacing-0 mt-1 mb-1">
            <b-alert
                variant="primary"
                show
            >
              <div class="alert-body">
                <span>Detalles de órdenes de compra por pagar</span>
              </div>
            </b-alert>
          </div>
        </template>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="">Listado de Documentos por pagar</label>
              <v-select
                  label="descripcion"
                  v-model="detalleForm.cuentax"
                  v-on:input="cargar_detalles_cuenta"
                  :options="cuentas"
                  placeholder="Selecciona una cuenta por pagar"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

              >
                <template slot="no-options">
                  No se han encontrado registros
                </template>
              </v-select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="" >Saldo pendiente</label>
              <label class="form-control" > {{(detalleForm.cuentax)?detalleForm.cuentax.saldo_actual_org:0 | formatMoney(2)}}</label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="" >Monto abono C$</label>
              <input @change="detalleForm.monto = detalleForm.cuentax? Math.max(Math.min(Number(!isNaN(detalleForm.monto)?detalleForm.monto.toFixed(2):0), Number(Number(detalleForm.cuentax.saldo_actual_org).toFixed(2))), 1):0"
                     class="form-control" ref="abono" type="number" min="0"
                     v-model.number="detalleForm.monto">
            </div>
          </div>
            <div class="col-md-2">
              <div class="form-group">
                <b-button class="mt-2" type="submit" variant="info" @click="agregarDetalle">
                  Agregar
                </b-button>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-sm-12">
            <b-alert variant="danger" show>
              <ul class="error-messages">
                <li
                    :key="message"
                    v-for="message in errorMessages.detalleCuentasxPagar"
                    v-text="message"
                ></li>
              </ul>
            </b-alert>

            <table class="table table-bordered table-hover table-responsive"  >
              <thead>
              <tr>
                <th></th>
                <th style="min-width:50px">Documento Origen</th>
                <th style="min-width:300px">Concepto</th>
                <th style="min-width:100px">Monto Abono {{(form.moneda)?form.moneda.codigo:'N/D'}}</th>
                <th style="min-width:150px" colspan="2">Saldo Pendiente {{(form.moneda)?form.moneda.codigo:'N/D'}}</th>
              </tr>
              </thead>
              <tbody>
              <template v-for="(item, index) in form.detalleCuentasxPagar">
                <tr>
                  <td style="width: 5%">
                    <button @click="eliminarLinea(item, index)">
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                  <td style="width: 10%">
                    <input class="form-control" disabled v-model="item.cuentax.no_documento_origen">
                  </td>

                  <td style="width: 20%">
                    <input disabled class="form-control" v-model="item.descripcion_pago">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detalleCuentasxPagar.${index}.descripcion_pago`]"
                          v-text="message">
                      </li>
                    </ul>
                  </td>

                  <td class="text-center" style="width: 12%">
                    <input @change="establecerConcepto(item)"
                           min="0" class="form-control" v-model.number="item.monto">
                    <ul class="error-messages">
                      <li
                          :key="message"
                          v-for="message in errorMessages[`detalleCuentasxPagar.${index}.monto`]"
                          v-text="message">
                      </li>
                    </ul>
                  </td>
                  <td colspan="2" class="text-center" style="width: 15%">
                    <strong>{{(form.moneda)?form.moneda.codigo:'N/D'}} {{calcularSaldoX(item)| formatMoney(2)}}</strong>
                  </td>

                </tr>
                <tr></tr>
              </template>
              </tbody>
              <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-right"> <strong> Total a pagar {{(form.moneda)?form.moneda.codigo:'N/D'}}</strong></td>
                <td  class="text-center">
                  <strong>{{total_a_pagar| formatMoney(2)}}</strong>
                </td>
                <td class="text-right"> <strong> Total saldo pendiente {{(form.moneda)?form.moneda.codigo:'N/D'}}</strong></td>
                <td  class="text-center">
                  <strong>{{form.saldo_total| formatMoney(2)}}</strong>
                </td>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </template>

    </form>
    <b-card-footer class="content-box-footer text-right mt-1">
      <b-row>
        <div class="col-sm-12 text-lg-right">
          <router-link  :to="{name: 'cajabanco-SolicitudesPagos'}">
            <b-button type="submit" variant="secondary" class="mt-1 mr-1">
              Cancelar
            </b-button>
          </router-link>
          <b-button class="mt-1" type="submit" variant="primary" @click="registrar" :disabled="btnAction !== 'Registrar'">
            {{btnAction}}
          </b-button>
        </div>
      </b-row>
    </b-card-footer>
    <template v-if="loading">
      <BlockUI>
        <b-spinner variant="info" label="loading..."/>
      </BlockUI>
    </template>
  </b-card>
</template>

<script type="text/ecmascript-6">
import loadingImage from '../../../assets/images/loader/block50.gif'
import numberasstring from '@/assets/custom-scripts/NumberAsString'
import roundNumber  from '@/assets/custom-scripts/Round'
import {
  BButton, BAlert, BFormCheckbox, BFormSelect, BCard, BCardFooter, BRow, BSpinner,
  BModal, VBToggle, VBTooltip, BFormGroup, BListGroup, BListGroupItem, BDropdown, VBModal,
  BDropdownDivider, BDropdownItem,BFormInput,BFormDatepicker} from 'bootstrap-vue'
import vSelect from 'vue-select'
import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
import solicitud from "../../../api/Tesoreria/SolicitudesPagos";
import axios from 'axios'
import Ripple from "vue-ripple-directive";

export default {
  components:{
    BButton,
    BAlert,
    BFormCheckbox,
    vSelect,
    BFormSelect,
    BCard,
    BCardFooter,
    BRow,
    BSpinner,
    BModal,
    VBTooltip,
    VBToggle,
    BFormGroup,
    BListGroup,
    BListGroupItem,
    BDropdown,
    BDropdownItem,
    BDropdownDivider,
    BFormInput,
    BFormDatepicker,
  },
  directives : {
    Ripple,
    'b-tooltip': VBTooltip,
    'b-toggle' : VBToggle,
    'b-modal' : VBModal
  },
  data() {
    return {
      loading:false,
      msg: 'Guardando los datos, espere un momento',
      url : loadingImage,   //It is important to import the loading image then use here
      cuentas:[],
      proveedores:[],
      detalleForm: {
        cuentax: '',
        fecha_pago: '',
        descripcion_pago: '',
        monto: 0,
        monto_me: 0,
        moneda_x:[],
        doc_exoneracion_ir:'',
        doc_exoneracion_imi:'',
        aplicaIR:false,
        aplicaIMI:false,
      },
      form: {
        tipo_solicitud:'',
        tipo_beneficiario:1,
        no_tiene_cuentasxpagar:true,
        proveedor : {},
        trabajador : {},
        beneficiario : '',
        concepto: '',
        lugar:'',
        moneda : '',
        t_cambio: 0,
        monto_solicitado : 0,
        fecha_solicitud : '',
        monto_letras: '',
        detalleCuentasxPagar: [],
      },
      monedas:[],
      tipos_solicitud: [],
      btnAction: 'Registrar',
      errorMessages: []
    }
  },
  computed: {
    total_a_pagar() {
      let self = this;

      if(self.form.tipo_beneficiario === 1 && !self.form.no_tiene_cuentasxpagar){
        self.form.monto_solicitado = Number(this.form.detalleCuentasxPagar.reduce((carry, item) => {
          return (carry + Number(item.monto))
        }, 0).toFixed(2));

        self.form.saldo_total = Number(this.form.detalleCuentasxPagar.reduce((carry, item) => {
          return (carry + Number(roundNumber.round(Number(item.cuentax.saldo_actual_org), 2)) - item.monto)
        }, 0).toFixed(2));

        if (!isNaN(this.form.monto_solicitado)) {
          this.form.monto_solicitado_me = roundNumber.decimalAdjust('ceil', Number((this.form.monto_solicitado / this.form.t_cambio).toFixed(4)), -2);
          this.form.saldo_total_me = roundNumber.decimalAdjust('ceil', Number((this.form.saldo_total / this.form.t_cambio).toFixed(4)), -2);

          return this.form.monto_solicitado;
        } else return 0;
      }
    },

  },
  methods: {
    registrar(){
      var self = this
      self.btnAction = 'Registrando, espere....'
      self.loading = true;
      self.monto_letras();
      solicitud.registrar(self.form, data =>{
            self.loading = false;
            this.$toast({
                  component : ToastificationContent,
                  props: {
                    title : 'Notificación',
                    icon : 'CheckIcon',
                    text : 'Datos registrados correctamente.',
                    variant : 'success',
                  }
                },
                {
                  position : 'bottom-right'
                });
            this.$router.push({
              name : 'cajabanco-SolicitudesPagos'
            })
          },
          err =>{
            self.loading = false;
            self.errorMessages = err
            self.btnAction = 'Registrar'
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title : 'Notificación',
                    icon : 'InfoIcon',
                    text : 'Ha ocurrido un error, intentelo de nuevo',
                    variant : 'warning',

                  }
                },
                {
                  position : 'bottom-right'
                });
            /*this.$router.push({
                name : 'inventario-tipos-proveedores'
            })*/
          })
    },
    monto_letras(){
      if(this.form.moneda && Number(this.form.monto_solicitado) >0){
        this.form.monto_letras = numberasstring.numberasstring(this.form.monto_solicitado,this.form.moneda.descripcion_singular.toUpperCase(),this.form.moneda.descripcion.toUpperCase(),true);
      }else{
        this.form.monto_letras ='Seleccione un tipo de moneda';
      }
      return this.form.monto_letras;
    },
    seleccionarTrabajador(e) {
      const trabajador = e.target.value;
      var self = this;
      self.form.trabajador = trabajador;
      self.form.beneficiario = trabajador.text;
      self.form.detalleCuentasxPagar = [];
      self.form.detallePago = [];
      self.detalleForm.cuentax = '';
    },
    limpiarDetallesEsp(){
      let self = this;
      self.form.detalleCuentasxPagar = [];
      self.form.trabajador = {};
    },

    limpiarDetalles(){
      let self = this;
      self.form.detalleCuentasxPagar = [];
      self.form.trabajador = {},
          self.form.proveedor = {}
    },

    limpiarDetallesGen(){
      let self = this;
      self.form.detalleCuentasxPagar = [];
      self.form.trabajador = {},
          self.form.proveedor = {}
      self.form.tipo_beneficiario='';
    },
    nuevo() {
      var self = this;
      solicitud.nueva({
          }, data => {
            self.form.t_cambio = Number(data.t_cambio.tasa);
            self.monedas = data.monedas;
            self.tipos_solicitud = data.tipos_solicitud;
            self.form.tipo_solicitud = self.tipos_solicitud[0];
            self.form.moneda = self.monedas[0]
            self.proveedores = data.proveedores;
            self.loading = false;
          },
          err => {
            self.loading= false;
            console.log(err);
          })

    },
    seleccionarBeneficiario(e) {
      const beneficiario = e.target.value;
      var self = this;
      self.form.proveedor = beneficiario;
      self.form.beneficiario = beneficiario.paguese_a;

      self.loading = true;
      self.form.detalleCuentasxPagar = [];
      self.form.detallePago = [];
      self.detalleForm.cuentax = '';

      cuenta.obtenerCuentasProveedor({
        id_proveedor: self.form.proveedor.id_proveedor
      }, data => {
        if (data !== null) {
          self.cuentas = data;
          self.detalleForm.cuentax = '';
        } else {
          alertify.warning("No se encuentran cuentas para este cliente.", 5);
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
      })


    },

    onDateSelect(date) {
      this.form.fecha_solicitud = moment(date).format("YYYY-MM-DD"); //
    },

    calcularSaldoX(item){

      return Number((roundNumber.round(Number(item.cuentax.saldo_actual_org),2))-Number(item.monto).toFixed(2));

    },

    cargar_detalles_cuenta() {
      var self = this;
      if(self.detalleForm.cuentax)
        self.detalleForm.moneda_x = self.monedas[1];
      self.detalleForm.monto_me = 0;
      self.detalleForm.monto = Number(Number(self.detalleForm.cuentax.saldo_actual_org).toFixed(2));
    },

    establecerConcepto(itemX) {
      itemX.monto = Math.max(Math.min(Number(!isNaN(itemX.monto)?itemX.monto.toFixed(2):0), Number(Number(itemX.cuentax.saldo_actual_org).toFixed(2))), 1);
      if(itemX.monto === Number(Number(itemX.cuentax.saldo_actual_org).toFixed(2))){
        itemX.descripcion_pago = 'Cancela factura No.'+itemX.cuentax.no_documento_origen+'.';
      }else{
        itemX.descripcion_pago = 'Abona factura No.'+itemX.cuentax.no_documento_origen+'. Saldo C$'+(Number(itemX.cuentax.saldo_actual_org)-itemX.monto).toFixed(2)+'.';
      }

      let monto_retencion_ir = 0, monto_retencion_imi = 0;

      if(itemX.aplicaIR){
        monto_retencion_ir = roundNumber.round(Number(itemX.monto)*0.02,2);
      }else{
        monto_retencion_ir=0;
      }

      if(itemX.aplicaIMI){
        monto_retencion_imi = roundNumber.round(Number(itemX.monto)*0.01,2);
      }else{
        monto_retencion_imi=0;
      }

      itemX.monto_retencion_ir=monto_retencion_ir;
      itemX.monto_retencion_imi=monto_retencion_imi;

    },

    calcularEquivalente(itemX) {
      if(itemX.moneda_x.id_moneda===1 && itemX.monto > 0){
        itemX.monto_me = Number((itemX.monto/this.form.t_cambio).toFixed(2));
      }

      if(itemX.moneda_x.id_moneda===3 && itemX.monto_me > 0){
        itemX.monto = roundNumber.round(Number((itemX.monto_me * this.form.t_cambio)),2);
      }
    },


    agregarDetalle() {
      var self = this;
      if (self.detalleForm.cuentax) {
        if (self.detalleForm.monto > 0) {
          if(self.detalleForm.cuentax.id_moneda ===  self.form.moneda.id_moneda){
            let validacion = 0;

            if(validacion===0) {
              let i = 0;
              let keyx = 0;

              if (self.form.detalleCuentasxPagar) {
                self.form.detalleCuentasxPagar.forEach((cuentax, key) => {
                  if (self.detalleForm.cuentax.id_cuentaxpagar === cuentax.cuentax.id_cuentaxpagar) {
                    i++;
                    keyx = key;
                  }
                });
              }
              if (i === 0) {

                if (Number(Number(self.detalleForm.monto).toFixed(2)) === Number(Number(self.detalleForm.cuentax.saldo_actual_org).toFixed(2))) {
                  self.detalleForm.descripcion_pago = 'Cancela factura No.' + self.detalleForm.cuentax.no_documento_origen + '.';
                } else {
                  self.detalleForm.descripcion_pago = 'Abona factura No.' + self.detalleForm.cuentax.no_documento_origen + '. Saldo C$' + (Number(self.detalleForm.cuentax.saldo_actual_org) - self.detalleForm.monto).toFixed(2) + '.';
                }

                let monto_retencion_ir = 0, monto_retencion_imi = 0;

                if (self.detalleForm.aplicaIR) {
                  monto_retencion_ir = roundNumber.round(Number(self.detalleForm.monto) * 0.02, 2);
                } else {
                  monto_retencion_ir = 0;
                }

                if (self.detalleForm.aplicaIMI) {
                  monto_retencion_imi = roundNumber.round(Number(self.detalleForm.monto) * 0.01, 2);
                } else {
                  monto_retencion_imi = 0;
                }

                self.form.detalleCuentasxPagar.push({
                  cuentax: self.detalleForm.cuentax,
                  monto: Number(self.detalleForm.monto),
                  monto_me: Number((self.detalleForm.monto / self.form.t_cambio).toFixed(2)),
                  monto_subtotal: roundNumber.round(Number(self.detalleForm.monto - monto_retencion_imi - monto_retencion_ir), 2),
                  monto_retencion_ir: monto_retencion_ir,
                  monto_retencion_imi: monto_retencion_imi,
                  aplicaIR: self.detalleForm.aplicaIR,
                  aplicaIMI: self.detalleForm.aplicaIMI,
                  doc_exoneracion_ir: self.detalleForm.doc_exoneracion_ir,
                  doc_exoneracion_imi: self.detalleForm.doc_exoneracion_imi,
                  descripcion_pago: self.detalleForm.descripcion_pago,

                });
                alertify.success("Artículo agregado!", 5);
              } else {
                let nuevo_total = self.form.detalleCuentasxPagar[keyx].monto + self.detalleForm.monto;
                console.log(nuevo_total);
                if (nuevo_total <= Number(self.form.detalleCuentasxPagar[keyx].cuentax.saldo_actual_org)) {
                  self.form.detalleCuentasxPagar[keyx].monto = nuevo_total;
                  self.form.detalleCuentasxPagar[keyx].monto_me = Number((self.detalleForm.monto / self.form.t_cambio).toFixed(2));
                  alertify.success("Cuenta agregada!", 5);

                  if (nuevo_total === Number(self.form.detalleCuentasxPagar[keyx].cuentax.saldo_actual_org)) {
                    self.form.detalleCuentasxPagar[keyx].descripcion_pago = 'Cancela factura No.' + self.form.detalleCuentasxPagar[keyx].cuentax.no_documento_origen + '.';
                  } else {
                    self.form.detalleCuentasxPagar[keyx].descripcion_pago = 'Abona factura No.' + self.detalleCuentasxPagar[keyx].cuentax.no_documento_origen + '. Saldo C$' + (Number(self.form.detalleCuentasxPagar[keyx].cuentax.saldo_actual_org) - nuevo_total).toFixed(2) + '.';
                  }

                } else {
                  self.form.detalleCuentasxPagar[keyx].monto = Number(self.form.detalleCuentasxPagar[keyx].cuentax.saldo_actual_org);
                  self.form.detalleCuentasxPagar[keyx].monto_me = Number((self.form.detalleCuentasxPagar[keyx].cuentax.saldo_actual_org / self.form.t_cambio).toFixed(2));
                  self.form.detalleCuentasxPagar[keyx].descripcion_pago = 'Cancela factura No.' + self.form.detalleCuentasxPagar[keyx].cuentax.no_documento_origen + '.';
                  alertify.warning("Se ha agregado el monto maximo disponible para cancelar esta cuenta", 5);
                }
              }

              self.detalleForm.cuentax = null;
              self.detalleForm.monto_me = 0;
              self.detalleForm.monto = 0;
              self.detalleForm.descripcion_pago = '';
              this.$refs.cuenta.$el.focus()
            }
          }else{
            alertify.warning("La moneda de la cuenta por pagar es distinta de la moneda de la solicitud", 5);
          }
        } else {
          alertify.warning("El monto del abono debe ser mayor que cero", 5);
        }
      } else {
        alertify.warning("Debe seleccionar una cuenta para realizar el abono", 5);
      }
    },
    eliminarLinea(item, index) {
      if (this.form.detalleCuentasxPagar.length >= 1) {
        this.form.detalleCuentasxPagar.splice(index, 1);

      } else {
        this.form.detalleCuentasxPagar = [];
      }
    },



  },
  mounted() {
    this.nuevo();
  }

}
</script>
<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>
