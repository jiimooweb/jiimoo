<template>
    <div class="Allpage">
        <router-view>

        </router-view>
    </div>
</template>


<script>
    export default{
        components : {

        },
        data(){
            return {

            }
        },
        methods : {

        },
        mounted(){

        }
    }
</script>

<style scoped>
*{
    font-family: '微软雅黑' !important;   
}
.Allpage {
    font-family: '微软雅黑' !important;   
  min-width: 1000px;
  width: 100%;
  height: 100%;
}
.Allpage > section {
  height: 100%;
}
.el-scrollbar__view{
  height: 100%;
}
</style>