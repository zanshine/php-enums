<?php

require_once __DIR__ . '/EnumTestCase.class.php';
require_once __DIR__ . '/../src/EnumGenerator.class.php';

/**
 *  @backupStaticAttributes enabled
 */
class EnumTest extends EnumTestCase
{
  
  private $enumFile;
  
  public function __construct()
  {
    if (! class_exists('FruitsEnum'))
    {
      EnumGenerator::setDefaultCachedClassesDir($this->tmpDir);
      $this->enumFile = EnumGenerator::getInstance()->compil('FruitsEnum', array('apple' , 'orange' , 'rasberry'));
      require_once $this->enumFile;
    }
  }
  
  public function __destruct()
  {
    if(file_exists($this->enumFile))
    {      
      unlink($this->enumFile);
    }
  }

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp()
  {
    parent::setUp();
    //
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown()
  {
    //
    parent::tearDown();
  }

  /**
   * @test
   * @testdox the generated enum class should not be be instanciable
   */
  public function enumIsNotInstanciable()
  {
    $ref = new ReflectionClass('FruitsEnum');
    $this->assertFalse($ref->getConstructor()->isPublic());
  }

  /**
   * @test
   * @testdox the generated instances static getters should be uppcase
   */
  public function enumHasUppcaseInstancesStaticGetters()
  {
    $ref = new ReflectionClass('FruitsEnum');
    $this->assertTrue($ref->hasMethod('apple'));
    $this->assertTrue($ref->hasMethod('Apple'));
    $this->assertTrue($ref->hasMethod('APPLE'));
  }

  /**
   * @test
   * @testdox the generated enum class should have static getters for each enum instance
   */
  public function enumHasInstancesStaticGetters()
  {
    $ref = new ReflectionClass('FruitsEnum');
    $this->assertTrue($ref->getMethod('APPLE')->isStatic());
    $this->assertTrue($ref->getMethod('ORANGE')->isStatic());
    $this->assertTrue($ref->getMethod('RASBERRY')->isStatic());
  }

  /**
   * @test
   * @testdox the generated instances static getters should return a self instance
   */
  public function enumInstancesStaticGettersIsTypeOfSelf()
  {
    $this->assertTrue(FruitsEnum::APPLE() instanceof FruitsEnum);
  }

  /**
   * @test
   * @testdox the generated instances static getters should return a singleton
   */
  public function enumInstancesStaticGettersReturnSingleton()
  {
    $this->assertEquals(FruitsEnum::APPLE(), FruitsEnum::APPLE());
  }

  /**
   * @test
   * @testdox different enums instances should not be equals
   */
  public function enumInstancesShouldNotBeEquals()
  {
    $this->assertNotEquals(FruitsEnum::APPLE(), FruitsEnum::ORANGE());
  }
  
  /**
   * @test
   * @testdox the enum instances should implements Enum
   */
  public function enumInstancesImplementsEnum()
  {
    $this->assertTrue(FruitsEnum::APPLE() instanceof Enum);
  }


}

