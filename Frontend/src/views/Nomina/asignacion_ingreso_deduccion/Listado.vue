<template>
  <b-card>
    <b-row>
      <div class="col-md-3 sm-text-center">
        <router-link class="btn btn-success" tag="button"
                     :to="{ name: 'nomina-asignacion-ingresos-registrar' }">
          <feather-icon icon="PlusCircleIcon"></feather-icon>
          Registrar
        </router-link>
      </div>
      <div @keyup.enter="filter.page = 1;obtenerplanillas();"
           class="col-md-9 sm-text-center form-inline justify-content-end">
        <b-form-checkbox v-model="filter.search.status" class="custom-control-primary mr-1">
          Mostrar Todos
        </b-form-checkbox>
        <select v-model="filter.search.field" class="form-control mb-1 mr-sm-1 mb-sm-0 search-field">
          <option value="descripcion">Areas</option>
        </select>
        <input v-model="filter.search.value" class="form-control mb-1 mr-sm-1 mb-sm-0" placeholder="Buscar"
               type="text">
        <b-button variant="info" @click="filter.page = 1;obtenerplanillas();"
                  v-b-tooltip.hover.top="'Buscar!'">
          <feather-icon icon="SearchIcon"></feather-icon>
        </b-button>
      </div>
    </b-row>
    <div class="table-responsive mt-3">
      <table class="table table-striped table-bordered">
        <thead>
        <tr>
          <th class="text-center table-number">Código</th>
          <th>Descripción</th>
          <!--<th>Cedula</th>
          <th>Area</th>
          <th>Cargo</th>-->
          <th class="text-center table-number">Estado</th>
          <th class="text-center action">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <template v-for="planilla in planillas">
          <tr :key="planilla.id_planilla_control">
            <td class="text-center">{{ planilla.codigo_planilla }}</td>
            <td>{{ planilla.descripcion }}</td>
            <!--<td>{{ planilla.cedula }}</td>
            <td>{{ planilla.planilla_area.descripcion }}</td>
            <td>{{ planilla.planilla_cargo.descripcion }}</td>-->
            <td class="text-center">
              <div v-if="planilla.estado">
                <b-badge variant="success"> Activo</b-badge>
              </div>
              <div v-else>
                <b-badge variant="danger"> Desactivado</b-badge>
              </div>
            </td>
            <td class="text-center">
              <template>
                <router-link v-b-tooltip="'Actualizar Asignacion'"
                             :to="{name: 'nomina-asignacion-ingresos-actualizar', params: {id_planilla_control: planilla.id_planilla_control}}"
                             tag="a">
                  <feather-icon icon="EditIcon"></feather-icon>
                </router-link>
              </template>
            </td>
          </tr>
        </template>
        <tr v-if="!planillas.length">
          <td class="text-center" colspan="8">Sin registros</td>
        </tr>
        </tbody>
      </table>

    </div>

  </b-card>

</template>

<script type="text/ecmascript-6">
import planilla from "@/api/Nomina/planillas_controles";
import {
  BFormDatepicker,
  BRow,
  BCol,
  BCard,
  BCardFooter,
  BPaginationNav,
  BButton,
  VBTooltip,
  BFormCheckbox,
  BFormGroup,
  BBadge,
  BTab,
  BTabs,
  BSpinner,
  BTable
} from 'bootstrap-vue';
import loadingImage from '../../../assets/images/loader/block50.gif'


export default {
  components: {
    BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
    BBadge,
    BTab,
    BTabs,
    BSpinner,
    BTable
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      descargando: false,
      loading: true,

      url:  loadingImage,   //It is important to import the loading image then use here
      filter: {
        page: 1,
        limit: 5,
        limitOptions: [5, 10, 15, 20],
        search: {
          field: 'descripcion',
          value: "",
          status: 0,
        }
      },
      planillas: [],
      total: 0
    };
  },
  methods: {
    obtenerplanillas() {
      var self = this;
      self.loading = true;
      planilla.obtener(self.filter, data => {
        self.planillas = data.rows
        self.total = data.total
        self.loading = false;
      }, err => {
        self.loading = false;
        console.log(err)
      })
    },
    changeLimit(limit) {
      this.filter.page = 1;
      this.filter.limit = limit;
      this.obtenerplanillas();
    },
    changePage(page) {
      this.filter.page = page;
      this.obtenerplanillas();
    },




  },
  /*components: {
        pagination: Pagination
      },*/
  mounted() {
    this.obtenerplanillas();
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