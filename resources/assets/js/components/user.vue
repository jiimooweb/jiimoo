<template>
    <el-main style="min-width:950px;">
        <el-breadcrumb>
            <el-breadcrumb-item separator='/' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
            </el-breadcrumb-item>
        </el-breadcrumb>
        <div class='info' style="width:98%;margin:20px auto;">
            <el-row>
                <el-col :span='3' style="line-height:40px;color:#666">
                    企业名称
                </el-col>
                <el-col :span='10'>
                    <el-input placeholder="企业名称" v-model="usermessage.name"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:80px;color:#666">
                    企业Logo
                </el-col>
                <el-col :span='10'>
                    <el-upload action="/qiniuUpload" :onSuccess="uploadSuccessLogo" :file-list="logolist" list-type="picture-card" :limit="1" :headers="headers" :on-remove="handleRemoveLogo">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:40px;color:#666;">
                    企业简介
                </el-col>
                <el-col :span='10'>
                    <el-input type='textarea' v-model="usermessage.intro" :autosize="{ minRows: 4, maxRows: 4}" placeholder="请输入内容"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:40px;color:#666">
                    企业联系电话
                </el-col>
                <el-col :span='10'>
                    <el-input placeholder="企业电话" v-model="usermessage.tel"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:80px;color:#666">
                    企业二维码
                </el-col>
                <el-col :span='10'>
                    <el-upload action="/qiniuUpload" :file-list="qrlist" list-type="picture-card" :limit="1" :onSuccess="uploadSuccessQrcode" :headers="headers" :on-remove="handleRemoveQrcode">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:40px;color:#666">
                    企业地址
                </el-col>
                <el-col :span='10'>
                    <el-input placeholder="企业地址" v-model="usermessage.address"></el-input>
                </el-col>
                <el-col :span='2' :offset='1'>
                    <el-button @click="mapSearch()">搜索</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="line-height:40px;color:#666">
                    地图
                </el-col>
                <el-col :span="20">
<div id="allmap" style="margin-bottom:10px;">
                </div>
                </el-col>
                
                <el-col :span='3' style="line-height:40px;color:#666">&nbsp;&nbsp;
                </el-col>
                <el-col :span='6'>
                    <div style="width:800px">
                        <tip slass="primary" value="点击地图可确定经纬度"></tip>
                    </div>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="3">
                    企业相册
                </el-col>
                <el-col :span="10">
                    <el-upload action="/qiniuUpload" :file-list="albumList" list-type="picture-card" :limit="4" :onSuccess="uploadSuccessAlbum" :headers="headers" :on-remove="handleRemoveAlbum">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span='3' style="color:#666">
                    企业详情
                </el-col>
                <el-col :span="13" style="height:500px;">
                    <quill-editor style="height:450px;" ref="myTextEditor" :content="content" @change="onEditorChange" :config="editorOption">
                    </quill-editor>
                </el-col>
            </el-row>
            <el-button type='primary' style="display:block;margin:0 auto;" @click="uploadUserMessage">保存</el-button>
        </div>
    </el-main>
</template>

<script>
import { quillEditor } from "vue-quill-editor";
import tip from "@/components/tip.vue";
import BaiduMap from "@/api/baidumap/baidu-map";
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
export default {
    data() {
        return {
            content: "",
            editorOption: {
                // something config
            },
            isNew: false,
            usermessage: {
                name: "",
                logo: "",
                intro: "",
                images: [],
                tel: "",
                qrcode: "",
                address: "",
                Lon: "", //经度
                Lat: "", //纬度
                desc: ""
            },
            albumList: [],
            breadlist: "",
            qrlist: [],
            logolist: [],
            ueditor: {
                value: "",
                config: {}
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
    components: { tip, quillEditor },
    methods: {
        onEditorChange({ editor, html, text }) {
            this.content = html;
        },
        uploadSuccessLogo(response) {
            this.usermessage.logo = response.url;
        },
        uploadSuccessQrcode(response) {
            this.usermessage.qrcode = response.url;
        },
        uploadSuccessAlbum(response) {
            this.albumList.push({ name: "未命名", url: response.url });
            this.usermessage.images.push(response.url);
            console.log(this.usermessage.images);
        },
        handlePictureCardPreview() {},
        handleRemoveLogo() {
            axios.post("/qiniuDelete", { url: this.logo }).then(res => {
                this.usermessage.logo = "";
                this.logolist = [];
            });
        },
        handleRemoveQrcode() {
            axios.post("/qiniuDelete", { url: this.qrcode }).then(res => {
                this.usermessage.qrcode = "";
                this.qrlist = [];
            });
        },
        handleRemoveAlbum(file) {
            axios.post("/qiniuDelete", { url: file.url }).then(res => {
                console.log(file);
                for(let i=0;i<this.albumList.length;i++){
                    if(this.albumList[i].uid === file.uid){
                        this.albumList.splice(i,1)
                    }
                }
                console.log(this.albumList);
                
                this.usermessage.images = [];
                
                for (let i = 0; i < this.albumList.length; i++) {
                    this.usermessage.images.push(this.albumList[i].url);
                }
            });
        },
        beforeAvatarUpload(file) {
            const isJPG = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;

            if (!isJPG) {
                this.$message.error("上传头像图片只能是 JPG 格式!");
            } else if (!isLt2M) {
                this.$message.error("上传头像图片大小不能超过 2MB!");
            }
            return isJPG && isLt2M;
        },
        getUserInfos() {
            axios
                .get(
                    "/web/" + store.state.xcx_flag.xcx_flag + "/api/infos"
                )
                .then(res => {
                    if (!res.data.data) {
                        console.log("木有数据");
                        this.isNew = true;
                    } else {
                        this.usermessage.name = res.data.data.name;
                        this.usermessage.logo = res.data.data.logo;
                        this.logolist.push({
                            name: "logo",
                            url: res.data.data.logo
                        });
                        this.usermessage.intro = res.data.data.intro;
                        this.usermessage.tel = res.data.data.tel;
                        this.usermessage.qrcode = res.data.data.qrcode;
                        this.qrlist.push({
                            name: "qrcode",
                            url: res.data.data.qrcode
                        });
                        this.usermessage.address = res.data.data.address;
                        this.usermessage.Lat = res.data.data.lat;
                        this.usermessage.Lon = res.data.data.lon;
                        this.usermessage.desc = res.data.data.desc;
                        this.usermessage.images = res.data.data.images;
                        this.albumList = [];
                        if (
                            this.usermessage.images == "" ||
                            this.usermessage.images == null
                        ) {
                            this.usermessage.images = [];
                        }
                        if (this.usermessage.images.length != 0) {
                            // this.usermessage.images = eval(
                            //     "(" + this.usermessage.images + ")"
                            // );
                            for (
                                let i = 0;
                                i < this.usermessage.images.length;
                                i++
                            ) {
                                this.albumList.push({
                                    name: "1",
                                    url: this.usermessage.images[i]
                                });
                            }
                        }
                        
                        this.content = res.data.data.desc;
                        this.mapInit(
                            this.usermessage.Lon,
                            this.usermessage.Lat
                        );
                    }
                });
        },
        uploadUserMessage() {
            console.log(this.usermessage.images);
            // this.usermessage.images = ''
            // return
            this.usermessage.desc = this.content;
            if (this.isNew) {
                axios
                    .post(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/infos",
                        {
                            name: this.usermessage.name,
                            logo: this.usermessage.logo,
                            intro: this.usermessage.intro,
                            tel: this.usermessage.tel,
                            qrcode: this.usermessage.qrcode,
                            images: this.usermessage.images,
                            address: this.usermessage.address,
                            lat: this.usermessage.Lat,
                            lon: this.usermessage.Lon,
                            desc: this.usermessage.desc
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "新增成功");
                    });
            } else {
                axios
                    .put(
                        "/web/" +
                            store.state.xcx_flag.xcx_flag +
                            "/api/infos/" +
                            store.state.xcxId.xcxID,
                        {
                            name: this.usermessage.name,
                            logo: this.usermessage.logo,
                            intro: this.usermessage.intro,
                            tel: this.usermessage.tel,
                            images: this.usermessage.images,
                            qrcode: this.usermessage.qrcode,
                            address: this.usermessage.address,
                            lat: this.usermessage.Lat,
                            lon: this.usermessage.Lon,
                            desc: this.usermessage.desc
                        }
                    )
                    .then(res => {
                        this.showMessage("success", "更新成功");
                    });
            }
        },
        showMessage(type, msg) {
            this.$message({
                message: msg,
                type: type
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

        mapSearch() {
            // this.mapInit(lon,lat)
            var _this = this;
            var map = new BMap.Map("allmap"); // 创建地图实例
            // var point = new BMap.Point(lon, lat); // 创建点坐标
            // map.centerAndZoom(point, 20); // 初始化地图，设置中心点坐标和地图级别
            map.enableScrollWheelZoom(true);
            var local = new BMap.LocalSearch(map, {
                renderOptions: { map: map }
            });
            local.search(this.usermessage.address);
            map.clearOverlays();
            map.addEventListener("click", function(e) {
                _this.usermessage.Lat = e.point.lat;
                _this.usermessage.Lon = e.point.lng;
                var lat = e.point.lat;
                var lng = e.point.lng;
                map.clearOverlays();
                map.addOverlay(new BMap.Marker(new BMap.Point(lng, lat))); //增加点
            });
        },
        mapInit(lon = "", lat = "") {
            var map = new BMap.Map("allmap"); // 创建地图实例
            var point = new BMap.Point(lon, lat); // 创建点坐标
            map.centerAndZoom(point, 20); // 初始化地图，设置中心点坐标和地图级别
            map.enableScrollWheelZoom(true);
            var _this = this;
            map.addEventListener("click", function(e) {
                _this.usermessage.Lat = e.point.lat;
                _this.usermessage.Lon = e.point.lng;
                var lat = e.point.lat;
                var lng = e.point.lng;
                map.clearOverlays();
                map.addOverlay(new BMap.Marker(new BMap.Point(lng, lat))); //增加点
            });
            if ((lat != "", lon != "")) {
                var Lat = parseFloat(lat);
                var Lng = parseFloat(lon);
                var point = new BMap.Point(Lng, Lat); // 创建点坐标
                map.centerAndZoom(point, 20); // 初始化地图，设置中心点坐标和地图级别
                map.enableScrollWheelZoom(true);
                map.clearOverlays();
                map.addOverlay(new BMap.Marker(new BMap.Point(Lng, Lat))); //增加点
                console.log(Lng + "/" + Lat);
            } else {
                var local = new BMap.LocalSearch(map, {
                    renderOptions: { map: map }
                });
                local.search(this.usermessage.address);
                map.clearOverlays();
            }
        }
    },
    watch: {
        $route() {
            this.getBread();
        }
    },
    mounted() {
        BaiduMap.init().then(BMap => {
            this.getUserInfos();
            this.getBread();
            this.$refs.myTextEditor.quill.enable(false);
        });
    }
};
</script>

<style scoped>
#allmap {
    width: 800px;
    height: 500px;
}
.info > div {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid #ddd;
}
.info > div:first-child {
    margin-top: 70px;
}
.el-breadcrumb__item span {
    font-size: 40px;
}
</style>