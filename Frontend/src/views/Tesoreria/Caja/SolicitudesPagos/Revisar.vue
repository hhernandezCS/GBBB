<template>
  <div class="main">
    <div class="row">
      <div class="col-md-12">
        <div class="content-box">
          <div class="content-box-header">
            <div class="box-title">Crear Nuevo Documento Contable</div>
            <div class="box-description">Formulario para registrar solicitud de pago</div>
          </div>
            <p>
                <button aria-controls="collapseExample" aria-expanded="false" class="btn btn-primary" data-target="#collapseExample" data-toggle="collapse" type="button">
                   Ver Detalles de Solicitud
                </button>
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">

                    <br>
                    <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for>Beneficiario: </label>
                            <label for><strong>{{solicitud_original.beneficiario}}</strong> </label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for>Monto: </label>
                            <label for><strong>{{solicitud_original.monto}}</strong> </label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for>Monto en letras: </label>
                            <label for><strong>{{solicitud_original.monto_letras}}</strong> </label>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for>         Concepto: </label>
                            <label for><strong>{{solicitud_original.concepto}}</strong> </label>
                        </div>
                    </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for>Lugar y fecha: </label>
                                <label for><strong>{{solicitud_original.lugar_solicitud + ' ' + moment(solicitud_original.fecha_solicitud).format("YYYY-MM-DD")}}</strong> </label>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
<br>
          <div class="row">

          <div class="col-sm-2">
              <div class="form-group">
                <label for>Tipo Documento</label>
                <v-select :options="tipos_documentos"
                  label="descripcion"
                  ref="tipo"
                  v-model="form.tipoDocumento"

                  v-on:input="obtenerNumeroDocumento"
                ></v-select>
                <!--v-on:change="cambiarFoco()"-->
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.tipoDocumento"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>
        
          <div class="col-sm-2">
              <div class="form-group">
                <label for>Numero Documento <small>(Automático)</small></label>
                <input class="form-control" readonly type="text" v-model="form.num_documento">
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.num_documento"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label for>Fecha Emision</label>
                <datepicker :format="format"  :language="es" @keyup.enter="$refs.moneda.focus" @selected="onDateSelect" ref="fecha" v-model="fechax"></datepicker>
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.fecha_emision"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

              <div class="col-sm-2">
                  <div class="form-group">
                      <label for>Tasa de Cambio</label>
                      <input class="form-control" readonly type="text" v-model="tasa_cambio.tasa">
                      <ul class="error-messages">
                          <li
                                  :key="message"
                                  v-for="message in errorMessages.tasa_cambio"
                                  v-text="message"
                          ></li>
                      </ul>
                  </div>
              </div>


              <div class="col-sm-2">
                  <div class="form-group">
                      <label for="">Banco</label>
                      <v-select
                              :options="bancos"
                              label="descripcion"
                              ref="banco"
                              v-model="banco"
                              v-on:input="obtenerCuentasBancarias()"
                      ></v-select>
                      <ul class="error-messages">
                          <li :key="message" v-for="message in errorMessages.banco" v-text="message"></li>
                      </ul>
                  </div>
              </div>


              <div class="col-sm-2">
                  <div class="form-group">
                      <label for="">Cuenta bancaria</label>
                      <v-select
                              :on-change="monto_letras"
                              :options="cuentas_bancarias"
                              label="numero_cuenta"
                              v-model="form.cuenta_bancaria"
                      ></v-select>
                      <ul class="error-messages">
                          <li :key="message" v-for="message in errorMessages.cuenta_bancaria" v-text="message"></li>
                      </ul>
                  </div>
              </div>

          </div>
        <div class="row">
            <!--<div class="col-sm-2">
                <div class="form-group">
                    <label for="">Moneda</label>
                    <v-select
                            :disabled="true"
                            :options="monedas"
                            label="descripcion"
                            ref="moneda"
                            v-model="form.moneda"

                    ></v-select>
                    v-on:change="$refs.valor.focus()"
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.id_moneda" v-text="message"></li>
                    </ul>
                </div>
            </div>-->

            <div class="col-sm-3">
                <div class="form-group">
                    <label for=""> Monto Solicitado ({{moneda_solicitada}})</label>
                    <input class="form-control" readonly ref="valor" v-model="monto_org">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for=""> IR ({{moneda_solicitada}})</label>
                    <input @change="calcularTotales" class="form-control"  placeholder="IR del cheque"  type="number" v-model="form.monto_ir">
                    <ul class="error-messages">
                        <li :key="message" v-for="message in errorMessages.monto_ir" v-text="message"></li>
                    </ul>
                </div>
            </div>

            <div  class="col-sm-3" v-if="solicitud_original.id_moneda===3">
                <div class="form-group">
                    <label for=""> Monto Córdobas</label>
                    <input class="form-control" readonly v-model="monto_cord">
                </div>
            </div>

            <div class="col-sm-3" v-if="solicitud_original.id_moneda===3">
                <div class="form-group">
                    <label for=""> IR Córdobas</label>
                    <input class="form-control" placeholder="IR del cheque"  readonly   v-model="monto_ir_cord">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for=""> Monto Neto del Cheque (<strong>{{moneda_aprobada}}</strong>)</label>
                    <input class="form-control" placeholder="IR del cheque"  readonly   v-model="form.monto_neto">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Valor en letras</label>
                    <input class="form-control" placeholder="IR del cheque"  readonly   v-model="form.monto_letras">
                </div>
            </div>



 <div class="col-sm-5">
              <div class="form-group">
                <label for>Concepto</label>
                <input  @keyup.enter="$refs.sucursal.$refs.search.focus" class="form-control" ref="concepto" type="text" v-model="form.concepto">
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.concepto"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>
            </div>

          <div v-if="!form.tipoDocumento">
                    <div class="alert alert-info">
                        <span>Se requiere que seleccione un tipo de documento para continuar.</span>
                    </div>
                    <hr>
                </div>

            <br>

        <div class="row">
                     <div class="col-sm-2">
              <div class="form-group">
                <label for>Sucursal</label>
                <v-select
                  :options="sucursales"
                  label="descripcion"              
                  ref="sucursal"
                           v-model="detalleForm.sucursal"

                  v-on:change="sucursalEvento()"
                        ></v-select>
                  <!--v-on:change="$refs.concepto_mov.focus()"-->
                         <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.sucursalx"
                    v-text="message"
                  ></li>
                   </ul>
           </div> 
            </div>

  <div class="col-sm-3">
              <div class="form-group">
                 <label for>Cuenta Contable</label>
                 	<typeahead  :initial="detalleForm.cuentaContable"
                                :trim="80"
                   :url="cuentasBusquedaURL" @input="seleccionarCuentaContable" @keyup.enter="$refs.concepto_mov.focus"
                   ref="cuenta_contable" style="width: 100%;"></typeahead>
                          <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.cuentaContablex"
                    v-text="message"
                  ></li>
                   </ul>
        </div> 
       </div>


         <div class="col-sm-2">
              <div class="form-group">
                 <label for>Concepto</label>
             <input @keyup.enter="revisarConceptoMov" class="form-control" ref="concepto_mov" type="text" v-model="detalleForm.concepto">
               <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.conceptox"
                    v-text="message"
                  ></li>
                   </ul>
        </div> 
       </div>


         <div class="col-sm-2">
              <div class="form-group">
                 <label for>Debe</label>
               <input @focus="$event.target.select()" @keyup.enter="$refs.haber.focus" class="form-control" ref="debe" type="number" v-model="detalleForm.debe">
          <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.debex"
                    v-text="message"
                  ></li>
                   </ul>
        </div> 
       </div>



         <div class="col-sm-2">
              <div class="form-group">
                 <label for>Haber</label>
                <input @focus="$event.target.select()" @keyup.enter="$refs.agregar.focus" class="form-control" ref="haber" type="number" v-model="detalleForm.haber">
              <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.haberx"
                    v-text="message"
                  ></li>
                   </ul>
        </div> 
       </div>


         <div class="col-sm-1">
              <div class="form-group">
                 <label for>&nbsp;</label>
                <button @click="agregarDetalle"  @keyup.enter="eventoAgregar" class="btn btn-info" ref="agregar">Agregar Línea</button>
        </div> 
       </div>
            
            </div>


          <div class="row">
            <div class="col-sm-12">
               <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.detalleMovimientos"
                    v-text="message"
                  ></li>
                </ul>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                     <th>Sucursal</th>
                    <th>Cuenta Contable</th>
                    <th>Concepto</th>
                    <th>Debe</th>
                    <th>Haber</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(item, index) in form.detalleMovimientos">
                    <tr>
                    <td style="width: 20%">
                        <v-select
                           :options="sucursales"
                  label="descripcion"              
                  v-model="item.sucursal"
                        ></v-select>
                          <ul class="error-messages">
                          <li
                         :key="message"
                        v-for="message in errorMessages[`detalleMovimientos.${index}.sucursal.id_sucursal`]"
                         v-text="message">
                         </li>
                          </ul>
                      </td>


                      <td style="width: 25%">
                         <input class="form-control" disabled type="text" v-model="item.cuentaContable.nombre_cuenta_completo">
                        <!--<v-select disabled
                          v-model="item.cuentaContable"
                          label="nombre_cuenta_completo"
                          
                          :options="cuentas_contables"
                        ></v-select>-->
                          <ul class="error-messages">
                          <li
                         :key="message"
                        v-for="message in errorMessages[`detalleMovimientos.${index}.cuentaContable.id_cuenta_contable`]"
                         v-text="message">
                         </li>
                          </ul>
                      </td>
                      <td style="width: 21%">
                        <input class="form-control" type="text" v-model="item.concepto">
                        <ul class="error-messages">
                          <li
                         :key="message"
                        v-for="message in errorMessages[`detalleMovimientos.${index}.concepto`]"
                         v-text="message">
                         </li>
                          </ul>
                      </td>
                      <td style="width: 16%">
                        <input @change="item.partida_valida = ((item.debe == 0 &&  item.haber > 0)||(item.haber == 0 &&  item.debe > 0)) ? true : false;" class="form-control" disabled type="number" v-model="item.debe">
                          <ul class="error-messages">
                          <li
                         :key="message"
                        v-for="message in errorMessages[`detalleMovimientos.${index}.debe`]"
                         v-text="message">
                         </li>
                          </ul>
                      </td>

                      <td style="width: 16%">
                        <input @change="item.partida_valida = ((item.haber == 0 &&  item.debe > 0)||(item.debe == 0 &&  item.haber > 0)) ? true : false;" class="form-control" disabled type="number" v-model="item.haber">
                          <ul class="error-messages">
                          <li
                         :key="message"
                        v-for="message in errorMessages[`detalleMovimientos.${index}.haber`]"
                         v-text="message">
                         </li>
                          </ul>
                      </td>
                      <td style="width: 2%">
                        <button @click="eliminarLinea(item, index)">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    <tr></tr>
                  </template>
                </tbody>
                <tfoot>
                 <tr>
                    <td colspan="1" style="width: 15%">
                    </td>
                    <td class="item-footer" colspan="2" style="width: 30%">Totales</td>
                    <td class="item-footer" colspan="1" style="width: 20%">
                      <strong class="item-dark form-control">{{total_debe | formatMoney(2)}}</strong>       
                    </td>
                     <td class="item-footer" colspan="1" style="width: 20%">
                       <strong class="item-dark form-control">{{total_haber | formatMoney(2)}}</strong>       
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="content-box-footer text-right">
            <router-link :to="{ name: 'listado-solicitudes-pago' }" tag="button">
              <button class="btn btn-default" type="button">Cancelar</button>
            </router-link>
            <button :disabled="btnAction != 'Registrar' ? true : false"
              @click="revisarSolicitud"
              class="btn btn-primary"
              ref="registrar"
              type="button"
            >{{ btnAction }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
import tipo_documento from "../../api/tipos_documentos";
import sucursal from '../../api/sucursales'
import tc from '../../api/tasas-cambio'
import solicitud_pago from "../../api/solicitudes_pago";
//import Typeahead from '../layout/Typeahead'
//import Datepicker from "vuejs-datepicker";
import es from 'vuejs-datepicker/dist/locale/translations/es'
import monedas from "../../api/monedas";
import banco from '../../api/bancos'

export default {
  /*components: {
    //Datepicker,
    //	'typeahead':Typeahead
  },*/
  data() {
    return {
     	cuentasBusqueda: {},
		cuentasBusquedaURL: "/contabilidad/cuentas-contables/buscarf",
        contador : 0,
      es: es,
      format: "d MMMM yyyy",
     	monedas: [],
        banco: [],
      cuentas_bancarias: [],
      tipos_documentos: [],
      solicitud_original:[],
        moneda_solicitada:'',
        moneda_aprobada:'',
        tasa_cambio:[],
      sucursales: [],
      bancos: [],
        monto_org:0,
        monto_cord:0,
        monto_ir_cord:0,
      fechax:new Date(),
      form: {
          monto_neto:0,
          monto_ir:0,
        num_documento: "",
        fecha_emision: moment(new Date()).format("YYYY-MM-DD"),
        concepto: "",
        valor: 0,
        detalleMovimientos: [],
          cuenta_bancaria:[]
      },
      detalleForm:{
        sucursal: "",
        debe: 0,
        haber: 0,
        concepto: "",
        cuentaContable: {},
      },
      btnAction: "Registrar",
      errorMessages: []
    };
  },
    computed: {
                total_debe() {
                return this.form.detalleMovimientos.reduce((carry, item) => {
                    return (carry + (Number(item.debe) * 1))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                }, 0)
            },
               total_haber() {
                return this.form.detalleMovimientos.reduce((carry, item) => {
                    return (carry + (Number(item.haber) * 1))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                }, 0)
            },
    },
  methods: {
      moment: function () {
          return moment();
      },
      obtenerCuentasBancarias() {
          var self = this
          if(self.banco.cuentas_bancarias_banco)
              self.cuentas_bancarias = self.banco.cuentas_bancarias_banco
      },
       seleccionarCuentaContable(e) {
        const cuenta = e.target.value;
				var self = this;
				self.detalleForm.cuentaContable = cuenta;
           self.revisarConceptoMov();
            },

      /*monto_letras(){
          /*console.log(this.form.cuenta_bancaria.id_moneda);
          console.log(this.form.valor );
          console.log(this.form.monto_ir);*/
          //console.log(this.form.monto_neto);
         /* if(this.form.cuenta_bancaria.id_moneda && this.form.valor >0){
              this.form.monto_letras = numberasstring.numberasstring(this.form.monto_neto,this.form.cuenta_bancaria.moneda_cuenta_bancaria.descripcion_singular.toUpperCase(),this.form.cuenta_bancaria.moneda_cuenta_bancaria.descripcion.toUpperCase(),true);
          }else{
              this.form.monto_letras ='Seleccione un tipo de moneda';
          }
          return this.form.monto_letras;
      },*/

      calcularTotales() {
          var self = this;
                  // console.log(self.solicitud_original.id_moneda);
                  if(self.form.cuenta_bancaria.id_moneda ===3 && self.solicitud_original.id_moneda ===3){
                      self.monto_cord = Number(self.monto_org * self.tasa_cambio.tasa).toFixed(2);
                      self.monto_ir_cord = Number(self.form.monto_ir * self.tasa_cambio.tasa).toFixed(2);
                      self.form.monto_neto = Number(self.form.valor  - self.form.monto_ir).toFixed(2);
                  }else{
                      self.monto_cord = self.monto_org;
                      self.monto_ir_cord = self.form.monto_ir;
                      self.form.monto_neto = Number(self.monto_cord - self.monto_ir_cord).toFixed(2);
                  }
                  self.form.valor = Number(self.monto_cord);
                  self.monto_letras()
      },

    onDateSelect(date) {
        var self = this;
        self.form.fecha_emision = moment(date).format("YYYY-MM-DD");
        tc.obtenerTC({
           fecha: self.form.fecha_emision
        }, data => {
                if(data.tasa !== null){
                    self.tasa_cambio = data;
                   // console.log(self.solicitud_original.id_moneda);
                    if(self.solicitud_original.id_moneda ===3){
                        self.monto_cord = Number(self.monto_org * self.tasa_cambio.tasa).toFixed(2);
                        self.monto_ir_cord = Number(self.form.monto_ir * self.tasa_cambio.tasa).toFixed(2);
                        self.form.monto_neto = Number(self.monto_org  - self.form.monto_ir).toFixed(2);
                    }else{
                        self.monto_cord = Number(self.monto_org);
                        self.monto_ir_cord = Number(self.form.monto_ir);
                        self.form.monto_neto = Number(self.monto_cord - self.monto_ir_cord).toFixed(2);
                    }
                    self.form.valor = Number(self.monto_cord);
                    self.monto_letras()
                }else{
                    alertify.warning("No se encuentra tasa de cambio para esta fecha.",5);
                }
        }, err => {
            if(err.fecha[0]){
            alertify.warning(err.fecha[0],5);
            }
        })

    },
      obtenerTC(){
          var self = this;
          tc.obtenerTC({
              fecha: self.form.fecha_emision
          }, data => {
              if(data.tasa !== null){
                  self.tasa_cambio = data;

                  if(self.solicitud_original.id_moneda ===3){
                     //console.log(self.form.monto_ir);
                      /* console.log(self.tasa_cambio.tasa);*/
                      self.monto_cord = Number(self.monto_org * self.tasa_cambio.tasa).toFixed(2);
                      self.monto_ir_cord = Number(self.form.monto_ir * self.tasa_cambio.tasa).toFixed(2);
                      self.form.monto_neto = Number(self.monto_cord  - self.monto_ir_cord).toFixed(2);

                  }else{
                      self.monto_cord = Number(self.monto_org);
                      self.monto_ir_cord = Number(self.form.monto_ir);
                      self.form.monto_neto = Number(self.monto_org - self.form.monto_ir).toFixed(2);
                  }
                  self.form.valor = Number(self.monto_cord);
                  self.monto_letras()

              }else{
                  alertify.warning("No se encuentra tasa de cambio para esta fecha.",5);
              }
          }, err => {
              console.log(err);
              if(err.fecha.length>0){
              alertify.warning(err.fecha[0],5);
              }
          })
      }
      ,
      obtenerSolicitud() {
          var self = this
          solicitud_pago.obtenerSolicitudPago({
              id_solicitud_pago: this.$route.params.id_solicitud_pago
          }, data => {
              if(data.estado === 1){
              if(data.documento_solicitud === null){
              //console.log(data);
                  self.solicitud_original = data.solicitud;
              //self.form.moneda = data.moneda_solicitud;
              self.form.concepto = data.solicitud.concepto;
              self.detalleForm.concepto = data.solicitud.concepto;
              self.form.valor = 0;
              self.form.id_solicitud_pago = this.$route.params.id_solicitud_pago;
              self.monto_org=self.solicitud_original.monto_solicitado;
              self.moneda_solicitada = self.solicitud_original.moneda_solicitud.descripcion;
              }else{
                  alertify.warning("Esta solicitud ya tiene un documento contable registrado.",5);
                  this.$router.push({
                      name: "listado-solicitudes-pago"
                  });
              }
              }else{
                  alertify.warning("Esta solicitud ya fue revisada anteriormente",5);
                  this.$router.push({
                      name: "listado-solicitudes-pago"
                  });
              }
              //self.form = data
          }, err => {
              console.log(err)
          })
      },

    		   obtenerMonedas() {
      var self = this;
      monedas.cargarMonedas(
        data => {
          self.monedas = data;
            self.form.moneda = self.monedas[0];
        },
        err => {
          console.log(err);
        }
      );
    },
    obtenerTiposDocumentosTodos() {
      var self = this;
      tipo_documento.obtenerTodos(
        data => {
          self.tipos_documentos = data;
          //console.log();
          self.form.tipoDocumento =self.tipos_documentos[3];
          self.obtenerNumeroDocumento()
        },
        err => {
          console.log(err);
        }
      );
    },
    obtenerSucursalesTodas() {
      var self = this;
      sucursal.obtenerTodos(
        data => {
          self.sucursales = data;
        },
        err => {
          console.log(err);
        }
      );
    },
    
     agregarDetalle() {
      
        if(this.detalleForm.sucursal){
         //  this.errorMessages.sucursalx = ['correcto']
          if(this.detalleForm.cuentaContable){
            if(this.detalleForm.debe > 0 || this.detalleForm.haber > 0){
               if(this.detalleForm.debe > 0 && this.detalleForm.haber > 0){
                 alertify.warning("No se puede agregar monto en ambos campos",5);
                  }else{
            this.form.detalleMovimientos.push({
            cuentaContable: this.detalleForm.cuentaContable,
            sucursal: this.detalleForm.sucursal,
            debe: this.detalleForm.debe*1,
            haber: this.detalleForm.haber*1,
            concepto: this.detalleForm.concepto,
          });
            this.detalleForm.cuentaContable={};
           // this.detalleForm.sucursal=''
            this.detalleForm.debe=0;
            this.detalleForm.haber=0;
           // this.detalleForm.concepto='';
            }
            }
      else{
        alertify.warning("El monto debe ser mayor que cero",5);
      }
          
      }else{
        alertify.warning("Debe seleccionar una cuenta contable",5);
      }

      }else{
        alertify.warning("Debe seleccionar una sucursal",5);
      }
    },

    eliminarLinea(item, index) {
      if (this.form.detalleMovimientos.length >= 1) {
      this.form.detalleMovimientos.splice(index, 1);
      
      }else{
        this.form.detalleMovimientos=[];
      }
    },

      obtenerNumeroDocumento() {
          var self = this;
          if(self.form.tipoDocumento){
              self.form.num_documento = self.form.tipoDocumento.prefijo +'-'+ self.form.tipoDocumento.secuencia;
          }

    },

      cambiarFoco() {
          var self = this;
           if(self.contador > 0){
           self.$refs.moneda.$refs.search.focus();
           }
          self.contador ++;
      },

      revisarConceptoMov() {
          var self = this;
          if(self.detalleForm.concepto !== ''){
              self.$refs.debe.focus();
          }else{
              self.$refs.concepto_mov.focus();
          }
      },

      obtenerTodosBancos(){
          var self = this;
          banco.obtenerTodosBancos(
              data => {
                  self.bancos = data;
              },
              err => {
                  console.log(err);
              }
          );

      },


      sucursalEvento() {
          var self = this;
          if(self.contador > 0){
          self.$refs.cuenta_contable.open();
          }
          //console.log(self.$refs.cuenta_contable);
      },

      eventoAgregar() {
          var self = this;
          if(self.detalleForm.sucursal !==''){
              self.$refs.cuenta_contable.open();
          }else{
              self. $refs.sucursal.$refs.search.focus();
          }
          //console.log(self.$refs.cuenta_contable);
      },



    revisarSolicitud() {
      var self = this;
      if((self.total_debe>0 && self.total_haber>0) && (self.total_debe===self.total_haber)){
          if(self.total_debe === self.form.valor){
      self.btnAction = "Registrando, espere....";
          solicitud_pago.revisar(
        self.form,
        data => {
          alertify.success("Documento registrado correctamente",5);
          this.$router.push({
            name: "listado-solicitudes-pago"
          });
        },
        err => {
          self.errorMessages = err;
          alertify.warning("Por favor Revisar los datos faltantes",5);
          self.btnAction = "Registrar";
        }
      );
          }else{
              alertify.warning("La sumatoria de las columnas debe y haber tienen que coincidir con el monto original de la solicitud de pago.",5);
          }
      }

      else{
          alertify.warning("La sumatoria de las columnas debe y haber tienen que ser iguales.",5);
      }
  }
  },
  mounted() {
    this.obtenerTiposDocumentosTodos() ;
    this.obtenerSucursalesTodas();
    this.obtenerMonedas();
    this.obtenerTodosBancos();
    this.obtenerSolicitud();
    this.obtenerTC();
    //this.$refs.banco.$refs.search.focus();
  }
};
</script>
