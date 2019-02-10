<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name" :maxlength="11"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.pricing}">
                            <label class="control-label">Precio</label>
                            <el-input v-model="form.pricing"></el-input>
                            <small class="form-control-feedback" v-if="errors.pricing" v-text="errors.pricing[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.limit_users }">
                            <label class="control-label">Límite de usuarios</label>
                            <el-input v-model="form.limit_users"></el-input>
                            <small class="form-control-feedback" v-if="errors.limit_users" v-text="errors.limit_users[0]"></small> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.limit_documents}">
                            <label class="control-label">Límite de documentos</label>
                            <el-input v-model="form.limit_documents"></el-input>
                            <small class="form-control-feedback" v-if="errors.limit_documents" v-text="errors.limit_documents[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="form-group" :class="{'has-danger': (errors.plan_documents)}">
                            <label class="control-label font-weight-bold mb-0">Habilitar documentos electrónicos</label> 

                            <el-checkbox-group v-model="form.plan_documents"  >
                                <el-checkbox v-for="(city,ind) in plan_documents" class="plan_documents" :label="city.id"  :key="ind">{{city.description}}</el-checkbox>
                            </el-checkbox-group>

                            <small class="form-control-feedback" v-if="errors.plan_documents" v-text="errors.plan_documents[0]"></small> 
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<style>
.plan_documents{ display:block ; margin: 15px 0 ;}
</style>

<script>

    import {EventBus} from '../../../helpers/bus'

    export default {
        props: ['showDialog', 'recordId','plan_documents'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'plans',
                error: {},
                form: {}, 
            }
        },
        created() {
            this.initForm() 
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    name: null,
                    pricing: null,
                    limit_users: null,
                    limit_documents: null,
                    plan_documents:[]
                }
            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar plan':'Nuevo plan'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                            this.form = response.data.data
                            this.form.plan_documents = Object.values(response.data.data.plan_documents)
                        })
                }
            },
            submit() {
                this.loading_submit = true  
                this.$http.post(`${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>