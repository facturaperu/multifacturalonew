<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" append-to-body>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.internal_id}">
                            <label class="control-label">Código Interno</label>
                            <el-input v-model="form.internal_id"></el-input>
                            <small class="form-control-feedback" v-if="errors.internal_id" v-text="errors.internal_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_type_id}">
                            <label class="control-label">Unidad</label>
                            <el-select v-model="form.unit_type_id">
                                <el-option v-for="option in unit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.unit_type_id" v-text="errors.unit_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.item_code}">
                            <label class="control-label">Código Sunat</label>
                            <el-input v-model="form.item_code"></el-input>
                            <small class="form-control-feedback" v-if="errors.item_code" v-text="errors.item_code[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.item_code_gs1}">
                            <label class="control-label">Código GSL</label>
                            <el-input v-model="form.item_code_gs1"></el-input>
                            <small class="form-control-feedback" v-if="errors.item_code_gs1" v-text="errors.item_code_gs1[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                            <label class="control-label">Moneda</label>
                            <el-select v-model="form.currency_type_id">
                                <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_price}">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.unit_price"></el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" :class="{'has-danger': errors.has_isc}">
                            <label class="control-label d-block">¿Tiene ISC?</label>
                            <el-switch
                                    v-model="form.has_isc"
                                    active-text="Si"
                                    inactive-text="No"
                                    @change="changeHasIsc">
                            </el-switch>
                            <small class="form-control-feedback" v-if="errors.has_isc" v-text="errors.has_isc[0]"></small>
                        </div>
                    </div>
                    <template v-if="form.has_isc">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.system_isc_type_id}">
                                <label class="control-label">Sistema Isc</label>
                                <el-select v-model="form.system_isc_type_id" filterable>
                                    <el-option v-for="option in system_isc_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.system_isc_type_id" v-text="errors.system_isc_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.percentage_isc}">
                                <label class="control-label">Porcentaje Isc</label>
                                <el-input v-model="form.percentage_isc"></el-input>
                                <small class="form-control-feedback" v-if="errors.percentage_isc" v-text="errors.percentage_isc[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" :class="{'has-danger': errors.suggested_price}">
                                <label class="control-label">Precio sugerido</label>
                                <el-input v-model="form.suggested_price"></el-input>
                                <small class="form-control-feedback" v-if="errors.suggested_price" v-text="errors.suggested_price[0]"></small>
                            </div>
                        </div>
                    </template>
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
                system_isc_types: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.unit_types = response.data.unit_types
                    this.currency_types = response.data.currency_types
                    this.system_isc_types = response.data.system_isc_types
                })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    item_type_id: '01',
                    internal_id: null,
                    item_code: null,
                    item_code_gs1: null,
                    description: null,
                    unit_type_id: null,
                    currency_type_id: null,
                    unit_price: null,
                    has_isc: null,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0
                }
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
                                this.$eventHub.$emit('reloadDataItems')
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
                            this.errors = error.response.data.errors
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
                this.initForm()
            },
            changeHasIsc() {
                this.form.system_isc_type_id = false
                this.form.percentage_isc = 0
                this.form.suggested_price = 0
            },
        }
    }
</script>