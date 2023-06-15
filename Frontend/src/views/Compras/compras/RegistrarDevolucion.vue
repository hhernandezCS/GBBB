<!--mpdf v-on:input="cargarProductosBodega(form.id_bodega)" mpdf-->
<template>
  <div class="main">
    <div class="row">
      <div class="col-md-12">
        <div class="content-box">
          <div class="content-box-header">
            <div class="box-title">Registrar Nueva Devolución</div>
            <div class="box-description">Formulario para registrar Devolución de compra</div>
          </div>
          <div class="row">
             <div class="col-sm-4">
              <div class="form-group">
                <label for>Código Entrada</label>
               <input :disabled="btnActionBuscar != 'Buscar Entrada' ? true : false" class="form-control" type="text" v-model="form.entrada_original.codigo_entrada">
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.codigo_entrada_org"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

             <div class="col-sm-2">
              <div class="form-group" style="padding: 7px;">
               <br>
                <button :disabled="btnActionBuscar != 'Buscar Entrada' ? true : false" @click="obtenerEntradaPorCodigo(form.entrada_original.codigo_entrada)" class="btn btn-success">{{ btnActionBuscar }}</button>
                <button :disabled="btnActionBuscar != 'Buscar Entrada' ? false : true" @click="restaurarFormulario" class="btn btn-danger">X</button>
              
              </div>
            </div>
            </div>

          <div class="row">
            <div class="col-sm-12">
              <div v-if="!form.entrada_original.codigo_entrada">
                    <div class="alert alert-info">
                        <span>Se requiere que seleccione una entrada válida, para realizar la devolución.</span>
                    </div>
                    <hr>
                </div>
                </div>
          </div>
            

          <div class="row">
         <div class="col-sm-4">
              <div class="form-group">
                <label for>Bodega</label>
                <input class="form-control" disabled type="text" v-model="form.entrada_original.entrada_bodega.nombre">
                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.id_bodega" v-text="message"></li>
                </ul>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for>Proveedor</label>
                <input class="form-control" disabled type="text"  v-model="form.entrada_original.entrada_proveedor.nombre_proveedor">
                <ul class="error-messages">
                  <li :key="message" v-for="message in errorMessages.id_proveedor" v-text="message"></li>
                </ul>
              </div>
            </div>


            <div class="col-sm-4">
              <div class="form-group">
                <label for>Código entrada</label>
                <input class="form-control" disabled type="text" v-model="form.entrada_original.codigo_entrada">
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.codigo_entrada"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for>Fecha entrada</label>
                 <input class="form-control" disabled type="text" v-model="form.entrada_original.fecha_entrada">
                <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages.fecha_entrada"
                    v-text="message"
                  ></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
               <ul class="error-messages">
                  <li
                    :key="message"
                    v-for="message in errorMessages[`entrada_original.entradas_productos`]"
                    v-text="message"
                  ></li>
                </ul>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Unidad medida</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad Recibida</th>
                    <th>Cantidad devuelta</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(item,index) in form.entrada_original.entradas_productos">
                    <tr :key="item.id_producto">
                      <td style="width: 25%">
                        <input class="form-control" readonly type="text" v-model="item.descripcion_producto">
                      </td>
                       <td style="width: 20%">
                        <input class="form-control" readonly type="text" v-model="item.unidad_medida">
                      </td>
                      <td style="width: 15%">
                        <input class="form-control" readonly type="number" v-model="item.precio_unitario">
                      </td>
                      <td style="width: 10%">
                          <span
                          class="form-control align-right"
                        >{{(Number(item.cantidad - Number(item.cantidad_faltante)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))}}</span>
                      </td>
                      <td style="width: 10%">
                        <input @change="item.cantidad_dev = Math.max(Math.min(Math.round(item.cantidad_dev), item.cantidad - item.cantidad_faltante), 0)" class="form-control" type="number" v-model="item.cantidad_dev">
                       <ul class="error-messages">
                          <li
                         :key="message"
                        v-for="message in errorMessages[`entrada_original.entradas_productos.${index}.cantidad_dev`]"
                         v-text="message">
                         </li>
                          </ul>
                      </td>
                      
                      <td style="width: 18%">
                        <span
                          class="form-control align-right"
                        >{{(Number(item.cantidad_dev * Number(item.precio_unitario)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))}}</span>
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
                    <td colspan="4" style="width: 50%">
                    
                    </td>

                    <td class="item-footer" colspan="1">Sub Total</td>
                    <td class="item-footer" colspan="1">
                      <span class="item-dark form-control">  {{subTotal | formatMoney(2)}}</span>         
                    </td>
                  </tr>
                  <tr>
                    <td class="item-empty" colspan="4"></td>

                    <td class="item-footer" colspan="1">
                      <strong>Total</strong>
                    </td>
                    <td class="item-footer" colspan="1">
                      <strong class="item-dark form-control">{{subTotal | formatMoney(2)}}</strong>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="content-box-footer text-right">
            <router-link :to="{ name: 'entradas' }" tag="button">
              <button class="btn btn-default" type="button">Cancelar</button>
            </router-link>
            <button
              :disabled="btnAction != 'Registrar' ? true : false"
              @click="crearEntradaPorDevolucion"
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
import producto from "../../api/productos";
import bodega from "../../api/bodegas";
import tipo from "../../api/tipo_entrada";
import proveedor from "../../api/proveedores";
import entrada from "../../api/entradas";
//import Datepicker from "vuejs-datepicker";

export default {
 /* components: {
    Datepicker
  },*/
  data() {
    return {
      format: "dd-MM-yyyy",

      form: {
        entrada_original:{
        entrada_bodega:{
          nombre:""
        },
        entrada_proveedor:{
          nombre_proveedor:""
        },
        entradas_productos:[],
        codigo_entrada:"",
        fecha_entrada: "",
        id_tipo_entrada: "",
        id_proveedor: "",
        id_bodega: ""
      },
        codigo_entrada: "",
        fecha_entrada: "",
        id_tipo_entrada: "",
        id_proveedor: "",
        id_bodega: ""
      },
      btnAction: "Registrar",
      btnActionBuscar: "Buscar Entrada",
      errorMessages: []
    };
  },
    computed: {
                subTotal() {
                return this.form.entrada_original.entradas_productos.reduce((carry, item) => {
                    return (carry + (Number(item.precio_unitario) * Number(item.cantidad_dev)))//.toFixed(2)//.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                }, 0)
            },
    },
  methods: {
     obtenerEntradaPorCodigo(codEntrada) {
      var self = this;
      self.btnActionBuscar = "Buscando, espere....";
      entrada.obtenerEntradaPorCodigo(
        {
          codigo_entrada: codEntrada
        },
        data => {
          self.form.entrada_original = data;
          self.btnActionBuscar= "Entrada Encontrada";
        },
        err => {
         alertify.warning(err,4)
         self.btnActionBuscar= "Buscar Entrada";
        }
      );
    },

    restaurarFormulario(){
     location.reload();
    },

    eliminarLinea(item, index) {
      if (this.form.entrada_original.entradas_productos.length > 1) {
        this.form.entrada_original.entradas_productos.splice(index, 1);
     }else{
        this.form.entrada_original.entradas_productos=[];
      }
    },

    crearEntradaPorDevolucion() {
      var self = this;
      self.btnAction = "Registrando, espere....";
    
      entrada.crearEntradaPorDevolucion(
        self.form,
        data => {
          this.$router.push({
            name: "entradas"
          });
        },
        err => {
          self.errorMessages = err;
          self.btnAction = "Registrar";
        }
      );
    }
  },
  mounted() {

  }
};
</script>
