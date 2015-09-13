<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zikula\Tools\Tests\Converter;

use Zikula\Tools\Converter;
use Zikula\Tools\Converter\IncludeConverter;
use Zikula\Tools\ConverterAbstract;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class IncludeConverterTest extends \PHPUnit_Framework_TestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new IncludeConverter();
    }

    /**
     * @covers       Zikula\Tools\Converter\IncludeConverter::convert
     * @dataProvider Provider
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
    }

    public function Provider()
    {
        return array(
            array(
                "{include file='page_header.tpl'}",
                "{% include 'page_header.tpl' %}"
            ),
            array(
                '{include file=\'footer.tpl\' foo=\'bar\' links=$links}',
                '{% include \'footer.tpl\' with {\'foo\' : \'bar\', \'links\' : links} %}'
            )
        );
    }

    /**
     * @covers Zikula\Tools\Converter\IncludeConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('include', $this->converter->getName());
    }

    /**
     * @covers Zikula\Tools\Converter\IncludeConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }

    private function getFileMock()
    {
        return $this->getMockBuilder('\SplFileInfo')
            ->enableOriginalConstructor()
            ->setConstructorArgs(array('mockFile'))
            ->getMock();
    }
}
