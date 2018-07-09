<template>
    <el-container>
        <el-header class="app-header">
            <top></top>
        </el-header>
        <el-main  v-loading="loading">
            <el-row style="width:1000px;margin:0 auto;">
                <el-col :offset="5" :span="4" style="line-height:40px;">
                    用户名：
                </el-col>
                <el-col :span="5">
                    <el-input v-model="userinfo.username"></el-input>
                </el-col>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
import store from "@/vuex/store";
import top from "@/components/top";
export default {
    components: {
        top
    },
    data(){
        return{
            loading:false,
            userinfo:{
                username:'',
                email:'',
                avatarUrl:''
            }
        }
    },
    methods:{

    },
    mounted(){  
        this.userinfo.username = store.state.userName
        this.userinfo.email = store.state.userEmail
        this.userinfo.avatarUrl = store.state.avatarUrl
    }
};
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
}
</style>