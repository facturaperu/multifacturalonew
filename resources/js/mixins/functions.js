export const functions = {
    data() {
        return {
            loading_search_exchange_rate: false,
            loading_search_customer: false
        }
    },
    methods: {
        searchExchangeRate() {
            return new Promise((resolve) => {
                this.loading_search_exchange_rate = true
                this.$http.post(`/services/exchange_rate`, this.form)
                    .then(response => {
                        let res = response.data
                        if (res.success) {
                            this.data = res.data;
                            this.form.buy = res.data[this.form.cur_date].buy;
                            this.form.sell = res.data[this.form.cur_date].sell;
                            this.$message.success(res.message)
                        } else {
                            this.$message.error(res.message)
                            this.loading_search_exchange_rate = false
                        }
                        resolve()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.loading_search_exchange_rate = false
                    })
                    .then(() => {
                        this.loading_search_exchange_rate = false
                    })
            })
        },
        searchExchangeRateByDate() {
            return new Promise((resolve) => {
                this.loading_search_exchange_rate = true
                this.$http.post(`/services/search_exchange_rate`, this.form)
                    .then(response => {
                        let res = response.data
                        if (res.success) {
                            this.form.exchange_rate_sell = res.data.sell
                        } else {
                            this.form.exchange_rate_sell = 0
                            this.$message.error(res.message)
                        }
                        resolve()
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
                    .then(() => {
                        this.loading_search_exchange_rate = false
                    })
            })
        },
        searchCustomerByNumber() {
            return new Promise((resolve) => {
                this.loading_search_customer = true
                let identity_document_type_name = ''
                if (this.form.identity_document_type_id === '6') {
                    identity_document_type_name = 'ruc'
                }
                if (this.form.identity_document_type_id === '1') {
                    identity_document_type_name = 'dni'
                }
                this.$http.get(`/services/${identity_document_type_name}/${this.form.number}`)
                    .then(response => {
                        console.log(response.data)
                        let res = response.data
                        if (res.success) {
                            this.form.name = res.data.name
                            this.form.trade_name = res.data.trade_name
                            this.form.address = res.data.address
                            this.form.department_id = res.data.department_id
                            this.form.province_id = res.data.province_id
                            this.form.district_id = res.data.district_id
                            this.form.phone = res.data.phone
                        } else {
                            this.$message.error(res.message)
                        }
                        resolve()
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
                    .then(() => {
                        this.loading_search_customer = false
                    })
            })
        }
    }
};

export const formDocumentItem = {
    data() {
        return {
            row: {}
        }
    },
    methods: {
        calculateRowItem(row) {
            let percentage_igv = 18

            if (row.affectation_igv_type_id !== '10') {
                percentage_igv = 0
            }

            //row.unit_price = parseFloat(this.form.unit_price)
            let unit_value = row.unit_price / (1 + percentage_igv / 100)

            //row.unit_value = _.round(_unit_value, 2)
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
            row.unit_value = _.round(unit_value, 2)

            let total_value_partial = unit_value * row.quantity
            let discount_base = 0
            let discount_no_base = 0
            this.form.discounts.forEach((discount) => {
                let discount_type = _.find(this.discounts, {'id': discount.discount_type_id})
                if (discount_type.base) {
                    discount_base += _.round(total_value_partial * discount.percentage / 100, 2)
                    console.log('total base:'+discount_base)
                } else {
                    discount_no_base += _.round(total_value_partial * discount.percentage / 100, 2)
                    console.log('total no base:'+discount_no_base)
                }
            })

            let total_isc = 0
            let total_other_taxes = 0

            let total_discount = discount_base + discount_no_base
            let total_value = total_value_partial - total_discount
            let total_base_igv = total_value_partial - discount_base + total_isc
            let total_igv  = total_base_igv * percentage_igv / 100
            let total_taxes = total_igv + total_isc + total_other_taxes
            let total = total_value + total_taxes

            row.total_discount = _.round(total_discount, 2)
            row.total_value = _.round(total_value, 2)
            row.total_base_igv = _.round(total_base_igv, 2)
            row.total_igv =  _.round(total_igv, 2)
            row.total_taxes = _.round(total_taxes, 2)
            row.total = _.round(total, 2)

            if (row.affectation_igv_type.free) {
                row.price_type_id = '02'
                row.total = 0
            }

            this.row = row
        }
    }
};
