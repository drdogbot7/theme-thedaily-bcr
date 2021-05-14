<?php
$linkToFileMetadata = get_option('link_to_file_metadata');
$itemFiles = $item->Files;
$visualMedia = array();
$otherFiles = array();
foreach ($itemFiles as $itemFile) {
    $mimeType = $itemFile->mime_type;
    if (strpos($mimeType, 'image') !== false) {
        $visualMedia[] = $itemFile;
    } else {
        $otherFiles[] = $itemFile;
    }
}
$hasVisualMedia = (count($visualMedia) > 0);
if ($hasVisualMedia) {
    queue_css_file('lightslider.min');
    queue_css_file('lightgallery.min');
    queue_js_file('lightgallery-all.min', 'js');
    queue_js_file('lightslider.min', 'js');
    queue_js_file('items-show', 'js');
}
echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show'));
?>

<h1><?php echo metadata('item', 'rich_title', array('no_escape' => true)); ?></h1>

<?php if ($hasVisualMedia): ?>
    <ul id="itemfiles" <?php echo (count($visualMedia) == 1) ? 'class="solo"' : ''; ?>>
        <?php $visualMediaCount = 0; ?>
        <?php foreach ($visualMedia as $visualMediaFile): ?>
        <?php $visualMediaCount++; ?>
        <?php $fileUrl = ($linkToFileMetadata == '1') ? record_url($visualMediaFile) : $visualMediaFile->getWebPath('original'); ?>
        <li 
            data-src="<?php echo $visualMediaFile->getWebPath('original'); ?>" 
            data-thumb="<?php echo $visualMediaFile->getWebPath('square_thumbnail'); ?>" 
            data-sub-html=".media-link-<?php echo $visualMediaCount; ?>"
            class="media resource"
        >
            <div class="media-render">
            <?php echo file_image('original', array(), $visualMediaFile); ?>
            </div>
            <div class="media-link-<?php echo $visualMediaCount; ?>">
            <a href="<?php echo $fileUrl; ?>"><?php echo metadata($visualMediaFile, 'rich_title', array('no_escape' => true)); ?></a>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<?php echo all_element_texts('item'); ?>

<!-- If the item belongs to a collection, the following creates a link to that collection. -->
<?php if (metadata('item', 'Collection Name')): ?>
<div id="collection" class="element">
    <h3><?php echo __('Collection'); ?></h3>
    <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
</div>
<?php endif; ?>

<!-- The following prints a list of all tags associated with the item -->
<?php if (metadata('item', 'has tags')): ?>
<div id="item-tags" class="element">
    <h3><?php echo __('Tags'); ?></h3>
    <div class="element-text"><?php echo tag_string('item'); ?></div>
</div>
<?php endif;?>

<?php if ((count($otherFiles) > 0) && get_theme_option('other_media') == 1): ?>
<div id="other-media" class="element">
    <h3><?php echo __('Files'); ?></h3>
    <?php foreach ($otherFiles as $otherFile): ?>
    <?php $fileUrl = ($linkToFileMetadata == '1') ? record_url($otherFile) : $otherFile->getWebPath('original'); ?>
    <div class="element-text"><a href="<?php echo $fileUrl; ?>"><?php echo metadata($otherFile, 'rich_title', array('no_escape' => true)); ?> - <?php echo $otherFile->mime_type; ?></a></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- The following prints a citation for this item. -->
<div id="item-citation" class="element">
    <h3><?php echo __('Citation'); ?></h3>
    <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
</div>

<div id="item-output-formats" class="element">
    <h3><?php echo __('Output Formats'); ?></h3>
    <div class="element-text"><?php echo output_format_list(); ?></div>
</div>

<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>

<nav>
<ul class="item-pagination navigation">
    <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
</ul>
</nav>

<?php echo foot(); ?>
