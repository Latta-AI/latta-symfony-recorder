<?php

namespace LattaAi\Recorder\Symfony;

use Exception;
use LattaAi\Recorder\LattaAttachment;
use LattaAi\Recorder\LattaRecorder;
use LattaAi\Recorder\LattaUtils;
use LattaAi\Recorder\models\LattaInstance;
use Symfony\Component\Filesystem\Filesystem;

class LattaSymfonyRecorder extends LattaRecorder
{
    private $filesystem;

    public function __construct($apiKey)
    {
        parent::__construct($apiKey);
        $this->filesystem = new Filesystem();
    }

    public function startRecording($framework, $framework_version, $os, $lang, $device)
    {
        if (!$this->filesystem->exists("latta-instance.txt")) {
            $lattaInstance = $this->api->putInstance($framework, $framework_version, $os, $lang, $device);
            $this->filesystem->appendToFile("latta-instance.txt", $lattaInstance->getId());
        }

        LattaRecorder::$relationID = isset($_COOKIE["Latta-Recording-Relation-Id"]) ? $_COOKIE["Latta-Recording-Relation-Id"] :
            (isset($_SERVER['HTTP_LATTA_RECORDING_RELATION_ID']) ? $_SERVER['HTTP_LATTA_RELATION_ID'] : null);

        if (LattaRecorder::$relationID == null) {
            LattaRecorder::$relationID = LattaUtils::uuidv4();
            setcookie("Latta-Recording-Relation-Id", LattaRecorder::$relationID, time() + (10 * 365 * 24 * 60 * 60), "/");
        }
    }

    public function reportError(Exception $e)
    {
        $lattaInstance = new LattaInstance($this->filesystem->readFile("latta-instance.txt"));
        $lattaSnapshot = $this->api->putSnapshot($lattaInstance, "", null, LattaRecorder::$relationID);

        $attachment = new LattaAttachment($e, LattaRecorder::$logs);

        $this->api->putAttachment($lattaSnapshot, $attachment);
        exit(1);
    }
}