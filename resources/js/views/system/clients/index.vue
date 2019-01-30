<template>
    <div>
        <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Dashboard</span></li>
            </ol>
        </header>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="chart-data-selector ready">
                            <div class="d-flex">
                                <h2 class="mr-3">Documentos</h2>
                                <el-select v-model="year">
                                    <!--<el-option value="2018" label="2018"></el-option>-->
                                    <el-option value="2019" label="2019"></el-option>
                                    <!--<el-option value="2020" label="2020"></el-option>-->
                                </el-select>
                            </div>
                            <div class="chart-data-selector-items mt-3">
                                <chart-line :data="dataChartLine" v-if="loaded"></chart-line>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <div class="col-xl-6">
                        <section class="card card-featured-left card-featured-secondary">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-secondary">
                                            <i class="fa fa-building"></i>
                                        </div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Total Clientes</h4>
                                            <div class="info">
                                                <strong class="amount">{{ records.length }}</strong>
                                            </div>
                                        </div>
                                        <!--<div class="summary-footer">-->
                                            <!--<a class="text-muted text-uppercase" href="#">(withdraw)</a>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xl-6">
                        <section class="card card-featured-left card-featured-primary mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-primary">
                                            <i class="fas fa-file"></i>
                                        </div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Total Comprobantes</h4>
                                            <div class="info">
                                                <strong class="amount">{{ total_documents }}</strong>
                                                <!--<span class="text-primary">(14 unread)</span>-->
                                            </div>
                                        </div>
                                        <!--<div class="summary-footer">-->
                                        <!--<a class="text-muted text-uppercase" href="#">(view all)</a>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <section class="card card-featured-left card-featured-tertiary mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-warning">
                                            <i class="fas fa-file"></i>
                                        </div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Total Facturas</h4>
                                            <div class="info">
                                                <strong class="amount">{{ total_documents }}</strong>
                                            </div>
                                        </div>
                                        <!--<div class="summary-footer">-->
                                            <!--<a class="text-muted text-uppercase" href="#">(statement)</a>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xl-6">
                        <section class="card card-featured-left card-featured-quaternary">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-danger">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Total Venta en Planes</h4>
                                            <div class="info">
                                                <strong class="amount">2</strong>
                                            </div>
                                        </div>
                                        <!--<div class="summary-footer">-->
                                            <!--<a class="text-muted text-uppercase" href="#">(report)</a>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info">
                Listado de Clientes
                <div class="right-wrapper pull-right">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
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
                            <th>Plan</th>
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
                            <td>{{ row.plan }}</td> 
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
                year: 2019,
                total_documents: 0,
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
                    this.total_documents = response.data.total_documents
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
