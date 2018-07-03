<template>
    <el-main>
        <el-tabs type="border-card" @tab-click="handletabClick">
            <el-tab-pane label="会员列表">
                <!-- <el-row style="margin-bottom:20px;">
                    <el-col>
                        <el-button type="primary" @click="openAddDialog()">添加</el-button>
                    </el-col>
                </el-row> -->
                <el-row>
                    <el-table :data='vipList' border style="min-width:1000px;">
                        <el-table-column prop="fan_id" label="ID" width='50px'></el-table-column>
                        <el-table-column label="等级" width='200px'>
                            <template slot-scope="scope">
                                <el-select v-model="vipList[scope.$index].group_id" placeholder="请选择" @change="changeVipGroup(scope.$index)">
                                    <el-option v-for="item in vipGroup" :key="item.id" :label="item.name" :value="item.id">
                                    </el-option>
                                </el-select>
                            </template>
                        </el-table-column>
                        <el-table-column prop="name" label="用户名" width='100px'></el-table-column>
                        <el-table-column label="标签" width='200px'>
                            <template slot-scope="scope">
                                <el-tag type="success" style="margin-right:2px;" v-for="(item,index) in vipList[scope.$index].tags" :key='index'>{{item.name}}</el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="card_id" label="会员卡号"></el-table-column>
                        <el-table-column prop="mobile" label="手机号" width='200px'></el-table-column>
                        <el-table-column prop="integral" label="可用积分" width='100px'></el-table-column>
                        <el-table-column prop="integral_total" label="总积分" width='100px'></el-table-column>
                        <el-table-column prop="money" label="余额" width='100px'></el-table-column>
                        <el-table-column width=250>
                            <template slot-scope="scope">
                                <el-row>
                                    <el-col>
                                        <el-button-group style="width:100%;">
                                            <el-button type="primary" size="small" @click="openEditDialog(scope.$index)">编辑</el-button>
                                            <el-button type="primary" size="small" @click="openIntegralDialog(scope.$index)">积分</el-button>
                                            <el-button type="primary" size="small" @click="openMoneyDialog(scope.$index)">余额</el-button>
                                            <el-button type="danger" size="small" @click="removeVip(scope.$index)">删除</el-button>
                                        </el-button-group>
                                    </el-col>
                                </el-row>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-row>
            </el-tab-pane>
            <el-tab-pane label="会员等级">
                <el-button type="primary" size="small" style="margin-bottom:20px;" @click="newGroupDialog()">新增</el-button>
                <el-table :data='vipGroup' border>
                    <el-table-column prop="name" label="等级名称"></el-table-column>
                    <el-table-column prop="value" label="积分"></el-table-column>
                    <el-table-column label="默认">
                        <template slot-scope="scope">
                            <el-switch :active-value="1" :inactive-value="0" v-model="vipGroup[scope.$index].default" @change="changeGroupSwitch(scope.$index)" active-color="#13ce66" inactive-color="#ff4949">
                            </el-switch>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-row>
                                <el-col :span='10'>
                                    <el-button size="small" @click="openGroupDialog(scope.$index)" type="primary">编辑</el-button>
                                </el-col>
                                <el-col :span='10'>
                                    <el-button size="small" @click="removeVipGroup(scope.$index)" type="danger">删除</el-button>
                                </el-col>
                            </el-row>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tab-pane>
            <el-tab-pane label="会员规则">
                <el-row style="margin-bottom:30px;border-bottom:1px solid #eee;padding-bottom:30px;">
                    <el-col :span="4">
                        启用会员设置
                    </el-col>
                    <el-col :span="10">
                        <el-switch v-model="vipSet.status" @change='vipStatusChange()' :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                        </el-switch>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:30px;border-bottom:1px solid #eee;padding-bottom:30px;">
                    <el-col :span="4" style="line-height:40px;">
                        会员卡消费积分比例
                    </el-col>
                    <el-col :span="9">
                        <el-input :disabled='!vipSet.status' v-model="vipSet.scale"></el-input>
                    </el-col>
                    <el-col :span="13">
                        <tip slass="primary" value="每消费多少元能得1积分"></tip>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:30px;border-bottom:1px solid #eee;padding-bottom:30px;">
                    <el-col :span="4">
                        会员等级优惠状态
                    </el-col>
                    <el-col :span="10">
                        <template>
                            <el-radio :disabled='!vipSet.status' v-model="vipSet.offer_status" :label="0">不开启</el-radio>
                            <el-radio :disabled='!vipSet.status' v-model="vipSet.offer_status" :label="1">满减</el-radio>
                            <el-radio :disabled='!vipSet.status' v-model="vipSet.offer_status" :label="2">折扣</el-radio>
                        </template>
                    </el-col>
                    <el-col :span="13">
                        <tip slass="primary" value="选择会员优惠方式"></tip>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:30px;border-bottom:1px solid #eee;padding-bottom:30px;">
                    <el-col :span="4">
                        结账是否自动启用优惠
                    </el-col>
                    <el-col :span="10">
                        <el-switch :disabled='!vipSet.status' v-model="vipSet.auto_status" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                        </el-switch>
                    </el-col>
                </el-row>
                <el-row style="margin-bottom:30px;border-bottom:1px solid #eee;padding-bottom:30px;">
                    <el-col :span="4">
                        会员等级对应折扣
                    </el-col>
                    <el-col>
                        <!-- <el-button :disabled='!vipSet.status' style="margin-top:20px;margin-bottom:20px;" @click="isTableOne()" type='primary' size='small'>新增</el-button> -->
                    </el-col>
                    <el-col :span="13" style="margin-top:30px;border-bottom:1px solid #eee;padding-bottom:30px;">
                        <el-table border v-show='vipSet.offer_status==2' :data='offerTable0'>
                            <el-table-column label="等级">
                                <template slot-scope="scope">
                                    <el-input readonly :disabled='!vipSet.status' v-model="offerGroup[scope.$index].name"></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="满减条件">
                                <template slot-scope="scope">
                                    <el-input :disabled='!vipSet.status' v-model="offerTable0[scope.$index].full">
                                        <template slot="append">元</template>
                                    </el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="满减金额">
                                <template slot-scope="scope">
                                    <el-input :disabled='!vipSet.status' v-model="offerTable0[scope.$index].reduction">
                                        <template slot="append">元</template>
                                    </el-input>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-table border v-show='vipSet.offer_status==1' :data='offerTable1'>
                            <el-table-column label="等级">
                                <template slot-scope="scope">
                                    <el-input readonly :disabled='!vipSet.status' v-model="offerGroup[scope.$index].name"></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column label="折扣">
                                <template slot-scope="scope">
                                    <el-input :disabled='!vipSet.status' v-model="offerTable1[scope.$index].discount">
                                        <template slot="append">%</template>
                                    </el-input>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-col>
                </el-row>
                <el-button :disabled='!vipSet.status' style="margin-top:20px;" @click='inputVipSet()' type='primary'>提交</el-button>
            </el-tab-pane>
            <el-tab-pane label="会员标签">
                <el-button style="display:block;margin-bottom:20px;" type="primary" size="small" @click="showTagDialog(0)">新增</el-button>
                <el-table :data='vipTagList' border>
                    <el-table-column prop="name" label="标签名称"></el-table-column>
                    <el-table-column label="操作" width='200px'>
                        <template slot-scope="scope">
                            <el-row>
                                <el-col :span="10">
                                    <el-button size="small" type="primary" @click="showTagDialog(1,scope.$index)">编辑</el-button>
                                </el-col>
                                <el-col :span="10">
                                    <el-button size="small" type="danger" @click="removeTag(scope.$index)">删除</el-button>
                                </el-col>
                            </el-row>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tab-pane>
        </el-tabs>

        <el-dialog :visible.sync="dialogShow" width="500px">
            <el-row style="margin-bottom:20px;">
                <el-col :span="3" style="line-height:40px;">
                    会员名
                </el-col>
                <el-col :offset="1" :span="18">
                    <el-input v-model="vipData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3" style="line-height:40px;">
                    会员等级
                </el-col>
                <el-col :offset="1" :span="18">
                    <el-input v-model="vipData.group_id"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span="3" style="line-height:40px;">
                    会员手机
                </el-col>
                <el-col :offset="1" :span="18">
                    <el-input v-model="vipData.mobile"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:40px;">
                    会员标签
                </el-col>
                <el-col :offset="1" :span='18'>
                    <el-select style="width:100%;" :multiple-limit='3' multiple v-model="vipData.tags" placeholder="请选择">
                        <el-option v-for="(item,index) in vipTagList" :key="index" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-button @click="isNewOperating()" type="primary" style="margin:20px auto 0;display:block;">提交</el-button>
        </el-dialog>
        <!-- 积分加减 -->
        <el-dialog :visible.sync="integralDialog" title="修改积分" width="400px">
            <el-input-number style="margin:0 auto 5px;display:block;" v-model="vipData.integral" :step="10"></el-input-number>
            <p style="padding-left:90px;">剩余可用积分:{{toIntegral}}</p>
            <el-button style="margin:20px auto 0;display:block;" @click="editIntegral()">提交</el-button>
        </el-dialog>
        <!-- 余额加减 -->
        <el-dialog :visible.sync="moneyDialog" title="修改余额" width="400px">
            <el-input-number style="margin:0 auto 5px;display:block;" v-model="vipData.money" :step="10"></el-input-number>
            <p style="padding-left:90px;">剩余余额:{{toMoney}}</p>
            <el-button style="margin:20px auto 0;display:block;" @click="editMoney()">提交</el-button>
        </el-dialog>
        <!-- 会员等级 -->
        <el-dialog :visible.sync="groupDialog" title="修改会员等级" width="400px">
            <el-row style="margin-bottom:20px;">
                <el-col>
                    等级名称
                </el-col>
                <el-col>
                    <el-input v-model="groupData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col>
                    等级积分
                </el-col>
                <el-col>
                    <el-input v-model="groupData.value"></el-input>
                </el-col>
            </el-row>
            <el-button @click="isGroup()" type="primary" style="margin:0 auto;display:block;">提交</el-button>
        </el-dialog>
        <el-dialog :visible.sync="tagDialog" title="新增标签">
            <el-row>
                <el-col>
                    标签名称
                </el-col>
                <el-col>
                    <el-input v-model="tagInput"></el-input>
                </el-col>
            </el-row>
            <el-button type="primary" style="margin:20px auto 0;display:block;" size="small" @click="inputNewTag()">提交</el-button>
        </el-dialog>
    </el-main>
</template>

<script>
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import tip from "@/components/tip.vue";
export default {
    components: { tip },
    data() {
        return {
            dialogShow: false,
            integralDialog: false,
            moneyDialog: false,
            groupDialog: false,
            tagDialog: false,
            //
            isNewVip: false,
            isNewGroup: false,
            isNewTag:false,
            vipList: [
                {
                    integral: "",
                    money: "",
                    tags: []
                }
            ],
            vipGroup: [],
            vipData: {
                id: "",
                group_id: "",
                name: "",
                card_id: "",
                mobile: "",
                integral: "",
                integral_total: "",
                money: ""
            },
            groupData: {
                name: "",
                value: ""
            },
            thisVipIndex: "",
            thisGroupIndex: "",
            thisTagIndex:'',
            vipSet: {
                status: "",
                scale: "", //小程序消费积分比
                offer_status: "", //会员优惠状态 0 : 关闭, 1 : 满减, 2 : 折扣
                auto_status: "", //结算时是否自动使用折扣
                offer: "" //会员等级对应的折扣
            },
            isOffer: 1, //0 满减  1 折扣
            offerTable0: [
                // {group:'',full:'',reduction:''}
            ],
            offerTable1: [
                // {group:'',discount:''}
            ],
            offerGroup: [],
            //vip标签
            vipTagList: [],
            tagInput: "",
            //修改前的tags
            oldTags: []
        };
    },
    computed: {
        toIntegral: function() {
            if (this.thisVipIndex !== "") {
                return (
                    this.vipList[this.thisVipIndex].integral +
                    this.vipData.integral
                );
            } else {
                return "error";
            }
        },
        toMoney: function() {
            if (this.thisVipIndex !== "") {
                return (
                    parseFloat(this.vipList[this.thisVipIndex].money) +
                    this.vipData.money
                );
            } else {
                return "error";
            }
        }
    },
    methods: {
        //切换前执行
        handletabClick(tab, event) {
            if (tab.index == 0) {
                this.getVipList();
            } else if (tab.index == 1) {
                this.getVipGroup();
            } else if (tab.index == 2) {
                this.getVipSet();
            } else if (tab.index == 3) {
                this.getVipTag();
            }
        },
        //切换group默认
        changeGroupSwitch(index) {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/groups/" +
                        this.vipGroup[index].id +
                        "/default"
                )
                .then(res => {
                    this.getVipGroup();
                });
        },
        //获取vip列表
        getVipList() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members"
                )
                .then(res => {
                    this.vipList = res.data.data;
                    this.getVipTag();
                    this.getVipGroup();
                });
        },
        //获取vip等级
        getVipGroup() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/groups"
                )
                .then(res => {
                    this.vipGroup = res.data.data;
                });
        },
        //添加vip
        addVip() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members",
                    {
                        //添加vip的参数..
                        group_id: this.vipData.group_id,
                        name: this.vipData.name,
                        mobile: this.vipData.mobile
                    }
                )
                .then(res => {
                    //添加成功...
                    this.dialogShow = false;
                    this.getVipList();
                    this.showMessage("success", "添加成功");
                });
        },
        //新建group
        addGroup() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/groups",
                    {
                        name: this.groupData.name,
                        value: this.groupData.value
                    }
                )
                .then(res => {
                    this.showMessage("success", "新增成功");
                    this.getVipGroup();
                    this.groupDialog = false;
                });
        },

        //打开添加dialog
        openAddDialog() {
            this.dialogShow = true;
            this.isNewVip = true;
            this.vipData = {
                id: "",
                group_id: "",
                name: "",
                card_id: "",
                mobile: "",
                integral: "",
                integral_total: "",
                money: ""
            };
        },
        //打开积分修改dialog
        openIntegralDialog(index) {
            this.integralDialog = true;
            this.thisVipIndex = index;
            this.vipData.integral = 0;
        },
        //打开余额修改dialog
        openMoneyDialog(index) {
            this.moneyDialog = true;
            this.thisVipIndex = index;
            this.vipData.money = 0;
        },

        //修改积分
        editIntegral() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members/changeIntegral",
                    {
                        member_id: this.vipList[this.thisVipIndex].id,
                        value: this.vipData.integral
                    }
                )
                .then(res => {
                    this.showMessage("success", "积分修改成功");
                    this.integralDialog = false;
                    this.getVipList();
                });
        },
        editMoney() {
            axios
                .post(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members/changeMoney",
                    {
                        member_id: this.vipList[this.thisVipIndex].id,
                        value: this.vipData.money
                    }
                )
                .then(res => {
                    this.showMessage("success", "余额修改成功");
                    this.moneyDialog = false;
                    this.getVipList();
                });
        },
        //新建vip等级
        newGroupDialog() {
            this.groupDialog = true;
            this.isNewGroup = true;
            this.groupData.name = "";
            this.groupData.value = "";
        },
        //打开VIP等级修改
        openGroupDialog(index) {
            this.groupDialog = true;
            this.isNewGroup = false;
            this.thisGroupIndex = index;
            this.groupData.name = this.vipGroup[index].name;
            this.groupData.value = this.vipGroup[index].value;
        },
        //修改vip资料
        editVip(index) {
            this.dialogShow = false;
            let inputTags = [];
            for (let i = 0; i < this.vipTagList.length; i++) {
                for (let j = 0; j < this.vipData.tags.length; j++) {
                    if (this.vipTagList[i].id == this.vipData.tags[j]) {
                        inputTags.push(this.vipTagList[i].id);
                    }
                }
            }
            //取出新旧2数组共同拥有部分
            //oldTags []  --  inputTags []
            let pubHas = [];
            for (let i = 0; i < this.oldTags.length; i++) {
                for (let j = 0; j < inputTags.length; j++) {
                    if (this.oldTags[i].id == inputTags[j].id) {
                        pubHas.push(this.oldTags[i].id);
                    }
                }
            }
            for(let i = 0;i<this.oldTags.length;i++){
                this.oldTags[i] = this.oldTags[i].id
            }
            //如果共用元素为空 pubHas = []
            if (pubHas.length == 0) {
                for (let j = 0; j < this.oldTags.length; j++) {
                    //删除 this.oldTags[j]
                    axios.post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/members/members/deleteTag",
                        {
                            member_id: this.vipList[this.thisVipIndex].id,
                            tag_Id: this.oldTags[j]
                        }
                    );
                }
                for (let z = 0; z < inputTags.length; z++) {
                    //添加 inputTags[z]
                    axios.post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/members/members/addTag",
                        {
                            member_id: this.vipList[this.thisVipIndex].id,
                            tag_Id: inputTags[z]
                        }
                    );
                }
            } else {
                //pubHas 与 新旧数组对比  旧多出的减，新多出的加
                let removerArr = this.subSet(this.oldTags, pubHas);
                console.log(removerArr);
                if (removerArr.length != 0) {
                    for (let j = 0; j < removerArr.length; j++) {
                        axios.post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/members/members/deleteTag",
                            {
                                member_id: this.vipList[this.thisVipIndex].id,
                                tag_Id: removerArr[j]
                            }
                        );
                    }
                }

                let addArr = this.subSet(inputTags, pubHas);
                console.log(addArr);

                if (addArr.length != 0) {
                    for (let z = 0; z < addArr.length; z++) {
                        axios.post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/members/members/addTag",
                            {
                                member_id: this.vipList[this.thisVipIndex].id,
                                tag_Id: addArr[z]
                            }
                        );
                    }
                }
            }
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members/" +
                        this.vipList[index].id,
                    {
                        //修改vip资料..
                        group_id: this.vipData.group_id,
                        name: this.vipData.name,
                        mobile: this.vipData.mobile
                    }
                )
                .then(res => {
                    //修改成功..
                    this.showMessage("success", "修改成功");
                    this.getVipList();
                });
        },

        //求数组差集 arr2 公共部分
        subSet(arr1, arr2) {
            var set1 = new Set(arr1);
            var set2 = new Set(arr2);

            var subset = [];

            for (let item of set1) {
                if (!set2.has(item)) {
                    subset.push(item);
                }
            }

            return subset;
        },
        //修改group
        editGroup() {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/groups/" +
                        this.vipGroup[this.thisGroupIndex].id,
                    {
                        name: this.groupData.name,
                        value: this.groupData.value
                    }
                )
                .then(res => {
                    this.showMessage("success", "修改等级成功");
                    this.groupDialog = false;
                    this.getVipGroup();
                });
        },
        //打开修改vip dialog
        openEditDialog(index) {
            this.thisVipIndex = index;
            this.dialogShow = true;
            this.isNewVip = false;
            this.vipData = {
                group_id: this.vipList[index].group_id,
                name: this.vipList[index].name,
                mobile: this.vipList[index].mobile,
                tags: []
            };
            for (let i = 0; i < this.vipList[index].tags.length; i++) {
                this.vipData.tags.push(this.vipList[index].tags[i].id);
            }
            this.oldTags = this.vipList[index].tags;
        },
        //删除vip
        removeVip(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members/" +
                        this.vipList[index].id
                )
                .then(res => {
                    //删除成功
                    this.showMessage("success", "删除成功");
                    this.getVipList();
                });
        },
        //删除group
        removeGroup() {
            axios.delete().then(res => {});
        },
        //是否新建vip
        isNewOperating() {
            if (this.isNewVip) {
                this.addVip();
            } else {
                this.editVip(this.thisVipIndex);
            }
        },
        //是否新建group
        isGroup() {
            if (this.isNewGroup) {
                this.addGroup();
            } else {
                this.editGroup();
            }
        },
        //vip列表group下拉修改
        changeVipGroup(index) {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members/" +
                        this.vipList[index].id +
                        "/group/" +
                        this.vipList[index].group_id
                )
                .then(res => {
                    this.showMessage("success", "修改成功");
                });
        },
        //vip列表tag下拉修改
        changeVipTag(index) {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/members/" +
                        this.vipList[index].id,
                    {
                        name: this.vipList[index].name,
                        mobile: this.vipList[index].mobile,
                        tags: this.vipList[index].tags
                    }
                )
                .then(res => {
                    this.showMessage("success", "修改成功");
                });
            // axios.get("/web/" +
            //             store.state.xcx_flag.xcx_flag +
            //             "/api/members/members/"+ this.vipList[index].id +"/tags/"+this.vipList[index].tags).then(res=>{
            //                 this.showMessage('success','修改成功')
            //             })
        },
        /////////////////////////////////获取会员卡设置//////////////////////////////////
        getVipSet() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/settings"
                )
                .then(res => {
                    this.vipSet = res.data.setting;
                    this.offerGroup = res.data.groups;
                    this.offerTable1 = [];
                    this.offerTable0 = [];
                    for (let i = 0; i < res.data.groups.length; i++) {
                        this.offerTable1.push({
                            group: res.data.groups[i].id,
                            discount: ""
                        });
                        this.offerTable0.push({
                            group: res.data.groups[i].id,
                            full: "",
                            reduction: ""
                        });
                    }
                    if (this.vipSet.offer_status === 1) {
                        // this.offerTable1 = this.vipSet.offer
                        for (let i = 0; i < this.vipSet.offer.length; i++) {
                            this.offerTable1[i].discount = this.vipSet.offer[
                                i
                            ].discount;
                        }
                    } else if (this.vipSet.offer_status === 2) {
                        // this.offerTable0 = this.vipSet.offer
                        for (let i = 0; i < this.vipSet.offer.length; i++) {
                            this.offerTable0[i].full = this.vipSet.offer[
                                i
                            ].full;
                            this.offerTable0[i].reduction = this.vipSet.offer[
                                i
                            ].reduction;
                        }
                    }
                });
        },
        //获取viptag
        getVipTag() {
            axios
                .get(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/tags"
                )
                .then(res => {
                    this.vipTagList = res.data.data;
                    console.log();
                    
                });
        },
        //新增折扣规则
        isTableOne() {
            if (this.vipSet.offer_status === 1) {
                if (
                    this.offerTable1[this.offerTable1.length - 1].group != "" &&
                    this.offerTable1[this.offerTable1.length - 1].discount != ""
                ) {
                    this.offerTable1.push({ group: "", discount: "" });
                } else {
                    this.showMessage("error", "上一条等级规则未填写完整");
                }
            } else if (this.vipSet.offer_status === 2) {
                if (
                    this.offerTable0[this.offerTable0.length - 1].group != "" &&
                    this.offerTable0[this.offerTable0.length - 1].full != "" &&
                    this.offerTable0[this.offerTable0.length - 1].reduction !=
                        ""
                ) {
                    this.offerTable0.push({
                        group: "",
                        full: "",
                        reduction: ""
                    });
                } else {
                    this.showMessage("error", "上一条等级规则未填写完整");
                }
            }
        },

        //提交vipset
        inputVipSet() {
            let tableValue = [];
            if (this.vipSet.status === 1) {
                if (this.vipSet.offer_status !== 0) {
                    if (this.vipSet.offer_status === 1) {
                        tableValue = this.offerTable1;
                        if (
                            this.offerTable1[this.offerTable1.length - 1]
                                .group == "" ||
                            this.offerTable1[this.offerTable1.length - 1]
                                .full == "" ||
                            this.offerTable1[this.offerTable1.length - 1]
                                .reduction == ""
                        ) {
                            this.showMessage("error", "等级规则未填写完整");
                            return;
                        }
                    } else if (this.vipSet.offer_status === 2) {
                        tableValue = this.offerTable0;
                        if (
                            this.offerTable0[this.offerTable0.length - 1]
                                .group == "" ||
                            this.offerTable0[this.offerTable0.length - 1]
                                .discount == ""
                        ) {
                            this.showMessage("error", "等级规则未填写完整");
                            return;
                        }
                    }

                    axios
                        .put(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/members/settings/" +
                                this.vipSet.id,
                            {
                                scale: this.vipSet.scale,
                                status: this.vipSet.status,
                                offer_status: this.vipSet.offer_status,
                                auto_status: this.vipSet.auto_status,
                                offer: tableValue
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
                                "/api/members/settings/" +
                                this.vipSet.id,
                            {
                                scale: this.vipSet.scale,
                                status: this.vipSet.status,
                                offer_status: this.vipSet.offer_status,
                                auto_status: this.vipSet.auto_status,
                                offer: ['1']
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "保存成功");
                        });
                }
            }
        },
        //vipset状态改变
        vipStatusChange() {
            axios
                .put(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/settings/" +
                        this.vipSet.id,
                    {
                        scale: this.vipSet.scale,
                        status: this.vipSet.status,
                        offer_status: this.vipSet.offer_status,
                        auto_status: this.vipSet.auto_status,
                        offer: this.vipSet.offer
                    }
                )
                .then(res => {});
        },

        //tag操作
        showTagDialog(i, index = 0) {
            this.tagDialog = true;
            if (i === 0) {
                this.tagInput = "";
                this.isNewTag = true
            } else {
                this.tagInput = this.vipTagList[index].name;
                this.isNewTag = false
                this.thisTagIndex = index
            }
        },
        //提交新的tag
        inputNewTag() {
            if(this.isNewTag){
                if (this.tagInput != "") {
                    axios
                        .post(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/members/tags",
                            {
                                name: this.tagInput
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "标签新建成功");
                            this.tagDialog = false;
                            this.getVipTag();
                        });
                } else {
                    this.showMessage("error", "标签名称不能为空");
                }
            }else{
                if (this.tagInput != "") {
                    axios
                        .put(
                            "/web/" +
                                store.state.xcx_flag.xcx_flag +
                                "/api/members/tags/"+this.vipTagList[this.thisTagIndex].id,
                            {
                                name: this.tagInput
                            }
                        )
                        .then(res => {
                            this.showMessage("success", "标签修改成功");
                            this.tagDialog = false;
                            this.getVipTag();
                        });
                } else {
                    this.showMessage("error", "标签名称不能为空");
                }
            }
            
        },
        //删除Tag
        removeTag(index) {
            axios
                .delete(
                    "/web/" +
                        store.state.xcx_flag.xcx_flag +
                        "/api/members/tags/" +
                        this.vipTagList[index].id
                )
                .then(res => {
                    this.showMessage("success", "删除成功");
                    this.getVipTag();
                });
        },
        //消息
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
            });
        }
    },
    mounted() {
        this.getVipList();
    }
};
</script>

<style scoped>
</style>