<template>
    <div>
        <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Clientes</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </header>
        <chart-line :data="dataChartLine" v-if="loaded"></chart-line>
        <div class="card">
            <div class="card-header bg-info">
                Listado de Clientes
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Hostname</th>
                            <th>Nombre</th>
                            <th>RUC</th>
                            <th>Correo</th>
                            <th class="text-right">Contador</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.hostname }}</td>
                            <td>{{ row.name }}</td>
                            <td>{{ row.number }}</td>
                            <td>{{ row.email }}</td>
                            <td class="text-right">{{ row.count_doc }}</td>
                            <td class="text-right">
                                <template v-if="!row.locked">
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickPassword(row.id)">Clave</button>
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                                </template>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <system-clients-form :showDialog.sync="showDialog"
                             :recordId="recordId"></system-clients-form>
    </div>
</template>

<script>

    import CompaniesForm from './form.vue'
    import {deletable} from "../../../mixins/deletable"
    import {changeable} from "../../../mixins/changeable"
    import ChartLine from './charts/Line'

    export default {
        mixins: [deletable,changeable],
        components: {CompaniesForm, ChartLine},
        data() {
            return {
                showDialog: false,
                resource: 'clients',
                recordId: null,
                records: [],
                loaded: false,
                dataChartLine : {
                    labels: null,
                    datasets: [
                        {
                            // label: 'Data One',
                            // backgroundColor: '#f87979',
                            data: null
                        }
                    ]
                }
            }
        },
        async mounted() {
            this.loaded = false
            await this.$http.get(`/${this.resource}/charts`)
                .then(response => {
                    let line = response.data.line
                    this.dataChartLine.labels = line.labels
                    this.dataChartLine.datasets[0].data = line.data
                    // console.log(response.data)
                    // this.records = response.data.data
                })
            this.loaded = true
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })

            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickPassword(id) {
                this.change(`/${this.resource}/password/${id}`)
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
