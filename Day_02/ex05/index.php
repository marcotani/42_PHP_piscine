<?php

require_once 'Elem.php'; // Your class with validPage()

function printTest($label, $elem) {
    echo "=== Testing: $label ===\n";
    if ($elem->validPage()) {
        echo "✅ Passed\n\n";
    } else {
        echo "❌ Failed\n\n";
    }
}

// ------------------ Rule 1 ------------------

function testRule1_valid() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));
    $head->pushElement(new Elem('title', 'My Title'));

    $body = new Elem('body');

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

function testRule1_invalid() {
    $html = new Elem('html');
    $body = new Elem('body');
    $head = new Elem('head');
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));
    $head->pushElement(new Elem('title', 'My Title'));

    $html->pushElement($body);
    $html->pushElement($head); // Wrong order
    return $html;
}

// ------------------ Rule 2 ------------------

function testRule2_invalid_multiple_title() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));
    $head->pushElement(new Elem('title', 'Title 1'));
    $head->pushElement(new Elem('title', 'Title 2'));

    $body = new Elem('body');

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

function testRule2_invalid_no_meta() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'Missing Meta'));

    $body = new Elem('body');

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

// ------------------ Rule 3 ------------------

function testRule3_invalid_p_with_span() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'Title'));
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));

    $p = new Elem('p');
    $p->pushElement(new Elem('span', 'bad')); // ❌ invalid

    $body = new Elem('body');
    $body->pushElement($p);

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

// ------------------ Rule 4 ------------------

function testRule4_invalid_tr_with_div() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));
    $head->pushElement(new Elem('title', 'Title'));

    $tr = new Elem('tr');
    $tr->pushElement(new Elem('div', 'wrong')); // ❌ invalid

    $table = new Elem('table');
    $table->pushElement($tr);

    $body = new Elem('body');
    $body->pushElement($table);

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

function testRule4_invalid_table_with_div() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));
    $head->pushElement(new Elem('title', 'Title'));

    $div = new Elem('div', 'bad');
    $table = new Elem('table');
    $table->pushElement($div); // ❌ invalid

    $body = new Elem('body');
    $body->pushElement($table);

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

// ------------------ Rule 5 ------------------

function testRule5_invalid_ul_with_p() {
    $html = new Elem('html');
    $head = new Elem('head');
    $head->pushElement(new Elem('meta', null, ['charset' => 'UTF-8']));
    $head->pushElement(new Elem('title', 'Title'));

    $ul = new Elem('ul');
    $ul->pushElement(new Elem('li', 'Good'));
    $ul->pushElement(new Elem('p', 'Bad')); // ❌ invalid

    $body = new Elem('body');
    $body->pushElement($ul);

    $html->pushElement($head);
    $html->pushElement($body);
    return $html;
}

// ------------------ Run Tests ------------------

printTest("Rule 1: Valid [html → head, body]", testRule1_valid());
printTest("Rule 1: Invalid order of head/body", testRule1_invalid());

printTest("Rule 2: Invalid - multiple <title>", testRule2_invalid_multiple_title());
printTest("Rule 2: Invalid - missing <meta charset>", testRule2_invalid_no_meta());

printTest("Rule 3: Invalid - <p> contains tag", testRule3_invalid_p_with_span());

printTest("Rule 4: Invalid - <tr> contains <div>", testRule4_invalid_tr_with_div());
printTest("Rule 4: Invalid - <table> contains <div>", testRule4_invalid_table_with_div());

printTest("Rule 5: Invalid - <ul> contains <p>", testRule5_invalid_ul_with_p());
