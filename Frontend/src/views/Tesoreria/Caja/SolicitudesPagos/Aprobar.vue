<template>
      <div class="panel">
      <div class="panel-heading">
        <span class="panel-title">Solicitud de pago</span>
        <div>
                 <router-link :to="{ name: 'listado-solicitudes-pago' }" class="btn" title="Regresar">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <!--<div class="btn-group">
                        <router-link :to="`/public/entrada/pdf/${form.id_entrada}`" target="_blank" class="btn" title="Ver PDF">
                            <i class="fa fa-file-pdf-o"></i>
                       </router-link>
                         <router-link :to="`/public/entrada/pdf/${form.id_entrada}?mode=download`" target="_blank" class="btn" title="Descargar PDF">
                            <i class="fa fa-download"></i>
                       </router-link>
                    </div>

                   <div class="btn-group">
                  
                   <template v-if="[0,1,2].indexOf(form.estado) >= 0">
                     <button class="btn btn-info"><i class="fa fa-files-o">   Duplicar</i></button>
                   </template>
                  </div>-->

                  <div class="btn-group">
                   <template v-if="[0,3,4].indexOf(form.estado) < 0">
                       <button class="btn btn-success" v-confirm="{ok: aprobar,message: 'Esta seguro de APROBAR la solicitud de pago? Esta accion no se podra deshacer.'}"><i class="fa fa-check">   Aprobar</i></button>
                   </template>
                  </div>
                    <div class="btn-group">
                   <template v-if="[0,4].indexOf(form.estado) < 0">
                       <button class="btn btn-danger" v-confirm="{ok: anular,message: 'Esta seguro de ANULAR la solicitud de pago? Esta accion no se podra deshacer.'}"><i class="fa fa-trash-o">   Anular</i></button>
                   </template>
                   </div>
                   </div>
</div>


      <div class="panel-body">
        <div class="document">
          <div class="document-heading">
             <div class="row">
                  <div class="col col-12">
                      <p>
                          <strong>Solicitud de Pago #{{form.id_solicitud_pago}}</strong>
                      </p>
                  </div>
             </div>
             <br>
            <div class="row">
              <div class="col col-4">
                <p>
                  <strong>Beneficiario:</strong>
                </p>
                  <span>{{form.beneficiario}}</span>
              </div>
              <div class="col col-4">
                  <p>
                      <strong>Concepto:</strong>
                  </p>
                  <span>{{form.concepto}}</span>
              </div>
              <div class="col col-4">
                <table class="document-summary">
                  <tbody>
                    <tr>
                      <td>Estado actual</td>
                      <td>
                <div v-if="form.estado===0">
								<span class="badge badge-danger" style="font-size: 100%;">Anulada</span>
                </div>

                <div v-if="form.estado===1">
								<span class="badge badge-info" style="font-size: 100%;">Esperando revisión</span>
                </div>

                <div v-if="form.estado===2">
								<span class="badge badge-warning" style="font-size: 100%;">Esperando aprobación</span>
                </div>
                          <div v-if="form.estado===3">
                              <span class="badge badge-default" style="font-size: 100%;">Aprobada</span>
                          </div>
                          <div v-if="form.estado===4">
                              <span class="badge badge-success" style="font-size: 100%;">Emitido</span>
                          </div>

                      </td>
                    </tr>
                    <tr>
                      <td>Fecha:</td>
                      <td>{{form.fecha_solicitud}}</td>
                    </tr>
                    <tr>
                      <td>Moneda:</td>
                      <td>{{form.moneda_solicitud.descripcion}}</td>
                    </tr>
                    <tr>
                      <td>Total:</td>
                      <td>{{form.monto | formatMoney(2)}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="document-body">
            <table class="document-table">
              <thead>
                <tr>
                  <th>Cuenta Contable</th>
                  <th>Concepto</th>
                  <th class="align-center">Debe</th>
                  <th class="align-center">Haber</th>
                </tr>
              </thead>
              <tbody>
              <template v-if="form.documento_solicitud">
                <template v-for="item in form.documento_solicitud.movimientos_cuentas" >
             <tr :key="item.id_documento_cuenta">
                    <td class="width-4">
                      {{item.cta_contable}}
                    </td>
                    <td class="width-4">
                    
                        <pre>{{item.concepto}}</pre>
                     
                    </td>
                    <td
                      class="width-2 align-right"
                    >{{item.debe | formatMoney(2)}}</td>
                 <td
                         class="width-2 align-right"
                 >{{item.haber | formatMoney(2)}}</td>
                  </tr>
                </template>
              </template>

              <template v-if="!form.documento_solicitud">
                  <td class="align-right" colspan="4">
                      No hay datos registrados
                  </td>
              </template>
              </tbody>
              <tfoot>
              <template v-if="form.documento_solicitud">
                <tr>
                  <td colspan="1"></td>
                  <td >Total</td>
                    <td class="align-right"> <strong>{{form.monto | formatMoney(2)}}</strong></td>
                     <td class="align-right"><strong>{{form.monto | formatMoney(2)}}</strong></td>
                </tr>
              </template>
              </tfoot>
            </table>
          </div>
          <div class="document-footer row">
              <div class="col col-3">
                  <p>
                      <strong>Realizada por:</strong>
                  </p>
                  <template v-if="form.usuario_registra.trabajador">
                  <span>{{form.usuario_registra.trabajador.primer_nombre+' '+form.usuario_registra.trabajador.primer_apellido}}</span>
                  </template>
                  <br>
                  <span>{{form.f_creacion}}</span>
              </div>
              <div class="col col-3">
                  <p>
                      <strong>Revisada por por:</strong>
                  </p>
                  <template v-if="form.usuario_revisa">
                  <span>{{form.usuario_revisa.trabajador.primer_nombre+' '+form.usuario_revisa.trabajador.primer_apellido}}</span>
                  </template>
                  <br>
                  <span>{{form.f_revision}}</span>
              </div>
              <div class="col col-3">
                  <p>
                      <strong>Aprobada por:</strong>
                  </p>
                  <template v-if="form.usuario_aprueba">
                  <span>{{form.usuario_aprueba.trabajador.primer_nombre+' '+form.usuario_aprueba.trabajador.primer_apellido}}</span>
                  </template>
                  <br>
                  <span>{{form.f_aprobacion}}</span>
              </div>
              <div class="col col-3">
                  <p>
                      <strong>Emitida por:</strong>
                  </p>
                  <template v-if="form.usuario_emite">
                  <span>{{form.usuario_emite.trabajador.primer_nombre+' '+form.usuario_emite.trabajador.primer_apellido}}</span>
                  </template>
                  <br>
                  <span>{{form.f_emision}}</span>
              </div>
          </div>
        </div>
      </div>
    </div>

</template>
<script type="text/ecmascript-6">

import solicitud from "../../api/solicitudes_pago";

export default {
  data() {
    return {
      format: "dd-MM-yyyy",
      form: {
          estado:0,
         moneda_solicitud: [],
          documento_solicitud: [],
          usuario_registra: [],
         total: 0,

      },
      btnAction: "Registrar",
      errorMessages: []
    };
  },
  methods: {
      obtenerSolicitudPago() {
      var self = this;
        solicitud.obtenerSolicitudPago(
        {
            id_solicitud_pago: this.$route.params.id_solicitud_pago
        },
        data => {
          self.form = data.solicitud;
        },
        err => {
          console.log(err);
        }
      );
    },

      aprobar() {
          solicitud.aprobar({
              id_solicitud_pago:this.$route.params.id_solicitud_pago
          }, data => {
              alertify.success("Solicitud de pago aprobada correctamente",5);
              this.obtenerSolicitudPago()
          }, err => {
              console.log(err)
          })
      },

      anular() {
                  solicitud.anular({
                      id_solicitud_pago:this.$route.params.id_solicitud_pago
                  }, data => {

                  alertify.warning("La Solicitud de pago ha sido anulada",5);
                      this.obtenerSolicitudPago()
                }, err => {
                      console.log(err)
                  })
      }
  },
  mounted() {
    this.obtenerSolicitudPago();
  }
};
</script>