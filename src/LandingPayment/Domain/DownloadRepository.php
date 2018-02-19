<?php

namespace LandingPayment\Domain;

interface DownloadRepository {

    /**
     * @param Download $download
     */
    public function save($download);
}
