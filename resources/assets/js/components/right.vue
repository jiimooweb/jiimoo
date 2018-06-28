<template>
        <div class="rightPage">
                <router-view></router-view>
        </div>
</template>

<script>
import router from '@/router/index.js'
import store from '@/vuex/store'
export default {
        name:'right',
        props:[],
        router,
        data(){
                return{
                        
                }
        },
        methods: {
                loginOut(){
                        localStorage.clear('token')
                        // store.state.auth.user = null
                        this.$router.push({path: '/login'}) 
                }
        }
}
</script>

<style scoped>
.rightPageScroll .el-scrollbar__view{
        height: 100% !important;
}
.rightPage{
        transition: all .2s;
        float: right;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
}
/* 第一列表 */
.pow1{
        width: 90%;
        margin-left: 5%;
        margin-top: 50px;
}

.p1list1{
        overflow: hidden;
        width: 100%;
}
.p1list1>a{
        float: left;
        display: block;
        width: 100%;
        height: 100px;
        background: rgba(238,250,255,.6);
}
.p1list1>a+a{
        margin-top: 10px;
}
.p1list1>a>i{
        width: 60px;
        text-align: center;
        float: left;
        margin-left: 50px;
        line-height: 100px;
        font-size: 60px;
}
.p1t{
        float: left;
        width: 85px;
        height: 100%;
        display: block;
}
.p1t>p:nth-child(1){
        margin: 0;
        color:#333;
        line-height: 36px;
        font-size: 30px;
        padding-left: 15px;
        margin-top: 20px;
}
.p1t>p:nth-child(2){
        color:#333;
        margin: 0;
        line-height: 18px;
        padding-left: 15px;
}

.p1num{
        width: 50%;
        height: 80px;
        margin-top: 10px;
        float: right;
        border-left: 1px solid #bbb;
}

.p1num>p{
        padding-right: 100px;
        color: #888;
        margin: 0;
        line-height: 40px;
        font-size: 14px;
        text-align: right;
}



/* 第二列表 */

.p1list2{
        margin-top: 20px;
        width: 100%;
        overflow: hidden;
}

.p1list2>p{
        font-size: 20px;
        padding-left: 10px;
        color: #333;
        margin: 10px 0;
}

.p1list2>a{
        float: left;
        display: block;
        width: 100%;
        height: 80px;
        margin-top: 10px;
        background: #eaeaea;
}
.p1list2>a>i{
        background: #e7ecfc;
        font-size: 30px;
        color: rgb(55, 134, 252);
        text-align: center;
        float: left;
        line-height: 80px;
        width: 80px;
        height: 80px;
}
.p2t{
        float: left;
        width: calc(100% - 80px);
        height: 100%;
}
.p2t>p:nth-child(1){
        color: #666;
}
.p2t>p{
        margin: 0;
        padding-left: 20px;
        font-size: 14px;
        line-height: 40px;
        color: #888;
}
@media screen and (min-width: 1100px) {
        /* 第一列表 */
        .p1list1>a{
                width: 49%;
        }
        .p1list1>a:nth-child(-n+3){
                margin-top: 0px;
        }
        .p1list1>a:nth-child(n+3){
                margin-top: 10px;
        }
        .p1list1>a:nth-child(2n+2){
                margin-left: 2%;
        }

        /* 第二列表 */

        .p1list2>a{
                width: 49%;
        }
        .p1list2>a:nth-child(2n+3){
                margin-left: 2%;
        }
}

@media screen and (min-width: 1400px) {
        /* 第一列表 */
        .p1list1>a{
                width: 32%;
                margin-top: 0px !important;
        }
        .p1list1>a+a{
                margin-left: 2%;
        }
        .p1num{
                width: 45%;
        }

        /* 第二列表 */
        .p1list2>a{
                width: 32%;
        }
        .p1list2>a+a{
                margin-left: 2%;
        }
        .p1list2>a:nth-child(4n+1){
                margin-left: 0;
        }
}
</style>