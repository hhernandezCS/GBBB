<template>
    <b-card>
        <form enctype="multipart/form-data">
            <b-row>

<!--                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="tipo_producto"> Tipo:</label>
                        <b-form-select @change="seleccionaTipo()" id="tipo_producto"
                                       v-model.number="form.tipo_producto">
                            <option value="1">Producto</option>
                            <option value="2">Servicio</option>
                            &lt;!&ndash;                        <option value="4">Bienes</option>&ndash;&gt;
                        </b-form-select>
                    </div>
                </div>-->

                <template v-if="form.tipo_producto === 1">


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">* Descripción</label>
                            <input class="form-control" placeholder="Descripción" v-model="form.descripcion">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.descripcion"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <b-form-group>
                            <label for=""> Unidad de medida</label>
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :disabled="!tipoProducto"
                                    :options="ums"
                                    label="siglas"
                                    v-model="form.unidad_medida"
                                    :clearable="false"

                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.unidad_medida"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>
                    <div class="col-sm-4">
                        <b-form-group>
                            <label for=""> Grupos</label>
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="grupos"
                                    @input="seleccionargrupo"
                                    label="descripcion"
                                    v-model="form.grupo"
                                    :clearable="false"
                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.grupo"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>

                    <div class="col-sm-4">
                        <b-form-group>
                            <label for=""> Sub-Grupos</label>
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="subgrupos"
                                    label="descripcion"
                                    v-model="form.subgrupo"
                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.subgrupo"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>

                    <div class="col-sm-4">
                        <b-form-group>
                            <label for=""> Marcas</label>


                            <a
                                    @click="abrirModal"
                                    class="btn-agregar col-sm-3"
                                    v-b-tooltip.hover.top="'Registrar nueva marca '"
                                    variant="success"
                            >
                                <feather-icon icon="PlusCircleIcon"></feather-icon>
                                Nueva
                            </a>


                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="marcas"
                                    label="descripcion"
                                    v-model="form.marca"
                                    v-on:input="obtenerCodigo"

                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.marca"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>

                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Código Barras</label>
                            <input class="form-control" placeholder="Código Barras"
                                   v-model="form.codigo_barra">
                            <barcode :format="formatx" :height="heightx" :marginTop="marginTopx"
                                     :textPosition="textPositionx" :width="widthx" v-bind:value="form.codigo_barra">
                                Generando Código de Barras
                            </barcode>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.codigo_barra"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>


                    <!--          <div class="col-sm-3">
                                <div class="form-group">
                                  <label for="">* Nombre {{ form.tipo_producto === 1 ? 'Producto' : 'Servicio' }}</label>
                                  <input class="form-control" placeholder="Nombre" v-model="form.nombre_comercial">
                                  <b-alert show variant="danger">
                                    <ul class="error-messages">
                                      <li
                                          :key="message"
                                          v-for="message in errorMessages.nombre_comercial"
                                          v-text="message"
                                      ></li>
                                    </ul>
                                  </b-alert>
                                </div>
                              </div>-->


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Código</label>
                            <input class="form-control" placeholder="Código Sistema" v-model="form.codigo_sistema" readonly>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.codigo_sistema"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Existencia Mínima</label>
                            <input :disabled="!tipoProducto" class="form-control" min="0"
                                   placeholder="Existencia Mínima"
                                   type="number" v-model.number="form.existencia_min">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.existencia_min"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Existencia Máxima</label>
                            <input :disabled="!tipoProducto" class="form-control" min="0"
                                   placeholder="Existencia Máxima"
                                   type="number" v-model.number="form.existencia_max">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.existencia_max"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Impuesto</label>
                            <v-select :options="impuestos" label="descripcion" v-model="form.impuesto_producto" :clearable="false"/>
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.impuesto_producto"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </div>
<!--                    <div class="col-sm-4">
                        <label for="aplicar_porcentaje"></label>
                        <b-form-checkbox
                                id="aplicar_porcentaje"
                                v-model="form.aplica_porcentaje_ganancia"
                                name="aplica_porcentaje"
                                @input="resetValue()"
                        >
                            Aplica porcentaje de ganancia
                        </b-form-checkbox>
                    </div>-->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Precio de compra C$</label>
                            <input class="form-control" min="0" placeholder="Costo de compra" type="number"
                                   v-model.number="form.precio_compra">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.precio_compra"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

<!--                    <template v-if="!form.aplica_porcentaje_ganancia">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""> Porcentaje Ganacia</label>
                                <input class="form-control" min="0" :disabled="!form.aplica_porcentaje_ganancia" placeholder="Porcentaje Ganancia" type="number"
                                       v-model.number="calcular_porcentaje">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.porcentaje_ganancia"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>

                        </div>
                    </template>
                    <template v-else>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""> Porcentaje Ganacia</label>
                                <input class="form-control" min="0" :disabled="!form.aplica_porcentaje_ganancia" placeholder="Porcentaje Ganancia" type="number"
                                       v-model.number="form.porcentaje_ganancia">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.porcentaje_ganancia"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>

                        </div>
                    </template>-->
<!--                    <template v-if="form.aplica_porcentaje_ganancia">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""> Precio de venta C$</label>
                                <input class="form-control" min="1" :disabled="form.aplica_porcentaje_ganancia" placeholder="Precio de venta" type="number"
                                       v-model.number="calcular_precio">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.precio_sugerido"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""> Precio de venta C$</label>
                                <input class="form-control" min="1" :disabled="form.aplica_porcentaje_ganancia" placeholder="Precio de venta" type="number"
                                       v-model.number="form.precio_sugerido">
                                <b-alert show variant="danger">
                                    <ul class="error-messages">
                                        <li
                                                :key="message"
                                                v-for="message in errorMessages.precio_sugerido"
                                                v-text="message"
                                        ></li>
                                    </ul>
                                </b-alert>
                            </div>
                        </div>
                    </template>-->

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Precio de venta C$</label>
                            <input class="form-control" min="1" placeholder="Precio de venta" type="number"
                                   v-model.number="form.precio_sugerido">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.precio_sugerido"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for=""> Precio de venta a Distribuidor C$</label>
                        <input class="form-control" min="1" placeholder="Precio de venta distribuidor" type="number"
                               v-model.number="form.precio_distribuidor">
                        <b-alert show variant="danger">
                          <ul class="error-messages">
                            <li
                                :key="message"
                                v-for="message in errorMessages.precio_distribuidor"
                                v-text="message"
                            ></li>
                          </ul>
                        </b-alert>
                      </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""> Imagen</label>
                            <div class="uploader">
                                <b-form-file @change="handleFileObject" accept="image/jpeg, image/png, image/gif"
                                             id="customFile"
                                             ref="file" v-model="form.imagen"/>
                            </div>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.imagen"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <template v-if="form.imagen">
                            <b-container class="p-1 bg-dark" fluid>
                                <b-row>
                                    <b-col>
                                        <b-img :src="get_avatar()" alt="imagen del producto')" fluid-grow thumbnail/>
                                    </b-col>
                                </b-row>
                            </b-container>
                        </template>

                    </div>
                </template>


                <template v-if="form.tipo_producto === 2">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tipo_servicio"> Tipo de servicio:</label>
                            <b-form-select id="tipo_servicio" v-model.number="form.tipo_servicio">
                                <option value="1">Servicios de infraestrutura</option>
                                <option value="2">Sistema Soportes</option>
                                <!--                        <option value="4">Bienes</option>-->
                            </b-form-select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">* Descripción</label>
                            <input class="form-control" placeholder="Descripción" v-model="form.descripcion">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.descripcion"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <b-form-group>
                            <label for=""> Unidad de medida</label>
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"

                                    :options="ums"
                                    label="siglas"
                                    v-model="form.unidad_medida"
                                    :clearable="false"
                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.unidad_medida"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>

                    <div class="col-sm-3">
                        <b-form-group>
                            <label for=""> Grupos</label>
                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="grupos"
                                    @input="seleccionargrupo"
                                    label="descripcion"
                                    v-model="form.grupo"
                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.grupo"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>

                    <div class="col-sm-3">
                        <b-form-group>
                            <label for=""> Sub Grupos</label>
                            <v-select

                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="subgrupos"
                                    label="descripcion"
                                    v-model="form.subgrupo"
                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.subgrupo"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>

                    <div class="col-sm-3">
                        <b-form-group>
                            <label for=""> Marcas</label>

                            <v-select
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                    :options="marcas"
                                    label="descripcion"
                                    v-model="form.marca"
                                    v-on:input="obtenerCodigo"
                            />
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.marca"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </b-form-group>
                    </div>


                    <!--          <div class="col-sm-3">
                                <div class="form-group">
                                  <label for="">* Nombre {{ form.tipo_producto === 1 ? 'Producto' : 'Servicio' }}</label>
                                  <input class="form-control" placeholder="Nombre" v-model="form.nombre_comercial">
                                  <b-alert show variant="danger">
                                    <ul class="error-messages">
                                      <li
                                          :key="message"
                                          v-for="message in errorMessages.nombre_comercial"
                                          v-text="message"
                                      ></li>
                                    </ul>
                                  </b-alert>
                                </div>
                              </div>-->


                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Código Barras</label>
                            <input :disabled="!tipoProducto" class="form-control" placeholder="Código Barras"
                                   v-model="form.codigo_barra">
                            <barcode :format="formatx" :height="heightx" :marginTop="marginTopx"
                                     :textPosition="textPositionx" :width="widthx" v-bind:value="form.codigo_barra">
                                Generando Código de Barras
                            </barcode>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.codigo_barra"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Código</label>
                            <input class="form-control" placeholder="Código Sistema" v-model="form.codigo_sistema">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.codigo_sistema"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Existencia Mínima</label>
                            <input :disabled="!tipoProducto" class="form-control" min="0"
                                   placeholder="Existencia Mínima"
                                   type="number" v-model.number="form.existencia_min">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.existencia_min"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Existencia Máxima</label>
                            <input :disabled="!tipoProducto" class="form-control" min="0"
                                   placeholder="Existencia Máxima"
                                   type="number" v-model.number="form.existencia_max">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.existencia_max"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <!--          <div class="col-sm-3">
                                <div class="form-group">
                                  <label for=""> Costo Unitario $</label>
                                  <input class="form-control" min="1" placeholder="Costo Estándar" type="number"
                                         v-model.number="form.costo_estandar">
                                  <b-alert show variant="danger">
                                    <ul class="error-messages">
                                      <li
                                          :key="message"
                                          v-for="message in errorMessages.costo_estandar"
                                          v-text="message"
                                      ></li>
                                    </ul>
                                  </b-alert>
                                </div>
                              </div>-->
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Precio de compra C$</label>
                            <input class="form-control" min="0" placeholder="Costo de compra" type="number"
                                   v-model.number="form.precio_compra">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.precio_compra"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

<!--                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Porcentaje Ganacia</label>
                            <input class="form-control" min="0" placeholder="Porcentaje Ganancia" type="number"
                                   v-model.number="form.porcentaje_ganancia" >
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.porcentaje_ganancia"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>

                    </div>-->

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Precio de venta C$</label>
                            <input class="form-control" min="1" placeholder="Precio de venta" type="number"
                                   v-model.number="form.precio_sugerido">
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.precio_sugerido"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Impuesto</label>
                            <v-select :options="impuestos" label="descripcion" v-model="form.impuesto_producto" :clearable="false" />
                            <ul class="error-messages">
                                <li :key="message" v-for="message in errorMessages.impuesto_producto"
                                    v-text="message"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for=""> Imagen</label>
                            <div class="uploader">
                                <b-form-file @change="handleFileObject" accept="image/jpeg, image/png, image/gif"
                                             id="customFile"
                                             ref="file" v-model="form.imagen"/>
                            </div>
                            <b-alert show variant="danger">
                                <ul class="error-messages">
                                    <li
                                            :key="message"
                                            v-for="message in errorMessages.imagen"
                                            v-text="message"
                                    ></li>
                                </ul>
                            </b-alert>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <template v-if="form.imagen">
                            <b-container class="p-1 bg-dark" fluid>
                                <b-row>
                                    <b-col>
                                        <b-img :src="get_avatar()" alt="imagen del producto')" fluid-grow thumbnail/>
                                    </b-col>
                                </b-row>
                            </b-container>
                        </template>

                    </div>
                </template>


                <!--
                        <div class="col-sm-3">
                          <div class="form-group">
                            <label for=""> Precio de venta a Distribuidor $</label>
                            <input class="form-control" min="1" placeholder="Precio de venta distribuidor" type="number"
                                   v-model.number="form.precio_distribuidor">
                            <b-alert show variant="danger">
                              <ul class="error-messages">
                                <li
                                    :key="message"
                                    v-for="message in errorMessages.precio_distribuidor"
                                    v-text="message"
                                ></li>
                              </ul>
                            </b-alert>
                          </div>
                        </div>
                -->

                <!--<div class="col-sm-3">
                    <div class="form-group">
                        <label for=""> Inventario Inicial</label>
                        <input class="form-control" :disabled="!tipoProducto" type="number" min="0"
                               v-model.number="form.cantidad_inicial" placeholder="Inventario Inicial">
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.cantidad_inicial" :key="message" v-text="message"></li>
                        </ul>
                    </div>
                </div>-->

                <!--<div class="col-sm-3">
                    <div class="form-group">
                        <label for>Bodega</label>
                        <v-select
                                :disabled="!tipoProducto"
                                v-model="form.bodega_inicial"
                                label="descripcion"
                                :options="bodegas"
                        ></v-select>
                        <ul class="error-messages">
                            <li v-for="message in errorMessages.bodega_inicial" :key="message" v-text="message"></li>
                        </ul>
                    </div>
                </div>-->
                <!-- Modal - registro basico cliente -->

                <div>
                    <b-modal hide-footer id="modal-select2" ref="modal" size="lg" title="Registrar Marca">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">* Nombre Marca:</label>
                                    <input class="form-control" placeholder="Dígite la marca" type="text"
                                           v-model="formMarcas.descripcion">
                                    <b-alert show variant="danger">
                                        <ul class="error-messages">
                                            <li v-for="message in errorMessages.descripcion">{{ message }}</li>
                                        </ul>
                                    </b-alert>

                                </div>
                            </div>

                        </div>

                        <div class="content-box-footer text-right">
                            <b-button @click="cerrarModal" class="mx-lg-1 mx-0" variant="secondary">Cancelar</b-button>
                            <b-button :disabled="btnActionMarca !== 'Registrar Marca'"
                                      @click="registrarMarca" class="mx-lg-1 mx-0"
                                      type="button"
                                      variant="primary">{{ btnActionMarca }}
                            </b-button>
                        </div>
                    </b-modal>
                </div>


                <!-- Fin Modal - registro basico cliente -->

            </b-row>
        </form>
        <b-card-footer>
            <div class="text-right">
                <router-link :to="{ name: 'inventario-productos' }">
                    <b-button class="mx-1" type="button" variant="secondary">Cancelar</b-button>
                </router-link>
                <b-button :disabled="btnAction !== 'Registrar' ? true : false" @click="registrarProducto"
                          variant="primary">{{ btnAction }}
                </b-button>
            </div>

            <template v-if="loading">
                <BlockUI :url="url"></BlockUI>
            </template>
        </b-card-footer>
    </b-card>
</template>

<script type="text/ecmascript-6">
  import loadingImage from '../../../assets/images/loader/block50.gif'
  import VueBarCode from 'vue-barcode'
  //import Pagination from '../layout/Pagination'
  import {
    BAlert,
    BButton,
    BCard,
    BCardFooter,
    BCol,
    BContainer,
    BFormCheckbox,
    BFormCheckboxGroup,
    BFormFile,
    BFormGroup,
    BFormSelect,
    BImg,
    BPaginationNav,
    BRow,
    VBTooltip
  } from 'bootstrap-vue'
  import ToastificationContent from "../../../@core/components/toastification/ToastificationContent";
  import vSelect from 'vue-select'
  import producto from "../../../api/Inventario/productos";
  import grupos from "@/api/Inventario/grupo";
  import subgrupo from "@/api/Inventario/subgrupo";
  import impuestos from "../../../api/admon/impuestos";
  import marcas from "../../../api/Inventario/marcas"
  import Round from "@/assets/custom-scripts/Round";

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
      BAlert,
      'barcode': VueBarCode,
      BFormFile,
      BImg,
      BContainer,
      BCol,
      BFormSelect,
    },
    directives: {
      'b-tooltip': VBTooltip,
    },
    data() {
      return {
        loading: true,
        msg: 'Guardando los datos, espere un momento',
        url: loadingImage,   //It is important to import the loading image then use here
        textPositionx: 'center',
        heightx: 25,
        widthx: 1,
        marginTopx: 0,
        formatx: 'CODE39',
        tipoProducto: true,
        grupos: [],
        subgrupos: [],
        impuestos: [],
        ums: [],
        marcas: [],
        bodegas: [],
        formMarcas: {
          estado: 1,
          descripcion: '',

        },
        form: {
          precio_compra: 0,
          precio_distribuidor: 0,
          codigo_sistema: '',
          codigo_consecutivo: 0,
          nombre_comercial: '',
          descripcion: '',
          costo_estandar: 0,
          precio_sugerido: 0,
          existencia_min: 1,
          existencia_max: 1,
          cantidad_inicial: 0,
          tipo_producto: 1,
          tipo_servicio: 1,
          codigo_barra: '',
          imagen: null,
          impuesto_producto: [],
          unidad_medida: [],
          porcentaje_ganancia: 0,
          marca: [],
          grupo: {},
          subgrupo: [],
          bodega_inicial: "",
          avatar: '',
          avatarName: '',
          aplica_porcentaje_ganancia: false,
        },
        uploader: [],
        btnAction: 'Registrar',
        errorMessages: [],
        btnActionMarca: 'Registrar Marca',
      }
    },

    methods: {
      handleFileObject(e) {
        let file = e.target.files[0];
        let reader = new FileReader();

        if (file['size'] < 2111775) {
          reader.onloadend = (file) => {
            //console.log('RESULT', reader.result)
            this.form.avatar = reader.result;
          }
          reader.readAsDataURL(file);
        } else {
          alert('El tamaño del archivo no puede ser superior a 2 MB')
        }
      },
      //For getting Instant Uploaded Photo
      get_avatar() {
        let photo = (this.form.avatar.length > 100) ? this.form.avatar : "img/products/" + this.form.avatar;
        return photo;
      },

      nueva() {
        const self = this;
        producto.nuevo({}, data => {
              // self.proveedores = data.proveedores;
              self.ums = data.unidades_medida;
              self.form.unidad_medida = self.ums[0];
              self.marcas = data.marcas;
              self.form.marca = self.marcas[0];
              self.grupos = data.grupos;
              self.form.grupo = self.grupos[0];
              self.seleccionargrupo();
              self.form.subgrupo = self.subgrupos[0];
              self.loading = false;
            },
            err => {
              this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: 'Notificación',
                      icon: 'InfoIcon',
                      text: 'Ha ocurrido un error:' + err,
                      variant: 'danger',
                      position: 'bottom-right'
                    }
                  },
                  {
                    position: 'bottom-right'
                  });
              self.loading = false;
            })

      },

      obtenerCodigo() {
        var self = this;
        producto.obtenerCodigoProducto({}, data => {
          //console.log(data);
          self.form.codigo_consecutivo = data.codigo_siguiente;
          self.obtenerConcatenarCodigo();
        }, err => {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: err,
                  variant: 'danger',
                  position: 'bottom-right'
                }
              },
              {
                position: 'bottom-right'
              });
        })
      },
      seleccionaTipo() {
        var self = this;
        if (self.form.tipo_producto === 1) {
          self.tipoProducto = true;
          self.form.costo_estandar = 0;
          self.form.precio_sugerido = 0;
          self.form.existencia_min = 0;
          self.form.existencia_max = 1;
          self.form.cantidad_inicial = 0;
          self.form.codigo_barra = 'N/A';
          self.form.imagen = '';
          self.form.grupo_producto = [];
          self.form.impuesto_producto = '';
          self.form.unidad_medida = [];
          self.ums.forEach((umx, indice) => {
            if (umx.id_unidad_medida === 1) {
              self.form.unidad_medida = self.ums[indice];
            }
          });

        } else if (self.form.tipo_producto === 2) {
          self.tipoProducto = false;
          self.form.costo_estandar = 0;
          self.form.precio_sugerido = 0;
          self.form.existencia_min = 1;
          self.form.existencia_max = 1;
          self.form.cantidad_inicial = 0;
          self.form.codigo_barra = '';
          self.form.imagen = '';
          self.form.grupo_producto = [];
          self.form.impuesto_producto = '';
          self.form.unidad_medida = [];
          self.ums.forEach((umx, indice) => {
            if (umx.id_unidad_medida === 1) {
              self.form.unidad_medida = self.ums[indice];
            }
          });

        } else {
          self.tipoProducto = true;
          self.form.costo_estandar = 0;
          self.form.precio_sugerido = 0;
          self.form.existencia_min = 0;
          self.form.existencia_max = 1;
          self.form.cantidad_inicial = 0;
          self.form.codigo_barra = 'N/A';
          self.form.imagen = '';
          self.form.grupo_producto = [];
          self.form.impuesto_producto = '';
          self.form.unidad_medida = [];
        }
      },
      /*obtenerTodasBodegas() {
          var self = this;
          bodega.obtenerTodasBodegas(
                  data => {
                      self.bodegas = data;
                      self.form.bodega_inicial=self.bodegas[0];
                  },
                  err => {
                      console.log(err);
                  }
          );
      },*/
      obtenerConcatenarCodigo() {
        let self = this;
        let descripcionGrupo = self.form.grupo.descripcion;
        let codigo = descripcionGrupo.substring(0, 3).toUpperCase();
        self.form.codigo_sistema = codigo + self.form.codigo_consecutivo;
      },


      obtenerTodosImpuestos() {
        const self = this;
        impuestos.obtenerTodosImpuestos(data => {
          self.impuestos = data;
          self.form.impuesto_producto = self.impuestos[0];
        }, err => {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'No se han podido cargar los impuestos correctamente.',
                  variant: 'warning',
                }
              },
              {
                position: 'bottom-right'
              });
        })
      },

      /*obtenerTodosgruposPS() {
          var self = this
          grupo.obtenerTodosgruposPS(data => {
              self.grupos = data
          }, err => {
              console.log(err)
          })
      },

      obtenerTodosUnidadMedida() {
          var self = this
          um.obtenerTodas(data => {
              self.ums = data
          }, err => {
              console.log(err)
          })
      },*/
      registrarProducto() {
        const self = this;
        self.btnAction = 'Registrando, espere....'
        self.loading = true;
        producto.registrarProducto(self.form, data => {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'CheckIcon',
                  text: 'Producto registrado correctamente',
                  variant: 'success',
                  position: 'bottom-right'
                }
              },
              {
                position: 'bottom-right'
              });
          self.loading = false;
          this.$router.push({
            name: 'inventario-productos'
          })
        }, {headers: {'content-type': 'multipart/form-data'}}, err => {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: err,
                  variant: 'danger',
                  position: 'bottom-right'
                }
              },
              {
                position: 'bottom-right'
              });
          self.loading = false;
          self.errorMessages = err;
          self.btnAction = 'Registrar'
        })
      },

      /**
       * Obtener todos los grupos
       */
      obtenerTodosgrupos() {
        var self = this;
        self.loading = true;
        grupos.obtenerTodos(data => {
          self.grupos = data;
          self.loading = false;
        }, err => {
          self.loading = false;
          console.log(err);
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: err,
                  variant: 'danger',
                  position: 'bottom-right'
                }
              },
              {
                position: 'bottom-right'
              });
        })
      },
      /**
       * Obtener todos los subgrupos
       */
      obtenerTodosSubgrupos() {
        var self = this;
        self.loading = true;
        subgrupo.obtenerTodos(data => {
          self.subgrupos = data;
          self.loading = false;
        }, err => {
          self.loading = false;
          console.log(err);
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: err,
                  variant: 'danger',
                  position: 'bottom-right'
                }
              },
              {
                position: 'bottom-right'
              });
        })
      },
      /**
       * Cargar subgrupo por cada grupo
       */
      obtenerSubrurogrupo(e) {
        const self = this;
        self.loading = true;
        subgrupo.obtenerGrupossubgrupo({
          id_grupo: self.form.grupo,
        }, data => {
          if (data !== null) {
            self.subgrupos = data;
            self.form.subgrupo = data[0];
            self.loading = false;
          } else {
            this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: 'Notificación',
                    icon: 'CheckIcon',
                    text: 'No se encuentran proyectos para este cliente.',
                    variant: 'warning',
                  }
                },
                {
                  position: 'bottom-right'
                });
            self.loading = false;
          }
          self.loading = false;
        }, err => {
          /*if(err.codigo_bateria){
            self.detalleForm.bateria_busqueda = '';
            self.$refs.bateria.focus();
            alertify.warning("No se encuentra esta batería.",5);
            self.detalleForm.cuentax = '';
          }*/
          self.loading = false;
        });

      },

      /**
       * Seleccionar grupo
       */

      seleccionargrupo(e) {
        const self = this;

        self.obtenerSubrurogrupo();
      },

      abrirModal() {
        this.$refs['modal'].show()
      },
      cerrarModal() {
        this.$refs['modal'].hide()
      },

      registrarMarca() {
        const self = this;
        self.btnActionMarca = 'Registrando, espere....';

        if (!self.registrandoMarca) self.registrandoMarca = true;
        marcas.registrar(self.formMarcas, data => {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: 'Marca registrada exitosamente.',
                  variant: 'success',

                }
              },
              {
                position: 'bottom-right'
              });
          self.form.marca = data;
          self.btnActionMarca = 'Registrar Marca';
          // self.form.factura_vendedor
          self.registrandoMarca = false;

          self.formMarcas.estado = 1;
          self.formMarcas.descripcion = '';


          self.$refs['modal'].hide()
        }, err => {
          this.$toast({
                component: ToastificationContent,
                props: {
                  title: 'Notificación',
                  icon: 'InfoIcon',
                  text: 'Ha ocurrido un error. ' + err,
                  variant: 'warning',

                }
              },
              {
                position: 'bottom-right'
              });
          self.errorMessages = err;
          self.registrandoMarca = false;
          self.btnActionMarca = 'Registrar Marca'
        })
      },
      resetValue(){
        //Reiniciar valores de variables al cambio el status de aplica calculo de porcentaje ganancia
        //para evitar recalculos
        this.form.porcentaje_ganancia = 0;
        this.form.precio_sugerido = 0;
      },

      /*initSelect2() {
          $('.select2').select2()
      }*/
    },
    computed:{
      calcular_precio() {
        if (this.form.aplica_porcentaje_ganancia) {
          return this.form.precio_sugerido = Round.round(Number((Number(this.form.precio_compra) * Number(this.form.porcentaje_ganancia / 100)) + Number(this.form.precio_compra)), 2);
        }
      },
      calcular_porcentaje(){
        if(!this.form.aplica_porcentaje_ganancia){
          return this.form.porcentaje_ganancia = Round.round((Number(this.form.precio_sugerido - this.form.precio_compra) / Number(this.form.precio_compra)) * 100, 2);
        }
      }
    },
    mounted() {
      //this.initUploader()
      this.nueva();
      this.obtenerTodosgrupos();
      this.obtenerTodosSubgrupos();
      this.obtenerTodosImpuestos();
        this.obtenerCodigo();

        this.$watch('form.grupo', (newVal, oldVal) => {
            // Llamar a la función obtenerConcatenarCodigo() cuando se detecte un cambio
            this.obtenerConcatenarCodigo();
        });
    }
  }
</script>

<style lang="scss">


    @import '@core/scss/vue/libs/vue-select.scss';

</style>
