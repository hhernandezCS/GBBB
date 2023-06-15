<template>
  <div class="main">
    <div class="content-box">
      <div class="content-box-header">
        <div class="box-title">Administrar Ordenes de Compra</div>
        <div class="box-description">Listado de Ordenes de Compra</div>
        <div class="box-description"><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal' }">Módulos</router-link>><router-link :style="'margin-right: 10px;color: blue;'" :to="{ name: 'pagina-principal-compras' }"> Módulo compras</router-link>> Ordenes de compra</div>
      </div>
      <div class="row">
        <!--<div class="col-md-6 sm-text-center">
          <router-link :to="{ name: 'registrar-orden-compra' }" class="btn btn-success" tag="button">
            <i class="pe-7s-plus"></i> Registrar Orden Compra
          </router-link>
        </div>-->
        <div
                @keyup.enter="filterOC.pageOC = 1;obtenerOrdenesCompra();"
                class="col-md-12 sm-text-center form-inline justify-content-end"
        >
          <select
                  class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                  v-model="filterOC.searchOC.fieldOC"
          >
            <option value="atencion">Contacto</option>
          </select>
          <input
                  class="form-control mb-1 mr-sm-1 mb-sm-0"
                  placeholder="Buscar"
                  type="text"
                  v-model="filterOC.searchOC.valueOC"
          >
          <button @click="filterOC.pageOC = 1;obtenerOrdenesCompra();" class="btn btn-info">
            <i class="pe-7s-search"></i> Buscar
          </button>
        </div>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
            <!--<th></th>-->
            <th>No. OC</th>
            <th>Proveedor</th>
            <th>Fecha</th>
            <th class="text-center table-number">Estado</th>
            <th class="text-center action">Opciones</th>
          </tr>
          </thead>
          <tbody>
          <template v-for="(orden,key) in ordenes_compras">
            <tr :key="orden.id_orden_compra">
              <!--<td class="detail-link">
                <div v-tooltip="'Mostrar Productos'" @click="mostrarProductosOC(key)" class="link"></div>
              </td>-->
              <td>{{ orden.id_orden_compra }}</td>
              <td>{{ orden.orden_compra_proveedor? orden.orden_compra_proveedor.nombre_comercial : 'N/A' }}</td>
              <td>{{ orden.f_orden_compra }}</td>

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
                        :to="{ name: 'mostrar-orden-compra', params: { id_orden_compra: orden.id_orden_compra } }"
                        tag="a"
                >
                  <i aria-hidden="true" class="fa fa-eye"></i>
                </router-link>

                <template v-if="orden.estado===99">
                  <router-link  v-tooltip="'Actualizar Orden de Compra'" :to="{ name: 'actualizar-orden-compra', params: { id_orden_compra: orden.id_orden_compra } }" tag="a"><i class="icon-pencil"></i></router-link>
                </template>

                <template v-if="[1,2,3].indexOf(Number(orden.estado)) >= 0">
                  <router-link  v-tooltip="orden.estado===1?'Cambiar estado a Ordenado': orden.estado===2?'Cambiar estado a Facturado': orden.estado===3?'Cambiar estado a Enviado':'Revisar Estado de Orden de Compra'" :to="{ name: 'revisar-orden-compra', params: { id_orden_compra: orden.id_orden_compra } }" tag="a"> <i aria-hidden="true" class="fa fa-check-circle"></i></router-link>
                </template>

                <template v-if="[3].indexOf(Number(orden.estado)) >= 0 && orden.tipo_compra===2">
                  <router-link  v-tooltip="'Crear Importación'" :to="{ name: 'registrar-importacion-oc', params: { id_orden_compra: orden.id_orden_compra } }" tag="a"><i class="fa fa-dropbox"></i></router-link>
                </template>
              </td>
            </tr>
            <tr v-if="orden.mostrar">
              <td></td>
              <td colspan="9">
                <table class="table table-striped table-bordered">
                  <thead>
                  <tr>
                    <th class="text-center table-number">Código producto</th>
                    <th>Descripción producto</th>
                    <th>Unidad de medida</th>
                    <th>Cantidad</th>
                    <th>Precio de compra</th>
                    <th>Descuento %</th>
                    <th>Subtotal</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr
                          :key="productosDetalle.id_orden_compra_producto"
                          v-for="productosDetalle in orden.orden_compra_productos">
                    <td>{{ productosDetalle.orden_compra_producto.codigo_sistema }}</td>
                    <td>{{ productosDetalle.orden_compra_producto.descripcion }}</td>
                    <td>{{ productosDetalle.orden_compra_producto.unidad_medida.unidad_medida }}</td>
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
                    <td> <strong>C$ {{orden.subtotal | formatMoney(2)}}</strong></td>
                  </tr>
                  <tr>
                    <td colspan="5"></td>
                    <td >Descuento</td>
                    <td> <strong>C$ {{orden.total_descuento | formatMoney(2)}}</strong></td>
                  </tr>
                  <tr>
                    <td class="item-footer" colspan="2"> Total Unidades</td>
                    <td></td>
                    <td  class="item-footer">
                      <strong>{{orden.tot_unidades}}</strong>
                    </td>
                    <td></td>
                    <td>Total</td>
                    <td> <strong>C$ {{orden.total | formatMoney(2)}}</strong></td>
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
              :limit="filterOC.limitOC"
              :limitOptions="filterOC.limitOptionsOC"
              :page="filterOC.pageOC"
              :total="totalOC"
              @changeLimit="changeLimitOC"
              @changePage="changePageOC"
      ></pagination>

<br>
      <br>
      <div class="content-box-header">
        <div class="box-title">Solicitudes de Compra Autorizadas</div>
        <div class="box-description">Listado de Solicitudes de Compra</div>
      </div>

      <div class="row">
        <div
                @keyup.enter="filter.page = 1;obtenerSolicitudesCompra();"
                class="col-md-12 sm-text-center form-inline justify-content-end"
        >
          <select
                  class="form-control mb-1 mr-sm-1 mb-sm-0 search-field"
                  v-model="filter.search.field"
          >
            <option value="observacion">observacion</option>
          </select>
          <input
                  class="form-control mb-1 mr-sm-1 mb-sm-0"
                  placeholder="Buscar"
                  type="text"
                  v-model="filter.search.value"
          >
          <button @click="filter.page = 1;obtenerSolicitudesCompra();" class="btn btn-info">
            <i class="pe-7s-search"></i> Buscar
          </button>
        </div>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
          <thead>
          <tr>
          <!--  <th></th>-->
            <th>No. SC</th>
            <th>Sucursal</th>
            <th>Area</th>
            <th>Trabajador</th>
            <th>Fecha Necesidad</th>
            <th class="text-center table-number">Estado</th>
            <th class="text-center action">Opciones</th>
          </tr>
          </thead>
          <tbody>
          <template v-for="(solicitud,key) in solicitudes_compras">
            <tr>
             <!-- <td class="detail-link">
                <div v-tooltip="'Mostrar Productos'" @click="mostrarProductos(key)" class="link"></div>
              </td>-->
              <td>{{ solicitud.id_solicitud_compra }}</td>
              <td>{{ solicitud.solicitud_sucursal? solicitud.solicitud_sucursal.descripcion : 'N/A' }}</td>
              <td>{{ solicitud.solicitud_area? solicitud.solicitud_area.descripcion : 'N/A' }}</td>
              <td>{{ solicitud.solicitud_trabajador? solicitud.solicitud_trabajador.primer_nombre + ' ' + solicitud.solicitud_trabajador.primer_apellido  : 'N/A' }}</td>
              <td>{{ solicitud.f_necesidad }}</td>
              <td class="text-center">
                <div v-if="solicitud.estado===0">
                  <span class="badge badge-danger">Rechazado</span>
                </div>
                <div v-if="solicitud.estado===1">
                  <span class="badge badge-blue">Solicitado</span>
                </div>
                <div v-if="solicitud.estado===2">
                  <span class="badge badge-success">Cotizado</span>
                </div>
                <div v-if="solicitud.estado===3">
                  <span class="badge badge-success">Autorizado</span>
                </div>
                <div v-if="solicitud.estado===4">
                  <span class="badge badge-success">Orden Compra</span>
                </div>
                <div v-if="solicitud.estado===99">
                  <span class="badge badge-grey">Borrador</span>
                </div>
              </td>
              <td class="text-center">
                <router-link
                        v-tooltip="'Mostrar Detalles de Solicitud'"
                        :to="{ name: 'mostrar-solicitud-compra', params: { id_solicitud_compra: solicitud.id_solicitud_compra } }"
                        tag="a"
                >
                  <i aria-hidden="true" class="fa fa-eye"></i>
                </router-link>

                <!-- Actividad SGCL-42

                No permitir registrar una orden cuando el estado de la solicitud es "Orden Compra" -->

                <template v-if="[3,4].indexOf(Number(solicitud.estado)) >= 0 && Number(solicitud.estado) != 4">
                  <router-link v-tooltip="'Crear Orden de Compra para esta Solicitud'" :to="{ name: 'registrar-orden-compra-solicitud', params: { id_solicitud_compra: solicitud.id_solicitud_compra } }" tag="a"> <i aria-hidden="true" class="fa fa-check-circle"></i></router-link>
                </template>

              </td>
            </tr>
            <tr v-if="solicitud.mostrar">
              <td></td>
              <td colspan="9">
                <table class="table table-striped table-bordered">
                  <thead>
                  <tr>
                    <th class="text-center table-number">Código producto</th>
                    <th>Descripción producto</th>
                    <th>Unidad de medida</th>
                    <th>Cantidad</th>
                    <th>Precio de compra</th>
                    <th>Descuento %</th>
                    <th>Subtotal</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr
                          :key="productosDetalle.id_solicitud_compra_producto"
                          v-for="productosDetalle in solicitud.solicitud_productos">
                    <td>{{ productosDetalle.solicitud_producto.codigo_sistema }}</td>
                    <td>{{ productosDetalle.solicitud_producto.descripcion }}</td>
                    <td>{{ productosDetalle.solicitud_producto.unidad_medida.unidad_medida }}</td>
                    <td>{{ productosDetalle.cantidad }}</td>
                    <td>{{ productosDetalle.precio_info }}</td>
                    <td>{{ productosDetalle.descuento }}</td>
                    <td>{{productosDetalle.subtotal}}</td>
                  </tr>
                  </tbody>
                  <tfoot>

                  <tr>
                    <td colspan="5"></td>
                    <td>Subtotal</td>
                    <td> <strong>C$ {{solicitud.subtotal | formatMoney(2)}}</strong></td>
                  </tr>
                  <tr>
                    <td colspan="5"></td>
                    <td >Descuento</td>
                    <td> <strong>C$ {{solicitud.total_descuento | formatMoney(2)}}</strong></td>
                  </tr>
                  <tr>
                    <td class="item-footer" colspan="2"> Total Unidades</td>
                    <td></td>
                    <td  class="item-footer">
                      <strong>{{solicitud.tot_unidades}}</strong>
                    </td>
                    <td></td>
                    <td>Total</td>
                    <td> <strong>C$ {{solicitud.total | formatMoney(2)}}</strong></td>
                  </tr>

                  </tfoot>
                </table>
              </td>
              <td></td>
            </tr>
          </template>
          <tr v-if="!solicitudes_compras.length">
            <td class="text-center" colspan="11">Sin datos</td>
          </tr>
          </tbody>
        </table>
      </div>
      <pagination
              :items="solicitudes_compras"
              :limit="filter.limit"
              :limitOptions="filter.limitOptions"
              :page="filter.page"
              :total="total"
              @changeLimit="changeLimit"
              @changePage="changePage"
      ></pagination>


    </div>
  </div>
</template>

<script type="text/ecmascript-6">
  import solicitud from "../../api/solicitudes_compras";
  import orden from "../../api/orden_compras";

  export default {
    data() {
      return {
        filter: {
          page: 1,
          limit: 5,
          limitOptions: [5, 10, 15, 20],
          search: {
            field: "observacion",
            value: "",
            autorizadas:true,
          },
        },

        filterOC: {
          pageOC: 1,
          limitOC: 5,
          limitOptionsOC: [5, 10, 15, 20],
          searchOC: {
            fieldOC: "atencion",
            valueOC: ""
          }
        },

        solicitudes_compras: [],
        ordenes_compras: [],
        total: 0,
        totalOC: 0
      };
    },
    methods: {
      mostrarProductos(key) {
        if (this.solicitudes_compras[key].mostrar) {
          this.solicitudes_compras[key].mostrar = 0;
        } else {
          this.solicitudes_compras[key].mostrar = 1;
        }
      },
      mostrarProductosOC(key) {
        if (this.ordenes_compras[key].mostrar) {
          this.ordenes_compras[key].mostrar = 0;
        } else {
          this.ordenes_compras[key].mostrar = 1;
        }
      },
      obtenerSolicitudesCompra() {
        var self = this;
        solicitud.obtenerSolicitudesCompra(
                self.filter,
                data => {
                  data.rows.forEach((solicitudes_compras, key) => {
                    data.rows[key].mostrar = 0;
                  });
                  self.solicitudes_compras = data.rows;
                  self.total = data.total;
                },
                err => {
                  console.log(err);
                }
        );
      },
      obtenerOrdenesCompra() {
        var self = this;
        orden.obtenerOrdenesCompra(
                self.filterOC,
                data => {
                  data.rows.forEach((ordenes_compras, key) => {
                    data.rows[key].mostrar = 0;
                  });
                  self.ordenes_compras = data.rows;
                  self.totalOC = data.total;
                },
                err => {
                  console.log(err);
                }
        );
      },
      changeLimit(limit) {
        this.filter.page = 1;
        this.filter.limit = limit;
        this.obtenerSolicitudesCompra();
      },
      changePage(page) {
        this.filter.page = page;
        this.obtenerSolicitudesCompra();
      },

      changeLimitOC(limit) {
        this.filterOC.pageOC = 1;
        this.filterOC.limitOC = limit;
        this.obtenerOrdenesCompra();
      },
      changePageOC(page) {
        this.filterOC.pageOC = page;
        this.obtenerOrdenesCompra();
      },
    },
    /*components: {
      pagination: Pagination
    },*/
    mounted() {
      this.obtenerSolicitudesCompra();
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