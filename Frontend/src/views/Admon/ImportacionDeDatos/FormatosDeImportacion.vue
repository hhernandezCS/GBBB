<template>
    <b-container fluid>
        <div>
            <b-card>
                <!-- Agrupacion para descarga e importacion de formato para marcas-->
                <b-form-group class="text-left" label="Formato para importar marcas" label-cols="12" label-cols-lg="4"
                              label-for="marcas" label-size="lg">
                    <b-button-group class="button-group">
                        <b-button @click="downloadFile('FormatoImportacionMarcas.xlsx')" class="mx-1"  variant="success" size="md">Descargar</b-button>
                        <router-link :to="{name: 'admon-inicializacion-datos'}">
                            <b-button  variant="info" size="md">Importar datos</b-button>
                        </router-link>
                    </b-button-group>
                </b-form-group>
                <!-- Agrupacion para descarga e importacion de formato para rubros-->
                <b-form-group class="text-left" label="Formato para importar grupos" label-cols="12" label-cols-lg="4"
                              label-for="grupos" label-size="lg">
                    <b-button-group class="button-group">
                        <b-button @click="downloadFile('FormatoImportacionGrupos.xlsx')" class="mx-1" size="md" variant="success">Descargar</b-button>
                        <router-link :to="{name: 'admon-inicializacion-datos'}">
                            <b-button size="md" variant="info">Importar datos</b-button>
                        </router-link>
                    </b-button-group>
                </b-form-group>

              <!-- Agrupacion para descarga e importacion de formato para subgrupos-->
              <b-form-group class="text-left" label="Formato para importar subgrupos" label-cols="12" label-cols-lg="4"
                            label-for="subgrupos" label-size="lg">
                <b-button-group class="button-group">
                  <b-button @click="downloadFile('FormatoImportacionSubGrupos.xlsx')" class="mx-1" size="md" variant="success">Descargar</b-button>
                  <router-link :to="{name: 'admon-inicializacion-datos'}">
                    <b-button size="md" variant="info">Importar datos</b-button>
                  </router-link>
                </b-button-group>
              </b-form-group>


              <!-- Agrupacion para descarga e importacion de formato para productos-->
                <b-form-group class="text-left" label="Formato para importar productos" label-cols="12" label-cols-lg="4"
                              label-for="productos" label-size="lg">
                    <b-button-group class="button-group">
                        <b-button @click="downloadFile('FormatoImportacionProductos.xlsx')" class="mx-1" size="md" variant="success">Descargar</b-button>
                        <router-link :to="{name: 'admon-inicializacion-datos'}">
                            <b-button size="md" variant="info">Importar datos</b-button>
                        </router-link>
                    </b-button-group>
                </b-form-group>

                <!-- Agrupacion para descarga e importacion de formato para listas de precios-->
                <b-form-group class="text-left" label="Formato para importar listas de precios" label-cols="12" label-cols-lg="4"
                              label-for="listas_precios" label-size="lg">
                    <b-button-group class="button-group">
                        <b-button @click="downloadFile('FormatoImportacionListasPrecios.xlsx')" class="mx-1" size="md" variant="success">Descargar</b-button>
                        <router-link :to="{name: 'admon-inicializacion-datos'}">
                            <b-button size="md" variant="info">Importar datos</b-button>
                        </router-link>
                    </b-button-group>
                </b-form-group>

                <!--Gif de pantalla de carga-->
                <template v-if="loading">
                    <BlockUI>
                        <b-spinner label="cargando..." variant="info"/>
                    </BlockUI>
                </template>
            </b-card>
        </div>
    </b-container>
</template>
<script type="text/ecmascript-6">
  import {
    BAlert,
    BButton,
    BButtonGroup,
    BCard,
    BCardFooter,
    BContainer,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormDatepicker,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BPaginationNav,
    BRow,
    VBTooltip,
    BSpinner
  } from 'bootstrap-vue'
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import vSelect from 'vue-select'
  import ToastificationContent from '../../../@core/components/toastification/ToastificationContent'

  export default {
    components: {
      BCard,
      BCardFooter,
      BPaginationNav,
      BFormCheckbox,
      BFormGroup,
      vSelect,
      BRow,
      BButton,
      BFormCheckboxGroup,
      BFormDatepicker,
      BAlert,
      BFormSelect,
      BContainer,
      BFormInput,
      BButtonGroup,
      BSpinner
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: false,
        descargando: false,
        url: loadingImage,
      }
    },
    methods: {
      downloadFile($documento) {
        let self = this;
        self.loading = true;
        axios({
          baseURL: window.location.origin,
          url: '/docs/' + $documento,
          // url: '/docs/FormatoImportacionMarcas.xlsx',
          method: 'GET',
          responseType: 'blob'
        }).then((res) => {
          let FILE = window.URL.createObjectURL(new Blob([res.data]));

          let docUrl = document.createElement('a');
          docUrl.href = FILE;
          docUrl.setAttribute('download', $documento);
          document.body.appendChild(docUrl);
          docUrl.click();
          self.loading = false;
        }).catch(() =>{
          self.loading = false;
          this.$toast({
              component: ToastificationContent,
              props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Ha ocurrido un error descargando el formato.',
                  variant: 'warning',
              }
          },
          {
              position: 'bottom-right'
          });
        });
      }
    },
    mounted() {
    }
  };
</script>
<style lang="scss">
    .button-group {
        margin-top: 3px !important;
    }
</style>
