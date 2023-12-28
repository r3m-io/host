<?php
namespace Package\R3m\Io\Host\Trait;

use R3m\Io\Module\Core;
use R3m\Io\Module\Event;
use R3m\Io\Module\File;

use Exception;

use R3m\Io\Exception\FileAppendException;
use R3m\Io\Exception\ObjectException;

trait Configure {

    /**
     * @throws ObjectException
     * @throws FileAppendException
     * @throws Exception
     */
    public function host_name_add($options=[]): void
    {
        $options = Core::object($options, Core::OBJECT_ARRAY);
        $object = $this->object();
        if($object->config(Config::POSIX_ID) !== 0){
            $exception = new Exception('Only root can configure host add...');
            Event::trigger($object, 'r3m.io.host.configure.host.add', [
                'options' => $options,
                'exception' => $exception
            ]);
            throw $exception;
        }
        $ip = '0.0.0.0';
        if(array_key_exists('ip', $options) || !empty($options['ip'])){
            $ip = $options['ip'];
        } else {
            $options['ip'] = $ip;
        }
        $host = false;
        if(array_key_exists('host', $options) || !empty($options['host'])){
            $host = $options['host'];
        }
        if($host === false){
            $exception = new Exception('Host cannot be empty...');
            Event::trigger($object, 'r3m.io.host.configure.host.add', [
                'options' => $options,
                'exception' => $exception
            ]);
            throw $exception;
        }
        $url = '/etc/hosts';
        if(File::exist($url)){
            $data = explode("\n", File::read($url));
            foreach($data as $nr => $row){
                if(stristr($row, $host) !== false){
                    Event::trigger($object, 'r3m.io.host.configure.host.add', [
                        'options' => $options,
                        'is_found' => true
                    ]);
                    return;
                }
            }
            $data = $ip . "\t" . $host . "\n";
            $append = File::append($url, $data);
            echo 'Ip: ' . $ip  .' Host: ' . $host . ' added.' . "\n";
            Event::trigger($object, 'r3m.io.host.configure.host.add', [
                'options' => $options,
                'is_added' => true
            ]);
        }
    }

    public function host_name_delete(){

    }
}