<?php
use rocco\ArangoORM\DB\DB;
use triagens\ArangoDb\Document;

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class ModelsTest extends BaseTest {

    static $test_vertex = "im_a_vertex_collection";
    static $test_edge = "im_an_edge_collection";


    function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $ch = DB::getCollectionHandler();
        $ch->create( self::$test_vertex );
    }

    function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $ch = DB::getCollectionHandler();
        $ch->drop( self::$test_vertex );
    }

    function testConstruct(){

        $vertexOne = TestVertexModel::create( [
            "name"  =>  "Vertex One"
        ] );
        $vertexTwo = TestVertexModel::create( [
            "name"  =>  "Vertex Two"
        ] );
        $edge = TestEdgeModel::create(
            $vertexOne, $vertexTwo,
            [
                "name"  =>  "optional"
            ]
        );

        $cursor = DB::getAll( TestVertexModel::$collection );
        self::assertTrue( $cursor->getCount() > 0 );
        self::assertInstanceOf( TestEdgeModel::class, $edge );
    }

    function testSearch(){
        // TODO: Make sure this passes after the search method is ready
        $a = Document::createFromArray( [ "propertyOne" => "A B C D"]);
        $b = Document::createFromArray( [ "propertyOne" => "A B E F G"]);
        $c = Document::createFromArray( [ "propertyOne" => "A C D E J K"]);

        $ch = DB::getCollectionHandler();
        $ch->create( "search_test" );
        $dh = DB::getDocumentHandler();
        $dh->save( "search_test", $a );
        $dh->save( "search_test", $b );
        $dh->save( "search_test", $c );

        $has_B = SearchTestModel::search( "B" );
        $has_F = SearchTestModel::search( "F" );
        $has_A = SearchTestModel::search( "A" );

        self::assertEquals( 2, count($has_B) );
        self::assertEquals( 1, count($has_F) );
        self::assertEquals( 3, count($has_A) );
    }

}

class TestVertexModel extends \rocco\ArangoORM\Models\Core\VertexModel {
    static $collection = "im_a_vertex_collection";
}
class TestEdgeModel extends \rocco\ArangoORM\Models\Core\EdgeModel {
    static $collection = "im_an_edge_collection";
}

class SearchTestModel extends \rocco\ArangoORM\Models\Core\VertexModel {
    static $collection = "search_test";
}