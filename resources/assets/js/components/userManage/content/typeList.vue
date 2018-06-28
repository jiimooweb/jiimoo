<template>
    <div class="pow2" style="overflow:hidden;">
        <el-scrollbar class="is-vertical" style='height:100%; overflow:hidden;'>
            <div class="pow2R">
                <el-main>
                    <!-- <el-breadcrumb>
                        <el-breadcrumb-item separator='>' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
                        </el-breadcrumb-item>
                    </el-breadcrumb> -->
                    <el-row>
                        <el-col :span='2'>
                            <el-button @click.stop="setFixPageShow(true),isnewArticle = true,clearUE()" type="button" size="small" style="margin:20px 0;">
                                添加
                            </el-button>
                        </el-col>
                        <el-col :span='3' style="margin:16px 0px;">
                            <el-select v-model="searchValue" filterable placeholder="请选择分类">
                                <el-option v-for="item in typeList" :key="item.id" :label="item.name" :value="item.id">
                                </el-option>
                            </el-select>
                        </el-col>
                        <el-col :span='4' style="margin:16px 10px;">
                            <el-input v-model="search" type='search' placeholder="请输入搜索关键词"></el-input>
                        </el-col>
                        <el-col :span='4' style="margin:16px 20px;">
                            <!-- <el-popover trigger="click" width="600" style="margin-bottom:16px;">
                                <el-row>
                                    <el-col :span='2' style="line-height:40px;">
                                        日期:
                                    </el-col>
                                    <el-col :span='12'>
                                        <el-date-picker v-model="searchData.data" type="daterange" align="right" unlink-panels range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期" :picker-options="pickerOptions">
                                        </el-date-picker>
                                    </el-col>
                                </el-row>
                                <el-button slot="reference">筛选</el-button>
                            </el-popover> -->
                            <el-button @click="getSearch()" type="primary">搜索</el-button>
                        </el-col>
                        <el-col :span='3' style="margin:16px 20px;float:right;">
                            <el-button @click="toType()">文章分类>></el-button>
                        </el-col>
                    </el-row>
                    <el-table :data="searchData?searchData:''" border v-loading="loading">
                        <el-table-column prop="title" label="标题"></el-table-column>
                        <el-table-column prop="category.name" width="100" label="分类"></el-table-column>
                        <el-table-column prop="click" width="100" label="点击" sortable></el-table-column>
                        <el-table-column prop="updated_at" width='160' label="时间" sortable></el-table-column>
                        <el-table-column label="操作" width='150'>
                            <template slot-scope="scope">
                                <!-- scope.$index  -->
                                <el-col :span="10">
                                    <el-button @click.stop="getRowArticle(scope.$index)" type="primary" size="small">
                                        编辑
                                    </el-button>
                                </el-col>
                                <el-col :span="10" :offset="1">
                                    <el-button @click.stop="removeArticle(scope.$index)" type="danger" size="small">
                                        删除
                                    </el-button>
                                </el-col>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-main>
            </div>
        </el-scrollbar>
        <el-dialog :visible.sync="fixShow" class="dialogFooter" height='80% !important' style="overflow:hidden;">
            <!-- <el-scrollbar class="rightPageScroll" style='min-width:950px;margin-left:-17px;height:700px;overflow-x:hidden;'> -->
            <el-row>
                <el-col :span="1" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                    标题:
                </el-col>
                <el-col :span="13">
                    <el-input v-model="fixMoudle.title" placeholder="请输入标题"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-top:20px;overflow:hidden;">
                <el-col :span="1" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                    分类:
                </el-col>
                <el-col :span="6">
                    <el-select v-model="fixMoudle.cate_id" placeholder="请选择">
                        <el-option v-for="item in typeList" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row style="margin-top:20px;">
                <el-col :span="1" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                    作者:
                </el-col>
                <el-col :span="13">
                    <el-input v-model="fixMoudle.author" placeholder="请输入内容"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-top:20px;">
                <el-col :span="1" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                    图片:
                </el-col>
                <el-col :span="13">
                    <el-upload class="upload-demo" :file-list="fileList" :before-upload="beforeAvatarUpload" action="/qiniuUpload" :before-remove='beforeRemove' :on-preview="handlePreview" :headers="headers" :onSuccess="uploadSuccess" :limit="1" list-type="picture">
                        <el-button size="small" type="primary">点击上传</el-button>
                        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过2Mb</div>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-top:20px;">
                <el-col :span="1" :offset="5" style="color:#333;font-weigth:bold;line-height:40px;">
                    内容:
                </el-col>
                <el-col :span="13" style="height:500px;overflow:auto;">
                    <quill-editor style="height:300px;" ref="myTextEditor" :content="content" :config="editorOption" @change="onEditorChange($event)">
                    </quill-editor>
                </el-col>
            </el-row>
            <el-row slot="footer">
                <el-col>
                    <span class="dialog-footer" style="margin:0 auto;display: block;width: 170px;">
                        <el-button @click="fixShow = false">取 消</el-button>
                        <el-button type="primary" @click="isArticle()">确 定</el-button>
                    </span>
                </el-col>
            </el-row>

            <!-- </el-scrollbar> -->
        </el-dialog>
    </div>
</template>
<script>
import _rq from "@/api/index";
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import { quillEditor } from "vue-quill-editor";
export default {
    name: "power2",
    data() {
        return {
            content: "",
            editorOption: {
                // something config
            },
            loading: true,
            newTypeState: false,
            newTypeName: "",
            search: "",
            isnewArticle: false,
            typeList: [],
            clickType: false,
            options: [
                {
                    value: "1",
                    label: "1"
                }
            ],
            searchValue: "",
            selectValue: {
                data: ""
            },
            pickerOptions: {
                shortcuts: [
                    {
                        text: "最近一周",
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(
                                start.getTime() - 3600 * 1000 * 24 * 7
                            );
                            picker.$emit("pick", [start, end]);
                        }
                    },
                    {
                        text: "最近一个月",
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(
                                start.getTime() - 3600 * 1000 * 24 * 30
                            );
                            picker.$emit("pick", [start, end]);
                        }
                    },
                    {
                        text: "最近三个月",
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(
                                start.getTime() - 3600 * 1000 * 24 * 90
                            );
                            picker.$emit("pick", [start, end]);
                        }
                    }
                ]
            },
            value: "",
            fixMoudle: {
                title: "",
                author: "",
                arcID: "",
                cate_id: "",
                fileUrl: []
                /*
                    {
                        name: "food.jpeg",
                        url:
                            "https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100"
                    }
                    */
            },
            fileList: [],
            breadlist: "",
            articleData: [],
            fixShow: false,
            dat: {
                content: ""
            }
        };
    },
    components: { quillEditor },
    methods: {
        //搜索
        getSearch(){
            let fnData = [];
            this.loading = true;
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/articles?keyword="+(this.search==''?'':this.search)+"&cate_id="+this.searchValue
                )
                .then(response => {
                    for (let i = 0; i < response.data.data.data.length; i++) {
                        fnData.push(response.data.data.data[i]);
                    }
                    this.loading = false;
                });
            this.articleData = fnData;
        },
        toType(){
            this.$router.push({path:'/userManage/content/articleType'})
        },
        onEditorBlur(editor) {
            console.log("editor blur!", editor);
        },
        onEditorFocus(editor) {
            console.log("editor focus!", editor);
        },
        onEditorReady(editor) {
            console.log("editor ready!", editor);
        },
        onEditorChange({ editor, html, text }) {
            // console.log('editor change!', editor, html, text)
            this.content = html;
        },
        returnContent() {
            console.log(this.$refs.ue.getUEContent());
        },
        clearUE() {
            this.fixMoudle.title = "";
            this.fixMoudle.author = "";
            this.fixMoudle.arcID = "";
            this.value = "";
            this.content = "";
            this.fixMoudle.fileUrl = "";
            this.fileList = [];
        },
        getTypeList() {
            let fnData = [];
            this.loading = true;
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
                    this.loading = false;
                });
            this.articleData = fnData;
            console.log(this.articleData);
        },
        addArticleType() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/article_cates",
                    { name: this.newTypeName }
                )
                .then(response => {
                    this.newTypeState = false;
                    console.log(response);
                    this.typeList.push(response.data.data);
                });
            // this.typeList=[{name:'123'},{name:'234s'}]
        },
        removeArticleType(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/article_cates/" +
                        this.typeList[index].id
                )
                .then(response => {
                    this.typeList.splice(index, 1);
                });
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
        uploadSuccess(response) {
            this.fixMoudle.fileUrl = response.url;
        },
        beforeRemove() {
            axios
                .post("/qiniuDelete", { url: this.fixMoudle.fileUrl })
                .then(res => {
                    this.fixMoudle.fileUrl = "";
                });
        },
        handlePreview(file) {
            //   console.log(file);
        },
        beforeAvatarUpload(file) {
            const isJPG = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;

            if (!isJPG) {
                this.$message.error("上传头像图片只能是 JPG 格式!");
            } else if (!isLt2M) {
                this.$message.error("上传头像图片大小不能超过 2MB!");
            }
            /*else{
            axios.post('/api/jiimoo/public/qiniuUpload')
        }*/
            return isJPG && isLt2M;
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },
        getRowArticle(index) {
            this.clearUE();
            this.isnewArticle = false;
            this.setFixPageShow(true);
            this.fixMoudle.title = this.articleData[index].title;
            this.fixMoudle.author = this.articleData[index].author;
            this.fixMoudle.arcID = this.articleData[index].id;
            this.fixMoudle.cate_id = this.articleData[index].cate_id;
            this.content = this.articleData[index].content;
            this.fixMoudle.fileUrl = this.articleData[index].thumb;
            this.fileList = [];

            if (this.articleData[index].thumb != "undefined") {
                this.fileList.push({
                    name: "picture",
                    url: this.articleData[index].thumb
                });
                console.log(this.articleData[index].thumb);
            }
        },
        isArticle() {
            if (this.fixMoudle.fileUrl == "") {
                this.fixMoudle.fileUrl = "";
            }
            if (this.isnewArticle) {
                this.creatArticle();
            } else {
                this.UpdataArticle();
            }
        },
        creatArticle() {
            if (this.fixMoudle.title == "") {
                this.showMessage("error", "标题不能为空");
                console.log(this.fixMoudle.fileUrl);

                return;
            }
            if (this.fixMoudle.fileUrl == "") {
                this.showMessage("error", "图片不能为空");
                return;
            }
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/articles",
                    {
                        title: this.fixMoudle.title,
                        thumb: this.fixMoudle.fileUrl,
                        author: this.fixMoudle.author,
                        content: this.content,
                        cate_id: this.value
                    }
                )
                .then(res => {
                    this.showMessage("success", "上传成功");
                    this.setFixPageShow(false);
                    this.getTypeList();
                });
        },
        UpdataArticle() {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/articles/" +
                        this.fixMoudle.arcID,
                    {
                        title: this.fixMoudle.title,
                        thumb: this.fixMoudle.fileUrl,
                        author: this.fixMoudle.author,
                        content: this.content,
                        cate_id: this.fixMoudle.cate_id
                    }
                )
                .then(response => {
                    this.showMessage("success", "上传成功");
                    this.setFixPageShow(false);
                    this.getTypeList();
                });
        },
        removeArticle(index) {
            axios.delete(
                "/web/" +
                    store.state.xcx_flag.xcx_flag +
                    "/api/articles/" +
                    this.articleData[index].id
            );
            this.getTypeList();
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
    computed: {
        searchData: function() {
            var search = this.search;
            if (search) {
                return this.articleData.filter(function(articleData) {
                    return ["title"].some(function(key) {
                        return (
                            String(articleData[key])
                                .toLowerCase()
                                .indexOf(search) > -1
                        );
                    });
                });
            }
            return this.articleData;
        },
        headers() {
            return {
                token: localStorage.token
            };
        }
    },
    mounted() {
        this.getBread();
        this.getTypeList();
        this.getArticleType();
        console.log(this.uploadUrl);
    }
};
</script>

<style>
.el-main {
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
.fixPage {
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
}
.dialogFooter .el-dialog {
    width: 950px;
    margin-top: 7% !important;
    height: 80%;
    overflow: auto;
}
.dialog-footer {
    height: 100%;
    overflow: hidden;
}
</style>