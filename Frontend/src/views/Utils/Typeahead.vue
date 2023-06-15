<template>
    <div :class="[isOpen ? 'typeahead typeahead-open' : 'typeahead']">
        <div class="typeahead-inner">
            <div :class="['typeahead-selected', 'form-control']"
                 ref="toggle" @click="toggle" @keydown="onKey" @focus="open"
                 :title="selected.text">
                <span>{{selected.text | trim(trim)}}</span>
                <span class="typeahead-toggler">
                  <i class="fa fa-angle-up" v-if="isOpen"></i>
                  <i class="fa fa-angle-down" v-else></i>
                </span>
            </div>
            <transition name="fade" mode="out-in">
                <div class="typeahead-dropdown" v-if="isOpen">
                    <div class="typeahead-input_wrap">
                        <input id="input" autocomplete="off" type="text" spellcheck="false"
                               autocorrect="off" autocapitalize="off"
                               class="typeahead-input form-control form-control" ref="search"
                               placeholder="Buscar..."

                               @blur="onBlur" @keydown.esc="onEsc"
                               @input="onSearch" @keydown.down="onDownKey"
                               @keydown.up="onUpKey" @keydown.enter="onEnterKey"

                        >
                        <transition name="fade">
                            <i class="fa fa-spinner fa-pulse typeahead-spinner"
                               v-if="isLoading"></i>
                        </transition>
                    </div>
                    <ul class="typeahead-list" v-if="results.length">
                        <li class="typeahead-item" v-for="(result, index) in results">
                            <a class="ac-result" :class="[selectIndex === index ? 'typeahead-active':'']"
                               @mousedown.prevent="select(result)"
                               @mouseover.prevent="onMouse(index)">{{result.text}}</a>
                        </li>
                    </ul>
                    <div class="typeahead-empty" v-else-if="!isLoading">
                        <small>No se encontraron resultados!</small>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import {BFormInput} from 'bootstrap-vue';
    import {get} from '../../api/api'
    import axios from 'axios'

    export default {
        components: {
            BFormInput
        },
        props: {
            focused: Boolean,
            tabindex: {
                type: Number,
                default: 1
            },
            trim: {
                type: Number,
                default: 45
            },
            url: {
                type: String,
                required: true
            },
            initial: {
                type: Object
            },
            default: {
                type: Object,
                default() {
                    return {id: null, text: 'Escribe para buscar o selecciona de la lista'}
                }
            },
            params: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        data() {
            return {
                isLoading: false,
                selectIndex: -1,
                isOpen: false,
                search: '',
                results: []
            }
        },
        computed: {
            selected() {
                return this.initial
                    ? this.initial
                    : this.default
            },
            abrir() {
                if (this.focused === true) {
                    console.log('entraste');
                    this.open();
                }
            }
        },
        watch: {
            focused(n, v) {
                if (n === true) {
                    this.open();
                }
            }
        },
        methods: {
            onEnterKey(e) {
                const option = this.results[this.selectIndex];

                if (option) {
                    this.select(option);
                }
                const valor = e.target.value;
                setTimeout(() => {
                    if (valor.length >= 7) {
                        this.select(this.results[this.selectIndex]);

                    }
                }, 1500);
            }
            ,
            onDownKey() {
                if (this.results.length - 1 > this.selectIndex) {
                    this.selectIndex++
                }
            }
            ,
            onUpKey() {
                if (this.selectIndex > 0) {
                    this.selectIndex--
                }
            }
            ,
            select(result) {
                this.$emit('input', {
                    target: {
                        value: result
                    }
                });
                this.onEsc();
            }
            ,
            onMouse(index) {
                this.selectIndex = index
            }
            ,
            onBlur() {
                this.close();
            }
            ,
            onEsc() {
                // this.$refs.search.blur()
                this.close();
            }
            ,
            close() {
                this.results = []
                this.isOpen = false
                this.search = ''
                this.selectIndex = -1
            }
            ,
            onSearch(event) {
                const q = event.target.value;
                this.selectIndex = 0;
                this.fetchData(q)
            }
            ,
            toggle() {
                if (this.isOpen) {
                    this.isOpen = false
                } else {
                    this.open()
                }
            }
            ,
            onKey(e) {
                const keyCode = e.keyCode || e.which
                if (!e.shiftKey && keyCode !== 9 && !this.isOpen) {
                    this.open()
                }
            }
            ,
            open() {
                this.isOpen = true;
                this.$nextTick(() => {
                    this.$refs.search.focus()
                });

                this.fetchData('')
            }
            ,
            fetchData(q) {
                this.isLoading = true;
                const params = {
                    q: q,
                    ...this.params
                };
                /*          get(this.url, params)
                              .then((res) => {
                                Vue.set(this.$data, 'results', res.data.results);
                                this.isLoading = false
                              })*/

                if (this.timeoutId) {
                    clearTimeout(this.timeoutId);
                }
                this.timeoutId = setTimeout(() => {

                    axios.get(this.url, {params})
                        .then((res) => {
                            Vue.set(this.$data, 'results', res.data.results);
                            this.isLoading = false
                        });

                }, 500);


            }
            ,
        }
        ,
    }
</script>
<style lang="scss">
    /*
* Style Typeahead
*/
    .typeahead {
        position: relative;
        display: block;
        background: #fff;
    }

    .typeahead-open {
        border-bottom: 0;
    }

    .typeahead-inner {
        position: relative;
    }

    .typeahead-large {
        height: auto !important;
    }

    .typeahead-selected {
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        display: -webkit-box !important;
        display: -ms-flexbox !important;
        display: flex !important;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        line-height: 15px;
        height: 37px;
    }

    .typeahead-toggler {
        padding-left: 5px;
    }

    .typeahead-dropdown {
        width: 100%;
        position: absolute;
        padding-top: 3px;
        z-index: 45;
        padding: 5px;
        background: #fff;
        border-right: 1px solid #eee;
        border-left: 1px solid #eee;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .typeahead-input {
        line-height: 13px;
        font-size: 13px;
        background: #fafafa;
        border: none;
        border-radius: 1px;
        -webkit-box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        padding: 4px 8px;
        width: 100%;
        display: block;
    }

    .typeahead-input:focus {
        outline-style: dotted;
        outline-width: 1px;
        outline-offset: 1px;
        outline-color: #3aa3e3;
    }

    .typeahead-input_wrap {
        position: relative;
    }

    .typeahead-spinner {
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .typeahead-list {
        padding: 5px 0 0 0;
        margin: 0;
    }

    .typeahead-item {
        display: block;
        margin-bottom: 1px;
    }

    .typeahead-item a {
        cursor: pointer;
        display: block;
        padding: 6px 5px;
        background: #fff;
    }

    .typeahead-active {
        background: #3aa3e3 !important;
        color: #fff;
    }

    .typeahead-empty {
        text-align: center;
        margin-top: 5px;
        padding: 6px 10px;
    }

    .typeahead-open .form-control {
        background: #fff;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border: 1 px solid  #eee;
    }
</style>
