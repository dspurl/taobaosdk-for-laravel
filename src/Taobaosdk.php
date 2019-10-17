<?php
/**
 * 淘宝客API2019年10月17号
 * 
 * 参数请参考淘宝客API：https://open.taobao.com/api.htm?docId=31137&docType=2
 * 如淘宝客API有的参数，本SDK没有出现的，则为淘宝客API已经去除该参数，如SDK写着必传的，以SDK为准，如SDK未写必传的,请以淘宝客API为准
 */
namespace Dspurl\Taobaosdk;
use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;
use Dspurl\Taobaosdk\top\TopClient;
use Dspurl\Taobaosdk\top\request\TbkSpreadGetRequest;
use Dspurl\Taobaosdk\top\domain\TbkSpreadRequest;
use Dspurl\Taobaosdk\top\request\TbkItemInfoGetRequest;
use Dspurl\Taobaosdk\top\request\TbkShopGetRequest;
use Dspurl\Taobaosdk\top\request\TbkContentGetRequest;
use Dspurl\Taobaosdk\top\request\TbkDgMaterialOptionalRequest;
use Dspurl\Taobaosdk\top\request\TbkDgOptimusMaterialRequest;
use Dspurl\Taobaosdk\top\request\TbkActivitylinkGetRequest;
use Dspurl\Taobaosdk\top\request\TbkItemRecommendGetRequest;
include "TopSdk.php";
class Taobaosdk
{
    /**
     * @var SessionManager
     */
    protected $session;
    /**
     * @var Repository
     */
    protected $config;
    /**
     * Packagetest constructor.
     * @param SessionManager $session
     * @param Repository $config
     */
    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }

    /**
     * 长链转短链(普通链接转淘客地址)
     * @param String $url                   //原始url, 只支持uland.taobao.com，s.click.taobao.com， ai.taobao.com，temai.taobao.com的域名转换，否则判错
     * 
     */
    public function TbkSpreadGetRequest($url)
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkSpreadGetRequest; 
        $requests = new TbkSpreadRequest;
        $requests->url=$url;
        $req->setRequests(json_encode($requests));
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 淘宝客商品详情查询(简版) 
     * @param String $num_iids              //商品ID串，用,分割，最大40个
     * @param String $ip                    //ip地址，影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
     * @param Number $platform              //链接形式：1：PC，2：无线，默认：１
     * 
     */
    public function TbkItemInfoGetRequest(
        $num_iids,
        $ip=null,
        $platform=null
    )
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkItemInfoGetRequest; 
        $req->setNumIids($num_iids);
        $req->setPlatform($platform?$platform:$config['platform']);
        $req->setIp($ip);
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 店铺搜索 
     * @param String $q                     //查询词
     * @param Number $page_no               //第几页，默认1，1~100
     * @param Number $page_size             //页大小，默认20，1~100
     * @param String $sort                  //排序_des（降序），排序_asc（升序），佣金比率（commission_rate）， 商品数量（auction_count），销售总数量（total_auction）
     * @param String $fields                //需返回的字段列表
     * @param Number $platform              //链接形式：1：PC，2：无线，默认：１
     * @param Boolean $is_tmall             //是否商城的店铺，设置为true表示该是属于淘宝商城的店铺，设置为false或不设置表示不判断这个属性
     * 
     * 
     */
    public function TbkShopGetRequest(
        $q,
        $page_no=null,
        $page_size=null,
        $sort=null,
        $fields=null,
        $platform=null,
        $is_tmall=null
    )
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkShopGetRequest; 
        $req->setFields($fields?$fields:"user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
        $req->setQ($q);
        $req->setSort($sort);
        $req->setIsTmall($is_tmall);
        $req->setPlatform($platform?$platform:$config['platform']);
        $req->setPageNo($page_no);
        $req->setPageSize($page_size);
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 图文内容输出 
     * @param Number $type                  //内容类型，1:图文、2: 图集、3: 短视频（必传）
     * @param Number $count                 //表示期望获取条数
     * @param Number $image_width           //图片宽度
     * @param Number $image_height          //图片高度
     * @param Number $adzone_id             //推广位id，mm_xx_xx_xx pid三段式中的第三段。adzone_id需属于appKey拥有者
     * 
     */
    public function TbkContentGetRequest($type=null,$count=null,$image_width=null,$image_height=null,$adzone_id=null)
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkContentGetRequest; 
        $req->setAdzoneId($adzone_id?$adzone_id:$config['adzoneId']);
        $req->setType($type);
        $req->setCount($count);
        $req->setImageWidth($image_width);
        $req->setImageHeight($image_height);
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 物料搜索
     * @param String $q                     //商品筛选-查询词
     * @param Number $material_id           //官方的物料Id(详细物料id见：https://tbk.bbs.taobao.com/detail.html?appId=45301&postId=8576096)，不传时默认为2836
     * @param Number $page_size             //页大小，默认20，1~100
     * @param Number $page_no               //第几页，默认：1
     * @param String $sort                  //排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi），价格（price）
     * @param Number $platform              //链接形式：1：PC，2：无线，默认：１
     * @param Boolean $has_coupon           //优惠券筛选-是否有优惠券。true表示该商品有优惠券，false或不设置表示不限
     * @param Boolean $include_good_rate    //商品筛选-好评率是否高于行业均值。True表示大于等于，false或不设置表示不限
     * @param Boolean $include_rfd_rate     //商品筛选(特定媒体支持)-退款率是否低于行业均值。True表示大于等于，false或不设置表示不限
     * @param Boolean $need_free_shipment   //商品筛选-是否包邮。true表示包邮，false或不设置表示不限
     * @param Boolean $is_overseas          //商品筛选-是否海外商品。true表示属于海外商品，false或不设置表示不限
     * @param Boolean $is_tmall             //商品筛选-是否天猫商品。true表示属于天猫商品，false或不设置表示不限
     * @param Number $npx_level             //商品筛选-牛皮癣程度。取值：1不限，2无，3轻微
     * @param String $itemloc               //商品筛选-所在地
     * @param String $cat                   //商品筛选-后台类目ID。用,分割，最大10个，该ID可以通过taobao.itemcats.get接口获取到，需要获取接口权限
     * @param Number $adzone_id             //mm_xxx_xxx_12345678三段式的最后一段数字
     * @param String $ip                    //ip参数影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
     * @param String $device_encrypt        //智能匹配-设备号加密类型：MD5
     * @param String $device_value          //智能匹配-设备号加密后的值（MD5加密需32位小写）
     * @param String $device_type           //智能匹配-设备号类型：IMEI，或者IDFA，或者UTDID（UTDID不支持MD5加密），或者OAID
     * @param Boolean $need_prepay          //商品筛选-是否加入消费者保障。true表示加入，false或不设置表示不限
     * @param Boolean $include_pay_rate_30  //商品筛选(特定媒体支持)-成交转化是否高于行业均值。True表示大于等于，false或不设置表示不限
     * 
     * 
     */
    public function TbkDgMaterialOptionalRequest(
        $q,
        $material_id=null,
        $page_size=null,
        $page_no=null,
        $sort=null,
        $platform=null,
        $has_coupon=null,
        $include_good_rate=null,
        $include_rfd_rate=null,
        $need_free_shipment=null,
        $is_overseas=null,
        $is_tmall=null,
        $npx_level=null,
        $itemloc=null,
        $cat=null,
        $adzone_id=null,
        $ip=null,
        $device_encrypt=null,
        $device_value=null,
        $device_type=null,
        $need_prepay=null,
        $include_pay_rate_30=null
    )
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkDgMaterialOptionalRequest; 
        $req->setPageSize($page_size);
        $req->setPageNo($page_no);
        $req->setPlatform($platform?$platform:$config['platform']);
        $req->setIsOverseas($is_overseas);
        $req->setIsTmall($is_tmall);
        $req->setSort($sort);
        $req->setItemloc($itemloc);
        $req->setCat($cat);
        $req->setQ($q);
        $req->setMaterialId($material_id);
        $req->setHasCoupon($has_coupon);
        $req->setIp($ip);
        $req->setAdzoneId($adzone_id?$adzone_id:$config['adzoneId']);
        $req->setNeedFreeShipment($need_free_shipment);
        $req->setNeedPrepay($need_prepay);
        $req->setIncludePayRate30($include_pay_rate_30);
        $req->setIncludeGoodRate($include_good_rate);
        $req->setIncludeRfdRate($include_rfd_rate);
        $req->setNpxLevel($npx_level);
        $req->setDeviceEncrypt($device_encrypt);
        $req->setDeviceValue($device_value);
        $req->setDeviceType($device_type);
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 物料精选
     * @param Number $material_id           //官方的物料Id(详细物料id见：https://tbk.bbs.taobao.com/detail.html?appId=45301&postId=8576096)
     * @param Number $adzone_id             //推广位id，mm_xx_xx_xx pid三段式中的第三段。adzone_id需属于appKey拥有者
     * @param Number $page_size             //页大小，默认20，1~100
     * @param Number $page_no               //第几页，默认：1
     * @param String $device_value          //智能匹配-设备号加密后的值（MD5加密需32位小写）
     * @param String $device_encrypt        //智能匹配-设备号加密类型：MD5
     * @param String $device_type           //智能匹配-设备号类型：IMEI，或者IDFA，或者UTDID（UTDID不支持MD5加密）
     * @param String $content_source        //内容专用-内容渠道信息
     */
    public function TbkDgOptimusMaterialRequest($material_id,$adzone_id=null,$page_size=null,$page_no=null,$device_value=null,$device_encrypt=null,$device_type=null,$content_source=null)
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkDgOptimusMaterialRequest; 
        $req->setPageSize($page_size);
        $req->setAdzoneId($adzone_id?$adzone_id:$config['adzoneId']);
        $req->setPageNo($page_no);
        $req->setMaterialId($material_id);
        $req->setDeviceValue($device_value);
        $req->setDeviceEncrypt($device_encrypt);
        $req->setDeviceType($device_type);
        $req->setContentSource($content_source);
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 官方活动转链
     * @param Number $promotion_scene_id    //官方活动ID，从官方活动页获取
     * @param Number $adzone_id             //推广位id，mm_xx_xx_xx pid三段式中的第三段。adzone_id需属于appKey拥有者
     * @param String $sub_pid               //媒体平台下达人的淘客pid
     * @param String $relation_id           //渠道关系ID，仅适用于渠道推广场景
     * @param String $union_id              //自定义输入串，英文和数字组成，长度不能大于12个字符，区分不同的推广渠道
     * @param Number $platform              //1：PC，2：无线，默认：１
     */
    public function TbkActivitylinkGetRequest($promotion_scene_id,$adzone_id=null,$sub_pid=null,$relation_id=null,$union_id=null,$platform=null)
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey'];
        $c->secretKey = $config['secretKey']; 
        $req = new TbkActivitylinkGetRequest; 
        $req->setPlatform($platform?$platform:$config['platform']);
        $req->setUnionId($union_id);
        $req->setAdzoneId($adzone_id?$adzone_id:$config['adzoneId']);
        $req->setPromotionSceneId($promotion_scene_id);
        $req->setSubPid($sub_pid);
        $req->setRelationId($relation_id);
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * 商品关联推荐
     * @param Number $num_iid   //商品Id
     * @param Number $platform  //链接形式：1：PC，2：无线，默认：１
     * @param String $fields    //需返回的字段列表
     * @param Number $count     //返回数量，默认20，最大值40
     */
    public function TbkItemRecommendGetRequest($num_iid,$platform=null,$fields=null,$count=null)
    {
        $config=$this->config->get('taobaosdk');
        $c = new TopClient; 
        $c->appkey = $config['appkey']; 
        $c->secretKey = $config['secretKey']; 
        $req = new TbkItemRecommendGetRequest; 
        $req->setFields($fields?$fields:'num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url'); 
        $req->setNumIid($num_iid); 
        $req->setCount($count); 
        $req->setPlatform($platform?$platform:$config['platform']); 
        $resp = $c->execute($req);
        return $this->xmltoArray($resp);
    }

    /**
     * XML转数组
     * @param string $data
     */
    private function xmltoArray($data)
    {
        return json_decode(json_encode($data),true);
    }
}