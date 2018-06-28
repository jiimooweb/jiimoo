<template>
    <el-main>
        <el-row style="margin-bottom:20px;">
            <el-col :span="2" style="line-height:40px;font-size:15px;color:#999;">
                授权过滤
            </el-col>
            <el-col :span="10">
                <el-select v-model="statusFilter" placeholder="请选择">
                    <el-option v-for="item in statusList" :key="item.value" :label="item.label" :value="item.value">
                    </el-option>
                </el-select>
            </el-col>
        </el-row>
        
        <el-table :data="searchfilter?searchfilter:''" style="width: 100%">
            <el-table-column prop="id" label="ID" width="100">
            </el-table-column>
            <el-table-column prop="nickname" label="用户名" width="100">
            </el-table-column>
            <el-table-column label="头像" header-align='center' width="120">
                <template slot-scope='scope'>
                    <img v-if="searchfilter[scope.$index].avatarUrl !== ''" :src="searchfilter[scope.$index].avatarUrl" width="60px" height='60px' style="display:block;margin:0 auto;border-radius:50%;border:1px solid #ddd;">
                    <img v-else src="img/logo.png" width="60px" height='60px' style="display:block;margin:0 auto;border-radius:50%;border:1px solid #ddd;">
                </template>
            </el-table-column>
            <el-table-column prop="openid" label="openid" width="300">
            </el-table-column>
            <el-table-column prop="province" label="地区" width="150">
            </el-table-column>
            <el-table-column prop="city" label="城市" width="150">
            </el-table-column>
        </el-table>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    data() {
        return {
            fansList: [],
            statusFilter:'',
            statusList:[
                {value:'',label:'所有用户'},
                {value:1,label:'已授权'},
                {value:0,label:'未授权'}
                ]
        };
    },
    methods: {
        getFansList() {
            axios
                .get("/web/" + store.state.xcx_flag.xcx_flag + "/api/fans")
                .then(res => {
                    this.fansList = res.data.data;
                });
        }
    },
    computed:{
        searchfilter: function() {
            var search = this.statusFilter;
            if (search) {
                return this.fansList.filter(function(fansList) {
                    return ["status"].some(function(key) {
                        return (
                            String(fansList[key])
                                .toLowerCase()
                                .indexOf(search) > -1
                        );
                    });
                });
            }
            return this.fansList;
        },
    },
    mounted() {
        this.getFansList();
    }
};
</script>

<style scoped>
</style>