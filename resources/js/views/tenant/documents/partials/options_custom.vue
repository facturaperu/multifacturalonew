<template>
    <el-dialog   :visible="showDialog"  @open="create" width="50%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <span slot="title">
            <div class="widget-summary widget-summary-xs pl-3 p-2">
                <div class="widget-summary-col widget-summary-col-icon">
                    <div class="summary-icon bg-success">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="widget-summary-col">
                    <div class="summary row">
                        <div class="col-md-6">
                            <h4 class="title">Venta exitosa : comprobante {{form.number}}</h4>
                        </div> 
                    </div> 
                </div>
            </div>
        </span>
        <div class="form-body el-dialog__body_custom">
            <div class="row">
                <div class="col-md-12 m-bottom">  
                    <el-tabs v-model="activeName"  >
                        <el-tab-pane label="Imprimir Ticket" name="first">
                            <embed :src="form.print_ticket" type="application/pdf" width="100%" height="400px"/>                                    
                        </el-tab-pane> 
                        <el-tab-pane label="Imprimir A4" name="second">                                    
                            <embed :src="form.print_a4" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>                        
                    </el-tabs>
                </div> 
                <div class="row col-md-12 dialog-footer"> 
                    <div class="col-md-6">   
                        <el-input v-model="form.customer_email">
                            <el-button slot="append" icon="el-icon-message"   @click="clickSendEmail" :loading="loading">Enviar</el-button>
                        </el-input>
                        <!-- <small class="form-control-feedback" v-if="errors.customer_email" v-text="errors.customer_email[0]"></small> -->

                    </div> 
                    <div class="col-md-6 float-right">  
                        <el-button type="primary" class="float-right" @click="clickNewDocument">Nuevo comprobante</el-button>                            
                        <el-button class="list float-right mr-4" @click="clickFinalize">Ir al listado</el-button>
                    </div>
                </div> 

            </div>
        </div>
    </el-dialog>
</template> 

<script>
    export default {
        props: ['showDialog', 'recordId', 'statusDocument'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'documents',
                errors: {},
                form: {},
                company: {},
                configuration: {},
                activeName: 'first',

            }
        },
        async created() {
            this.initForm() 
        },
        methods: {
            clickNewSale(){
                this.initForm()
                this.$eventHub.$emit('cancelSale')

            }, 
            clickFinalize() {
                location.href = (this.isContingency) ? `/contingencies` : `/${this.resource}`
            },
            clickNewDocument() {
                this.clickClose()
            },
            initForm() {
                this.errors = {};
                this.configuration = {};
                this.form = {
                    customer_email: null,
                    download_pdf: null,
                    print_a4: null,
                    print_ticket: null,
                    external_id: null,
                    number: null, 
                    id: null
                } 
            },
            async create() {
                
                await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                    this.form = response.data.data; 
                    this.titleDialog = 'Comprobante: '+this.form.number;
                }); 

            }, 
            clickSendEmail() {
                            
                if(this.form.customer_email == null || this.form.customer_email == '') return this.$message.error('Ingrese el correo')
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
            // clickConsultCdr(document_id) {
            //     this.$http.get(`/${this.resource}/consult_cdr/${document_id}`)
            //         .then(response => {
            //             if (response.data.success) {
            //                 this.$message.success(response.data.message)
            //                 this.$eventHub.$emit('reloadData')
            //             } else {
            //                 this.$message.error(response.data.message)
            //             }
            //         })
            //         .catch(error => {
            //             this.$message.error(error.response.data.message)
            //         })
            // },
            // clickFinalize() {
            //     location.href = (this.isContingency) ? `/contingencies` : `/${this.resource}`
            // },
            // clickNewDocument() {
            //     this.clickClose()
            // },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
