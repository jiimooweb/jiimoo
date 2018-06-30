<template>
<el-row class="headerPage">
            <el-col :span="4">
                <div class="header-logo">
                    <img width='40' height='40' style="margin-top:5px;margin-left:5px;" src="img/logo-w.png" alt="">
                </div>
                <span>
                    任意门网络
                </span>
            </el-col>
            <el-col :span="6" :offset='12'>
                <ul class="header-nav">
                    <li>
                        <a href="javascript://"  @click="loginOut">登出</a>
                    </li>
                    <li>
                        <a href="javascript://" @click="toUserList">主页</a>
                    </li>   
                    <li>
                        <a href="javascript://" v-if='isAdmin' @click="toSystemSet">通用设置</a>
                    </li>   
                </ul>
            </el-col>
        </el-row>
        
</template>

<script>
    import store from "@/vuex/store";
    export default {
        data(){
            return{
                user:{
                    id:'',
                    name:'',
                    
                },
                isAdmin:''
            }
        },
        methods:{
            toSystemSet(){
                this.$router.push({path:'/systemSet'})
            },
            toUserList(){
                this.$router.push({path:'/userList'})
            },
            loginOut(){
                localStorage.clear('token')
                this.$router.push({path:'/login'})
            },
            getuserInfo(){
                
            }
        },
        mounted(){
            // this.getuserInfo()
            if(store.state.identity.identity == 'Admin' || localStorage.getItem('identity') == 'Admin'){
                this.isAdmin = true
            }else{
                this.isAdmin = false
            }
        }
    }
</script>

<style scoped>
        .headerPage{
            background: #1976d2;
            border-bottom: 1px solid #fff;
            height: 60px;
            border-radius: 0 0 20px 20px;
        }
        .header-logo{
            float: left;
            width: 50px;
            height: 50px;
            margin-left: 20px;
            margin-top: 5px;
            border-radius: 50%;
            /* background: #fff; */
        }
        .header-logo+span{
            line-height: 60px;
            font-size: 20px;
            color: #fff;
            font-weight:bold;
        }
        .header-nav{
            width: 100%;
            height: 100%;
            margin: 0px;
        }   
        .header-nav li{
            list-style: none;
            float: right;
            display: block;
        }
        .header-nav li a{
            padding: 0 20px;
            color: #fff;
            line-height: 60px;
        }
</style>