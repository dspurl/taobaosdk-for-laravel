## dspurl/taobaosdk


laravel5.8封装taobao-sdk-PHP的简单实现，功能不多，只满足2019年10月的淘宝客API

集成的API接口列表
- [taobao.tbk.item.recommend.get](https://open.taobao.com/api.htm?docId=24517&docType=2 "taobao.tbk.item.recommend.get")	//商品关联推荐
- [taobao.tbk.activitylink.get](https://open.taobao.com/api.htm?docId=41918&docType=2 "taobao.tbk.activitylink.get")	//官方活动转链
- [taobao.tbk.dg.optimus.material](https://open.taobao.com/api.htm?docId=33947&docType=2 "taobao.tbk.dg.optimus.material")	//物料精选
- [taobao.tbk.dg.material.optional](https://open.taobao.com/api.htm?docId=35896&docType=2 "taobao.tbk.dg.material.optional")	//物料搜索
- [taobao.tbk.content.get](https://open.taobao.com/api.htm?docId=31137&docType=2 "taobao.tbk.content.get")	//图文内容输出
- [taobao.tbk.shop.get](https://open.taobao.com/api.htm?docId=24521&docType=2 "taobao.tbk.shop.get")	//店铺搜索
- [taobao.tbk.item.info.get](https://open.taobao.com/api.htm?docId=24518&docType=2 "taobao.tbk.item.info.get")	//淘宝客商品详情查询(简版)
- [taobao.tbk.spread.get](https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.19f924adkQTdTG&docId=27832&docType=2 "taobao.tbk.spread.get")	//长链转短链 

## 安装

###### 安装dspurl/taobaosdk
```shell
$ composer require dspurl/taobaosdk
```
comfig/app.php修改providers和aliases
```php
'providers' => [
	...
	Dspurl\Taobaosdk\TaobaosdkServiceProvider::class,
	...
]
'aliases' => [
	...
	'Taobaosdk' => Dspurl\Taobaosdk\Facades\Taobaosdk::class
	...
]
```

###### 执行
```shell
$ composer dump-autoload
```
###### 发布资源文件
```shell
php artisan vendor:publish --provider="Dspurl\Taobaosdk\TaobaosdkServiceProvider"
```
###### 配置config\taobaosdk.php
```php
return [
    'appkey' => '淘宝客appkey',
    'secretKey' => '淘宝客secretKey',
    'adzoneId' => '推广位id',	//mm_xx_xx_xx pid三段式中的第三段。adzone_id需属于appKey拥有者
    'platform' => 2,	//链接形式：1：PC，2：无线，默认：１
];
```
###### 修改routes/web.php
```php
Route::get('/test','TestController@test');
```
###### 添加控制器TestController.php
```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Taobaosdk;
class TestController extends Controller
{
    public function test(Request $request){
        // $data=Taobaosdk::TbkItemRecommendGetRequest(558044672087);   //商品关联推荐
        // $data=Taobaosdk::TbkActivitylinkGetRequest(1570638662450,197164077); //官方活动转链
        // $data=Taobaosdk::TbkDgOptimusMaterialRequest(3756);  //物料精选
        // $data=Taobaosdk::TbkDgMaterialOptionalRequest('女装');   //物料搜索
        // $data=Taobaosdk::TbkContentGetRequest(1);    //图文内容输出
        // $data=Taobaosdk::TbkShopGetRequest('女装'); //店铺搜索
        // $data=Taobaosdk::TbkItemInfoGetRequest('562551658548'); //获取商品详情
        // $data=Taobaosdk::TbkSpreadGetRequest('https://s.click.taobao.com/eptgOyv'); //长链转短链
        // print_r($data);
        // exit;
    }
}
```
###### 访问：http://配置的站点/test

## License

Laravel Passport is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
