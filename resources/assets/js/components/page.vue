
<template>
  <div class="Allpage">
    <router-view></router-view>
  </div>
</template>

<script>
import router from "@/router/index.js";

export default {
    name: "page",
    props: [],
    router
};
</script>


<style scoped>
* {
    font-family: "微软雅黑" !important;
}
.Allpage {
    font-family: "微软雅黑" !important;
    min-width: 1000px;
    width: 100%;
    height: 100%;
}
.Allpage > section {
    height: 100%;
}
.el-scrollbar__view {
    height: 100%;
}
</style>