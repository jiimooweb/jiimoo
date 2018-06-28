<template>
        <div class="pow2">
                <div class="pow2L">
                        <router-link v-for="(item,index) in power2List" :key="index" class="p2link" :default-active="$route.path" :to="item.link">{{item.name}}</router-link>
                </div>
                <div class="pow2R">
                        <router-view></router-view>
                </div>
        </div>
</template>

<script>
        export default {
                name: "power2",
                data(){
                        return{
                                power2List:[
                                        {
                                                name:"页面1",
                                                link:"p21"
                                        },
                                        {
                                                name:"页面2",
                                                link:"p22"
                                        }
                                ]
                        }
                },
                methods:{

                }
        }
</script>

<style scoped>
        .pow2{
                width: 100%;
                height: calc(100% - 50px);
        }
        .pow2L{
                background: #fff;
                width: 200px;
                height: 100%;
                float: left;
        }
        .pow2L>a{
                display: block;
                background: #fff;
                width: calc(100% - 24px);
                padding-left: 24px;
                height: 42px;
                line-height: 42px;
                font-size: 14px;
                color:rgba(0,0,0,.65);
        }
        .pow2L>a.router-link-active{
                padding-left: 20px;
                width: calc(100% - 20px - 4px);
                border-left: 4px solid #3091f2;
                background: rgba(48,145,242,.2);
        }
        .pow2R{
                width: calc(100% - 200px);
                height: 100%;
        }
</style>