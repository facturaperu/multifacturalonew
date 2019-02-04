<template>
    <div>
        <div class="ib" v-if="path_logo != ''">
            <img :src="path_logo" class="img-fluid" style="max-height: 70px;">
        </div>
        <div v-else class="text-center" style="color: #CCC; cursor: pointer;" @click="dialogVisible = true">
            <i class="fa fa-circle fa-4x"></i>
        </div>
        <div class="">
            <el-dialog title="Logo" class="text-left" :visible.sync="dialogVisible" @closed="closed">
                <p class="text-center">* Se recomienda resoluciones 700x300.</p>
                <div class="text-center">
                    <el-upload class="uploader" slot="append" :headers="headers" :data="{'type': 'logo'}" action="/companies/uploads" :show-file-list="false" :on-success="successUpload">
                        <img v-if="imageUrl" width="100%" :src="imageUrl" alt="">
                        <i v-else class="el-icon-plus uploader-icon"></i>
                    </el-upload>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="dialogVisible = false">Cerrar</el-button>
                </span>
            </el-dialog>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'path_logo'],
        data() {
            return {
                headers: headers_token,
                dialogVisible: false,
                imageUrl: ''
            }
        },
        computed: {
            href() {
                if (this.path_logo == '') return '#';
                
                return this.url;
            }
        },
        methods: {
            successUpload(response, file, fileList) {
                this.imageUrl = URL.createObjectURL(file.raw);
                
                if (response.success) {
                    this.$message.success(response.message);
                    
                    return;
                }
                
                this.$message({message:'Error al subir el archivo', type: 'error'});
            },
            closed() {
                this.dialogVisible = false;
                
                location.href = this.url;
            }
        }
    }
</script>

<style lang="scss">
    .uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>
