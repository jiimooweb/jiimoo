<template>
    <el-container>
            <el-header>
                <top></top>
            </el-header>
            <el-container>
                <el-aside class="leftAside">
                    <left></left>
                </el-aside>
                <el-aside class="rightAside">
                    <right></right>
                </el-aside>
            </el-container>
    </el-container>
</template>

<script>
import left from '@/components/left'
import right from '@/components/right'
import top from '@/components/top'
    export default {
        data() {
            return{
            }
        },
        components: { left,right,top }

    }
</script>
<style>
.el-menu--vertical>ul{
    min-width: 150px;
}
.el-menu--vertical>ul>li{
    padding: 0 10px !important;
    line-height: 45px;
    height: 45px;
    width: 150px;
}
</style>

<style scoped>
*{
    padding: 0;
}
.el-container>aside{
    min-height: 100% !important;
    position: relative;
    /* border-right:1px solid #1da5d3; */
}
.el-container .leftAside{
    overflow: hidden;
    width: 150px !important;
    transition: all .2s;
}
.el-container .leftAside.active{
    width: 80px !important;
    transition: all .2s;
}
.el-container .rightAside{
    background: #eef5f9;
    width: calc(100% - 150px) !important;
}
.el-container .rightAside.active{
    width: calc(100% - 80px) !important;
}

</style>
<style>
.el-main{
    min-width: 980px;
    background: #fff;
    margin-top: 20px;
    border-radius: 10px;
    margin-left: 10px;
    margin-right: 10px;
}
</style>