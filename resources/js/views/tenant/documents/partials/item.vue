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
                            <el-select v-model="form.item_id" @change="filterItem" filterable>
                                <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.affectation_igv_type_id}">
                            <label class="control-label">Afectación Igv</label>
                            <el-select v-model="form.affectation_igv_type_id" filterable>
                                <el-option v-for="option in affectation_igv_types" :key="option.code" :value="option.code" :label="option.description"></el-option>
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
                            <el-input v-model="form.unit_price"></el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
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
//    import ElInput from "../../../../../../node_modules/element-ui/packages/input/src/input";

    export default {
        props: ['showDialog', 'operationTypeId'],
        components: {itemForm},
        data() {
            return {
                titleDialog: '',//this.$t('items.titles.new'),
                resource: 'documents',
                showDialogNewItem: false,
                errors: {},
                form: {},
                item: {},
//                categories: [],
//                all_items: [],
                items: [],
                affectation_igv_types: [],
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
                this.discounts = response.data.discounts
                this.charges = response.data.charges
            })

            this.$eventHub.$on('reloadDataItems', () => {
                this.reloadDataItems()
            })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
//                    category_id: [1],
                    item_id: null,
                    affectation_igv_type_id: null,
                    quantity: 1,
                    unit_price: 0,
                    // retail_unit_price: 0,
                    // wholesale_unit_price: 0
                    charges: [],
                    discounts: []
                }
                // this.use_price = 1
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
            filterItem() {
                this.item = _.find(this.items, {'id': this.form.item_id})
                this.form.unit_price = this.item.unit_price
                this.form.retail_unit_price = this.item.retail_unit_price
                this.form.wholesale_unit_price = this.item.wholesale_unit_price
            },
            clickAddItem() {

//                $table->unsignedInteger('item_id');
//                $table->string('item_description');
//                $table->integer('quantity');
//                $table->decimal('unit_value', 12, 2);
//
//                $table->char('affectation_igv_type_id', 8);
//                $table->decimal('total_base_igv', 12, 2);
//                $table->decimal('percentage_igv', 12, 2);
//                $table->decimal('total_igv', 12, 2);
//
//                $table->char('system_isc_type_id', 8)->nullable();
//                $table->decimal('total_base_isc', 12, 2)->default(0);
//                $table->decimal('percentage_isc', 12, 2)->default(0);
//                $table->decimal('total_isc', 12, 2)->default(0);
//
//                $table->decimal('total_base_other_taxes', 12, 2)->default(0);
//                $table->decimal('percentage_other_taxes', 12, 2)->default(0);
//                $table->decimal('total_other_taxes', 12, 2)->default(0);
//                $table->decimal('total_taxes', 12, 2);
//
//                $table->char('price_type_id', 8);
//                $table->decimal('unit_price', 12, 2)->default(0);
//                $table->decimal('unit_value_free', 12, 2)->default(0);
//
//                $table->decimal('total_value', 12, 2);
//                $table->decimal('total', 12, 2);
//
//                $table->json('attributes')->nullable();
//                $table->json('charges')->nullable();
//                $table->json('discounts')->nullable();

                let item_description = this.item.description

//                if (this.item.additional_information) {
//                    item_description += '|'+this.item.additional_information
//                }
                let row = {
                    item_id: this.item.id,
                    item_description: item_description,
                    quantity: this.form.quantity,
                    unit_value: 0,
                    affectation_igv_type_id: '07000010',
                    total_base_igv: 0,
                    percentage_igv: 0,
                    total_igv: 0,
                    system_isc_type_id: null,
                    total_base_isc: 0,
                    percentage_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    percentage_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    price_type_id: null,
                    unit_price: this.form.unit_price,
                    unit_price_free: 0,
                    total_value: 0,
                    total: 0,
                    attributes: [],
                    charges: [],
                    discounts: [],
                };

                let affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id})
                let percentage_igv = 0
//                let price_type_id = '16000001'

                if (['10'].indexOf(affectation_igv_type.id) > -1) {
                    percentage_igv = 18
                }

                if (affectation_igv_type.free) {
                    row.unit_price = 0
                    row.unit_price_free = this.form.unit_price
                    row.price_type_id = '02'
                } else {
                    row.unit_price = this.form.unit_price
                    row.unit_price_free = 0
                    row.price_type_id = '01'
                }


//                this.form.affectation_igv_type_id === '') {
//
//                }

//                let percentage_igv = 18

//                switch (this.use_price) {
//                    case 2:
//                        row.unit_price =  this.form.retail_unit_price
//                       break;
//                    case 3:
//                        row.unit_price =  this.form.wholesale_unit_price
//                        break;
//                }
//
//                let exportation = (this.operationTypeId === '17000002')

//                let igv_percentage =  (exportation)?0:0.18
                row.total = _.round(row.unit_price * row.quantity, 2)
                row.total_igv = _.round(row.total / (1 + igv_percentage) * igv_percentage, 2)
                let subtotal = _.round(row.total - row.total_igv, 2)
                row.unit_value = _.round(subtotal / row.quantity, 2)

                row.affectation_igv_type_id = (exportation)?'07000040':'07000010'
                row.total_unaffected = (exportation)?subtotal:0
                row.total_taxed = (!exportation)?subtotal:0

                row.total_value = _.round(row.unit_value * row.quantity, 2)

                this.initForm()
                this.$emit('add', row)
            },
            reloadDataItems() {
                this.$http.get(`/${this.resource}/table/items`).then((response) => {
                    this.items = response.data
                })
            },
        }
    }

</script>