<?php

namespace NorseBlue\ExtensibleObjects\Tests\Feature;

use BadMethodCallException;
use Exception;
use NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethodException;
use NorseBlue\ExtensibleObjects\Exceptions\ExtensionNotCallableException;
use NorseBlue\ExtensibleObjects\Exceptions\GuardedExtensionMethodException;
use NorseBlue\ExtensibleObjects\Tests\Helpers\ChildExtensionMethodReplacement;
use NorseBlue\ExtensibleObjects\Tests\Helpers\ChildObject;
use NorseBlue\ExtensibleObjects\Tests\Helpers\CreatableObject;
use NorseBlue\ExtensibleObjects\Tests\Helpers\CreatableObjectExtensionMethod;
use NorseBlue\ExtensibleObjects\Tests\Helpers\DynamicMethodUsingPrivateValue;
use NorseBlue\ExtensibleObjects\Tests\Helpers\DynamicMethodUsingProtectedValue;
use NorseBlue\ExtensibleObjects\Tests\Helpers\FooObject;
use NorseBlue\ExtensibleObjects\Tests\Helpers\GuardedExtensionMethod;
use NorseBlue\ExtensibleObjects\Tests\Helpers\GuardedObject;
use NorseBlue\ExtensibleObjects\Tests\Helpers\OtherExtensionMethod;
use NorseBlue\ExtensibleObjects\Tests\Helpers\SimpleObject;
use NorseBlue\ExtensibleObjects\Tests\TestCase;

class ExtensionMethodTest extends TestCase
{
    protected function setUp(): void
    {
        SimpleObject::registerExtensionMethod('add_to_private', DynamicMethodUsingPrivateValue::class);
        SimpleObject::registerExtensionMethod('subtract_from_protected', DynamicMethodUsingProtectedValue::class);
        ChildObject::registerExtensionMethod('subtract_from_protected', ChildExtensionMethodReplacement::class);
    }

    protected function tearDown(): void
    {
        SimpleObject::unregisterExtensionMethod('add_to_private');
        SimpleObject::unregisterExtensionMethod('subtract_from_protected');
        ChildObject::unregisterExtensionMethod('subtract_from_protected');
    }

    /** @test */
    public function it_checks_registered_extension_methods()
    {
        $this->assertTrue(SimpleObject::hasExtensionMethod('add_to_private'));
        $this->assertTrue(SimpleObject::hasExtensionMethod('subtract_from_protected'));
        $this->assertFalse(SimpleObject::hasExtensionMethod('nonexistent'));

        $extensions = SimpleObject::getExtensionMethods();

        $this->assertCount(2, $extensions);
        $this->assertArrayHasKey('add_to_private', $extensions);
        $this->assertArrayHasKey('subtract_from_protected', $extensions);
        $this->assertInstanceOf(DynamicMethodUsingPrivateValue::class, $extensions['add_to_private']['method']);
        $this->assertInstanceOf(
            DynamicMethodUsingProtectedValue::class,
            $extensions['subtract_from_protected']['method']
        );
    }

    /** @test */
    public function it_executes_method_using_private_property_correctly()
    {
        $obj = new SimpleObject();

        $result = $obj->add_to_private(3);

        $this->assertEquals(3, $result);
    }

    /** @test */
    public function it_executes_method_using_protected_property_correctly()
    {
        $obj = new SimpleObject();

        $result = $obj->subtract_from_protected(3);

        $this->assertEquals(-3, $result);
    }

    /** @test */
    public function it_throws_exception_when_extension_method_class_is_not_extension_method()
    {
        try {
            SimpleObject::registerExtensionMethod('foo', FooObject::class);
        } catch (Exception $e) {
            $this->assertInstanceOf(ClassNotExtensionMethodException::class, $e);

            return;
        }

        $this->fail(ClassNotExtensionMethodException::class . ' was not thrown.');
    }

    /** @test */
    public function it_throws_exception_when_extension_method_is_not_callable()
    {
        try {
            SimpleObject::registerExtensionMethod('foo', 'not callable');
        } catch (Exception $e) {
            $this->assertInstanceOf(ExtensionNotCallableException::class, $e);

            return;
        }

        $this->fail(ExtensionNotCallableException::class . ' was not thrown.');
    }

    /** @test */
    public function it_throws_exception_when_calling_not_existing_extension_method()
    {
        $obj = new SimpleObject();

        try {
            $obj->nonexistent();
        } catch (Exception $e) {
            $this->assertInstanceOf(BadMethodCallException::class, $e);

            return;
        }

        $this->fail(BadMethodCallException::class . ' was not thrown.');
    }

    /** @test */
    public function child_object_inherits_parent_extension_methods()
    {
        $this->assertTrue(ChildObject::hasExtensionMethod('add_to_private'));
        $this->assertFalse(ChildObject::hasExtensionMethod('add_to_private', true));
        $this->assertTrue(ChildObject::hasExtensionMethod('subtract_from_protected'));

        $extensions = ChildObject::getExtensionMethods();
        $extensions_excluding_parent = ChildObject::getExtensionMethods(true);
        $parent_extensions = ChildObject::getParentExtensionMethods();

        $this->assertCount(2, $extensions);
        $this->assertCount(1, $extensions_excluding_parent);
        $this->assertCount(2, $parent_extensions);

        $this->assertInstanceOf(
            DynamicMethodUsingPrivateValue::class,
            $extensions['add_to_private']['method']
        );
        $this->assertInstanceOf(
            ChildExtensionMethodReplacement::class,
            $extensions['subtract_from_protected']['method']
        );

        $this->assertInstanceOf(
            ChildExtensionMethodReplacement::class,
            $extensions_excluding_parent['subtract_from_protected']['method']
        );

        $this->assertInstanceOf(
            DynamicMethodUsingPrivateValue::class,
            $parent_extensions['add_to_private']['method']
        );
        $this->assertInstanceOf(
            DynamicMethodUsingProtectedValue::class,
            $parent_extensions['subtract_from_protected']['method']
        );
    }

    /** @test */
    public function child_executes_parent_extension_method()
    {
        $obj = new ChildObject();

        $result = $obj->add_to_private(3);

        $this->assertEquals(3, $result);
    }

    /** @test */
    public function child_executes_own_extension_method()
    {
        $obj = new ChildObject();

        $result = $obj->subtract_from_protected(3);

        $this->assertEquals(-6, $result);
    }

    /** @test */
    public function cannot_override_guarded_method()
    {
        $this->assertFalse(GuardedObject::hasExtensionMethod('guarded'));
        GuardedObject::registerExtensionMethod('guarded', GuardedExtensionMethod::class, true);
        $this->assertTrue(GuardedObject::hasExtensionMethod('guarded'));
        $this->assertTrue(GuardedObject::isGuardedExtensionMethod('guarded'));
        $this->assertContains('guarded', GuardedObject::getGuardedExtensionMethods());

        try {
            GuardedObject::registerExtensionMethod('guarded', OtherExtensionMethod::class);
        } catch (Exception $e) {
            $this->assertInstanceOf(GuardedExtensionMethodException::class, $e);

            return;
        }

        $this->fail(GuardedExtensionMethodException::class . ' was not thrown.');
    }

    /** @test */
    public function cannot_unregister_guarded_method()
    {
        $this->assertFalse(GuardedObject::hasExtensionMethod('unregisterable'));
        GuardedObject::registerExtensionMethod('unregisterable', GuardedExtensionMethod::class, true);
        $this->assertTrue(GuardedObject::hasExtensionMethod('unregisterable'));
        $this->assertTrue(GuardedObject::isGuardedExtensionMethod('unregisterable'));
        $this->assertContains('unregisterable', GuardedObject::getGuardedExtensionMethods());

        try {
            GuardedObject::unregisterExtensionMethod('unregisterable');
        } catch (Exception $e) {
            $this->assertInstanceOf(GuardedExtensionMethodException::class, $e);

            return;
        }

        $this->fail(GuardedExtensionMethodException::class . ' was not thrown.');
    }

    /** @test */
    public function creatable_extensible_object()
    {
        $this->assertFalse(CreatableObject::hasExtensionMethod('creatable'));
        CreatableObject::registerExtensionMethod('creatable', CreatableObjectExtensionMethod::class);
        $this->assertTrue(CreatableObject::hasExtensionMethod('creatable'));

        $creatable = CreatableObject::create();
        $this->assertEquals('created', $creatable->creatable());
    }
}
