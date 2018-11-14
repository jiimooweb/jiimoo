<template>
    <el-main>
        <el-row v-show='votesType === 0'>
            <el-button style="margin-bottom:20px;" type='primary' @click="openAddDialog()" size="small">新增</el-button>
            <el-table :data='votesList' border style="min-width:1200px;">
                <el-table-column prop="id" label="投票编号" width="50" align='center'></el-table-column>
                <el-table-column label="投票状态" width="100" align="center">
                    <template slot-scope="scope">
                        <p v-if='votesList[scope.$index].vote_state === 0'>
                            <el-tag color='#409EFF' style="color:#fff;">未开始</el-tag>
                        </p>
                        <p v-if='votesList[scope.$index].vote_state === 1'>
                            <el-tag color='#26CD4A' style="color:#fff;" type="success">已开始</el-tag>
                        </p>
                        <p v-if='votesList[scope.$index].vote_state === -1'>
                            <el-tag color="#BBBBBB" style="color:#fff;" type="info">已结束</el-tag>
                        </p>
                    </template>
                </el-table-column>
                <el-table-column prop="title" label="投票标题"></el-table-column>
                <el-table-column label="投票类型" width="100" align="center">
                    <template slot-scope="scope">
                        <p v-if='votesList[scope.$index].type === 1'>一般</p>
                        <p v-else>活动</p>
                    </template>
                </el-table-column>
                <el-table-column prop="vote_start_date" label="投票开始时间" width="200" align="center"></el-table-column>
                <el-table-column prop="vote_due_date" label="投票结束时间" width="200" align="center"></el-table-column>
                <el-table-column label="操作" width="220" align="center">
                    <template slot-scope="scope">
                        <el-row>
                            <el-col>
                                <el-button-group style="width:100%;">
                                    <el-button type="success" size="small" @click="intoPlayers(scope.$index)">选手/选项</el-button>
                                    <el-button type="primary" size="small" @click="intoDialog(scope.$index)">编辑</el-button>
                                    <el-button type='danger' size="small" @click="getRemoveIndex(scope.$index)">删除</el-button>
                                </el-button-group>
                            </el-col>
                        </el-row>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>

        <!-- 一般选项列表 -->
        <el-row v-show='votesType === 1'>
            <!-- <p>一般选项列表</p> -->
            <el-button style="margin-bottom:20px;" @click="intoVotesList()" size="small">
                <<返回</el-button>
                    <p>投票标题:{{votesGeneralData.title}}</p>
                    <p>投票类型:{{votesGeneralData.type}}</p>
                    <el-button style="margin-bottom:20px;" @click="addGeneralItem(1)" type="primary" size="small">新增</el-button>
                    <el-table :data='votesGeneralList' border style="width:800px;">
                        <el-table-column label="选项内容">
                            <template slot-scope="scope">
                                <el-input v-model="votesGeneralList[scope.$index].content"></el-input>
                            </template>
                        </el-table-column>
                        <el-table-column label="选项票数">
                            <template slot-scope="scope">
                                <el-input-number v-model="votesGeneralList[scope.$index].total" style="width:100%;" :min="0"></el-input-number>
                            </template>
                        </el-table-column>
                        <el-table-column label="选项票数" width="80px">
                            <template slot-scope="scope">
                                <el-button type="danger" size="small" @click="removeGeneralItem(scope.$index)">删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-button @click="editGeneralList()" type="primary" size="small" style="margin:20px 0 0 0;display:block;">提交</el-button>
        </el-row>
        <!-- 活动选手列表 -->
        <el-row v-show='votesType === 2'>
            <!-- <p>活动选手列表</p> -->
            <el-button style="margin-bottom:20px;" @click="intoVotesList()" size="small">
                <<返回</el-button>
                    <p>投票标题 : {{votesGeneralData.title}}</p>
                    <p>投票类型 : {{votesGeneralData.type}}</p>
                    <el-button style="margin-bottom:20px;" @click="addGeneralItem(0)" type="primary" size="small">新增</el-button>
                    <el-table :data='votesPlayersList' border style="min-width:1200px;">
                        <el-table-column prop="name" label="姓名">
                        </el-table-column>
                        <el-table-column label="图片" width="103px">
                            <template slot-scope="scope">
                                <img :src="votesPlayersList[scope.$index].image" width='80px' height='80px'>
                            </template>
                        </el-table-column>
                        <el-table-column prop="phone" label="联系方式">
                        </el-table-column>
                        <el-table-column prop="address" label="地址">
                        </el-table-column>
                        <!-- <el-table-column prop="description" label="描述">
                        </el-table-column> -->
                        <el-table-column prop="total" label="票数">
                        </el-table-column>
                        <el-table-column prop="is_pass" label="是否审核">
                            <template slot-scope="scope">
                                <el-switch @change='changePlayerSwitch(scope.$index)' v-model="votesPlayersList[scope.$index].is_pass" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
                            </template>
                        </el-table-column>
                        <el-table-column label="操作" width='160px'>
                            <template slot-scope="scope">
                                <el-button @click="openEditPlayer(scope.$index)" type="primary" size="small">编辑</el-button>
                                <el-button @click="removePlayer(scope.$index)" type="danger" size="small">删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
        </el-row>

        <!-- 选项投票信息dialog -->
        <el-dialog :visible.sync='GeneralDialog' :title="dialogTitle" width="950px" :close-on-click-modal='false' :close-on-press-escape='false'>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    投票标题
                </el-col>
                <el-col :span="19">
                    <el-input v-model="GeneralData.title"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    投票类型
                </el-col>
                <el-col :span="19">
                    <template>
                        <el-radio v-model="GeneralData.type" :label="1">一般</el-radio>
                        <el-radio v-model="GeneralData.type" :label="0">活动</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    投票时间
                </el-col>
                <el-col :span="5">
                    <!-- <el-date-picker style="width:100%;" :picker-options="pickerOptions" v-model="voteTime" format="yyyy-MM-dd" type="daterange" @change="changeVoteTime()" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
                    </el-date-picker> -->
                    <el-date-picker @change="changeVoteTime()" style="width:100%;" :picker-options="pickerOptionsa" v-model="voteTime[0]" type="date" format="yyyy-MM-dd" placeholder="开始日期"></el-date-picker>
                    
                </el-col>
                <el-col :span="5">
                    <el-time-select @change="changeVoteTime()" align='center' style="width:90%;display:block;float:right;" placeholder="起始时间" format='HH:mm' v-model="startTime1" :picker-options="{start: '00:00',step: '00:03',end: '24:00'}"></el-time-select>
                </el-col>
                <el-col :offset="5" :span="5">
                    <el-date-picker @change="changeVoteTime()" style="width:100%;" :picker-options="pickerOptionsb" v-model="voteTime[1]" type="date" format="yyyy-MM-dd" placeholder="结束日期"></el-date-picker>
                </el-col>
                <el-col :span="5">
                    <el-time-select @change="changeVoteTime()" align='center' style="width:90%;display:block;float:right;" placeholder="结束时间" v-model="endTime1" :picker-options="{start: '00:00',step: '00:03',end: '24:00'}"></el-time-select>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    投票周期
                </el-col>
                <el-col :span="19">
                    <template>
                        <el-radio v-model="GeneralData.cycle" :label="0">活动时间可投票数唯一</el-radio>
                        <el-radio v-model="GeneralData.cycle" :label="1">每天恢复可投票数</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    可投票数
                </el-col>
                <el-col :span="19">
                    <el-input-number v-model="GeneralData.num" :min="1"></el-input-number>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    可投上限（每次投票）
                </el-col>
                <el-col :span="19">
                    <el-input-number v-model="GeneralData.limit" :min="1" :max="GeneralData.num||1"></el-input-number>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;" v-if='GeneralData.type === 0'>
                <el-col :span="5" style="text-align:center;">
                    允许报名
                </el-col>
                <el-col :span="19">
                    <template>
                        <el-radio v-model="GeneralData.is_apply" :label="1">是</el-radio>
                        <el-radio v-model="GeneralData.is_apply" :label="0">否</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;" v-if='GeneralData.type === 0 && GeneralData.is_apply === 1'>
                <el-col :span="5" style="text-align:center;">
                    报名时间
                </el-col>
                <!-- <el-col :span="10">
                    <el-date-picker style="width:100%;" :picker-options="pickerOptions1" v-model="applyTime" format="yyyy-MM-dd" type="daterange" @change="changeApplyTime()" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期">
                    </el-date-picker>
                </el-col>
                <el-col :offset="5" :span="5">
                    <el-time-select @change="changeApplyTime()" align='center' style="width:90%;display:block;" placeholder="起始时间" format='HH:mm' v-model="startTime2" :picker-options="{start: '00:00',step: '00:30',end: '24:00'}"></el-time-select>
                </el-col>
                <el-col :span="5">
                    <el-time-select @change="changeApplyTime()" align='center' style="width:90%;display:block;float:right;" placeholder="结束时间" v-model="endTime2" :picker-options="{start: '00:00',step: '00:30',end: '24:00'}"></el-time-select>
                </el-col> -->

                <el-col :span="5">
                    <el-date-picker @change="changeApplyTime()" style="width:100%;" :picker-options="pickerOptionsc" v-model="applyTime[0]" type="date" format="yyyy-MM-dd" placeholder="开始日期"></el-date-picker>
                </el-col>
                <el-col :span="5">
                    <el-time-select @change="changeApplyTime()" align='center' style="width:90%;display:block;float:right;" placeholder="起始时间" format='HH:mm' v-model="startTime2" :picker-options="{start: '00:00',step: '00:03',end: '24:00'}"></el-time-select>
                </el-col>
                <el-col :offset="5" :span="5">
                    <el-date-picker @change="changeApplyTime()" style="width:100%;" :picker-options="pickerOptionsd" v-model="applyTime[1]" type="date" format="yyyy-MM-dd" placeholder="结束日期"></el-date-picker>
                </el-col>
                <el-col :span="5">
                    <el-time-select @change="changeApplyTime()" align='center' style="width:90%;display:block;float:right;" placeholder="结束时间" v-model="endTime2" :picker-options="{start: '00:00',step: '00:03',end: '24:00'}"></el-time-select>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;" v-if='GeneralData.type === 0 && GeneralData.is_apply === 1'>
                <el-col :span="5" style="text-align:center;">
                    选手审核
                </el-col>
                <el-col :span="19">
                    <template>
                        <el-radio v-model="GeneralData.is_check" :label="1">是</el-radio>
                        <el-radio v-model="GeneralData.is_check" :label="0">否</el-radio>
                    </template>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    投票图片
                </el-col>
                <el-col :span="19">
                    <el-upload action="/qiniuUpload" :file-list="albumList" list-type="picture-card" :limit="3" :onSuccess="uploadSuccessAlbum" :headers="headers" :on-remove="handleRemoveAlbum">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="5" style="text-align:center;">
                    投票描述
                </el-col>
                <el-col :span="19">
                    <quill-editor style="height:400px;" ref="myTextEditor" :content="GeneralData.description" @change="onEditorChange1($event)">
                    </quill-editor>
                </el-col>
            </el-row>
            <el-button @click="editGeneralData()" type="primary" size="small" style="margin:100px auto 0;display:block;">提交</el-button>
        </el-dialog>

        <!-- 选项信息dialog1 -->
        <el-dialog :visible.sync='PlayersDialog1' :close-on-click-modal='false' :close-on-press-escape='false'>
        </el-dialog>
        <!-- 选手信息dialog2 -->
        <el-dialog :visible.sync='PlayersDialog2' :close-on-click-modal='false' :close-on-press-escape='false'>
            <el-row style="margin:20px 0 0;">
                <el-col :span='3'>
                    名字
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="PlayersData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin:20px 0 0;">
                <el-col :span='3'>
                    图片
                </el-col>
                <el-col :span='20'>
                    <el-upload style="margin:0 auto;display:block;" class="avatar-uploader" :headers="headers" action="/qiniuUpload" :show-file-list="false" :on-success="handleAvatarSuccess">
                        <img v-if="PlayersData.image" :src="PlayersData.image" class="avatar" width="100" height='100'>
                        <i v-else class="el-icon-plus avatar-uploader-icon" style="margin:0 auto;display:block;"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin:20px 0 0;">
                <el-col :span='3'>
                    联系方式
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="PlayersData.phone"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin:20px 0 0;">
                <el-col :span='3'>
                    地址
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="PlayersData.address"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin:20px 0 0;">
                <el-col :span='3'>
                    票数
                </el-col>
                <el-col :span='20'>
                    <el-input-number v-model="PlayersData.total" :min="0"></el-input-number>
                </el-col>
            </el-row>
            <el-row style="margin:20px 0 0;" v-if="isNewPlayer">
                <el-col :span='3'>
                    是否通过审核
                </el-col>
                <el-col :span='20'>
                    <el-switch v-model="PlayersData.is_pass" active-color="#13ce66" inactive-color="#ff4949" :active-value="1" :inactive-value="0">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row style="margin:20px 0 0;">
                <el-col :span='3'>
                    描述
                </el-col>
                <el-col :span='20'>
                    <quill-editor style="height:280px;" ref="myTextEditor" :content="PlayersData.description" @change="onEditorChange($event)">
                    </quill-editor>
                </el-col>
            </el-row>

            <el-row style="margin-top:90px;">
                <el-button @click="editPlayersList()" type="primary" size="small" style="margin:0 auto 0;display:block;">提交</el-button>
            </el-row>
        </el-dialog>
        <!-- removeVotesDialog -->
        <el-dialog :visible.sync='removeVotesDialog' width="400px">
            <el-row>
                <p style="text-align:center;">是否确认删除投票</p>
                <!-- <p style="text-align:center;color:red;">{{votesList.length === 0?'':votesList[removeIndex].title}}</p> -->
            </el-row>
            <el-row>

                <el-button style='float:right' @click="removeVotesDialog = false" size="small">取消</el-button>
                <el-button style='float:right;margin-right:10px;' @click="removeVotes(removeIndex)" type="primary" size="small">确定</el-button>
            </el-row>
        </el-dialog>
    </el-main>

</template>
<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import { quillEditor } from "vue-quill-editor";
export default {
    data() {
        return {
            startTime1: "00:00",
            endTime1: "00:00",
            startTime2: "00:00",
            endTime2: "00:00",
            votesList: [],
            votesType: 0, //0 列表状态，1 一般选项列表，2 活动选手列表
            dialogTitle: "",
            isNewVotes: false,
            applyIsTrue: false,

            albumList:[],
            voteImage:[],
            removeVotesDialog: false,
            removeIndex: "",
            //一般选项列表
            voteTime: [],
            applyTime: [],
            thisGeneralIndex: "",
            GeneralDialog: false,
            GeneralData: "",
            votesGeneralList: [],
            votesGeneralData: {
                title: "1",
                type: "1",
                id: ""
            },
            //活动选手列表
            isNewPlayer: false,
            thisPlayersIndex: "",
            PlayersDialog1: false,
            PlayersDialog2: false,
            PlayersData: {
                name: "",
                image: "",
                phone: "",
                address: "",
                total: 0,
                is_pass: 0,
                description: ""
            },
            votesPlayersList: [],
            pickerOptionsa:{
                disabledDate: time => {
                    return Date.now() - 8.64e7 > time.getTime() || Date.parse(new Date(this.voteTime[1])) < time.getTime();
                }
            },
            pickerOptionsb:{
                disabledDate: time => {
                    return Date.parse(new Date(this.voteTime[0])) > time.getTime() || Date.now() - 8.64e7 > time.getTime();
                }
            },
            pickerOptionsc:{
                disabledDate: time => {
                    return Date.parse(new Date(this.voteTime[1])) < time.getTime() || Date.parse(new Date(this.voteTime[0])) > time.getTime() || Date.now() - 8.64e7 > time.getTime() || Date.parse(new Date(this.applyTime[1])) < time.getTime();
                }
            },
            pickerOptionsd:{
                disabledDate: time => {
                    return Date.parse(new Date(this.voteTime[1])) < time.getTime() || Date.parse(new Date(this.applyTime[0])) > time.getTime() || Date.now() - 8.64e7 > time.getTime();
                }
            },
            pickerOptions1: {
                disabledDate: time => {
                    return (
                        time.getTime() < this.voteTime[0] ||
                        time.getTime() > this.voteTime[1]
                    );
                }
            }
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
        //图片事件
        uploadSuccessAlbum(response) {
            this.albumList.push({ name: "未命名", url: response.url });
            this.voteImage.push(response.url);
            console.log(this.voteImage);
            
        },
        handleRemoveAlbum(file) {
            axios.post("/qiniuDelete", { url: file.url }).then(res => {
                this.voteImage.images = [];
                for(let i=0;i<this.albumList.length;i++){
                    if(this.albumList[i].uid === file.uid){
                        this.albumList.splice(i,1)
                    }
                }
                for (let i = 0; i < this.albumList.length; i++) {
                    this.voteImage.images.push(this.albumList[i].url);
                }
            });
        },
        getRemoveIndex(index) {
            this.removeIndex = index;
            this.removeVotesDialog = true;
        },
        //
        onEditorChange1({ editor, html, text }) {
            this.GeneralData.description = html;
        },
        onEditorChange({ editor, html, text }) {
            this.PlayersData.description = html;
        },
        changePlayerSwitch(index) {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/votes/applicants/audited",
                    {
                        vote_id: this.votesGeneralData.id,
                        applicant_id: this.votesPlayersList[index].id,
                        is_pass: this.votesPlayersList[index].is_pass
                    }
                )
                .then(res => {
                    axios
                        .post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/votes/" +
                                this.votesGeneralData.id +
                                "/applicants"
                        )
                        .then(res => {
                            this.votesPlayersList = res.data.data.all;
                        });
                });
        },
        //获取所有投票
        getVotesList() {
            axios
                .get(
                    "/web/" + store.state.xcx_flag.xcx_flag + "/api/votes/infos"
                )
                .then(res => {
                    this.votesList = res.data.data.data;
                    console.log(this.votesList);
                });
        },
        //判断并进入对应的选手列表
        intoPlayers(index) {
            if (this.votesList[index].type === 1) {
                //进入一般选项页面
                this.votesType = 1;
                this.votesGeneralData.title = this.votesList[index].title;
                this.votesGeneralData.type = "一般";
                this.votesGeneralData.id = this.votesList[index].id;
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/votes/" +
                            this.votesList[index].id +
                            "/options"
                    )
                    .then(res => {
                        this.votesGeneralList = res.data.data;
                    });
            } else {
                //进入活动选首页面
                this.votesType = 2;
                this.votesGeneralData.title = this.votesList[index].title;
                this.votesGeneralData.type = "活动";
                this.votesGeneralData.id = this.votesList[index].id;
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/votes/" +
                            this.votesList[index].id +
                            "/applicants"
                    )
                    .then(res => {
                        this.votesPlayersList = res.data.data.all;
                    });
            }
        },
        //选手列表返回votes列表
        intoVotesList() {
            this.votesType = 0;
            this.votesGeneralList = [];
            this.votesPlayersList = [];
            this.getVotesList();
        },
        //新建投票
        openAddDialog() {
            this.GeneralData = {
                type: 0,
                title: "",
                description: "",
                vote_start_date: "",
                vote_due_date: "",
                cycle: 1,
                num: "",
                limit: "",
                vote_state: "",
                is_apply: 1,
                apply_start_date: "",
                apply_due_date: "",
                is_check: 1,
                startTime1: "00:00",
                endTime1: "00:00",
                startTime2: "00:00",
                endTime2: "00:00"
            };
            this.voteTime = [];
            this.applyTime = [];
            this.GeneralDialog = true;
            this.dialogTitle = "新建投票信息";
            this.isNewVotes = true;
        },
        //进入投票编辑页面
        intoDialog(index) {
            this.dialogTitle = "编辑投票信息";
            this.isNewVotes = false;
            // console.log(this.votesList[index].image);
            // let imageList = eval("("+this.votesList[index].image+")")
            // console.log(imageList);

            this.albumList = []
            this.voteImage = []
            for(let i=0;i<this.votesList[index].image.length;i++){
                this.albumList.push({url:this.votesList[index].image[i],name:'1'})
                this.voteImage = this.votesList[index].image[i]
            }   
            
            if (this.votesList[index].type === 1) {
                this.GeneralDialog = true;
                this.GeneralData = this.votesList[index];
                if (this.votesList[index].type === null) {
                    this.votesList[index].type = 0;
                }
                if (this.votesList[index].cycle === null) {
                    this.votesList[index].cycle = 1;
                }
                if (this.votesList[index].is_apply === null) {
                    this.votesList[index].is_apply = 1;
                }
                if (this.votesList[index].is_check === null) {
                    this.votesList[index].is_check = 1;
                }
                this.voteTime = [
                    this.votesList[index].vote_start_date,
                    this.votesList[index].vote_due_date
                ];
                this.startTime1 = this.formatDate(
                    this.votesList[index].vote_start_date,
                    "hh:mm"
                );
                this.endTime1 = this.formatDate(
                    this.votesList[index].vote_due_date,
                    "hh:mm"
                );
            } else {
                this.GeneralDialog = true;
                this.GeneralData = this.votesList[index];
                if (this.votesList[index].type === null) {
                    this.votesList[index].type = 0;
                }
                if (this.votesList[index].cycle === null) {
                    this.votesList[index].cycle = 1;
                }
                if (this.votesList[index].is_apply === null) {
                    this.votesList[index].is_apply = 1;
                }
                if (this.votesList[index].is_check === null) {
                    this.votesList[index].is_check = 1;
                }
                this.voteTime = [
                    this.votesList[index].vote_start_date,
                    this.votesList[index].vote_due_date
                ];
                this.applyTime = [
                    this.votesList[index].apply_start_date,
                    this.votesList[index].apply_due_date
                ];
                this.startTime1 = this.formatDate(
                    this.votesList[index].vote_start_date,
                    "hh:mm"
                );
                this.endTime1 = this.formatDate(
                    this.votesList[index].vote_due_date,
                    "hh:mm"
                );
                this.startTime2 = this.formatDate(
                    this.votesList[index].apply_start_date,
                    "hh:mm"
                );
                this.endTime2 = this.formatDate(
                    this.votesList[index].apply_due_date,
                    "hh:mm"
                );
            }
        },
        //保存投票编辑信息
        editGeneralData() {
            if (this.isNewVotes) {
                if (this.GeneralData.type === 1) {
                    if (!this.isPassed()) {
                        return false;
                        console.log(123);
                    }

                    axios
                        .post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/votes/infos",
                            {
                                image:this.voteImage,
                                type: this.GeneralData.type,
                                cycle:this.GeneralData.cycle,
                                title: this.GeneralData.title,
                                description: this.GeneralData.description,
                                vote_start_date: this.formatDate(
                                    this.GeneralData.vote_start_date
                                ),
                                vote_due_date: this.formatDate(
                                    this.GeneralData.vote_due_date
                                ),
                                num: this.GeneralData.num,
                                limit: this.GeneralData.limit
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "新建成功");
                            this.GeneralDialog = false;
                            this.getVotesList();
                        });
                } else {
                    if (!this.isPassed()) {
                        return false;
                    }
                    if (!this.applyIsTrue && this.GeneralData.is_apply === 1) {
                        this.showMessage(
                            "error",
                            "报名时间不能大于活动结束时间"
                        );
                        return false;
                    }
                    axios
                        .post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/votes/infos",
                            {
                                image:this.voteImage,
                                type: this.GeneralData.type,
                                title: this.GeneralData.title,
                                cycle:this.GeneralData.cycle,
                                description: this.GeneralData.description,
                                vote_start_date: this.formatDate(
                                    this.GeneralData.vote_start_date
                                ),
                                vote_due_date: this.formatDate(
                                    this.GeneralData.vote_due_date
                                ),
                                num: this.GeneralData.num,
                                limit: this.GeneralData.limit,
                                is_apply: this.GeneralData.is_apply,
                                apply_start_date: this.formatDate(
                                    this.GeneralData.apply_start_date
                                ),
                                apply_due_date: this.formatDate(
                                    this.GeneralData.apply_due_date
                                ),
                                is_check: this.GeneralData.is_check
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "新建成功");
                            this.GeneralDialog = false;
                            this.getVotesList();
                        });
                }
            } else {
                if (this.GeneralData.type === 1) {
                    if (!this.isPassed()) {
                        return false;
                    }
                    axios
                        .put(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/votes/infos/" +
                                this.GeneralData.id,
                            {
                                image:this.voteImage,
                                type: this.GeneralData.type,
                                cycle:this.GeneralData.cycle,
                                title: this.GeneralData.title,
                                description: this.GeneralData.description,
                                vote_start_date: this.formatDate(
                                    this.GeneralData.vote_start_date
                                ),
                                vote_due_date: this.formatDate(
                                    this.GeneralData.vote_due_date
                                ),
                                num: this.GeneralData.num,
                                limit: this.GeneralData.limit
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "提交成功");
                            this.GeneralDialog = false;
                            this.getVotesList();
                        });
                } else {
                    if (!this.isPassed()) {
                        return false;
                    }
                    if (!this.applyIsTrue && this.GeneralData.is_apply === 1) {
                        this.showMessage(
                            "error",
                            "报名时间不能大于活动结束时间"
                        );
                        return false;
                    }
                    axios
                        .put(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/votes/infos/" +
                                this.GeneralData.id,
                            {
                                image:this.voteImage,
                                type: this.GeneralData.type,
                                cycle:this.GeneralData.cycle,
                                title: this.GeneralData.title,
                                description: this.GeneralData.description,
                                vote_start_date: this.formatDate(
                                    this.GeneralData.vote_start_date
                                ),
                                vote_due_date: this.formatDate(
                                    this.GeneralData.vote_due_date
                                ),
                                num: this.GeneralData.num,
                                limit: this.GeneralData.limit,
                                is_apply: this.GeneralData.is_apply,
                                apply_start_date: this.formatDate(
                                    this.GeneralData.apply_start_date
                                ),
                                apply_due_date: this.formatDate(
                                    this.GeneralData.apply_due_date
                                ),
                                is_check: this.GeneralData.is_check
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "提交成功");
                            this.GeneralDialog = false;
                            this.getVotesList();
                        });
                }
            }
        },
        //删除投票信息
        removeVotes(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/votes/infos/" +
                        this.votesList[index].id
                )
                .then(res => {
                    this.showMessage("success", "删除成功");
                    this.getVotesList();
                    this.removeVotesDialog = false;
                });
        },
        //更改时间
        changeVoteTime() {
            this.GeneralData.vote_start_date =
                this.formatDate(this.voteTime[0], "YY-MM-DD") +
                " " +
                this.startTime1;
            this.GeneralData.vote_due_date =
                this.formatDate(this.voteTime[1], "YY-MM-DD") +
                " " +
                this.endTime1;
            // console.log(Date.parse(this.formatDate(nowTime,"YY-MM-DD hh:mm")));
            // console.log(Date.parse(this.GeneralData.vote_due_date));
            console.log( Date.parse(this.GeneralData.vote_due_date) < Date.parse(this.GeneralData.vote_start_date));
            

            let nowTime = new Date();
            if (
                Date.parse(this.GeneralData.vote_start_date) <
                Date.parse(this.formatDate(nowTime, "YY-MM-DD hh:mm"))
            ) {
                this.showMessage("error", "活动时间不能小于当前时间");
                this.applyIsTrue = false;
                return false;
            } else {
                this.applyIsTrue = true;
            }
            if( Date.parse(this.GeneralData.vote_due_date) < Date.parse(this.GeneralData.vote_start_date)){
                 this.showMessage("error", "投票开始时间不能大于投票结束时间");
                this.applyIsTrue = false;
                return false;
            }
            if (this.GeneralData.apply_due_date != "") {
                if (
                    this.GeneralData.vote_due_date <
                    this.GeneralData.apply_due_date
                ) {
                    this.showMessage("error", "报名时间不能大于活动结束时间");
                    this.applyIsTrue = false;
                } else {
                    this.applyIsTrue = true;
                }

                if (
                    this.GeneralData.vote_start_date >
                    this.GeneralData.apply_start_date
                ) {
                    this.showMessage("error", "报名时间不能小于活动结束时间");
                    this.applyIsTrue = false;
                } else {
                    this.applyIsTrue = true;
                }
            }
        },
        changeApplyTime() {
            // this.applyTime[0] = this.formatDate(this.formatDate(this.applyTime[0],"YY-MM-DD")+" "+this.startTime2);
            // this.applyTime[1] = this.formatDate(this.formatDate(this.applyTime[1],"YY-MM-DD")+" "+this.endTime2);
            // this.applyTime[0] = this.formatDate(this.applyTime[0]
            // this.applyTime[1] = this.formatDate(this.applyTime[1]
            this.GeneralData.apply_start_date =
                this.formatDate(this.applyTime[0], "YY-MM-DD") +
                " " +
                this.startTime2;
            this.GeneralData.apply_due_date =
                this.formatDate(this.applyTime[1], "YY-MM-DD") +
                " " +
                this.endTime2;
            if (this.GeneralData.apply_due_date != "") {
                if (
                    this.GeneralData.vote_due_date <
                    this.GeneralData.apply_due_date
                ) {
                    this.showMessage("error", "报名时间不能大于活动结束时间");
                    this.applyIsTrue = false;
                } else {
                    this.applyIsTrue = true;
                }

                if (
                    this.GeneralData.vote_start_date >
                    this.GeneralData.apply_start_date
                ) {
                    this.showMessage("error", "报名时间不能小于活动结束时间");
                    this.applyIsTrue = false;
                } else {
                    this.applyIsTrue = true;
                }
                if( Date.parse(this.GeneralData.apply_start_date) > Date.parse(this.GeneralData.apply_due_date)){
                    this.showMessage("error", "报名开始时间不能大于报名结束时间");
                    this.applyIsTrue = false;
                    return false;
                }
            }
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        },

        //新增选项1
        addGeneralItem(i) {
            if (i === 1) {
                if (this.votesGeneralList.length == 0) {
                    this.votesGeneralList.push({
                        content: "",
                        total: 0
                    });
                } else {
                    for (let i = 0; i < this.votesGeneralList.length; i++) {
                        if (this.votesGeneralList[i].content == "") {
                            this.showMessage(
                                "error",
                                "新增选项前请填写好前面的选项内容"
                            );
                            return;
                        } else {
                            if (i == this.votesGeneralList.length - 1) {
                                this.votesGeneralList.push({
                                    content: "",
                                    total: 0
                                });
                                return;
                            }
                        }
                    }
                }
            } else {
                this.PlayersDialog2 = true;
                this.isNewPlayer = true;
                this.PlayersData = {
                    name: "",
                    image: "",
                    phone: "",
                    address: "",
                    total: 0,
                    is_pass: 0,
                    description: ""
                };
            }
        },
        //编辑选手
        openEditPlayer(index) {
            this.PlayersDialog2 = true;
            this.thisPlayersIndex = index;
            this.isNewPlayer = false;
            this.PlayersData = {
                name: this.votesPlayersList[index].name,
                image: this.votesPlayersList[index].image,
                phone: this.votesPlayersList[index].phone,
                address: this.votesPlayersList[index].address,
                total: this.votesPlayersList[index].total,
                is_pass: this.votesPlayersList[index].is_pass,
                description: this.votesPlayersList[index].description
            };
        },
        //获取选手列表
        getVotesPlayersList() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/votes/" +
                        this.votesGeneralData.id +
                        "/applicants"
                )
                .then(res => {
                    console.log(res);
                    
                    this.votesPlayersList = res.data.data.all;
                });
        },
        //保存当前选项列表
        editGeneralList() {
            for (let i = 0; i < this.votesGeneralList.length; i++) {
                if (this.votesGeneralList[i].content == "") {
                    this.showMessage("error", "选项内容不能为空");
                    return false;
                }
            }
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/votes/options",
                    {
                        vote_id: this.votesGeneralData.id,
                        options: this.votesGeneralList
                    }
                )
                .then(res => {
                    this.showMessage("success", "保存成功");
                    axios
                        .post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/votes/" +
                                this.votesGeneralData.id +
                                "/options"
                        )
                        .then(res => {
                            this.votesGeneralList = res.data.data;
                        });
                });
        },
        editPlayersList() {
            for (let item in this.PlayersData) {
                if (
                    this.PlayersData[item] === "" ||
                    this.PlayersData[item] === undefined
                ) {
                    this.showMessage("error", "选手资料不完整");
                    return false;
                }
            }
            if (this.isNewPlayer) {
                //新增
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/votes/applicants",
                        {
                            vote_id: this.votesGeneralData.id,
                            name: this.PlayersData.name,
                            phone: this.PlayersData.phone,
                            address: this.PlayersData.address,
                            image: this.PlayersData.image,
                            description: this.PlayersData.description,
                            total: this.PlayersData.total,
                            is_pass: this.PlayersData.is_pass
                        }
                    )
                    .then(res => {
                        this.PlayersDialog2 = false;
                        this.getVotesPlayersList();
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/votes/applicants/" +
                            this.votesPlayersList[this.thisPlayersIndex].id,
                        {
                            vote_id: this.votesGeneralData.id,
                            name: this.PlayersData.name,
                            phone: this.PlayersData.phone,
                            address: this.PlayersData.address,
                            image: this.PlayersData.image,
                            description: this.PlayersData.description,
                            total: this.PlayersData.total,
                            is_pass: this.PlayersData.is_pass
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "编辑成功");
                        this.getVotesPlayersList();
                        this.PlayersDialog2 = false;
                    });
            }
        },
        //删除当前选项
        removeGeneralItem(index) {
            this.votesGeneralList.splice(index, 1);
        },
        //删除当前选手
        removePlayer(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/votes/applicants/" +
                        this.votesPlayersList[index].id
                )
                .then(res => {
                    this.showMessage("success", "删除成功");
                    this.getVotesPlayersList();
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
        //通用表单验证
        isPassed() {
            if (this.GeneralData.title == "") {
                this.showMessage("error", "标题不能为空");
                return false;
            }
            if (this.GeneralData.description == "") {
                this.showMessage("error", "描述不能为空");
                return false;
            }
            if (this.GeneralData.vote_start_date == "") {
                if (
                    this.GeneralData.apply_start_date == "" &&
                    this.GeneralData.type === 0
                ) {
                    this.showMessage("error", "时间不能为空");
                }
                this.showMessage("error", "时间不能为空");
                return false;
            }
            // console.log(Date.parse(this.formatDate(nowTime,"YY-MM-DD hh:mm")));
            // console.log(Date.parse(this.GeneralData.vote_start_date));
            if (this.isNewVotes) {
                let nowTime = new Date();
                if (
                    Date.parse(this.GeneralData.vote_start_date) <
                    Date.parse(this.formatDate(nowTime, "YY-MM-DD hh:mm"))
                ) {
                    this.showMessage("error", "报名时间不能小于当前");
                    this.applyIsTrue = false;
                    return false;
                } else {
                    return true;
                }
            }

            return true;
        },
        //上传图片
        handleAvatarSuccess(response, file, fileList) {
            axios
                .post("/qiniuDelete", {
                    url: this.PlayersData.image
                })
                .then(res => {
                    this.PlayersData.image = response.url;
                });
        }
    },
    mounted() {
        this.getVotesList();
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
    width: 80px;
    height: 80px;
    line-height: 80px;
    text-align: center;
    margin: 0 auto;
}
.avatar {
    margin: 0 auto;
    width: 80px;
    height: 80px;
    display: block;
}
</style>