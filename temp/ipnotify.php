<?php
echo '----start----------', "\n";
class Linenotify
{
    private $curl = '';
    private $url = 'https://notify-api.line.me/api/notify';
    private $token = '';
    private $header = '';
    private $post = '';
    private $result = '';

    public $maxlengthnotify = 1000;
    public $message = ' ';
    public $stickerPackageId = '';
    public $stickerId = '';
    public $image = '';

    public function __construct($token = '')
    {
        $this->token = $token;
        $this->headers = ['Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $this->token];
        $this->init();
    }

    private function init()
    {
        $this->curl = curl_init($this->url);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    private function queryurl($post = [])
    {
        if ($post) {
            $this->post = rawurldecode(http_build_query($post));
        } else {
            $posts = [];
            if ($this->stickerPackageId && $this->stickerId) {
                $posts['stickerPackageId'] = $this->stickerPackageId;
                $posts['stickerId'] = $this->stickerId;
            }
            if ($this->image) {
                $posts['imageFullsize'] = $this->image;
                $posts['imageThumbnail'] = $this->image;
            }
            $posts['message'] = $this->message ?: '';
            if ($posts) {
                $this->post = rawurldecode(http_build_query($posts));
            }
        }
    }

    public function send($post = '')
    {
        $rs = new \stdClass();
        $rs->status = -1; // -1 message is null   0 cannot sent is have error    1 send successed
        if (mb_strlen($this->message) > $this->maxlengthnotify) {
            $rs->status = 0;
            $rs->error = 'message maxlength > ' . $this->maxlengthnotify;
        }
        if ($this->token) {
            $this->queryurl($post);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->post);
            $result = curl_exec($this->curl);
            if (curl_error($this->curl)) {
                $rs->status = 0;
                $rs->error = curl_error($this->curl);
            } else {
                $rs->status = 1;
                $rs->result = json_decode($result);
            }
            curl_close($this->curl);
        } else {
            $rs->staus = 0;
            $rs->error = 'no token';
        }
        $this->result = $rs;
        return $rs;
    }

    public function __toString()
    {
        return json_encode($this->result);
    }
}

$ip = file_get_contents('./ip.txt');
echo 'Old ip is:', $ip, "\n";
$externalContent = file_get_contents('http://checkip.dyndns.com/');
preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
$externalIp = $m[1];
echo 'New ip is:', $externalIp, "\n";
if ($ip != $externalIp) {
    echo $externalIp;
    file_put_contents('./ip.txt', $externalIp);
    $token = "qRv1u98l1gWMfDeyHqQ6dYP9sWA54WUOyw5XwwqTAZn";
    $ln = new Linenotify($token);
    $str = $externalIp;
    $ln->message = $str;
    echo 'send line notirfy----->';
    $rs = $ln->send();
}
