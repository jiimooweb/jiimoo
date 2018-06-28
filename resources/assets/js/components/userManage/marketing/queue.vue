<template>
    <el-main>
        <el-breadcrumb>
            <el-breadcrumb-item separator='>' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
            </el-breadcrumb-item>
        </el-breadcrumb>
        <el-row style="margin:20px 0;">
            <el-col :span='2' style="line-height:40px;">
                排队名称:
            </el-col>
            <el-col :span='5'>
                <el-input placeholder="请输入名称"></el-input>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span='1' style="line-height:40px;">
                队列:
            </el-col>
            <el-col :span='4' :offset="1">
                <el-input v-model="newTag">
                    <el-button size="small" @click="addTag()" slot="append" icon="el-icon-plus"></el-button>
                </el-input>
            </el-col>
            <el-col :span='12' :offset='1'>
                <el-tag v-for="(item,index) in tagList" :key="index" style="margin-right:5px;margin-top:4px;" closable @close="removeTag(index)">{{item.flag}}</el-tag>
                <!-- <el-button v-else class="button-new-tag" size="small" @click="showInput">+ 添加队列</el-button> -->
            </el-col>
        </el-row>
        <el-row style="margin-top:20px;">
            <el-table :data='tagList' border>
                <!-- <el-table-column type="index" v-for='item in tagList' :prop="rList[index].rname" :label="item.name"></el-table-column> -->
                <el-table-column prop="flag" label="队列名" width='100px'></el-table-column>
                <el-table-column label="等待列表">
                    <template slot-scope="scope">
                        <ul class="queueList">
                            <li v-for="(item,index) in tagList[scope.$index].fans" :key="index">
                                <el-tag slot="reference" closable @close="queueCancelPeople(scope.$index)">{{item.no}}</el-tag>
                            </li>
                        </ul>
                    </template>
                </el-table-column>
                <el-table-column width=200>
                    <template slot-scope="scope">
                        <el-row>
                            <el-col :span='10'>
                                <el-button size="small" @click="queueConfirmPeople(scope.$index)" type="primary">确定</el-button>
                            </el-col>
                            <el-col :span='10'>
                                <el-button size="small" @click="queueAddPeople(scope.$index)" type="success">添加</el-button>
                            </el-col>
                        </el-row>

                    </template>
                </el-table-column>
            </el-table>
        </el-row>
        <el-dialog :visible.sync="newOneDialog" title="新增名称">
            <el-row>
                <el-col>
                    姓名：
                </el-col>
                <el-col>
                    <el-input v-model="newName"></el-input>
                </el-col>
            </el-row>
            <el-button style="margin:20px auto 0;display:block;" type='primary' @click="queueAddPeople">
                新增
            </el-button>
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
            newOneDialog: false,
            newName: "",
            newNum: "",
            breadlist: "",
            newTag: "",
            tagList: [
                // {
                //     name: "队列A",
                //     fans: [{ no: "水水" }, { no: "浩浩" }]
                // },
                // {
                //     name: "队列B",
                //     fans: [
                //         { no: "灿灿" },
                //         { no: "东东" },
                //         { no: "发发" }
                //     ]
                // },
                // { name: "队列C" },
                // { name: "队列D" }
            ]
        };
    },
    methods: {
        //添加人员
        queueAddPeople(index) {
            // this.tagList[this.newNum].children.push({ rname: this.newName });
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/queues/" +
                        this.tagList[index].id +
                        "/add"
                )
                .then(res => {
                    this.getQueques();
                });
        },
        //确定人员
        queueConfirmPeople(index){
            axios.get("/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/queues/" +
                        this.tagList[index].fans[0].id +
                        "/confirm").then(res=>{
                            this.getQueques();
            })
        },
        //删除人员
        queueCancelPeople(index){
            axios.get("/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/queues/" +
                        this.tagList[index].fans[0].id +
                        "/cancel").then(res=>{
                            this.getQueques();
            })
        },
        showInput() {
            this.inputVisible = true;
            this.$nextTick(_ => {
                this.$refs.saveTagInput.$refs.input.focus();
            });
        },
        //添加队列
        addTag() {
            if (this.newTag != "") {
                // this.tagList.push({ name: this.newTag });
                axios.post("/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/queues",{name:'未命名',flag:this.newTag}).then(res=>{
                            this.getQueques()
                            this.newTag = ''
                })
            } else {
            }
        },
        removeTag(index) {
            axios.delete("/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/queues/"+this.tagList[index].id).then(res=>{
                            this.getQueques()
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
        //获取队列列表
        getQueques() {
            axios
                .get(
                    "/web/" + store.state.xcx_flag.xcx_flag + "/api/queues"
                )
                .then(res => {
                    console.log(res.data.data);
                    this.tagList = res.data.data;
                });
        }
    },
    watch: {
        $route() {
            this.getBread();
        }
    },
    mounted() {
        this.getBread();
        this.getQueques();
    }
};
</script>

<style scoped>
.el-main {
    background: #fff;
    margin-top: 20px;
    border-radius: 10px;
    margin-left: 10px;
    margin-right: 10px;
}
.queueList {
    padding: 0;
    margin: 0;
}
.queueList > li {
    float: left;
    list-style: none;
    display: block;
    margin: 0 10px;
}
.queueList > li > span {
    padding: 0 15px;
}
</style>