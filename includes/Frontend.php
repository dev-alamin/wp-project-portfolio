<?php
namespace WPPP;

class Frontend{
    public function __construct(){
        new \WPPP\Assets();
        new \WPPP\Frontend\Shortcode();
    }
}