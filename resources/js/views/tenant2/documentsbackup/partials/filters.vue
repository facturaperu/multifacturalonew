<template>
 <div class="box"> 
    <div class="box-body no-padding col-md-2">
        <div class="el-form-item  col-xs-12">
            <div class="el-form-item__content">
                <label class="control-label">{{titulo}}</label>
                <el-input v-model="texto"></el-input>
            </div>
        </div>     
        <div class="el-form-item  col-xs-12">
            <div class="el-form-item__content">
                <button class="btn btn-custom" @click="buscar()"><i class="fa fa-search"></i>Buscar</button>                                
            </div>
        </div>
    </div> 
 </div>
</template>

<script>
    export default {
        props: ['type'],
        data() {
            return {
                titulo: '',
                texto: ''
            }
        },
        created() {
          switch (this.type)
            {
               case 'customers': 
                    this.titulo = 'RUC';
               break;
            
               case 'items': 
                    this.titulo = 'Descripción';
               break;
            
               default:  
                    this.titulo = 'Número';
            }
        },
        methods: {
            buscar() {
                this.$emit('search', this.texto)
            }
        }
    }
</script>