<?php

namespace Jaxon\Tests\TestRegistration;

use Jaxon\Jaxon;
use Jaxon\Exception\SetupException;
use Jaxon\Plugin\Request\CallableClass\CallableClassPlugin;
use Jaxon\Plugin\Request\CallableClass\CallableObject;
use Jaxon\Plugin\Request\CallableDir\CallableDirPlugin;
use Jaxon\Tests\Ns\Ajax\ClassA;
use Jaxon\Tests\Ns\Ajax\ClassB;
use Jaxon\Tests\Ns\Ajax\ClassC;
use PHPUnit\Framework\TestCase;

use function Jaxon\jaxon;
use function strlen;

class NamespaceTest extends TestCase
{
    /**
     * @var CallableDirPlugin
     */
    protected $xDirPlugin;

    /**
     * @var CallableClassPlugin
     */
    protected $xClassPlugin;

    /**
     * @throws SetupException
     */
    public function setUp(): void
    {
        jaxon()->setOption('core.prefix.class', '');

        // This directory is already registered with the autoload.
        jaxon()->register(Jaxon::CALLABLE_DIR, __DIR__ . '/../src/Ns/Ajax',
            ['namespace' => "Jaxon\\Tests\\Ns\\Ajax", 'autoload' => false]);
        // This directory needs to be registered with the autoload.
        jaxon()->register(Jaxon::CALLABLE_DIR, __DIR__ . '/../src/dir_ns', "Jaxon\\NsTests");

        $this->xDirPlugin = jaxon()->di()->getCallableDirPlugin();
        $this->xClassPlugin = jaxon()->di()->getCallableClassPlugin();
    }

    /**
     * @throws SetupException
     */
    public function tearDown(): void
    {
        jaxon()->reset();
        parent::tearDown();
    }

    public function testPluginName()
    {
        $this->assertEquals(Jaxon::CALLABLE_DIR, $this->xDirPlugin->getName());
    }

    /**
     * @throws SetupException
     */
    public function testCallableDirClasses()
    {
        $xClassACallable = $this->xClassPlugin->getCallable(ClassA::class);
        $xClassBCallable = $this->xClassPlugin->getCallable(ClassB::class);
        $xClassCCallable = $this->xClassPlugin->getCallable(ClassC::class);
        // Test callables classes
        $this->assertEquals(CallableObject::class, get_class($xClassACallable));
        $this->assertEquals(CallableObject::class, get_class($xClassBCallable));
        $this->assertEquals(CallableObject::class, get_class($xClassCCallable));
        // Check methods
        $this->assertTrue($xClassACallable->hasMethod('methodAa'));
        $this->assertTrue($xClassACallable->hasMethod('methodAb'));
        $this->assertFalse($xClassACallable->hasMethod('methodAc'));
    }

    /**
     * @throws SetupException
     */
    public function testCallableDirJsCode()
    {
        // The js is generated by the CallableClass plugin
        // $this->assertEquals('70c275d2514aa3168dd0c949f8b743c0', $this->xClassPlugin->getHash());
        $this->assertEquals(32, strlen($this->xClassPlugin->getHash()));
        // $this->assertEquals(file_get_contents(__DIR__ . '/../src/js/ns.js'), $this->xClassPlugin->getScript());
        $this->assertEquals(2710, strlen($this->xClassPlugin->getScript()));
    }

    /**
     * @throws SetupException
     */
    public function testRegisterWithIncorrectSeparator()
    {
        // Overwrite the first registration
        jaxon()->register(Jaxon::CALLABLE_DIR, __DIR__ . '/../src/dir_ns',
            ['namespace' => "Jaxon\\NsTests", 'separator' => ':']);
        // The js is generated by the CallableClass plugin
        // $this->assertEquals('70c275d2514aa3168dd0c949f8b743c0', $this->xClassPlugin->getHash());
        $this->assertEquals(32, strlen($this->xClassPlugin->getHash()));
        // $this->assertEquals(file_get_contents(__DIR__ . '/../src/js/ns.js'), $this->xClassPlugin->getScript());
        $this->assertEquals(2710, strlen($this->xClassPlugin->getScript()));
    }

    /**
     * @throws SetupException
     */
    public function testRegisterWithUnderscoreAsSeparator()
    {
        //!! Actually this test fails because the feature is not implemented.
        //!! The library can't generate js code using the underscore as separator in namespaces.
        // Overwrite the first registration
        jaxon()->register(Jaxon::CALLABLE_DIR, __DIR__ . '/../src/dir_ns',
            ['namespace' => "Jaxon\\NsTests", 'separator' => '_']);
        // The js is generated by the CallableClass plugin
        // $this->assertEquals('5e27e08f6be05cfb4c3b5e472f31d4db', $this->xClassPlugin->getHash());
        $this->assertEquals(32, strlen($this->xClassPlugin->getHash()));
        // $this->assertEquals(file_get_contents(__DIR__ . '/../src/js/ns.js'), $this->xClassPlugin->getScript());
        $this->assertEquals(2554, strlen($this->xClassPlugin->getScript()));

        $xCallable = $this->xClassPlugin->getCallable('Jaxon_Tests_Ns_Ajax_ClassA');
        $this->assertEquals(CallableObject::class, get_class($xCallable));
    }

    public function testClassNotFound()
    {
        // No callable for standard PHP functions.
        $this->expectException(SetupException::class);
        $this->xDirPlugin->getCallable('Simple');
    }
}
