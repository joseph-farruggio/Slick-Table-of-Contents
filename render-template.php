
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

$bgColor = (get_field('background_color')) ? 'background-color: ' . get_field('background_color') . ';' : '';
$textColor = (get_field('text_color')) ? 'color: ' . get_field('text_color') . ';' : '';
$padding = (get_field('background_color')) ? 'padding: 2rem;' : '';

$style = implode(" ", array($bgColor, $textColor, $padding));

$tag = ( get_field('list_style') == 'number' ) ? array('<ol>','</ol>') : array('<ul>','</ul>');

$headingList = implode(", ", get_field('headings') );

$post = get_post(); 
?>
    <style>
        .editor-styles-wrapper .wp-block .slick-table-contents a, .slick-table-contents a {
            color: inherit; 
        }
    </style>

    <div id="<?= $block['id']; ?>" class="<?= $className; ?>" style="<?= $style; ?>">
        <?php if ( get_field('title') ): ?>
            <h2><?php the_field('title'); ?></h2>
        <?php endif; ?>

        <?php
        if (!$is_preview) {
            // ul or ol
            echo $tag[0];

            if ( has_blocks( $post->post_content ) ) { 
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
            }
            echo $tag[1];
        }
        else {
            echo $tag[0]; ?>
                <li><a href="#">This placeholder table of contents is only visible in the editor</a></li>
                <li>
                    <a href="#">Check the frontend for the real table of contents</a>
                        <?php echo $tag[0]; ?>
                            <li><a href="#">This is a subheading</a></li>
                            <li><a href="#">This is another subheading</a></li>
                        <?php echo $tag[1]; ?>
                </li>
                <li>
                    <a href="">This is a another heading</a>
                  
                        <?php echo $tag[0]; ?>
                            <li><a href="#">This is a subheading</a></li>
                            <li><a href="#">This is another subheading</a></li>
                        <?php echo $tag[1]; ?>
                </li>
            <?php echo $tag[1];
        }
    echo "</div>";

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