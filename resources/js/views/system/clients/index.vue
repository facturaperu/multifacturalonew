<template>
<!--<<<<<<< HEAD-->
    <!--<div class="card">-->
        <!--<div class="card-header bg-info">-->
            <!--<h4 class="m-b-0 text-white">Listado de Clientes</h4>-->
            <!--<button type="button" class="btn waves-effect waves-light btn-secondary" @click.prevent="clickCreate()">+ Nuevo</button>-->
        <!--</div>-->
        <!--<div class="card-body">-->
            <!--<div class="table-responsive">-->
                <!--<table class="table">-->
                    <!--<thead>-->
                    <!--<tr>-->
                        <!--<th>#</th>-->
                        <!--<th>Hostname</th>-->
                        <!--<th>Nombre</th>-->
                        <!--<th>RUC</th>-->
                        <!--<th>Correo</th>-->
                        <!--&lt;!&ndash; <th class="text-right">Número</th> &ndash;&gt;-->
                        <!--&lt;!&ndash; <th class="text-right">Acciones</th> &ndash;&gt;-->
                    <!--</tr>-->
                    <!--</thead>-->
                    <!--<tbody>-->
                    <!--<tr v-for="(row, index) in records">-->
                        <!--<td>{{ index + 1 }}</td>-->
                        <!--<td>{{ row.hostname }}</td>-->
                        <!--<td>{{ row.name }}</td>-->
                        <!--<td>{{ row.number }}</td>-->
                        <!--<td>{{ row.email }}</td>-->
                        <!--&lt;!&ndash; <td class="text-right">{{ row.number }}</td> &ndash;&gt;-->
                        <!--&lt;!&ndash; <td class="text-right">-->
                            <!--<button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>-->
                            <!--<button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>-->
                        <!--</td> &ndash;&gt;-->
                    <!--</tr>-->
                    <!--</tbody>-->
                <!--</table>-->
<!--=======-->
    <div>
        <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Clientes</span></li>
            </ol>


            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>
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
                            <!-- <th class="text-right">Número</th> -->
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
                            <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickPassword(row.id)">Clave</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </td>
                            <!-- <td class="text-right">{{ row.number }}</td> -->
                            <!-- <td class="text-right">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </td> -->
                        </tr>
                        </tbody>
                    </table>
                </div>
<!--&gt;>>>>>> 1c08a13bd88b598a1bc69a8efcc8470958ee89c2-->
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

    export default {
        mixins: [deletable,changeable],
        components: {CompaniesForm},
        data() {
            return {
                showDialog: false,
                resource: 'clients',
                recordId: null,
                records: [],
            }
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
