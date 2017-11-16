<?php
namespace app\wx\controller;
use think\Db;
class Index
{
    public $client;
    public $wc;
    public function __construct(){
        //获取微信配置信息
        $this->wc = Db::name('wx_set')->find();
    }
    public function index(){
        define("TOKEN", $this->wc['token']);
        $arr=$this->wc;
        $this->valid();
    }

    public function valid()
    {
        if(!empty($_GET["echostr"])){
             $echoStr = $_GET["echostr"];
        }
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }

    }
    public function checkSignature()
    {
        if($this->wc['wait_access'] == 0) {
            // you must define TOKEN by yourself
            if (!defined("TOKEN")) {
                throw new Exception('TOKEN is not defined!');
            }
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $token = TOKEN;
            $tmpArr = array($token, $timestamp, $nonce);
            // use SORT_STRING rule
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
            if ($tmpStr == $signature) {
                ob_clean();
                echo $_GET['echostr'];
                exit;
            } else {
                return false;
            }
        }else{
            $this->responseMsg();
        }
    }
    public function responseMsg(){
        $postStr = file_get_contents("php://input");
        if (empty($postStr)){
            exit("");
        }
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        //点击菜单拉取消息时的事件推送
        /*
         * 1、click：点击推事件
         * 用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南）
         * 并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
         */
        if($postObj->MsgType == 'event' && $postObj->Event == 'CLICK'){
            $keyword = trim($postObj->EventKey);
        }
        if(empty($keyword)){
            // 关注回复
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                    </xml>";
            $contentStr = $this->wc['concern'];
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            exit($resultStr);
        }
        // 图文回复
        $wx_img = Db::name('wx_set')->where("token like '%$keyword%'")->find();
        if($wx_img) {
            $textTpl = "<xml>
                              <ToUserName><![CDATA[%s]]></ToUserName>
                              <FromUserName><![CDATA[%s]]></FromUserName>
                              <CreateTime>%s</CreateTime>
                              <MsgType><![CDATA[%s]]></MsgType>
                              <ArticleCount><![CDATA[%s]]></ArticleCount>
                              <Articles>
                                  <item>
                                    <Title><![CDATA[%s]]></Title> 
                                    <Description><![CDATA[%s]]></Description>
                                    <PicUrl><![CDATA[%s]]></PicUrl>
                                    <Url><![CDATA[%s]]></Url>
                                  </item>                               
                              </Articles>
                         </xml>";
            if(substr($wx_img['pic'],0,4)=='http'){
                $imgUrl = $wx_img['pic'];
            }else{
                $imgUrl = 'http://'.$_SERVER['HTTP_HOST'].'/public'.$wx_img['pic'];
            }
            $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,'news','1',$wx_img['title'],$wx_img['desc'], $imgUrl, $wx_img['url']);
            exit($resultStr);
        }


        // 文本回复
        $wx_text = Db::name('wx_set')->where("token like '%$keyword%'")->find();
        if($wx_text) {
            $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                        </xml>";
            $contentStr = $wx_text['text'];
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
            exit($resultStr);
        }
        // 默认回复
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                    </xml>";
        $contentStr = $this->wc['default'];
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
        exit($resultStr);
    }
}