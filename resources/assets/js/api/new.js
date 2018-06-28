! function () {
        //查找w对象中是否存在v属性，有则返回本身，无则返回i
        function o(w, v, i) {
                return w.getAttribute(v) || i
        }

        // j 函数 根据TagName获取节点
        function j(i) {
                return document.getElementsByTagName(i)
        }

        function l() {
                var i = j("script"),//用j函数 查找  script 节点
                        w = i.length,//获取 script 节点的长度
                        v = i[w - 1];//获取 script 节点第n-1个属性
                return {//获取o函数返回的值
                        l: w,
                        z: o(v, "zIndex", -1),
                        o: o(v, "opacity", .8),
                        c: o(v, "color", "179,212,255"),
                        n: o(v, "count", 150)//根据下文猜测为出现的点数量
                }
        }

        // k函数 获取当前文档环境的高度和宽度  并在之后赋予canvas
        function k() {

                //       canvas的宽度 = 窗口宽度  ||          存在有宽度的根节点           ||          存在有宽度的body，
                r = u.width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
                        //  并且把canvas的高度设为窗口高度  ||           存在有高度的根节点            ||     存在有高度的body 
                        n = u.height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
        }

        function b() {
                e.clearRect(0, 0, r, n);//重新绘制canvas
                var w = [f].concat(t);//把下文中的初始化的  f对象  与下文循环中的  t对象  进行连接并赋予w
                var x, v, A, B, z, y;
                t.forEach(function (i) {//遍历下文创建的  t数组,i为当前遍历的t的子元素
                        i.x += i.xa, 
                        i.y += i.ya, 
                        i.xa *= i.x > r || i.x < 0 ? -1 : 1, 
                        i.ya *= i.y > n || i.y < 0 ? -1 : 1, 
                        e.fillRect(i.x - 0.5, i.y - 0.5, 1, 1);
                        for (v = 0; v < w.length; v++) {//w为新合成的数组
                                x = w[v];
                                if (i !== x && null !== x.x && null !== x.y) {
                                        B = i.x - x.x, //得到2点之间x值的差
                                        z = i.y - x.y, //得到2点之间y值的差
                                        y = B * B + z * z;//得到2点之间的距离的平方
                                        y < x.max && (x === f && y >= x.max / 2 && (i.x -= 0.03 * B, i.y -= 0.03 * z), //x为当前遍历的子元素
                                        A = (x.max - y) / x.max, 
                                        e.beginPath(), 
                                        e.lineWidth = A / 2, 
                                        e.strokeStyle = "rgba(" + s.c + "," + (A + 0.2) + ")", 
                                        e.moveTo(i.x, i.y), 
                                        e.lineTo(x.x, x.y), 
                                        e.stroke())
                                }
                        }
                        w.splice(w.indexOf(i), 1)
                }), m(b)
        }

        // 以下为创建canvas节点
        var u = document.createElement("canvas"),
                s = l(),//得到l函数的返回值
                c = "c_n" + s.l,//结合上下文，此处为获取script节点的长度
                e = u.getContext("2d"),//获取canvas的上下文
                r, n, m = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (i) {
                        window.setTimeout(i, 1000 / 45)//设置浏览器刷新，这是兼容写法
                },
                a = Math.random,//a赋值随机数方法
                f = {//初始化对象f的值
                        x: null,
                        y: null,
                        max: 20000
                };
        u.id = c;//给canvas设置id
        u.style.cssText = "position:fixed;top:0;left:0;z-index:" + s.z + ";opacity:" + s.o; //设置canvas的css样式
        j("body")[0].appendChild(u);//在body下添加canvas节点
        k(),//k函数获取容器高度及宽度
                window.onresize = k;//onresize 窗口调整大小时 执行k函数重新获取容器高度及宽度
        window.onmousemove = function (i) {//鼠标移动时获取x坐标及y坐标
                i = i || window.event, f.x = i.clientX, f.y = i.clientY
        }, window.onmouseout = function () {//鼠标移出窗口时重置x，y的值为null
                f.x = null, f.y = null
        };
        for (var t = [], p = 0; s.n > p; p++) {//新建数组t, 在  p<上文设定的点的数量 时执行
                var h = a() * r,//r为调用浏览器重绘，返回一个非0的id，乘与 a函数返回的随机数，得到一个随机的id值
                        g = a() * n,//同上
                        q = 2 * a() - 1,//随机得到每个点移动的X值
                        d = 2 * a() - 1;//随机得到每个点移动的y值
                t.push({//吧得到的随机的4个数字以对象的形式加进t数组
                        x: h,
                        y: g,
                        xa: q,
                        ya: d,
                        max: 6000
                })
        }
        setTimeout(function () {
                b()
        }, 100)
}();