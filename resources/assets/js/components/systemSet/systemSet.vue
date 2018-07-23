<template>
    <el-container>
        <el-header class="app-header">
            <top></top>
        </el-header>
        <el-main>
            <el-row style="width:1200px;margin:0 auto;">
                <el-col style="margin-top:20px;" v-for="(item,index) in moduleList" :span='6' :offset="index%3 > 0 ? 2 : 0" :key="index">
                    <el-card>
                        <router-link :to="item.link" style="color:#666;font-size:14px;">{{item.name}}</router-link>
                    </el-card>
                </el-col>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
import axios from "axios";
import store from "@/vuex/store";
import VueAxios from "vue-axios";
import top from "@/components/top";
    export default {
        components: { top },
        data(){
            return{
                moduleList:[
                    {
                        name:'模块管理',
                        link:'/moduleSet'
                    },{
                        name:'小程序展示列表',
                        link:'/XCXShowList'
                    }
                ]
            }
        }
    }
</script>

<style scoped>
*{
    padding: 0; 
}
</style>