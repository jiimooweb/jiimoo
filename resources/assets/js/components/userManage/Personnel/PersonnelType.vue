<template>
    <el-main style="min-width:950px;">
        <el-row style="margin-bottom:50px;">
            <el-breadcrumb>
                <el-breadcrumb-item separator-class="el-icon-arrow-right" v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </el-row>
        <el-row>
            <el-button type='success' size="small" style="margin-bottom:10px;" @click="fixShow = true,newTypeName = ''">新增</el-button>
            <el-table border :data="personnelType?personnelType:''" >
                <el-table-column prop="career" label="类别名称/标签名称"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-row>
                            <!-- <el-col :span='4'>
                                <el-button type='primary' @click="setType(scope.$index)">编辑</el-button>
                            </el-col> -->
                             <el-col :span='4'>
                                <el-button type='danger' size="small" @click="deletePersonnelType(scope.$index)">删除</el-button>
                            </el-col>
                        </el-row>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>
        <el-dialog :visible.sync="fixShow" class="dialog-footer" style="width:800px;margin:0 auto;" title="新增">
            <el-row>
                <el-col :span="5" style="line-height:40px;">
                    类别名称
                </el-col>
                <el-col>
                    <el-input placeholder="请输入类别名称" v-model="newTypeName"></el-input>
                </el-col>
            </el-row>
            <el-button type='primary' size="small" style="display:block;margin:20px auto 0;" @click="newPersonnelType">新增</el-button>
        </el-dialog>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    data() {
        return {
            personnelType:[],
            breadlist:'',
            fixShow:false,
            newTypeName:''
        }
    },
    methods: {
        getBread() {
            this.breadlist = this.$route.matched;
            this.$route.matched.forEach((item, index) => {
                //先判断父级路由是否是空字符串或者meta是否为首页，直接复写路径到根路径
                item.meta.CName === "首页"
                    ? (item.path = "/")
                    : this.$route.path === item.path;
            });
        },
        getPersonnelType(){
            axios.get('/web/'+ store.state.xcx_flag.xcx_flag +'/api/displays/careers').then(res=>{
                console.log(res.data.data.data)
                this.personnelType = res.data.data.data
            })
        },
        newPersonnelType(){
            axios.post('/web/'+ store.state.xcx_flag.xcx_flag +'/api/displays/careers',{career:this.newTypeName}).then(res=>{
                this.getPersonnelType()
                this.fixShow = false
            })
        },
        deletePersonnelType(index){
            axios.delete('/web/'+ store.state.xcx_flag.xcx_flag +'/api/displays/careers/'+this.personnelType[index].id).then(res=>{
                this.getPersonnelType()
            })
        }
    },
    watch: {
        $route() {
            this.getBread()
        }
    },
    mounted(){
        this.getBread()
        this.getPersonnelType()
    }
};
</script>

<style scoped>
.el-main{
    background: #fff;
    margin-top: 20px;
    border-radius: 10px;
    margin-left: 10px;
    margin-right: 10px;
}

</style>