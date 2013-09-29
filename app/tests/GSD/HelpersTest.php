<?php
class HelpersTest extends TestCase {

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPickFromListEmptyArrayThrowsError()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        pick_from_list($command, 'title', array());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPickFromListBadDefaultThrowsError()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        pick_from_list($command, 'title', array('option 1'), -1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPickFromListBadDefaultThrowsError2()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        pick_from_list($command, 'title', array('option 1'), 2);
    }

    public function testPickFromListWorksExample1()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        $command->shouldReceive('info')->once();
        $command->shouldReceive('line')->times(2);
        $command->shouldReceive('ask')->once()->andReturn(1);
        $choice = pick_from_list($command, 'title', array('option'));
        $this->assertEquals(1, $choice);
    }

    public function testPickFromListWorksExample2()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        $command->shouldReceive('info')->once();
        $command->shouldReceive('line')->times(3);
        $command->shouldReceive('ask')->once()->andReturn(2);
        $choice = pick_from_list($command, 'title', array('option 1', 'opt2'));
        $this->assertEquals(2, $choice);
    }

    // First time through loop user selects bad choice, causing a second loop
    public function testPickFromListWorksExample3()
    {
        $formatter = Mockery::mock('stdClass');
        $formatter->shouldReceive('formatBlock')->once()->andReturn('');
        $helperset = Mockery::mock('stdClass');
        $helperset->shouldReceive('get')->once()->andReturn($formatter);
        $command = Mockery::mock('Illuminate\Console\Command');
        $command->shouldReceive('info')->times(2);
        $command->shouldReceive('line')->times(4);
        $command->shouldReceive('getHelperSet')->once()->andReturn($helperset);
        $command->shouldReceive('ask')->times(2)->andReturn('x', 1);

        $choice = pick_from_list($command, 'title', array('option'));
        $this->assertEquals(1, $choice);
    }

    public function testPickFromListWorksWithDefault()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        $command->shouldReceive('info')->once();
        $command->shouldReceive('line')->times(3);
        $command->shouldReceive('ask')->once()->andReturn('');
        $choice = pick_from_list($command, 'title', array('option 1', 'opt2'), 2);
        $this->assertEquals(2, $choice);
    }

    public function testPickFromListWorksWithAbort()
    {
        $command = Mockery::mock('Illuminate\Console\Command');
        $command->shouldReceive('info')->once();
        $command->shouldReceive('line')->times(3);
        $command->shouldReceive('ask')->once()->andReturn(2);
        $choice = pick_from_list($command, 'title', array('option'), 0, "Abort");
        $this->assertEquals(-1, $choice);
    }

    public function testAllNull()
    {
        $this->assertTrue(all_null());
        $this->assertTrue(all_null(null));
        $this->assertTrue(all_null(null, null, null, null));
        $this->assertFalse(all_null(0));
        $this->assertFalse(all_null(null, null, '', null));
        $this->assertFalse(all_null(null, null, null, 33));
    }
}