<template>
  <div class="main">
    <div class="content-box">
      <div class="content-box-header">
        <div class="box-title">Administrar Ordenes de Compra Artículos y Servicios</div>
        <div class="box-description">Listado de Ordenes de Compra</div>
        <div class="box-description"><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal' }">Módulos</router-link>><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal-compras' }"> Módulo compras</router-link>> Ordenes de compra Artículos y Servicios</div>
      </div>
      <div class="row">
        <div class="col-md-6 sm-text-center">
          <router-link :to="{ name: 'registrar-orden-compra-ps' }" class="btn btn-success" tag="button">
            <i class="pe-7s-plus"></i> Registrar Orden Compra
          </router-link>
        </div>
        <div
                @keyup.enter="filter.page = 1;obtenerOrdenesCompra();"
                class="col-md-6 sm-text-center form-inline justify-content-end"
        >
          <select
                  class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                  v-model="filter.search.field"
          >
            <option value="atencion">Contacto</option>
          </select>
          <input
                  class="form-control mb-1 mr-sm-1 mb-sm-0"
                  placeholder="Buscar"
                  type="text"
                  v-model="filter.search.value"
          >
          <button @click="filter.page = 1;obtenerOrdenesCompra();" class="btn btn-info">
            <i class="pe-7s-search"></i> Buscar
          </button>
        </div>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
           <!-- <th></th>-->
           <th>No.OC</th>
            <th>No. Factura Proveedor</th>
            <th>Proveedor</th>
            <th>Fecha</th>
            <th class="text-center table-number">Estado</th>
            <th class="text-center action">Opciones</th>
          </tr>
          </thead>
          <tbody>
          <template v-for="(orden,key) in ordenes_compras">
            <tr :key="orden.id_orden_servicio">
              <!--<td class="detail-link">
                <div v-tooltip="'Mostrar Productos'" @click="mostrarProductos(key)" class="link"></div>
              </td>-->
               <td>{{ orden.id_orden_servicio }}</td>
                <td>{{ orden.numero_factura?orden.numero_factura:'N/D' }}</td>
                <td>{{ orden.orden_compra_proveedor? orden.orden_compra_proveedor.nombre_comercial : 'N/A' }}</td>
                <td>{{ orden.f_orden_servicio }}</td>

                <td class="text-center">
                  <div v-if="orden.estado===0">
                    <span class="badge badge-danger">Rechazado</span>
                  </div>
                  <div v-if="orden.estado===1">
                    <span class="badge badge-blue">Registrado</span>
                  </div>
                  <div v-if="orden.estado===2">
                    <span class="badge badge-success">Ordenado</span>
                  </div>
                  <div v-if="orden.estado===3">
                    <span class="badge badge-success">Facturado</span>
                  </div>
                  <div v-if="orden.estado===4">
                    <span class="badge badge-success">Enviado</span>
                  </div>
                  <div v-if="orden.estado===5">
                    <span class="badge badge-success">Recibido</span>
                  </div>
                  <div v-if="orden.estado===99">
                    <span class="badge badge-grey">Borrador</span>
                  </div>
                </td>
                <td class="text-center">
                  <router-link
                          v-tooltip="'Mostrar Detalles de la Orden de Compra'"
                          :to="{ name: 'mostrar-orden-compra-ps', params: { id_orden_servicio: orden.id_orden_servicio } }"
                          tag="a"
                  >
                    <i aria-hidden="true" class="fa fa-eye"></i>
                  </router-link>

                  <template v-if="orden.estado===99">
                    <router-link  v-tooltip="'Actualizar Orden de Compra'" :to="{ name: 'actualizar-orden-compra-ps', params: { id_orden_servicio: orden.id_orden_servicio } }" tag="a"><i class="icon-pencil"></i></router-link>
                  </template>

                  <template v-if="[1,2,3,4].indexOf(Number(orden.estado)) >= 0">
                    <router-link  v-tooltip="orden.estado===1?'Cambiar estado a Ordenado': orden.estado===2?'Cambiar estado a Facturado': orden.estado===3?'Cambiar estado a Enviado': orden.estado===4?'Cambiar estado a Recibido':'Revisar Estado de Orden de Compra'" :to="{ name: 'revisar-orden-compra-ps', params: { id_orden_servicio: orden.id_orden_servicio } }" tag="a"> <i aria-hidden="true" class="fa fa-check-circle"></i></router-link>
                  </template>

                </td>
              </tr>
              <tr v-if="orden.mostrar">
                <td></td>
                <td colspan="9">
                  <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                      <th  colspan="3">Descripción</th>
                      <th>Cantidad</th>
                      <th>Precio de compra</th>
                      <th>Descuento %</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                            :key="productosDetalle.id_orden_servicio_detalle"
                            v-for="productosDetalle in orden.orden_compra_listado">
                      <td colspan="3">{{ productosDetalle.descripcion }}</td>
                      <td>{{ productosDetalle.cantidad }}</td>
                      <td>{{ productosDetalle.precio }}</td>
                      <td>{{ productosDetalle.descuento }}</td>
                      <td>{{productosDetalle.subtotal}}</td>
                    </tr>
                    </tbody>
                    <tfoot>

                    <tr>
                      <td colspan="5"></td>
                      <td>Subtotal</td>
                      <td> <strong> {{orden.subtotal | formatMoney(2)}}</strong></td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                      <td >Descuento</td>
                      <td> <strong> {{orden.total_descuento | formatMoney(2)}}</strong></td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                      <td >I.V.A.</td>
                      <td> <strong> {{orden.total_descuento | formatMoney(2)}}</strong></td>
                    </tr>
                    <tr>
                      <td class="item-footer" colspan="2"> Total Unidades</td>
                      <td></td>
                      <td  class="item-footer">
                        <strong>{{orden.tot_unidades}}</strong>
                      </td>
                      <td></td>
                      <td>Total</td>
                      <td> <strong> {{orden.total | formatMoney(2)}}</strong></td>
                    </tr>

                    </tfoot>
                  </table>
                </td>
                <td></td>
              </tr>
            </template>
            <tr v-if="!ordenes_compras.length">
              <td class="text-center" colspan="11">Sin datos</td>
            </tr>
            </tbody>
          </table>
        </div>
        <pagination
                :items="ordenes_compras"
                :limit="filter.limit"
                :limitOptions="filter.limitOptions"
                :page="filter.page"
                :total="total"
                @changeLimit="changeLimit"
                @changePage="changePage"
        ></pagination>

        <template v-if="loading">
          <BlockUI :message="msg" :url="url"></BlockUI>
        </template>

      </div>
    </div>
  </template>

  <script type="text/ecmascript-6">
    import orden from "../../api/orden_compras_ps";
    import loadingImage from '../../assets/images/block50.gif'
    export default {
      data() {
        return {
          loading:true,
          msg: 'Cargando el contenido espere un momento',
          url : '/public'+loadingImage,
          filter: {
            page: 1,
            limit: 5,
            limitOptions: [5, 10, 15, 20],
            search: {
              field: "atencion",
              value: ""
            }
          },

          ordenes_compras: [],
          total: 0
        };
      },
      methods: {

        mostrarProductos(key) {
          if (this.ordenes_compras[key].mostrar) {
            this.ordenes_compras[key].mostrar = 0;
          } else {
            this.ordenes_compras[key].mostrar = 1;
          }
        },

        obtenerOrdenesCompra() {
          var self = this;
          orden.obtenerOrdenesCompra(
                  self.filter,
                  data => {
                    data.rows.forEach((ordenes_compras, key) => {
                      data.rows[key].mostrar = 0;
                    });
                    self.ordenes_compras = data.rows;
                    self.total = data.total;
                    self.loading = false;
                  },
                  err => {
                    self.loading = false;
                    console.log(err);
                  }
          );
        },

        changeLimit(limit) {
          this.filter.page = 1;
          this.filter.limit = limit;
          this.obtenerOrdenesCompra();
        },
        changePage(page) {
          this.filter.page = page;
          this.obtenerOrdenesCompra();
        },
      },
      mounted() {
        this.obtenerOrdenesCompra();
      }
    };
  </script>
  <style lang="scss" scoped>
    .search-field {
      min-width: 120px;
    }
    .table {
      a {
        color: #2675dc;
      }
      .table-number {
        width: 60px;
      }
      .action {
        width: 180px;
      }
      .detail-link {
        width: 60px;
        position: relative;
        .link {
          width: 10px;
          height: 4px;
          margin-left: auto;
          margin-right: auto;
          cursor: pointer;
          margin-top: 8px;
          background-color: #595959;
          border: 1px solid #595959;
          &:before {
            content: "";
            width: 4px;
            height: 10px;
            left: 0px;
            right: 0px;
            cursor: pointer;
            margin: 0px auto;
            margin-top: -4px;
            position: absolute;
            background-color: #595959;
            border: 1px solid #595959;
          }
        }
      }
    }
  </style>