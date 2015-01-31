<?php

 /**
 * Video_Embed
 *
 * @author JoseRobinson.com
 * @link GitHup: https://github.com/jrobinsonc/Video_Embed
 * @version 1.4.1
 */
class Video_Embed
{
    private $video_url;
    private $html_attrs;

    public function __construct($video_url = '', $html_attrs = '')
    {
        $this->video_url = $video_url;
        $this->html_attrs = $html_attrs;
    }

    public function __toString()
    {
        $video_embed_url = $this->get_embed_url($this->video_url);

        if (false === $video_embed_url)
            return '';

        return sprintf('<iframe src="%s" frameborder="0" %s webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
            $video_embed_url,
            $this->html_attrs
        );
    }

    public function get_embed_url($video_url)
    {
        // Vimeo
        if (strpos($video_url, 'vimeo.com') !== false)
            $video_url = sprintf('//player.vimeo.com/video/%s', $this->get_vimeo_video_id($video_url));
        
        // Youtube
        else if (strpos($video_url, 'youtu.be') !== false || strpos($video_url, 'youtube.com') !== false)
            $video_url = sprintf('//www.youtube-nocookie.com/embed/%s', $this->get_youtube_video_id($video_url));

        return isset($video_url)? $video_url : false;
    }

    public function get_youtube_video_id($url)
    {
        if (preg_match('#//youtu.be/(.+)$#', $url, $matches) === 1) 
        {
            $video_id = $matches[1];
        } 
        else if (strpos($url, '?') !== false) 
        {
            $url_query = parse_url($url, PHP_URL_QUERY);

            if (strlen($url_query) > 0) 
            {
                parse_str($url_query);

                if (isset($v))
                {
                    $video_id = $v;
                }
            }
        }
        
        return isset($video_id)? $video_id : false;
    }

    public function get_youtube_video_image($video_id, $thumb_type = '0')
    {
        $thumbs_types_list = array(
            '0','1','2','3',
            'default', // The default thumbnail image.
            'hqdefault', // The high quality version of the thumbnail.
            'mqdefault', // The medium quality version of the thumbnail.
            'sddefault', // The standard quality version of the thumbnail.
            'maxresdefault' // The maximum quality version of the thumbnail.
        );

        if (! in_array($thumb_type, $thumbs_types_list))
            $thumb_type = $thumbs_types_list[0];

        return sprintf('http://img.youtube.com/vi/%s/%s.jpg', $video_id, $thumb_type);
    }

    public function get_youtube_video_data($video_id)
    {
        return json_decode(file_get_contents("http://gdata.youtube.com/feeds/api/videos/{$video_id}?alt=json"), TRUE);
    }

    public function get_vimeo_video_id($url)
    {
        if (preg_match('#vimeo.com/[a-z0-9-]+/[a-z0-9-]+$#', $url, $matches) === 1)
        {
            $headers = get_headers($url);

            for($i = count($headers) - 1; $i >= 0; $i--) 
            {
                $header = $headers[$i];

                if(strpos($header, "Location: /") === 0)
                    $video_id = substr($header, strlen("Location: /"));
            }
        } 
        else if (preg_match('#vimeo.com/(video/)?(.+)$#', $url, $matches) === 1) 
        {
            $video_id = $matches[2];
        } 

        return isset($video_id)? $video_id : false;
    }

    public function get_vimeo_video_image($video_id)
    {
        $data = $this->get_vimeo_video_data($video_id);
        
        return $data[0]->thumbnail_large;
    }

    public function get_vimeo_video_data($video_id)
    {
        return json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$video_id}.json"), TRUE);
    }
}
