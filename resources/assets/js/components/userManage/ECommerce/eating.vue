<template>
    <el-main>
        <el-row style="min-width:1200px;">
            <el-tabs type="border-card" @tab-click="handletabClick">
                <!-- 商品列表 -->
                <el-tab-pane label="商品列表">
                    <el-row style="margin-bottom: 20px;">
                        <el-col :span="1" style="line-height:40px;">
                            筛选：
                        </el-col>
                        <el-col :span="5">
                            <el-select v-model="search.type" placeholder="请选择">
                                <el-option v-for="item in searchTypeList" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col>
                            <el-button type="primary" size="small" @click="openNewProducts()" style="margin-bottom:20px;">新增</el-button>
                        </el-col>
                        <el-col>
                            <el-table :data="searchfilter?searchfilter:''" border>
                                <el-table-column prop="name" label="商品名称"></el-table-column>
                                <el-table-column label="图片" width="150" align="center">
                                    <template slot-scope="scope">
                                        <img :src="searchfilter[scope.$index].thumb" width="80px" height='80px'>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="c_price" label="现价" width="100" align="center"></el-table-column>
                                <el-table-column label="原价" width="100" align="center">
                                    <template slot-scope="scope">
                                        <p style="text-decoration:line-through">{{searchfilter[scope.$index].o_price}}</p>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="intro" label="简介"></el-table-column>
                                <el-table-column label="上架" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="searchfilter[scope.$index].display" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column label="热卖" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="searchfilter[scope.$index].hot" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column label="推荐" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="searchfilter[scope.$index].recommend" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column label="卖完" width="100" align="center">
                                    <template slot-scope="scope">
                                        <el-switch @change="changeSwitch(scope.$index)" v-model="searchfilter[scope.$index].oversell" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                                        </el-switch>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="name" label="操作" width="150" align="center">
                                    <template slot-scope="scope">
                                        <el-button size="small" @click="openEditProducts(scope.$index)" type="primary">编辑</el-button>
                                        <el-button size="small" @click="removeProduct(scope.$index)" type="danger">删除</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-col>
                    </el-row>
                </el-tab-pane>
                <!-- 商品分类 -->
                <el-tab-pane label="商品分类">
                    <el-button type="primary" size="small" @click="openNewType()" style="margin-bottom:20px;">新增</el-button>
                    <el-table :data="productsTypeList" border>
                        <el-table-column prop="id" label="分类ID"></el-table-column>
                        <el-table-column prop="name" label="分类名称"></el-table-column>
                        <el-table-column prop="products_count" label="分类商品数"></el-table-column>
                        <el-table-column label="操作" width="150">
                            <template slot-scope="scope">
                                <el-button size="small" type="primary" @click="openEditType(scope.$index)">编辑</el-button>
                                <el-button size="small" type="danger" @click="removeType(scope.$index)">删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
                <!-- 基础设置 -->
                <el-tab-pane label="基础设置">
                    <el-row v-if="false">
                        <audio controls="controls">
                            <source :src="aUrl">
                        </audio>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            店铺营业开关
                        </el-col>
                        <el-col :span="10">
                            <el-switch v-model="baseSetData.status" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                            </el-switch>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            店名
                        </el-col>
                        <el-col :span="10">
                            <el-input v-model="baseSetData.name"></el-input>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            封面
                        </el-col>
                        <el-col :span="10">
                            <el-upload style="width:100px;height:100px;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarBaseSuccess">
                                <img v-if="baseSetData.thumb" :src="baseSetData.thumb" class="avatar" width="100" height='100'>
                                <i v-else class="el-icon-plus avatar-uploader-icon" style="margin:0 auto;display:block;"></i>
                            </el-upload>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            公告
                        </el-col>
                        <el-col :span="10">
                            <el-input v-model="baseSetData.notice"></el-input>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            用户是否需要填写（电话，地址）
                        </el-col>
                        <el-col :span="10">
                            <el-switch v-model="baseSetData.verify" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                            </el-switch>
                        </el-col>
                    </el-row>
                    <el-row style="margin:20px 0 0;">
                        <el-col :span="3">
                            是否开启满减优惠
                        </el-col>
                        <el-col :span="10">
                            <el-switch v-model="baseSetData.offer_status" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                            </el-switch>
                        </el-col>
                    </el-row>
                    <el-row style="margin:30px 0 0;" v-if="baseSetData.offer_status">
                        <el-col :span="3">
                            满减内容
                        </el-col>
                        <el-col :span="10">
                            <el-button type="primary" size="small" style="margin:0 0 20px;" @click="addRulesSale()">添加规则</el-button>
                            <el-table :data='baseSetData.offer' border>
                                <el-table-column label="满减条件">
                                    <template slot-scope="scope">
                                        <el-input v-model="baseSetData.offer[scope.$index].condition"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column label="满减优惠">
                                    <template slot-scope="scope">
                                        <el-input v-model="baseSetData.offer[scope.$index].reduce"></el-input>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-col>
                        <el-col :offset="3" :span="10">
                            <tip slass="primary" value="满减优惠请按从低到高填写"></tip>
                        </el-col>
                    </el-row>
                    <el-button style="margin:20px 0 0;" type="primary" size="small" @click="editBaseSet()">保存</el-button>
                </el-tab-pane>
                <el-tab-pane label="外卖订单" v-if='baseSetData.type === 1 || baseSetData.type === 2'>
                    <span style="color:#666;margin-left:20px;margin-right:20px;">日期:</span>
                    <el-date-picker v-model="orderSearchTime" type="daterange" value-format='yyyy-MM-dd' range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
                    </el-date-picker>
                    <el-button type="primary" @click="searchDateOrders()">搜索</el-button>
                    <el-tabs @tab-click="handletabClick1" tab-position="left" v-model="articleLeftTab" style="margin:20px 0 0;">
                        <el-tab-pane label="全部订单" name="all">
                            <el-table :data='ordersSign[0] && orderList' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="已完成" name="Out">
                            <el-table :data='ordersSign[1] && filterOrderForOut' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="已支付" name="Paid">
                            <el-table :data='ordersSign[2] && filterOrderForPaid' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                                <el-table-column label="操作" width="200">
                                    <template slot-scope="scope">
                                        <el-popover placement="top" width="160" v-model="filterOrderForPaid[scope.$index].visible1">
                                            <p>是否确认此订单?</p>
                                            <div style="text-align: right; margin: 0">
                                                <el-button size="mini" type="text" @click="filterOrderForPaid[scope.$index].visible1 = false">取消</el-button>
                                                <el-button type="primary" size="mini" @click="confirmOrder(filterOrderForPaid[scope.$index].id),filterOrderForPaid[scope.$index].visible1 = false">确定</el-button>
                                            </div>
                                            <el-button type="primary" slot="reference" size="small">确认订单</el-button>
                                        </el-popover>
                                        <el-popover placement="top" width="160" v-model="filterOrderForPaid[scope.$index].visible2">
                                            <p>是否确认取消订单?</p>
                                            <div style="text-align: right; margin: 0">
                                                <el-button size="mini" type="text" @click="filterOrderForPaid[scope.$index].visible2 = false">取消</el-button>
                                                <el-button type="primary" size="mini" @click="cancelOrder(filterOrderForPaid[scope.$index].id),filterOrderForPaid[scope.$index].visible2 = false">确定</el-button>
                                            </div>
                                            <el-button type="danger" slot="reference" size="small">取消订单</el-button>
                                        </el-popover>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="已接单" name="accept">
                            <el-table :data='ordersSign[3] && filterOrderForAccept' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="未支付" name="UnPaid">
                            <el-table :data='ordersSign[4] && filterOrderForUnPaid' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="退款审核" name="Refund">
                            <el-table :data='ordersSign[5] && filterOrderForOnRefund' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                                <el-table-column label="操作" width="200">
                                    <template slot-scope="scope">
                                        <el-popover placement="top" width="160" v-model="filterOrderForOnRefund[scope.$index].visible1">
                                            <p>是否确认对此订单进行退款?</p>
                                            <div style="text-align: right; margin: 0">
                                                <el-button size="mini" type="text" @click="filterOrderForOnRefund[scope.$index].visible1 = false">取消</el-button>
                                                <el-button type="primary" size="mini" @click="confirmRefundOrder(filterOrderForOnRefund[scope.$index].id),filterOrderForOnRefund[scope.$index].visible1 = false">确定</el-button>
                                            </div>
                                            <el-button type="primary" slot="reference" size="small">确认退款</el-button>
                                        </el-popover>
                                        <el-popover placement="top" width="160" v-model="filterOrderForOnRefund[scope.$index].visible2">
                                            <p>是否拒绝订单退款?</p>
                                            <div style="text-align: right; margin: 0">
                                                <el-button size="mini" type="text" @click="filterOrderForOnRefund[scope.$index].visible2 = false">取消</el-button>
                                                <el-button type="primary" size="mini" @click="confirmRefundOrder1(filterOrderForOnRefund[scope.$index].id),filterOrderForOnRefund[scope.$index].visible2 = false">确定</el-button>
                                            </div>
                                            <el-button type="danger" slot="reference" size="small">取消退款</el-button>
                                        </el-popover>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="退款成功" name="AfterRefund">
                            <el-table :data='ordersSign[6] && filterOrderForAfterRefund' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                            </el-table>
                        </el-tab-pane>
                    </el-tabs>
                </el-tab-pane>
                <el-tab-pane label="店内订单" v-if='baseSetData.type === 0 || baseSetData.type === 2'>
                    <el-table :data='filterOrderForOutOrder' border>
                        <el-table-column prop="sign" label="桌号"></el-table-column>
                        <el-table-column prop="order_no" label="订单号"></el-table-column>
                        <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                        <el-table-column prop="price" label="订单金额"></el-table-column>
                        <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                        <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                        <el-table-column prop="created_at" label="下单时间"></el-table-column>
                        <el-table-column label="操作" width="200">
                                    <template slot-scope="scope">
                                        <el-popover placement="top" width="160" v-model="filterOrderForOutOrder[scope.$index].visible1">
                                            <p>是否确认此订单?</p>
                                            <div style="text-align: right; margin: 0">
                                                <el-button size="mini" type="text" @click="filterOrderForOutOrder[scope.$index].visible1 = false">取消</el-button>
                                                <el-button type="primary" size="mini" @click="confirmOrder(filterOrderForOutOrder[scope.$index].id),filterOrderForOutOrder[scope.$index].visible1 = false">确定</el-button>
                                            </div>
                                            <el-button type="primary" slot="reference" size="small">确认订单</el-button>
                                        </el-popover>
                                        <el-popover placement="top" width="160" v-model="filterOrderForOutOrder[scope.$index].visible2">
                                            <p>是否确认取消订单?</p>
                                            <div style="text-align: right; margin: 0">
                                                <el-button size="mini" type="text" @click="filterOrderForOutOrder[scope.$index].visible2 = false">取消</el-button>
                                                <el-button type="primary" size="mini" @click="cancelOrder(filterOrderForOutOrder[scope.$index].id),filterOrderForOutOrder[scope.$index].visible2 = false">确定</el-button>
                                            </div>
                                            <el-button type="danger" slot="reference" size="small">取消订单</el-button>
                                        </el-popover>
                                    </template>
                                </el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </el-row>

        <!-- dialog -->
        <el-dialog :visible.sync="productsDialog" :title='productsDialogTitle' width='900px'>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品名称</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品分类</el-col>
                <el-col :span="20">
                    <el-select v-model="productsData.cate_id" placeholder="请选择">
                        <el-option v-for="item in productsTypeList" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品图片</el-col>
                <el-col :span="20">
                    <el-upload style="width:100px;height:100px;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarSuccess">
                        <img v-if="productsData.thumb" :src="productsData.thumb" class="avatar" width="100" height='100'>
                        <i v-else class="el-icon-plus avatar-uploader-icon" style="margin:0 auto;display:block;"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品简介</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.intro"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">原价</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.o_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">现价</el-col>
                <el-col :span="20">
                    <el-input v-model="productsData.c_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">上架</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.display" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">热卖</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.hot" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">推荐</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.recommend" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">卖完</el-col>
                <el-col :span="20">
                    <el-switch v-model="productsData.oversell" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0"></el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3">商品详情</el-col>
                <el-col :span="20">
                    <quill-editor style="height:400px;" ref="myTextEditor" :content="productsData.content" @change="onEditorChange($event)">
                    </quill-editor>
                </el-col>
            </el-row>
            <el-button type="primary" size="small" style="margin:100px auto 0;display:block;" @click="inputProductData()">提交</el-button>
        </el-dialog>

        <!-- type dialog -->
        <el-dialog :visible.sync="typeDialog" :title='typeDialogTitle' width="500px">
            <el-row>
                <el-col>分类名称</el-col>
                <el-col>
                    <el-input v-model="typeName"></el-input>
                </el-col>
            </el-row>
            <el-button @click="inputProductType()" type="primary" size="small" style="margin:20px auto 0;display:block;">提交</el-button>
        </el-dialog>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import tip from "@/components/tip.vue";
import { quillEditor } from "vue-quill-editor";
export default {
    data() {
        return {
            aUrl:
                "data:audio/wav;base64,//MoxAAMUFXwAU8QAGePjKfIOJuWMugC2FerhuC4HA3oe7V7PsEDmXB8Hwc+CDsuD5//wff///////8uf4YV1doX2ry2Xv2Y//MoxAkPUcqcAZpoAIFALWcUsZslh6qjJFyCMb2TcK8e5J+h2W6H/oILP//////ppuh//+ggS7wiBP/3B+T///oV//3jKX9U//MoxAYOmhawAdoQADTgGULIPxq41ZkZLK3uzT8/8atJn+ZXb6f///////0dL0pTZtyMhE3nqd2tbQYWepajH7lKQOHHp0yG//MoxAYNKHa8yp5gSABsgGg5FnuPbMBBcIFgTm7dRkTpX6znV////2rRPGb0J2o1hUDCZYdQ+upppRxVX2ZaYI0AbhrWTW71//MoxAwO4IasAG5gSFu2aYTcDaQB2Sf0ll0oGno///2/7BtBYPDmVhtUeVDo0lUUFyQEDsQHaDyC4qShRf4xjimAT+DeBOtS//MoxAsNWa68AJtKlC6i6KYJGUm+nJRv/////////Ipzn+ryFA5yXUCD/BxOEDg4Olj1uxO+oor9BeEgDDBYWm4f/7mFMy26//MoxBAQWKLIAFYeTCoGCf0vAgi2CEHijq/53DfzxALL+UOUIE/aJ7aChMyFTrKqRRpqRf9CDR47/6P1L/01+s4MYAZggiO1//MoxAkNGPrMAJPQcSziU5xHxah6lXF22OleIoH6r1mFuAnAgATBwIVqXeFbuESi4e7hJFxg0BJqECj6Im8BrDGJeTVBH2Dn//MoxA8NGPbMyIPGcR6B/S5X0PFfEyBsiYo2F47OwM1gJiNKpKSVY3G6gkMGBBAbTk9VGkkcu1m2wAHpg4A8lqNisL2Kxma0//MoxBUNQHcaXmtSStQSip8YfNRNEoVGpYCqVP2hqeU3KPNSjShec//U6TPr1qMQnA9XGsc6AUTGxWu1rWGADjB/PSJFP/////MoxBsKaG6wAJZSKP/+V1B4cA3DMOz3sk1RIvcInAdeLp46eCAqFMJs60DII7df7ff///1//////+noQqc/1+/1bxpUQnat//MoxCwM4iqkAKNEmFlPl8tcX0a6EckgAAAEkgHoBJgL5+cOjpOQBHydywxebF///////uLg8XIf1vnDgog+Ik///wNV8hlV//MoxDMLyFL6XmwyJk2ljOvMA1gVamvL6fsRdWBwxCpmKu////+hIsePAJxBKvvFankkKhEg3bbRbaKB/8aTiVZXpLRtVnNR//MoxD4KSFbEAA4wJFV+4YU06DWV7//+tzJZyBEJTpEYHVERKd4ND/////+VVQ2222SPlNXJAP5jCeFJzRbiMBIBMPWm6agq//MoxE8NKHryX0YYAgF4C//s6DDMQBuLg//lxnPF83DmDldgKIMT+S5gaFxnYKYPgah5kA0GE/63T34cwwU7mVlf///rstzp//MoxFUX6mKwy4poALvJf/8vAYgEAYCq/983q7QRlekjnJ6atS8RANhkm4U/W6XlBh0lzcbmmtmNTy+7VNSTmu2f0kkSSqUc//MoxDAQiUbAAdgwAKoapHDSJFFtBQCidQWez6vR9NUVUGuSyQWgAeqKIMBICqcSasZ/TqZikcTfFvQap/+X7+E919Xve1db//MoxCgSYf8KXlPEm9w77xnG9++KXYxkIggOQcggikW9v////X5G3ncjORxC0bVU5YfUdAbhLmZYhuCEPWStdqXPWDCDkzGD//MoxBkRgT7Mym4McFPe/GVQLl/MPCwdBLfBA07O73uVXyIvKqsuiJRJiBhl5SaegyS//0xrxQaCKPxnwrOUKwQcFmkTdlBq//MoxA4O8VbAAJUElABhE4eUZE2aUQ/EcLdQ5JdfzZT+R/QGX0Chn7ASBm1ECjnaYBFnJf8V10LNNlX6YoAA+oo+M/brF5h9//MoxA0LyHq8AJ4gSMmZa2gGBgNAwUt9FZdU1R5uGtK/s/ER5hYSuPKNoNId//Qq/+fgT4CESasm6Tm6hMkaEjIX//v///////MoxBgPUwq4AHhEuf//1c7FkYchU7/T/+U7odGzq5GJ1/t/5FO8ltvI0jXai0BjkVkq///6XWUhm/T+1f+yzqYjurpbv9mq//MoxBULWv68AAAKuX3R6zWKgiPLHnRCOrlOVSlUJiDjhohYu3Uw/AAPkz7/04Y///////////TX60pY51pVu9qy/WtXdJCX//MoxCIMqjLRlAhEmKCGEF/R5Y7SUAwrfsqWlDCC//z96jvDvBThhlzUaEZ5KABO///////////eis/RDNOb//T6lRZjo4MS//MoxCoNOi68AHiEmL/+IkJAxUBG7act1syzqDlIiQCNAr4yJkWSVvQFsNFb/t/6f///////b/UOjgO361YXP6yI5yS27536//MoxDAMqeKwAJqKmLwaSgZ359Zu60RqAOcAHNhbK03brD6CMn/+pH/1Cf/////On/hB7C0sVcW5I97neFXev9vqj//8w/+///MoxDgMgYasyqNElPqR36spUOC00NGdWNEyaOh4Yig5XY5/P/+MiNv9f///jpz/5gUJ/F/i3zn///yFPeHf/VJKH207Dvkg//MoxEENKYacVNqOlFPvqQ8i+eVj869vuNek3n2IThAdHDMRAqJBOB5T/5gGl7hkDssR/9X0Kntv222P1UlGTutVh2UNLh8Q//MoxEcNSJKUK1oYAg5DlFooNWclSTGii4IxDVwDMU3Mz6ReFoBvANJb2om7Mmo3Ljt+i9aSCaaE4h/N3QfcwKhqeBd3nFIy//MoxEwXeWrAy5iIAOOeU/wTP8uFyv/+UHOuLxOHkf//86eB/hfEuZrfEF+nB6C/jhOlWxasYEAaAOwoHIeR0mrXm59BVjnO//MoxCkSiTbQAc9YAOqm6dtu/ZMTU1VT102HLuDAOAkGlJ7Rui//3OuW3//7daowwD/szD//LSMADAEvSpYQrDIgAzkv/7Co//MoxBkNGJ7ZlUkoAHgCHt5kFlO1vI4hna/KjQVAQ+Gv///93///grWfFQj5bGIcM3hEIF5Q1g/6G1PP4wY6jyLhwTgKjORf//MoxB8UIyJwAZtQAAEBBk5P/5k04WNf+IQfoSK//+/U9P//ypOhpOft//+v1mFScux5//////V3nn9XONJ18/9k/Aw33Hdz//MoxAkPWc7Iy5g4ALOzoKWrIGgkd5qc4+ruisko4LCIHAxx+760La8//Ss////v//se7ff/yoy1k+ZFdbn9ZUQKSaOH/+/y//MoxAYMmHq8y9gwAJqLtiAWlAW1xrZ3Ls0mOr9ftLe/+6sHHfZ////2i4SBp61TRAXHqLEzICNC9f/9YzT8Ak0g+AVRefM5//MoxA4MIHqsAMveSDwC4HCAvC4k5RsfcrOyNKuZrk/3f////XUe+wGiP/////l1EETbltFoAoH9HgQDAA01JhIKCAcApZFt//MoxBgNIHruXlpMSpYDBCVf9AnLiYq9RcMht6Rgd//1a/////////j1CTdlu2AGAwHxYACyQEqyMYuT7atito6BVd3J21s///MoxB4NQILuXmvYSlhkbiI85/OsEv///+j/////tqFqhZIDBMckkkAgHohxQA6Jk/chouUtajpFj7TFi8kvhhSqcQOB8Pgg//MoxCQNAIK5H1EQAuESwfEHEC/9X8v//////+j/rw2+Mi18vctDgby/7W4HpFkctEXNwF4hv5I+OAuGigLEJAfY3/oMX03P//MoxCsWUXqwAZiYAJbMpe/9TIsxul/m9i+npESJgdhbQKPz5zKNDZ4V/P1cEgdUSLL/8o6PptAoQeWV///xCBDGPuta68Iy//MoxAwM+WbUAc9oANDnz59GtGyHgJCamrVomISMcjfOGq/1v/V/p/dSZKIL+cX0u4bfqDD+iHeB7f8SEg/5kBXB6j6nUdBv//MoxBMLgXLMAIxKlFEJf1E0YfqIn/3/Wy0uVFB9X3MKel3DfExzmf5mEgEcv6ZDQ2s/9QnoFVNOuoUKFvSH6iZf+j/1/qJH//MoxCAMgXLIAISKlD16AKZmtxI79Y3iLzvUb///6gYz5/hMQCqCibIvpmADwMtP0C+AG41fs7BJDV/1M/0f//3O/U0ep4b6//MoxCkNGVLAyptUcJ/I+32dP+v/FA5ytRIZY5aBaAKB2oGZoASwJKR9e8yMyHFBVv7kgQSwur5oUgr+hv///6hRNz493Jf///MoxC8NMTbqXmrEcv/////QRUSG5BJIIB9SR0Z0AHgGuc5rOaMQCom/xkLR395StXCgLFv/zpJZ36vX6uvWCp0qFYNf//8c//MoxDUNOMqtHpKEcq1ySuwudEQIHmwNm35GeXHVTBQmhA195H/fJ3hoGgClDbrq0wS/57//d////ngm5v///6pMQU1FMy45//MoxDsM8HIoANaSSDkuNaqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq//MoxEIAAANIAAAAAKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq//MoxH0AAANIAAAAAKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq//MoxLgAAANIAAAAAKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq",
            productsTypeList: [],
            searchTypeList: [],
            productsList: [],
            search: {
                type: ""
            },
            productsDialog: false,
            productsDialogTitle: "",
            isNewProdutc: false,
            thisProductIndex: "",
            productsData: {
                name: "",
                thumb: "",
                intro: "",
                o_price: "",
                c_price: "",
                oversell: "",
                content: "",
                hot: "",
                display: "",
                recommend: "",
                cate_id: ""
            },

            thisTypeIndex: "",
            typeDialog: false,
            typeDialogTitle: "",
            typeName: "",
            isNewType: false,

            isNewBaseData: false,
            baseSetData: {
                name: "",
                thumb: "",
                notice: "",
                offer_status: "",
                offer: []
            },

            articleLeftTab: "Paid",
            orderList: [],
            orderSearchTime: [],

            //订单竖向标签页
            ordersSign:[false,false,true,false,false,false,false]
        };
    },
    components: { tip },
    computed: {
        searchfilter: function() {
            var search = this.search.type;
            if (search) {
                return this.productsList.filter(function(productsList) {
                    return ["cate_id"].some(function(key) {
                        return (
                            String(productsList[key])
                                .toLowerCase()
                                .indexOf(search) > -1
                        );
                    });
                });
            }
            return this.productsList;
        },
        //已完成
        filterOrderForOut() {
            let filterCondition = "3";
            if (filterCondition && this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["status"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf(filterCondition) > -1
                        );
                    });
                });
            }
        },
        //已支付
        filterOrderForPaid() {
            let filterCondition = "1";
            if (filterCondition && this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["status"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf(filterCondition) > -1 &&
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf("-") == -1
                        );
                    });
                });
            }
        },
        //已接单
        filterOrderForAccept() {
            let filterCondition = "2";
            if (filterCondition && this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["status"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf(filterCondition) > -1 &&
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf("-") == -1
                        );
                    });
                });
            }
        },
        //未支付
        filterOrderForUnPaid() {
            let filterCondition = "0";
            if (filterCondition && this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["status"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf(filterCondition) > -1
                        );
                    });
                });
            }
        },
        //退款审核
        filterOrderForOnRefund() {
            let filterCondition = "-2";
            if (filterCondition && this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["status"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf(filterCondition) > -1
                        );
                    });
                });
            }
        },
        //退款成功
        filterOrderForAfterRefund() {
            let filterCondition = "-3";
            if (filterCondition && this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["status"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase()
                                .indexOf(filterCondition) > -1
                        );
                    });
                });
            }
        },
        //线下订单
        filterOrderForOutOrder() {
            if (this.orderList.length !=0) {
                return this.orderList.filter(function(orderList) {
                    return ["sign"].some(function(key) {
                        return (
                            String(orderList[key])
                                .toLowerCase() !== 'null' && String(orderList[key])
                                .toLowerCase() !== 'undefined'
                        );
                    });
                });
            }
        },
        headers() {
            return {
                token: localStorage.token
            };
        }
    },
    methods: {
        //切换前执行
        handletabClick(tab, event) {
            if (tab.index == 0) {
                this.getProductsList();
            } else if (tab.index == 1) {
                this.getProductsType();
            } else if (tab.index == 2) {
                this.getbaseSet();
            } else if (tab.index == 3) {
                this.getOrdersList();
            }
        },
        //切换前执行
        handletabClick1(tab, event) {
            this.ordersSign = [false,false,false,false,false,false,false]
            if (tab.index == 0) {
                this.ordersSign[0] = true
            } else if (tab.index == 1) {
                this.ordersSign[1] = true
            } else if (tab.index == 2) {
                this.ordersSign[2] = true
            } else if (tab.index == 3) {
                this.ordersSign[3] = true
            } else if (tab.index == 4) {
                this.ordersSign[4] = true
            } else if (tab.index == 5) {
                this.ordersSign[5] = true
            } else if (tab.index == 6) {
                this.ordersSign[6] = true
            }
        },
        onEditorChange({ editor, html, text }) {
            this.productsData.content = html;
        },
        //获取商品列表
        getProductsList() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/products"
                )
                .then(res => {
                    this.productsList = res.data.data;
                });
        },
        //获取商品分类列表
        getProductsType() {
            axios
                .get(
                    "/web/" + store.state.xcx_flag.xcx_flag + "/api/foods/cates"
                )
                .then(res => {
                    this.productsTypeList = [];
                    this.searchTypeList = [];
                    this.searchTypeList.push({ name: "所有商品", id: "" });
                    for (let i in res.data.data) {
                        this.productsTypeList.push(res.data.data[i]);
                        this.searchTypeList.push(res.data.data[i]);
                    }
                });
        },
        //打开新增商品
        openNewProducts(index) {
            this.productsDialog = true;
            this.productsDialogTitle = "新增商品";
            this.isNewProdutc = true;

            this.productsData = {
                name: "",
                thumb: "",
                intro: "",
                o_price: "",
                c_price: "",
                oversell: "",
                content: "",
                hot: "",
                display: "",
                recommend: "",
                cate_id: ""
            };
        },
        //打开商品编辑
        openEditProducts(index) {
            this.productsDialog = true;
            this.productsDialogTitle = "编辑商品";
            this.isNewProdutc = false;
            this.thisProductIndex = index;

            this.productsData = this.productsList[index];
        },
        //保存dialogdata
        inputProductData() {
            if (this.isNewProdutc) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/products",
                        {
                            name: this.productsData.name,
                            thumb: this.productsData.thumb,
                            cate_id: this.productsData.cate_id,
                            intro: this.productsData.intro,
                            o_price: this.productsData.o_price,
                            c_price: this.productsData.c_price,
                            oversell: this.productsData.oversell,
                            content: this.productsData.content,
                            hot: this.productsData.hot,
                            display: this.productsData.display,
                            recommend: this.productsData.recommend
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                        this.productsDialog = false;
                        this.getProductsList();
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/products/" +
                            this.productsList[this.thisProductIndex].id,
                        {
                            name: this.productsData.name,
                            thumb: this.productsData.thumb,
                            cate_id: this.productsData.cate_id,
                            intro: this.productsData.intro,
                            o_price: this.productsData.o_price,
                            c_price: this.productsData.c_price,
                            oversell: this.productsData.oversell,
                            content: this.productsData.content,
                            hot: this.productsData.hot,
                            display: this.productsData.display,
                            recommend: this.productsData.recommend
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "修改成功");
                        this.productsDialog = false;
                        this.getProductsList();
                    });
            }
        },
        //删除商品
        removeProduct(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/products/" +
                        this.productsList[index].id
                )
                .then(res => {
                    this.showMessage("success", "删除成功");
                    this.getProductsList();
                });
        },
        //商品switch改变
        changeSwitch(index) {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/products/" +
                        this.productsList[index].id,
                    {
                        name: this.productsList[index].name,
                        thumb: this.productsList[index].thumb,
                        cate_id: this.productsList[index].cate_id,
                        intro: this.productsList[index].intro,
                        o_price: this.productsList[index].o_price,
                        c_price: this.productsList[index].c_price,
                        oversell: this.productsList[index].oversell,
                        content: this.productsList[index].content,
                        hot: this.productsList[index].hot,
                        display: this.productsList[index].display,
                        recommend: this.productsList[index].recommend
                    }
                )
                .then(res => {
                    this.showMessage("success", "修改成功");
                    this.getProductsList();
                });
        },
        //上传图片
        handleAvatarSuccess(response, file, fileList) {
            axios
                .post("/qiniuDelete", { url: this.productsData.thumb })
                .then(res => {
                    this.productsData.thumb = response.url;
                });
        },
        handleAvatarBaseSuccess(response, file, fileList) {
            axios
                .post("/qiniuDelete", { url: this.productsData.thumb })
                .then(res => {
                    this.baseSetData.thumb = response.url;
                });
        },
        //新增商品分类
        openNewType() {
            this.typeName = "";
            this.typeDialog = true;
            this.isNewType = true;
            this.typeDialogTitle = "新增分类";
        },
        //编辑商品分类
        openEditType(index) {
            this.typeName = this.productsTypeList[index].name;
            this.typeDialog = true;
            this.isNewType = false;
            this.typeDialogTitle = "编辑分类";
            this.thisTypeIndex = index;
        },
        //保存商品分类
        inputProductType() {
            if (this.isNewType) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/cates",
                        {
                            name: this.typeName
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                        this.typeDialog = false;
                        this.getProductsType();
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/cates/" +
                            this.productsTypeList[this.thisTypeIndex].id,
                        {
                            name: this.typeName
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                        this.typeDialog = false;
                        this.getProductsType();
                    });
            }
        },
        //删除商品分类
        removeType(index) {
            if (this.productsTypeList[index].products_count !== 0) {
                this.showMessage(
                    "error",
                    "商品分类存在商品无法删除，请先删除分类底下的商品再删除分类"
                );
            } else {
                axios
                    .delete(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/cates/" +
                            this.productsTypeList[index].id
                    )
                    .then(res => {
                        this.showMessage("success", "删除成功");
                        this.getProductsType();
                    });
            }
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },

        //基础设置

        //获取基础设置
        getbaseSet() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/settings"
                )
                .then(res => {
                    this.baseSetData = res.data.data;

                    if (
                        this.baseSetData.offer === null ||
                        this.baseSetData.offer.length === 0
                    ) {
                        this.baseSetData = {
                            name: "",
                            thumb: "",
                            notice: "",
                            offer_status: 1,
                            offer: []
                        };
                        this.isNewBaseData = true;
                    } else {
                        this.isNewBaseData = false;
                        this.baseSetData.offer = res.data.data.offer
                    }
                });
        },
        //保存基础设置
        editBaseSet() {
            if (this.baseSetData.name == "") {
                this.showMessage("error", "店铺名称未填写");
                return;
            } else if (this.baseSetData.thumb == "") {
                this.showMessage("error", "未上传店铺封面");
                return;
            } else if (
                this.baseSetData.offer_status &&
                this.baseSetData.offer == []
            ) {
                this.showMessage("error", "未填写满减规则");
                return;
            }
            if (this.isNewBaseData) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/settings",
                        {
                            status: this.baseSetData.status,
                            name: this.baseSetData.name,
                            thumb: this.baseSetData.thumb,
                            notice: this.baseSetData.notice,
                            offer_status: this.baseSetData.offer_status,
                            offer: this.baseSetData.offer
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "保存成功");
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/foods/settings/" +
                            this.baseSetData.id,
                        {
                            status: this.baseSetData.status,
                            name: this.baseSetData.name,
                            thumb: this.baseSetData.thumb,
                            notice: this.baseSetData.notice,
                            offer_status: this.baseSetData.offer_status,
                            offer: this.baseSetData.offer
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "保存成功");
                    });
            }
        },
        //新增满减规则
        addRulesSale() {
            if (
                this.baseSetData.offer.length !== 0 &&
                this.baseSetData.offer != null
            ) {
                if (
                    this.baseSetData.offer[this.baseSetData.offer.length - 1]
                        .condition == "" ||
                    this.baseSetData.offer[this.baseSetData.offer.length - 1]
                        .reduce == ""
                ) {
                    this.showMessage(
                        "error",
                        "上一条规则没有填写完整，无法新增规则"
                    );
                } else {
                    this.baseSetData.offer.push({ condition: "", reduce: "" });
                }
            } else {
                this.baseSetData.offer.push({ condition: "", reduce: "" });
            }
        },

        //订单管理

        //获取订单列表
        getOrdersList() {
            let end_time = Date.parse(new Date());
            let start_time = end_time - 8.64e7 * 7;
            this.orderSearchTime = [this.formatDate(start_time,'YY-MM-DD'),this.formatDate(end_time,'YY-MM-DD')]
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders?start_time="+this.formatDate(start_time,'YY-MM-DD')+"&"+"end_time="+this.formatDate(end_time,'YY-MM-DD')
                )
                .then(res => {
                    this.orderList = res.data.data;
                    let payNum1 = 0
                    let payNum2 = 0
                    for(let i = 0;i<this.orderList.length;i++){
                        if(this.orderList[i].status === 1){
                            payNum1++
                        }
                        if(String(this.orderList[i].sign).toLowerCase() !== 'null' && String(this.orderList[i].sign).toLowerCase() !== 'undefined'){
                            payNum2++
                        }
                    }
                    document.querySelector('.el-tabs__nav').classList.add('hasAfter')
                    document.querySelector('#tab-3').setAttribute('data-content',payNum1)
                    document.querySelector('#tab-4').setAttribute('data-content',payNum2)
                    document.querySelector('#tab-Paid').setAttribute('data-content',payNum1)
                });
            
        },
        //搜索日期
        searchDateOrders(){
             axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders?start_time="+this.orderSearchTime[0]+"&"+"end_time="+this.orderSearchTime[1]
                )
                .then(res => {
                    this.orderList = res.data.data;
                });
        },
        //确认接单
        confirmOrder(id) {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders/confirm",
                    {
                        id: id
                    }
                )
                .then(res => {
                    this.showMessage("success", "您已成功接单");
                    this.getOrdersList();
                });
        },
        //取消接单
        cancelOrder(id) {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders/cancel_order",
                    {
                        id: id
                    }
                )
                .then(res => {
                    this.showMessage("success", "您已取消接单");
                    this.getOrdersList();
                });
        },

        //确认订单退款
        confirmRefundOrder(id) {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders/confirm_refund",
                    {
                        id: id,
                        review:'agree'
                    }
                )
                .then(res => {
                    this.showMessage("success", "订单已退款！");
                    this.getOrdersList();
                });
        },
        //取消订单退款
        confirmRefundOrder1(id) {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders/confirm_refund",
                    {
                        id: id
                    }
                )
                .then(res => {
                    this.showMessage("success", "订单退款已拒绝！");
                    this.getOrdersList();
                });
        },

        formatDate(time, format = "YY-MM-DD hh:mm:ss") {
            var date = new Date(time);

            var year = date.getFullYear(),
                month = date.getMonth() + 1, //月份是从0开始的
                day = date.getDate(),
                hour = date.getHours(),
                min = date.getMinutes(),
                sec = date.getSeconds();
            var preArr = Array.apply(null, Array(10)).map(function(
                elem,
                index
            ) {
                return "0" + index;
            }); ////开个长度为10的数组 格式为 00 01 02 03

            var newTime = format
                .replace(/YY/g, year)
                .replace(/MM/g, preArr[month] || month)
                .replace(/DD/g, preArr[day] || day)
                .replace(/hh/g, preArr[hour] || hour)
                .replace(/mm/g, preArr[min] || min)
                .replace(/ss/g, preArr[sec] || sec);

            return newTime;
        }
    },
    mounted() {
        this.getProductsList();
        this.getProductsType();
        this.getbaseSet()
        this.getOrdersList()
    }
};
</script>

<style scoped>
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

<style>
.el-tabs__nav.hasAfter #tab-3::after {
    content: attr(data-content);
    position: absolute;
    background: red;
    width: 20px;
    height: 20px;
    line-height: 20px;
    margin-left:-3px;
    font-size: 12px;
    color: #fff;
    text-align: center;
    border-radius: 50%;
}
.el-tabs__nav.hasAfter #tab-4::after {
    content: attr(data-content);
    position: absolute;
    background: red;
    width: 20px;
    height: 20px;
    line-height: 20px;
    margin-left:-3px;
    font-size: 12px;
    color: #fff;
    text-align: center;
    border-radius: 50%;
}
.el-tabs__nav #tab-Paid::after{
    content: attr(data-content);
    position: absolute;
    background: red;
    width: 20px;
    height: 20px;
    margin-left:-3px;
    line-height: 20px;
    font-size: 12px;
    color: #fff;
    text-align: center;
    border-radius: 50%;
}
</style>