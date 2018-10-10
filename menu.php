<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 10:21
 */

/**
 * @return mixed
 * 获取access_token
 */
function get_token()
{
    $appID = 'wx6948cfcaaa7ae74b';
    $appSecret = 'c30556876f75c467651ec8b4ac62f4b8';

    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appID}&secret={$appSecret}";

    //使用php curl函数提交请求,获取token

    //1.初始化
    $ch = curl_init();

    //2.设置请求变量
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //3.执行
    $output = curl_exec($ch);

    //4.释放
    curl_close($ch);

    //5.获取access_token
    $array = json_decode($output, true);
    $access_token = $array['access_token'];
    return $access_token;
}


function create_menu()
{
    $access_token = get_token();
    echo $access_token;echo "<br>";
    $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
    $jsonmenu = '{
     "button":[
      {
           "name":"合同管理",
           "sub_button":[
           {    
               "type":"view",
               "name":"合同首页",
               "url":"http://3a4a44aa.ngrok.io/sdk-source/wechat-contract/index.html"
            },
            {
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
               "type":"click",
               "name":"为我们点赞",
               "key":"V1001_GOOD"
            }]
       }]
    }';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonmenu);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);
    curl_close($ch);
    echo $output;
    return $output;
}

function deleteMenu()
{
    $token = get_token();

    $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$token}";

    //1.初始化
    $ch = curl_init();

    //2.设置请求变量
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //3.执行
    $output = curl_exec($ch);

    //4.释放
    curl_close($ch);

    echo $output;
}



create_menu();
//deleteMenu();


