<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                            <label class="control-label">Fecha Esmisión</label>
                            <el-date-picker v-model="form.date_of_issue" type="date" :clearable="false" value-format="yyyy-MM-dd"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.date_of_reference}">
                            <label class="control-label">Fecha Referencia</label>
                            <el-date-picker v-model="form.date_of_reference" type="date" :clearable="false" value-format="yyyy-MM-dd" @change="changeDateOfReference"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_of_reference" v-text="errors.date_of_reference[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end justify-content-end pt-2">
                        <div class="form-group">
                            <button type="button" class="btn waves-effect waves-light btn-info" @click.prevent="clickSearchDocuments">Buscar comprobantes</button>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="form.documents.length > 0">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Número</th>
                                    <th class="text-right">Total Gravado</th>
                                    <th class="text-right">Total Igv</th>
                                    <th class="text-right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.documents">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ row.number }}<br/>
                                        <small v-text="row.document_type_description"></small>
                                    </td>
                                    <td class="text-right">{{ row.total_taxed }}</td>
                                    <td class="text-right">{{ row.total_igv }}</td>
                                    <td class="text-right">{{ row.total }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.documents.length > 0">Guardar</el-button>
            </div>
        </form>
    </el-dialog>

    <!--<el-dialog :title="$t('summaries.titles.new')" :visible="showDialog" @close="close">-->
        <!--<form class="columns is-multiline" @submit.prevent="submit" autocomplete="off">-->
            <!--<div class="column is-4">-->
                <!--<my-control :label="$t('fields.client_id')" cfor="client_id" :errors="errors" error-field="client_id">-->
                    <!--<el-select v-model="form.user_id" id="client_id" filterable>-->
                        <!--<el-option v-for="option in clients" :key="option.user_id" :value="option.user_id" :label="option.company_name"></el-option>-->
                    <!--</el-select>-->
                <!--</my-control>-->
            <!--</div>-->
            <!--<div class="column is-3">-->
                <!--<my-control :label="$t('fields.date_of_issue')" cfor="date_of_issue" :errors="errors" error-field="date_of_issue">-->
                    <!--<el-date-picker v-model="form.date_of_issue" type="date" :clearable="false" value-format="yyyy-MM-dd"></el-date-picker>-->
                <!--</my-control>-->
            <!--</div>-->
            <!--<div class="column is-3">-->
                <!--<my-control :label="$t('fields.date_of_reference')" cfor="date_of_reference" :errors="errors" error-field="date_of_reference">-->
                    <!--<el-date-picker v-model="form.date_of_reference" type="date" :clearable="false" value-format="yyyy-MM-dd"></el-date-picker>-->
                <!--</my-control>-->
            <!--</div>-->
            <!--<div class="column is-narrow">-->
                <!--<el-button type="primary" icon="el-icon-search" @click="searchDocuments" :loading="loading_search">{{ $t('buttons.search')}} </el-button>-->
            <!--</div>-->
            <!--<template v-if="form.documents.length > 0">-->
                <!--<div class="column is-12">-->
                    <!--<table class="table is-fullwidth">-->
                        <!--<thead>-->
                        <!--<tr>-->
                            <!--<th>#</th>-->
                            <!--<th>{{ $t('fields.date_of_issue') }}</th>-->
                            <!--<th>{{ $t('fields.number') }}</th>-->
                            <!--<th>{{ $t('fields.total') }}</th>-->
                        <!--</tr>-->
                        <!--</thead>-->
                        <!--<tbody>-->
                        <!--<tr v-for="(row, index) in form.documents">-->
                            <!--<td>{{ index + 1 }}</td>-->
                            <!--<td>{{ row.date_of_issue }}</td>-->
                            <!--<td>{{ row.document_number }}</td>-->
                            <!--<td>{{ row.total }}</td>-->
                        <!--</tr>-->
                        <!--</tbody>-->
                    <!--</table>-->
                <!--</div>-->
            <!--</template>-->
            <!--<template v-else>-->
                <!--<div class="column is-12">-->
                    <!--{{ $t('labels.no_results_found') }}-->
                <!--</div>-->
            <!--</template>-->
            <!--<div class="column is-12 has-text-right">-->
                <!--<el-button type="default" @click.prevent="close">{{ $t('buttons.cancel') }}</el-button>-->
                <!--<el-button type="primary"-->
                           <!--:loading="submit_loading"-->
                           <!--native-type="submit"-->
                           <!--v-if="form.documents.length > 0">{{ $t('buttons.generate') }}</el-button>-->
            <!--</div>-->
        <!--</form>-->
    <!--</el-dialog>-->
</template>
<script>

//    import {formable} from '../../mixins/formable'
//    import {post} from '../../helpers/functions'
//
    export default {
//        mixins: [formable],
        props: ['showDialog'],
        data () {
            return {
                loading_submit: false,
                loading_search: false,
                titleDialog: null,
                resource: 'summaries',
                errors: {},
                form: {},
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    process_type_id: '01',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    date_of_reference: moment().format('YYYY-MM-DD'),
                    documents: [],
                }
            },
            create() {
                this.titleDialog = 'Registrar Resumen'
            },
            clickSearchDocuments() {
                this.loading_search = true
                this.$http.post(`/${this.resource}/documents`, {
                    'date_of_reference': this.form.date_of_reference
                })
                    .then((response) => {
                        this.form.documents = response.data.data
                        if (this.form.documents.length === 0) {
                            this.$message.info('No se encontraron resultados')
                        }
                    })
                    .catch((error) => {
                        this.$message.error(error.response.data.message)
                    })
                    .then(() => {
                        this.loading_search = false
                    })
            },
            changeDateOfReference() {
                this.form.documents = []
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
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
        }
    }
</script>
