<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label">Formato de PDF</label>
                    <el-select v-model="format" >
                        <el-option key="a4" value="a4" label="Tamaño A4"></el-option>
                        <el-option key="ticket" value="ticket" label="Tamaño Ticket"></el-option>
                    </el-select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 mt-2">
                <button type="button" class="btn waves-effect waves-light btn-lg btn-info pull-right" @click="clickPrint">
                    <i class="fa fa-print"></i>&nbsp;&nbsp;Imprimir
                </button>
            </div>
            <div class="col-6 mt-2">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary" @click="clickDownload">
                    <i class="fa fa-download"></i>&nbsp;&nbsp;Descargar
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <el-input v-model="form.customer_email">
                    <el-button slot="append" icon="el-icon-message" @click="clickSendEmail" :loading="loading">Enviar</el-button>
                </el-input>
                <small class="form-control-feedback" v-if="errors.customer_email" v-text="errors.customer_email[0]"></small>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button @click="clickFinalize">Ir al listado</el-button>
                <el-button type="primary" @click="clickNewDocument">Nuevo comprobante</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId', 'showClose'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'documents',
                format: null,
                errors: {},
                form: {}
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.format = 'a4';
                this.errors = {};
                this.form = {
                    customer_email: null,
                    download_pdf: null,
                    external_id: null,
                    number: null,
                    id: null
                };
            },
            create() {
                this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                    this.form = response.data.data;
                    this.titleDialog = 'Comprobante: '+this.form.number;
                });
            },
            clickPrint(){
                window.open(`/print/document/${this.form.external_id}/${this.format}`, '_blank');
            },
            clickDownload() {
                window.open(`${this.form.download_pdf}/${this.format}`, '_blank');
            },
            clickSendEmail() {
                this.loading = true
                this.$http.post(`/${this.resource}/email`, {
                    customer_email: this.form.customer_email,
                    id: this.form.id
                })
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success('El correo fue enviado satisfactoriamente')
                        } else {
                            this.$message.error('Error al enviar el correo')
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
                        this.loading = false
                    })
            },
            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickNewDocument() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>