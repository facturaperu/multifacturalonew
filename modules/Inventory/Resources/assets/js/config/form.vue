<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuraciones</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Facturar sin inventario</label>
                            <div class="form-group" :class="{'has-danger': errors.stock}">
                                <el-switch v-model="form.stock" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.stock" v-text="errors.stock[0]"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Config from '../../../../../../resources/js/views/tenant/configurations/form.vue';
    
    export default {
        mixins: [Config]
    }
</script>
