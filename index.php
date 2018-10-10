<?php
class Request
{
    public function http_curl($url,$type='get',$res='json',$arr=''){
        //1.初始化
        $ch = curl_init();
        //2.设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if( $type == 'post' ){
            //如果是post请求的话,设置post的一些参数
            curl_setopt($ch , CURLOPT_POST , 1);
            curl_setopt($ch , CURLOPT_POSTFIELDS, $arr);
        }
        //3.执行
        $result = curl_exec($ch);
        //4.关闭
        curl_close($ch);
        if( curl_errno($ch)){
            //打印错误日志
            var_dump(curl_error($ch));
        }
        if( $res == 'json' ){
            //将json转化成数组的形式
            $result = json_decode($result , TRUE);
        }
        return $result;
    }


    /**
     * @return mixed
     * 发起get请求,用于获取access_token
     */
    public function getWxAccessToken(){
        //这里使用session来暂时保存access_token，可以使用mysql数据库来保存数据
        if( isset($_SESSION['access_token']) && $_SESSION['access_token'] && ($_SESSION['expires_in']-time()>0) ){
            //如果缓存中已经存在了access_token，并且没有过期，可以直接取用就行
            return $_SESSION['access_token'];
        }else{
            //如果不存在access_token或者已经过期了，就生成一个
            $appId='wx6948cfcaaa7ae74b';
            $appSecret='c30556876f75c467651ec8b4ac62f4b8';
            $url ='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
            $arr = $this->http_curl($url,'get','json');
            //这里获取成功以后，需要更新一下$_SESSION['access_token']和$_SESSION['expires_in']里面的数据
            $access_token = $arr['access_token'];
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expires_in']   = time() + 7200 ;
            return $arr['access_token'];
        }

    }


    public function createMenu(){
        $access_token = '14_03NDSKZyFAP0_fERFHIUjTE9awriVhDimAdnJaqfYqIeWloXNIzVGYAsdqISviDlaGoOCW_iOcnZx9VOlZKkW6yYLqG-Gks7C8HiL8ipgrqFXCCQb6LlcaCY5f_x1ZUjTmXTt0ZgNfRywjmISIOgADAZPV';
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        //拼装要生成的菜单
        $array = array(
            'button'=>array(
                //第一个一级菜单
                array(
                    'type' => 'click',
                    'name' => urlencode('菜单一'),
                    'key' => 'Item1'
                ),
                //第二个一级菜单
                array(
                    'name' => urlencode('菜单二'),
                    'sub_button' =>array(
                        //三个二级菜单
                        array(
                            'type'=>'view',
                            'name'=>urlencode('百度一下'),
                            'url' =>'http://www.baidu.com/'
                        ),
                        array(
                            'type'=>'view',
                            'name'=>urlencode('搜索一下'),
                            'url' =>'http://www.google.com/'
                        ),
                        array(
                            'type'=>'click',
                            'name'=>urlencode('赞一下'),
                            'key' =>'Item2'
                        ),
                    ),
                ),
                //第三个一级菜单
                array(
                    'type'=>'click',
                    'name'=>urlencode('菜单三'),
                    'key' =>'Item3'
                )
            )
        );
        //转化成json的格式
        $arrayJson = urldecode(json_encode( $array ));
        $res = $this->http_curl($url,'post','json',$arrayJson);
        var_dump($res);
    }
}

$menu = new Request();
$menu->createMenu();

