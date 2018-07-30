<template> 
    <div>
        <el-header class="app-header">
            <top></top>
        </el-header>
        <el-row style="width:1200px;margin:20px auto;">
            <el-col :span="2">
                <el-button type='primary' style='padding:10px 20px;' @click="showDialog">新增模板</el-button>
            </el-col>
        </el-row>
        <el-row style="width:1200px;margin:0 auto;">
            <el-col :span="5" v-for="(item,index) in xcxList" :key="index" :offset="index%4 > 0 ? 1 : 0" style="margin-top:20px;">
                <el-card :body-style="{ padding: '0px' }">
                    <img :src="item.thumb" class="image">
                    <div style="padding: 14px;">
                        <span>{{item.title}}</span>
                        <span>（{{item.price}}元）</span> 
                        <div class="bottom clearfix">
                            <!-- <time class="time">{{item.updated_at}}</time> -->
                            <el-button type="danger" class="button" @click="deleteItem(index)">删除</el-button>
                            <el-button type="primary" class="button" @click="setDialog(index)" style="margin-right:5px;">编辑</el-button>
                        </div>
                    </div>
                </el-card> 
            </el-col>
        </el-row> 
        <el-dialog :title='dialogTitle' :visible.sync="dialogShow">
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
                    价格
                </el-col>
                <el-col>
                    <el-input v-model="dialogValue.price"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    app_id
                </el-col>
                <el-col>
                    <el-input v-model="dialogValue.app_id"></el-input>
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
                <el-col :span='3' :offset='10'>
                    <el-button type='primary' style="margin:20px auto;width:100%;height:40px;" @click="isNeworUpdata()">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
    </div>
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
            a:'',
            xcxList: [],
            isNew: false,
            dialogShow: false,
            dialogTitle:'',
            thumbPic: [],
            dialogValue: {
                id: "",
                title: "",
                thumb: "",
                price: "",
                app_id: ""
            }
        };
    },
    methods: {
        getXCXShowList() {
            axios.get("/api/miniprogram").then(res => {
                this.xcxList = res.data.data;
            });
        },
        uploadSuccess(response) {
            this.dialogValue.thumb = response.url;
            console.log(this.dialogValue.thumb);
        },
        handleRemove() {
            axios
                .post("/qiniuDelete", { url: this.dialogValue.thumb })
                .then(res => {
                    this.dialogValue.thumb = "";
                });
        },
        isNeworUpdata() {
            if (this.isNew) {
                this.newXCX();
            } else {
                this.updataXCX();
            }
        },
        showDialog() {
            this.dialogShow = true;
            this.isNew = true;
            this.thumbPic = [];
            this.dialogTitle = '新增'
        },
        setDialog(index) {
            this.isNew = false;
            this.dialogShow = true;
            this.dialogValue = {
                id: this.xcxList[index].id,
                title: this.xcxList[index].id,
                thumb: this.xcxList[index].thumb,
                price: this.xcxList[index].price,
                app_id: this.xcxList[index].app_id
            };
            this.thumbPic.push({
                name: "thumb",
                url: this.xcxList[index].thumb
            });
            this.dialogTitle = '修改'
        },
        newXCX() {
            axios
                .post("/api/miniprogram", {
                    title: this.dialogValue.title,
                    thumb: this.dialogValue.thumb,
                    price: this.dialogValue.price,
                    app_id: this.dialogValue.app_id
                })
                .then(res => {
                    this.getXCXShowList();
                    this.dialogShow = false;
                    this.dialogValue = {
                        id: "",
                        title: "",
                        thumb: "",
                        price: "",
                        app_id: ""
                    }
                });
        },
        updataXCX() {
            axios
                .put("/api/miniprogram/" + this.dialogValue.id, {
                    title: this.dialogValue.title,
                    thumb: this.dialogValue.thumb,
                    price: this.dialogValue.price,
                    app_id: this.dialogValue.app_id
                })
                .then(res => {
                    this.getXCXShowList();
                    this.dialogShow = false;
                    this.dialogValue = {
                        id: "",
                        title: "",
                        thumb: "",
                        price: "",
                        app_id: ""
                    }
                });
        },
        deleteItem(index){
            console.log(index);
            
            axios
                .delete("/api/miniprogram/" + this.xcxList[index].id)
                .then(res => {
                    this.getXCXShowList();
                });
        }
    },
    mounted() {
        this.getXCXShowList();
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