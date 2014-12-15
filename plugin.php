<?php

class MesphotosCdn extends KokenPlugin {

        function __construct() {
            $this->register_filter("api.content","render_api");
            $this->register_filter("site.output","render_site");
        }

        function render_api($data) {
            $live = ($_SERVER['SCRIPT_URL'] != "/preview.php");
            if (trim($this->data->cdn_host)!="" && $this->data->cdn_image==1 && $live) {
                $url_parts = parse_url(base_url("/"));
                $data['cache_path']['prefix']=str_replace($url_parts['host'],trim($this->data->cdn_host),$data['cache_path']['prefix']);
                foreach ($data['presets'] as $quality => $details) {
                    $data['presets'][$quality]['url']=str_replace($url_parts['host'],trim($this->data->cdn_host),$details['url']);
                    $data['presets'][$quality]['hdpi_url']=str_replace($url_parts['host'],trim($this->data->cdn_host),$details['hdpi_url']);
                    $data['presets'][$quality]['cropped']['url']=str_replace($url_parts['host'],trim($this->data->cdn_host),$details['cropped']['url']);
                    $data['presets'][$quality]['cropped']['hdpi_url']=str_replace($url_parts['host'],trim($this->data->cdn_host),$details['cropped']['hdpi_url']);
                }

                $is_ssl = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1 : $_SERVER['SERVER_PORT'] == 443;
                if ($this->data->cdn_http==1 && $is_ssl) {
                    $data['cache_path']['prefix']=str_replace("https://","http://",$data['cache_path']['prefix']);
                    foreach ($data['presets'] as $quality => $details) {
                        $data['presets'][$quality]['url']=str_replace("https://","http://",$details['url']);
                        $data['presets'][$quality]['hdpi_url']=str_replace("https://","http://",$details['hdpi_url']);
                        $data['presets'][$quality]['cropped']['url']=str_replace("https://","http://",$details['cropped']['url']);
                        $data['presets'][$quality]['cropped']['hdpi_url']=str_replace("https://","http://",$details['cropped']['hdpi_url']);
                    }
                }
            }
            return $data;
        }

        function render_site($data) {
            $live = ($_SERVER['SCRIPT_URL'] != "/preview.php");
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

