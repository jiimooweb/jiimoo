import * as types from './mutation-type.js';

export default {
    //user的id
    [types.SET_USERID](state, userId) {
        state.userId = userId;
    },
    //user的name
    [types.SET_USERNAME](state, userName) {
        state.userName = userName;
    },
    //当前选择的小程序id
    [types.SET_XCXID](state, xcxId) {
        state.xcxId = xcxId;
    },
    //当前小程序flag
    [types.SET_XCXFLAG](state, xcxflag) {
        state.xcx_flag = xcxflag;
    },
    //user的头像
    [types.SET_AVATARURL](state, avatarUrl) {
        state.avatarUrl = avatarUrl;
    },
    //user的邮箱
    [types.SET_USEREMAIL](state, userEmail) {
        state.userEmail = userEmail;
    },
    //当前小程序的Nice_name
    [types.SET_NICKNAME](state, nick_name) {
        state.nick_name = nick_name;
    },
    //当前小程序的权限
    [types.SET_IDENTITY](state, identity) {
        state.identity = identity;
    }
};