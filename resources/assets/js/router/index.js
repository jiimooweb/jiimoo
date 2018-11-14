import Vue from 'vue'
import Router from 'vue-router'
import login from '@/components/login'
import userList from '@/components/userList'
import userManage from '@/components/userManage'
import userSet from '@/components/userSet'
import power1 from '@/components/power1'
import typeList from '@/components/userManage/content/typeList'
import content from '@/components/userManage/content/content'
import articleType from '@/components/userManage/content/articleType'
import productsList from '@/components/userManage/content/productsList'
import productsType from '@/components/userManage/content/productsType'
import Carousel from '@/components/userManage/content/Carousel'
import marketing from '@/components/userManage/marketing/marketing'
import queue from '@/components/userManage/marketing/queue'
import vip from '@/components/userManage/marketing/vip'
import reservation from '@/components/userManage/marketing/Reservation/reservation'
import votes from '@/components/userManage/marketing/votes'
import coupons from '@/components/userManage/marketing/coupons'
import ECommerce from '@/components/userManage/ECommerce/ECommerce'
import eating from '@/components/userManage/ECommerce/eating'
import shoping from '@/components/userManage/ECommerce/shoping'
import Personnel from '@/components/userManage/Personnel/Personnel'
import PersonnelType from '@/components/userManage/Personnel/PersonnelType'
import PersonnelList from '@/components/userManage/Personnel/PersonnelList'
import manageSet from '@/components/manageSet'
import wxSet from '@/components/wxSet'
import systemSet from '@/components/systemSet/systemSet'
import moduleSet from '@/components/systemSet/moduleSet'
import XCXShowList from '@/components/systemSet/XCXShowList'
import user from '@/components/user'
import fans from '@/components/userManage/fans'

Vue.use(Router)

export default new Router({
	routes: [
		{path: '/',redirect: '/userList',meta: {CName: '首页'}},
		{path: '/userList',name: 'userList',component: userList,meta: {CName: '首页'}},
		{path: '/manageSet',name: 'manageSet',component: manageSet,meta: {CName: '模块管理'}},
		{path: '/login',name: 'login',component: login,meta: {CName: '登录'}},
		{path: '/userManage',name: 'userManage',redirect:'/userManage/p1',component: userManage,meta: {CName: '管理页面'},
			// userManage start
			children: [
				{path: 'p1',name: 'power1',component: power1},
				//内容
				{path: 'content',name: 'content',meta: {CName: '内容管理'},component:content,children:[
					{path: 'articles',name: 'articles',meta: {CName: '文章管理'},component: typeList},
					{path: 'productsList',name: 'productsList',meta: {CName: '产品管理'},component: productsList},
					{path: 'Carousel',name: 'Carousel',meta: {CName: '轮播图管理'},component: Carousel}
				]},
				{path: 'content/articleType',name: 'articleType',meta: {CName: '文章分类'},component: articleType},
				{path: 'content/productsType',name: 'productsType',meta: {CName: '产品分类'},component: productsType},
				//粉丝管理
				{path:'fans',name:'fans',meta:{CName:'粉丝管理'},component:fans},
				//营销
				{path: 'marketing',name: 'marketing',meta: {CName: '营销管理'},component:marketing,children:[
					{path:'queue',name: 'queue',meta: {CName: '排队管理'},component: queue},
					{path:'vip',name: 'vip',meta: {CName: '会员管理'},component: vip},
					{path:'coupons',name: 'coupons',meta: {CName: '优惠券'},component: coupons},
					{path:'reservation',name: 'reservation',meta: {CName: '预约'},component: reservation},
					{path:'votes',name: 'votes',meta: {CName: '投票'},component: votes}
				]},
				//电商管理
				{path: 'ECommerce',name: 'ECommerce',meta: {CName: '电商管理'},component:ECommerce,children:[
					{path:'eating',name: 'eating',meta: {CName: '点餐管理'},component: eating},
					{path:'shoping',name: 'shoping',meta: {CName: '商城管理'},component: shoping}
				]},
				//人员管理
				{path: 'Personnel',name: 'Personnel',component: Personnel,meta:{CName:'人员管理'},children:[
					{path:'PersonnelType',name:'PersonnelType',meta: {CName: '人员类别'},component:PersonnelType},
					{path:'PersonnelList',name:'PersonnelList',meta: {CName: '人员列表'},component:PersonnelList}
				]},
				//微信设置
				{path: 'wxset',name: 'wxset',component: wxSet,meta: {CName: '微信设置'}},
				//个人信息
				{path: 'user',name: 'user',component: user,meta: {CName: '个人设置'}}
			]
		},
		// userManage end
		{path: '/userSet',name: 'userSet',component: userSet,meta: {CName: '权限管理'}},
		//管理员设置
		{path: '/systemSet',name: 'systemSet',component: systemSet,meta: {CName: '公共设置'}},
		{path: '/moduleSet',name:'moduleSet',meta: {CName: '模板设置'},component:moduleSet},
		{path: '/XCXShowList',name:'XCXShowList',meta: {CName: '小程序展示列表'},component:XCXShowList},
		
	],
	
})
