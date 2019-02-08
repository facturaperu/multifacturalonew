<template>
    <div>
        <div class="text-center" style="color: #CCC; cursor: pointer;" @click="dialogVisible = true">
            <img :src="src" class="img-fluid" style="max-height: 70px;">
        </div>
        <div class="">
            <el-dialog title="Logo" class="text-left" :visible.sync="dialogVisible" @closed="closed">
                <p class="text-center">* Se recomienda resoluciones 700x300.</p>
                <div class="text-center">
                    <el-upload class="uploader" slot="append" :headers="headers" :data="{'type': 'logo'}" action="/companies/uploads" :show-file-list="false" :before-upload="beforeAvatarUpload" :on-success="successUpload">
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
            src() {
                if (this.path_logo != '') return this.path_logo;
                
                return '/logo/700x300.jpg';
            }
        },
        methods: {
            beforeAvatarUpload(file) {
                const isIMG = ((file.type === 'image/jpeg') || (file.type === 'image/png') || (file.type === 'image/jpg'));
                const isLt2M = file.size / 1024 / 1024 < 2;
                
                if (!isIMG) this.$message.error('La imagen no es valida!');
                if (!isLt2M) this.$message.error('La imagen excede los 2MB!');
                
                return isIMG && isLt2M;
            },
            successUpload(response, file, fileList) {
                this.imageUrl = URL.createObjectURL(file.raw);
                
                if (response.success) {
                    this.$message.success(response.message);
                    
                    return;
                }
                
                this.$message({message:'Error al subir el archivo', type: 'error'});
                this.imageUrl = '';
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
