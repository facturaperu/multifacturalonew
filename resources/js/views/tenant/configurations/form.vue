<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuraciones</h3>
        </div>
        <div class="card-body">
            
            <form autocomplete="off">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Reenvio de Facturas automático</label>
                            <div class="form-group" :class="{'has-danger': errors.send_auto}">
                                <el-switch v-model="form.send_auto" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.send_auto" v-text="errors.send_auto[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="typeUser != 'integrator'">
                            <label class="control-label">Crontab</label>
                            <div class="form-group" :class="{'has-danger': errors.cron}">
                                <el-switch v-model="form.cron" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.cron" v-text="errors.cron[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Envío de comprobantes a servidor alterno de SUNAT</label>
                            <div class="form-group" :class="{'has-danger': errors.sunat_alternate_server}">
                                <el-switch v-model="form.sunat_alternate_server" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.sunat_alternate_server" v-text="errors.sunat_alternate_server[0]"></small>
                            </div>
                        </div>
                         <div class="col-md-4 mt-4" v-if="typeUser != 'integrator'">
                            <label class="control-label">Impuesto bolsa plástica</label>
                            <div class="form-group" :class="{'has-danger': errors.amount_plastic_bag_taxes}">
                                <el-input-number v-model="form.amount_plastic_bag_taxes" @change="changeAmountPlasticBagTaxes" :precision="2" :step="0.1" :max="0.5" :min="0.1"></el-input-number>
                                <small class="form-control-feedback" v-if="errors.amount_plastic_bag_taxes" v-text="errors.amount_plastic_bag_taxes[0]"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props:['typeUser'],

        data() {
            return {
                loading_submit: false,
                resource: 'configurations',
                errors: {},
                form: {}
            }
        },
        async created() {
            await this.initForm();
            
            await this.$http.get(`/${this.resource}/record`) .then(response => {
                if (response.data !== '') this.form = response.data.data;
            });
        },
        methods: {
            initForm() {
                this.errors = {};
                
                this.form = {
                    send_auto: true,
                    stock: true,
                    cron: true,
                    amount_plastic_bag_taxes: 0.1,
                    sunat_alternate_server: false,
                    id: null
                };
            },
            submit() {
                this.loading_submit = true;
                
                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            changeAmountPlasticBagTaxes() {
                this.loading_submit = true;

                this.$http.post(`/${this.resource}/icbper`, this.form).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
        }
    }
</script>
