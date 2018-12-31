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

export const exchangeRate = {
    methods: {
        async searchExchangeRateByDate(exchange_rate_date) {
            let response = await this.$http.post(`/services/search_exchange_rate`, {
                exchange_rate_date: exchange_rate_date
            })
            if (response.data.success) {
                return response.data.data.sell
            } else {
                this.$message.error(response.data.message)
                return 0
            }
        }
    }
};
