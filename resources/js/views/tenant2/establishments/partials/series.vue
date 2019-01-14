<template>
    <el-dialog title="Series" :visible="showDialog" @close="close" @open="getData">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12" v-if="records.length > 0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Tipo de documento</th>
                                <th>Número</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in records">
                                <template v-if="row.id">
                                    <td>{{ row.document_type_description }}</td>
                                    <td>{{ row.number }}</td>
                                    <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                                        <!--<el-button type="danger" icon="el-icon-delete" plain @click.prevent="clickDelete(row.id)"></el-button>-->
                                    </td>
                                </template>
                                <template v-else>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.document_type_id}">
                                            <el-select v-model="row.document_type_id">
                                                <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>
                                            <small class="form-control-feedback" v-if="row.errors.document_type_id" v-text="row.errors.document_type_id[0]"></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0" :class="{'has-danger': row.errors.number}">
                                            <el-input v-model="row.number" :maxlength="4"></el-input>
                                            <small class="form-control-feedback" v-if="row.errors.number" v-text="row.errors.number[0]"></small>
                                        </div>
                                    </td>
                                    <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickSubmit(index)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 text-center pt-2" v-if="showAddButton">
                    <el-button type="primary" icon="el-icon-plus" @click="clickAddRow">Nuevo</el-button>
                </div>
            </div>
        </div>
    </el-dialog>

</template>

<script>

    import {deletable} from '../../../../mixins/deletable'

    export default {
        props: ['showDialog', 'establishmentId'],
        mixins: [deletable],
        data() {
            return {
                resource: 'series',
                records: [],
                document_types: [],
                showAddButton: true,
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.document_types = response.data.document_types
                })
        },
        methods: {
            initForm() {
                this.records = []
                this.showAddButton = true
            },
            async getData() {
                if (this.establishmentId) {
                    await this.$http.get(`/${this.resource}/records/${this.establishmentId}`)
                        .then(response => {
                            if (response.data !== '') {
                                this.records = response.data.data
                            }
                        })
                }
            },
            clickAddRow() {
                this.records.push({
                    id: null,
                    document_type_id: null,
                    number: null,
                    errors: {},
                    loading: false
                })
                this.showAddButton = false
            },
            clickCancel(index) {
                this.records.splice(index, 1)
                this.showAddButton = true
            },
            clickSubmit(index) {
                let form = {
                    id: this.records[index].id,
                    establishment_id: this.establishmentId,
                    document_type_id: this.records[index].document_type_id,
                    number: this.records[index].number,
                }
                this.$http.post(`/${this.resource}`, form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getData()
                            this.showAddButton = true
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.records[index].errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.getData()
                )
            }
        }
    }
</script>