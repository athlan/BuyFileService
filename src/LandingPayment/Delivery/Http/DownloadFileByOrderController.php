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

    public function __construct(DownloadContentUC $downloadContentUC) {
        $this->downloadContentUC = $downloadContentUC;
    }

    public function download(Request $request, $orderId) {
        $result = $this->downloadContentUC->canDownloadContent($orderId);

        if ($result === false) {
            throw new NotFoundHttpException();
        }

        $ip = $request->getClientIp();
        $product = $this->downloadContentUC->doDownload($orderId, $ip);
        $productMetadata = $product->getMetadata();
        $fileToStream = $productMetadata['filestream.path'];

        $response = BinaryFileResponse::create($fileToStream);
        $response->expire();
        $response->mustRevalidate();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
}
