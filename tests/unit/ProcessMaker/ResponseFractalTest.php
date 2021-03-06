<?php

namespace Tests\Unit\ProcessMaker;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Serializer\ArraySerializer;
use ProcessMaker\Model\ReportTable;
use ProcessMaker\Transformers\ReportTableTransformer;
use Tests\TestCase;

class ResponseFractalTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test response Fractal item
     */
    public function testResponseItem()
    {
        $this->createDataReportTable();

        $reportTable = ReportTable::first();

        $response = fractal($reportTable, new ReportTableTransformer())->respond();

        $data = json_decode($response->getContent(), true);

        //verify the response is not null
        $this->assertNotNull($response);
        //verify the response status is 200 Ok
        $this->assertEquals(200, $response->getStatusCode());

        //verify if the fields exist in the data response
        $this->verifyStructure($data);

        //Custom serializer
        $response = fractal($reportTable, new ReportTableTransformer)
            ->serializeWith(new ArraySerializer)
            ->respond();

        $data = json_decode($response->getContent(), true);

        //verify the response is not null
        $this->assertNotNull($response);
        //verify the response status is 200 Ok
        $this->assertEquals(200, $response->getStatusCode());

        //verify if the fields exist in the data response
        $this->verifyStructure($data);
    }

    /**
     * Test Response Fractal Paged
     */
    public function testResponsePaged()
    {
        $reportTables = ReportTable::where('type', 'NORMAL')->paginate(4);

        $response = fractal($reportTables, new ReportTableTransformer())->respond();

        $data = json_decode($response->getContent(), true);

        //verify the response is not null
        $this->assertNotNull($response);
        //verify the response status is 200 Ok
        $this->assertEquals(200, $response->getStatusCode());

        //verify if the fields exist in the data response
        $this->assertInternalType('array', $data['data']);
        $this->assertInternalType('array', $data['meta']);
        $this->assertArrayHasKey('total', $data['meta']);
        $this->assertArrayHasKey('per_page', $data['meta']);
        $this->assertArrayHasKey('current_page', $data['meta']);
        $this->assertArrayHasKey('total_pages', $data['meta']);
        $this->assertArrayHasKey('filter', $data['meta']);
        $this->assertArrayHasKey('sort_by', $data['meta']);
        $this->assertArrayHasKey('sort_order', $data['meta']);
        foreach ($data['data'] as $reportTableData) {
            $this->verifyStructure($reportTableData);
        }

        //custom Serializer and Paginator
        $paginator = new IlluminatePaginatorAdapter(
            new LengthAwarePaginator($reportTables, 4, 2)
        );

        $response = fractal($reportTables, new ReportTableTransformer())
            ->serializeWith(new ArraySerializer())
            ->paginateWith($paginator)
            ->respond();

        $data = json_decode($response->getContent(), true);

        //verify the response is not null
        $this->assertNotNull($response);
        //verify the response status is 200 Ok
        $this->assertEquals(200, $response->getStatusCode());

        //verify if the fields exist in the data response
        $this->assertInternalType('array', $data['data']);
        $this->assertInternalType('array', $data['meta']);
        $this->assertInternalType('array', $data['meta']['pagination']);
        $this->assertArrayHasKey('total', $data['meta']['pagination']);
        $this->assertArrayHasKey('count', $data['meta']['pagination']);
        $this->assertArrayHasKey('per_page', $data['meta']['pagination']);
        $this->assertArrayHasKey('current_page', $data['meta']['pagination']);
        $this->assertArrayHasKey('total_pages', $data['meta']['pagination']);
        $this->assertArrayHasKey('links', $data['meta']['pagination']);
        foreach ($data['data'] as $reportTableData) {
            $this->verifyStructure($reportTableData);
        }
    }

    /**
     * Verify structure of response
     *
     * @param array $data
     */
    private function verifyStructure($data)
    {
        //verify if the fields exist in the data response
        $this->assertArrayHasKey('uid', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('process', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('grid', $data);
        $this->assertArrayHasKey('tag', $data);

        $this->assertInternalType('array', $data['fields']);
    }

    /**
     * Populate table for test
     */
    private function createDataReportTable()
    {
        $reportTable = ReportTable::All()->toArray();
        if (count($reportTable) < 3) {
            factory(ReportTable::class, 10)->create();
        }
    }
}
