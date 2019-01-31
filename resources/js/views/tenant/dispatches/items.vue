<template>
    <el-dialog :title="titleDialog" :visible="dialogVisible" @open="create" @close="close" top="8vh">
        <div class="form-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">
                            Producto
                            <!-- <a href="#" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a> -->
                            <a href="#" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                        </label>
                        <el-select v-model="form.item" filterable>
                            <el-option v-for="option in items" :key="option.id" :value="option" :label="option.full_description"></el-option>
                        </el-select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">Cantidad</label>
                        <el-input-number v-model="form.quantity" :precision="2" :step="1" :min="0.01" :max="99999999"></el-input-number>
                    </div>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click.prevent="close">Cancel</el-button>
            <el-button type="primary" @click="clickAddItem">Agregar</el-button>
        </span>
        
        <item-form :showDialog.sync="showDialogNewItem" :external="true"></item-form>
    </el-dialog>
</template>

<script>
    import itemForm from '../items/form.vue';
    
    export default {
        components: {itemForm},
        props: ['dialogVisible'],
        data() {
            return {
                titleDialog: 'Agregar Producto',
                showDialogNewItem: false,
                resource: 'dispatches',
                items: [],
                form: {}
            }
        },
        methods: {
            create() {
                this.$http.post(`/${this.resource}/tables`).then(response => {
                    this.items = response.data.items;
                });
                
                this.form = {};
            },
            close() {
                this.$emit('update:dialogVisible', false);
            },
            clickAddItem() {
                if ((this.form.item != null) && (this.form.quantity != null)) this.$emit('addItem', this.form);
            }
        }
    }
</script>
