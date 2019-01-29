<template>
    <div>
        <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Planes</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </header>
        <div class="card">
            <div class="card-header bg-info">
                Planes Activos
            </div>
            <div class="card-body">
                <div class="pricing-table row no-gutters mt-3 mb-3">
					
                    <template v-for="(row, index) in records">

                        <div class="col-lg-3 col-sm-6 text-center" style="padding:10px;background-color:007bff" :key="index">
							<div class="plan most-popular">
								<div class="plan-ribbon-wrapper "></div>
								<h3>{{row.name}}<span>S/ {{row.pricing}}</span></h3> 
								<ul>
									<li><strong>{{row.limit_users}}</strong> Usuarios</li>
									<li><strong>{{row.limit_documents}}</strong> Comprobantes</li>
                                    <template v-for="(da, i) in row.documents_active">
                                        <li :key="i">{{da}}</li>
                                    </template>

								</ul>
                                <div v-if="!row.locked">
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-danger float-right" style="margin-left:6px;" @click.prevent="clickDelete(row.id)"><i class="fas fa-trash"></i> </button>
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-primary float-right"  @click.prevent="clickCreate(row.id)"><i class="fas fa-edit"></i> </button><br>
                                </div>
							</div>
						</div>
                        
                    </template>
						
						  
                </div>
            </div>
        </div>
        <system-plans-form :showDialog.sync="showDialog"
                             :recordId="recordId"></system-plans-form>
    </div>
</template>

<script>

    import PlansForm from './form.vue'
    import {deletable} from "../../../mixins/deletable" 

    export default {
        mixins: [deletable],
        components: {PlansForm},
        data() {
            return {
                showDialog: false,
                resource: 'plans',
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
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
