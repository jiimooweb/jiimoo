<?php

namespace App\Api\Controllers\Booking;

// use App\Models\Foods\Cate;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
// use App\Http\Requests\Foods\CateRequest;

class CategoriesController extends Controller
{
    
    public function index() 
    {
        $json_string = '{
            "categories": [{
                "id": 4632,
                "name": "理发",
                "data": {},
                "productsCount": 2,
                "coverImage": "https://nzr2ybsda.qnssl.com/images/24978/FkGlOCq6KBSMk_MRll660rVUh8xS.jpg?imageMogr2/strip/thumbnail/500x1000%3E/quality/90!/interlace/1/format/jpeg"
            }, {
                "id": 4633,
                "name": "造型",
                "data": {},
                "productsCount": 1,
                "coverImage": "https://nzr2ybsda.qnssl.com/images/24978/Fpb0KJRBvf3A_7HH2tnLaUfXxNpt.jpg?imageMogr2/strip/thumbnail/500x1000%3E/quality/90!/interlace/1/format/jpeg"
            }]
        }';
        $obj = json_decode($json_string);
        return response()->json(['status' => 'success', 'data' => $obj]);
    }
    
    public function currentCategoryList() 
    {

        // dd(request()->page);
        $json_string = '{"status":200,"meta":{"devMessage":null,"errorKey":null,"userMessage":{"i18n":{"key":null,"interpolations":null},"plain":null}},"data":{"paginationMeta":{"currentPage":1,"previousPage":null,"nextPage":null,"perPage":15,"totalPages":1,"totalCount":2},"products":[{"id":26225,"name":"总监设计师洗剪烫染 单人套餐","description":"烫发套餐：\n总监设计师洗剪吹\n日本菲灵特柔Spa烫\n\n染发套餐：\n首席设计师洗剪吹\n德国施华寇染发\n\n（以上2选1，不可重复）\n","detail":{"type":"BlockComponent","items":null},"detailEnabled":false,"picture":[{"url":"https://nzr2ybsda.qnssl.com/images/24978/FkGlOCq6KBSMk_MRll660rVUh8xS.jpg?imageMogr2/strip/thumbnail/2000x3000%3E/quality/90!/interlace/1/format/jpeg","thumbnailUrl":"https://nzr2ybsda.qnssl.com/images/24978/FkGlOCq6KBSMk_MRll660rVUh8xS.jpg?imageMogr2/strip/thumbnail/500x1000%3E/quality/90!/interlace/1/format/jpeg"}],"hasNoPayment":null,"variations":[{"price":49900,"name":"default","quantity":-1,"id":51873}],"categories":["理发"],"staffCount":3},{"id":26226,"name":"设计师洗剪吹 单人1次","description":"总监设计师洗剪吹1次\n施华蔻深层护理1次\n头部头发按摩1次\n（以上3选2，不可重复选择）\n","detail":{"items":[{"storageKey":"images/127232/FkYAsyYfSSJ6mgRh4wmH7yI9SIxd.jpeg","thumbUrl":"!","h":648,"url":"!","s":37373,"w":640,"format":"jpg","type":"Image","id":null,"storage":"qn"},{"type":"RichText","id":null,"value":"\u003cp\u003e\u0026nbsp; \u0026nbsp; \u0026nbsp;不错的一家店，强烈推荐。\u003c/p\u003e\u003cp\u003e\u0026nbsp; \u0026nbsp; \u0026nbsp;\u003c/p\u003e","defaultValue":null,"backupValue":null,"version":null},{"storageKey":"images/127232/FiT-d3QaGtzqaXF_LFUX0SHuYt41.png","thumbUrl":"!","h":525,"url":"!","s":40080,"w":500,"format":"jpg","type":"Image","id":null,"storage":"qn"},{"type":"RichText","id":null,"value":"\u003cp\u003e\u0026nbsp; \u0026nbsp; \u0026nbsp; \u0026nbsp;简单理解啊接，是厄贾兰撒娇！ 绝对绿色 i 金额上来得瑟解决电视里第三方姐。\u003c/p\u003e","defaultValue":null,"backupValue":null,"version":null}]},"detailEnabled":true,"picture":[{"url":"https://nzr2ybsda.qnssl.com/images/24978/FuJCT0-qSKZFQopQILYN-Nj4r5Jq.jpg?imageMogr2/strip/thumbnail/2000x3000%3E/quality/90!/interlace/1/format/jpeg","thumbnailUrl":"https://nzr2ybsda.qnssl.com/images/24978/FuJCT0-qSKZFQopQILYN-Nj4r5Jq.jpg?imageMogr2/strip/thumbnail/500x1000%3E/quality/90!/interlace/1/format/jpeg"}],"hasNoPayment":null,"variations":[{"price":19900,"name":"default","quantity":-1,"id":153953}],"categories":["理发"],"staffCount":2}]}} ';
        $obj = json_decode($json_string);
        return response()->json(['status' => 'success', 'data' => $obj]);
    }

    public function store(CateRequest $requset) 
    {   
        // $cate = Cate::create(request(['name']));
        // if($cate) {
        //     return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $cate]);               
        // }

        // return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        // $cate = Cate::withCount('products')->find(request()->cate);             
        // $status = $cate ? 'success' : 'error';
        // return response()->json(['status' => $status, 'data' => $cate]);
    }

    public function update(CateRequest $requset) 
    {
        // if(Cate::where('id', request()->cate)->update(request(['name']))) {
        //     return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        // }

        // return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
         // TODO:判断删除权限
        // if(Cate::where('id', request()->cate)->delete()) {
        //     return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        // }
        
        // return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }
}
