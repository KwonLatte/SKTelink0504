<?php

/*
 * @file Ansim.php
 * @bref Java API를 사용하지 않고 0504 번호를 고객사가 관리하는 경우에 사용
 * 단발성 통신일 뿐이니, param validation등은 직접 처리하세요
 * @author KwonLatte (https://github.com/KwonLatte/SKTelink0504)
 */

class Ansim
{
    private $config = array();
    private $_debug = '';

    function __construct() {

        // 설정파일 호출
        require_once('config.php');
        $this->config = $config;
    }

    // 안심번호를 대상번호에 매핑
    // 050412345678 => 0212345678
    function reqNewAnsim($ansim, $rcpt) {

        $this->size = $this->config['REQSIZE03'];
        $this->code = $this->config['REQCODE03'];

        $message = $this->padding($ansim, 20)
            . $this->padding('1234567890', 20)
            . $this->padding($rcpt, 20)
            . $this->padding('1234567890', 20)
            . $this->padding($rcpt, 20)
            . $this->padding($this->config['GROUP01'], 10)
            . $this->padding('', 60);

        return $this->_getResponse($message);
    }

    // 안심번호 해제
    function delAnsim($ansim) {

        $this->size = $this->config['REQSIZE02'];
        $this->code = $this->config['REQCODE02'];

        $message = $this->padding($ansim, 20)
            . $this->padding($this->config['GROUP01'], 10);

        return $this->_getResponse($message);
    }

    // 안심번호와 연결 된 번호 확인
    function getRealNumber($number) {

        $this->size = $this->config['REQSIZE04'];
        $this->code = $this->config['REQCODE04'];

        $message = $this->padding($number, 20);

        return $this->_getResponse($message);
    }

    // 소켓통신
    function _getResponse($message) {

        set_time_limit(0);

        ob_implicit_flush();

        $address = $this->config['IP'];
        $port = $this->config['PORT'];

        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        }

        if (socket_connect($sock, $address, $port) === false) {
            echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

        /// 타임아웃 설정
        socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 10, 'usec' => 0));
        socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 10, 'usec' => 0));

        $message = $this->_make_header() . $message;

        $this->_debug = ':::: DEBUG ::::' . PHP_EOL;
        $this->_debug .= "REQUEST >> [" . $message . "]" . PHP_EOL;

        $message .= "\r\n";

        socket_write($sock, $message);

        $res = '';
        while ($buf = socket_read($sock, 2048)) {
            $res .= $buf;
        }

        $this->_debug .= "RESPONSE >> [{$res}]" . PHP_EOL;

        socket_close($sock);

        return $res;
    }

    // 소켓통신시 자료형 크기 대응
    function padding($str, $size) {

        $str_len = strlen($str);

        $gap = $size - $str_len;

        for ($i = 0; $i < $gap; $i++) {
            $str .= ' ';
        }

        return $str;
    }

    // 소켓통신 헤더설정
    function _make_header() {

        return $this->size . $this->code . $this->config['CLIENT_CODE'];
    }

    function debug() {

        echo $this->_debug;
    }
}
