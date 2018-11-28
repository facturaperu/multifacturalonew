<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Nuevo Comprobante</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4 col-lg-3">
                            <div class="form-group" :class="{'has-danger': errors.document_type_code}">
                                <label class="control-label">Tipo de comprobante</label>
                                <el-select v-model="form.document_type_code" @change="changeDocumentType">
                                    <el-option v-for="option in document_types" :key="option.code" :value="option.code" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.document_type_code" v-text="errors.document_type_code[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" :class="{'has-danger': errors.series}">
                                <label class="control-label">Serie</label>
                                <el-select v-model="form.series">
                                    <el-option v-for="option in series" :key="option.number" :value="option.number" :label="option.number"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.series" v-text="errors.series[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.currency_type_code}">
                                <label class="control-label">Moneda</label>
                                <el-select v-model="form.currency_type_code" @change="changeCurrencyType">
                                    <el-option v-for="option in currency_types" :key="option.code" :value="option.code" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.currency_type_code" v-text="errors.currency_type_code[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                <label class="control-label">Fecha de emisión</label>
                                <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Formato de PDF</label>
                                <el-select v-model="form.optional.format_pdf" >
                                    <el-option key="a4" value="a4" label="Tamaño A4"></el-option>
                                    <el-option key="ticket" value="ticket" label="Tamaño Ticket"></el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.customer_id}">
                                <label class="control-label">Cliente</label>
                                <el-select v-model="form.customer_id" filterable>
                                    <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.customer_id" v-text="errors.customer_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 d-flex align-items-end pt-2">
                            <div class="form-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="clickAddItem">+ Agregar Producto</button>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                <label class="control-label">Fecha de vencimiento</label>
                                <el-date-picker v-model="form.date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.purchase_order}">
                                <label class="control-label">Orden Compra</label>
                                <el-input v-model="form.purchase_order"></el-input>
                                <small class="form-control-feedback" v-if="errors.purchase_order" v-text="errors.purchase_order[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.observations}">
                                <label class="control-label">Observaciones</label>
                                <el-input v-model="form.optional.observations" type="textarea" autosize></el-input>
                                <small class="form-control-feedback" v-if="errors.observations" v-text="errors.observations[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="form.items.length > 0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripcición</th>
                                        <th>Afectación Igv</th>
                                        <th class="text-right">Precio Unitario</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            <el-select v-model="row.item_id" @change="changeItem(index)" filterable>
                                                <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>
                                        </td>
                                        <td>
                                            <el-select v-model="row.affectation_igv_type_code" @change="changeItem(index)" filterable>
                                                <el-option v-for="option in affectation_igv_types" :key="option.code" :value="option.code" :label="option.description"></el-option>
                                            </el-select>
                                        </td>
                                        <td>
                                            <el-input v-model="row.unit_price" @input="changeRow(index)" class="input-text-right"></el-input>
                                        </td>
                                        <td class="text-right">
                                            <el-input-number v-model="row.quantity" :min="1"   @focus="changeRow(index)"  @change="changeRow(index)"></el-input-number>
                                        </td>
                                        <td class="text-right">
                                            <span v-text="row.total"></span>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-right" v-if="form.total_exonerated > 0">Total Exoneradas : {{ currency_symbol }} {{ form.total_exonerated }}</p>
                            <p class="text-right" v-if="form.total_taxed > 0">Total Gravadas : {{ currency_symbol }} {{ form.total_taxed }}</p>
                            <p class="text-right" v-if="form.total_igv > 0">Total Igv : {{ currency_symbol }} {{ form.total_igv }}</p>
                            <template v-if="form.total > 0">
                                <hr>
                                <h3 class="text-right"><b>Total : </b>{{ currency_symbol }} {{ form.total }}</h3>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0 && form.total > 0">Generar</el-button>
                </div>
            </form>
        </div>

        <document-options :showDialog.sync="showDialogOptions"
                          :recordId="documentNewId"
                          :showClose="false"></document-options>
    </div>
</template>

<script>

    import DocumentOptions from '../documents/partials/options.vue'

    export default {
        components: {DocumentOptions},
        data() {
            return {
                showDialogOptions: false,
                loading_submit: false,
                resource: 'documents',
                errors: {},
                form: {}, 
                document_types: [],
                currency_types: [],
                affectation_igv_types: [],
                items: [],
                customers: [],
                company: null,
                establishment: null,
                all_series: [],
                series: [],
                currency_symbol: 'S/',
                documentNewId: null
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.document_types = response.data.document_types_invoice
                    this.affectation_igv_types = response.data.affectation_igv_types
                    this.currency_types = response.data.currency_types
                    this.items = response.data.items
                    this.customers = response.data.customers
                    this.company = response.data.company
                    this.establishment = response.data.establishment
                    this.all_series = response.data.series

                    this.form.soap_type_id = this.company.soap_type_id
                    this.form.establishment_id = this.establishment.id

                    this.changeDocumentType()
                })
        },
        methods: {
            initForm() { 
                this.errors = {}
                this.form = {
                    id: null,
                    external_id: '-',
                    state_type_id: '01',
                    soap_type_id: null,
                    ubl_version: 'v21',
                    group_id: '01',
                    document_type_code: '01',
                    series: null,
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    date_of_due: moment().format('YYYY-MM-DD'),
                    currency_type_code: 'PEN',
                    customer_id: null,
                    establishment_id: null,
                    items: [],
                    total_exportation: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_isc: 0,
                    total_other_taxes: 0,
                    total_other_charges: 0,
                    total_discount: 0,
                    total_value: 0,
                    total: 0,
                    operation_type_code: '0101',
                    base_global_discount: 0,
                    percentage_global_discount: 0,
                    total_global_discount: 0,
                    total_free: 0,
                    total_prepayment: 0,
                    purchase_order: null,
                    optional: {
                        observations: null,
                        method_payment:null, 
                        salesman:null,
                        box_number:null,
                        format_pdf:'a4',
                    },
                    filename: '-' ,
                }
            }, 
            resetForm() {
                this.initForm()
                this.form.soap_type_id = this.company.soap_type_id
                this.form.establishment_id = this.establishment.id
                this.changeDocumentType()
            },
            clickAddItem() { 
                this.form.items.push({
                    item_id: null,
                    item_description: null,
                    unit_type_code: null,
                    carriage_plate: null,
                    quantity: 0,
                    unit_value: 0,
                    price_type_code: '01',
                    unit_price: 0,
                    affectation_igv_type_code: '10',
                    total_igv: 0,
                    percentage_igv: 18,
                    system_isc_type_code: null,
                    total_isc: 0,
                    charge_type_code: null,
                    charge_percentage: 0,
                    total_charge: 0,
                    discount_type_code: null,
                    discount_percentage: 0,
                    total_discount: 0,
                    total_value: 0,
                    total: 0,
                })
 
            },
            clickRemoveItem(index) { 
                this.form.items.splice(index, 1)
                this.calculateTotal()  
            },
            changeItem(index) {
                let item = _.find(this.items, {id: this.form.items[index].item_id})
                this.form.items[index].item_description = item.description
                this.form.items[index].unit_price = parseFloat(item.unit_price)
                this.form.items[index].unit_type_code = item.unit_type.code
                this.calculateRowTotal(index)
            },
            changeCurrencyType() {
                this.currency_symbol = (this.form.currency_type_code === 'PEN')?'S/':'$'
            },
            changeRow(index) {
                this.calculateRowTotal(index)
            },
            calculateRowTotal(index) {
                let unit_price = parseFloat(this.form.items[index].unit_price)
                let quantity = parseFloat(this.form.items[index].quantity)

                let unit_value = 0
                let total = 0
                let total_igv = 0
                let total_value = 0

                if (this.form.items[index].affectation_igv_type_code === '10') {
                    unit_value = _.round(unit_price / 1.18, 2)
                    total = _.round(unit_price * quantity, 2)
                    total_igv = _.round(total - (_.round(total /1.18, 2)), 2)
                    total_value = _.round(total /1.18, 2)
                }
                if (this.form.items[index].affectation_igv_type_code === '20') {
                    unit_value = _.round(unit_price, 2)
                    total = _.round(unit_price * quantity, 2)
                    total_igv = 0
                    total_value = total
                }

                this.form.items[index].unit_value = unit_value
                this.form.items[index].total_value = total_value
                this.form.items[index].total_igv = total_igv
                this.form.items[index].total = total
                this.calculateTotal()
            },
            calculateTotal() {
                let total_exonerated = 0
                let total_taxed = 0
                let total_igv = 0
                let total = 0
                this.form.items.forEach((row) => {
                    if (row.affectation_igv_type_code === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_code === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    total_igv += parseFloat(row.total_igv)
                    total += parseFloat(row.total)
                });
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_taxed = _.round(total_taxed, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_taxed, 2)
                this.form.total = _.round(total, 2)
 
            },
            changeDocumentType() {
                this.form.series = null
                let document_type = _.find(this.document_types, {'code': this.form.document_type_code})
                this.series = _.filter(this.all_series, {'document_type_id': document_type.id})
                this.form.group_id = (this.form.document_type_code === '01')?'01':'02'
                this.form.series = (this.series.length > 0)?this.series[0].number:null
            },
            submit() {
                
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.resetForm()
                            this.documentNewId = response.data.data.id
                            this.showDialogOptions = true
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
                location.href = '/documents'
            }
        }
    }
</script>