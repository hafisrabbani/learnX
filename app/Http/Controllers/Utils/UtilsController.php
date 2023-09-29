<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\TTS;
use Illuminate\Http\Request;
use App\Services\FileUpload\FileUploadService;
use GuzzleHttp\Client;

class UtilsController extends Controller
{

    public function randomIcon($returnJson = false)
    {
        // random icon for education
        $fontawesomClassIcon = [
            'fas fa-graduation-cap',
            'fas fa-book',
            'fas fa-book-reader',
            'fas fa-chalkboard-teacher',
            'fas fa-chalkboard',
            'fas fa-user-graduate',
            'fas fa-user-tie',
            'fas fa-rocketchat',
            'fas fa-rocket',
        ];

        $randomColorBs = [
            "primary",
            "secondary",
            "success",
            "danger",
            "warning",
            "info",
        ];

        $data = [
            'icon' => $fontawesomClassIcon[array_rand($fontawesomClassIcon)],
            'color' => $randomColorBs[array_rand($randomColorBs)],
        ];

        if ($returnJson) {
            return response()->json($data);
        }
        return $data;
    }

    public function downloadTugas(Request $request, $filename)
    {
        $type = $request->type ?? 'tugas';
        $fileUploadService = app(FileUploadService::class);
        return $fileUploadService->download($filename, $type);
    }

    public function phpCompiler(Request $request)
    {
        $request->validate([
            'php_code' => 'required|string'
        ]);

        // Sanitize the input PHP code
        $input = $request->input('php_code');
        $input = str_replace(['<?php', '?>'], '', $input);
        $input = str_replace('<?=', 'echo ', $input);

        // Validate and filter for potential malicious code
        if (preg_match('/(exec|system|passthru|shell_exec|popen|proc_open)/i', $input)) {
            return response()->json([
                'output' => 'Error: Forbidden function detected.'
            ], 403);
        }

        $statusCode = 200;
        try {
            ob_start();
            eval($input);
            $output = ob_get_clean();
        } catch (\Throwable $e) {
            $output = $e->getMessage();
            $statusCode = 500;
        }

        return response()->json([
            'output' => $output
        ], $statusCode);
    }

    public function pythonCompiler(Request $request)
    {
        $client = new Client();
        try {
            $request = $client->request('POST', 'env('INTERNAL_API_URL')/cloud-compute', [
                'json' => [
                    'code' => $request->code,
                ]
            ]);

            $response = json_decode($request->getBody()->getContents(), true);
            return response()->json([
                'status' => 'success',
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function redirectExternal(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        return redirect()->away($request->url);
    }

    public function getMateriAudio($id)
    {
        $findMateri = TTS::findOrfail($id);
        $client = new Client();

        try {
            $request = $client->request('GET', 'https://api.prosa.ai/v2/speech/tts/' . $findMateri->job_id, [
                'headers' => [
                    'X-Api-Key' => env('API_KEY_PROSA')
                ],
                'verify' => false
            ]);

            $response = json_decode($request->getBody()->getContents(), true);
            // return the audio from base64 to binary
            $audio = base64_decode($response['result']['data']);
            // play on browser
            return response($audio, 200, [
                'Content-Type' => 'audio/mpeg',
                'Content-Disposition' => 'inline; filename="audio.mp3"'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
