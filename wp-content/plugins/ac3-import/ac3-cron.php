<?php

/**
 * Plugin Name: AC3 Import
 */

global $wpdb;

$table = $wpdb->prefix . "ac3_importer_crons";
$data = $wpdb->get_row("SELECT * FROM {$table}");

$url = $data->url;
$filename = __DIR__  . '/input-ac3.xml';
touch($filename);
chmod($filename, 0777);

// Récupérer le contenu de la ressource distante
$content = file_get_contents($url);

// Sauvegarder le contenu dans un fichier local
file_put_contents($filename, $content);

$xml = simplexml_load_file(__DIR__  . '/input-ac3.xml');

// Créer un nouveau document XML
$outputXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><LISTEPA></LISTEPA>');
$outputXml->addAttribute('date', '2018-11-29 12:37:55');

// Tester pour chaque balise si elle possède des balises enfants
// Si elle n'a pas de balise enfant testé si celle ci possède du contenu ou non (orpheline)
// Si elle a des balises enfant testé si il y a répétitons (balises jumelles) au non
// Si il y a des balises jumelles (on les fusionne) testé si elles ont des enfants ou non et si ceux-ci ont du contenu. Si elles partagent une même balise enfant on les fusionne

foreach ($xml->BIEN as $bien) {
    // Niveau 1
    $levelOutput1 = $outputXml->addChild($bien->getName());
    foreach ($bien->children() as $tagLevel1) {
        // Niveau 2
        $levelOutput2 = $levelOutput1->addChild($tagLevel1->getName());
        // Si tags de niveau 2 ont des tags enfants
        if (count($tagLevel1->children()) > 0) {
            $contentArray = [];
            foreach ($tagLevel1->children() as $tagLevel2) {
                // Niveau 3
                if (count($tagLevel2->children()) > 0) {
                    // Si tags de niveau 3 ont des tags enfants
                    foreach ($tagLevel2->children() as $tagLevel3) {
                        // Niveau 4
                        $content = mb_convert_encoding((string) $tagLevel3, 'ISO-8859-1', 'UTF-8');
                        $contentArray[$tagLevel3->getName()][] = $content;
                    }
                } else {
                    // var_dump($tagLevel2->getName());
                    // Si tags de niveau 3 n'ont pas de tags enfants
                    wrapCDATA($levelOutput2, $tagLevel2->getName(), $tagLevel2);
                }
            }
            foreach ($contentArray as $tag => $content) {
                wrapCDATAChildren($levelOutput2, $tag, $content);
            }
        }
    }
}

$outputXml->asXML(__DIR__  . '/output-test.xml');
chmod(__DIR__  . '/output-test.xml', 0777);



function wrapCDATAChildren($outputXml, $tag, $content)
{
    $element = $outputXml->addChild($tag);
    $dom = dom_import_simplexml($element);
    $cdata = $dom->ownerDocument->createCDATASection(implode('|', $content));
    $dom->appendChild($cdata);
}

function wrapCDATA($outputXml, $tag, $content)
{
    $element = $outputXml->addChild($tag);
    $dom = dom_import_simplexml($element);
    $cdata = $dom->ownerDocument->createCDATASection($content);
    $dom->appendChild($cdata);
}
