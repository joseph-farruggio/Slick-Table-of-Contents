
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

?>

<?php if ( get_field('method') == 'auto') : ?>
    <?php if ( $is_preview ): ?>
        
        <div id="<?php echo esc_attr($block['id']); ?>" class="<?php echo esc_attr($className); ?>" <?php echo $style; ?>>
            <?php if ( get_field('title') ): ?>
                <h2 class="ignore"><?php the_field('title'); ?></h2>
            <?php endif; ?>
            
            <?php echo $tag[0]; ?>
                <li><a href="#">This placeholder table of contents is only visible in the editor</a></li>
                <li>
                    <a href="#">Check the frontend for the real table of contents</a>
                    <?php if ( strpos($headingList, 'h3') !== FALSE ) : ?>
                        <?php echo $tag[0]; ?>
                            <li><a href="#">This is a subheading</a></li>
                            <li><a href="#">This is another subheading</a></li>
                        <?php echo $tag[1]; ?>
                    <?php endif; ?>
                </li>
                <li>
                    <a href="">This is a another heading</a>
                    <?php if ( strpos($headingList, 'h3') !== FALSE ) : ?>
                        <?php echo $tag[0]; ?>
                            <li><a href="#">This is a subheading</a></li>
                            <li><a href="#">This is another subheading</a></li>
                        <?php echo $tag[1]; ?>
                    <?php endif; ?>
                </li>
            <?php echo $tag[1]; ?>
        </div>
    <?php else: ?>
        <div class="<?php echo esc_attr($className); ?>" <?php echo $style; ?>>
            <?php if ( get_field('title') ): ?>
                <h2 class="ignore"><?php the_field('title'); ?></h2>
            <?php endif; ?>
            <div id="<?php echo esc_attr($block['id']); ?>" class="<?php echo $colClassName; ?>" x-data="toc('<?php echo esc_attr($block['id']); ?>', '<?php the_field('headings'); ?>', <?php echo $orderedList; ?>)" ></div>
        </div>
    <?php endif; ?>
<?php else: $items = explode("\n", get_field('custom_items') ); ?>
    <div id="<?php echo esc_attr($block['id']); ?>" class="<?php echo esc_attr($className); ?>" <?php echo $style; ?>>
        <?php echo $tag[0]; ?>
        <?php foreach ((array) $items as $item) : ?>
                <?php $item = explode(' : ', $item); ?>
                <li class="toc-list-item">
                    <a href="<?php echo $item[0]; ?>" class="toc-link"><?php echo $item[1]; ?></a>
                </li>
            <?php endforeach; ?>
        <?php echo $tag[1]; ?>
    </div>
<?php endif; ?>