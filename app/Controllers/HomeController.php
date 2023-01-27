<?php

namespace App\Controllers;

use DateTime;
use App\Database\LogsDB;
use App\Controllers\AbstractController;
use Slim\Http\ServerRequest as Request;
use Psr\Http\Message\ResponseInterface as Response;


class HomeController extends AbstractController
{


    public function __construct($container)
    {
        parent::__construct($container);
    }

    /**
     * Return the "Status" view
     * @param Request $request
     * @param Response $response
     * @return HTML
     */
    public function status(Request $request, Response $response)
    {
        $logs = LogsDB::get()->toArray();
        $busy = LogsDB::whereNull('end')->count()>=1?true:false;
        
        return $this->withJSON($response, [
            "status" => "OK",
            'busy' => $busy,
            "logs"=>$logs
        ]);
    }

    public function convert(Request $request, Response $response)
    {
        $file = $_FILES['file'];

        // Vérification de l'existence du fichier
        if (!isset($file)) {
            return $this->withStatus($response,400,"Please POST a 'file' in body of query.");
        }

        $this->logger->debug("Input File array is : \n" . print_r($file, true));
        $log = LogsDB::Create([
            "file" => $file['name'],
            "tmp_name" => $file['tmp_name'],
            'size' => $file['size'],
            'start' => date("Y-m-d H:i:s"),
            'end' => null,
            'status' => 'created'
        ]);
        $log->save();

        // Définition du chemin du fichier
        $filePath = $file['tmp_name'];

        $this->logger->debug("File Path is : " . $filePath . "\n");
        // Exécution de l'opération de type shell sur le fichier
        $shellCommand = "/opt/convert_to_cbz.py \"$filePath\"";
        exec($shellCommand, $output, $returnValue);

        //$output = shellRun($shellCommand);
        $this->logger->debug("exec command was : " . $shellCommand . "\n");
        $this->logger->debug("exec output : " . print_r($output, true) . "\n");
        $this->logger->debug("exit code : " . intval($returnValue) . "\n");

        $log->end=date("Y-m-d H:i:s");
        $log->save();
        /* If the exit code of the shell command is not 0, then the script will return a 500 error. */
        if ($returnValue != 0) {
            $log->status="Failed";
            $log->save();
            $this->withStatus($response,500,"There was an error processing  file.\n" . print_r($output, true));
        }

        $log->status="Success";
        $log->save();
        $this->logger->debug("Should send : " . $output[0] . "\n");


        $returnFile = $output[0];
        if (file_exists($returnFile)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . replace_extension($file['name'], "cbz") . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($returnFile));
            readfile($returnFile);
            //Delete file after upload
            unlink($returnFile);
            exit;
        } else {
            http_response_code(500);
            exit("There was an error processing the file.\n" . $returnFile . "\n" . print_r($output, true));
        }
    }
}
