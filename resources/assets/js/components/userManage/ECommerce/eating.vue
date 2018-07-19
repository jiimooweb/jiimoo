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
                    <el-row v-show="false">
                        <audio id='messageAudio' controls="controls">
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
                    <el-row style="margin:30px 0 0;" v-if="baseSetData.offer_status === 1">
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
                    <el-tabs @tab-click="handletabClick1" class="childTab" tab-position="left" v-model="articleLeftTab" style="margin:20px 0 0;">
                        <el-tab-pane label="全部订单" name="all">
                            <el-table :data='ordersSign[0] && orderList' border>
                                <el-table-column prop="order_no" label="订单号"></el-table-column>
                                <el-table-column prop="fan.nickname" label="微信名"></el-table-column>
                                <el-table-column prop="mobile" label="电话"></el-table-column>
                                <el-table-column prop="price" label="订单金额"></el-table-column>
                                <el-table-column prop="mj_offer" label="满减金额"></el-table-column>
                                <el-table-column prop="coupon_offer" label="优惠券优惠金额"></el-table-column>
                                <el-table-column prop="created_at" label="下单时间"></el-table-column>
                                <el-table-column label="操作" width="160">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>
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
                                <el-table-column label="操作" width="160">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>
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
                                <el-table-column label="操作" width="350">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
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
                                <el-table-column label="操作" width="160">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>
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
                                <el-table-column label="操作" width="160">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>
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
                                <el-table-column label="操作" width="350">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
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
                                <el-table-column label="操作" width="160">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>
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
                        <el-table-column label="操作" width="350">
                                    <template slot-scope="scope">
                                        <el-dropdown width="160">
                                            <el-button class="el-dropdown-link" size="small" type='primary'>
                                                商品列表<i class="el-icon-arrow-down el-icon--right"></i>
                                            </el-button>
                                            <el-dropdown-menu slot="dropdown">
                                                <el-dropdown-item v-for="(item,index) in filterOrderForPaid[scope.$index].products" :key='index'>{{item.name}}</el-dropdown-item>
                                            </el-dropdown-menu>
                                        </el-dropdown>
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
import { setTimeout, clearTimeout } from 'timers';
export default {
    data() {
        return {
            aUrl:
                "data:audio/wav;base64,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vJMd0PMvJfvRIdiVCCxBQgda+P/uPkf/+pVXw4fUXg6wA+G0//MoxAoOcZrIyoQElRMqWYCOg3oBQGYFlmzdBdOpJamT////////+mliMCGDsDeol3vu7ptsdH22rE+38SvHx+xXHsH6W+mw//MoxAsM2e7MypNEmZ8BNgwJL9IyLhv91N///////p3fVCkRzMuzlrbZeu06woQ289s/6DNq/UOgMKCxfHgXCS39H//////t//MoxBIMKVbQACnElUV2Y5DnIioOwjt89cSzsP4YQ1ARN1u1Q91eyHIfuSU+vw///S/l61C0ClAlKdRJqOvqY7DnKLKfeLix//MoxBwMMbboFAAElJEKXDD63lA8YJwmvkP/3C6rR5oPoWI////////P/a/Ss3PPKOE6Fl2+xqVMJlSZ9HZz0MUqezuk01m5//MoxCYM6kbcCgBOuI6ax8j//4qKMcGHKmB6H/+4AAH23AHycBhc7YtAYsQP////8qtqaRgENjfaGioSLCKS9GduVa5P+NoC//MoxC0K4ENGXgmGJttttAFkoACTAwHorVbFrMyEBxK7H71a3kLHKien//9m0PmDARNKFk4bZEpBNxMMXvqq+tEQIB/CwU6I//MoxDwMkFceXgPwJvwmSAAsIETo11oJ/pN///////+pD2GIUMHBK6lYpDszu6qIOIni83ZM3RWo7LQBbLaB9J4WI+isNhwk//MoxEQMqb7IAGwEld84T2f//96cksisVOnetuUJCYIgQLGjzUggLZRn/ecs9nv0Kv4g0mmWaD/7KQAk5OkDwUhP7o0TwbDR//MoxEwNCHMSX0cQAgn2uYxGMAugsgh/tZGi8oPxQPyn+ZMQxBKESCGBTCYOCz62S2Y3joNgJgFBwZlCRP///zScwkJypESN//MoxFIXaxKsAYtQAZ/////557c9xbfARfdERt4UVEHrv+Uk/28svMzNKd0UUgoRIp0SGOtGNlIK1/OqPQWPPyICEp08HZkj//MoxC8PEULEAcEYAO0JA0FQ0x3/73KBoyRViOeFWoWLWLRQXGCQXXlOSOC8vE3lF27bEYngQlRJNDUzKufIliaSsQDNHvnW//MoxC0NQPqsAUhIAPS/ap7///HLvu1OCEG9BDFjLmmVBmRBmvnCxqfi7SkrTJyA8yumlsqAZO+Zbuqya3dfRKaL21ZlBWKJ//MoxDMW0OKQAZrAAHZfNjqslM/NLWk0upqbsrv27nK1O0BHteZuO/oaRv/yjP9G2XNA47rVMDAPAPw/XZS0lahGqy2GZ7FQ//MoxBIRmLatldl4AMJdS6LJYdmGVhdW3KsvwbK60ai1yzjoQ5mhRpWS1rfNoIdUAv/////qDK5PRUDzRg5tn/1qK6TStAAt//MoxAYOOL7pvmvMTgB6yoCnJcl4bAXYJMBjUaHubjhDoMcmnQoLveaRi2h5fZ2FIH1FClH////94sx+mfckNQzCo3LbRfus//MoxAgMON7+WGsGcnqZBZl9p05H0XDoR9ttaPdftrWfXiqsHXL4aso4QQLPvHBj9ZQ5//rWS//rUsyGqAsvGIzMTubEcV0I//MoxBILiHKwyKPSSGgobrcl5EUSuV6j1sGgniL+dBUJd/JnGMylB8lVzGQKrOwtVCx/KtPbqH4DAAQC9zvNXxAeUP6jmr////MoxB4L8G6wAA4eKPIdiz7oPNZ3CFVOZOX/R///qQvHh5RbwKUmETTatMyiDm6BoCBQe3w74gwj1BoO///9fWdO7hYYHhgs//MoxCkMmGbAyhYeKOD+0wkTiypzZQpAMP/mRQi5GVDYBuBgCxhIThBwNCo6Yc//////////////shlChwSjvXL42LUFW0AC//MoxDELGbbMqnnEl7ooHUQCU/gmLh8Lk3MhDmyZyfbgoc9wiEQF/////pPxiGCohAqi7h5ZKi1Yct///+itFt//8DXfgeal//MoxD8NQFbRHjpeJGNlujCB3Jm/I6W7opbb6/q32///////ZPqUhlGHnkzgGvFioFGr3Xf//1rNHSzb9uIBXALcTLatYEBJ//MoxEUNKb8GX0MQAsRyC5d15ysJhwWRRUSprHCjWpN/0aP/+v//9S61f1f3RMS4QyOXCeXm6JQNKFapimPU1kiSCKZgiXjP//MoxEsWulqQAZloANqCFD7azE1qEp3i0Uf+/9VuQ5muwva9uJSClAIQDkG6ZLUcxpMrcrmbea6/////////////1EQBAFw6//MoxCsNEcZAAc8oAACBRUt/////+hUEQGBUFgdBwFwUBcHRoZDxcgRoEc3f/iws3qFhYVFfrFRZv///pFm////6hVVMQU1F//MoxDELEE04AEpSJDMuOTkuNVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV//MoxD8AAANIAAAAAFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV//MoxHoAAANIAAAAAFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV//MoxLUAAANIAAAAAFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV",
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
                oversell: 0,
                content: "",
                hot: 0,
                display: 0,
                recommend: 0,
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
                // this.getOrdersList();
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
                oversell: 0,
                content: "",
                hot: 0,
                display: 0,
                recommend: 0,
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
            
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/foods/orders?start_time="+this.orderSearchTime[0]+"&"+"end_time="+this.orderSearchTime[1]
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
                    // if(payNum1!==0){
                    //     document.querySelector('.el-tabs__nav #tab-Paid').classList.add('hasAfter')
                    //     // document.querySelector('.el-tabs__nav #tab-Paid').setAttribute('class','el-tabs__item is-top is-active hasAfter')
                    //     document.querySelector('#tab-Paid').setAttribute('id','tab-Paid')
                    // }
                    //     document.querySelector('#tab-3').setAttribute('data-content',payNum1)
                    //     document.querySelector('#tab-Paid').setAttribute('data-content',payNum1)
                    // if(payNum2!==0){
                    //     // document.querySelector('.el-tabs__nav').classList.add('hasAfter1')
                    //     document.querySelector('.el-tabs__nav #tab-4').setAttribute('class','el-tabs__item is-top is-active hasAfter1')
                    // }
                    //     document.querySelector('#tab-4').setAttribute('data-content',payNum2)
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
                    // if(payNum1!==0){
                    //     // document.querySelector('.el-tabs__nav').classList.add('hasAfter')
                    //     document.querySelector('.el-tabs__nav').setAttribute('class','el-tabs__nav hasAfter')
                    //     document.querySelector('#tab-Paid').setAttribute('id','tab-Paid')
                    // }
                    //     document.querySelector('#tab-3').setAttribute('data-content',payNum1)
                    //     document.querySelector('#tab-Paid').setAttribute('data-content',payNum1)
                    // if(payNum2!==0){
                    //     // document.querySelector('.el-tabs__nav').classList.add('hasAfter1')
                    //     document.querySelector('.el-tabs__nav').setAttribute('class','el-tabs__nav hasAfter1')
                    // }
                    //     document.querySelector('#tab-4').setAttribute('data-content',payNum2)
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
        },

        //websocket
        initWebsocket(){
            const wsuri = "wss://www.rdoorweb.com:9501";
            this.websock = new WebSocket(wsuri);
            this.websock.onopen = this.websocketonOpen;
            this.websock.onmessage = this.websocketonmessage;
            this.websock.onclose = this.websocketclose;
        },  
        //打开接口
        websocketonOpen(){
            this.websock.send('xcx_id_'+localStorage.getItem('XCXID'));
            this.wensocketTimeout = setInterval(()=>{
                this.websock.send('xcx_id_'+localStorage.getItem('XCXID'))
            },20000)
            this.againGetOrder = setInterval(()=>{
                this.getOrdersList()
            },3600000)
        },
        //接受数据
        websocketonmessage(e){
            document.querySelector('#messageAudio').play()
        },
        //关闭websocket
        websocketclose(e){  //关闭
            clearInterval(this.wensocketTimeout)
            this.websocketonOpen()
        }
    },
    mounted() {
        let end_time = Date.parse(new Date());
        let start_time = end_time - 8.64e7 * 7;
        this.orderSearchTime = [this.formatDate(start_time,'YY-MM-DD'),this.formatDate(end_time,'YY-MM-DD')]
        this.getProductsList();
        this.getProductsType();
        this.getbaseSet()
        this.getOrdersList()
        this.initWebsocket()
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
/* .el-tabs__nav #tab-3.hasAfter::after {
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
.el-tabs__nav #tab-4.hasAfter1::after {
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
} */
</style>