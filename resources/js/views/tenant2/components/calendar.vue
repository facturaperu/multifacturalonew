<template>
      
         <div class="box " > 
              
            <div class="box-body no-padding">
                <div class="el-form-item  col-xs-12">
                    <div class="el-form-item__content">
                        <el-date-picker 
                        :inline=true
                        v-model="d"
                        type="date"
                        name="d"
                        placeholder="Inicio"> 
                        </el-date-picker> 
                    </div>
                </div>

                <div class="el-form-item  col-xs-12">
                    <div class="el-form-item__content">
    
                        <el-date-picker
                        v-model="a"
                        :inline=true 
                        type="date"
                        name="a" 
                        placeholder="Término">
                        </el-date-picker>  
                        
                    </div>
                </div>


                <div class="el-form-item  col-xs-12">
                    <div class="el-form-item__content">
                        <el-select v-model="type_doc" name="type_doc" clearable placeholder="Tipo de Documento">
                            <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                            </el-option>
                        </el-select>
                    </div>
                </div>
                
                <div class="el-form-item  col-xs-12">
                    <div class="el-form-item__content">
                        <button class="btn btn-custom"  type="submit"><i class="fa fa-search"></i> Buscar</button>                                

                    </div>
                </div>
  
            </div> 
         </div>
</template>

<script>

    export default { 
        data() {
            return {
                d:'',
                a:'',

                options: [{
                value: '01',
                label: 'Factura'
                }, {
                value: '03',
                label: 'Boleta'
                }, {
                value: '07',
                label: 'Nota de Crédito'
                }, {
                value: '08',
                label: 'Nota de Débito'
                }, {
                value: '10',
                label: 'Todos'
                }],
                type_doc: '10'
            }
        },
        created() { 
        },
        methods: {
          
        }
    }
</script>