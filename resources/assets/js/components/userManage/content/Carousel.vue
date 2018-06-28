<template>
    <el-container>
        <el-main style="min-width:980px;">
            <!-- <el-breadcrumb>
                <el-breadcrumb-item separator='/' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
                </el-breadcrumb-item>
            </el-breadcrumb> -->
            <el-row style="width:500px;float:left;">
                <el-col style="width:500px;">
                    <el-carousel height='250px' style="width:100%;">
                        <el-carousel-item v-for="(item,index) in showPicList" v-if="item.display" :key="index">
                            <img style="width:100%;height:auto;" :src="item.url">
                        </el-carousel-item>
                    </el-carousel>
                </el-col>

            </el-row>
            <el-row style="width:600px;margin-left:50px;float:left;">
                <el-col>
                    <el-button type="primary" size="small" @click="showSwipeGroundDialog">新增</el-button>
                </el-col>
                <el-col>
                    <el-table :data="bannerList" ref="singleTable" @current-change="handleCurrentChange">
                        <el-table-column width="100" label="图组启用">
                            <template slot-scope="scope">
                                <el-switch v-model="bannerList[scope.$index].display" @change="switchChange(scope.$index)" :active-value='1' :inactive-value='0' active-color="#13ce66" inactive-color="#ff4949">
                                </el-switch>
                            </template>
                        </el-table-column>
                        <el-table-column prop="name" label="轮播组名称" width="200"></el-table-column>
                        <el-table-column prop="date" label="操作">
                            <template slot-scope="scope">
                                <el-col :span='6'>
                                    <el-button size="small" type="primary" @click="showNewswipePicDialog(scope.$index)">编辑</el-button>
                                </el-col>
                                <el-col :span='6'>
                                    <el-button size="small" type="danger" @click="deleteSwipeGroup(scope.$index)">删除</el-button>
                                </el-col>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-col>
            </el-row>
        </el-main>
        <el-dialog :visible.sync="swipeGroundDialogShow" title="新增轮播组" @close="swiperGroupHandleClose">
            <el-row>
                <el-col>
                    轮播组名称：
                </el-col>
                <el-col>
                    <el-input v-model="swipeGroundValue.name" placeholder="请输入名称"></el-input>
                </el-col>
            </el-row>
            <span slot="footer" class="dialog-footer">
                <el-button @click="swipeGroundDialogShow = false">取 消</el-button>
                <el-button type="primary" @click="newSwipeGround()">确 定</el-button>
            </span>
        </el-dialog>
        <el-dialog :visible.sync="newswipePicDialogShow" title="编辑轮播组图片">
            <el-row>
                <el-col>
                    图片组
                </el-col>
                <el-col>
                    <el-upload class="upload-demo" :limit='4' action="/qiniuUpload" :headers="headers" :on-success="handleSuccess" :on-preview="handlePreview" :on-remove="handleRemove" :file-list="fileList" list-type="picture">
                        <el-button size="small" type="primary">点击上传</el-button>
                        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                    </el-upload>
                </el-col>
            </el-row>
            <!-- <el-button @click="inputSwipePic" type="primary">提交</el-button> -->
        </el-dialog>
        <el-dialog :visible.sync="onePicDialogShow" @close='handleClose' title="编辑图片">
            <el-row>
                <el-col>
                    图片路径
                </el-col>
                <el-col>
                    <el-input v-model="picMessage.url"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    图片备注
                </el-col>
                <el-col>
                    <el-input v-model="picMessage.remake"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    图片显示
                </el-col>
                <el-col>
                    <el-switch v-model="picMessage.display" :active-value='1' :inactive-value='0' active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    图片所属组
                </el-col>
                <el-col>
                    <el-select v-model="picMessage.group" placeholder="请选择">
                        <el-option v-for="item in bannerList" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-button @click="inputPicMessage" type="primary" style="display:block;margin:20px auto 0;">保存</el-button>
        </el-dialog>
    </el-container>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import loginVue from "../../login.vue";
export default {
    data() {
        return {
            inputSwipeID: "",
            onePicDialogShow: false,
            newswipePicDialogShow: false,
            swipeGroundDialogShow: false,
            currentRow: 1,
            bannerList: [],
            swipeGroundValue: {
                name: ""
            },
            fileList: [
                // {
                //     name:'1',
                //     url:'http://p8r2g6z46.bkt.clouddn.com/sdq12DSs/20180608/3536bdf98a1e1b54a53f34a7ce755d43.jpg'
                // }
            ],
            picMessage: {
                id: "",
                image: "",
                url: "",
                remake: "",
                display: "",
                group: ""
            },
            showPicList: []
        };
    },
    mounted() {
        this.getSwipeGround();
    },
    methods: {
        //单张图片设置
        handleClose() {
            this.getSwipePic(this.inputSwipeID)
            this.newswipePicDialogShow = true;
        },
        //图片列表设置
        swiperGroupHandleClose(){
            this.fileList = []
        },
        handlePreview(file) {
            this.onePicDialogShow = true;
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swipers/" +
                        file.id
                )
                .then(res => {
                    this.picMessage = {
                        id: res.data.data.id,
                        image: res.data.data.image,
                        url: res.data.data.url,
                        remake: res.data.data.remake,
                        display: res.data.data.display,
                        group: res.data.data.group
                    };
                });
            this.newswipePicDialogShow = false;
        },
        //保存pic信息
        inputPicMessage() {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swipers/" +
                        this.picMessage.id,
                    {
                        image: this.picMessage.image,
                        url: this.picMessage.url,
                        remake: this.picMessage.remake,
                        display: this.picMessage.display,
                        group: this.picMessage.group
                    }
                )
                .then(res => {
                    this.onePicDialogShow = false;
                    this.getSwipePic(this.inputSwipeID);
                });
        },
        handleRemove(file) {
            axios.post("/qiniuDelete", { url: file.url }).then(res => {
                axios.delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swipers/" +
                        file.id
                );
            });
        },
        handleSuccess(res) {
            this.picMessage = {
                name: "未命名",
                image: res.url,
                url: "www.rdoorweb.com",
                remake: "未备注",
                display: 1,
                group: this.bannerList[this.inputSwipeID].id
            };
            this.inputSwipePic(this.bannerList[this.inputSwipeID].id);
        },

        showNewswipePicDialog(index) {
            this.newswipePicDialogShow = true;
            this.inputSwipeID = index;
            this.getSwipePic(index);
        },
        showSwipeGroundDialog() {
            this.swipeGroundValue.name = ''
            this.swipeGroundDialogShow = true;
        },
        newSwipeGround() {
            if (this.swipeGroundValue.name != "") {
                this.swipeGroundDialogShow = false;
                var display = 0;
                if (this.bannerList == []) {
                    display = 1;
                }
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/swiper_groups",
                        {
                            name: this.swipeGroundValue.name,
                            display: display
                        }
                    )
                    .then(res => {
                        this.getSwipeGround();
                    });
            } else {
                this.showMessage("error", "轮播组名字不能为空");
            }
        },
        deleteSwipeGroup(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swiper_groups/" +
                        this.bannerList[index].id
                )
                .then(res => {
                    this.getSwipeGround();
                });
        },
        inputSwipePic() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swipers",
                    {
                        name: this.picMessage.name,
                        image: this.picMessage.image,
                        url: this.picMessage.url,
                        remake: this.picMessage.remake,
                        display: this.picMessage.display,
                        group: this.bannerList[this.inputSwipeID].id
                    }
                )
                .then(res => {
                    console.log("上传成功");
                    this.getSwipePic(this.inputSwipeID);
                });
        },
        getSwipePic(index) {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swiper_groups/" +
                        this.bannerList[index].id
                )
                .then(res => {
                    console.log(res.data.data.swipers);
                    
                    this.fileList = [];
                    for (let i = 0; i < res.data.data.swipers.length; i++) {
                        this.fileList.push({
                            name: res.data.data.swipers[i].remake,
                            url: res.data.data.swipers[i].image,
                            id: res.data.data.swipers[i].id,
                            display: res.data.data.swipers[i].display
                        });
                    }
                    if (this.bannerList[index].display == 1) {
                        this.showPicList = this.fileList;
                        console.log(this.showPicList);
                        
                    }
                });
        },
        getSwipeGround() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swiper_groups"
                )
                .then(res => {
                    this.bannerList = res.data.data;
                    for (let i = 0; i < this.bannerList.length; i++) {
                        if (this.bannerList[i].display == 1) {
                            this.getSwipePic(i);
                            return;
                        }
                    }
                });
        },
        //switch change
        switchChange(index) {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/swiper_groups/" +
                        this.bannerList[index].id,
                    {
                        name: this.bannerList[index].name,
                        display: this.bannerList[index].display
                    }
                )
                .then(res => {
                    this.getSwipeGround();
                });
        },
        //表格单选执行
        handleCurrentChange(val) {
            //val 为 选中行的资料
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        }
    },
    computed: {
        headers() {
            return {
                token: localStorage.token
            };
        }
    }
};
</script>

<style scoped>
.el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
}

.el-carousel__item:nth-child(2n + 1) {
    background-color: #d3dce6;
}
</style>