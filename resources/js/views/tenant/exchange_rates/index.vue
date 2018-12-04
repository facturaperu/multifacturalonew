<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Listado de tipos de cambio</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Día</th>
                        <th>Compra</th>
                        <th>Venta</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.date }}</td>
                        <td>{{ row.buy }}</td>
                        <td>{{ row.sell }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col">
                    <el-button type="primary" @click.prevent="clickGet" :loading="loading_search_exchange_rate">Obtener</el-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {functions} from '../../../mixins/functions'

    export default {
        mixins: [functions],
        data() {
            return {
                resource: 'exchange_rates',
                records: [],
                data: null,
            }
        },
        created() {
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
            },
            clickGet() {
                this.searchExchangeRate().then(() => {
                    if(this.data){
                        this.$http.post(`/${this.resource}`, this.data)
                            .then(response => {
                                if (response.data.success) {
                                    this.$message.success(response.data.message)
                                    this.getData()
                                    this.data = null
                                } else {
                                    this.$message.error(response.data.message)
                                }
                            })
                            .catch(error => {
                                if (error.response.status === 422) {
                                    this.errors = error.response.data.errors
                                } else {
                                    console.log(error)
                                }
                            })
                            .then(() => {
                                this.loading_search_exchange_rate = false
                            })
                    }
                })
            }
        }
    }
</script>
