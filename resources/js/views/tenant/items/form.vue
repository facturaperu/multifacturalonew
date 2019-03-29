<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción <span class="text-danger">*</span></label>
                            <el-input v-model="form.description" dusk="description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_type_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.unit_type_id" dusk="unit_type_id">
                                <el-option v-for="option in unit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.unit_type_id" v-text="errors.unit_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.currency_type_id" dusk="currency_type_id">
                                <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.sale_unit_price}">
                            <label class="control-label">Precio Unitario (Venta) <span class="text-danger">*</span></label>
                            <el-input v-model="form.sale_unit_price" dusk="sale_unit_price"></el-input>
                            <small class="form-control-feedback" v-if="errors.sale_unit_price" v-text="errors.sale_unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.sale_affectation_igv_type_id}">
                            <label class="control-label">Tipo de afectación (Venta)</label>
                            <el-select v-model="form.sale_affectation_igv_type_id">
                                <el-option v-for="option in affectation_igv_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.sale_affectation_igv_type_id" v-text="errors.sale_affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.internal_id}">
                            <label class="control-label">Código Interno
                                <el-tooltip class="item" effect="dark" content="Código interno de la empresa para el control de sus productos" placement="top-start">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model="form.internal_id" dusk="internal_id"></el-input>
                            <small class="form-control-feedback" v-if="errors.internal_id" v-text="errors.internal_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.item_code}">
                            <label class="control-label">Código Sunat
                                <el-tooltip class="item" effect="dark" content="Código proporcionado por SUNAT, campo obligatorio para exportaciones" placement="top">
                                    <i class="fa fa-info-circle"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model="form.item_code" dusk="item_code"></el-input>
                            <small class="form-control-feedback" v-if="errors.item_code" v-text="errors.item_code[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.calculate_quantity}">
                            <label class="control-label">Calcular cantidad por precio</label>
                            <el-checkbox v-model="form.calculate_quantity">Activo</el-checkbox><br>                            
                            <small class="form-control-feedback" v-if="errors.calculate_quantity" v-text="errors.calculate_quantity[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h5 class="separator-title">Campos adicionales</h5>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.purchase_unit_price}">
                            <label class="control-label">Precio Unitario (Compra)</label>
                            <el-input v-model="form.purchase_unit_price" dusk="purchase_unit_price"></el-input>
                            <small class="form-control-feedback" v-if="errors.purchase_unit_price" v-text="errors.purchase_unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.purchase_affectation_igv_type_id}">
                            <label class="control-label">Tipo de afectación (Compra)</label>
                            <el-select v-model="form.purchase_affectation_igv_type_id">
                                <el-option v-for="option in affectation_igv_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.purchase_affectation_igv_type_id" v-text="errors.purchase_affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-12">
                    </div>
                    <div class="col-md-3" v-show="recordId==null">
                        <div class="form-group" :class="{'has-danger': errors.stock}">
                            <label class="control-label">Stock</label>
                            <el-input v-model="form.stock" ></el-input>
                            <small class="form-control-feedback" v-if="errors.stock" v-text="errors.stock[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.stock_min}">
                            <label class="control-label">Stock Mínimo</label>
                            <el-input v-model="form.stock_min"></el-input>
                            <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="form.warehouses">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Ubicación</th>
                                <th class="text-right">Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in form.warehouses">
                                <th>{{ row.warehouse_description }}</th>
                                <th class="text-right">{{ row.stock }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId', 'external'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'items',
                errors: {},
                form: {},
                unit_types: [],
                currency_types: [],
                system_isc_types: [],
                affectation_igv_types: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.unit_types = response.data.unit_types
                    this.currency_types = response.data.currency_types
                    this.system_isc_types = response.data.system_isc_types
                    this.affectation_igv_types = response.data.affectation_igv_types

                    this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                    this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                })
        },
        methods: {
            initForm() {
                this.loading_submit = false,
                this.errors = {}
                this.form = {
                    id: null,
                    item_type_id: '01',
                    internal_id: null,
                    item_code: null,
                    item_code_gs1: null,
                    description: null,
                    unit_type_id: 'NIU',
                    currency_type_id: 'PEN',
                    sale_unit_price: 0,
                    purchase_unit_price: 0,
                    has_isc: false,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0,
                    sale_affectation_igv_type_id: null,
                    purchase_affectation_igv_type_id: null,
                    calculate_quantity: false,
                    stock: 0,
                    stock_min: 1,
                }
            },
            resetForm() {
                this.initForm()
                this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
                this.form.purchase_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Producto':'Nuevo Producto'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                        })
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if (this.external) {
                                this.$eventHub.$emit('reloadDataItems', response.data.id)
                            } else {
                                this.$eventHub.$emit('reloadData')
                            }
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.resetForm()
            },
            changeHasIsc() {
                this.form.system_isc_type_id = null
                this.form.percentage_isc = 0
                this.form.suggested_price = 0
            },
            changeSystemIscType() {
                if (this.form.system_isc_type_id !== '03') {
                    this.form.suggested_price = 0
                }
            }
        }
    }
</script>