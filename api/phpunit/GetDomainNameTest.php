<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;


require_once("../../../wp-load.php");


final class GetDomainNameTest extends TestCase
{
    public function testRootDomainWithEmpty() {
        $re = get_root_domain('');
        self::assertTrue($re == '', 'Expect empty string');
    }
    public function testRootDomainMalFormed() {
        $re = get_root_domain('abc');
        self::assertTrue($re == 'abc', 'Expect empty string');
    }
    public function testRootDomainUnsupportedRoot() {
        $re = get_root_domain('abc.eu');
        self::assertTrue($re == 'abc.eu', 'Expect empty string');
    }
    public function testRootDomainSupportedRootWithKr() {
        $re = get_root_domain('abc.kr');
        self::assertTrue($re == 'abc.kr', 'Expect empty string');
    }

    public function testRootDomainSupportedRootWithCoKr() {
        $re = get_root_domain('abc.co.kr');
        self::assertTrue($re == 'abc.co.kr', 'Expect empty string');
    }

    public function testRootDomainSupportedRootWithCom() {
        $re = get_root_domain('abc.com');
        self::assertTrue($re == 'abc.com', 'Expect empty string');
    }

    public function testRootDomainSupportedRootWithNet() {
        $re = get_root_domain('abc.net');
        self::assertTrue($re == 'abc.net', 'Expect empty string');
    }

    public function testRootDomainSupportedRootWithMultiParts() {
        $re = get_root_domain('www.my-domain.co.kr');
        self::assertTrue($re == 'my-domain.co.kr', 'Expect empty string');
        $re = get_root_domain('this.is.abc.co.kr');
        self::assertTrue($re == 'abc.co.kr', 'Expect empty string');
        $re = get_root_domain('this.is.abc.kr');
        self::assertTrue($re == 'abc.kr', 'Expect empty string');
        $re = get_root_domain('and.kr.co.kr.the.com');
        self::assertTrue($re == 'the.com', 'Expect empty string');
    }


}
