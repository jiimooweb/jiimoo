<template>
    <el-main>
        <el-breadcrumb>
            <el-breadcrumb-item separator='/' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
            </el-breadcrumb-item>
        </el-breadcrumb>
        <el-row>
            <el-row>
                <el-col :span='2'>
                    <el-button @click.stop="newType" type="button" size="small" style="margin:20px 0;">
                        添加
                    </el-button>
                </el-col>
                <el-col :span='3' style="margin:16px 20px;float:right;">
                    <el-button @click="toList()">产品列表>></el-button>
                </el-col>
            </el-row>
            <el-table :data="typeList?typeList:''" border>
                <el-table-column prop="name" label="分类名称"></el-table-column>
                <el-table-column prop="pid" label="归属分类" width='80'></el-table-column>
                <el-table-column prop="id" label="分类ID" width='80'></el-table-column>
                <el-table-column label="编辑" width='80'>
                    <template slot-scope="scope">
                        <!-- scope.$index  -->
                        <el-button @click.stop="getRowArticle(scope.$index)" :title="scope.row" type="primary" size="small">
                            编辑
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column label="删除" width='80'>
                    <template slot-scope="scope">
                        <!-- scope.$index  -->
                        <el-button @click.stop="removeArticleType(scope.$index)" :title="scope.row" type="danger" size="small">
                            删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-dialog :visible.sync="newTypeDialog" class="dialog-footer" width='950px' height='auto !important'>
                <el-row style="margin-bottom:20px;">
                    <el-col :span="2" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                        分类名称:
                    </el-col>
                    <el-col :span="10">
                        <el-input v-model="typeName" placeholder="请输入分类名称"></el-input>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:20px;">
                    <el-col :span="2"  :offset="5">
                        父级分类:
                    </el-col>
                    <el-col  :span="10">
                        <el-select v-model="typePid" placeholder="请选择">
                            <el-option v-for="item in typeList" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-col>
                </el-row>
                <el-row v-if="!newTypeState"  style="margin-bottom:20px;">
                    <el-col :span="2" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                        分类ID:
                    </el-col>
                    <el-col :span="10">
                        <el-input v-model="typeID" :disabled='typeIDDisplay'></el-input>
                    </el-col>
                </el-row>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="newTypeDialog = false">取 消</el-button>
                    <el-button type="primary" @click="isNewType()">确 定</el-button>
                </span>
            </el-dialog>
        </el-row>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    data() {
        return {
            breadlist: "",
            typeList: [],
            typePid: "",
            newTypeDialog: false,
            newTypeState: false,
            typeName: "",
            typeID: "",
            typeIDDisplay:true
        };
    },
    methods: {
        toList(){
            this.$router.push({path:'/userManage/content/productsList'})
        },
        getProductsType() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/product_cates"
                )
                .then(res => {
                    this.typeList = res.data.data
                });
        },
        //编辑按钮
        getRowArticle(index){
            this.newTypeDialog = true
            this.newTypeState = false
            this.typeName = this.typeList[index].name,
            this.typeID = this.typeList[index].id,
            this.typePid = this.typeList[index].pid
        },
        removeArticleType(index){
            axios.delete("/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/product_cates/"+this.typeList[index].id).then(res=>{
                                this.getProductsType()
            })
        },
        getBread() {
            this.breadlist = this.$route.matched;
            this.$route.matched.forEach((item, index) => {
                //先判断父级路由是否是空字符串或者meta是否为首页，直接复写路径到根路径
                item.meta.CName === "首页"
                    ? (item.path = "/")
                    : this.$route.path === item.path;
            });
        },
        newType() {
            this.newTypeDialog = true;
            this.newTypeState = true;
            this.typeName = ''
            this.typePid = ''
            
        },
        isNewType() {
            if (this.newTypeState) {
                //添加
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/product_cates",
                        { name: this.typeName, pid: this.typePid==''?0:this.typePid }
                    )
                    .then(res => {
                        this.newTypeDialog = false;
                        this.getProductsType()
                    });
            } else {
                //修改
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/product_cates/"+this.typeID,
                        { name: this.typeName, pid: this.typePid==''?0:this.typePid }
                    )
                    .then(res => {
                        this.newTypeDialog = false;
                        this.getProductsType()
                    });
            }
        }
    },
    watch: {
        $route() {
            this.getBread();
        }
    },
    mounted() {
        this.getBread();
        this.getProductsType();
    }
};
</script>

<style scoped>
</style>