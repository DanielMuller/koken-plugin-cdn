<?php

class MesphotosCdn extends KokenPlugin {

        function __construct() {
            $this->require_setup = true;
            $this->register_filter("api.content","render_api");
            $this->register_filter("site.output","render_site");
        }

        function render_api($data) {
            $live = ($_SERVER['SCRIPT_NAME'] != "/preview.php");
            if (trim($this->data->cdn_host)!="" && $this->data->cdn_image==1 && $live) {
                $data['cache_path']['prefix'] = $this->replace_host($data['cache_path']['prefix']);
                foreach ($data['presets'] as $quality => $details) {
                    $data['presets'][$quality]['url'] = $this->replace_host($details['url']);
                    $data['presets'][$quality]['hidpi_url'] = $this->replace_host($details['hidpi_url']);
                    $data['presets'][$quality]['cropped']['url'] = $this->replace_host($details['cropped']['url']);
                    $data['presets'][$quality]['cropped']['hidpi_url'] = $this->replace_host($details['cropped']['hidpi_url']);
                }
            }
            return $data;
        }

        function replace_host($url) {
            $url_parts = parse_url($url);

            $is_ssl = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1 : $_SERVER['SERVER_PORT'] == 443;
            if ($is_ssl) {
                if ($this->data->cdn_http==1) $scheme="http";
                else $scheme="https";
            }
            else {
                $scheme="http";
            }

            $url_parts['host'] = trim($this->data->cdn_host);
            $url_parts['scheme'] = $scheme;

            return $url_parts['scheme']."://".$url_parts['host'].(array_key_exists('path',$url_parts) ? $url_parts['path'] : "").( array_key_exists('query',$url_parts) ? "?".$url_parts['query'] : "");
        }

        function render_site($data) {
            $live = ($_SERVER['SCRIPT_NAME'] != "/preview.php");
            if (trim($this->data->cdn_host)!="" && $live) {
                $proto = ($this->data->cdn_http==1) ? "http:" : "" ;
                $pattern = Array();
                $replacement = Array();
                $std_replacement = '\1'.$proto.'//'.trim($this->data->cdn_host).'\2';

                if ($this->data->cdn_js==1) {
                    $pattern[] = '/(script.+src=")(\/[^\/][^"]+")/';
                    $replacement[] = $std_replacement;
                }
                if ($this->data->cdn_css==1) {
                    $pattern[] = '/(rel="stylesheet".+href=")(\/[^\/][^"]+")/';
                    $replacement[] = $std_replacement;
                }
                if ($this->data->cdn_icon==1) {
                    $pattern[] = '/(rel=".*icon".+href=")(\/[^\/][^"]+")/';
                    $replacement[] = $std_replacement;
                }
                if (sizeof($pattern)>0) {
                    return preg_replace($pattern,$replacement,$data);
                }
            }
            return $data;
        }
}
