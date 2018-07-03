<template>
	<div class="dialog" v-loading="loading">
		<div class="loginPage">
			<div>
				<img src="img/logo.png" width='200px' style="display:block;margin:0 auto;">
				<h4 style="margin-top:10px;font-size:30px;color:#333;font-weight:100;text-align:center;">任意门后台管理系统</h4>
				<el-form class="loginform" :model="loginInfo" :rules="rules" ref="loginInfo">
					<el-form-item prop="user">
						<el-input type="text" id="user" v-model="loginInfo.user" placeholder="账号："></el-input>
					</el-form-item>
					<el-form-item prop="password">
						<el-input type="password" id="password" v-model="loginInfo.password" placeholder="密码：" @keyup.enter.native="submitRules('loginInfo')"></el-input>
					</el-form-item>
					<el-button class="loginbtn" type="primary" @click="submitRules('loginInfo')">登录</el-button>
				</el-form>
			</div>
      <el-row>
      </el-row>
		</div>  
	</div>
</template>

<script>
import router from "@/router/index.js";
import _rq from "../api/index";
import Rules from "../api/rules";
import store from "@/vuex/store";
import axios from "axios";
import VueAxios from "vue-axios";
import qs from "qs";
import { mapState, mapActions, mapGetters } from "vuex";

export default {
  name: "login",
  computed: {},
  data() {
    return {
      rules: Rules,
      loading: false,
      loginInfo: {
        // user: "admin",
        user: "",
        // password: "Adminjiimoo",
        password: "",
        beDisabled: false
      },
      user_id: store.state.userId
    };
  },

  methods: {
    submitRules(refName) {
      this.$refs[refName].validate(valid => {
        if (valid) {
          //这里是验证成功后该干嘛干嘛
          this.handleSubmit();
        } else {
          console.log("验证失败");
          return false;
        }
      });
    },
    handleSubmit() {
      //验证
      this.loading = true;
      axios
        .post("api/user/login", {
          username: this.loginInfo.user,
          password: this.loginInfo.password
        })
        .then(
          response => {
            localStorage.token = response.data.token;
            var tokenTime = new Date();
            var Time = tokenTime.getTime();
            localStorage.tokenTime = Time;
            store.commit("SET_USERID", { userId: response.data.user.id });

            store.commit("SET_USERNAME", {
              userName: response.data.user.username
            });
            store.commit("SET_USEREMAIL", {
              userEmail: response.data.user.email
            });
            store.commit("SET_AVATARURL", {
              avatarUrl: response.data.user.avatarUrl
            });
            store.commit("SET_IDENTITY", {
              identity: response.data.user.identity
            });
            localStorage.setItem('identity',response.data.user.identity)
            this.loading = true;
            return this.$router.push({ path: "/" });
          },
          response => {
            this.$message.error("账号密码不正确!");
            this.loading = false;
          }
        );
    }
  }
};
</script>
<style>
.loginform input {
  display: block;
  margin: 0 auto;
  border: none !important;
  border-bottom: 1px solid #409eff !important;
  border-radius: 0 !important;
  width: 300px;
}
</style>

<style scoped>
.dialog {
  overflow: hidden;
  width: 100%;
  height: 100%;
}
.loginPage {
  width: 300px;
  margin: 0 auto;
  display: table;
  overflow: hidden;
  background: #fff;
  /* width: calc(50% - 1px); */
  /* width: 100%; */
  height: 100%;
  /* border-right: 1px solid #ddd; */
  background-color: #fff;
}
.loginPage > div {
  display: table-cell;
  vertical-align: middle;
  margin: 0 auto 0;
  /* padding-top:50%; */
  /* margin-top:calc((100% - 200px)/2); */
  width: 300px;
  height: 200px;
}
.loginform {
  /* margin-top: 200px; */
}
.loginform input {
  width: 300px;
}

@media screen and (max-width: 900px) {
  .loginPage {
    width: 100%;
    border: 0px;
  }
}
.loginPage p {
  color: red;
  text-align: left;
  line-height: 0px;
}

.loginbtn {
  display: block;
  margin: 0 auto;
}
</style>