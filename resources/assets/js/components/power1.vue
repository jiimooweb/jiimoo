<template>
    <el-scrollbar class="rightPageScroll" style='height:100%;'>
        <el-main>
            <el-row style="min-width:1200px;">
                <el-col>
                    <el-card class="box-card">
                        <div slot="header" class="clearfix">
                            <span>小程序数据助手</span>
                        </div>
                        <div class="text item">
                            <img src="Img/a0.png" width='300px' height='300px' style="display:block;float:left;margin-top:100px;">
                            <div style="width:700px;display:block;float:left;margin-left:80px;">
                                <p style="font-size:14px;color:#999;">使用说明:</p>
                                <p style="font-size:14px;color:#999;">小程序管理员：扫描上面的小程序码即可打开“小程序数据助手”，可以选择查看已绑定小程序的数据。其他微信用户：经管理员授权后可以查看已授权小程序的数据</p>
                            </div>
                            <img src="Img/a0test.png" width='700px'  style="display:block;margin:50px 0 0 80px;float:left;">
                        </div>
                        
                    </el-card>
                </el-col>
                <el-col>

                </el-col>
            </el-row>
        </el-main>

    </el-scrollbar>
</template>

<script>
export default {
    name: "power1",
    data() {
        return {
            myBarOption: {
                barColor: "#959595"
            },
            p1l1: [
                {
                    iconClass: "fa fa-user-circle",
                    colorStyle: "color: rgb(81, 181, 95);",
                    allNumName: "总用户量",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-file-text-o",
                    colorStyle: "color: rgb(250, 163, 34);",
                    allNumName: "订单总数",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-jpy",
                    colorStyle: "color: rgb(113, 129, 217);",
                    allNumName: "订单金额",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-user-circle",
                    colorStyle: "color: rgb(81, 181, 95);",
                    allNumName: "总用户量",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-file-text-o",
                    colorStyle: "color: rgb(250, 163, 34);",
                    allNumName: "订单总数",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-jpy",
                    colorStyle: "color: rgb(113, 129, 217);",
                    allNumName: "订单金额",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-user-circle",
                    colorStyle: "color: rgb(81, 181, 95);",
                    allNumName: "总用户量",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-file-text-o",
                    colorStyle: "color: rgb(250, 163, 34);",
                    allNumName: "订单总数",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-jpy",
                    colorStyle: "color: rgb(113, 129, 217);",
                    allNumName: "订单金额",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-user-circle",
                    colorStyle: "color: rgb(81, 181, 95);",
                    allNumName: "总用户量",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-file-text-o",
                    colorStyle: "color: rgb(250, 163, 34);",
                    allNumName: "订单总数",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-jpy",
                    colorStyle: "color: rgb(113, 129, 217);",
                    allNumName: "订单金额",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-user-circle",
                    colorStyle: "color: rgb(81, 181, 95);",
                    allNumName: "总用户量",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-file-text-o",
                    colorStyle: "color: rgb(250, 163, 34);",
                    allNumName: "订单总数",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                },
                {
                    iconClass: "fa fa-jpy",
                    colorStyle: "color: rgb(113, 129, 217);",
                    allNumName: "订单金额",
                    allnumValue: "0",
                    tadayNum: "0",
                    yesdayNum: "0"
                }
            ],
            p1l2: [
                {
                    powName: "电商",
                    iconClass: "fa fa-shopping-cart",
                    powDev: "电商商品、运费等管理",
                    link: "javascript://"
                }
            ]
        };
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
/* 第一列表 */
.pow1 {
    width: 90%;
    margin-left: 5%;
    margin-top: 50px;
}

.p1list1 {
    overflow: hidden;
    width: 100%;
}
.p1list1 > a {
    float: left;
    display: block;
    width: 100%;
    height: 100px;
    background: #eee;
}
.p1list1 > a + a {
    margin-top: 10px;
}
.p1list1 > a > i {
    width: 60px;
    text-align: center;
    float: left;
    margin-left: 50px;
    line-height: 100px;
    font-size: 60px;
}
.p1t {
    float: left;
    width: 85px;
    height: 100%;
    display: block;
}
.p1t > p:nth-child(1) {
    margin: 0;
    color: #333;
    line-height: 36px;
    font-size: 30px;
    padding-left: 15px;
    margin-top: 20px;
}
.p1t > p:nth-child(2) {
    color: #333;
    margin: 0;
    line-height: 18px;
    padding-left: 15px;
}

.p1num {
    width: 50%;
    height: 80px;
    margin-top: 10px;
    float: right;
    border-left: 1px solid #bbb;
}

.p1num > p {
    padding-right: 100px;
    color: #888;
    margin: 0;
    line-height: 40px;
    font-size: 14px;
    text-align: right;
}

/* 第二列表 */

.p1list2 {
    margin-top: 20px;
    width: 100%;
    overflow: hidden;
}

.p1list2 > p {
    font-size: 20px;
    padding-left: 10px;
    color: #333;
    margin: 10px 0;
}

.p1list2 > a {
    float: left;
    display: block;
    width: 100%;
    height: 80px;
    margin-top: 10px;
    background: #eee;
}
.p1list2 > a > i {
    background: #e7ecfc;
    font-size: 30px;
    color: rgb(55, 134, 252);
    text-align: center;
    float: left;
    line-height: 80px;
    width: 80px;
    height: 80px;
}
.p2t {
    float: left;
    width: calc(100% - 80px);
    height: 100%;
}
.p2t > p:nth-child(1) {
    color: #666;
}
.p2t > p {
    margin: 0;
    padding-left: 20px;
    font-size: 14px;
    line-height: 40px;
    color: #888;
}
@media screen and (min-width: 1100px) {
    /* 第一列表 */
    .p1list1 > a {
        width: 49%;
    }
    .p1list1 > a:nth-child(-n + 3) {
        margin-top: 0px;
    }
    .p1list1 > a:nth-child(n + 3) {
        margin-top: 10px;
    }
    .p1list1 > a:nth-child(2n + 2) {
        margin-left: 2%;
    }

    /* 第二列表 */

    .p1list2 > a {
        width: 49%;
    }
    .p1list2 > a:nth-child(2n + 3) {
        margin-left: 2%;
    }
}

@media screen and (min-width: 1400px) {
    /* 第一列表 */
    .p1list1 > a {
        width: 32%;
    }
    .p1list1 > a + a {
        margin-left: 2%;
    }
    .p1list1 > a:nth-child(3n + 1) {
        margin-left: 0%;
    }
    .p1num {
        width: 45%;
    }

    /* 第二列表 */
    .p1list2 > a {
        width: 32%;
    }
    .p1list2 > a + a {
        margin-left: 2%;
    }
    .p1list2 > a:nth-child(4n + 1) {
        margin-left: 0;
    }
}
</style>