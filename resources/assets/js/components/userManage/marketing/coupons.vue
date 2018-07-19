<template>
    <el-main>
        <el-tabs type="border-card">
            <el-tab-pane label="优惠券管理">
                <el-button type="primary" style="margin:20px 0;" @click="openCouponsDialog(0)">新增优惠券</el-button>
                <el-row style="min-width:1200px;">
                    <el-col :span="7" :offset="1" v-for="(item,key) in couponsList" :key='key' class="couponsCard">
                        <el-card :body-style="{ padding: '10px' }">
                            <div class="imgPPage">
                                <div class="imagePage">
                                    <img :src="item.thumb" class="image">
                                </div>
                            </div>
                            <div style="padding: 14px 14px 0 14px;">
                                <span class="cardName">{{item.name}}</span>
                                <br>
                                <time class="time">2018-8-8</time>
                                <div class="bottom clearfix">
                                    <el-button class="button" @click="removeCoupons(key)" style="margin-top:20px;width:60px;height:30px;" type="danger">删除</el-button>
                                    <el-button class="button" @click="openCouponsDialog(1,key)" style="margin-top:20px;width:60px;height:30px;" type="primary">编辑</el-button>
                                </div>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
            </el-tab-pane>
            <el-tab-pane label="优惠券派发">
                
            </el-tab-pane>
        </el-tabs>
        
        <el-dialog :visible.sync="couponsDialog" class="couponsDialog" width='500px' :title='couponsDialogTitle'>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    优惠券名称
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="couponsData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    优惠券类型
                </el-col>
                <el-col :span='20'>
                    <template>
                        <el-radio v-model="couponsData.type" :label="0">代金券</el-radio>
                        <el-radio v-model="couponsData.type" :label="1">折扣券</el-radio>
                        <el-radio v-model="couponsData.type" :label="2">服务券</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    在兑换中心显示
                </el-col>
                <el-col :span='20'>
                    <el-switch v-model="couponsData.display" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    缩略图
                </el-col>
                <el-col :span='20'>
                    <el-upload style="width:100px;height:100px;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarSuccess">
                        <img v-if="couponsData.thumb" :src="couponsData.thumb" class="avatar" width="100" height='100'>
                        <i v-else class="el-icon-plus avatar-uploader-icon" style="margin:0 auto;display:block;"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    满减条件
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="couponsData.use_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    满减优惠
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="couponsData.price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    领取类型
                </el-col>
                <el-col :span='20'>
                    <template>
                        <el-radio v-model="couponsData.exchange" :label="0">无条件领取</el-radio>
                        <el-radio v-model="couponsData.exchange" :label="1">积分兑换</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    每人共可领取数量
                </el-col>
                <el-col :span='20'>
                    <el-input-number v-model="couponsData.limit" :min="1"></el-input-number>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    每人每日可领取数量
                </el-col>
                <el-col :span='20'>
                    <el-input-number v-model="couponsData.day_limit" :min="1"></el-input-number>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    优惠券总数
                </el-col>
                <el-col :span='20'>
                    <el-input-number v-model="couponsData.total" :min="1"></el-input-number>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    领取条件(积分)
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="couponsData.receive_num"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    优惠券说明
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="couponsData.desc"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span='4' style="line-height:40px;">
                    时间类型
                </el-col>
                <el-col :span='20'>
                    <template>
                        <el-radio v-model="couponsData.time_type" :label="0">固定时间段</el-radio>
                        <el-radio v-model="couponsData.time_type" :label="1">领取当天生效</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row v-if="couponsData.time_type !== ''" style="margin-bottom: 20px;">
                <el-col v-if='couponsData.time_type === 0'>
                    时间段
                </el-col>
                <el-col v-if='couponsData.time_type === 0'>
                    <el-date-picker v-model="allTime" format="yyyy - MM - dd" type="daterange" @change="changeAllTime()" value-format="yyyy-MM-dd" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
                    </el-date-picker>
                </el-col>
                <el-col v-if='couponsData.time_type === 1' :span="12">
                    <p style="text-align:center;">多久后生效</p>
                    <el-input-number style="margin:0 auto;display:block;" v-model="couponsData.startat" :min="1" label="多久后生效"></el-input-number>

                </el-col>
                <el-col v-if='couponsData.time_type === 1' :span="12">
                    <p style="text-align:center;">生效时间</p>
                    <el-input-number style="margin:0 auto;display:block;" v-model="couponsData.time_limit" :min="1" label="生效时间"></el-input-number>
                </el-col>
            </el-row>
            <el-button style="margin:20px auto 0;display:block;" type="primary" @click="isNewCoupons()">提交</el-button>
        </el-dialog>
    </el-main>

</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    data() {
        return {
            isNew: false,
            couponsDialogTitle: "",
            couponsDialog: false,
            couponsData: {
                type: "",
                name: "",
                use_price: "",
                price: "",
                thumb: "",
                desc: "",
                exchange: "", //兑换类型，0 普通兑换  1 积分兑换
                receive_num: "", //领取条件（积分数）
                display: 1, //是否在兑换中心显示 0  1
                time_type: "", //0 固定时间段  1 用户领取后计算
                //固定时间段
                start_time: "",
                end_time: "",
                //用户领取后计算

                startat: "", //领取后多久开始算
                time_limit: "", //开始计算后有效期
                limit: "", //每人可领取数量
                day_limit: "", //每人每日可领取数量
                send_amount: "", //所有用户已领取数量
                total: "" //优惠券总数
            },
            removePhoto: "",
            couponsList: [],
            allTime: [],
            isBlank:false
        };
    },
    computed: {
        headers() {
            return {
                token: localStorage.token
            };
        }
    },
    methods: {
        //优惠券
        //----获取优惠券
        getCoupongs() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/coupons/coupons"
                )
                .then(res => {
                    this.couponsList = res.data.data.data;
                });
        },
        //isNewCoupons
        isNewCoupons() {
            if (this.isNew) {
                this.addCoupons();
            } else {
                this.editCoupons();
            }
        },

        //优惠券判断
        hasBlank(){
            for(let x in this.couponsData){
                if(this.couponsData[x] === '' && (
                    x == 'name' ||
                    x == 'type' ||
                    x == 'totle' ||
                    x == 'thumb' ||
                    x == 'time_type' ||
                    x == 'exchange' ||
                    x == 'use_price' ||
                    x == 'receive_num' ||
                    x == 'price'
                )
                ){
                    this.showMessage('error','有资料未填写')
                    this.isBlank = true
                    return false
                }
            }
        },
        //新增优惠券
        addCoupons() {
            this.hasBlank()
            if(this.isBlank){
                this.isBlank = false
                return false
            }
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/coupons/coupons",
                    {
                        type: this.couponsData.type,
                        name: this.couponsData.name,
                        use_price: this.couponsData.use_price,
                        price: this.couponsData.price,
                        thumb: this.couponsData.thumb,
                        desc: this.couponsData.desc,
                        exchange: this.couponsData.exchange, //兑换类型，0 普通兑换  1 积分兑换
                        receive_num: this.couponsData.receive_num, //领取条件（积分数）
                        display: this.couponsData.display, //是否在兑换中心显示 0  1
                        time_type: this.couponsData.time_type, //0 固定时间段  1 用户领取后计算
                        //固定时间段
                        start_time: this.couponsData.start_time,
                        end_time: this.couponsData.end_time,
                        //用户领取后计算
                        startat: this.couponsData.startat, //领取后多久开始算
                        time_limit: this.couponsData.time_limit, //开始计算后有效期
                        limit: this.couponsData.limit, //每人可领取数量
                        day_limit: this.couponsData.day_limit, //每人每日可领取数量
                        //send_amount: this.couponsData.send_amount, //所有用户已领取数量
                        total: this.couponsData.total //优惠券总数
                    }
                )
                .then(res => {
                    this.getCoupongs();
                    this.showMessage('success','添加成功')
                    this.couponsDialog = false;
                });
        },
        //修改Coupons
        editCoupons() {
            this.hasBlank()
            if(this.isBlank){
                this.isBlank = false
                return false
            }
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/coupons/coupons/" +
                        this.couponsData.id,
                    {
                        type: this.couponsData.type,
                        name: this.couponsData.name,
                        use_price: this.couponsData.use_price,
                        price: this.couponsData.price,
                        thumb: this.couponsData.thumb,
                        desc: this.couponsData.desc,
                        exchange: this.couponsData.exchange, //兑换类型，0 普通兑换  1 积分兑换
                        receive_num: this.couponsData.receive_num, //领取条件（积分数）
                        display: this.couponsData.display, //是否在兑换中心显示 0  1
                        time_type: this.couponsData.time_type, //0 固定时间段  1 用户领取后计算
                        //固定时间段
                        start_time: this.couponsData.start_time,
                        end_time: this.couponsData.end_time,
                        //用户领取后计算
                        startat: this.couponsData.startat, //领取后多久开始算
                        time_limit: this.couponsData.time_limit, //开始计算后有效期
                        limit: this.couponsData.limit, //每人可领取数量
                        day_limit: this.couponsData.day_limit, //每人每日可领取数量
                        //send_amount: this.couponsData.send_amount, //所有用户已领取数量
                        total: this.couponsData.total //优惠券总数
                    }
                )
                .then(res => {
                    this.getCoupongs();
                    this.showMessage('success','修改成功')
                    this.couponsDialog = false;
                });
        },
        //删除Coupons
        removeCoupons(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/coupons/coupons/" +
                        this.couponsList[index].id
                )
                .then(res => {
                    this.getCoupongs();
                });
        },
        //打开coupons dialog  0 new 1 edit
        openCouponsDialog(i, index = 0) {
            if (i === 0) {
                this.couponsDialogTitle = "新增优惠券";
                this.isNew = true;
                this.couponsDialog = true;
                for (var i in this.couponsData) {
                    this.couponsData[i] = "";
                    this.couponsData.display = 1;
                }
            } else {
                this.couponsDialogTitle = "编辑优惠券";
                this.isNew = false;
                this.couponsDialog = true;
                this.indexToCouponsData(index);
            }
        },

        //获取对应index 的优惠券信息
        indexToCouponsData(i) {
            // this.couponsData = this.couponsList[i];
            var str = JSON.stringify(this.couponsList[i]);
            this.couponsData = JSON.parse(str);
            if (
                this.couponsData.start_time == "" ||
                this.couponsData.start_time == null
            ) {
                this.allTime = [];
            } else {
                this.allTime = [
                    this.couponsData.start_time,
                    this.couponsData.end_time
                ];
            }
            this.removePhoto = this.couponsData.thumb;
            console.log(this.allTime);
        },
        //上传缩略图
        handleAvatarSuccess(response, file, fileList) {
            axios
                .post("/qiniuDelete", { url: this.removePhoto })
                .then(res => {
                    this.couponsData.thumb = response.url;
                });
        },
        //changeAllTime 更改allTime时间
        changeAllTime() {
            this.couponsData.start_time = this.allTime[0];
            this.couponsData.end_time = this.allTime[1];
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },
    },
    mounted() {
        this.getCoupongs();
    }
};
</script>

<style scoped>
.couponsCard {
    margin-top: 20px;
}
.couponsCard:nth-child(3n + 1) {
    margin-left: 0px;
}



.time {
    font-size: 13px;
    color: #999;
    padding-left: 10px;
}

.bottom {
    margin-top: 13px;
    line-height: 12px;
}

.button {
    padding: 0;
    float: right;
}
.button + .button {
    margin-right: 3px;
}
.cardName {
    font-size: 20px;
    padding-left: 10px;
    font-weight: bold;
}
.imgPPage{
    float: left;
    width: 111px;
    height: 111px;
    overflow: hidden;
}
.imagePage {
    width: 111px;
    height: 111px;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
}
.image {
    width: 111px;
    display: block;
}

.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}

.clearfix:after {
    clear: both;
}


.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.avatar-uploader .el-upload:hover {
    border-color: #409eff;
}
.avatar-uploader-icon {
    border: 1px solid #ddd;
    font-size: 28px;
    color: #8c939d;
    width: 100px;
    height: 100px;
    line-height: 100px;
    text-align: center;
}
.avatar {
    width: 100px;
    height: 100px;
    display: block;
}
</style>