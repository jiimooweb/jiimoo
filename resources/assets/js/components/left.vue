<template>
    <el-scrollbar style="height:100%;">
        <div class="left" ref="left">
            <div class="leftTitle">
                <p>小程序后台</p>
                <!-- <router-link :to='powerList[0].link'>
                <i class="fa fa-th" aria-hidden="true"></i>&nbsp;总览</router-link> -->
            </div>
            <!-- <div class="leftpage">
                                <p v-for="item1,index in powerList" style="height:42px;">
                                       <router-link :default-active="$route.path" :to="item1['link']">{{item1["name"]}}</router-link>
                                        <ul class="listC">
                                                <li v-for="item2 in item1.listC">
                                                        <router-link :to="item2.link">{{item2.name}}</router-link>
                                                </li>
                                        </ul>
                                </p>
                        </div> -->

            <el-menu :default-active="menu.activeIndex" :router="menu.router" :collapse="menu.collapse" style="width:100%;">
                <el-menu-item index="/userManage/p1">数据分析 </el-menu-item>
                <el-submenu index="2" v-if='ifValue[0].parentIf'>
                    <template slot="title">营销管理>></template>
                    <el-menu-item v-if='ifValue[0].child[0].childIf' index="/userManage/marketing/vip">会员卡</el-menu-item>
                    <el-menu-item v-if='ifValue[0].child[1].childIf' index="/userManage/marketing/queue">排队</el-menu-item>
                    <el-menu-item v-if='ifValue[0].child[2].childIf' index="/userManage/marketing/coupons">优惠券</el-menu-item>
                    <!-- <el-menu-item v-if='ifValue[0].child[3].childIf' index="/">积分</el-menu-item> -->
                    <el-menu-item v-if='ifValue[0].child[3].childIf' index="/userManage/marketing/reservation">预约</el-menu-item>
                    <el-menu-item v-if='ifValue[0].child[4].childIf' index="/userManage/marketing/votes">投票</el-menu-item>
                </el-submenu>
                <!-- <el-submenu index="3">
                    <template slot="title">用户管理</template>
                    <el-menu-item index="/userManage/fans">用户列表</el-menu-item>
                </el-submenu> -->
                <el-menu-item index="/userManage/fans">用户列表 </el-menu-item>
                <el-submenu index="4" v-if='ifValue[1].parentIf'>
                    <template slot="title">展示管理>></template>
                    <!-- <el-menu-item v-if='ifValue[1].child[0].childIf' index="4-2">信息管理</el-menu-item> -->
                    <!-- <el-menu-item v-if='ifValue[1].child[1].childIf' index="/userManage/content/articleType/">文章分类</el-menu-item> -->
                    <el-menu-item v-if='ifValue[1].child[2].childIf' index="/userManage/content/articles/">文章管理</el-menu-item>
                    <!-- <el-menu-item v-if='ifValue[1].child[4].childIf' index="/userManage/content/productsType">产品分类</el-menu-item> -->
                    <el-menu-item v-if='ifValue[1].child[3].childIf' index="/userManage/content/productsList">产品管理</el-menu-item>
                    <el-menu-item v-if='ifValue[1].child[5].childIf' index="/userManage/content/Carousel">轮播图管理</el-menu-item>
                </el-submenu>
                <el-submenu index="5" v-if='ifValue[2].parentIf'>
                    <template slot="title">电商管理>></template>
                    <el-menu-item v-if='ifValue[2].child[0].childIf' index="/userManage/ECommerce/eating">点餐管理</el-menu-item>
                    <!-- <el-menu-item v-if='ifValue[2].child[1].childIf' index="/userManage/ECommerce/shoping">商城管理</el-menu-item> -->
                </el-submenu>
                <el-submenu index="6" v-if='ifValue[3].parentIf'>
                    <template slot="title">人员管理>></template>
                    <el-menu-item v-if='ifValue[3].child[0].childIf' index="/userManage/Personnel/PersonnelType">人员类别</el-menu-item>
                    <el-menu-item v-if='ifValue[3].child[1].childIf' index="/userManage/Personnel/PersonnelList">人员列表</el-menu-item>
                </el-submenu>
                <el-menu-item index="/userManage/wxset">微信设置 </el-menu-item>
                <el-menu-item index="/userManage/user">企业信息 </el-menu-item>
                <!-- <el-menu-item index="/">系统管理</el-menu-item> -->
            </el-menu>

            <!-- <div class="leftClick">
                <a ref="setnav" class="setnav" href="javascript://">
                    <i class="fa fa-bars" aria-hidden="true"></i>导航设置</a>
                <i ref="leftArrow" @click='simLeft' class="leftArrow fa fa-angle-double-left" aria-hidden="true"></i>
            </div> -->
        </div>
    </el-scrollbar>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    name: "left",
    props: [],
    data() {
        return {
            menu: {
                router: true,
                collapse: true,
                activeIndex: "1"
            },
            ifValue: [
                {
                    //营销管理
                    parentIf: false,
                    child: [
                        { childIf: false },
                        { childIf: false },
                        { childIf: false },
                        { childIf: false },
                        { childIf: false }
                    ]
                },
                {
                    //内容管理
                    parentIf: false,
                    child: [
                        { childIf: false },
                        { childIf: false },
                        { childIf: false },
                        { childIf: false },
                        { childIf: false },
                        { childIf: false }
                    ]
                },
                {
                    //电商管理
                    parentIf: false,
                    child: [{ childIf: false }, { childIf: false }]
                },
                //人员管理
                {
                    parentIf:false,
                    child: [{ childIf: false }, { childIf: false }]
                }
            ],
            simTrue: false,
            activeNames: [],
            modelList: [],
            allModelList: [],
            modelChildList: [],
            hasCombo: []
        };
    },
    methods: {
        simLeft: function() {
            // if (!this.simTrue) {
            //     document.querySelector(".leftAside").classList.add("active");
            //     document.querySelector(".rightAside").classList.add("active");
            //     this.$refs.setnav.style.width = "0";
            //     this.$refs.setnav.style.opacity = "0";
            //     this.$refs.leftArrow.style.transform = "rotate(180deg)";
            //     this.$refs.leftArrow.style.width = "80px";
            // } else {
            //     document.querySelector(".leftAside").classList.remove("active");
            //     document
            //         .querySelector(".rightAside")
            //         .classList.remove("active");
            //     this.$refs.setnav.style.width = "109px";
            //     this.$refs.setnav.style.opacity = "1";
            //     this.$refs.leftArrow.style.transform = "rotate(0deg)";
            //     this.$refs.leftArrow.style.width = "40px";
            // }
            // this.simTrue = !this.simTrue;
        },
        getModule() {
            axios
                .get("/api/xcx/choice/" + store.state.xcx_flag.xcx_flag)
                .then(res => {
                    this.hasCombo = res.data.data.hasCombo;
                    this.getAModule();
                    // this.loading = false;
                });
        },
        getAModule() {
            this.modelList = [];
            this.modelChildList = [];
            axios.get("/api/module").then(res => {
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
                                for (
                                    let z = 0;
                                    z < this.hasCombo.sub.length;
                                    z++
                                ) {
                                    if (itId == this.hasCombo.sub[z]) {
                                        it.has = true;
                                    }
                                }
                                this.modelList[j].children.push(it);
                            }
                        }
                        this.modelChildList.push(res.data.data.data[i]);
                    }
                }
                for(let i=0;i<this.modelList.length;i++){
                    for(let j=0;j<this.modelList[i].children.length;j++){
                        if(this.modelList[i].children[j].has){
                            this.ifValue[i].parentIf = true
                            this.ifValue[i].child[j].childIf = true
                        }
                    }
                }
                this.loading = false;
            });
        },

        //存储小程序资料
        storageXCX(){
            store.commit("SET_XCXID", { xcxID: localStorage.getItem('XCXID') });
            store.commit("SET_XCXFLAG", { xcx_flag: localStorage.getItem('XCXFLAG') });
            this.getModule();
        }
    },
    mounted() {
        this.storageXCX()
        
    }
};
</script>

<style>
*{
    padding: 0;
}
.left {
    transition: all 0.2s;
    float: left;
    display: inline-block;
    width: calc(100% - 1px);
    height: 100%;
    overflow: hidden;
}
.leftTitle {
    width: 100%;
}
.leftTitle > p {
    font-size: 18px;
    color: #368dff;
    text-align: center;
}
.leftTitle > a {
    border-bottom: 1px solid #fff;
    padding-bottom: 5px;
    display: block;
    width: 100%;
    font-size: 16px;
    text-align: center;
    color: #368dff;
}

.el-menu-item,.el-submenu__title{
    text-align: center !important;
}
</style>