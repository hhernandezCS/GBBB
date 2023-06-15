<template>
  <div class="main">
    <div class="row">
      <div class="col-md-12">
        <div class="content-box">
          <div class="content-box-header">
            <div class="box-title">Crear Orden de Compra</div>
            <div class="box-description">Formulario para registrar Orden de compra</div>
          </div>
          <div class="row">

            <div class="col-sm-4">
              <div class="form-group">
                <label for=""> Tipo Orden Compra:</label>
                <select class="form-control" v-model.number="form.tipo_compra">
                  <option value="1">Nacional</option>
                  <option value="2">Internacional</option>
                </select>
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.tipo_compra"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Proveedor</label>
                <v-select
                        label="nombre_comercial"
                        v-model="form.proveedor"
                        :options="proveedores"
                        v-on:input="cargar_detalles_proveedor()"
                ></v-select>
                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.proveedor" v-text="message"></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>RUC Proveedor</label>
                <input class="form-control" disabled type="text" v-model="form.numero_ruc_proveedor">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.numero_ruc_proveedor"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Moneda</label>
                <v-select
                        label="descripcion"
                        v-model="form.moneda"
                        :options="monedas"
                ></v-select>
                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.moneda" v-text="message"></li>
                </ul>
              </div>
            </div>
            <!--<td>
              <input class="form-control" disabled v-model="item.no_factura_proveedor">
              <ul class="error-messages">
                <li
                        :key="message"
                        v-for="message in errorMessages[`detallOrdenesCompras.${index}.no_factura_proveedor`]"
                        v-text="message">
                </li>
              </ul>
            </td>-->

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Atención:</label>
                <input class="form-control" type="text" v-model="form.atencion">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.atencion"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Email Proveedor:</label>
                <input class="form-control" type="text" v-model="form.email_proveedor">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.email_proveedor"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-4">
              <div class="form-group">
                <label for>Télefono Proveedor:</label>
                <input class="form-control" type="text" v-model="form.telefono_proveedor">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.telefono_proveedor"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-4">
              <div class="form-group">
                <label for>Referencia:</label>
                <input class="form-control" type="text" v-model="form.referencia_solicitud">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.referencia_solicitud"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Area / Departamento:</label>
                <input class="form-control" type="text" v-model="form.area_proveedor">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.area_proveedor"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Fecha Orden Compra</label>
                <datepicker :format="format" :language="es" :value="new Date()" @selected="onDateSelect"></datepicker>
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.f_orden_servicio"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

          </div>

          <div class="alert alert-success">
            <span><strong>DETALLES CONSOLIDADOR</strong></span>
          </div>

          <div class="row">

            <div class="col-sm-12">
              <div class="form-group">
                <label for>Dirección</label>
                <input class="form-control" type="text" v-model="form.direccion">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.direccion"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label for=""> Tipo Pago:</label>
                <select @change="cambiarTipoPago" class="form-control" v-model.number="form.id_condicion_pago">
                  <option value="1">Contado</option>
                  <option value="2">Crédito</option>
                </select>
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.id_condicion_pago"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label for=""> Días Crédito</label>
                <select class="form-control" v-model.number="form.plazo_credito">
                  <option :disabled="form.id_condicion_pago!==1" value=0>Contado</option>
                  <option :disabled="form.id_condicion_pago===1" value=8>Trámite de Cheque (8 días)</option>
                  <option :disabled="form.id_condicion_pago===1" value=15>Crédito (15 días)</option>
                  <option :disabled="form.id_condicion_pago===1" value=30>Crédito (30 días)</option>
                  <option :disabled="form.id_condicion_pago===1" value=45>Crédito (45 días)</option>
                </select>

                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.plazo_credito" v-text="message"></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-3">
              <div class="form-group">
                <label for=""> Tipo:</label>
                <select class="form-control" v-model.number="form.id_medio_pago">
                  <option value="1">Crédito</option>
                  <option value="2">Transferencia</option>
                  <option value="3">Cheque</option>
                  <option value="4">Minuta Depósito</option>
                  <option value="5">Efectivo</option>
                  <option value="6">Tarjeta</option>
                </select>
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.id_medio_pago"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label for>Condición envío</label>
                <select class="form-control" v-model.number="form.condicion_envio">
                  <option value="Envío">Envío</option>
                  <option value="Retiro en tienda"> Retiro en tienda</option>
                </select>
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.condicion_envio"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-3">
              <div class="form-group">
                <label for>Tiempo de Entrega</label>
                <input class="form-control" type="text" v-model.number="form.tiempo_entrega">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.tiempo_entrega"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-9">
              <div class="form-group">
                <label for>Nota</label>
                <input class="form-control" type="text" v-model="form.nota">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.nota"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

          </div>

          <div v-if="!form.proveedor">
            <div class="alert alert-info">
              <span>Se requiere que seleccione una proveedor para continuar.</span>
            </div>
            <hr>
          </div>

          <div class="alert alert-success">
            <span><strong>Detalle de productos</strong></span>
          </div>
          <div class="row">

            <div class="col-sm-3">
              <div class="form-group">
                <label for>Clasificación Compra</label>
                <v-select
                        v-model="detalleForm.clasificacionx"
                        label="descripcion"
                        :options="clasificaciones"
                ></v-select>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label for>Descripción producto | servicio</label>
                <input class="form-control" ref="producto" type="text" v-model="detalleForm.descripcion">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.descripcion"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-3">
              <div class="form-group">
                <label for>Cantidad</label>
                <input class="form-control" ref="cantidad" type="number" min="0" v-model.number="detalleForm.cantidad">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.cantidadx"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label for>Precio</label>
                <div class="input-group">
                  <div class="input-group-addon">{{form.moneda ? form.moneda.codigo : ''}}
                  </div>
                  <input class="form-control" type="number" min="0" v-model.number="detalleForm.precio">
                </div>

                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.preciox"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label for>&nbsp;</label>
                <button @click="agregarDetalle" class="btn btn-info btn-agregar" ref="agregar">Agregar Producto</button>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-sm-12">
              <ul class="error-messages">
                <li
                        :key="message"
                        v-for="message in errorMessages.detalleProductos"
                        v-text="message"
                ></li>
              </ul>
              <table class="table table-bordered"  >
                <thead>
                <tr>
                  <th></th>
                  <th >Clasificación Compra</th>
                  <th >Descripción Artículo | Servicio</th>
                  <th >Cantidad</th>
                  <th >Precio Unitario</th>
                  <th >Descuento</th>
                  <th >SubTotal</th>


                </tr>
                </thead>
                <tbody>
                <template v-for="(item, index) in form.detalleProductos">
                  <tr>
                    <td style="width: 2%">
                      <button @click="eliminarLinea(item, index)">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>

                    <td style="width: 12%">
                      <v-select
                              v-model="item.clasificacionx"
                              label="descripcion"
                              :options="clasificaciones"
                      ></v-select>
                      <ul class="error-messages">
                        <li
                                v-for="message in errorMessages[`detalleProductos.${index}.clasificacionx.id_clasificacion_compra`]"
                                :key="message"
                                v-text="message">
                        </li>
                      </ul>
                    </td>

                    <td>
                      <input class="form-control" disabled v-model="item.descripcion">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.descripcion`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>

                    <td>
                      <input  class="form-control" type="number" min="0" v-model.number="item.cantidad">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.cantidad`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>


                    <td>
                      <input  class="form-control" type="number" min="0" v-model.number="item.precio">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.precio`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>


                    <td>
                      <input  class="form-control" type="number" min="0" max="99" v-model.number="item.descuento">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.descuento`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>

                    <td>
                      <strong>{{form.moneda ? form.moneda.codigo : ''}} {{sub_total(item)| formatMoney(2)}}</strong>
                    </td>
                  </tr>
                  <tr></tr>
                </template>
                </tbody>
                <tfoot>

                <tr>
                  <td colspan="5"></td>
                  <td>Subtotal  </td>
                  <td> <strong>{{form.moneda?form.moneda.codigo:'N/D'}} {{total_subt | formatMoney(2)}}</strong></td>
                </tr>
                <tr>
                  <td colspan="5"></td>
                  <td>Descuento</td>
                  <td> <strong>{{form.moneda ? form.moneda.codigo : ''}} {{total_descuento | formatMoney(2)}}</strong></td>
                </tr>
                <tr>
                  <td colspan="4"></td>
                  <td>I.V.A.</td>
                  <td><input class="form-control" min="0" max="99" v-model.number="form.porcentaje_iva"></td>
                  <td> <strong>{{form.moneda ? form.moneda.codigo : ''}} {{calcular_impuesto | formatMoney(2)}}</strong></td>
                </tr>
                <tr>
                  <td class="item-footer" colspan="3"> Total Unidades</td>
                  <td  class="item-footer">
                    <strong>{{total_cantidad}}</strong>
                  </td>
                  <td></td>
                  <td>Total</td>
                  <td> <strong>{{form.moneda ? form.moneda.codigo : ''}} {{gran_total | formatMoney(2)}}</strong></td>
                </tr>

                </tfoot>
              </table>
            </div>
          </div>

          <div class="content-box-footer text-right">
            <router-link tag="button" :to="{ name: 'listado-ordenes-compras-ps' }">
              <button type="button" class="btn btn-default">Cancelar</button>
            </router-link>
            <button
                    :disabled="btnActionDraft !== 'Guardar Borrador'"
                    @click="form.es_borrador=true;registrar()"
                    class="btn btn-dark"
                    type="button"
            >{{ btnActionDraft }}</button>
            <button
                    :disabled="btnAction !== 'Registrar'"
                    @click="form.es_borrador=false;registrar()"
                    class="btn btn-primary"
                    type="button"
            >{{ btnAction }}</button>
          </div>


        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
  import orden from "../../api/orden_compras_ps";
  import es from 'vuejs-datepicker/dist/locale/translations/es'


  export default {
    /*components: {
      Datepicker
    },*/
    data() {
      return {
        es: es,
        format: "dd-MM-yyyy",
        proveedores: [],
        monedas:[],
        clasificaciones:[],

        detalleForm:{
          productox: '',
          cantidad: 1,
          monto_descuento:0,
          precio: 0,
          subtotal: 0,
          total: 0
        },

        form: {
          observacion: "",
          f_orden_servicio: moment(new Date()).format("YYYY-MM-DD"),
          proveedor: "",
          referencia_solicitud: "",
          telefono_proveedor: "",
          email_proveedor: "",
          atencion: "",
          area_proveedor: "",
          moneda:[],
          direccion:"",
          condicion_envio: "Envío",
          nota: "",
          tiempo_entrega: 15,
          porcentaje_iva:15,
          id_condicion_pago: 1,
          plazo_credito:0,
          id_medio_pago: 4,
          tipo_compra:1,
          iva:0,
          total: 0,
          detalleProductos: [],
          es_borrador:false
        },
        btnAction: "Registrar",
        btnActionDraft: "Guardar Borrador",
        errorMessages: []
      };
    },
    computed: {

      total_cantidad() {
        return this.form.detalleProductos.reduce((carry, item) => {
          return (carry + Number(item.cantidad))
        }, 0)
      },
      total_subt() {
        return this.form.detalleProductos.reduce((carry, item) => {
                  return(carry + Number(item.subtotal.toFixed(2)));
                }
                , 0)
      },
      total_descuento() {
        return this.form.detalleProductos.reduce((carry, item) => {
                  return(carry + Number(Number(item.monto_descuento).toFixed(2)));
                }
                , 0)
      },

      calcular_impuesto() {
        this.form.iva = this.form.detalleProductos.reduce((carry, item) => {
                  return(carry + Number(Number(item.total*(Number(this.form.porcentaje_iva/100))).toFixed(2)));
                }
                , 0)

        return Number((this.form.iva).toFixed(2));
      },

      gran_total() {

        this.form.total =  this.form.detalleProductos.reduce((carry, item) => {
                  return (carry + Number(item.total.toFixed(2)));
                }
                , 0)

        return Number((this.form.total + this.form.iva).toFixed(2));
      },
    },
    methods: {
      cambiarTipoPago(){
        let self = this;
        if(self.form.id_condicion_pago === 1){
          self.form.plazo_credito = 0;
        }else{
          self.form.plazo_credito = 8;
        }
      },

      sub_total(itemX) {
        itemX.subtotal= Number((Number(itemX.precio)* Number(itemX.cantidad)).toFixed(2));
        itemX.monto_descuento= Number(Number(itemX.subtotal* Number(itemX.descuento/100)).toFixed(2));
        itemX.total =itemX.subtotal - itemX.monto_descuento;
        if(!isNaN(itemX.total)){
          return itemX.total;
        }
        else return 0;
      },

      onDateSelect(date) {
        //console.log(date); //
        this.form.f_orden_servicio = moment(date).format("YYYY-MM-DD"); //
      },

      cargar_detalles_proveedor() {
        var self = this
        if(self.form.proveedor)

        self.form.telefono_proveedor = self.form.proveedor.telefono_contacto;
        self.form.email_proveedor = self.form.proveedor.correo_contacto;
        self.form.atencion =self.form.proveedor.contacto_proveedor;
        self.form.numero_ruc_proveedor =self.form.proveedor.numero_ruc;
        self.form.direccion_proveedor =self.form.proveedor.direccion;

      },
      agregarDetalle() {
        let self = this;
        if(this.detalleForm.descripcion){
          if(this.detalleForm.cantidad>0 && this.detalleForm.precio > 0){

            let i = 0;
            let keyx = 0;
            if(self.form.detalleProductos){
              self.form.detalleProductos.forEach((productox, key) => {
                //console.log('ID_PRODUCTO ',self.detalleForm.productox.id_producto);
                if(self.detalleForm.descripcion===productox.descripcion){
                  i++;
                  keyx = key;
                }
              });
            }

            if(i === 0){
              this.form.detalleProductos.push({
                descripcion: this.detalleForm.descripcion,
                cantidad: this.detalleForm.cantidad,
                precio: this.detalleForm.precio,
                clasificacionx: this.detalleForm.clasificacionx,
                descuento: 0,
                monto_descuento:Number(0),
                subtotal: 0,
                total: 0,
              });
              this.detalleForm.descripcion='';
              this.detalleForm.clasificacionx='';
              this.detalleForm.cantidad=0;
              this.detalleForm.precio=0;
              this.detalleForm.subtotal=0;
              this.detalleForm.monto_descuento=0;
              this.detalleForm.descuento=0;
              this.detalleForm.total=0;

            }else{
              this.$swal.fire({
                title: 'El producto ya existe en el detalle, desea sumar la nueva cantidad al monto existente?',
                text: "También se sobreescribirá el costo unitario",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, confirmar',
                cancelButtonText:'Cancelar'
              }).then((result) => {
                if (result.value) {
                  self.form.detalleProductos[keyx].cantidad = Number(self.form.detalleProductos[keyx].cantidad) + self.detalleForm.cantidad;
                  self.form.detalleProductos[keyx].precio = self.detalleForm.precio;
                  this.detalleForm.descripcion='';
                  this.detalleForm.cantidad=0;
                  this.detalleForm.precio=0;
                  this.detalleForm.subtotal=0;
                  this.detalleForm.monto_descuento=0;
                  this.detalleForm.descuento=0;
                  this.detalleForm.total=0;
                }
              })
            }
          }else{
            alertify.warning("Los valores para cantidad y precio unitario deben ser mayores que cero",5);
          }
        }else{
          alertify.warning("Debe seleccionar un producto",5);
        }
      },
      eliminarLinea(item, index) {
        if (this.form.detalleProductos.length >= 1) {
          this.form.detalleProductos.splice(index, 1);

        }else{
          this.form.detalleProductos=[];
        }
      },


    nueva() {
        var self = this;
        orden.nueva({
        }, data => {
          self.monedas = data.monedas;
          self.form.moneda = self.monedas[0];
          self.clasificaciones= data.clasificaciones;
          self.proveedores= data.proveedores;
          self.form.proveedor= self.proveedores[0];
          self.cargar_detalles_proveedor();
        },
                err => {
                  console.log(err);
                })

      },

      registrar() {
        var self = this;
        self.btnAction = "Registrando, espere....";
        self.btnActionDraft = "Guardando, espere....";
      if(self.form.es_borrador ===false){
        self.$swal.fire({
          title: 'Esta seguro de guardar y emitir la orden de compra?',
          text: "Esta acción no se puede deshacer",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, confirmar',
          cancelButtonText:'Cancelar'
        }).then((result) => {
          if (result.value) {
            orden.registrar(
                    self.form,
                    data => {
                      alertify.success("Orden de Compra emitida correctamente",5);
                      this.$router.push({
                        name: "listado-ordenes-compras-ps"
                      });
                    },
                    err => {
                      self.errorMessages = err;
                      self.btnAction = "Registrar";
                      self.btnActionDraft = "Guardar Borrador";
                    }
            );
          }else{
            self.btnAction = "Registrar";
            self.btnActionDraft = "Guardar Borrador";
          }
        })
      }else{
        orden.registrar(
                self.form,
                data => {
                  alertify.success("Orden de Compra emitida correctamente",5);
                  this.$router.push({
                    name: "listado-ordenes-compras-ps"
                  });
                },
                err => {
                  self.errorMessages = err;
                  self.btnAction = "Registrar";
                  self.btnActionDraft = "Guardar Borrador";
                }
        );
      }

      }
    },
    mounted() {
      this.nueva();
    }
  };
</script>
<style>
  .btn-agregar {
    margin-top:33px;
  }
</style>
