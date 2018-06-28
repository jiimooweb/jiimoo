import {
    LOGIN_SUCCESS,
    USERINFO_SUCCESS,
    USERINFO_FAILURE,
    LOGOUT_USER,
    UPDATE_USER_SUCCESS,
    CHANGE_INTEGRAL_FLAG,
    UPDATE_XCXLIST,
    GET_XCXID,
    UPDATE_USERID,
    UPDATE_USERNAME,
    UPDATE_USEREMAIL
} from '../types'
const state = {
    token: localStorage.token || null,
    user: {
        id: '122',
        name: '',
        email:'',
        haspow:true
    },
    userID:'',
    userNAME:'',
    userEMAIL:'',
    xcxList:[],
    sendIntegralFlag: 1,
    xcxID:''
};

const mutations = {
    [LOGIN_SUCCESS](state, action) {
        state.token = action.token
    },
    [USERINFO_SUCCESS](state, action) {
        state.user = action.user;
    },
    [USERINFO_FAILURE](state, action) {
        state.user = null
    },
    [LOGOUT_USER](state, action) {
        localStorage.removeItem("token");
        state.user = null;
        state.token = null;
    },
    [UPDATE_USER_SUCCESS](state, action) {
        state.user = action.user
    },
    [CHANGE_INTEGRAL_FLAG](state, action){
        state.sendIntegralFlag = action;
    },
    [UPDATE_XCXLIST](state, action){
        state.xcxList = action;
    },
    [GET_XCXID](state, action){
        state.xcxID = action;
    },
    [UPDATE_USERID](state, action){
        state.userID = action.userID;
    },
    [UPDATE_USERNAME](state, action){
        state.userNAME = action.userNAME;
    },
    [UPDATE_USEREMAIL](state, action){
        state.userEMAIL = action.userEMAIL;
    }
};

export default {
    state,
    mutations
}
