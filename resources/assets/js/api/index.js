import axios from 'axios';
import VueAxios from 'vue-axios'
import store from '../vuex/store'
import VueRouter from 'vue-router'

const router = new VueRouter()
//水水IP
const ssIP = '192.168.31.50'
//浩哥IP
const hgIP = '192.168.31.106'
//沙发IP
const sfIP = '192.168.31.71'
//小灿IP
const xcIP = '192.168.31.161'

// 共用请求
/*
    type   类别
    ip     请求地址
    params 附带参数

    return  response
*/
let _rq = function(type,ip,params,fn = function(a){}){
    if(type=='get'){
        return axios.get(ip).then((response)=>{ 
            fn(response)
        })
    }else if(type=='post'){
        return axios.post(ip,params).then((response)=>{ 
            fn(response)
        })
    }else if(type=='put'){
        return axios.put(ip,params).then((response)=>{ 
            fn(response)
        })
    }else if(type=='delete'){
        return axios.delete(ip,params).then((response)=>{ 
            fn(response)
        })
    }
    
}

var showMessage = function(type, msg){
    this.$message({
        message: msg,
        type: type
    });
}

let loginOut = function(){
    localStorage.clear('token')
    this.$router.push({path:'/'})
}

// 阻塞器
axios.interceptors.request.use(
    config => {
        if (localStorage.token) { // 判断是否存在token，如果存在的话，则每个http header都加上token
            config.headers['token'] = localStorage.token
        }else{
            config.headers['token'] = null
        }
        return config;
    }
);
axios.interceptors.response.use(
    response => {
        var tokenTime = new Date()
        var Time = tokenTime.getTime()
        localStorage.tokenTime = Time
        return response
    },
    error => {
        if (error.response) {
            if (error.response.status === 403) {
                if (error.response.status === 403) {
                    // MessageBox.alert('登陆超时', "请重新登陆").then(action => {
                    // store.commit("LOGOUT_USER");
                    // router.push("/login");
                    // });
                }
            }
            return Promise.reject(error.response.data); 
            // 返回接口返回的错误信息
        }
    }
)


export default {_rq,showMessage}