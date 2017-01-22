<?php
/**
 * Created by PhpStorm.
 * User: Youi
 * Date: 2015-12-03
 * Time: 22:45
 */

main();

function main() {
    !isset($_GET['url']) && exit_script('Hello Tumblr Video Url!');

    $query_param = get_query_param($_GET['url']);
    !$query_param && echoTxt("NOT VALID TUMBLR URL: [{$_GET['url']}]") && exit_script();

    $post_info = query_tumblr_api($query_param);
    !$post_info && echoTxt("NO POST INFO FETCHED FROM TUMBLR WITH GIVEN URL: [{$_GET['url']}], THE POST MIGHT BE DELETED") && exit_script();

    $post_info = $post_info['posts'][0];
    switch ($post_info['type']) {
        case 'video':
            $url = get_video_url($post_info);
            echoTxt($url) && exit_script();
            break;
		case 'link':
		case 'regular':
		case 'answer':
        case 'photo':
        default:
            echoTxt("NOT VIDEO POST") && exit_script();
            break;
    }
}

function get_query_param($url) {
    if (preg_match('<https?://(.+)/post/(\d+)>', $url, $match)) {
        return array(
            'post_domain' => $match[1],
            'post_id'     => $match[2]
        );
    } else {
        return false;
    }
}

function query_tumblr_api($query_param) {
    $api_url = "http://{$query_param['post_domain']}/api/read/json?id={$query_param['post_id']}";

    $i = 0;
    do {
        $json_str    = file_get_contents($api_url);
        $status_code = (int)parseHeaders($http_response_header, 'status');
    } while (strlen($json_str) < 10 && $i++ < 3 && $status_code !== 404);

    if (preg_match('<\{.+\}>', $json_str, $match)) {
        return json_decode($match[0], true);
    } else {
        return false;
    }
}

function parseHeaders(array $headers, $header = null) {
    $output = array();

    if ('HTTP' === substr($headers[0], 0, 4)) {
        list(, $output['status'], $output['status_text']) = explode(' ', $headers[0]);
        unset($headers[0]);
    }

    foreach ($headers as $v) {
        $h                         = preg_split('/:\s*/', $v);
        $output[strtolower($h[0])] = $h[1];
    }

    if (null !== $header) {
        if (isset($output[strtolower($header)])) {
            return $output[strtolower($header)];
        }

        return null;
    }

    return $output;
}

function get_video_url($post_info) {
    $video_source = $post_info['video-source'];
    if ($video_info = unserialize($video_source)) {
        $video_info = $video_info['o1'];
        $video_id   = substr($video_info['video_preview_filename_prefix'], 0, -1);

        return "http://vt.tumblr.com/$video_id.mp4";
    }

    if (preg_match('<src="(.+?)">', $video_source, $match)) {
        return $match[1];
    }

    return false;
}

function echoTxt($content) {
    header('Content-Type: text/plain');

    echo $content;

    return true;
}

function exit_script($message = null) {
    exit($message);
}