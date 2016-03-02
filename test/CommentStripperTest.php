<?php

namespace Mleko\ValidateCommit\Test;

class CommentStripperTest extends \PHPUnit_Framework_TestCase
{

    public function dataProvider()
    {
        return [
            ["Add composer hook\n", file_get_contents(__DIR__ . '/samples/commit.with.comment.txt')],
            ["Add composer hook\nBody message\n", file_get_contents(__DIR__ . '/samples/commit.with.mixed.comment.txt')],
            ["Add composer hook", file_get_contents(__DIR__ . '/samples/commit.with.comment.no.end.line.txt')]
        ];
    }

    /**
     * @param string $expected
     * @param string $message
     * @dataProvider dataProvider
     */
    public function testCommentStrip($expected, $message)
    {
        $stripper = new \Mleko\ValidateCommit\CommentStripper();
        $this->assertEquals($expected, $stripper->stripComments($message));
    }
}
