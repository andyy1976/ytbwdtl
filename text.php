<?php

function send_request($opt) {
	$conf = [
		'url'      => '',
		'postdata' => [], //有数据时自动转为post
		'headers'  => [],
		'post'     => false, //默认为false，true时当前请求强制转为post
	];
	$conf = array_merge($conf, $opt);
	if (is_string($conf['headers'])) {
		$conf['headers'] = trim($conf['headers']);
		$conf['headers'] = preg_split('/\r?\n/', $conf['headers']);
	}
	if (!$conf['url']) {
		return '';
	}
	// var_dump($conf['postdata']);
	// var_dump($conf['headers']);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $conf['url']);
	if ($conf['postdata'] || $conf['post'] === true) {
		curl_setopt($ch, CURLOPT_POST, 1);
		$postdata = http_build_query($conf['postdata']);
		$conf['postdata'] && curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); //设置post数据
	} else {
		curl_setopt($ch, CURLOPT_POST, 0);
	}
	// var_dump($conf['postdata']);
	//curl_setopt($ch,CURLOPT_HEADER,1); //将头文件的信息作为数据流输出
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回内容不输出到浏览器
	// curl_setopt($ch, CURLOPT_REFERER, $conf['url']); //设置来源地址
	$conf['headers'] && curl_setopt($ch, CURLOPT_HTTPHEADER, $conf['headers']); //设置请求头
	$result = curl_exec($ch);
	//如果上面设置啦头信息到数据流可以用下面的方法取响应头中的信息
	//$weizhi = strpos($result, "\r\n\r\n");
	//请求头信息
	//$re_header = substr($result, 0, $weizhi);
	//返回的内容
	//$result = substr($result, $weizhi + 4);
	//preg_match_all('/Set-Cookie:stest=(.*)/i', $result, $cookie);
	//请求出错退出
	if ($error = curl_error($ch)) {
		die($error);
	}
	curl_close($ch);
	return $result;
}
       $header = <<<eot
Host: api.smsbao.com
User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3
Accept-Encoding: gzip, deflate
Cookie: UM_distinctid=15f0b94c0ff21e-04c8667e7984408-12666d4a-1fa400-15f0b94c1004b1
Connection: keep-alive
Upgrade-Insecure-Requests: 1
Cache-Control: max-age=0
eot;
$url='https://api.smsbao.com/sms?u=lky2016&p=122983a34ae0958ed2fae00f2bf38025&m=17673135929&c=%E3%80%90%E4%B8%87%E5%BA%97%E9%80%9A%E8%81%94%E3%80%91%E6%82%A8%E4%BA%8E2017-10-11%E7%94%B3%E8%AF%B7%E9%87%8D%E7%BD%AE%E7%99%BB%E5%BD%95%E5%AF%86%E7%A0%81%EF%BC%8C%E9%AA%8C%E8%AF%81%E7%A0%81%E4%B8%BA%EF%BC%9A608325%E3%80%82';
// http://api.smsbao.com/sms?u=lky2016&p=122983a34ae0958ed2fae00f2bf38025&m=13657432623&c=%E3%80%90%E4%B8%87%E5%BA%97%E9%80%9A%E8%81%94%E3%80%91%E6%82%A8%E4%BA%8E2017-10-11%E7%94%B3%E8%AF%B7%E9%87%8D%E7%BD%AE%E7%99%BB%E5%BD%95%E5%AF%86%E7%A0%81%EF%BC%8C%E9%AA%8C%E8%AF%81%E7%A0%81%E4%B8%BA%EF%BC%9A550217%E3%80%82
// https://api.smsbao.com/sms?u=lky2016&p=122983a34ae0958ed2fae00f2bf38025&m=17673135929&c=%E3%80%90%E4%B8%87%E5%BA%97%E9%80%9A%E8%81%94%E3%80%91%E6%82%A8%E4%BA%8E2017-10-11%E7%94%B3%E8%AF%B7%E9%87%8D%E7%BD%AE%E7%99%BB%E5%BD%95%E5%AF%86%E7%A0%81%EF%BC%8C%E9%AA%8C%E8%AF%81%E7%A0%81%E4%B8%BA%EF%BC%9A608325%E3%80%82
echo send_request([
	'url'     => $url,
	'headers' => $header,
]); 


?>