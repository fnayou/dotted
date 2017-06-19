<?php
/**
 * This file is part of the fnayou/dotted package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou;

/**
 * Class DotNotationTest.
 */
class DottedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test constructor.
     */
    public function testConstructor()
    {
        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('setValues')
            ->with(
                $this->equalTo(FakeParameters::getBaseArrayContent())
            )
            ->willReturn($this->returnSelf());

        $reflectionClass = new \ReflectionClass($classname);
        $constructor = $reflectionClass->getConstructor();
        $constructor->invoke($mock, FakeParameters::getBaseArrayContent());
    }

    /**
     * test static method create.
     */
    public function testStaticCreate()
    {
        $content = FakeParameters::getBaseArrayContent();
        $apiError = Dotted::create($content);
        $this->assertInstanceOf(Dotted::class, $apiError);
    }

    /**
     * test getValues should success.
     */
    public function testGetValuesShouldSuccess()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->assertSame($content, $dotted->getValues());
    }

    /**
     * test get should success.
     */
    public function testGetShouldSuccess()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->assertSame($content['key1'], $dotted->get('key1'));
        $this->assertSame($content['key2'], $dotted->get('key2'));
        $this->assertSame($content['key3'], $dotted->get('key3'));
        $this->assertSame($content['key4'], $dotted->get('key4'));
        $this->assertSame($content['key4']['key5'], $dotted->get('key4.key5'));
        $this->assertSame($content['key4']['key6'], $dotted->get('key4.key6'));
        $this->assertSame($content['key4']['key7'], $dotted->get('key4.key7'));
        $this->assertSame($content['key8'][0], $dotted->get('key8.0'));
        $this->assertSame($content['key8'][1], $dotted->get('key8.1'));
    }

    /**
     * test get with unique path should success.
     */
    public function testGetUniqueShouldSuccess()
    {
        $content = FakeParameters::getBaseArrayContent();

        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->setMethods(['explode'])
            ->setConstructorArgs([$content])
            ->getMock();

        $mock->expects($this->once())
            ->method('explode')
            ->with(
                $this->equalTo('key4.key5')
            )
            ->willReturn(['key4', 'key5']);

        $this->assertSame($content['key4']['key5'], $mock->get('key4.key5'));
    }

    /**
     * test get fake path should return null.
     */
    public function testGetFakeShouldReturnNull()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->assertNull($dotted->get('fake'));
    }

    /**
     * test get fake path with default should return default.
     */
    public function testGetFakeWithDefaultShouldReturnDefault()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->assertNotNull($dotted->get('fake', 'blabla'));
        $this->assertSame('fakeValue', $dotted->get('fake', 'fakeValue'));
    }

    /**
     * test get without path should throw exception.
     */
    public function testGetWithoutPathShouldFail()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->expectException(\InvalidArgumentException::class);
        $dotted->get('');
    }

    /**
     * test get with unique path should return true.
     */
    public function testHasUniqueShouldReturnTrue()
    {
        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->setMethods(['explode'])
            ->setConstructorArgs([FakeParameters::getBaseArrayContent()])
            ->getMock();

        $mock->expects($this->once())
            ->method('explode')
            ->with(
                $this->equalTo('key4.key5')
            )
            ->willReturn(['key4', 'key5']);

        $this->assertTrue($mock->has('key4.key5'));
    }

    /**
     * test get with unique with fake path should return false.
     */
    public function testHasUniqueWithFakeShouldReturnFalse()
    {
        $content = FakeParameters::getBaseArrayContent();

        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->setMethods(['explode'])
            ->setConstructorArgs([$content])
            ->getMock();

        $mock->expects($this->once())
            ->method('explode')
            ->with(
                $this->equalTo('key4.key99')
            )
            ->willReturn(['key4', 'key99']);

        $this->assertFalse($mock->has('key4.key99'));
    }

    /**
     * test set should success.
     */
    public function testSetShouldSuccess()
    {
        $baseContent = FakeParameters::getBaseArrayContent();
        $newContent = FakeParameters::getNewArrayContent();

        $dotted = new Dotted($baseContent);
        $dotted->set('key9', $newContent['key9']);
        $dotted->set('key10', $newContent['key10']);
        $dotted->set('key11', $newContent['key11']);
        $dotted->set('key13', $newContent['key13']);

        $this->assertSame($newContent['key9'], $dotted->get('key9'));
        $this->assertSame($newContent['key10'], $dotted->get('key10'));
        $this->assertSame($newContent['key11'][0], $dotted->get('key11.0'));
        $this->assertSame($newContent['key11'][1], $dotted->get('key11.1'));
        $this->assertSame($newContent['key13']['key14'], $dotted->get('key13.key14'));
    }

    /**
     * test set with unique path should success.
     */
    public function testSetUniqueShouldSuccess()
    {
        $baseContent = FakeParameters::getBaseArrayContent();

        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->setMethods(['explode'])
            ->setConstructorArgs([$baseContent])
            ->getMock();

        $mock->expects($this->exactly(2))
            ->method('explode')
            ->with(
                $this->equalTo('key13.key14')
            )
            ->willReturn(['key13', 'key14']);

        $mock->set('key13.key14', 'value14');

        $newContent = FakeParameters::getNewArrayContent();

        $this->assertSame($newContent['key13']['key14'], $mock->get('key13.key14'));
    }

    /**
     * test set without path should throw exception.
     */
    public function testSetWithoutPathShouldFail()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->expectException(\InvalidArgumentException::class);
        $dotted->set('', '');
    }

    /**
     * test set with not array path should throw exception.
     */
    public function testSetWithNotArrayPathShouldFail()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $this->expectException(\InvalidArgumentException::class);
        $dotted->set('key1.foo', 'bar');
    }

    /**
     * test set with override should success.
     */
    public function testSetWithOverrideShouldSuccess()
    {
        $content = FakeParameters::getBaseArrayContent();
        $dotted = new Dotted($content);

        $dotted->set('key1', 'newValue');

        $this->assertSame('newValue', $dotted->get('key1'));
    }

    /**
     * test add should success.
     */
    public function testAddShouldSuccess()
    {
        $baseContent = FakeParameters::getBaseArrayContent();

        $dotted = new Dotted($baseContent);
        $dotted->add('key4.key5', ['newKey' => 'newValue']);

        $this->assertTrue($dotted->has('key4.key5.newKey'));
        $this->assertSame('newValue', $dotted->get('key4.key5.newKey'));
    }

    /**
     * test add with unique path should success.
     */
    public function testAddUniqueShouldSuccess()
    {
        $baseContent = FakeParameters::getBaseArrayContent();

        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->setMethods(['arrayMergeRecursiveDistinct'])
            ->setConstructorArgs([$baseContent])
            ->getMock();

        $mock->expects($this->once())
            ->method('arrayMergeRecursiveDistinct')
            ->with(
                $this->equalTo(['value5']),
                $this->equalTo(['newKey' => 'newValue'])
            )
            ->willReturn(['value5', 'newKey' => 'newValue']);

        $mock->add('key4.key5', ['newKey' => 'newValue']);

        $this->assertTrue($mock->has('key4.key5.newKey'));
        $this->assertSame('newValue', $mock->get('key4.key5.newKey'));
    }

    /**
     * test add with unique path should success.
     */
    public function testFlattenUniqueShouldSuccess()
    {
        $baseContent = FakeParameters::getBaseArrayContent();

        $classname = Dotted::class;
        $mock = $this->getMockBuilder($classname)
            ->setMethods(['arrayFlattenRecursive'])
            ->setConstructorArgs([$baseContent])
            ->getMock();

        $mock->expects($this->once())
            ->method('arrayFlattenRecursive')
            ->with(
                $baseContent
            )
            ->willReturn(FakeParameters::getFlattenContent());

        $flatten = $mock->flatten();

        foreach ($flatten as $key => $value) {
            $this->assertTrue($mock->has($key));
            $this->assertSame($value, $mock->get($key));
        }
    }
}
