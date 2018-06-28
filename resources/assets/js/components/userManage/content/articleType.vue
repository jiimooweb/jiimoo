<template>
	<div class="pow2">
		<el-scrollbar class="is-vertical" style='height:100%;'>
			<div class="pow2R">
				<el-main>
					<!-- <el-breadcrumb>
						<el-breadcrumb-item separator='/' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
						</el-breadcrumb-item>
					</el-breadcrumb> -->
					<el-row>
						<el-col :span='2'>
							<el-button @click.stop="newType" type="button" size="small" style="margin:20px 0;">
								添加
							</el-button>
						</el-col>
                        <el-col :span='3' style="margin:16px 20px;float:right;">
                            <el-button @click="toList()">文章列表>></el-button>
                        </el-col>
					</el-row>
					<el-table :data="typeList?typeList:''" border>
						<el-table-column prop="name" label="分类名称"></el-table-column>
						<el-table-column prop="articles_count" label="文章数" width='80'></el-table-column>
						<el-table-column prop="id" label="分类ID" width='80'></el-table-column>
						<el-table-column label="编辑" width='80'>
							<template slot-scope="scope">
								<!-- scope.$index  -->
								<el-button @click.stop="getRowArticle(scope.$index)" :title="scope.row" plain type="primary" size="small">
									编辑
								</el-button>
							</template>
						</el-table-column>
						<el-table-column label="删除" width='80'>
							<template slot-scope="scope">
								<!-- scope.$index  -->
								<el-button @click.stop="removeArticleType(scope.$index)" :title="scope.row" plain type="danger" size="small">
									删除
								</el-button>
							</template>
						</el-table-column>
					</el-table>
					<el-dialog :visible.sync="fixShow" class="dialog-footer" width='950px' height='auto !important'>
							<el-row>
								<el-col :span="2" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
									分类名称:
								</el-col>
								<el-col :span="10">
									<el-input v-model="typeName" placeholder="请输入分类名称"></el-input>
								</el-col>
							</el-row>
							<el-row v-if="!newTypeState">
								<el-col :span="2" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
									分类ID:
								</el-col>
								<el-col :span="10">
									<el-input v-model="typeID" :disabled='typeIDdisplay'></el-input>
								</el-col>
							</el-row>
						<span slot="footer" class="dialog-footer">
							<el-button @click="fixShow = false">取 消</el-button>
							<el-button type="primary" @click="addArticleType(),setFixPageShow(false)">确 定</el-button>
						</span>
					</el-dialog>
				</el-main>
			</div>
		</el-scrollbar>
	</div>
</template>
<script>
import _rq from "@/api/index";
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    name: "articleType",
    data() {
        return {
            typeIDdisplay: true,
            newTypeState: false,
			isnew: false,
			typeNum:'',
            typeName: "",
            typeID: "",
            typeList: [],
            clickType: false,
            options: [
                {
                    value: "1",
                    label: "1"
                }
            ],
            value: "",
            breadlist: "",
            articleData: [],
            fixShow: false,
            dat: {
                content: ""
            }
        };
    },
    methods: {
        toList(){
            this.$router.push({path:'/userManage/content/articles'})
        },
		newType(){
			this.setFixPageShow(true)
			this.newTypeState=true
			this.typeName= ""
            this.typeID= ""
		},
        getTypeList() {
            let fnData = [];
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/articles"
                )
                .then(response => {
                    for (let i = 0; i < response.data.data.data.length; i++) {
                        fnData.push(response.data.data.data[i]);
                    }
                });
            this.articleData = fnData;
                console.log(this.articleData);
        },
        addArticleType() {
            if (this.newTypeState) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/article_cates",
                        { name: this.typeName }
                    )
                    .then(response => {
                        this.newTypeState = false;
						this.typeList.push(response.data.data);
						this.showMessage('success','添加成功')
                    });
            }else{
				axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/article_cates/"+this.typeID,
                        { name: this.typeName }
                    )
                    .then(response => {
						this.typeList[this.typeNum].name = this.typeName
						this.newTypeState = false
						this.showMessage('success','修改成功')
						})
			}

            // this.typeList=[{name:'123'},{name:'234s'}]
        },
        removeArticleType(index) {
            if(this.articleData[index].articles_count == 0){
                axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/article_cates/" +
                        this.typeList[index].id
                )
                .then(response => {
                    this.typeList.splice(index, 1);
						this.showMessage('success','删除成功')
                });
            }else{
                this.showMessage('error', '分类下有文章，请删除后再删除分类')
            }
            
        },
        getBread() {
            this.breadlist = this.$route.matched;
            this.$route.matched.forEach((item, index) => {
                //先判断父级路由是否是空字符串或者meta是否为首页，直接复写路径到根路径
                item.meta.CName === "首页"
                    ? (item.path = "/")
                    : this.$route.path === item.path;
            });
        },
        setFixPageShow(fixShow) {
            this.fixShow = fixShow;
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },
        getRowArticle(index) {
			this.typeNum = index
            this.newTypeState = false;
            this.typeName = this.typeList[index].name;
            this.typeID = this.typeList[index].id;
			this.setFixPageShow(true);
        },
        getArticleType() {
            var ArticleType = [];
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/article_cates"
                )
                .then(response => {
                    for (let i = 0; i < response.data.data.length; i++) {
                        ArticleType.push(response.data.data[i]);
                    }
                });
            this.typeList = ArticleType;
            console.log(this.typeList);
        }
    },
    watch: {
        $route() {
            this.getBread();
        }
    },
    computed: {},
    mounted() {
        console.log(store.state.xcx_flag.xcx_flag);
        // store.state.xcx_flag
        this.getBread();
        // //获取分类列表
        this.getTypeList();
        console.log();
        this.getArticleType();
    }
};
</script>

<style>
.el-main{
    background: #fff;
    margin-top: 20px;
    border-radius: 10px;
    margin-left: 10px;
    margin-right: 10px;
}
pow2 > .el-scrollbar > .el-scrollbar__wrap,
.rightPageScroll.el-scrollbar > .el-scrollbar__wrap {
    overflow-x: hidden !important;
}
.pow2 {
    width: 100%;
    height: 100%;
}
.pow2L {
    background: #fff;
    width: 199px;
    height: 100%;
    float: left;
    border-right: 1px solid #1da5d3;
}
.pow2L > a {
    display: block;
    background: #fff;
    width: calc(100% - 40px);
    padding-left: 40px;
    height: 42px;
    line-height: 42px;
    font-size: 14px;
    color: rgba(0, 0, 0, 0.65);
}
.pow2L > a.router-link-active {
    padding-left: 20px;
    width: calc(100% - 20px - 4px);
    border-left: 4px solid #3091f2;
    background: rgba(48, 145, 242, 0.2);
}
.pow2R {
    width: 100%;
    height: 100%;
    overflow-y: auto;
}
/* .fixPage {
    position: fixed;
    width: 100%;
    height: 100%;
    transform: translateZ(1px);
    z-index: 2;
    top: 0px;
    left: 0px;
    background: rgba(0, 0, 0, 0.6);
}
.fixContent {
    padding: 20px 0;
    min-width: 1000px;
    width: 60%;
    height: 600px;
    margin-left: 20%;
    margin-top: 150px;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #d3d3d3;
} */
</style>