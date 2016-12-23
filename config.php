<?php

# 통신서버환경, SK텔링크에서 제공
$config['IP'] = "";
$config['PORT'] = 123456;

# 고객번호 기입
$config['CLIENT_CODE'] = "1111";

# SK텔링크의 그룹코드
$config['GROUP01'] = "your_code";

# 아래 변경금지
$config['REQCODE01'] = "1001";  /// 신규 안심번호 발급
$config['REQSIZE01'] = "0110";  /// 신규 안심번호 발급 메시지 크기

$config['REQCODE02'] = "1002";  /// 안심번호 해제
$config['REQSIZE02'] = "0030";  /// 안심번호 해제 메시지 크기

$config['REQCODE03'] = "1003";  /// 안심번호 수정
$config['REQSIZE03'] = "0170";  /// 안심번호 수정 메시지 크기

$config['REQCODE04'] = "1004";  /// 안심번호 조회
$config['REQSIZE04'] = "0020";  /// 안심번호 조회 메시지 크기
