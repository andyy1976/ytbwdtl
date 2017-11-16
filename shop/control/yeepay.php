<?php
/**
 * 广告展示
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

function debugLog ( $filename, $contents ) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $filename = BASE_ROOT_PATH. '/data/log/yeepay/' . $filename;
    $contents = "\nIP\n" . $ip . "\n" . $contents;
    $contents = "\nREQUEST\n" . $_SERVER['REQUEST_METHOD'] . "\n" . $contents;
    file_put_contents($filename, $contents);
}


class yeepayControl {

    public function notifyOp()
    {
        $raw_post_data = file_get_contents('php://input', 'r');

        if ($_SERVER['REQUEST_METHOD']=='POST' || !empty($_POST)) {
            ksort($_POST);
            $filename = 'Post-'. time() .'.txt';
            $contents = "\nGET\n" .print_r($_GET, true)
                      . "\nPOST\n" .print_r($_POST, true)
                      . "\nphp://input\n" .print_r($raw_post_data, true)
                      . "\n";
            debugLog( $filename, $contents );
        }

        else {
            $filename = 'Get-'. time() .'.txt';
            $contents = "\nGET\n" .print_r($_GET, true)
                      . "\nPOST\n" .print_r($_POST, true)
                      . "\nphp://input\n" .print_r($raw_post_data, true)
                      . "\n";
            debugLog( $filename, $contents );
            exit;
        }



        // include BASE_PATH . '/api/ybdf/SendTransferBatch.php';
        // $a = new Model_Yibao();
        // $hmac = $a->Hmacsafe($raw_post_data);
        // if ($hmac == "SUCCESS") {

            // status = S -> $raw_post_data 为GBK编码
            $arr = simplexml_load_string($raw_post_data);
            $contents = "\nARR\n" .print_r($arr, true) ."\n";
            $filename = 'ARR-RAW'. time() .'.txt';
            debugLog( $filename, $contents );

            if (!$arr) {

                // status = F -> $raw_post_data 为UTF-8编码，需转为GBK
                $raw_post_data = mb_convert_encoding($raw_post_data,'gbk','utf-8');
                $arr = simplexml_load_string($raw_post_data);

                $contents = "\nARR\n" .print_r($arr, true) ."\n";
                $filename = 'ARR-ENCODING'. time() .'.txt';
                debugLog( $filename, $contents );
            }

            // $raw_post_data = mb_convert_encoding($raw_post_data,'gbk','utf-8');
            // $arr = json_decode(json_encode((array) simplexml_load_string($raw_post_data)), true);
            
            $arr = (array) $arr;

            $batch_No = $arr['batch_No'];
            $order_Id = $arr['order_Id'];
            $status   = $arr['status'];
            $message  = $arr['message'];

            $model_pd = Model('predeposit');

            if ($status == 'S') {
                $data['pdc_payment_state'] = '1'; // 1 - 已付款
                $data['pdc_payment_time'] = time();
                $data['yb_error_info'] = '成功'; // mb_convert_encoding($message, 'gbk', 'utf-8'); // //$message; // 
            } else {
                $data['pdc_payment_state'] = '6'; // 6 - 付款失败
                $data['pdc_payment_time'] = time();
                $data['yb_error_info'] = $message;
            }

            $condition = array('pdc_id' => $order_Id);
            $result = $model_pd->editPdCash($data, $condition);

            if ($result) {
                echo 'S';
            }

        // }


    }

}

