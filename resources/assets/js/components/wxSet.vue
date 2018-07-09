<template>
	<el-container>
		<el-main v-loading="loading" style="min-height:300px;">
			<el-tabs type="border-card" v-if='xcxBind1'>
				<el-tab-pane label="版本设置">
					<el-card class="box-card" style="height:200px;">
						<div slot="header" class="clearfix">
							<span>体验版本</span>
							
						</div>
						<el-row style="margin-top:20px;">
							<el-col>
								版本号:
							</el-col>
							<br>
							<el-col>
								<p style="color:#1da5d3;line-height:40px;">v1.0.0.1</p>
							</el-col>
						</el-row>
						<el-row style="margin-top:20px;">
							<el-col>
								创建时间:
							</el-col>
							<br>
							<el-col>
								<p style="color:#1da5d3;line-height:40px;">{{xcxMessage.xcx_created_at}}</p>
							</el-col>
						</el-row>
						<!-- <el-row style="margin-top:20px;">
							<el-col>
								更新时间:
							</el-col>
							<br>
							<el-col>
								<p style="color:#1da5d3;line-height:40px;">{{xcxMessage.xcx_updated_at}}</p>
							</el-col>
						</el-row> -->
						<el-row style="float:right;margin-top:30px;">
							<el-button type='primary' style="width:70px;height:40px;padding: 3px 0">上传</el-button>
							<el-button @click="getPreviewQrcode()">预览</el-button>
						</el-row>
					</el-card>
					<el-card class="box-card">
						<div slot="header" class="clearfix">
							<span>审核版本</span>
							<el-button style="float: right; padding: 3px 0" @click="openReview()" type="text" v-if="true">提交审核</el-button>
							<el-button style="float: right; padding: 3px 0" type="text" v-else>审核中</el-button>
						</div>
					</el-card>
					<el-card class="box-card">
						<div slot="header" class="clearfix">
							<span>线上版本</span>
							<el-button style="float: right; padding: 3px 0" type="text">提交审核</el-button>
						</div>
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
		<el-dialog title="审核资料填写" class="reviewTable" :visible.sync="reviewVisible" width="30%">
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
						<el-option v-for="(item,index) in this.category" :key="index" :label="item.first_class+' > '+item.second_class" :value="index">
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
					<el-button type='primary' @click="inputCommitauto()" style="display:block;margin:0 auto;">提交</el-button>
				</el-col>
			</el-row>
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
            qrcodePreview: false,
            preViewQrcode: "",
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
                category: '',
                tag: ""
            },
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
            versionList:[
                {
                    template_id:'',//模板id
                    version:'',//版本号
                    audit_id:'',//审核id
                    status:'',//-1 上传  0 审核成功  1 审核失败  2 审核中  3 发布
                    item_list:'',//提交审核向
                    reason:'',//审核失败原因
                    succ_time:'',//成功时间
                    fail_time:'',//失败时间
                    create_time:'',//创建时间 
                }
            ],
            //当前最新体验版本
            testV:[],
            //当前审核版本
            reviewV:[],
            //当前线上版本
            onlineV:[]
        };
    },
    methods: {
        //体验版本信息
        getTestV(){
            axios.get("/wechat/" + store.state.xcxId.xcxID + "/get_audits").then(res=>{
                this.versionList = res.data
                for(let i=0;i<this.versionList.length;i++){
                    if(this.versionList[i].status === -1){
                        this.testV.push(this.versionList[i])
                    }else if(this.versionList[i].status === 0 ||
                     this.versionList[i].status === 1 ||
                     this.versionList[i].status === 2){
                         this.reviewV.push(this.versionList[i])
                    }else{
                        this.onlineV.push(this.versionList[i])
                    }
                }
                console.log(this.testV);
                console.log(this.reviewV);
                console.log(this.onlineV);
            })
        },


		//打开审核资料
		openReview(){
			this.reviewVisible = true
			this.reviewTable = {
                title: "",
                powitem: "", //功能页面
                category: {},
                tag: ""
            }
		},
		//提交审核
		inputCommitauto() {
			if(this.reviewTable.title == ''
				|| this.reviewTable.powitem == ''
				|| this.reviewTable.category === ''
				|| this.reviewTable.tag == ''
			){  
				this.showMessage('error','有项目未填')
			}else{
                this.showMessage('success','成功提交审核（未完成）')
                this.reviewVisible = false
            }
		},
        //获取category列表
        getCategoryList() {
            axios
                .get(
                    "/wechat/" + store.state.xcxId.xcxID + "/get_category"
                )
                .then(res => {
                    this.category = res.data.category_list;
				// console.log(res.data.category_list);
				});
				
            //getPage
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_page")
                .then(res => {
                    this.pageList = res.data.page_list;
                });
        },

        //获取体验版预览二维码
        convertBase64UrlToBlob(urlData) {
            var bytes = window.atob(urlData.split(",")[1]); //去掉url的头，并转换为byte
            //处理异常,将ascii码小于0的转换为大于0
            var ab = new ArrayBuffer(bytes.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < bytes.length; i++) {
                ia[i] = bytes.charCodeAt(i);
            }
            return new Blob([ab], { type: "image/png" });
        },
        getPreviewQrcode() {
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_qrcode/",{responseType:'blob'})
                .then(res => {
                    this.qrcodePreview = true;
                    // this.preViewQrcode = "data:image/png;base64," + res.data;
                    var reader = new FileReader();
                    // console.log(this.convertBase64UrlToBlob(res.data));

                    // this.preViewQrcode = this.convertBase64UrlToBlob(res.data);
                    console.log(res.data);
                    window.URL.revokeObjectURL(this.preViewQrcode)
                    this.preViewQrcode = window.URL.createObjectURL(res.data);
                    console.log(this.preViewQrcode);
                    
                },res=>{
                    this.showMessage('error','二维码获取错误，请联系管理员,或者稍后再重新获取')
                });
        },
        //获取体验者列表
        getTestList() {
            axios
                .get("/wechat/" + store.state.xcxId.xcxID + "/get_testers")
                .then(res => {
                    this.testData = res.data.data;
                    // console.log(this.testData);
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
                    console.log(res);
                    if (res.data.status === 'unauthorize') {
                        console.log("未绑定");
                        this.buttonUrl = res.data.data;
                        this.loading = false;
                        this.xcxBind2 = true;
                    } else {
                        console.log("已绑定");
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
                    console.log(this.templateList[index].status);
                });
		},
		showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },
    },
    mounted() {
        this.getXCXAuthorized();
        this.getTestList();
        this.getNoticeTemplateList();
        this.getTestV()
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