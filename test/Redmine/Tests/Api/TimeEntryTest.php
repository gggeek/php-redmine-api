<?php

namespace Redmine\Tests\Api;

use Redmine\Api\TimeEntry;

/**
 * @coversDefaultClass Redmine\Api\TimeEntry
 * @author     Malte Gerth <mail@malte-gerth.de>
 */
class TimeEntryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test all()
     *
     * @covers ::all
     * @test
     *
     * @return void
     */
    public function testAllReturnsClientGetResponse()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/time_entries.json')
            ->willReturn($getResponse);

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->all());
    }

    /**
     * Test all()
     *
     * @covers ::all
     * @test
     *
     * @return void
     */
    public function testAllReturnsClientGetResponseWithParameters()
    {
        // Test values
        $parameters = array(
            'project_id' => 5,
            'user_id' => 10,
            'limit' => 2
        );
        $getResponse = array('API Response');

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->any())
            ->method('get')
            ->with(
                $this->logicalAnd(
                    $this->stringStartsWith('/time_entries.json?'),
                    $this->stringContains('project_id=5'),
                    $this->stringContains('user_id=10'),
                    $this->stringContains('limit=2')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->all($parameters));
    }

    /**
     * Test show()
     *
     * @covers ::get
     * @covers ::show
     * @test
     *
     * @return void
     */
    public function testShowReturnsClientGetResponse()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/time_entries/5.json')
            ->willReturn($getResponse);

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->show(5));
    }

    /**
     * Test remove()
     *
     * @covers ::delete
     * @covers ::remove
     * @test
     *
     * @return void
     */
    public function testRemoveCallsDelete()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('delete')
            ->with('/time_entries/5.xml')
            ->willReturn($getResponse);

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->remove(5));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @expectedException Exception
     * @test
     *
     * @return void
     */
    public function testCreateThrowsExceptionWithEmptyParameters()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create(array('id' => 5)));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @expectedException Exception
     * @test
     *
     * @return void
     */
    public function testCreateThrowsExceptionIfIssueIdAndProjectIdAreMissingInParameters()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'hours' => '5.25'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @expectedException Exception
     * @test
     *
     * @return void
     */
    public function testCreateThrowsExceptionIfHoursAreMissingInParameters()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'issue_id' => '15',
            'project_id' => '25',
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @covers ::post
     * @test
     *
     * @return void
     */
    public function testCreateCallsPost()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'issue_id' => '15',
            'project_id' => '25',
            'hours' => '5.25'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('post')
            ->with(
                '/time_entries.xml',
                $this->logicalAnd(
                    $this->stringStartsWith('<?xml version="1.0"?>' . "\n" . '<time_entry>'),
                    $this->stringEndsWith('</time_entry>' . "\n"),
                    $this->stringContains('<issue_id>15</issue_id>'),
                    $this->stringContains('<project_id>25</project_id>'),
                    $this->stringContains('<hours>5.25</hours>')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test update()
     *
     * @covers ::put
     * @covers ::update
     * @test
     *
     * @return void
     */
    public function testUpdateCallsPut()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'hours' => '10.25'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('put')
            ->with(
                '/time_entries/5.xml',
                $this->logicalAnd(
                    $this->stringStartsWith('<?xml version="1.0"?>' . "\n" . '<time_entry>'),
                    $this->stringEndsWith('</time_entry>' . "\n"),
                    $this->stringContains('<hours>10.25</hours>')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new TimeEntry($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->update(5, $parameters));
    }
}
