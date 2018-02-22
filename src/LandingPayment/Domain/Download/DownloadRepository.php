<?php

namespace LandingPayment\Domain\Download;

interface DownloadRepository {

    /**
     * @param Download $download
     */
    public function save(Download $download);
}
