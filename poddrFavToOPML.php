<?php

$homeDir = getenv("HOME");
$favourites = "$homeDir/snap/poddr/current/.config/poddr/favourites.json";
$subscriptions = "$homeDir/Downloads/subscriptions.xml";
if (!file_exists($favourites)) {
  echo 'Poddr favourites.json not found.';
  exit;
} else {
  if (file_exists($subscriptions)) {
    if (!unlink($subscriptions)) {
      echo "Could not delete $subscriptions, please manually delete and try again.";
      exit;
    } else {
      echo "Successfully deleted current $subscriptions, proceeding to generate new one.";
    }
  }
  $dom =  new DOMDocument;
  $dom->encoding = 'utf-8';
  $dom->xmlVersion = '1.0';
  $dom->formatOutput = true;
  $root = $dom->createElement('opml');
  $root->setAttribute('version', '1.0');
  $head_node = $dom->createElement('head');
  $root->appendChild($head_node);
  $title_node = $dom->createElement('title', 'Feeds Generated with PoddrToOPML');
  $head_node->appendChild($title_node);
  $body_node = $dom->createElement('body');
  $root->appendChild($body_node);
  $outline_node = $dom->createElement('outline');
  $body_node->appendChild($outline_node);
  $outline_node->setAttribute('text', 'feeds');
  $arr = json_decode(file_get_contents("$homeDir/snap/poddr/5/.config/poddr/favourites.json"), true);
  foreach($arr as $item) {
    $child_outline_node = $dom->createElement('outline');
    $outline_node->appendChild($child_outline_node);
    $child_outline_node->setAttribute('type', 'rss');
    $child_outline_node->setAttribute('text', htmlspecialchars($item['title']));
    $child_outline_node->setAttribute('title', htmlspecialchars($item['title']));
    $child_outline_node->setAttribute('xmlUrl', $item['rss']);
  }
  $dom->appendChild($root);
  $dom->save($subscriptions);
}

?>