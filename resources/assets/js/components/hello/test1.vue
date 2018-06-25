<template>
    <div>
        <h1>test1</h1>
        <el-button @click="visible = true">按钮</el-button>
        <el-dialog v-model="visible" title="Hello world">
          <p>欢迎使用 Element</p>
        </el-dialog>
        <group>
          <cell title="title" value="value"></cell>
        </group>
    </div>
</template>

<script>
    import { Group, Cell } from 'vux'
    export default{
        components: {
            Group,
            Cell
        },
        data(){
            return {
                visible: false
            }
        },
        methods : {

        },
        mounted(){

        }
    }
</script>