<template>
  <b-card>
    <ComingSoon />
  </b-card>
</template>

<script type="text/ecmascript-6">
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
} from 'bootstrap-vue'
import ComingSoon from '../error/UnderMaintenance';
export default {
  components: {
    BFormDatepicker, BRow, BCol, BCard, BCardFooter, BPaginationNav, BButton, BFormCheckbox, BFormGroup,
    BBadge,
    BTab,
    BTabs,
    ComingSoon
  },
  directives: {
    'b-tooltip': VBTooltip,
  },
  data() {
    return {
      loading: true,
      //It is important to import the loading image then use here
      filter: {
        page: 1,
        limit: 20,
        limitOptions: [5, 10, 15, 20],
        search: {
          field: 'descripcion',
          value: '',
          status: 0
        }
      },
      origenes: [],
      total: 0,
      descargando : false
    }
  },
  methods: {


  },
  /* components: {
     pagination: Pagination
   },*/
  mounted() {

  }
}
</script>
<style lang="scss" scoped>
@import '@core/scss/vue/libs/vue-select.scss';

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
