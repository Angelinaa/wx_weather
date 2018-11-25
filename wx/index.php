<?php
	$nonce=$_GET['nonce'];
	$token='haha';
	$timestamp=$_GET['timestamp'];
	$echostr=$_GET['echostr'];
	$signature=$_GET['signature'];

	$array=array();
	$array=array($nonce,$timestamp,$token);
	sort($array);
	
	$str=sha1(implode($array));
	if($str==$signature&&$echostr){
     	echo $echostr; 
      	exit;      
    }
//获取微信推送过来的post数据
$postArr=$GLOBALS['HTTP_RAW_POST_DATA'];
//处理消息类型，并设置回复类型和内容
$postObj=simplexml_load_string($postArr);
//判断数据包是否是订阅的事件推送
if(strtolower($postObj->MsgType)=='event'){
  //如果是subscribe事件
 	if(strtolower($postObj->Event=='subscribe')){
      //回复用户消息（文本格式）
     	$toUser=$postObj->FromUserName;
      	$fromUser=$postObj->ToUserName;
      	$time=time();
      	$msgType='text';
      	$content='欢迎关注我,嘻嘻嘻！';
      	$template="<xml>
        				<ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>";
      	$info=sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
      	echo $info;      
    }   
}
//判断数据包是否是文本
if(strtolower($postObj->MsgType)=='text'){
  //接收文本消息
 	$content=$postObj->Content;
  	$keyword = trim($postObj->Content);
  	$str = mb_substr($keyword,-2,2,"UTF-8");
    $str_key = mb_substr($keyword,0,-2,"UTF-8");
  //回复用户消息
  	$fromUsername = $postObj->FromUserName;
    $toUsername = $postObj->ToUserName;
  	$time=time();
  	$msgType='text';
  	//$content='您发送的是：'.$content;
  	//$template="<xml>
    			//<ToUserName><![CDATA[%s]]></ToUserName>
                //<FromUserName><![CDATA[%s]]></FromUserName>
                //<CreateTime>%s</CreateTime>
                //<MsgType><![CDATA[%s]]></MsgType>
                //<Content><![CDATA[%s]]></Content>
                //</xml>";
  	//$info=sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
  	//echo $info;
  
  	$textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";   
    if($str == '天气' && !empty($str_key)){
           $contentStr ="【".$str_key. "天气预报】\n"."2018年11月23日 15时发布"."\n\n实时天气\n"."晴 0℃~8℃ 南风3-4级"."\n\n温馨提示：天气寒冷，建议穿着毛衣、棉服、羽绒服、长裤等温暖冬季服装。"."\n\n明天\n"."晴 0℃~6℃ 东南风2-3级"."\n\n后天\n"."晴转多云 0℃~5℃ 东南风2-3级转3-4级";
    } else {
            $contentStr = "感谢您关注【冷冷smile】"."\n"."微信号：lengsmiling"."\n"."谢谢关注！更多功能有待开发哟~"."\n"."目前平台功能如下："."\n"."【1】 查天气，如输入：苏州天气"."\n"."更多内容，敬请期待...";
    }
    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    echo $resultStr;
}