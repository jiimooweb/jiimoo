<template>

    <el-container>
        <el-header class="app-header">
            <top></top>
        </el-header>
        <el-main v-loading="loading">

            <el-row style="min-width:1100px;width:1100px; margin:20px auto 0;">
                <el-col :span="1" v-if='isAdmin' style="color:#333;font-weigth:bold;line-height:40px;">
                    <el-button type='primary' @click="openFix">
                        添加
                    </el-button>
                </el-col>
            </el-row>
            <el-table :data="userListT" style="margin-top:10px;width:1100px;min-width:1100px; margin:10px auto;" border>
                <el-table-column prop="id" label="ID" width="50" align="center"></el-table-column>
                <el-table-column label="" width='150'>
                    <template slot-scope="scope">
                        <img :src="userListT[scope.$index].head_img" width='60' height='60' style="border-radius:50%;display:block;margin:0 auto;">
                    </template>
                </el-table-column>
                <el-table-column prop="nick_name" label="小程序名字" width='200'></el-table-column>

                <el-table-column label="星级" width="150" align="center" header-align='left'>
                    <template slot-scope="scope">
                        <!-- {{userListT[scope.$index].pivot.sort}} -->
                        <el-rate :change="changeStart(userListT[scope.$index].pivot.xcx_id,userListT[scope.$index].pivot.sort)" v-model="userListT[scope.$index].pivot.sort"></el-rate>
                    </template>
                </el-table-column>
                <el-table-column prop="updated_at" width='200' label="时间"></el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <!-- scope.$index  -->
                        <el-row>
                            <el-col :span="6" :offset="1" style="width:65px;">
                                <el-button @click.stop="inXCX(scope.$index)" type="primary" size="small">
                                    进 入
                                </el-button>
                            </el-col>
                            <el-col :span="6" :offset="1" v-if='isAdmin' style="width:65px;">
                                <el-button type="primary" @click="intoManage(scope.$index)" size="small">
                                    管 理
                                </el-button>
                            </el-col>
                            <el-col :span="6" :offset="1" v-if='isAdmin' style="width:65px;">
                                <el-button @click.stop="deleteXCX(scope.$index),getList()" type="danger" size="small">
                                    删 除
                                </el-button>
                            </el-col>
                        </el-row>
                    </template>
                </el-table-column>
            </el-table>
            <el-dialog :visible.sync="fixshow" title='新建小程序' class="dialog-footer" width='800px'>
                <el-row style="margin-bottom:10px;">
                    <el-col :span="3" :offset="4" style="color:#333;font-weigth:bold;line-height:40px;">
                        小程序名字
                    </el-col>
                    <el-col :span="13">
                        <el-input v-model="newXCX.name" placeholder="请输入名称"></el-input>
                    </el-col>
                </el-row>
                <!-- <el-row style="margin-bottom:10px;">
                    <el-col :span="3" :offset="4" style="color:#333;font-weigth:bold;line-height:40px;">
                        小程序appid:
                    </el-col>
                    <el-col :span="13">
                        <el-input v-model="newXCX.app_id" placeholder="请输入app_id"></el-input>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:10px;">
                    <el-col :span="3" :offset="4" style="color:#333;font-weigth:bold;line-height:40px;">
                        小程序秘钥:
                    </el-col>
                    <el-col :span="13">
                        <el-input v-model="newXCX.app_secret" placeholder="请输入秘钥"></el-input>
                    </el-col>
                </el-row> -->
                <el-row style="margin-bottom:10px;">
                    <el-col :span="3" :offset="4">
                        开始时间
                    </el-col>
                    <el-col :span="13">
                        <el-date-picker format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd HH-mm-ss" v-model="startTime" type="date" placeholder="选择日期">
                        </el-date-picker>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:10px;">
                    <el-col :span="3" :offset="4">
                        结束时间
                    </el-col>
                    <el-col :span="13">
                        <el-date-picker format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd HH-mm-ss" v-model="endTime" type="date" placeholder="选择日期">
                        </el-date-picker>
                    </el-col>
                </el-row>
                <span slot="footer" class="dialog-footer" style="text-align:center;">
                    <el-button @click="fixshow = false">取 消</el-button>
                    <el-button type="primary" @click="addXCX(newXCX)">确 定</el-button>
                </span>
            </el-dialog>
        </el-main>
    </el-container>

</template>

<script>
// import Api from "../api/index";
import top from "@/components/top";
import axios from "axios";
import store from "@/vuex/store";
import VueAxios from "vue-axios";
import { mapState, mapActions, mapGetters } from "vuex";
import router from "@/router/index.js";
import { SET_USEREMAIL } from "../vuex/mutation-type";
export default {
    components: {
        top
    },
    computed: {
        ...mapGetters({
            user_id: "userId"
        }),
        ...mapState([]),
        isture: function() {
            return this.info[0]["enable"] == 1 ? "是" : "否";
        }
    },
    data() {
        return {
            updataNum:'',
            startTime:'',
            endTime:'',
            xcxTimgDialog: false,
            isAdmin: "",
            loading: true,
            fixshow: false,
            newXCX: {
                name: "",
                app_id: "",
                app_secret: "",
                user_id: ""
            },
            userListT: [],
            userPow: true
        };
    },
    methods: {
        //保存xcx编辑
        inputChange(){
            axios.post('api/xcx/choice/'+this.userListT[this.updataNum].xcx_flag,{
                nick_name:this.userListT[this.updataNum].nick_name,
                start_time:this.startTime,
                end_time:this.endTime
            }).then(res=>{
                this.xcxTimgDialog = false
                this.getList()
            })
        },
        //编辑按钮
        updataXcx(index){
            this.updataNum = index
            this.xcxTimgDialog = true
            this.startTime = this.userListT[index].start_time
            this.endTime = this.userListT[index].end_time
        },
        changeStart(xcxID, sort) {
            axios
                .put("api/user/update_sort", {
                    user_id: store.state.userId,
                    xcx_id: xcxID,
                    sort: sort
                })
                .then(res => {
                    // this.getList()
                });
        },
        getList() {
            axios.get("api/xcx").then(
                response => {
                    if (this.userListT) {
                        this.userListT = [];
                    }
                    for (var i = 0; i < response.data.data.length; i++) {
                        this.userListT.push(response.data.data[i]);
                    }
                    this.loading = false;
                    console.log(response);
                },
                response => {
                    console.log("失败");
                }
            );
        },
        changeList() {
            this.userListT = [];
        },
        getUserlist() {
            axios.get("api/xcx/choice/Fss99");
        },
        openFix() {
            this.fixshow = true;
        },
        addXCX(parms) {
            if (this.name == "" || this.app_id == "" || this.app_secret == "") {
                showMessage("error", "小程序资料不能为空");
            }
            this.loading = true;
            axios
                .post("api/xcx", {
                    nick_name: parms.name,
                    // app_id: parms.app_id,
                    // app_secret: parms.app_secret,
                    // user_id: this.user_id.userId,
                    start_time:this.startTime,
                    end_time:this.endTime
                })
                .then(response => {
                    (this.fixshow = false), this.getList();
                });
        },
        deleteXCX(index) {
            this.loading = true;
            axios
                .delete("api/xcx/" + this.userListT[index].id)
                .then(response => {
                    this.getList();
                });
        },
        inXCX(index) {
            this.intoManage(index);
            this.$router.push({ path: "/userManage" });
        },
        //test
        intoManage(index) {
            store.commit("SET_XCXID", { xcxID: this.userListT[index].id });
            store.commit("SET_XCXFLAG", {
                xcx_flag: this.userListT[index].xcx_flag
            });
            localStorage.setItem('XCXID',this.userListT[index].id)
            localStorage.setItem('XCXFLAG',this.userListT[index].xcx_flag)
            store.commit("SET_NICKNAME", {
                nick_name: this.userListT[index].nick_name
            });
            localStorage.setItem('NICKNAME',this.userListT[index].nick_name)
            this.$router.push({ path: "/manageSet" });
        }
    },
    mounted() {
        if (localStorage.token) {
            this.getList();
            this.newXCX.user_id = store.state.userId;
            if (store.state.identity.identity == 'Admin' || localStorage.getItem('identity') == 'Admin') {
                this.isAdmin = true;
            } else {
                this.isAdmin = false;
            }
        }
    }
};
</script>

<style scoped>
.el-main {
    padding: 0;
}
.Allpage {
    overflow-y: auto;
}
.app-header {
    padding: 0;
}
.userListPage {
    width: 100%;
    min-width: 950px;
    overflow-y: auto;
    min-height: 100%;
    background: #ecf0f5;
}
.userItem {
    background: #fff;
    border-radius: 10px;
    box-shadow: 1px 4px 15px 2px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    width: 80%;
    height: 150px;
}
.userP {
    float: left;
    width: 100px;
    height: 100px;
    margin-left: 20px;
    border-radius: 50%;
    margin-top: 25px;
}
.userP > a {
    display: block;
    width: 100px;
    height: 100px;
}
.userP > a > img {
    /* position: absolute; */
    z-index: 1000;
    width: 100px;
    height: 100px;
}
.userP > a > img[src=""] {
    opacity: 0;
}
.userP > a > i {
    /* position: absolute; */
    z-index: 999;
    display: block;
    margin-top: -103px;
    width: 100px;
    height: 100px;
    font-size: 100px;
    color: rgb(70, 150, 255);
}

.userPow {
    margin-top: 25px;
    width: calc(80% - 100px - 130px);
    margin-left: 5%;
    float: left;
}
.userName {
    line-height: 60px;
    padding-left: 10px;
    font-size: 40px;
    color: #666;
}
.userTime {
    padding-left: 10px;
    color: #999;
}

.userSet {
    width: 10%;
    float: left;
    margin-left: 30px;
    margin-top: 60px;
    height: 40px;
    text-align: center;
}
.userSet > a {
    display: block;
    width: 100px;
    height: 40px;
    color: #666;
    box-shadow: 2px 4px 10px 1px rgba(0, 0, 0, 0.1);
    line-height: 40px;
    border-radius: 10px;
}

.userTop {
    border-bottom: 1px solid #ddd;
    background: #fff;
    width: 100%;
    height: 100px;
}
.logo {
    margin-top: 15px;
    margin-left: 20px;
    height: 70px;
    float: left;
}
.logo img {
    height: 70px;
}
.navList {
    width: 100%;
    padding: 0 10px;
    float: right;
    margin-right: 20px;
}
.navList > a {
    float: left;
    padding: 0 15px;
    color: #999;
    line-height: 100px;
}

.userList {
    padding: 0;
}

.userList > li {
    list-style: none;
    height: 100px;
    border-top: 1px solid #409eff;
    border-bottom: 1px solid #409eff;
}
.userList > li + li {
    margin-top: 20px;
}
.userList > li > a.xcxImg {
    border-radius: 50%;
    display: block;
    background: #bbb;
    float: left;
    width: 80px;
    margin-top: 10px;
    height: 80px;
    margin-left: 50px;
}
.userList > li > p > a.xcxName {
    line-height: 25px;
    color: #333;
    font-size: 20px;
    margin-left: 20px;
}
.manageList {
    float: left;
}
.manageList > a {
    float: left;
    line-height: 25px;
    font-size: 14px;
    margin-left: 20px;
    color: #666;
}
</style>