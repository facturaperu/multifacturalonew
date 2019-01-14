<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.voided_description}">
                            <label class="control-label">Descripción del motivo de anulación</label>
                            <el-input v-model="form.voided_description"></el-input>
                            <small class="form-control-feedback" v-if="errors.voided_description" v-text="errors.voided_description[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="danger" native-type="submit" :loading="loading_submit">Anular</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId', 'showClose', 'urlContinue'],
        data() {
            return {
                titleDialog: null,
                loading_submit: false,
                resource: 'documents',
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
                    number: null,
                    voided_description: null
                }
            },
            create() {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form.id = response.data.data.id
                        this.form.number = response.data.data.number
                        this.titleDialog = 'Comprobante: '+this.form.number
                    })
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}/voided`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$eventHub.$emit('reloadData')
                            this.$message.success(response.data.message)
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            this.$message.error(error.response.data.message)
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
//            clickPrint(){
//                window.open(`/${this.resource}/to_print/${this.form.id}`, '_blank');
//            },
//            clickDownload() {
//                window.open(this.form.download_pdf, '_blank');
//            },
//            clickSendEmail() {
//                this.loading = true
//                post(`/${this.resource}/email`, {
//                    customer_email: this.form.customer_email,
//                    id: this.form.id
//                })
//                    .then(response => {
//                        if (response.success) {
//                            this.$message.success('El correo fue enviado satisfactoriamente')
//                        } else {
//                            this.$message.error('Error al enviar el correo')
//                        }
//                    })
//                    .catch(error => {
//                        console.log(error)
//                    })
//                    .then(() => {
//                        this.loading = false
//                    })
//            },
//            clickFinalize() {
//                location.href = `/${this.resource}`
//            },
//            clickNewDocument() {
//                this.clickClose()
//            },
//            clickClose() {
//                this.$emit('update:showDialog', false)
//                this.initForm()
//            },
        }
    }
</script>