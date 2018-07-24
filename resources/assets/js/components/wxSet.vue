<template>
    <el-container>
        <el-main v-loading="loading" style="min-height:300px;">
            <el-tabs type="border-card" v-if='xcxBind1' @tab-click="handletabClick">
                <el-tab-pane label="版本设置">
                    <el-card class="box-card" style="height:200px;">
                        <div slot="header" class="clearfix">
                            <span>体验版本</span>
                        </div>
                        <el-row style="margin-top:20px;" v-if="testV.length>0 && testVStatus !== -4">
                            <el-col>
                                版本号:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">{{testV[0].version}}</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-if="testV.length>0 && testVStatus !== -4">
                            <el-col>
                                上传时间:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">{{testV[0].created_at}}</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-else>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">暂无体验版本，请点击上传选择模板提交</p>
                            </el-col>
                        </el-row>
                        <el-row style="float:right;margin-top:30px;">
                            <el-button type='primary' style="width:70px;height:40px;padding: 3px 0" @click="openTestReview()">上传</el-button>
                            <el-button @click="getPreviewQrcode()" :disabled="testV.length===0">预览</el-button>
                            <el-button @click="openReview()" v-if="reviewVStatus === -4 || reviewVStatus === 1 || reviewVStatus === 0 || reviewVStatus === 3 || reviewVStatus === -4" :disabled="testV.length===0 || reviewVStatus === 3">审核</el-button>
                            <el-button @click="openReview()" v-else :disabled="reviewVStatus === 2">审核中</el-button>
                        </el-row>
                    </el-card>
                    <el-card class="box-card" style="height:200px;">
                        <div slot="header" class="clearfix">
                            <span>审核版本</span>
                        </div>
                        <el-row style="margin-top:20px;" v-if="reviewV.length>0 && reviewVStatus !== -4">
                            <el-col>
                                版本号:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">{{reviewV[0].version}}</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-if="reviewV.length>0 && reviewVStatus !== -4">
                            <el-col>
                                审核提交时间:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">{{reviewV[0].created_at}}</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-else>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">暂无审核版本，请选择体验版进行审核</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-if="reviewV.length>0 && reviewVStatus !== -4">
                            <el-col>
                                审核状态:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;" v-if="reviewVStatus === 2">审核中</p>
                                <p style="color:#00db00;line-height:40px;" v-if="reviewVStatus === 0 || reviewVStatus === 3">审核成功</p>
                                <p style="color:red;line-height:40px;" v-if="reviewVStatus === 1">审核失败</p>
                            </el-col>
                        </el-row>
                        <el-row style="float:right;margin-top:30px;">
                            <el-button @click="showReviewFailure()" type='primary' v-if='reviewVStatus === 1'>发布失败原因</el-button>
                            <el-button @click="releaseOnline()" type='primary' :disabled="reviewVStatus !== 0">发布</el-button>
                        </el-row>
                    </el-card>
                    <el-card class="box-card" style="height:200px;">
                        <div slot="header" class="clearfix">
                            <span>线上版本</span>
                        </div>
                        <el-row style="margin-top:20px;" v-if="onlineV.length>0">
                            <el-col>
                                版本号:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">{{onlineV[0].version}}</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-if="onlineV.length>0">
                            <el-col>
                                发布时间:
                            </el-col>
                            <br>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">{{onlineV[0].created_at}}</p>
                            </el-col>
                        </el-row>
                        <el-row style="margin-top:20px;" v-else>
                            <el-col>
                                <p style="color:#1da5d3;line-height:40px;">暂无发布版本，请对审核版进行发布</p>
                            </el-col>
                        </el-row>
                        <el-row style="float:right;margin-top:30px;">
                            <el-button @click="getOnlineQrcode()" type='primary' :disabled="onlineVStatus !== 3">预览</el-button>
                        </el-row>
                    </el-card>
                </el-tab-pane>
                <el-tab-pane label="基本设置">
                    <el-table :data="autoData" style="width: 100%" border class="istable">
                        <el-table-column prop="name" label="基本信息" width="100" style="background:#ddd;"></el-table-column>
                        <el-table-column prop="value" label="" width="300">
                            <template slot-scope="scope">
                                <div v-if="scope.$index == 1" style="width:80px;height:80px;overflow:hidden;border-radius: 50%;margin:0 auto;">
                                    <img width="80px" height="80px" :src="autoData[scope.$index].value">
                                </div>
                                <p v-else>{{autoData[scope.$index].value}}</p>
                            </template>
                        </el-table-column>
                        <el-table-column prop="desc" label="说明"></el-table-column>
                    </el-table>
                </el-tab-pane>
                <el-tab-pane label="体验者管理">
                    <span style="line-height:42px;">管理员可添加小程序项目成员，并配置成员的权限</span>
                    <el-button style="float:right;" @click="dialogVisible = true">添加体验者</el-button>
                    <el-table :data="testData" style="width: 100%;margin-top:20px;" border>
                        <el-table-column prop="wechatid" label="成员" width="400" style="background:#ddd;"></el-table-column>
                        <el-table-column prop="created_at" label="加入时间" width="400"></el-table-column>
                        <el-table-column label="操作">
                            <template slot-scope="scope">
                                <el-button type="danger" @click="releaseBindtest(scope.$index)">解绑</el-button>
                                <!-- <el-button type="success" v-else @click="bindtest(scope.$index)">绑定</el-button> -->
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
                <!-- <el-tab-pane label="服务器域名配置">服务器域名配置</el-tab-pane> -->
                <el-tab-pane label="模板消息配置" style="min-width:100%;">
                    <el-table class="templateInfo" :data="templateList" style="margin:20px auto;" border>
                        <el-table-column prop="template_id" label="编号" width="200" style="background:#ddd;"></el-table-column>
                        <el-table-column prop="title" label="模板标题" width="200"></el-table-column>
                        <el-table-column prop="keywords" label="关键词" width="500"></el-table-column>
                        <el-table-column label="操作">
                            <template slot-scope="scope">
                                <el-switch v-model="templateList[scope.$index].status" @change='templateStatus(scope.$index)' :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                                </el-switch>
                                <!-- <el-button type="success" v-else @click="bindtest(scope.$index)">绑定</el-button> -->
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
                <el-tab-pane label="获取参数二维码" style="min-width:100%;">
                    <el-row style="margin:10px 0;">
                        <el-col :span='3' style="line-height:40px;">
                            二维码页面
                        </el-col>
                        <el-col :span="10">
                            <el-select v-model="qrcodeX.page" placeholder="请选择">
                                <el-option v-for="(item,index) in this.pageList" :key="index" :label="item" :value="item">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-row>
                    <el-row style="margin:10px 0;">
                        <el-col :span='3' style="line-height:40px;">
                            二维码参数
                        </el-col>
                        <el-col :span='4'>
                            <el-input v-model="qrcodeX.scene"></el-input>
                        </el-col>
                    </el-row>
                    <el-row style="margin:10px 0;">
                        <el-col :span='3' style="line-height:40px;">
                            二维码大小
                        </el-col>
                        <el-col :span='4'>
                            <el-input-number v-model="qrcodeX.width" :min="1" label="宽度"></el-input-number>
                        </el-col>
                    </el-row>
                    <el-button @click="getSceneQrcode()" type="primary">生成二维码</el-button>
                </el-tab-pane>
            </el-tabs>
            <el-card class="box-card" v-if='xcxBind2' style="width:100%;">
                <div slot="header" class="clearfix">
                    <span>绑定</span>
                </div>
                <div>
                    <el-button @click='intoBind' style="display:block;background:url(img/weixinLogo.png) no-repeat 10px 5px #62b900;padding-left:55px;background-size:30px;color:#fff;border-color:#62b900;">授权绑定</el-button>
                    <p style="font-size: 14px;color: #5e6d82;line-height: 1.5em;">请点击按钮完成第三方授权绑定</p>
                </div>
            </el-card>
        </el-main>
        <!-- 上传dialog -->
        <el-dialog title="选择模板" :visible.sync="testVisible" width="400px">
            <el-row>
                <el-col :span='6' style="line-height:40px;">
                    小程序模板
                </el-col>
                <el-col :span='18'>
                    <el-select v-model="testComponts" placeholder="请选择">
                        <el-option v-for="item in this.componentsList" :key="item.template_id" :label="item.title" :value="item.template_id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row>
                <!-- <el-col>
                    预览
                </el-col>
                <el-col>
                    <img src="">
                </el-col> -->
            </el-row>
            <el-button type='primary' size='small' @click="commingTest()" style="display:block;margin:20px auto 0;">提交</el-button>
        </el-dialog>
        <el-dialog title="使用微信扫描二维码" :visible.sync="qrcodePreview" width="400px">
            <img :src="preViewQrcode" style="margin:0 auto;display:block;width:100%;">
        </el-dialog>
        <el-dialog title="添加体验者" :visible.sync="dialogVisible" width="30%">
            <el-row>
                <el-cor>
                    <el-input v-model="wxAccount" placeholder="微信号"></el-input>
                </el-cor>
            </el-row>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="dialogVisible = false,addTester()">确 定</el-button>
            </span>
        </el-dialog>
        <!-- 审核表单 -->
        <el-dialog title="审核资料填写" class="reviewTable" :visible.sync="reviewVisible" width="500px" @close='handleClose'>
            <el-row>
                <el-col>
                    标题
                </el-col>
                <el-col>
                    <el-input v-model="reviewTable.title" placeholder="输入页面标题"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    功能页面选择
                </el-col>
                <el-col>
                    <el-select v-model="reviewTable.powitem" placeholder="请选择">
                        <el-option v-for="(item,index) in this.pageList" :key="index" :label="item" :value="item">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    所在服务类目
                </el-col>
                <el-col>
                    <el-select v-model="reviewTable.category" placeholder="选择类目">
                        <el-option v-for="(item,index) in this.category" :key="index" :label="item.first_class+' > '+item.second_class" :value="{
                            first_class:item.first_class,
                            second_class:item.second_class,
                            first_id:item.first_id,
                            second_id:item.second_id,
                            }">
                        </el-option>
                    </el-select>
                    <!-- <el-select v-model="reviewTable.powitem" placeholder="二级类目">
						<el-option v-for="item in category" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select> -->
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    标签
                </el-col>
                <el-col>
                    <el-input v-model="reviewTable.tag" placeholder="输入相关标签"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-button type='primary' @click="addCommitauto()" style="display:block;margin:0 auto;">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
        <el-dialog title="审核资料列表" class="reviewTable" :visible.sync="reviewListVisible" width="500px">
            <el-button style="margin-left:30px;" @click="openCommitauto()" size='small'>新增</el-button>
            <el-row>
                <el-col>
                    <el-table :data="reviewList">
                        <el-table-column prop="title" label="标题" width="200" style="background:#ddd;"></el-table-column>
                        <el-table-column label="操作" width="200" style="background:#ddd;">
                            <template slot-scope="scope">
                                <el-button @click="removeCommitauto()" size='small' type='danger'>删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-button type='primary' @click="inputCommitauto()" style="display:block;margin:0 auto;" size='small'>提交</el-button>
                </el-col>    
            </el-row>
        </el-dialog>
        <el-dialog :visible.sync='reviewFailure' width="300" title='失败原因'>
            {{reviewFailureText}}
        </el-dialog>
    </el-container>
</template>

<script>
import axios from "axios";
import store from "@/vuex/store";
import VueAxios from "vue-axios";
export default {
    data() {
        return {
            reviewVisible: false, // 审核表单开启
            reviewListVisible: false, // 审核列表开启
            reviewFailure:false,
            reviewFailureText:'',
            qrcodePreview: false,
            preViewQrcode: "",
            onlineQrcode: "",
            dialogVisible: false,
            wxAccount: "",
            loading: true,
            xcxBind1: false,
            xcxBind2: false,
            xcxMessage: {
                xcx_created_at: "",
                xcx_updated_at: "",
                qrcode_url: ""
            },
            category: [],
            reviewTable: {
                title: "",
                powitem: "", //功能页面
                category: "",
                tag: ""
            },
            reviewList:[],
            autoData: [
                {
                    name: "小程序名称",
                    value: "噜嘟嘟",
                    desc: "小程序发布后，非个人类帐号可通过认证方式改名。"
                },
                {
                    name: "小程序头像",
                    value: '<img src="">',
                    desc: "一个月内可申请修改5次"
                },
                {
                    name: "介绍",
                    value: "个人学习与测试",
                    desc: "一个月内可申请5次修改"
                },
                {
                    name: "主体信息",
                    value: "个人",
                    desc: "企业法人及个体工商户"
                },
                {
                    name: "小程序ID",
                    value: "wx9e6b760477338476",
                    desc: "每个小程序ID特有的ID号"
                }
            ],
            testData: [],
            xcxData: {
                qrcode:
                    "http://mmbiz.qpic.cn/mmbiz_jpg/E9OTCTFJTuzv9RVv7ibeLl3VeJWiaGsIftGnXuswCassLQzkmhgasjGn7pDlK1LeibD5fNSqsDbhnGxflwcws67jw/0"
            },
            templateList: [],
            pageList: [],
            buttonUrl: "",
            //版本资料列表
            versionList: [
                {
                    template_id: "", //模板id
                    version: "", //版本号
                    audit_id: "", //审核id
                    status: "", //-1 上传  0 审核成功  1 审核失败  2 审核中  3 发布
                    item_list: "", //提交审核向
                    reason: "", //审核失败原因
                    succ_time: "", //成功时间
                    fail_time: "", //失败时间
                    created_at: "" //创建时间
                }
            ],
            //当前最新体验版本(first)
            testV: [],
            testVStatus:-4,
            componentsList:[],
            testComponts:'',
            testVisible:false,

            //当前审核版本(first)
            reviewV: [],
            reviewVStatus:-4,
            //当前线上版本(first)
            onlineV: [],
            onlineVStatus:-4,


            //生成二位码参数
            qrcodeX:{
                page:'',
                scene:'',
                width:'430'
            }
        };
    },
    methods: {
        handletabClick(tab, event){
            if (tab.index == 0) {
                this.getTestV();
            }else if(tab.index == 4){
                this.getCategoryList()
            }
        },
        //版本信息
        getTestV() {
            this.testV = [],
            this.testVStatus=-4,
            this.reviewV = [],
            this.reviewVStatus=-4,
            this.onlineV= [],
            this.onlineVStatus=-4,
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_audits")
                .then(res => {
                    this.versionList = res.data.data;
                    for (let i = 0; i < this.versionList.length; i++) {
                        if (this.versionList[i].status === -1) {
                            this.testV.push(this.versionList[i]);
                        } else if (
                            this.versionList[i].status === 0 ||
                            this.versionList[i].status === 1 ||
                            this.versionList[i].status === 2
                        ) {
                            this.reviewV.push(this.versionList[i]);
                        } else {
                            this.onlineV.push(this.versionList[i]);
                        }
                    }
                    
                    //整理线上版本
                    if(!(this.onlineV.length > 0)){
                        this.onlineVStatus = -4
                    }else{
                        this.onlineVStatus = this.onlineV[0].status
                    }
                    
                    //整理审核版本
                    if(!(this.reviewV.length > 0)){
                        if(this.onlineV.length > 0){
                            this.reviewV.push(this.onlineV[0])
                            this.reviewVStatus = this.reviewV[0].status
                        }else{
                            this.reviewVStatus = -4
                        }
                    }else{
                        if(this.onlineV.length > 0){
                            if(Date.parse(this.formatDate(this.reviewV[0].created_at))>Date.parse(this.formatDate(this.onlineV[0].created_at))){
                                this.reviewVStatus = this.reviewV[0].status
                            }else{
                                this.reviewVStatus = -4
                            }
                        }else{
                            this.reviewVStatus = this.reviewV[0].status
                        }
                    }
                    
                    //整理体验版本
                    if(!(this.testV.length > 0)){
                        if(this.onlineV.length > 0){
                            this.testV.push(this.reviewV[0])
                            this.testVStatus = this.testV[0].status
                        }else{
                            this.testVStatus = -4
                        }
                    }else{
                        this.testVStatus = this.testV[0].status
                    }
                    if(Date.parse(this.formatDate(this.testV[0].created_at)) > Date.parse(this.formatDate(this.reviewV[0].updated_at)) &&
                            Date.parse(this.formatDate(this.testV[0].created_at)) > Date.parse(this.formatDate(this.onlineV[0].audit_time))
                    ){
                        this.testVStatus = this.testV[0].status
                    }else{
                        this.testVStatus = -4
                    }                    
                });
        },
        //获取模板
        getTemplate(){
            axios.get("/api/templates").then(res => {
                this.componentsList = res.data.data;
            });
        },
        //打开上传
        openTestReview(){
            this.testVisible = true
            this.testComponts = ''
        },
        //提交上传
        commingTest(){
            axios.get("/wechat/" + store.state.xcxId.xcxID + "/commit/" + this.testComponts).then(res=>{
                if(res.data.errcode === 0){
                    this.showMessage('success','上传成功')
                    this.testVisible = false
                    this.getTestV();
                }else{
                    this.testVisible = false
                    this.showMessage('error','上传失败，请稍后再试或与管理员联系。')
                    this.getTestV();
                }
                
            })
        },
        //发布版本
        releaseOnline(){
            axios.get("/wechat/" + store.state.xcxId.xcxID + "/release").then(res=>{
                    this.showMessage('success','发布成功')
                    this.getTestV()
            })
        },
        //查看失败原因
        showReviewFailure(){
            this.reviewFailure = true
            this.reviewFailureText = this.reviewV[0].reason
        },

        //打开审核资料
        openReview() {
            this.reviewListVisible = true;
        },
        //打开新增审核资料
        openCommitauto(){
            this.reviewListVisible = false;
            this.reviewVisible = true;
            this.reviewTable = {
                    title: "",
                    powitem: "", //功能页面
                    category: '',
                    tag: ""
                };
        },
        //添加审核
        addCommitauto() {
            if (
                this.reviewTable.title == "" ||
                this.reviewTable.powitem == "" ||
                this.reviewTable.category === "" ||
                this.reviewTable.tag == ""
            ) {
                this.showMessage("error", "有项目未填");
            } else {
                this.reviewList.push({
                    address:this.reviewTable.powitem,
                    tag:this.reviewTable.tag,
                    first_class:this.reviewTable.category.first_class,
                    second_class:this.reviewTable.category.second_class,
                    first_id:this.reviewTable.category.first_id,
                    second_id:this.reviewTable.category.second_id,
                    title:this.reviewTable.title
                })
                console.log(this.reviewList);
                
                this.reviewTable = {
                    title: "",
                    powitem: "", //功能页面
                    category: '',
                    tag: ""
                };
                this.reviewVisible = false;
                
            }
        },
        //删除审核记录
        removeCommitauto(index){
            this.reviewList.splice(index,1)
        },
        //提交审核
        inputCommitauto(){
            axios.post('/wechat/' + store.state.xcxId.xcxID + "/submit_audit",{
                itemList:this.reviewList,
                id:this.testV[0].id
                }).then(res=>{
                    this.showMessage("success", "成功提交审核");
                    this.reviewVisible = false;
                    this.reviewList = []
                    this.getTestV()
                },res=>{
                    this.showMessage("error", res.errmsg);
                    this.reviewVisible = false;
                    this.reviewList = []
                    this.getTestV()
                })
        },

        //单项审核资料关闭
        handleClose(){
            this.reviewListVisible = true;
        },
        //获取category列表
        getCategoryList() {
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_category")
                .then(res => {
                    this.category = res.data.category_list;
                });

            //getPage
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_page")
                .then(res => {
                    this.pageList = res.data.page_list;
                });
        },

        //获取体验版预览二维码
        getPreviewQrcode() {
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_qrcode/", {
                    responseType: "blob"
                })
                .then(
                    res => {
                        this.qrcodePreview = true;
                        var reader = new FileReader();
                        window.URL.revokeObjectURL(this.preViewQrcode);
                        this.preViewQrcode = window.URL.createObjectURL(
                            res.data
                        );
                    },
                    res => {
                        this.showMessage(
                            "error",
                            "二维码获取错误，请联系管理员,或者稍后再重新获取"
                        );
                    }
                );
        },
        //获取线上版本二维码
        getOnlineQrcode(){
            axios
                .post("/wechat/" + store.state.xcxId.xcxID + "/get_qrcode_online",{}, {
                    responseType: "blob"
                })
                .then(
                    res => {
                        this.qrcodePreview = true;
                        var reader = new FileReader();
                        window.URL.revokeObjectURL(this.preViewQrcode);
                        this.preViewQrcode = window.URL.createObjectURL(
                            res.data
                        );
                    },
                    res => {
                        this.showMessage(
                            "error",
                            "二维码获取错误，请联系管理员,或者稍后再重新获取"
                        );
                    }
                );
        },
        //生成二维码
        getSceneQrcode(){
            if(this.qrcodeX.page == ''){
                this.showMessage('error','二维码页面未选择')
                return false
            }
            axios.post("/wechat/" + store.state.xcxId.xcxID + "/get_qrcode_scene",{
                page:this.qrcodeX.page,
                scene:this.qrcodeX.scene,
                width:this.qrcodeX.width
            }, {
                    responseType: "blob"
                }).then(res=>{
                    this.qrcodePreview = true;
                    var reader = new FileReader();
                    window.URL.revokeObjectURL(this.preViewQrcode);
                    this.preViewQrcode = window.URL.createObjectURL(
                        res.data
                    );
            })
        },


        //获取体验者列表
        getTestList() {
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_testers")
                .then(res => {
                    this.testData = res.data.data;
                });
        },
        //绑定连接跳转
        intoBind() {
            // this.xcxBind = !this.xcxBind;
            window.location.href = this.buttonUrl;
        },
        //获取绑定信息
        getXCXAuthorized() {
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/miniprogram")
                .then(res => {
                    // console.log(res);
                    if (res.data.status === "unauthorize") {
                        // console.log("未绑定");
                        this.buttonUrl = res.data.data;
                        this.loading = false;
                        this.xcxBind2 = true;
                    } else {
                        // console.log("已绑定");
                        // console.log(eval("(" + res.data.data.func_info + ")"));
                        // console.log(res.data);
                        this.getCategoryList();
                        this.loading = false;
                        this.xcxBind1 = true;
                        this.autoData[0].value = res.data.data.nick_name;
                        this.autoData[1].value = res.data.data.head_img;
                        this.autoData[2].value = res.data.data.signature;
                        this.autoData[3].value = res.data.data.principal_name;
                        this.autoData[4].value = res.data.data.app_id;
                        this.xcxMessage.xcx_created_at =
                            res.data.data.created_at;
                        this.xcxMessage.xcx_updated_at =
                            res.data.data.updated_at;
                        this.xcxMessage.qrcode_url = res.data.data.qrcode_url;
                    }
                });
        },
        //绑定体验者微信号
        addTester() {
            axios
                .get(
                    "/wechat/" +
                        store.state.xcxId.xcxID +
                        "/bind_tester/" +
                        this.wxAccount
                )
                .then(res => {
                    // console.log(res);
                    this.getTestList();
                });
        },
        //解绑体验者微信号
        releaseBindtest(index) {
            axios
                .get(
                    "/wechat/" +
                        store.state.xcxId.xcxID +
                        "/unbind_tester/" +
                        this.testData[index].wechatid
                )
                .then(res => {
                    // console.log(res);
                    this.getTestList();
                });
        },
        //获取模板消息列表
        getNoticeTemplateList() {
            axios
                .get(
                    "/wechat/" +
                        store.state.xcxId.xcxID +
                        "/get_notice_templates"
                )
                .then(res => {
                    this.templateList = res.data;
                });
        },
        //改变模板消息
        templateStatus(index) {
            axios
                .get(
                    "/wechat/" +
                        store.state.xcxId.xcxID +
                        "/add_template/" +
                        this.templateList[index].id
                )
                .then(res => {
                    // console.log(this.templateList[index].status);
                });
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },
        //format time
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
    },
    mounted() {
        this.getTemplate()
        this.getTestV();
        this.getXCXAuthorized();
        this.getTestList();
        this.getNoticeTemplateList();
    }
};
</script>

<style scoped>
.box-card {
    margin-bottom: 30px;
}
.box-card .el-row {
    float: left;
}
.box-card .el-row + .el-row {
    margin-left: 30px;
}
.reviewTable .el-row {
    margin-left: 30px;
    margin-right: 30px;
    margin-bottom: 20px;
}
.reviewTable .el-row .el-col {
    margin-bottom: 3px;
}
</style>