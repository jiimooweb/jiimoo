<template>
    <el-container v-loading='loading'>
        <el-header class="app-header">
            <top></top>
        </el-header>
        <div style="width:1300px;margin:0 auto;">
            <p style="font-size:18px;color:#666;line-height:80px;">当前小程序：{{this.nick_name.nick_name}}</p>
        </div>
        <el-tabs type="border-card" @tab-click="handletabClick" style="width:1300px;margin:10px auto;">
            <el-tab-pane label="基础信息" style="padding:0 40px;">
                <el-row style="margin-bottom:20px;">
                    <el-col :span="3" style="line-height:40px;">
                        小程序名称
                    </el-col>
                    <el-col :span="10">
                        <el-input v-model="userListT.nick_name"></el-input>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:20px;">
                    <el-col :span="3" style="line-height:60px;">
                        小程序头像
                    </el-col>
                    <el-col :span="10">
                        <img :src="userListT.head_img" width="60px" height="60px" style="border-radius:50%;">
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:20px;">
                    <el-col :span="3" style="line-height:40px;">
                        小程序开始日期
                    </el-col>
                    <el-col :span="10">
                        <el-date-picker v-model="userListT.start_time" format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd HH-mm-ss" type="date" placeholder="选择日期">
                        </el-date-picker>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:20px;">
                    <el-col :span="3" style="line-height:40px;">
                        小程序结束日期
                    </el-col>
                    <el-col :span="10">
                        <el-date-picker v-model="userListT.end_time" format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd HH-mm-ss" type="date" placeholder="选择日期">
                        </el-date-picker>
                    </el-col>
                </el-row>
            </el-tab-pane>
            <el-tab-pane label="用户管理" style="padding:0 40px;">
                <el-button @click="openNewDialog()" type='primary' style="margin:20px 0;display:block;width:70px; height:40px;">新增</el-button>
                <el-table :data="hasUserList">
                    <el-table-column prop="id" label="ID" width="150">
                    </el-table-column>
                    <el-table-column width="200">
                        <template slot-scope="scope">
                            <img width="50" height="50" v-if="hasUserList[scope.$index].avatarUrl" :src="hasUserList[scope.$index].avatarUrl">
                            <img width="50" height="50" v-else src="img/logo.png">
                        </template>
                    </el-table-column>
                    <el-table-column prop="username" label="用户名" width="250">
                    </el-table-column>
                    <el-table-column prop="email" label="邮箱" width="300">
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-row>
                                <el-col>
                                    <el-button style="display:block;width:70px; height:40px;" type='danger' @click="deleteUser(scope.$index)">删除</el-button>
                                </el-col>
                            </el-row>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tab-pane>
            <el-tab-pane label="模块管理">
                <el-main style="width:1200px;margin:0 auto;overflow:hidden;height:80px;">
                    <el-button @click="changeDialog" type="success" round style="width:100px;height:40px;margin:15px 0;">添加</el-button>
                    <el-button @click="updateShow" type="primary" round style="width:100px;height:40px;margin:15px 0;">编辑</el-button>
                    <el-button @click="deleteShow" type="danger" round style="width:100px;height:40px;margin:15px 0;">删除</el-button>
                </el-main>
                <el-scrollbar style="height:600px;">
                    <el-main style="width:1200px;margin:0 auto;overflow-x:hidden;" v-loading="loading">
                        <el-row>
                            <el-collapse v-model="activeNames" @change="handleChange">
                                <el-collapse-item style="font-size:30px;line-height:30px;" v-for="(item,index) in modelList" :title="item.desc+'('+ item.name +')'" :name="item.id+''" :key="index">
                                    <ul class="cardList">
                                        <li @click.stop="clickCard(index,key)" v-for="(child,key) in item.children" :key="key">
                                            <el-card shadow="hover">
                                                {{child.desc}}({{child.name}})
                                                <el-switch style="float:right;height:23px;" v-model="modelList[index].children[key]['has']" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                                            </el-card>
                                        </li>
                                    </ul>
                                </el-collapse-item>
                            </el-collapse>
                        </el-row>
                    </el-main>
                </el-scrollbar>
            </el-tab-pane>
            <el-tab-pane label="模版管理">

            </el-tab-pane>
            <el-tab-pane label="小程序公共变量">
                <el-button @click="addVersion()" style="width:70px;height:40px;">添加</el-button>
                <el-table :data="userListT.ext_json">
                    <el-table-column label="变量名">
                        <template slot-scope="scope">
                            <el-input v-model="userListT.ext_json[scope.$index].name"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column label="变量值">
                        <template slot-scope="scope">
                            <el-input v-model="userListT.ext_json[scope.$index].value"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button @click="removeVersion(scope.$index)" style="width:70px;height:40px;margin:0px auto 0;" type="danger">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-button @click="saveVersion()" style="width:70px;height:40px;margin:20px auto 0;" type="primary">保存</el-button>
            </el-tab-pane>
        </el-tabs>
        <el-dialog title="新增" :visible.sync="centerDialogVisible" width="30%" center>
            <el-row>
                <el-col>
                    模块name(英文)：
                </el-col>
                <el-col>
                    <el-input type='text' v-model="selectName"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    模块desc(中文)：
                </el-col>
                <el-col>
                    <el-input type='text' v-model="selectDesc"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    模块层级：
                </el-col>
                <el-col>
                    <el-select v-model="selectValue" placeholder="请选择层级">
                        <el-option v-for="item in modelList" :key="item.id" :label="item.desc+'('+ item.name +')'" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-button @click="inputNewModel" type='primary' style="width:100px; height:40px;margin-top:20px;">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
        <el-dialog title="删除" :visible.sync="DeleteDialogVisible" width="30%" center>
            <el-row>
                <el-col>
                    <el-select v-model="deleteValue" placeholder="模块">
                        <el-option v-for="(item,index) in allModelList" :key="index" :label="item.desc+'('+ item.name +')'" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-button @click="inputDeleteModel" type='primary' style="width:100px; height:40px;margin-top:20px;">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
        <el-dialog title="修改" :visible.sync="updateDialogVisible" width="30%" center>
            <el-row>
                <el-col>
                    模块：
                </el-col>
                <el-col>
                    <el-select v-model="deleteValue" placeholder="模块">
                        <el-option v-for="(item,index) in allModelList" :key="index" :label="item.desc+'('+ item.name +')'" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    修改后模块name(英文)：
                </el-col>
                <el-col>
                    <el-input type='text' v-model="selectName"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    修改后模块desc(中文)：
                </el-col>
                <el-col>
                    <el-input type='text' v-model="selectDesc"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-button @click="inputUpdateModel" type='primary' style="width:100px; height:40px;margin-top:20px;">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>

        <!-- userList添加 -->
        <el-dialog title="新增用户" width='500px' :visible.sync="newUserDialog">
            <el-row>
                <el-col>
                    新增用户
                </el-col>
                <el-col>
                    <el-select style="width:100%;" v-model="newUserId" placeholder="请选择用户">
                        <el-option style="padding-left:5px;" v-for="item in userList" :key="item.id" :label="item.username" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col>
                    <el-button style="display:block;margin:30px auto 0;width:70px;height:40px;" type='primary' @click="newUser()">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
    </el-container>

</template>

<script>
import Vue from "vue";
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import top from "@/components/top";
export default {
    components: { top },
    data() {
        return {
            loading: true,
            modelName: "",
            selectDesc: "",
            selectName: "",
            selectValue: "",
            deleteValue: "",
            nick_name: store.state.nick_name,
            xcxId: store.state.xcxId,
            nick_name: store.state.nick_name,
            xcx_flag: store.state.xcx_flag,
            centerDialogVisible: false,
            updateDialogVisible: false,
            DeleteDialogVisible: false,
            newUserDialog: false,
            hasUserList: [],
            userList: [],
            newUserId: "",
            activeNames: [],
            isOpen: true,
            modelList: [],
            allModelList: [],
            modelChildList: [],
            hasCombo: [],
            userListT: ""
        };
    },
    methods: {
        handletabClick(tab, event) {
            if (tab.index == 0) {
                this.getProductsList();
            } else if (tab.index == 1) {
                this.getUserList();
            }
        },
        //获得小程序列表
        getList() {
            axios.get("api/xcx").then(
                response => {
                    for (var i = 0; i < response.data.data.length; i++) {
                        if (
                            localStorage.getItem('XCXFLAG') ==
                            response.data.data[i].xcx_flag
                        ) {
                            this.userListT = response.data.data[i];
                            if(this.userListT.ext_json !== ''){
                                this.userListT.ext_json = eval ("(" + this.userListT.ext_json+ ")")
                            }else{
                                this.userListT.ext_json = []
                            }
                        }
                    }
                },
                response => {
                    console.log("失败");
                }
            );
        },
        //切换前执行
        handletabClick(tab, event) {
            if (tab.index == 1) {
                this.getUserList();
            } else if (tab.index == 2) {
                this.getModule();
            }
        },
        //获取小程序相关用户列表
        getUserList() {
            axios
                .get("api/xcx/choice_user/" + localStorage.getItem('XCXFLAG'))
                .then(res => {
                    this.hasUserList = res.data.data.hasUsers;
                    this.userList = res.data.data.users;
                    this.loading = false;
                });
        },
        //添加自定义变量
        addVersion(){
            if(this.userListT.ext_json.length>0){
                if(this.userListT.ext_json[this.userListT.ext_json.length - 1].name != '' && this.userListT.ext_json[this.userListT.ext_json.length - 1].name != null){
                    this.userListT.ext_json.push({name:'',value:''})
                }else{
                    this.$message.error('上一条变量未命名');
                }
            }else{
                this.userListT.ext_json.push({name:'',value:''})
            }
        },
        //保存自定义变量列表
        saveVersion(){
            axios.put('/api/ext_json/'+ localStorage.getItem('XCXID') + '/update',{
                ext_json:this.userListT.ext_json
            }).then(res=>{

            })
        },
        //删除自定义变量
        removeVersion(index){
            this.userListT.ext_json.splice(index,1)
        },
        openNewDialog() {
            this.newUserDialog = true;
        },
        //新增用户
        newUser() {
            let userList = [];
            for (let i = 0; i < this.hasUserList.length; i++) {
                userList.push(this.hasUserList[i].id);
            }
            userList.push(this.newUserId);
            axios
                .post("api/xcx/choice_user/" + localStorage.getItem('XCXFLAG'), {
                    user_ids: userList
                })
                .then(res => {
                    this.newUserDialog = false;
                    this.getUserList();
                });
        },
        //删除用户
        deleteUser(index) {
            this.hasUserList.splice(index, 1);
            let userList = [];
            for (let i = 0; i < this.hasUserList.length; i++) {
                userList.push(this.hasUserList[i].id);
            }
            axios
                .post("api/xcx/choice_user/" + localStorage.getItem('XCXFLAG'), {
                    user_ids: userList
                })
                .then(res => {
                    this.newUserDialog = false;
                    this.getUserList();
                });
        },
        //点击卡牌切换状态
        clickCard(index, index1) {
            let arr1 = []
            console.log(this.nick_name.nick_name);
            
            arr1 = this.hasCombo
                console.log(this.hasCombo === undefined);
            if(this.hasCombo === undefined){
                arr1 = []
            }
            var id = this.modelList[index].children[index1].id + ''
            if(arr1.length > 0){
                for(let i=0;i<arr1.length;i++){
                    if(id == arr1[i]){
                        arr1.splice(i,1)
                        break;
                    }else{
                        if(i == arr1.length-1){
                            arr1.push(id)
                            break;
                        }
                    }
                }
            }else{
                arr1.push(id)
            }
            this.loading = true
            axios
                .post("api/xcx/choice/" + localStorage.getItem('XCXFLAG'), {
                    nick_name: localStorage.getItem('NICKNAME'),
                    app_id: this.xcxId.xcxID,
                    combos: ["999"],
                    modules: arr1
                })
                .then(res => {
                    axios
                        .get("api/xcx/choice/" + localStorage.getItem('XCXFLAG'))
                        .then(res => {
                            this.hasCombo = res.data.data.hasCombo.sub;
                            this.getAModule();
                        });
                });
        },
        //switch状态改变是执行
        switchChange(id, is) {
            if(this.hasCombo.length > 0){
                for(let i=0;i<this.hasCombo.length;i++){
                    if(id.toString() == this.hasCombo[i]){
                        this.hasCombo.splice(i,1)
                        continue
                    }else{
                        if(i == this.hasCombo.length-1){
                            id = id+''
                            this.hasCombo.push(id)
                        }
                    }
                }
            }else{
                id = id+''
                this.$set(this.hasCombo,0,id)
            }
            return
            console.log(this.hasCombo);
            this.loading = true;
            axios
                .post("api/xcx/choice/" + localStorage.getItem('XCXFLAG'), {
                    nick_name: this.nick_name.nick_name,
                    app_id: this.xcxId.xcxID,
                    combos: ["999"],
                    modules: this.hasCombo
                })
                .then(res => {
                    axios
                        .get("api/xcx/choice/" + localStorage.getItem('XCXFLAG'))
                        .then(res => {
                            this.hasCombo = res.data.data.hasCombo.sub;
                            this.getAModule();
                        });
                });
        },
        //清除dialog显示文本
        updateShow() {
            this.updateDialogVisible = true;
            this.selectValue = "";
            this.selectName = "";
            this.selectDesc = "";
        },
        //关闭dialog
        deleteShow() {
            this.DeleteDialogVisible = true;
        },
        //更新模块资料
        inputUpdateModel() {
            if (
                this.deleteValue != "" &&
                this.selectName != "" &&
                this.selectDesc != ""
            ) {
                // this.loading = true;
                this.updateDialogVisible = !this.updateDialogVisible;
                axios
                    .put("api/module/" + this.deleteValue, {
                        name: this.selectName,
                        desc: this.selectDesc
                    })
                    .then(res => {
                        this.getModule();
                    });
            }
        },
        //删除模块资料
        inputDeleteModel() {
            if (this.deleteValue != "") {
                // this.loading = true;
                this.DeleteDialogVisible = !this.DeleteDialogVisible;
                axios.delete("api/module/" + this.deleteValue).then(res => {
                    this.getModule();
                });
            }
        },
        //新建模块资料
        inputNewModel() {
            // this.loading = true;
            this.centerDialogVisible = !this.centerDialogVisible;
            axios
                .post("api/module", {
                    name: this.selectName,
                    desc: this.selectDesc,
                    parent: this.selectValue
                })
                .then(res => {
                    this.getModule();
                });
        },
        changeDialog() {
            this.selectValue = "";
            this.selectName = "";
            this.selectDesc = "";
            this.centerDialogVisible = !this.centerDialogVisible;
        },
        
        handleChange(val) {
            // console.log(val);
        },
        getModule() {
            axios
                .get("api/xcx/choice/" + localStorage.getItem('XCXFLAG'))
                .then(res => {
                    this.hasCombo = res.data.data.hasCombo.sub;
                    this.getAModule();
                    // this.loading = false;
                });
        },
        //获取第一第二层module
        getAModule() {
            this.allModelList = []
            this.deleteValue = ''
            this.modelList = [];
            this.modelChildList = [];
            axios.get("api/module").then(res => {
                //筛选第一层
                for (let i = 0; i < res.data.data.data.length; i++) {
                    if (!res.data.data.data[i].parent) {
                        var it = res.data.data.data[i];
                        it.children = [];
                        this.modelList.push(it);
                        this.activeNames.push(res.data.data.data[i].id + "");
                    }
                    this.allModelList.push(res.data.data.data[i]);
                }
                //筛选第二层
                for (let i = 0; i < res.data.data.data.length; i++) {
                    if (res.data.data.data[i].parent) {
                        for (let j = 0; j < this.modelList.length; j++) {
                            if (
                                res.data.data.data[i].parent ==
                                this.modelList[j].id
                            ) {
                                let it = res.data.data.data[i];
                                let itId = it.id;
                                if(this.hasCombo){
                                    for (
                                        let z = 0;
                                        z < this.hasCombo.length;
                                        z++
                                    ) {
                                        if (itId == this.hasCombo[z]) {
                                            it.has = true;
                                        }
                                    }
                                }
                                this.modelList[j].children.push(it);
                            }
                        }
                        this.modelChildList.push(res.data.data.data[i]);
                    }
                }
                
                this.loading = false;
            });
        }
    },
    mounted() {
        this.getUserList();
        this.getList();
    },
    beforeUpdate: function() {}
};
</script>
<style scoped>
.el-scrollbar .el-scrollbar__wrap {
    overflow-x: hidden !important;
}
* {
    padding: 0px;
    margin: 0px;
}

.cardList {
    padding: 10px 0;
    height: auto;
}
.cardList li {
    cursor: pointer;
    float: left;
    list-style: none;
    width: 30% !important;
    height: 65px;
    margin-left: 3%;
}
.cardList li:nth-child(3n + 1) {
    margin-left: 0px;
}
.cardList li:nth-child(n + 4) {
    margin-top: 20px;
}
.cardList li:last-child {
    margin-bottom: 20px;
}
</style>


