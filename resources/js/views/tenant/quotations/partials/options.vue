<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"
                :close-on-click-modal="false"
                :close-on-press-escape="false"
                :show-close="false"> 
            <div class="row" v-show="!showGenerate">
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Imprimir A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('a4')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div> 
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Imprimir Ticket</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('ticket')">
                        <i class="fa fa-receipt"></i>
                    </button>
                </div> 
            </div>
            <br>
            <div class="row"> 
                <div class="col-md-9" v-show="!showGenerate">
                    <div class="form-group"> 
                        <el-checkbox v-model="generate">Generar comprobante electrónico</el-checkbox>                            
                    </div>
                </div>
            </div>
            <div class="row" v-if="generate">

                <div class="col-lg-6">
                    <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                        <label class="control-label">Fecha de emisión</label>
                        <el-date-picker v-model="document.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue" ></el-date-picker>
                        <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                    </div>
                </div> 

                <div class="col-lg-6">
                    <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                        <!--<label class="control-label">Fecha de emisión</label>-->
                        <label class="control-label">Fecha de vencimiento</label>
                        <el-date-picker v-model="document.date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="false"  ></el-date-picker>
                        <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                    </div>
                </div> 
                <div class="col-lg-8">
                    <div class="form-group" :class="{'has-danger': errors.document_type_id}"> 
                        <label class="control-label">Tipo comprobante</label>
                        <el-select v-model="document.document_type_id" @change="changeDocumentType" popper-class="el-select-document_type" dusk="document_type_id" class="border-left rounded-left border-info">
                            <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                        </el-select>
                        <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group" :class="{'has-danger': errors.series_id}">
                        <label class="control-label">Serie</label>
                        <el-select v-model="document.series_id">
                            <el-option v-for="option in series" :key="option.id" :value="option.id" :label="option.number"></el-option>
                        </el-select>
                        <small class="form-control-feedback" v-if="errors.series_id" v-text="errors.series_id[0]"></small>
                    </div>
                </div>
            </div>
                

            <span slot="footer" class="dialog-footer">
                <template v-if="showClose">
                    <el-button @click="clickClose">Cerrar</el-button>         
                    <el-button class="submit" type="primary" @click="submit" :loading="loading_submit" v-if="generate">Generar</el-button>

                </template>
                <template v-else>
                    <el-button class="submit" type="primary" plain  @click="submit" :loading="loading_submit" v-if="generate">Generar comprobante</el-button>                
                    <el-button @click="clickFinalize" v-else>Ir al listado</el-button>
                    <el-button type="primary" @click="clickNewQuotation">Nueva cotización</el-button>
                </template>
            </span>
        </el-dialog>

    
        <document-options :showDialog.sync="showDialogOptions"
                          :recordId="documentNewId"
                          :isContingency="false"
                          :showClose="true"></document-options>
    </div>
</template>

<script>

    import DocumentOptions from '../../documents/partials/options.vue'

    export default {
        components: {DocumentOptions},

        props: ['showDialog', 'recordId', 'showClose','showGenerate'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'quotations',
                resource_documents: 'documents',
                errors: {},
                form: {},
                document:{},
                document_types: [],
                all_series: [],
                series: [],
                generate:false,
                loading_submit:false,
                showDialogOptions: false,
                documentNewId: null,
                
            }
        },
        created() {
            this.initForm()
            this.initDocument()
        },
        methods: {
            initForm() {
                this.generate = (this.showGenerate) ? true:false
                this.errors = {}
                this.form = {
                    id: null,
                    external_id: null, 
                    identifier: null,
                    date_of_issue:null,
                    quotation:null,
                }
            },
            initDocument(){

                this.document = {
                    document_type_id:null,
                    series_id:null,
                    establishment_id: null, 
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: null,
                    customer_id: null,
                    currency_type_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    operation_type_id: null,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    additional_information:null,
                    actions: {
                        format_pdf:'a4',
                    },
                    quotation_id:null
                }
            },
            resetDocument(){

                this.generate = (this.showGenerate) ? true:false                
                this.initDocument()
                this.document.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                this.changeDocumentType()
            },
            submit() { 

                this.loading_submit = true
                this.assignDocument()

                this.$http.post(`/${this.resource_documents}`, this.document).then(response => {
                        if (response.data.success) {
                            this.documentNewId = response.data.data.id;
                            this.showDialogOptions = true;
                            this.$eventHub.$emit('reloadData')
                            this.resetDocument() 

                        }
                        else {
                            this.$message.error(response.data.message);
                        }
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data;
                        }
                        else {
                            this.$message.error(error.response.data.message);
                        }
                    }).then(() => {
                        this.loading_submit = false;
                    });
            },
            changeDateOfIssue() {
                this.document.date_of_due = this.document.date_of_issue 
            },
            assignDocument(){ 

                let q = this.form.quotation 

                this.document.establishment_id = q.establishment_id  
                // this.document.date_of_issue = q.date_of_issue
                this.document.time_of_issue = moment().format('HH:mm:ss')
                this.document.customer_id = q.customer_id
                this.document.currency_type_id = q.currency_type_id
                this.document.purchase_order = null
                this.document.exchange_rate_sale = q.exchange_rate_sale
                this.document.total_prepayment = q.total_prepayment
                this.document.total_charge = q.total_charge
                this.document.total_discount = q.total_discount
                this.document.total_exportation = q.total_exportation
                this.document.total_free = q.total_free
                this.document.total_taxed = q.total_taxed
                this.document.total_unaffected = q.total_unaffected
                this.document.total_exonerated = q.total_exonerated
                this.document.total_igv = q.total_igv
                this.document.total_base_isc = q.total_base_isc
                this.document.total_isc = q.total_isc
                this.document.total_base_other_taxes = q.total_base_other_taxes
                this.document.total_other_taxes = q.total_other_taxes
                this.document.total_taxes = q.total_taxes
                this.document.total_value = q.total_value
                this.document.total = q.total
                this.document.operation_type_id = '0101'
                // this.document.date_of_due = q.date_of_issue
                this.document.items = q.items
                this.document.charges = q.charges
                this.document.discounts = q.discounts
                this.document.attributes = []
                this.document.guides = q.guides
                this.document.additional_information =null
                this.document.actions = {
                    format_pdf : 'a4'
                }
                this.document.quotation_id = this.form.id
// console.log(this.document)
            },
            create() {

                this.$http.get(`/${this.resource}/option/tables`).then(response => {
                    this.document_types = response.data.document_types_invoice
                    this.all_series = response.data.series                     
                    this.document.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null
                    this.changeDocumentType()
                })

                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = 'Cotización registrada: '+this.form.identifier
                    })
            },
            changeDocumentType() {
                this.filterSeries() 
            },
            filterSeries() {
                this.document.series_id = null
                this.series = _.filter(this.all_series, {'document_type_id': this.document.document_type_id})
                this.document.series_id = (this.series.length > 0)?this.series[0].id:null
            },
            clickFinalize() {
                location.href = `/${this.resource}`
            },
            clickNewQuotation() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
                this.resetDocument()
            },
            clickToPrint(format){
                window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
            } 
        }
    }
</script>