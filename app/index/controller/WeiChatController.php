<?php
/**
 * Created by PhpStorm.
 * User: dwq
 * Date: 2018/10/4
 * Time: 17:24
 */

namespace app\index\controller;


use swooleFrame\frameTools\Request;

class WeiChatController
{
    public static $weiChat = [
        'AppID'=>'wx7f10536bb0d43c7c',
        'AppSecret'=>'ac2005f6ff245031ff0d056dd9e23aa2',
        'token'=>'dong',
    ];

    public function valid()
    {
        $echoStr = Request::get("echostr");
        if($echoStr!==''){
            //如果接收到了echostr就是在验证签名
            if($this->checkSignature()){
                echo $echoStr;
            }
            echo '1';
        }
        else{
            //处理消息回复
            $this->responseMsg();
        }

    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        //$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //php7版本后的改进,swoole专用这个来获取xml数据包
        $postStr = Request::getRawContent();

        var_dump($postStr);
        file_put_contents(__DIR__.'/b.txt',$postStr);

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";


            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "Welcome to wechat world!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }

        }else {
            echo "";
            return ;
        }
    }

    //校验方法
    private function checkSignature()
    {
        // you must define TOKEN by yourself

        $signature = Request::get("signature");
        $timestamp = Request::get("timestamp");
        $nonce = Request::get('nonce');

        //var_dump($signature,$timestamp,$nonce);
        $token = self::$weiChat['token'];
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );


        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }


}