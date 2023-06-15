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
                <template v-if="form.proveedor">
                  <input class="form-control" type="text"  disabled v-model="form.proveedor.numero_ruc">
                </template>
              </div>
            </div>

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
                <label for>Solicitud de Compra:</label>
                <input class="form-control" type="text" disabled v-model="form.id_solicitud_compra">
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.id_solicitud_compra"
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
                          v-for="message in errorMessages.f_orden_compra"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-4">
              <div class="form-group">
                <label for>Bodega</label>
                <v-select
                        label="descripcion"
                        v-model="form.bodega"
                        :options="bodegas"
                ></v-select>
                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.solicitud_bodega" v-text="message"></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Moneda</label>
                <v-select
                        :disabled="true"
                        label="descripcion"
                        v-model="form.moneda"
                        :options="monedas"
                ></v-select>
                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.solicitud_moneda" v-text="message"></li>
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
                <label for=""> Medio de pago:</label>
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
                <label for>Producto</label>

                <typeahead style="width: 100%;" :initial="detalleForm.productox" :trim="80" :url="productosBusquedaURL" @input="seleccionarProducto"></typeahead>
                <ul class="error-messages">
                  <li
                          :key="message"
                          v-for="message in errorMessages.productox"
                          v-text="message"
                  ></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-3">
              <div class="form-group">
                <label for>Cantidad</label>
                <input class="form-control" ref="cantidad" type="number" v-model.number="detalleForm.cantidad">
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
                  <input class="form-control" type="number" v-model.number="detalleForm.precio">
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
                  <th >Producto</th>
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
                    <td>
                      <input class="form-control" disabled v-model="item.productox.descripcion">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.productox.id_producto`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>

                    <td>
                      <input  class="form-control" type="number" v-model.number="item.cantidad">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.cantidad`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>


                    <td>
                      <input  class="form-control" type="number" v-model.number="item.precio">
                      <ul class="error-messages">
                        <li
                                :key="message"
                                v-for="message in errorMessages[`detalleProductos.${index}.precio`]"
                                v-text="message">
                        </li>
                      </ul>
                    </td>


                    <td>
                      <input  class="form-control" type="number" v-model.number="item.descuento">
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
                  <td colspan="4"></td>
                  <td>Subtotal</td>
                  <td> <strong>{{form.moneda ? form.moneda.codigo : ''}} {{total_subt | formatMoney(2)}}</strong></td>
                </tr>
                <tr>
                  <td colspan="4"></td>
                  <td>Descuento</td>
                  <td> <strong>{{form.moneda ? form.moneda.codigo : ''}} {{total_descuento | formatMoney(2)}}</strong></td>
                </tr>
                <tr>
                  <td colspan="3"></td>
                  <td>I.V.A.</td>
                  <td><input class="form-control" min="0" max="99" v-model.number="form.porcentaje_iva"></td>
                  <td> <strong>{{form.moneda ? form.moneda.codigo : ''}} {{total_impuesto | formatMoney(2)}}</strong></td>
                </tr>
                <tr>
                  <td class="item-footer" colspan="2"> Total Unidades</td>
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
            <router-link tag="button" :to="{ name: 'listado-ordenes-compras' }">
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
  import orden from "../../api/orden_compras";
  import es from 'vuejs-datepicker/dist/locale/translations/es'


  export default {
    /*components: {
      Datepicker
    },*/
    data() {
      return {
        productosBusqueda: {},
        productosBusquedaURL: "/inventario/productos/buscarPS",
        es: es,
        format: "dd-MM-yyyy",
        proveedores: [],
        solicitud_original:'',
        monedas:[],
        bodegas:[],

        detalleForm:{
          productox: {},
          cantidad: 1,
          monto_descuento:0,
          precio: 0,
          subtotal: 0,
          total: 0
        },

        form: {
          observacion: "",
          f_orden_compra: moment(new Date()).format("YYYY-MM-DD"),
          proveedor: "",
          referencia_solicitud: "",
          telefono_proveedor: "",
          email_proveedor: "",
          atencion: "",
          area_proveedor: "",
          total: 0,
          iva:0,
          porcentaje_iva:15,
          direccion:"",
          condicion_envio: "Envío",
          nota: "",
          tiempo_entrega: 15,
          id_condicion_pago: 1,
          plazo_credito:0,
          id_medio_pago: 4,
          tipo_compra:2,
          detalleProductos: [],
          es_borrador:false,
          id_solicitud_compra: '',
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
      total_impuesto() {
        this.form.iva= Number(this.form.detalleProductos.reduce((carry, item) => {
                  return(carry + Number(Number(item.subtotal*(this.form.porcentaje_iva/100)).toFixed(2)));
                }
                , 0).toFixed(2))

        return this.form.iva;
      },
      gran_total() {

        this.form.total =  this.form.detalleProductos.reduce((carry, item) => {
                  return (carry + Number(item.total.toFixed(2)));
                }
                , 0)

        return Number(this.form.total+this.form.iva);
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
      seleccionarProducto(e) {
        const productoP = e.target.value;
        var self = this;
        self.detalleForm.productox = productoP;

        if(self.detalleForm.productox)
          self.detalleForm.precio = self.detalleForm.productox.costo_estandar;

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
        this.form.f_orden_compra = moment(date).format("YYYY-MM-DD"); //
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
        if(this.detalleForm.productox){
          if(this.detalleForm.cantidad>0 && this.detalleForm.precio > 0){

            let i = 0;
            let keyx = 0;
            if(self.form.detalleProductos){
              self.form.detalleProductos.forEach((productox, key) => {
                //console.log('ID_PRODUCTO ',self.detalleForm.productox.id_producto);
                if(self.detalleForm.productox.id_producto===productox.productox.id_producto){
                  i++;
                  keyx = key;
                }
              });
            }

            if(i === 0){
              this.detalleForm.productox.descripcion = this.detalleForm.productox.text;
              this.form.detalleProductos.push({
                productox: this.detalleForm.productox,
                cantidad: this.detalleForm.cantidad,
                precio: this.detalleForm.precio,
                descuento: 0,
                monto_descuento:Number(0),
                subtotal: 0,
                total: 0,
              });
              this.detalleForm.productox={};
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
                  this.detalleForm.productox={};
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

      cargarDatosSolicitud() {
        var self = this;
        orden.nueva({
          id_solicitud_compra: this.$route.params.id_solicitud_compra
        }, data => {

          self.solicitud_original= data.solicitud;

          if(self.solicitud_original.estado !== 3 && self.solicitud_original.estado !== 4){
            alertify.error("La solicitud ya no puede ser procesada",5);
            this.$router.push({
              name: "listado-ordenes-compras"
            });
          }else{
            self.monedas = data.monedas;
            self.bodegas = data.bodegas;
            self.proveedores= data.proveedores;
            self.form.proveedor= self.proveedores[0];
            self.form.id_solicitud_compra = self.solicitud_original.id_solicitud_compra;
            self.form.bodega = self.solicitud_original.solicitud_bodega;
            self.form.iva = self.solicitud_original.iva;
            self.form.porcentaje_iva = self.solicitud_original.porcentaje_iva;
            self.form.moneda = self.solicitud_original.solicitud_moneda;
            self.cargar_detalles_proveedor();

            if(self.solicitud_original.solicitud_productos){
              self.solicitud_original.solicitud_productos.forEach((productox, key) => {
                this.form.detalleProductos.push({
                  productox: productox.solicitud_producto,
                  cantidad: Number(productox.cantidad_cotizado),
                  precio: productox.precio_cotizado,
                  descuento: productox.descuento,
                  monto_descuento:Number(0),
                  subtotal: 0,
                  total: 0,
                });
              });
            }
          }

        })

      },

      registrar() {
        var self = this;
        self.btnAction = "Registrando, espere....";
        self.btnActionDraft = "Guardando, espere....";


          if (self.form.es_borrador === false) {
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
                          alertify.success("Orden de Compra emitida correctamente", 5);
                          this.$router.push({
                            name: "listado-ordenes-compras"
                          });
                        },
                        err => {
                          self.errorMessages = err;
                          self.btnAction = "Registrar";
                          self.btnActionDraft = "Guardar Borrador";
                        }
                );
              }
            })
          } else {
            orden.registrar(
                    self.form,
                    data => {
                      alertify.success("Orden de Compra emitida correctamente", 5);
                      this.$router.push({
                        name: "listado-ordenes-compras"
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
      this.cargarDatosSolicitud();
    }
  };
</script>
<style>
  .btn-agregar {
    margin-top:33px;
  }
</style>
