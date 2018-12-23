<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">
                                Producto/Servicio
                                <a href="#" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>
                            <el-select v-model="form.item_id" @change="changeItem" filterable>
                                <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.affectation_igv_type_id}">
                            <label class="control-label">Afectación Igv</label>
                            <el-select v-model="form.affectation_igv_type_id" filterable>
                                <el-option v-for="option in affectation_igv_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.affectation_igv_type_id" v-text="errors.affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity" :min="1"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_price}">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.unit_price">
                                <template slot="prepend">{{ form.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                    <!--<div class="col-md-2">-->
                        <!--<div class="form-group" :class="{'has-danger': errors.has_isc}">-->
                            <!--<label class="control-label d-block">¿Tiene ISC?</label>-->
                            <!--<el-switch-->
                                    <!--v-model="form.has_isc"-->
                                    <!--active-text="Si"-->
                                    <!--inactive-text="No"-->
                                    <!--@change="changeHasIsc">-->
                            <!--</el-switch>-->
                            <!--<small class="form-control-feedback" v-if="errors.has_isc" v-text="errors.has_isc[0]"></small>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<template v-if="form.has_isc">-->
                        <!--<div class="col-md-6">-->
                            <!--<div class="form-group" :class="{'has-danger': errors.system_isc_type_id}">-->
                                <!--<label class="control-label">Sistema Isc</label>-->
                                <!--<el-select v-model="form.system_isc_type_id" filterable>-->
                                    <!--<el-option v-for="option in system_isc_types" :key="option.id" :value="option.id" :label="option.description"></el-option>-->
                                <!--</el-select>-->
                                <!--<small class="form-control-feedback" v-if="errors.system_isc_type_id" v-text="errors.system_isc_type_id[0]"></small>-->
                            <!--</div>-->
                        <!--</div>-->
                        <!--<div class="col-md-2">-->
                            <!--<div class="form-group" :class="{'has-danger': errors.percentage_isc}">-->
                                <!--<label class="control-label">Porcentaje Isc</label>-->
                                <!--<el-input v-model="form.percentage_isc"></el-input>-->
                                <!--<small class="form-control-feedback" v-if="errors.percentage_isc" v-text="errors.percentage_isc[0]"></small>-->
                            <!--</div>-->
                        <!--</div>-->
                        <!--<div class="col-md-2">-->
                            <!--<div class="form-group" :class="{'has-danger': errors.suggested_price}">-->
                                <!--<label class="control-label">Precio sugerido</label>-->
                                <!--<el-input v-model="form.suggested_price"></el-input>-->
                                <!--<small class="form-control-feedback" v-if="errors.suggested_price" v-text="errors.suggested_price[0]"></small>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</template>-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6" v-if="discounts.length > 0">
                                <label class="control-label">
                                    Descuentos
                                    <a href="#" @click.prevent="clickAddDiscount">[+ Agregar]</a>
                                </label>
                                <table class="table">
                                    <tr v-for="(row, index) in form.discounts">
                                        <td>
                                            <el-select v-model="row.discount_type_id" @change="changeDiscountType(index)">
                                                <el-option v-for="option in discounts" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>
                                        </td>
                                        <td>
                                            <el-input v-model="row.percentage"></el-input>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger" @click.prevent="clickRemoveDiscount(index)">x</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6" v-if="charges.length > 0">
                                <label class="control-label">
                                    Cargos
                                    <a href="#" @click.prevent="clickAddCharge">[+ Agregar]</a>
                                </label>
                                <table class="table">
                                    <tr v-for="(row, index) in form.charges">
                                        <td>
                                            <el-select v-model="row.charge_type_id" @change="changeChargeType(index)">
                                                <el-option v-for="option in charges" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>
                                        </td>
                                        <td>
                                            <el-input v-model="row.percentage"></el-input>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger" @click.prevent="clickRemoveCharge(index)">x</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" native-type="submit">Agregar</el-button>
            </div>
        </form>
        <item-form :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>
    </el-dialog>
</template>

<script>

    import itemForm from '../../items/form.vue'

    export default {
        props: ['showDialog', 'operationTypeId'],
        components: {itemForm},
        data() {
            return {
                titleDialog: 'Agregar Producto o Servicio',
                resource: 'documents',
                showDialogNewItem: false,
                errors: {},
                form: {},
                item: {},
//                categories: [],
//                all_items: [],
                items: [],
                affectation_igv_types: [],
                system_isc_types: [],
                discounts: [],
                charges: [],
                use_price: 1
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
//                this.categories = response.categories
                this.items = response.data.items
                this.affectation_igv_types = response.data.affectation_igv_types
                this.system_isc_types = response.data.system_isc_types
                this.discounts = response.data.discounts
                this.charges = response.data.charges
            })

            this.$eventHub.$on('reloadDataItems', (item_id) => {
                this.reloadDataItems(item_id)
            })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
//                    category_id: [1],
                    item_id: null,
                    affectation_igv_type_id: '10',
                    has_isc: false,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0,
                    quantity: 1,
                    unit_price: 0,
                    charges: [],
                    discounts: [],
                    currency_type_symbol: null,
                }
            },
            clickAddDiscount() {
                this.form.discounts.push({
                    discount_type_id: null,
                    percentage: 0
                })
            },
            clickRemoveDiscount(index) {
                this.form.discounts.splice(index, 1)
            },
            changeDiscountType(index) {
                let discount_type_id = this.form.discounts[index].discount_type_id
                let discount_type = _.find(this.discounts, {id: discount_type_id})
                this.form.discounts[index].percentage = discount_type.percentage
            },
            clickAddCharge() {
                this.form.charges.push({
                    charge_type_id: null,
                    percentage: 0
                })
            },
            clickRemoveCharge(index) {
                this.form.charges.splice(index, 1)
            },
            changeChargeType(index) {
                let charge_type_id = this.form.charges[index].charge_type_id
                let charge_type = _.find(this.charges, {id: charge_type_id})
                this.form.charges[index].percentage = charge_type.percentage
            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
//            filterItems() {
//                this.form.item_id = null
//                this.items = this.all_items.filter((f) => {
//                    return f.category_id === _.last(this.form.category_id)
//                });
//            },
            changeItem() {
                this.item = _.find(this.items, {'id': this.form.item_id})
                this.form.unit_price = this.item.unit_price
                this.form.currency_type_symbol = this.item.currency_type.symbol
            },
            clickAddItem() {
                let item_description = this.item.description
                let affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id})

                let row = {
                    item_id: this.item.id,
                    item_description: item_description,
                    currency_type_id: this.item.currency_type_id,
                    unit_type_id: this.item.unit_type_id,
                    quantity: this.form.quantity,
                    unit_value: 0,
                    affectation_igv_type_id: affectation_igv_type.id,
                    affectation_igv_type_description: affectation_igv_type.description,
                    total_base_igv: 0,
                    percentage_igv: 18,
                    total_igv: 0,
                    system_isc_type_id: null,
                    total_base_isc: 0,
                    percentage_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    percentage_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    price_type_id: '01',
                    unit_price: parseFloat(this.form.unit_price),
                    total_value: 0,
                    total: 0,

                    total_discount: 0,
                    total_charge: 0,
                    attributes: [],
                    charges: [],
                    discounts: [],
                };

                let _percentage_igv = 18

                if (row.affectation_igv_type_id !== '10') {
                    _percentage_igv = 0
                }

                //row.unit_price = parseFloat(this.form.unit_price)
                let _unit_value = row.unit_price / (1 + _percentage_igv / 100)

                row.unit_value = _.round(_unit_value, 2)
//                _unit_value = row.unit_price / (1 + _percentage_igv / 100)

//                if (this.item.has_isc) {
//                    row.percentage_isc = parseFloat(this.item.percentage_isc)
//                    row.suggested_price = parseFloat(this.item.suggested_price)
//                    row.system_isc_type_id = this.item.system_isc_type_id
//
//                    let _unit_value_isc = 0
//                    _unit_value = row.unit_price / (1 + _percentage_igv / 100)
//
//                    if (this.item.system_isc_type_id === '01') {
//                        _unit_value /= (1 + row.percentage_isc / 100)
//                        _unit_value_isc = _unit_value * row.percentage_isc / 100
//                        //row.unit_value = _unit_value /_unit_value_isc
//                    }
//                    if (this.item.system_isc_type_id === '02') {
//                        //_unit_value = _unit_value
//                    }
//                    if (this.item.system_isc_type_id === '03') {
//                        _unit_value_isc = row.suggested_price * row.percentage_isc / 100
//                        row.unit_value = _unit_value - _unit_value_isc
//                    }
//
//                    row.total_isc = _unit_value_isc * row.quantity
//
//                } else {
//                    _unit_value = row.unit_price / (1 + _percentage_igv / 100)
//                }
                row.unit_value = _.round(_unit_value, 2)

                let _total_value_partial = _unit_value * row.quantity
                let _discount_base = 0
                let _discount_no_base = 0
                this.form.discounts.forEach((discount) => {
                    let discount_type = _.find(this.discounts, {'id': discount.discount_type_id})
                    if (discount_type.base) {
                        _discount_base += _.round(_total_value_partial * discount.percentage / 100, 2)
                        console.log('total base:'+_discount_base)
                    } else {
                        _discount_no_base += _.round(_total_value_partial * discount.percentage / 100, 2)
                        console.log('total no base:'+_discount_no_base)
                    }
                })

                row.total_discount = _discount_base + _discount_no_base
                row.total_value = _.round(_total_value_partial - row.total_discount, 2)
                row.total_base_igv = _.round(_total_value_partial - _discount_base + row.total_isc, 2)
                row.total_igv =  _.round(row.total_base_igv * _percentage_igv / 100, 2)
                row.total_taxes = row.total_igv + row.total_isc + row.total_other_taxes
                row.total = row.total_value + row.total_igv + row.total_isc

                if (affectation_igv_type.free) {
                    row.price_type_id = '02'
                    row.total = 0
                }

                this.initForm()
                this.$emit('add', row)
            },
            reloadDataItems(item_id) {
                this.$http.get(`/${this.resource}/table/items`).then((response) => {
                    this.items = response.data
                    this.form.item_id = item_id
                    this.changeItem()
                })
            },
        }
    }

</script>