<?php

namespace LandingPayment\Delivery\Http;

use LandingPayment\Usecase\DownloadContentUC;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadFileByOrderController {

    /**
     * @var DownloadContentUC
     */
    private $downloadContentUC;

    private $fileToStream;

    public function __construct(DownloadContentUC $downloadContentUC, $fileToStream) {
        $this->downloadContentUC = $downloadContentUC;
        $this->fileToStream = $fileToStream;
    }

    public function download(Request $request, $orderId) {
        $result = $this->downloadContentUC->canDownloadContent($orderId);

        if ($result === false) {
            throw new NotFoundHttpException();
        }

        $ip = $request->getClientIp();
        $this->downloadContentUC->doDownload($orderId, $ip);

        $response = BinaryFileResponse::create($this->fileToStream);
        $response->expire();
        $response->mustRevalidate();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
}
