<?php
/**
 * Created by PhpStorm.
 * User: Youi
 * Date: 2015-12-03
 * Time: 22:45
 */

main();

function main() {
    !isset($_GET['url']) && exit_script('Hello Tumblr OneDrive for Business FileName!');

	$filename = parse_url($_GET['url'], PHP_URL_PATH);
	$filename = str_replace("/", "", $filename);
	$filename = str_replace(":", "", $filename);
	echoTxt($filename) && exit_script();
}

function echoTxt($content) {
    header('Content-Type: text/plain');

    echo $content;

    return true;
}

function exit_script($message = null) {
    exit($message);
}