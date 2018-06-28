<template>
    <el-main style="min-width:950px;">
        <el-row style="margin-bottom:50px;">
            <el-breadcrumb>
                <el-breadcrumb-item separator-class="el-icon-arrow-right" v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </el-row>
        <el-row>
            <el-button type='success' size="small" style="margin-bottom:10px;" @click="openNewDialog()">新增</el-button>
            <el-table border :data="personnelList?personnelList:''">
                <el-table-column prop="name" label="人员名称" width='150px'></el-table-column>
                <el-table-column label="标签/类别" width='300px'>
                    <template slot-scope="scope">
                        <el-tag style="margin-right:10px;" v-for='(item,index) in personnelList[scope.$index].career.slice(0,4)' v-model="item.career" :key="index">{{item.career}}</el-tag>
                        <span v-if='personnelList[scope.$index].career.length>4'>...</span>
                    </template>
                </el-table-column>
                <el-table-column prop="experience" label="工作经验（年）" width='150px'></el-table-column>
                <el-table-column prop="duty" label="工作职业，证书，职责"></el-table-column>
                <el-table-column label="操作" width='320px'>
                    <template slot-scope="scope">
                        <el-row>
                            <el-col :span='6'>
                                <el-button type='primary' size="small" @click="setPersonnel(scope.$index)">编辑</el-button>
                            </el-col>
                            <el-col :span='6'>
                                <el-button type='danger' size="small" @click="deletePersonnel(scope.$index)">删除</el-button>
                            </el-col>
                        </el-row>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>
        <el-dialog :visible.sync="newFixShow" class="dialoFooter" style="margin:0 auto;">
            <el-row>
                <el-col>
                    人员名称
                </el-col>
                <el-col>
                    <el-input placeholder="请输入人员名称" v-model="dialogText.name"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    照片
                </el-col>
                <el-col>
                    <el-upload style="width:100px;height:100px;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarSuccess">
                        <img v-if="dialogText.photo" :src="dialogText.photo" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    经验
                </el-col>
                <el-col>
                    <el-input placeholder="输入年限" v-model="dialogText.experience"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    职业 或 证书名 或 职责
                </el-col>
                <el-col>
                    <el-input placeholder="请输入工作职业 或 证书名 或 职责" v-model="dialogText.duty"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    希望薪资
                </el-col>
                <el-col>
                    <el-input placeholder="请输入希望薪资" v-model="dialogText.salary"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    类别
                </el-col>
                <el-col :span='24'>
                    <el-select style="width:100%;" multiple v-model="dialogText.careers" placeholder="请选择">
                        <el-option v-for="item in personnelType" :key="item.id" :label="item.career" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    是否任职
                </el-col>
                <el-col>
                    <el-switch v-model="dialogText.status" active-value="0" inactive-value="1" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    评分
                </el-col>
                <el-col>
                    <el-rate v-model="dialogText.rank"></el-rate>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    评价
                </el-col>
                <el-col>
                    <el-input v-model="dialogText.evaluate"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    简历/简介
                </el-col>
                <el-col style="height:400px;">
                    <quill-editor style="height:300px;" ref="myTextEditor" :content="dialogText.resume" :config="editorOption" @change="onEditorChange($event)">
                    </quill-editor>
                </el-col>
            </el-row>
            <el-row style="margin-top:10px">
                <el-col>
                    <el-button size="small" type='primary' style="display:block;margin:0 auto;" @click="isNewPersonnel">提交</el-button>
                </el-col>
            </el-row>
        </el-dialog>
        <el-row>
            <el-pagination layout="prev, pager, next" :current-page.sync='page' @current-change='sizeChange(page)' :page-size='pageSize' :total="allPage"></el-pagination>
        </el-row>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import { quillEditor } from 'vue-quill-editor'
export default {
    data() {
        return {
            content: '',
            editorOption: {
            // something config
            },
            allPage: "",
            page: 1,
            pageSize: 20,
            isNew: false,
            personnelList: [],
            personnelType: [],
            breadlist: "",
            newFixShow: false,
            newTypeName: "",
            careersSelect: "",
            dialogText: {
                id: "",
                name: "",
                experience: "",
                duty: "",
                photo: "",
                salary: "", //期望薪资
                careers: [],
                resume: "",
                status: "",
                rank: "",
                evaluate: ""
            },
            removePhoto:''
        };
    },
    computed: {
        headers() {
            return {
                token: localStorage.token
            };
        },
        statusTobool() {
            if (this.dialogText.status == 1) {
                return true;
            } else {
                return false;
            }
        }
    },
    methods: {
        onEditorChange({ editor, html, text }) {
            this.content = html;
        },
        sizeChange(index) {
            this.getPersonnelList(index);
        },
        isNewPersonnel() {
            if (this.isNew) {
                this.newPersonnel();
            } else {
                this.updataPersonnel();
            }
        },
        handleAvatarSuccess(response, file, fileList) {
            axios.post("/qiniuDelete", { url: this.removePhoto }).then(res => {
                this.dialogText.photo = response.url
            });
            console.log(this.removePhoto);
            
        },
        clearDialogText() {
            this.dialogText = {
                name: "",
                experience: "",
                duty: "",
                photo: "",
                salary: "", //期望薪资
                careers: [],
                resume: "",
                status: "",
                rank: "",
                evaluate: ""
            };
        },
        openNewDialog() {
            this.clearDialogText();
            this.newFixShow = true;
            this.isNew = true;
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
        getPersonnelList(page) {
            this.personnelList = [];
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/displays/applicants?page=" +
                        page
                )
                .then(res => {
                    this.personnelList = res.data.data;
                    this.allPage = res.data.total;
                });
        },
        getPersonnelType() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/displays/careers"
                )
                .then(res => {
                    this.personnelType = res.data.data.data;
                });
        },
        newPersonnel() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/displays/applicants",
                    {
                        name: this.dialogText.name,
                        experience: this.dialogText.experience,
                        duty: this.dialogText.duty,
                        photo: this.dialogText.photo,
                        salary: this.dialogText.salary,
                        careers: this.dialogText.careers,
                        resume: '',
                        status: this.dialogText.status,
                        rank: this.dialogText.rank,
                        evaluate: this.dialogText.evaluate
                    }
                )
                .then(res => {
                    this.newFixShow = false;
                    this.personnelList = res.data.data.data;
                });
        },
        setPersonnel(index) {
            this.dialogText.id = this.personnelList[index].id;
            this.dialogText.name = this.personnelList[index].name;
            this.dialogText.experience = this.personnelList[index].experience;
            this.dialogText.duty = this.personnelList[index].duty;
            this.dialogText.photo = this.personnelList[index].photo;
            this.removePhoto = this.personnelList[index].photo;
            console.log(this.personnelList[index].photo);
            

            this.dialogText.salary = this.personnelList[index].salary;
            this.dialogText.resume = this.personnelList[index].resume;
            this.dialogText.status = this.personnelList[index].status;
            this.dialogText.rank = this.personnelList[index].rank;
            this.dialogText.evaluate = this.personnelList[index].evaluate;
            this.isNew = false;

            this.dialogText.careers = [];
            for (let i = 0; i < this.personnelList[index].career.length; i++) {
                this.dialogText.careers.push(
                    this.personnelList[index].career[i].id
                );
            }
            this.newFixShow = true;
            this.$refs.myTextEditor.quill.enable(false);
        },
        updataPersonnel() {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/displays/applicants/" +
                        this.dialogText.id,
                    {
                        name: this.dialogText.name,
                        experience: this.dialogText.experience,
                        duty: this.dialogText.duty,
                        photo: this.dialogText.photo,
                        salary: this.dialogText.salary,
                        careers: this.dialogText.careers,
                        resume: this.dialogText.resume,
                        status: this.dialogText.status,
                        rank: this.dialogText.rank,
                        evaluate: this.dialogText.evaluate
                    }
                )
                .then(res => {
                    this.getPersonnelList(this.page);
                    this.newFixShow = false;
                });
        },
        deletePersonnel(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/displays/applicants/" +
                        this.personnelList[index].id
                )
                .then(res => {
                    this.getPersonnelList(this.page);
                });
        }
    },
    watch: {
        $route() {
            this.getBread();
        }
    },
    mounted() {
        this.getBread();
        this.getPersonnelType();
        this.getPersonnelList(this.page);
    }
};
</script>

<style scoped>
.el-main {
    background: #fff;
    margin-top: 20px;
    border-radius: 10px;
    margin-left: 10px;
    margin-right: 10px;
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

.dialogFooter .el-dialog {
    width: 950px;
    margin-top: 7% !important;
    height: 80% !important;
    overflow: auto;
}
</style>