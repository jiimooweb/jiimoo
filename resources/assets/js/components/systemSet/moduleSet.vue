<template>
    <el-container>
        <el-header class="app-header">
            <top></top>
        </el-header>
        <el-main v-loading='loading'>
            <el-row style="width:1200px;margin:20px auto;">
                <el-col :span="2">
                    <el-button type='primary' style='padding:10px 20px;' @click="showDialog">新增模板</el-button>
                </el-col>
            </el-row>
            <el-row style="width:1200px;margin:0 auto;">
                <el-col :span="5" v-for="(item,index) in this.componentsList" :key="index" :offset="index%4 > 0 ? 1 : 0" style="margin-top:20px;">
                    <el-card :body-style="{ padding: '0px' }">
                        <img :src="item.thumb" class="image">
                        <div style="padding: 14px;">
                            <span>{{item.title}}</span>
                            <span>({{item.version}})</span>
                            <div class="bottom clearfix">
                                <time class="time">{{item.updated_at}}</time>
                                <el-button type="danger" class="button" @click="deleteItem(index)">删除</el-button>
                                <el-button type="primary" class="button" @click="setDialog(index)" style="margin-right:5px;">编辑</el-button>
                            </div>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </el-main>
        <el-dialog :before-close="handleClose" :title='dialogTitle' :visible.sync="dialogShow">
            <el-row>
                <el-col>
                    模板ID
                </el-col>
                <el-col>
                    <el-input v-model="dialogValue.template_id"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    标题
                </el-col>
                <el-col>
                    <el-input v-model="dialogValue.title"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    封面图
                </el-col>
                <el-col>
                    <el-upload action="/qiniuUpload" v-model="dialogValue.thumb" list-type="picture-card" :limit="1" :onSuccess="uploadSuccess" :file-list="thumbPic" :headers="headers" :on-remove="handleRemove">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    二维码
                </el-col>
                <el-col>
                    <el-upload action="/qiniuUpload" list-type="picture-card" :limit="1" :onSuccess="uploadSuccessQrcode" :file-list="qrcodePic" :headers="headers" :on-remove="handleRemoveQrcode">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    简介
                </el-col>
                <el-col>
                    <el-input v-model="dialogValue.desc"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    版本号
                </el-col>
                <el-col>
                    <el-input v-model="dialogValue.version"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' :offset='10'>
                    <el-button type='primary' style="margin:20px auto;width:100%;height:40px;" @click="isNeworUpdata()">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
    </el-container>
</template>

<script>
import axios from "axios";
import store from "@/vuex/store";
import VueAxios from "vue-axios";
import top from "@/components/top";
export default {
    components: { top },
    computed: {
        headers() {
            return {
                token: localStorage.token
            };
        }
    },
    data() {
        return {
            isNew: true,
            loading: true,
            dialogTitle: "新增",
            dialogShow: false,
            dialogValue: {
                id: "",
                template_id: "",
                title: "",
                thumb: "",
                qrcode: "",
                desc: "",
                version: "",
                ext_json: ""
            },
            componentsList: [
                {
                    id: "",
                    template_id: "",
                    title: "",
                    thumb: "",
                    qrcode: "",
                    desc: "",
                    version: "",
                    ext_json: ""
                }
            ],
            thumbPic: [],
            qrcodePic: []
        };
    },
    methods: {
        deleteItem(index) {
            this.loading = true;
            axios
                .delete("/api/templates/" + this.componentsList[index].id)
                .then(res => {
                    this.getComponentsList();
                });
        },
        handleClose() {
            if (!this.isNew) {
                this.thumbPic = [];
                this.qrcodePic = [];
            }
            this.dialogShow = false;
        },
        showDialog() {
            if (this.isNew) {
                this.dialogShow = true;
            } else {
                this.isNew = true;
                this.dialogShow = true;
                this.dialogValue = {
                    id: "",
                    template_id: "",
                    title: "",
                    thumb: "",
                    qrcode: "",
                    desc: "",
                    version: "",
                    ext_json: ""
                };
            }
        },
        setDialog(index) {
            this.isNew = false;
            this.dialogShow = true;
            this.dialogTitle = "修改";
            this.dialogValue.id = this.componentsList[index].id;
            this.dialogValue.template_id = this.componentsList[
                index
            ].template_id;
            this.dialogValue.title = this.componentsList[index].title;
            this.dialogValue.thumb = this.componentsList[index].thumb;
            this.thumbPic = [];
            this.thumbPic.push({
                name: "thumb",
                url: this.componentsList[index].thumb
            });
            this.dialogValue.qrcode = this.componentsList[index].qrcode;
            this.qrcodePic = [];
            this.qrcodePic.push({
                name: "qrcode",
                url: this.componentsList[index].qrcode
            });
            this.dialogValue.desc = this.componentsList[index].desc;
            this.dialogValue.version = this.componentsList[index].version;
            this.dialogValue.ext_json = this.componentsList[index].ext_json;
        },
        uploadSuccess(response) {
            this.dialogValue.thumb = response.url;
            console.log(this.dialogValue.thumb);
        },
        uploadSuccessQrcode(response) {
            this.dialogValue.qrcode = response.url;
        },
        handleRemove() {
            axios
                .post("/qiniuDelete", { url: this.dialogValue.thumb })
                .then(res => {
                    this.dialogValue.thumb = "";
                });
        },
        handleRemoveQrcode() {
            axios
                .post("/qiniuDelete", { url: this.dialogValue.qrcode })
                .then(res => {
                    this.dialogValue.qrcode = "";
                });
        },
        beforeAvatarUpload() {
            const isJPG = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;

            if (!isJPG) {
                this.$message.error("上传头像图片只能是 JPG 格式!");
            } else if (!isLt2M) {
                this.$message.error("上传头像图片大小不能超过 2MB!");
            }
            return isJPG && isLt2M;
        },
        getComponentsList() {
            axios.get("/api/templates").then(res => {
                this.componentsList = res.data.data;
                for (let i = 0; i < this.componentsList.length; i++) {
                    this.componentsList[i].updated_at = this.componentsList[
                        i
                    ].updated_at.slice(0, 10);
                }
                console.log(this.componentsList);
                
                this.loading = false;
                this.thumbPic = [];
                    this.qrcodePic = [];
                    this.dialogValue = {
                        id: "",
                        template_id: "",
                        title: "",
                        thumb: "",
                        qrcode: "",
                        desc: "",
                        version: "",
                        ext_json: ""
                    };
            });
        },
        isNeworUpdata() {
            if (this.isNew) {
                this.newComponents();
            } else {
                this.updataComponents();
            }
        },
        newComponents() {
            this.dialogShow = false;
            this.loading = true;
            axios
                .post("/api/templates", {
                    template_id: this.dialogValue.template_id,
                    title: this.dialogValue.title,
                    thumb: this.dialogValue.thumb,
                    qrcode: this.dialogValue.qrcode,
                    desc: this.dialogValue.desc,
                    version: this.dialogValue.version,
                    ext_json: this.dialogValue.ext_json
                })
                .then(res => {
                    this.getComponentsList();
                });
        },
        updataComponents() {
            this.dialogShow = false;
            this.loading = true;
            axios
                .put("/api/templates/" + this.dialogValue.id, {
                    template_id: this.dialogValue.template_id,
                    title: this.dialogValue.title,
                    thumb: this.dialogValue.thumb,
                    qrcode: this.dialogValue.qrcode,
                    desc: this.dialogValue.desc,
                    version: this.dialogValue.version,
                    ext_json: this.dialogValue.ext_json
                })
                .then(res => {
                    this.getComponentsList();
                });
        }
    },
    mounted() {
        this.getComponentsList();
    }
};
</script>

<style scoped>
* {
    padding: 0;
}
.time {
    font-size: 13px;
    color: #999;
    line-height: 30px;
}

.bottom {
    margin-top: 13px;
    line-height: 12px;
}

.button {
    padding: 0;
    float: right;
    padding: 8px 13px;
}

.image {
    width: 100%;
    display: block;
}

.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}

.clearfix:after {
    clear: both;
}
.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.avatar-uploader .el-upload:hover {
    border-color: #409eff;
}
.avatar-uploader-icon {
    border: 1px solid #ddd;
    font-size: 28px;
    color: #8c939d;
    width: 100px;
    height: 100px;
    line-height: 100px;
    text-align: center;
}
.avatar {
    width: 100px;
    height: 100px;
    display: block;
}
</style>