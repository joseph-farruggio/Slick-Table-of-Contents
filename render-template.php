
<?
// Create id attribute allowing for custom "anchor" value.
if( !empty($block['anchor']) ) {
    $block['id'] = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'slick-table-contents';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

$style = "";

$bgColor = (get_field('background_color')) ? 'background-color: ' . get_field('background_color') . ';' : '';
$textColor = (get_field('text_color')) ? 'color: ' . get_field('text_color') . ';' : '';
$padding = (get_field('background_color')) ? 'padding: 2rem;' : '';

$style = implode(" ", array($bgColor, $textColor, $padding));

$tag = ( get_field('list_style') == 'number' ) ? array('<ol>','</ol>') : array('<ul>','</ul>');

$headingList = implode(", ", get_field('headings') );

if ( has_blocks( $post->post_content ) ) {  ?>
    <style>
        .slick-table-contents a {
            color: inherit; 
        }
    </style>

    <div id="<?= $block['id']; ?>" class="<?= $className; ?>" style="<?= $style; ?>">
        <?php if ( get_field('title') ): ?>
            <h2><?php the_field('title'); ?></h2>
        <?php endif; ?>

        <?php
        // ul or ol
        echo $tag[0];
        
        $post = get_post(); 
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
        
        echo $tag[1];
    echo "</div>";
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