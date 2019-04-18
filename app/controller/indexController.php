<?php
/**
 * yato index.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/27
 * Version: 1.0
 */

namespace app\controller;


use core\base\controller;
use app\models\SymbolDayModel;

class indexController extends controller
{
    public function requestData($url, $params = array(), $header = array(), $cookie='',$timeout = 5) {
        if(!$url) return false;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 6);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $ar_url = parse_url($url);
        if($ar_url['scheme'] === "https")
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
        if(!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        if($cookie)
            curl_setopt($curl,CURLOPT_COOKIE,$cookie);
        if(!empty($params)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            //throw new Exception('Request Exception: ' . curl_error($curl), 1);
        }
        curl_close($curl);
        if($response) {
            return $response;
        }
        return false;
    }

    public function gupiao() {
        set_time_limit(0);
        //$start = 566;
        $cookie = '_ga=GA1.2.525690195.1552205261; device_id=a3182cb158ba6131040f14af14df31e0; aliyungf_tc=AQAAANNUMl+14wsAYkaYPfrpNVxcIgQa; xq_a_token=3450822dc3b6c0b631c3ba4768fcddac23c054d7; xq_a_token.sig=f1ZlcbP6BFkUA32I1cexLDa2KTk; xq_r_token=0de8d3b6155ce156310ff6d4e214d4532198ccec; xq_r_token.sig=8xgxBORSc2oawdjl5r3ksadcr9s; _gid=GA1.2.441161088.1555063539; Hm_lvt_1db88642e346389874251b5a1eded6e3=1553514877,1553850580,1554805054,1555063539; u=891555063539609; Hm_lpvt_1db88642e346389874251b5a1eded6e3=1555063651';
        $symbolList = array(
            0=>'SZ000',
            1=>'SH600',
            2=>'SZ002',
            3=>'SH601',
        );
        $mod = new SymbolDayModel();
        foreach($symbolList as $sym) {
            for ($start = 1; $start <= 999; $start++) {
                $symbol = $sym . str_pad($start, 3, 0, STR_PAD_LEFT);
                $url    = 'https://xueqiu.com/stock/forchartk/stocklist.json';
                $param  = array(
                    'symbol' => $symbol,
                    'period' => '1day',//时间间隔
                    'type' => 'before',//复权，after 不复权before
                    'begin' => strtotime('2019-03-21 09:30') * 100000,//1478620800000,//开始时间
                    'end' => strtotime('2019-03-21 15:30') * 100000,//1510126200000,//strtotime('2019-03-09'),//结束时间
                );
                $result   = $this->requestData($url . '?' . http_build_query($param), [], [], $cookie);
                $result   = json_decode($result, 1);
                $result = $result['chartlist'];
//                krsort($result);
//var_dump($result);exit;
                $replaceData = array();
                for($i=0;$i<10;$i++) {
                    $data = array_pop($result);
//                    print_r($result);exit;
//                    $data = current($data);
                    if (empty($data)) {
                        continue;
                    }

                    $data['code'] = $symbol;
                    $data['days'] = date('Ymd', strtotime($data['time']));
                    unset($data['time']);
                    unset($data['timestamp']);
                    $replaceData[] = $data;
                }
                if($replaceData)
                    $mod->multiReplace($replaceData);
                echo $symbol . "\n\r";
//                exit;
            }
        }
    }

    public function index()
    {
        echo 1;exit;
//        $data = $t->connect()->select('*')->from('db')->getRow();
//        $data = $this->loadModel('test');
        $mod = new SymbolDayModel();
        $data = $mod->getRowByDay("days='20190326' and close=high and percent>7");
        foreach($data as $val){
            echo $val['code'];
            echo "<br/>";
            $tmp = $mod->getRow("days='20190329' and code='{$val['code']}'");
            if($tmp && abs($tmp['close']-$val['close'])/$val['close']<0.05){
                echo $tmp['code'];
                echo "<br/>";
            }
        }
        exit;
    }

}