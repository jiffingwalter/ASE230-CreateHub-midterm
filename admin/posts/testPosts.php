<?php
// POST TESTING
require_once('../../lib/global.php');
require_once($GLOBALS['authAdminOnlyDirectory']);
require_once($GLOBALS['postHandlingDirectory']);

echo '<pre><h1>POST TESTING AREA</h1>';

// testing stage ---------------------------------------------------------------------------------------


// -----------------------------------------------------------------------------------------------------

// tests post comparison functions
function testPostComparison(){
    try {
        // todo -- modularize this
        // test 1
        echo '<h3>testing post comparison...</h3>';
        echo '<h4>test 1, post that is completely identical...</h4>';
        $testPost=get_post(475467);
        $testPost['author']=get_post_author($testPost['pid'])['uid'];
        $testPost['tags']=parse_tags_out($testPost['pid']);
        $testAttachment=get_attachments($testPost['pid'])[0];
        $testAttachment['error']=0;

        $post_comparison=compare_post($testPost,$testAttachment);
        echo '<br><br>';
        var_dump($post_comparison);
        echo (!$post_comparison['textChanged'] && !$post_comparison['tagsChanged'] && !$post_comparison['attachmentChanged'] && !$post_comparison['authorChanged'])?'<h3>post DID NOT change</h3>':'<h3>post DID change</h3>';

        // test 2
        echo '<h4>test 2, post that is NOT identical...</h4>';
        $testPost=get_post(944839);
        $testPost['author']=get_post_author($testPost['pid'])['uid'];
        $testPost['tags']=parse_tags_out($testPost['pid']);
        $testAttachment=get_attachments($testPost['pid'])[0];
        $testAttachment['error']=0;

        $post_comparison=compare_post($testPost,$testAttachment);
        echo '<br><br>';
        var_dump($post_comparison);
        echo (!$post_comparison['textChanged'] && !$post_comparison['tagsChanged'] && !$post_comparison['attachmentChanged'] && !$post_comparison['authorChanged'])?'<h3>post DID NOT change</h3>':'<h3>post DID change</h3>';
    } catch (Throwable $error){
        echo '<h3>Caught error in testPostComparison</h3>';
        echo $error.'<br>';
    }
}