<?php

namespace FlexPhp\Core\Http;

class Response
{
    protected string $content;
    protected int $status;
    protected string $statusText;
    protected array $headers;
    protected string $version;

    const STATUS_TEXTS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Content Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Content',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->headers = $headers;
        $this->setContent($content);
        $this->setStatus($status);
        $this->setVersion('1.0');
    }

    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
        $this->statusText = self::STATUS_TEXTS[$status] ?? 'unknown status';

        return $this;
    }

    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }

    public function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        foreach ($this->headers as $name => $values) {
            $replace = 0 === strcasecmp($name, 'Content-Type');
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $value) {
                header($name.': '.$value, $replace, $this->status);
            }
        }

        header(sprintf('HTTP/%s %s %s', $this->version, $this->status, $this->statusText), true, $this->status);
        return $this;
    }

    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }
}