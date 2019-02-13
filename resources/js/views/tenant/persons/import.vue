<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.file}">
                            <label class="control-label">Seleccionar archivo</label>
                            <el-upload
                                    ref="upload"
                                    :headers="headers"
                                    :data="{'type': type}"
                                    action="/persons/import"
                                    :show-file-list="false"
                                    :auto-upload="true"
                                    :multiple="false"
                                    :on-error="errorUpload"
                                    :on-success="successUpload">
                                <el-button slot="trigger" type="primary">Selecciona un archivo</el-button>
                            </el-upload>
                            <small class="form-control-feedback" v-if="errors.file" v-text="errors.file[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Procesar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'type'],
        data() {
            return {
                loading_submit: false,
                headers: headers_token,
                titleDialog: null,
                resource: 'persons',
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
                    file: null,
                }
            },
            create() {
                if(this.type === 'customers') {
                    this.titleDialog = 'Importar Clientes'
                }
                if(this.type === 'suppliers') {
                    this.titleDialog = 'Importar Proveedores'
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
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
                this.initForm()
            },
            successUpload(response, file, fileList) {
                if (response.success) {
                    this.$message.success(response.message)
                    this.$eventHub.$emit('reloadData')
                    this.close()
                } else {
                    this.$message({message:response.message, type: 'error'})
                }
            },
            errorUpload(response) {
                console.log(response)
            }
        }
    }
</script>