var rules = {
    //用户名，密码
    user: [
        { required: true, message: "请输入用户名", trigger: "blur" },
        // {min: 2,max: 5,message: "长度在 2 到 5 个字符"},
        // {pattern: /^[\u4E00-\u9FA5]+$/,message: "用户名只能为中文"}
        //{ pattern:/^[a-zA-Z]w{1,4}$/, message: '以字母开头，长度在2-5之间， 只能包含字符、数字和下划线'}
    ],
    password: [
        { required: true, message: "请输入密码", trigger: "blur" },
    ],

    //不为空 
}


export default rules