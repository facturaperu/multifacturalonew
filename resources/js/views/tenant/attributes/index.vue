<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Listado de Atributos</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Descripción</th> 
                        <th>Activo</th> 
                        <th class="text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.id }}</td>
                        <td class="text-right">{{ row.description }}</td>
                        <td class="text-right">{{ row.active }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div>
        </div>
  
  
        <tribute-concept-types-form :showDialog.sync="showDialog"
                        @load:list="getData"
                         :recordId="recordId"></tribute-concept-types-form>
    </div>
</template>


<script>


    import TributeConceptTypesForm from './form.vue'
    import {deletable} from '../../../mixins/deletable'
    
    export default { 
        mixins: [deletable],
        components: {TributeConceptTypesForm},
        data() {
            return { 
                
                showDialog: false,
                resource: 'tribute_concept_types',
                recordId: null,
                records: [],
            }
        },
        created() { 
            this.getData()

        },
        methods: { 
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data 
                    })
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.getData()
                )
            }
        }
    }
</script>
