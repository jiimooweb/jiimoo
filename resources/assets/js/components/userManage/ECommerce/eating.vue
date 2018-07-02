<template>
    <el-main>
        <el-row style="min-width:1200px;">
            <el-tabs type="border-card" @tab-click="handletabClick">
                <!-- 商品列表 -->
                <el-tab-pane label="商品列表">
                    <el-row style="margin-bottom: 20px;">
                        <el-col :span="1" style="line-height:40px;">
                            筛选：
                        </el-col>
                        <el-col :span="5">
                            <el-select v-model="search.type" placeholder="请选择">
                                <el-option v-for="item in searchTypeList" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col>
                            <el-button type="primary" size="small" @click="openNewProducts()" style="margin-bottom:20px;">新增</el-button>
                        </el-col>
                        <el-col>
                            <el-table :data="searchfilter?searchfilter:''" border>
                                <el-table-column prop="name" label="商品名称"></el-table-column>
                                <el-table-column label="图片" width="150" align="center">
                                    <template slot-scope="scope">
                                        <img :src="productsList[scope.$index].thumb" width="80px" height='80px'>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="c_price" label="现价" width="100" align="center"></el-table-column>
                                <el-table-column label="原价" width="100" align="center">
                                    <template slot-scope="scope">
                                        <p style="text-decoration:line-through">{{productsList[scope.$index].o_price}}</p>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="intro" label="简介"></el-table-column>
                                <el-table-column label="上架" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="productsList[scope.$index].display" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column label="热卖" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="productsList[scope.$index].hot" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column label="推荐" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="productsList[scope.$index].recommend" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column label="卖完" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="productsList[scope.$index].oversell" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="name" label="操作" width="150" align="center">
                                    <template slot-scope="scope">
                                        <el-button size="small" @click="openEditProducts(scope.$index)" type="primary">编辑</el-button>
                                        <el-button size="small" @click="removeProduct(scope.$index)" type="danger">删除</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-col>
                    </el-row>
                </el-tab-pane>
                <!-- 商品分类 -->
                <el-tab-pane label="商品分类">
                    <el-button type="primary" size="small" @click="openNewType()" style="margin-bottom:20px;">新增</el-button>
                    <el-table :data="productsTypeList" border>
                        <el-table-column prop="id" label="分类ID"></el-table-column>
                        <el-table-column prop="name" label="分类名称"></el-table-column>
                        <el-table-column prop="products_count" label="分类商品数"></el-table-column>
                        <el-table-column label="操作" width="150">
                            <template slot-scope="scope">
                                <el-button size="small" type="primary" @click="openEditType(scope.$index)">编辑</el-button>
                                <el-button size="small" type="danger" @click="removeType(scope.$index)">删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
                <!-- 基础设置 -->
                <el-tab-pane label="基础设置">
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            店名
                        </el-col>
                        <el-col :span="10">
                            <el-input></el-input>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            封面
                        </el-col>
                        <el-col :span="10">
                            <el-upload style="width:100px;height:100px;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarBaseSuccess">
                                <img v-if="baseSetData.thumb" :src="baseSetData.thumb" class="avatar" width="100" height='100'>
                                <i v-else class="el-icon-plus avatar-uploader-icon" style="margin:0 auto;display:block;"></i>
                            </el-upload>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            公告
                        </el-col>
                        <el-col :span="10">
                            <el-input v-model="baseSetData.notice"></el-input>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            是否开启满减优惠
                        </el-col>
                        <el-col :span="10">
                            <el-switch v-model="baseSetData.offer_status" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                            </el-switch>
                        </el-col>
                    </el-row>
                    <el-row style="margin:30px 0 0;">
                        <el-col :span="3">
                            优惠内容
                        </el-col>
                        <el-col :span="10">
                            <el-button type="primary" size="small" style="margin:0 0 20px;">添加规则</el-button>
                            <el-table :data='baseSetData.offer' border>
                                <el-table-column label="满减条件"> 
                                    <template slot-scope="scope">
                                        <el-input v-model="baseSetData.offer[scope.$index].condition"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column label="满减优惠"> 
                                    <template slot-scope="scope">
                                        <el-input v-model="baseSetData.offer[scope.$index].reduce"></el-input>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-col>
                    </el-row>
                    <el-button style="margin:20px 0 0;" type="primary" size="small">保存</el-button>
                </el-tab-pane>
            </el-tabs>
        </el-row>

        <!-- dialog -->
        <el-dialog :visible.sync="productsDialog" :title='productsDialogTitle' width='900px'>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品名称</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品分类</el-col>
                <el-col :span="20">
                    <el-select v-model="productsData.cate_id" placeholder="请选择">
                        <el-option v-for="item in productsTypeList" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品图片</el-col>
                <el-col :span="20">
                    <el-upload style="width:100px;height:100px;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarSuccess">
                        <img v-if="productsData.thumb" :src="productsData.thumb" class="avatar" width="100" height='100'>
                        <i v-else class="el-icon-plus avatar-uploader-icon" style="margin:0 auto;display:block;"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品简介</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.intro"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">原价</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.o_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">现价</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.c_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">上架</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.display" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">热卖</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.hot" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">推荐</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.recommend" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">卖完</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.oversell" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品详情</el-col>
                <el-col :span="20">
                    <quill-editor style="height:400px;" ref="myTextEditor" :content="productsData.content" @change="onEditorChange($event)">
                    </quill-editor>
                </el-col>
            </el-row>
            <el-button type="primary" size="small" style="margin:100px auto 0;display:block;" @click="inputProductData()">提交</el-button>
        </el-dialog>

        <!-- type dialog -->
        <el-dialog :visible.sync="typeDialog" :title='typeDialogTitle' width="500px">
            <el-row>
                <el-col>分类名称</el-col>
                <el-col>
                    <el-input v-model="typeName"></el-input>
                </el-col>
            </el-row>
            <el-button @click="inputProductType()" type="primary" size="small" style="margin:20px auto 0;display:block;">提交</el-button>
        </el-dialog>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import { quillEditor } from "vue-quill-editor";
export default {
    data() {
        return {
            productsTypeList: [],
            searchTypeList: [],
            productsList: [],
            search: {
                type: ""
            },
            productsDialog: false,
            productsDialogTitle: "",
            isNewProdutc: false,
            thisProductIndex: "",
            productsData: {
                name: "",
                thumb: "",
                intro: "",
                o_price: "",
                c_price: "",
                oversell: "",
                content: "",
                hot: "",
                display: "",
                recommend: "",
                cate_id: ""
            },

            thisTypeIndex: "",
            typeDialog: false,
            typeDialogTitle: "",
            typeName: "",
            isNewType: false,

            baseSetData: {
                name: "",
                thumb: "",
                notice: "",
                offer_status: "",
                offer: []
            }
        };
    },
    computed: {
        searchfilter: function() {
            var search = this.search.type;
            if (search) {
                return this.productsList.filter(function(productsList) {
                    return ["cate_id"].some(function(key) {
                        return (
                            String(productsList[key])
                                .toLowerCase()
                                .indexOf(search) > -1
                        );
                    });
                });
            }
            return this.productsList;
        },
        headers() {
            return {
                token: localStorage.token
            };
        }
    },
    methods: {
        //切换前执行
        handletabClick(tab, event) {
            if (tab.index == 0) {
                this.getProductsList();
            } else if (tab.index == 1) {
                this.getProductsType();
            } else if (tab.index == 2){
                this.getbaseSet()
            }
        },
        onEditorChange({ editor, html, text }) {
            this.productsData.content = html;
        },
        //获取商品列表
        getProductsList() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/products"
                )
                .then(res => {
                    this.productsList = res.data.data;
                });
        },
        //获取商品分类列表
        getProductsType() {
            axios
                .get(
                    "/web/" + store.state.xcx_flag.xcx_flag + "/api/foods/cates"
                )
                .then(res => {
                    this.productsTypeList = [];
                    this.searchTypeList = [];
                    this.searchTypeList.push({ name: "所有商品", id: "" });
                    for (let i in res.data.data) {
                        this.productsTypeList.push(res.data.data[i]);
                        this.searchTypeList.push(res.data.data[i]);
                    }
                });
        },
        //打开新增商品
        openNewProducts(index) {
            this.productsDialog = true;
            this.productsDialogTitle = "新增商品";
            this.isNewProdutc = true;

            this.productsData = {
                name: "",
                thumb: "",
                intro: "",
                o_price: "",
                c_price: "",
                oversell: "",
                content: "",
                hot: "",
                display: "",
                recommend: "",
                cate_id: ""
            };
        },
        //打开商品编辑
        openEditProducts(index) {
            this.productsDialog = true;
            this.productsDialogTitle = "编辑商品";
            this.isNewProdutc = false;
            this.thisProductIndex = index;

            this.productsData = this.productsList[index];
        },
        //保存dialogdata
        inputProductData() {
            if (this.isNewProdutc) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/products",
                        {
                            name: this.productsData.name,
                            thumb: this.productsData.thumb,
                            cate_id: this.productsData.cate_id,
                            intro: this.productsData.intro,
                            o_price: this.productsData.o_price,
                            c_price: this.productsData.c_price,
                            oversell: this.productsData.oversell,
                            content: this.productsData.content,
                            hot: this.productsData.hot,
                            display: this.productsData.display,
                            recommend: this.productsData.recommend
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                        this.productsDialog = false;
                        this.getProductsList();
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/products/" +
                            this.productsList[this.thisProductIndex].id,
                        {
                            name: this.productsData.name,
                            thumb: this.productsData.thumb,
                            cate_id: this.productsData.cate_id,
                            intro: this.productsData.intro,
                            o_price: this.productsData.o_price,
                            c_price: this.productsData.c_price,
                            oversell: this.productsData.oversell,
                            content: this.productsData.content,
                            hot: this.productsData.hot,
                            display: this.productsData.display,
                            recommend: this.productsData.recommend
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "修改成功");
                        this.productsDialog = false;
                        this.getProductsList();
                    });
            }
        },
        //删除商品
        removeProduct(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/products/" +
                        this.productsList[index].id
                )
                .then(res => {
                    this.showMessage("success", "删除成功");
                    this.getProductsList();
                });
        },
        //商品switch改变
        changeSwitch(index) {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/products/" +
                        this.productsList[index].id,
                    {
                        name: this.productsList[index].name,
                        thumb: this.productsList[index].thumb,
                        cate_id: this.productsList[index].cate_id,
                        intro: this.productsList[index].intro,
                        o_price: this.productsList[index].o_price,
                        c_price: this.productsList[index].c_price,
                        oversell: this.productsList[index].oversell,
                        content: this.productsList[index].content,
                        hot: this.productsList[index].hot,
                        display: this.productsList[index].display,
                        recommend: this.productsList[index].recommend
                    }
                )
                .then(res => {
                    this.showMessage("success", "修改成功");
                    this.getProductsList();
                });
        },
        //上传图片
        handleAvatarSuccess(response, file, fileList) {
            axios
                .post("/qiniuDelete", { url: this.productsData.thumb })
                .then(res => {
                    this.productsData.thumb = response.url;
                });
        },
        handleAvatarBaseSuccess(response, file, fileList) {
            axios
                .post("/qiniuDelete", { url: this.productsData.thumb })
                .then(res => {
                    this.baseSetData.thumb = response.url;
                });
        },
        //新增商品分类
        openNewType() {
            this.typeName = "";
            this.typeDialog = true;
            this.isNewType = true;
            this.typeDialogTitle = "新增分类";
        },
        //编辑商品分类
        openEditType(index) {
            this.typeName = this.productsTypeList[index].name;
            this.typeDialog = true;
            this.isNewType = false;
            this.typeDialogTitle = "编辑分类";
            this.thisTypeIndex = index;
        },
        //保存商品分类
        inputProductType() {
            if (this.isNewType) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/cates",
                        {
                            name: this.typeName
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                        this.typeDialog = false;
                        this.getProductsType();
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/cates/" +
                            this.productsTypeList[this.thisTypeIndex].id,
                        {
                            name: this.typeName
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                        this.typeDialog = false;
                        this.getProductsType();
                    });
            }
        },
        //删除商品分类
        removeType(index) {
            if (this.productsTypeList[index].products_count !== 0) {
                this.showMessage(
                    "error",
                    "商品分类存在商品无法删除，请先删除分类底下的商品再删除分类"
                );
            } else {
                axios
                    .delete(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/cates/" +
                            this.productsTypeList[index].id
                    )
                    .then(res => {
                        this.showMessage("success", "删除成功");
                        this.getProductsType();
                    });
            }
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },

        //基础设置

        //获取基础设置
        getbaseSet(){
            axios.get("/web/" + store.state.xcx_flag.xcx_flag + "/api/foods/settings").then(res=>{
                this.baseSetData = res.data.data
            })
        }
    },
    mounted() {
        this.getProductsList();
        this.getProductsType();
    }
};
</script>

<style scoped>
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