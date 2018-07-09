<template>
    <el-main>
        <el-breadcrumb>
            <el-breadcrumb-item separator='/' v-for="(item, index) in breadlist" :key='index' :to="{path: item.path}">{{item.meta.CName}}
            </el-breadcrumb-item>
        </el-breadcrumb>
        <el-row>
            <el-col :span='2'>
                <el-button @click="newProduct()" type="button" size="small" style="margin:20px 0;">
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
                <el-button @click="toType()">产品分类>></el-button>
            </el-col>
        </el-row>
        <el-table :data="searchfilter?searchfilter:''" border v-loading="loading">
            <el-table-column prop="name" label="产品名"></el-table-column>
            <el-table-column label="产品图" width='90' align='center'>
                <template slot-scope="scope">
                    <img :src="productsList[scope.$index].thumb" width="70px" height="70px">
                </template>
            </el-table-column>
            <el-table-column prop="category.name" width="100" label="分类"></el-table-column>
            <el-table-column prop="stock" width="100" label="库存"></el-table-column>
            <el-table-column width='160' label="是否上架">
                <template slot-scope="scope">
                    <el-switch v-model="productsList[scope.$index].display" @change='changeSwitch(scope.$index)' :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column width='160' label="热卖">
                <template slot-scope="scope">
                    <el-switch v-model="productsList[scope.$index].hot" @change='changeSwitch(scope.$index)' :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column width='160' label="推荐">
                <template slot-scope="scope">
                    <el-switch v-model="productsList[scope.$index].recommend" @change='changeSwitch(scope.$index)' :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </template>
            </el-table-column>
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
        <el-dialog :visible.sync="showProductDialog" class="dialogFooter" width="600px">
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    产品名称
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="dialogData.name"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    分类
                </el-col>
                <el-col :span='20'>
                    <el-select v-model="dialogData.cate_id" placeholder="请选择分类">
                        <el-option v-for="item in typeList" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    封面图
                </el-col>
                <el-col :span='20'>
                    <el-upload action="/qiniuUpload" :file-list="thumbList" list-type="picture-card" :headers="headers" :onSuccess="uploadThumbSuccess" :limit="1" :on-preview="handleThumbPreview" :on-remove="handleThumbRemove">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    轮播图
                </el-col>
                <el-col :span='20'>
                    <el-upload action="/qiniuUpload" :file-list="bannerList" list-type="picture-card" :headers="headers" :onSuccess="uploadBannerSuccess" :limit="4" :on-preview="handleBannerPreview" :on-remove="handleBannerRemove">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    原价
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="dialogData.o_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    现价
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="dialogData.c_price"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    库存
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="dialogData.stock"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    是否上架
                </el-col>
                <el-col :span='20'>
                    <el-switch v-model="dialogData.display" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    热卖
                </el-col>
                <el-col :span='20'>
                    <el-switch v-model="dialogData.hot" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    推荐
                </el-col>
                <el-col :span='20'>
                    <el-switch v-model="dialogData.recommend" :active-value="1" :inactive-value="0" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    参数
                </el-col>
                <el-col :span='20'>
                    <el-input v-model="dialogData.format"></el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    优惠券
                </el-col>
                <el-col :span='20'>
                    <el-select style="width:100%;" :multiple-limit='3' multiple v-model="coupons" placeholder="请选择">
                        <el-option v-for="(item,index) in couponsList" :key="index" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row style="margin-bottom:20px;">
                <el-col :span='3'>
                    详情
                </el-col>
                <el-col :span="20" style="height:500px;overflow:auto;">
                    <quill-editor style="height:400px;" ref="myTextEditor" :content="this.dialogData.content" @change="onEditorChange($event)">
                    </quill-editor>
                </el-col>
            </el-row>
            <span slot="footer" class="dialog-footer">
                <el-button @click="showProductDialog = false">取 消</el-button>
                <el-button type="primary" @click="isNewProduct()">确 定</el-button>
            </span>
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
      showProductDialog: false,
      isNewProductValue: false,
      updateId: "",
      loading: false,
      breadlist: "",
      searchValue: "",
      search: "",
      typeList: [],
      searchData: [],

      productsList: [],
      dialogData: {
        id: "",
        name: "",
        cate_id: "",
        thumb: "",
        banner: [],
        o_price: "", //原价
        c_price: "", //现价
        stock: "", //库存
        display: 0,
        hot: 0, //热卖
        recommend: 0, //推荐
        format: "", //参数
        coupons: [],
        content: ""
      },
      thumbList: [],
      bannerList: [],
      coupons: [],
      couponsList: []
    };
  },
  components: { quillEditor },
  methods: {
    //----获取优惠券
    getCoupongs() {
      axios
        .get(
          "/web/" + store.state.xcx_flag.xcx_flag + "/api/coupons/coupons"
        )
        .then(res => {
          this.couponsList = res.data.data.data;
        });
    },
    //整理优惠券数据
    sortCoupons(arr) {
        this.coupons = []
      for (let i = 0; i < arr.length; i++) {
        this.coupons.push(arr[i].id);
        console.log(arr.length);
      }
    },
    //搜索
    getSearch() {
      let fnData = [];
      this.loading = true;
      axios
        .get(
          "/web/" +
            store.state.xcx_flag.xcx_flag +
            "/api/products?keyword=" +
            (this.search == "" ? "" : this.search) +
            "&cate_id=" +
            this.searchValue
        )
        .then(response => {
          for (let i = 0; i < response.data.data.data.length; i++) {
            fnData.push(response.data.data.data[i]);
          }
          this.loading = false;
        });
      this.productsList = fnData;
    },
    toType() {
      this.$router.push({ path: "/userManage/content/productsType" });
    },
    onEditorChange({ editor, html, text }) {
      this.dialogData.content = html;
    },
    //图片事件
    uploadThumbSuccess(file) {
      this.dialogData.thumb = file.url;
    },
    handleThumbPreview(file) {},
    handleThumbRemove(file) {
      axios.post("/qiniuDelete", { url: file.url }).then(res => {
        this.productsList[this.updateId].thumb = "";
      });
    },

    uploadBannerSuccess(file) {
      this.dialogData.banner.push(file.url);
      console.log(this.dialogData.banner);
    },
    handleBannerPreview(file) {},
    handleBannerRemove(file) {
      axios.post("/qiniuDelete", { url: file.url }).then(res => {
        this.productsList[this.updateId].banner = [];
        for (let i = 0; i < this.bannerList.length; i++) {
          this.productsList[this.updateId].banner.push(this.bannerList[i].url);
        }
      });
    },
    //
    getBread() {
      this.breadlist = this.$route.matched;
      this.$route.matched.forEach((item, index) => {
        //先判断父级路由是否是空字符串或者meta是否为首页，直接复写路径到根路径
        item.meta.CName === "首页"
          ? (item.path = "/")
          : this.$route.path === item.path;
      });
    },
    newProduct() {
      this.showProductDialog = true;
      this.isNewProductValue = true;
      this.thumbList = [];
      this.bannerList = [];
      this.dialogData = {
        id: "",
        name: "",
        cate_id: "",
        thumb: "",
        banner: "",
        o_price: "", //原价
        c_price: "", //现价
        stock: "", //库存
        display: 0,
        hot: 0, //热卖
        recommend: 0, //推荐
        format: "", //参数
        coupons: [],
        content: ""
      };
      this.coupons = []
    },
    getRowArticle(index) {
      this.updateId = this.productsList[index].id;
      this.showProductDialog = true;
      this.isNewProductValue = false;
      this.dialogData = {
        id: this.productsList[index].id,
        name: this.productsList[index].name,
        cate_id: this.productsList[index].cate_id,
        thumb: this.productsList[index].thumb,
        banner: this.productsList[index].banner,
        o_price: this.productsList[index].o_price, //原价
        c_price: this.productsList[index].c_price, //现价
        stock: this.productsList[index].stock, //库存
        display: this.productsList[index].display,
        hot: this.productsList[index].hot, //热卖
        recommend: this.productsList[index].recommend, //推荐
        format: this.productsList[index].format, //参数
        coupons: this.productsList[index].coupons,
        content: this.productsList[index].content
      };
      this.sortCoupons(this.productsList[index].coupons);
      if (this.dialogData.banner == null) {
        this.dialogData.banner = [];
        // console.log(this.dialogData.banner);
      }
      this.bannerList = [];
      this.thumbList = [];
      this.thumbList.push({ name: "1", url: this.dialogData.thumb });
      for (let i = 0; i < this.productsList[index].banner.length; i++) {
        if (
          this.productsList[index].banner !== null ||
          this.productsList[index].banner !== ""
        ) {
          this.bannerList.push({
            name: "未命名",
            url: this.productsList[index].banner[i]
          });
        }
      }
    },
    changeSwitch(index) {
      axios
        .put(
          "/web/" +
            store.state.xcx_flag.xcx_flag +
            "/api/products/" +
            this.productsList[index].id,
          {
            name: this.productsList[index].name,
            cate_id: this.productsList[index].cate_id,
            thumb: this.productsList[index].thumb,
            banner: this.productsList[index].banner,
            o_price: this.productsList[index].o_price, //原价
            c_price: this.productsList[index].c_price, //现价
            stock: this.productsList[index].stock, //库存
            display: this.productsList[index].display,
            hot: this.productsList[index].hot, //热卖
            recommend: this.productsList[index].recommend, //推荐
            format: this.productsList[index].format, //参数
            coupons: this.productsList[index].coupons,
            content: this.productsList[index].content
          }
        )
        .then(res => {
          this.showMessage("success", "修改成功");
        });
    },
    removeArticle(index) {
      axios
        .delete(
          "/web/" +
            store.state.xcx_flag.xcx_flag +
            "/api/products/" +
            this.productsList[index].id
        )
        .then(res => {
          this.showMessage("success", "删除成功");
          this.getAllProducts();
        });
    },
    //获取所有分类
    getTypeList() {
      axios
        .get(
          "/web/" + store.state.xcx_flag.xcx_flag + "/api/product_cates"
        )
        .then(res => {
          this.typeList = res.data.data;
        });
    },
    //获取所有产品
    getAllProducts() {
      axios
        .get("/web/" + store.state.xcx_flag.xcx_flag + "/api/products")
        .then(res => {
          this.productsList = res.data.data.data;
          console.log(this.productsList);
        });
    },
    isNewProduct() {
      if (this.isNewProductValue) {
        axios
          .post(
            "/web/" + store.state.xcx_flag.xcx_flag + "/api/products",
            {
              name: this.dialogData.name,
              cate_id: this.dialogData.cate_id,
              thumb: this.dialogData.thumb,
              banner: this.dialogData.banner,
              o_price: this.dialogData.o_price,
              c_price: this.dialogData.c_price,
              stock: this.dialogData.stock,
              display: this.dialogData.display,
              hot: this.dialogData.hot,
              recommend: this.dialogData.recommend,
              format: this.dialogData.format,
              coupons: this.coupons,
              content: this.dialogData.content
            }
          )
          .then(res => {
            this.showProductDialog = false;
            this.showMessage("success", "新增成功");
            this.getAllProducts();
          });
      } else {
        axios
          .put(
            "/web/" +
              store.state.xcx_flag.xcx_flag +
              "/api/products/" +
              this.dialogData.id,
            {
              name: this.dialogData.name,
              cate_id: this.dialogData.cate_id,
              thumb: this.dialogData.thumb,
              banner: this.dialogData.banner,
              o_price: this.dialogData.o_price,
              c_price: this.dialogData.c_price,
              stock: this.dialogData.stock,
              display: this.dialogData.display,
              hot: this.dialogData.hot,
              recommend: this.dialogData.recommend,
              format: this.dialogData.format,
              coupons: this.coupons,
              content: this.dialogData.content
            }
          )
          .then(res => {
            this.showProductDialog = false;
            this.showMessage("success", "修改成功");
            this.getAllProducts();
          });
      }
    },
    showMessage(type, msg) {
      this.$message({
        message: msg,
        type: type
      });
    }
  },
  watch: {
    $route() {
      this.getBread();
    }
  },
  computed: {
    searchfilter: function() {
      var search = this.search;
      if (search) {
        return this.productsList.filter(function(productsList) {
          return ["name"].some(function(key) {
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
    headers() {
      return {
        token: localStorage.token
      };
    }
  },
  mounted() {
    this.getBread();
    this.getTypeList();
    this.getAllProducts();
    this.getCoupongs();
  }
};
</script>

<style scoped>
.dialogFooter .el-dialog {
  width: 950px;
  margin-top: 7% !important;
  height: 80%;
  overflow: auto;
}
</style>