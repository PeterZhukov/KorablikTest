<?php

namespace PeterZhukov\KorablikTestBundle\Services;


interface ApiDownloaderInterface
{
    public function download($url);
}