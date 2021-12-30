
<?
// Create id attribute allowing for custom "anchor" value.
if( !empty($block['anchor']) ) {
    $block['id'] = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'toc';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}


if ( get_field('background_color') )  {
    $className .= ' has-background';
}

if ( get_field('text_color') )  {
    $className .= ' has-text-color';
}

$style = "";

$bgColor = (get_field('background_color')) ? 'background-color: ' . get_field('background_color') . '; ' : '';
$textColor = (get_field('text_color')) ? 'color: ' . get_field('text_color') . '; ' : '';

$style = 'style="' . implode(" ", array($bgColor, $textColor)) .'"';

if ( get_field('text_color') ) {
    $className .= ' ' . get_field('text_color');
}

if ( get_field('layout') == 'horizontal' ) {
    $className .= ' horizontal';
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$colClassName = ( get_field('columns') ) ? 'has-text-columns' : '';


$orderedList = ( get_field('list_style') == 'number' ) ? true : false;
$tag = ( get_field('list_style') == 'number' ) ? array('<ol class="tocList '.$colClassName.'">','</ol>') : array('<ul class="tocList '.$colClassName.'">','</ul>');
$headingList = implode(", ", get_field('headings') );

$post = get_post(); 

if ( has_blocks( $post->post_content ) ) {
    echo "<ul>";

    $blocks = parse_blocks( $post->post_content );
    $i = 0;
    foreach( $blocks as $block ) {
        if ( $blocks[$i]['blockName'] === 'core/heading' ) {
            $fullstring = $blocks[$i]['innerHTML'];
            $parsed = get_string_between($fullstring, '>', '</h');
            echo "<li><a href='#" . toSafeID($parsed) . "'>" . $parsed . "</a></li>";
        }  
        $i++;  
    }
    
    echo "</ul>";
}

?>

<?php if (!$is_preview): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var headings = content.querySelectorAll('<?= $headingList; ?>');
            var headingMap = {};
            Array.prototype.forEach.call(headings, function (heading) {
                var id = heading.id ? heading.id : heading.textContent.trim().toLowerCase()
                .split(' ').join('-').replace(/[!@#$%^&*():]/ig, '');
                headingMap[id] = !isNaN(headingMap[id]) ? ++headingMap[id] : 0;
                if (headingMap[id]) {
                heading.id = id + '-' + headingMap[id];
                } else {
                heading.id = id;
                }
            });
        });
    </script>
<?php endif; ?>